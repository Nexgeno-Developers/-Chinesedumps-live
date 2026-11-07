<?php
/* PHP 7+/8: provides mysql_* on top of mysqli when the old extension is missing (no-op on PHP 5.x) */
require_once dirname(__FILE__) . '/compat_mysql.php';
/*
 * System activity log  -  writes one row into the `system_log` table for every action
 * done by a visitor, a customer, an admin / staff user or an automatic job.
 *
 * PHP 5.3+ compatible (no ??, no scalar type hints, no ...). Safe to require many times.
 * Table is created by cms2/sql/system_log.sql.
 *
 * Main entry point
 *   syslog_write($module, $action, $description, $opts = array())
 *       $opts: status (success|failed|info), entity_type, entity_id, entity_label,
 *              details (array|string, extra data - passwords / tokens are removed automatically),
 *              actor (array that overrides the detected actor: type,id,name,email,role)
 *
 * Helpers for the "who changed what" style messages
 *   syslog_snapshot($table, $pk, $id)                   -> row before / after a change
 *   syslog_log_created / syslog_log_updated / syslog_log_deleted(...)
 *
 * Rules this file follows
 *   1. Logging must NEVER break a page: no function here dies, throws, prints or shows an error.
 *      If the table is missing or the insert fails the action simply is not logged
 *      (one line is written to the PHP error log).
 *   2. Passwords, tokens, OTP codes, captcha answers and card data are never stored.
 *   3. The insert uses the already open default mysql link. An insert replaces
 *      mysql_insert_id() / mysql_affected_rows(), so call the logger AFTER the page
 *      has read those values.
 *   4. It can be switched off with  'SYSTEM_LOG_ENABLED' => false  in includes/config/secrets.local.php.
 */

if (!defined('SYSLOG_TABLE')) {
    define('SYSLOG_TABLE', 'system_log');
}

/* ------------------------------------------------------------------ settings */

if (!function_exists('syslog_enabled')) {
    function syslog_enabled()
    {
        static $enabled = null;
        if ($enabled !== null) {
            return $enabled;
        }
        if (!function_exists('secret')) {
            return true; /* settings not loaded yet: stay on, but do not cache the answer */
        }
        $enabled = true;
        $v = secret('SYSTEM_LOG_ENABLED', true);
        if ($v === false || $v === 0 || $v === '0') {
            $enabled = false;
        } elseif (is_string($v) && in_array(strtolower(trim($v)), array('false', 'no', 'off'), true)) {
            $enabled = false;
        }
        return $enabled;
    }
}

/* ------------------------------------------------------------------ small utilities */

if (!function_exists('syslog_text')) {
    /* scalar -> clean single string, control characters removed, cut to $max characters */
    function syslog_text($value, $max = 255)
    {
        if (!is_scalar($value)) {
            return '';
        }
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', ' ', (string)$value);
        if (function_exists('mb_strlen') && function_exists('mb_substr')) {
            if (mb_strlen($value, 'UTF-8') > $max) {
                $value = mb_substr($value, 0, $max - 3, 'UTF-8') . '...';
            }
        } elseif (strlen($value) > $max) {
            $value = substr($value, 0, $max - 3) . '...';
        }
        return $value;
    }
}

if (!function_exists('syslog_utf8')) {
    /* make sure a string is valid UTF-8 so json_encode() does not fail */
    function syslog_utf8($value)
    {
        $value = (string)$value;
        if (function_exists('mb_check_encoding') && !mb_check_encoding($value, 'UTF-8')) {
            $value = mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
        }
        return $value;
    }
}

if (!function_exists('syslog_esc')) {
    function syslog_esc($value)
    {
        $value = (string)$value;
        $escaped = function_exists('mysql_real_escape_string') ? @mysql_real_escape_string($value) : false;
        if ($escaped === false || $escaped === null) {
            $escaped = addslashes($value);
        }
        return $escaped;
    }
}

if (!function_exists('syslog_ident')) {
    /* table / column names: letters, digits, underscore only */
    function syslog_ident($name)
    {
        return preg_match('/^[A-Za-z0-9_]+$/', (string)$name) ? (string)$name : '';
    }
}

if (!function_exists('syslog_is_sensitive')) {
    function syslog_is_sensitive($key)
    {
        return (bool)preg_match('/pass|pwd|token|secret|otp|captcha|card|cvv|cvc|csrf|api[_-]?key|session|signature/i', (string)$key);
    }
}

if (!function_exists('syslog_mask')) {
    /* recursive copy of $data with sensitive keys replaced and long values shortened */
    function syslog_mask($data, $depth = 0)
    {
        if ($depth > 4) {
            return '...';
        }
        if (is_object($data)) {
            $data = get_object_vars($data);
        }
        if (!is_array($data)) {
            return is_scalar($data) ? syslog_text(syslog_utf8($data), 500) : '';
        }
        $out = array();
        foreach ($data as $k => $v) {
            if (syslog_is_sensitive($k)) {
                $out[$k] = '(hidden)';
            } else {
                $out[$k] = syslog_mask($v, $depth + 1);
            }
        }
        return $out;
    }
}

if (!function_exists('syslog_json')) {
    function syslog_json($data)
    {
        if ($data === null || $data === '' || $data === array()) {
            return '';
        }
        if (is_string($data)) {
            return syslog_text(syslog_utf8($data), 60000);
        }
        $json = @json_encode(syslog_mask($data));
        if ($json === false || $json === null) {
            $json = syslog_text(print_r(syslog_mask($data), true), 60000);
        }
        return $json;
    }
}

if (!function_exists('syslog_client_ip')) {
    function syslog_client_ip()
    {
        $candidates = array('HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        foreach ($candidates as $key) {
            if (empty($_SERVER[$key])) {
                continue;
            }
            $parts = explode(',', (string)$_SERVER[$key]);
            $ip = trim($parts[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
        return '';
    }
}

/* ------------------------------------------------------------------ who did it */

if (!function_exists('syslog_actor')) {
    /* Detects the person / job doing the current request. */
    function syslog_actor()
    {
        static $cache = null;
        $actor = array('type' => 'guest', 'id' => 0, 'name' => '', 'email' => '', 'role' => '');

        if (php_sapi_name() == 'cli') {
            $actor['type'] = 'system';
            $actor['name'] = 'Automatic job';
            return $actor;
        }

        $script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', (string)$_SERVER['SCRIPT_NAME']) : '';
        $inAdmin = (strpos($script, '/cms2/') !== false);

        if ($inAdmin) {
            if (!empty($_SESSION['adminId'])) {
                $actor['type'] = 'admin';
                $actor['id'] = (int)$_SESSION['adminId'];
                $actor['name'] = isset($_SESSION['strAdmin']) ? syslog_text($_SESSION['strAdmin'], 150) : '';
                $actor['role'] = isset($_SESSION['adminRole']) ? syslog_text($_SESSION['adminRole'], 30) : '';
            }
            return $actor;
        }

        if (!empty($_SESSION['uid'])) {
            $uid = (int)$_SESSION['uid'];
            if ($cache !== null && $cache['id'] === $uid) {
                return $cache;
            }
            $actor['type'] = 'user';
            $actor['id'] = $uid;
            $actor['name'] = isset($_SESSION['Uname']) ? syslog_text($_SESSION['Uname'], 150) : '';
            $actor['email'] = isset($_SESSION['email']) ? syslog_text($_SESSION['email'], 190) : '';
            $res = @mysql_query("SELECT user_fname, user_lname, user_email FROM tbl_user WHERE user_id='" . $uid . "' LIMIT 1");
            $row = $res ? @mysql_fetch_assoc($res) : false;
            if ($row) {
                $full = trim($row['user_fname'] . ' ' . $row['user_lname']);
                if ($full !== '') {
                    $actor['name'] = syslog_text($full, 150);
                }
                if ($row['user_email'] !== '') {
                    $actor['email'] = syslog_text($row['user_email'], 190);
                }
            }
            $cache = $actor;
        }
        return $actor;
    }
}

if (!function_exists('syslog_who')) {
    /* "Customer John Doe (john@mail.com)", "Admin 'faisal' (Super Admin)", "A visitor" ... */
    function syslog_who($actor = null)
    {
        if (!is_array($actor)) {
            $actor = syslog_actor();
        }
        $name = isset($actor['name']) ? $actor['name'] : '';
        $email = isset($actor['email']) ? $actor['email'] : '';
        switch (isset($actor['type']) ? $actor['type'] : 'guest') {
            case 'admin':
                $roles = array('super_admin' => 'Super Admin', 'admin' => 'Admin', 'editor' => 'Editor', 'support' => 'Support');
                $role = (isset($actor['role']) && isset($roles[$actor['role']])) ? $roles[$actor['role']] : 'Admin';
                return 'Admin user "' . ($name !== '' ? $name : 'ID ' . (int)$actor['id']) . '" (' . $role . ')';
            case 'user':
                return 'Customer ' . ($name !== '' ? $name : 'ID ' . (int)$actor['id']) . ($email !== '' ? ' (' . $email . ')' : '');
            case 'system':
                return 'The system';
            default:
                return 'A visitor';
        }
    }
}

/* ------------------------------------------------------------------ the writer */

if (!function_exists('syslog_write')) {
    /*
     * Stores one log row. Returns true when saved, false when skipped / failed. Never throws.
     */
    function syslog_write($module, $action, $description, $opts = array())
    {
        try {
            if (!syslog_enabled() || !function_exists('mysql_query')) {
                return false;
            }
            if (!is_array($opts)) {
                $opts = array();
            }

            $actor = syslog_actor();
            if (isset($opts['actor']) && is_array($opts['actor'])) {
                $actor = array_merge($actor, $opts['actor']);
            }
            $status = (isset($opts['status']) && in_array($opts['status'], array('success', 'failed', 'info'), true)) ? $opts['status'] : 'success';
            $actorType = in_array($actor['type'], array('guest', 'user', 'admin', 'system'), true) ? $actor['type'] : 'guest';

            $method = isset($_SERVER['REQUEST_METHOD']) ? (string)$_SERVER['REQUEST_METHOD'] : (php_sapi_name() == 'cli' ? 'CLI' : '');
            $url = '';
            if (isset($_SERVER['REQUEST_URI'])) {
                $url = (string)$_SERVER['REQUEST_URI'];
                /* never keep tokens / query secrets that may be in the URL */
                $url = preg_replace('/([?&](?:[^=&]*(?:pass|token|key|secret|uid|otp|code)[^=&]*)=)[^&]*/i', '$1(hidden)', $url);
            } elseif (isset($_SERVER['SCRIPT_NAME'])) {
                $url = (string)$_SERVER['SCRIPT_NAME'];
            }

            $row = array(
                'created_at'     => date('Y-m-d H:i:s'),
                'actor_type'     => $actorType,
                'actor_id'       => (int)$actor['id'],
                'actor_name'     => syslog_text($actor['name'], 150),
                'actor_email'    => syslog_text($actor['email'], 190),
                'actor_role'     => syslog_text($actor['role'], 30),
                'module'         => syslog_text($module, 60),
                'action'         => syslog_text($action, 60),
                'status'         => $status,
                'entity_type'    => syslog_text(isset($opts['entity_type']) ? $opts['entity_type'] : '', 60),
                'entity_id'      => syslog_text(isset($opts['entity_id']) ? $opts['entity_id'] : '', 60),
                'entity_label'   => syslog_text(isset($opts['entity_label']) ? syslog_utf8($opts['entity_label']) : '', 255),
                'description'    => syslog_text(syslog_utf8($description), 1500),
                'details'        => syslog_json(isset($opts['details']) ? $opts['details'] : ''),
                'ip_address'     => syslog_text(syslog_client_ip(), 45),
                'request_method' => syslog_text($method, 10),
                'request_url'    => syslog_text(syslog_utf8($url), 500),
                'user_agent'     => syslog_text(isset($_SERVER['HTTP_USER_AGENT']) ? syslog_utf8($_SERVER['HTTP_USER_AGENT']) : '', 255)
            );

            $cols = array();
            $vals = array();
            foreach ($row as $col => $val) {
                $cols[] = '`' . $col . '`';
                if ($col == 'actor_id') {
                    $vals[] = (int)$val;
                } elseif ($col == 'details' && $val === '') {
                    $vals[] = 'NULL';
                } else {
                    $vals[] = "'" . syslog_esc($val) . "'";
                }
            }
            $sql = 'INSERT INTO `' . SYSLOG_TABLE . '` (' . implode(',', $cols) . ') VALUES (' . implode(',', $vals) . ')';

            $ok = @mysql_query($sql);

            /* lets the admin fallback logger know that this request is already described */
            if (!isset($GLOBALS['syslog_written'])) {
                $GLOBALS['syslog_written'] = 0;
            }
            $GLOBALS['syslog_written']++;

            if (!$ok) {
                static $reported = false;
                if (!$reported) {
                    $reported = true;
                    @error_log('[system_log] could not save log entry (' . @mysql_errno() . ' ' . @mysql_error() . '). Run cms2/sql/system_log.sql if the table is missing.');
                }
                return false;
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

/* ------------------------------------------------------------------ before / after helpers */

if (!function_exists('syslog_snapshot')) {
    /* Returns the current row (assoc array) of $table where $pk = $id, or null. */
    function syslog_snapshot($table, $pk, $id)
    {
        if (!syslog_enabled()) {
            return null;
        }
        $table = syslog_ident($table);
        $pk = syslog_ident($pk);
        if ($table === '' || $pk === '') {
            return null;
        }
        $res = @mysql_query("SELECT * FROM `" . $table . "` WHERE `" . $pk . "`='" . syslog_esc($id) . "' LIMIT 1");
        if (!$res) {
            return null;
        }
        $row = @mysql_fetch_assoc($res);
        return $row ? $row : null;
    }
}

if (!function_exists('syslog_connect')) {
    /*
     * For small endpoints that never open the database themselves (OTP, sign-out ...):
     * opens the normal site connection so the log row can be saved.
     */
    function syslog_connect()
    {
        static $done = false;
        if ($done || !syslog_enabled()) {
            return;
        }
        $done = true;
        if (!function_exists('secret') || !function_exists('mysql_connect')) {
            return;
        }
        /* Same host / user / password as the site, so mysql_connect() hands back the page's own
           link when one is already open (no second connection) and opens one when it is not.
           Nothing is included here on purpose: including classDbConnection.php inside a function
           would turn its global settings variables into local ones. */
        $link = @mysql_connect(secret('DB_HOST', 'localhost'), secret('DB_USER', 'root'), secret('DB_PASS', ''));
        if ($link) {
            @mysql_select_db(secret('DB_NAME', 'chinesed_db'), $link);
        }
    }
}

if (!function_exists('syslog_ptype_label')) {
    /* cart / order product type code -> words */
    function syslog_ptype_label($ptype)
    {
        $ptype = (string)$ptype;
        $map = array(
            'p' => 'Secure PDC (PDF)', '' => 'Secure PDC (PDF)', 'sp' => 'Testing Engine only', 'both' => 'Secure PDC + Testing Engine',
            '7' => 'package option 7 (workbook)', '8' => 'package option 8 (workbook + engine/racks)', '9' => 'package option 9 (workbook + bootcamp)'
        );
        if (isset($map[$ptype])) {
            return $map[$ptype];
        }
        if (preg_match('/^p(\d+)$/', $ptype, $m)) {
            return 'Unlimited access package (' . $m[1] . ' month' . ($m[1] == '1' ? '' : 's') . ')';
        }
        return 'type ' . $ptype;
    }
}

if (!function_exists('syslog_cart_item_label')) {
    /* readable name of a `temp_order` (cart) row */
    function syslog_cart_item_label($row)
    {
        if (!is_array($row)) {
            return 'an item';
        }
        $months = (int)syslog_pick($row, 'subscrip', 0);
        $when = $months > 0 ? ', ' . $months . ' month' . ($months == 1 ? '' : 's') : '';
        if ((int)syslog_pick($row, 'Pid', 0) > 0) {
            return 'exam ' . syslog_exam_label($row['Pid']) . ' (' . syslog_ptype_label(syslog_pick($row, 'PType')) . $when . ')';
        }
        if (syslog_pick($row, 'Product') == 'All') {
            return 'the Unlimited Access package' . ($when !== '' ? ' (' . ltrim($when, ', ') . ')' : '');
        }
        if (syslog_pick($row, 'TrackID') !== '' && syslog_pick($row, 'TrackID') != '0') {
            $cert = syslog_snapshot('tbl_cert', 'cert_id', (int)$row['TrackID']);
            return 'the all-exams bundle of certification ' . syslog_pick($cert, 'cert_name', 'ID ' . (int)$row['TrackID']) . $when;
        }
        if (syslog_pick($row, 'VendorID') !== '' && syslog_pick($row, 'VendorID') != '0') {
            $ven = syslog_snapshot('tbl_vendors', 'ven_id', (int)$row['VendorID']);
            return 'the all-exams bundle of vendor ' . syslog_pick($ven, 'ven_name', 'ID ' . (int)$row['VendorID']) . $when;
        }
        return 'an item';
    }
}

if (!function_exists('syslog_user_label')) {
    /* "John Doe (john@mail.com)" for a customer id, "customer ID 5" when it cannot be found */
    function syslog_user_label($userId)
    {
        $userId = (int)$userId;
        if ($userId > 0 && syslog_enabled()) {
            $res = @mysql_query("SELECT user_fname, user_lname, user_email FROM tbl_user WHERE user_id='" . $userId . "' LIMIT 1");
            $row = $res ? @mysql_fetch_assoc($res) : false;
            if ($row) {
                $name = trim($row['user_fname'] . ' ' . $row['user_lname']);
                return ($name !== '' ? $name . ' ' : '') . '(' . $row['user_email'] . ')';
            }
        }
        return 'customer ID ' . $userId;
    }
}

if (!function_exists('syslog_exam_label')) {
    /* exam code for an exam id */
    function syslog_exam_label($examId)
    {
        $row = syslog_snapshot('tbl_exam', 'exam_id', (int)$examId);
        return syslog_pick($row, 'exam_name', 'exam ID ' . (int)$examId);
    }
}

if (!function_exists('syslog_snapshot_one')) {
    /* First row of a single-row settings table (e.g. `website`). */
    function syslog_snapshot_one($table)
    {
        if (!syslog_enabled()) {
            return null;
        }
        $table = syslog_ident($table);
        if ($table === '') {
            return null;
        }
        $res = @mysql_query("SELECT * FROM `" . $table . "` LIMIT 1");
        $row = $res ? @mysql_fetch_assoc($res) : false;
        return $row ? $row : null;
    }
}

if (!function_exists('syslog_pick')) {
    /* $row[$col] when the row has it, otherwise $fallback (handy for building labels) */
    function syslog_pick($row, $col, $fallback = '')
    {
        return (is_array($row) && isset($row[$col]) && $row[$col] !== '') ? $row[$col] : $fallback;
    }
}

if (!function_exists('syslog_count')) {
    /* number of rows in $table where $col = $value (0 on any problem) */
    function syslog_count($table, $col, $value)
    {
        if (!syslog_enabled()) {
            return 0;
        }
        $table = syslog_ident($table);
        $col = syslog_ident($col);
        if ($table === '' || $col === '') {
            return 0;
        }
        $res = @mysql_query("SELECT COUNT(*) FROM `" . $table . "` WHERE `" . $col . "`='" . syslog_esc($value) . "'");
        $row = $res ? @mysql_fetch_row($res) : false;
        return $row ? (int)$row[0] : 0;
    }
}

if (!function_exists('syslog_field_labels')) {
    /* Friendly field names so messages read like English. Unknown columns are humanised automatically. */
    function syslog_field_labels($table)
    {
        $maps = array(
            'tbl_vendors' => array('ven_name' => 'Vendor name', 'ven_url' => 'URL slug', 'ven_descr' => 'Description', 'ven_home' => 'Show on home page', 'ven_hot' => 'Hot vendor', 'allvendors' => 'Show in all vendors', 'ven_status' => 'Status', 'ven_title' => 'SEO title', 'ven_keywords' => 'SEO keywords', 'ven_desc' => 'SEO description', 'ven_pri3' => 'Price 3 months', 'ven_pri6' => 'Price 6 months', 'ven_pri12' => 'Price 12 months', 'youtube_links' => 'Video links', 'demo_json' => 'Attachments', 'default_image' => 'Default image', 'ven_upddate' => 'Updated date', 'swregid' => 'SWREG id'),
            'tbl_cert' => array('ven_id' => 'Vendor', 'cert_name' => 'Certification name', 'cert_url' => 'URL slug', 'cert_descr' => 'Description', 'cert_home' => 'Show on home page', 'cert_hot' => 'Hot certification', 'allcerts' => 'Show in all certifications', 'cert_status' => 'Status', 'cert_title' => 'SEO title', 'cert_keywords' => 'SEO keywords', 'cert_desc_seo' => 'SEO description', 'cert_pri3' => 'Price 3 months', 'cert_pri6' => 'Price 6 months', 'cert_pri12' => 'Price 12 months', 'cert_date' => 'Date', 'demo_data' => 'Demo files', 'cert_upddate' => 'Updated date', 'swregid' => 'SWREG id'),
            'tbl_exam' => array('ven_id' => 'Vendor', 'cert_id' => 'Certifications', 'exam_name' => 'Exam code', 'exam_fullname' => 'Exam full name', 'alias_name' => 'Alias name', 'exam_type' => 'Exam type', 'labName' => 'Lab name', 'exam_url' => 'URL slug', 'exam_pri0' => 'Base price', 'exam_pri3' => 'Price (PDF)', 'exam_pri6' => 'Price 6 months', 'exam_pri12' => 'Price 12 months', 'both_pri' => 'Price (PDF + Engine)', 'engn_pri3' => 'Price (Engine only)', 'QA' => 'Number of Q&A', 'discount' => 'Discount', 'exam_home' => 'Show on home page', 'exam_hot' => 'Hot exam', 'special' => 'Special', 'exam_status' => 'Status', 'expire_status' => 'Retired', 'exam_descr' => 'Description', 'exam_descr2' => 'Second description', 'exam_related_descr' => 'Related description', 'exam_title' => 'SEO title', 'exam_keywords' => 'SEO keywords', 'exam_desc_seo' => 'SEO description', 'exam_date' => 'Date', 'exam_upddate' => 'Updated date', 'telegram_url' => 'Telegram link', 'whatsapp_url' => 'WhatsApp link', 'skype_url' => 'Skype link', 'video_code' => 'Video code', 'course_image' => 'Course image', 'youtube_links' => 'Video links', 'faq_json' => 'FAQs', 'free_dump_pdf' => 'Free dump PDF', 'free_dump_label' => 'Free dump label', 'demo_practice_file' => 'Demo practice file', 'demo_practice_label' => 'Demo practice label', 'demo_images' => 'Demo images', 'exam_demo' => 'Demo file', 'exam_full' => 'Full file'),
            'coupon' => array('coupon_title' => 'Title', 'coupon_code' => 'Coupon code', 'start_date' => 'Start date', 'end_date' => 'End date', 'coupon_value' => 'Value', 'coupon_type' => 'Type', 'number_of_uses' => 'Number of uses', 'status' => 'Status', 'comments' => 'Comments'),
            'tbl_course' => array('name' => 'Course name', 'status' => 'Status (1 = stable, 0 = unstable)'),
            'tbl_user' => array('user_fname' => 'First name', 'user_lname' => 'Last name', 'user_email' => 'Email', 'user_user' => 'Username', 'user_phone' => 'Phone', 'user_status' => 'Status', 'user_address' => 'Address', 'user_city' => 'City', 'user_state' => 'State', 'user_country' => 'Country', 'user_postcode' => 'Post code', 'user_gender' => 'Gender', 'user_birth' => 'Birth date'),
            'tbladmin' => array('admin_name' => 'Login name', 'admin_fname' => 'First name', 'admin_lname' => 'Last name', 'admin_email' => 'Email', 'admin_status' => 'Status', 'admin_role' => 'Role'),
            'sliders' => array('s_image' => 'Image', 's_alt' => 'Alt text', 's_link' => 'Link', 'status' => 'Status', 'banner_order' => 'Display order', 'type' => 'Banner type'),
            'order_master' => array('Status' => 'Order status', 'Net_Amount' => 'Net amount', 'Gross_Amount' => 'Gross amount', 'OrderDesc' => 'Order description'),
            'website' => array('title' => 'Website title', 'website_url' => 'Website URL', 'admin_title' => 'Admin title', 'copyright' => 'Copyright text', 'course_update' => 'Course update text', 'contactus_email' => 'Contact email', 'phone' => 'Phone', 'paypalid' => 'PayPal ID', 'from_email' => 'From email', 'menucolum' => 'Menu columns'),
            'content_pages' => array('page_title' => 'Page title', 'content_title' => 'Content title', 'page_contents' => 'Page content', 'meta_keywords' => 'Meta keywords', 'meta_descx' => 'Meta description'),
            'tbl_globlecontents' => array('page_title' => 'Page title', 'content_title' => 'Content title', 'page_contents' => 'Content block 1', 'page_contents1' => 'Content block 2', 'page_contents2' => 'Content block 3', 'meta_keywords' => 'Meta keywords', 'meta_descx' => 'Meta description')
        );
        return isset($maps[$table]) ? $maps[$table] : array();
    }
}

if (!function_exists('syslog_short')) {
    /* readable short version of a value for the "old -> new" text */
    function syslog_short($value)
    {
        if ($value === null) {
            return '(empty)';
        }
        $value = trim(preg_replace('/\s+/', ' ', strip_tags((string)$value)));
        if ($value === '') {
            return '(empty)';
        }
        if (strlen($value) > 200) {
            return '(long text, ' . strlen($value) . ' characters)';
        }
        return syslog_text(syslog_utf8($value), 120);
    }
}

if (!function_exists('syslog_diff')) {
    /*
     * Compares two rows and returns a list of changes:
     *   array( array('field' => 'Status', 'old' => 'Active', 'new' => 'In Active'), ... )
     * $skip = columns to ignore (e.g. timestamps).
     */
    function syslog_diff($before, $after, $labels = array(), $skip = array())
    {
        $changes = array();
        if (!is_array($before) || !is_array($after)) {
            return $changes;
        }
        foreach ($after as $col => $new) {
            if (in_array($col, $skip, true) || !array_key_exists($col, $before)) {
                continue;
            }
            $old = $before[$col];
            if ((string)$old === (string)$new) {
                continue;
            }
            $label = isset($labels[$col]) ? $labels[$col] : ucfirst(str_replace('_', ' ', $col));
            if (syslog_is_sensitive($col)) {
                $changes[] = array('field' => $label, 'old' => '(hidden)', 'new' => '(changed)');
            } else {
                $changes[] = array('field' => $label, 'old' => syslog_short($old), 'new' => syslog_short($new));
            }
        }
        return $changes;
    }
}

if (!function_exists('syslog_changes_text')) {
    function syslog_changes_text($changes, $limit = 8)
    {
        $parts = array();
        foreach ($changes as $i => $c) {
            if ($i >= $limit) {
                $parts[] = 'and ' . (count($changes) - $limit) . ' more';
                break;
            }
            $parts[] = $c['field'] . ': "' . $c['old'] . '" changed to "' . $c['new'] . '"';
        }
        return implode('; ', $parts);
    }
}

if (!function_exists('syslog_log_created')) {
    /* "<who> added a new <thing> "<label>" (ID n)." */
    function syslog_log_created($module, $action, $thing, $id, $label, $extra = '', $details = array())
    {
        $text = syslog_who() . ' added a new ' . $thing . ' "' . $label . '"' . ($id !== '' && $id !== 0 ? ' (ID ' . $id . ')' : '') . '.' . ($extra !== '' ? ' ' . $extra : '');
        return syslog_write($module, $action, $text, array(
            'entity_type' => $thing, 'entity_id' => $id, 'entity_label' => $label, 'details' => $details
        ));
    }
}

if (!function_exists('syslog_log_updated')) {
    /*
     * "<who> updated <thing> "<label>" (ID n). Changes: ..."
     * $before / $after are rows from syslog_snapshot() taken before and after the update.
     */
    function syslog_log_updated($module, $action, $thing, $id, $label, $before, $after, $table = '', $skip = array())
    {
        $labels = $table !== '' ? syslog_field_labels($table) : array();
        $changes = syslog_diff($before, $after, $labels, $skip);
        $text = syslog_who() . ' updated ' . $thing . ' "' . $label . '" (ID ' . $id . ').';
        if (count($changes) == 0) {
            $text .= ' Saved without changing any value.';
        } else {
            $text .= ' Changes: ' . syslog_changes_text($changes) . '.';
        }
        return syslog_write($module, $action, $text, array(
            'entity_type' => $thing, 'entity_id' => $id, 'entity_label' => $label,
            'details' => array('changes' => $changes)
        ));
    }
}

if (!function_exists('syslog_log_setting')) {
    /* "<who> changed the website setting <label>. Changes: ..." for single-row settings tables. */
    function syslog_log_setting($action, $label, $before, $after, $table = '', $skip = array())
    {
        $labels = $table !== '' ? syslog_field_labels($table) : array();
        $changes = syslog_diff($before, $after, $labels, $skip);
        $text = syslog_who() . ' changed the website setting "' . $label . '".';
        if (count($changes) == 0) {
            $text .= ' Saved without changing any value.';
        } else {
            $text .= ' Changes: ' . syslog_changes_text($changes) . '.';
        }
        return syslog_write('Settings', $action, $text, array(
            'entity_type' => 'setting', 'entity_label' => $label, 'details' => array('changes' => $changes)
        ));
    }
}

if (!function_exists('syslog_log_status_toggle')) {
    /*
     * Used by the admin on/off switches (cms2/changestatus*.php).
     * $oldValue is the value BEFORE the switch (1 = was on).
     */
    function syslog_log_status_toggle($table, $pk, $id, $field, $oldValue)
    {
        $map = array(
            'tbl_vendors'  => array('vendor', 'ven_name', 'Vendors', 'VENDOR_SWITCHED'),
            'tbl_cert'     => array('certification', 'cert_name', 'Certifications', 'CERTIFICATION_SWITCHED'),
            'tbl_exam'     => array('exam', 'exam_name', 'Exams', 'EXAM_SWITCHED'),
            'order_master' => array('order', 'Order_Id', 'Orders', 'ORDER_SWITCHED'),
            'tbl_course'   => array('course', 'name', 'Courses', 'COURSE_SWITCHED')
        );
        if (!isset($map[$table])) {
            return false;
        }
        $row = syslog_snapshot($table, $pk, $id);
        $label = syslog_pick($row, $map[$table][1], 'ID ' . (int)$id);
        $nowOn = ((int)$oldValue == 1) ? false : true;
        $what = (substr($field, -4) == 'home') ? '"Show on home page"' : 'the status';
        $text = syslog_who() . ' ' . ($nowOn ? 'enabled ' : 'disabled ') . $what . ' of ' . $map[$table][0] . ' "' . $label . '" (ID ' . (int)$id . ').';
        if ($table == 'tbl_vendors' || $table == 'tbl_cert') {
            $text .= ' The exams under it were switched the same way.';
        }
        if ($table == 'order_master') {
            $text .= ' The access of the items in this order was switched the same way.';
        }
        return syslog_write($map[$table][2], $map[$table][3], $text, array(
            'entity_type' => $map[$table][0], 'entity_id' => (int)$id, 'entity_label' => $label,
            'details' => array('field' => $field, 'old_value' => (int)$oldValue, 'new_value' => $nowOn ? 1 : 0)
        ));
    }
}

if (!function_exists('syslog_permission_matrix')) {
    /* "Module name" => "Read, Write, Edit, Delete" for one admin user (used to log permission changes). */
    function syslog_permission_matrix($adminId)
    {
        $out = array();
        if (!syslog_enabled()) {
            return $out;
        }
        $res = @mysql_query("SELECT m.Name, MAX(p.`Read`) AS r, MAX(p.`Write`) AS w, MAX(p.`Edit`) AS e, MAX(p.`Delete`) AS d FROM tblmodules m LEFT JOIN tblpermissions p ON m.ModuleID = p.ModuleID AND p.AdminID='" . (int)$adminId . "' GROUP BY m.Name ORDER BY m.Name");
        if ($res) {
            while ($row = @mysql_fetch_assoc($res)) {
                $parts = array();
                if ($row['r'] == 1) { $parts[] = 'Read'; }
                if ($row['w'] == 1) { $parts[] = 'Write'; }
                if ($row['e'] == 1) { $parts[] = 'Edit'; }
                if ($row['d'] == 1) { $parts[] = 'Delete'; }
                $out[$row['Name']] = count($parts) ? implode(', ', $parts) : 'No access';
            }
        }
        return $out;
    }
}

if (!function_exists('syslog_log_deleted')) {
    function syslog_log_deleted($module, $action, $thing, $id, $label, $extra = '', $details = array())
    {
        $text = syslog_who() . ' deleted ' . $thing . ' "' . $label . '" (ID ' . $id . ').' . ($extra !== '' ? ' ' . $extra : '');
        return syslog_write($module, $action, $text, array(
            'entity_type' => $thing, 'entity_id' => $id, 'entity_label' => $label, 'details' => $details
        ));
    }
}

/* ------------------------------------------------------------------ admin safety net */

if (!function_exists('syslog_admin_page_label')) {
    function syslog_admin_page_label($file)
    {
        $map = array(
            'addvendors.php' => 'Add Vendor', 'editvendor.php' => 'Edit Vendor', 'vendormanage.php' => 'Vendors Management',
            'addcertif.php' => 'Add Certification', 'editcertif.php' => 'Edit Certification', 'certifmanage.php' => 'Certifications Management',
            'addexam.php' => 'Add Exam', 'editexam.php' => 'Edit Exam', 'exammanage.php' => 'Exams Management',
            'bulk_update.php' => 'Bulk Update Exams', 'bulk_update_date.php' => 'Bulk Update Exams', 'updateDate.php' => 'Update Exam Dates',
            'assign_user.php' => 'Assign Exam to User',
            'addslider.php' => 'Add Banner', 'editslider.php' => 'Edit Banner', 'editslider_new.php' => 'Edit Exam Result Image', 'bannermanage.php' => 'Banners Management', 'bannermanage_new.php' => 'Banners Management',
            'add_gallery.php' => 'Add Exam Result Image', 'galley_management.php' => 'Exam Results Management', 'gallery_new.php' => 'Exam Results Management', 'addreport.php' => 'Add Exam Report',
            'addcoupon.php' => 'Add Coupon', 'editcoupon.php' => 'Edit Coupon', 'managecoupon.php' => 'Manage Coupons',
            'course_add.php' => 'Add Course', 'course_edit.php' => 'Edit Course', 'courses_status_manage.php' => 'Manage Courses',
            'adduser.php' => 'Add Customer', 'edituser.php' => 'Edit Customer', 'manageuser.php' => 'Customer Management', 'userstats.php' => 'Customer Report',
            'addadmin.php' => 'Add Admin User', 'editadmin.php' => 'Edit Admin User', 'manageadmin.php' => 'Admin User Management', 'permission.php' => 'Admin Permissions',
            'masterorder.php' => 'Final Orders', 'orderdetail.php' => 'Order Detail', 'editorder.php' => 'Edit Order', 'ordermanage.php' => 'Order Management',
            'odrproces.php' => 'List Cart', 'processsave.php' => 'Process Cart', 'reodrproces.php' => 'Re-Order Cart', 'processsavere.php' => 'Process Re-Order',
            'manage_keys.php' => 'Manage Licence Keys', 'keys_action.php' => 'Licence Key Action', 'multi_keys_action.php' => 'Generate Licence Keys',
            'managefull.php' => 'Full Download Management', 'managedemo.php' => 'Demo Download Management', 'view_demoDownload.php' => 'Demo Emails', 'exam_request.php' => 'Exam Request Emails',
            'mailmanage.php' => 'Mailing List', 'addsubscriber.php' => 'Add Subscriber', 'editsubscriber.php' => 'Edit Subscriber',
            'global.php' => 'Configuration', 'website.php' => 'Website Title', 'copyright.php' => 'Website Copyright', 'contactus.php' => 'Contact Email Addresses',
            'bootomcontact.php' => 'Footer Contact', 'mainimg.php' => 'Front Page Images', 'menusetting.php' => 'Menu Settings', 'menumodules.php' => 'Menu Modules', 'changemodule.php' => 'Menu Module Switch',
            'editemails.php' => 'Email Templates', 'vendorgloble.php' => 'Global Content', 'editpagetext.php' => 'Page Content', 'editstylesheet.php' => 'Style Sheet',
            'password.php' => 'Change Password',
            'changestatus.php' => 'Status Switch', 'changestatusall.php' => 'Order Status Switch', 'changestatusnew.php' => 'Status Switch', 'changestatusnewnew.php' => 'Status Switch'
        );
        return isset($map[$file]) ? $map[$file] : ucwords(str_replace('_', ' ', preg_replace('/\.php$/', '', $file)));
    }
}

if (!function_exists('syslog_admin_fallback_register')) {
    /*
     * Called from cms2 session guard (includes/common/inc/sessionheader.php).
     * When an admin page changes something but has no detailed log call, one general
     * line ("<admin> used <page>") is stored at the end of the request, so nothing is missed.
     */
    function syslog_admin_fallback_register()
    {
        static $registered = false;
        if ($registered || !syslog_enabled()) {
            return;
        }
        $registered = true;
        register_shutdown_function('syslog_admin_fallback_run');
    }
}

if (!function_exists('syslog_admin_fallback_run')) {
    function syslog_admin_fallback_run()
    {
        try {
            if (!empty($GLOBALS['syslog_written']) || empty($_SESSION['adminId'])) {
                return;
            }
            $file = isset($_SERVER['SCRIPT_NAME']) ? basename($_SERVER['SCRIPT_NAME']) : '';
            if ($file === '' || $file == 'systemlog.php') {
                return;
            }

            $needed = '';
            if (function_exists('cms2_page_permissions')) {
                $map = cms2_page_permissions();
                if (isset($map[$file])) {
                    $needed = $map[$file][1];
                }
            }
            $isPost = (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST));
            $isDelete = (isset($_POST['btn_delete']) || (isset($_GET['action']) && $_GET['action'] == 'up'));
            $isActionGet = (isset($_GET['act']) || isset($_GET['action']) || isset($_GET['status']) || isset($_GET['del']) || isset($_GET['delete']));

            $changing = false;
            if ($isDelete) {
                $changing = true;
            } elseif ($needed == 'write' || $needed == 'edit' || $needed == 'delete') {
                $changing = ($isPost || $isActionGet);
            }
            if (!$changing) {
                return;
            }

            $page = syslog_admin_page_label($file);
            $verb = $isDelete ? 'deleted records using' : 'saved changes using';
            $keys = array();
            foreach (array_merge(array_keys($_GET), array_keys($_POST)) as $k) {
                $keys[] = $k;
            }
            syslog_write('Admin Panel', $isDelete ? 'ADMIN_DELETE' : 'ADMIN_CHANGE',
                syslog_who() . ' ' . $verb . ' the "' . $page . '" page.',
                array('status' => 'info', 'entity_type' => 'page', 'entity_label' => $page,
                      'details' => array('page' => $file, 'sent_fields' => $keys, 'get' => $_GET, 'post' => $_POST))
            );
        } catch (Exception $e) {
            /* never break the page while it is shutting down */
        }
    }
}
