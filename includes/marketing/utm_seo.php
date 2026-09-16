<?php
/**
 * UTM / marketing attribution helper.
 * Reads cookie or posted hidden fields, saves utm_seo_param, and formats email HTML.
 */

if (!function_exists('utm_seo_read_string')) {

    function utm_seo_read_string($value)
    {
        if (!is_string($value)) {
            return '';
        }
        if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }
        return trim($value);
    }

    function utm_seo_ensure_db()
    {
        if (function_exists('mysql_query')) {
            $ok = @mysql_query('SELECT 1');
            if ($ok) {
                return true;
            }
        }

        $dbFile = dirname(__FILE__) . '/../config/classDbConnection.php';
        if (is_file($dbFile)) {
            if (!class_exists('classDbConnection')) {
                require_once $dbFile;
            }
            new classDbConnection();
            return true;
        }

        return false;
    }

    function utm_seo_escape($value)
    {
        if (!is_string($value)) {
            $value = (string) $value;
        }
        if (function_exists('mysql_real_escape_string')) {
            return mysql_real_escape_string($value);
        }
        return addslashes($value);
    }

    function utm_seo_from_cookie()
    {
        if (!isset($_COOKIE['cd_marketing']) || $_COOKIE['cd_marketing'] === '') {
            return array();
        }

        $raw = utm_seo_read_string($_COOKIE['cd_marketing']);
        $data = json_decode($raw, true);
        return is_array($data) ? $data : array();
    }

    function utm_seo_from_post()
    {
        $map = array(
            'visitor_id' => 'cd_visitor_id',
            'utm_source' => 'cd_utm_source',
            'utm_medium' => 'cd_utm_medium',
            'utm_campaign' => 'cd_utm_campaign',
            'utm_term' => 'cd_utm_term',
            'utm_content' => 'cd_utm_content',
            'utm_id' => 'cd_utm_id',
            'ad_type' => 'cd_ad_type',
            'ad_id' => 'cd_ad_id',
            'ad_id_param' => 'cd_ad_id_param'
        );

        $data = array();
        foreach ($map as $key => $postKey) {
            if (isset($_POST[$postKey]) && utm_seo_read_string($_POST[$postKey]) !== '') {
                $data[$key] = utm_seo_read_string($_POST[$postKey]);
            }
        }

        if (isset($_POST['cd_marketing_json']) && utm_seo_read_string($_POST['cd_marketing_json']) !== '') {
            $json = json_decode(utm_seo_read_string($_POST['cd_marketing_json']), true);
            if (is_array($json)) {
                $data = array_merge($json, $data);
            }
        }

        return $data;
    }

    function utm_seo_collect()
    {
        $postData = utm_seo_from_post();
        if (utm_seo_has_data($postData)) {
            return $postData;
        }
        return utm_seo_from_cookie();
    }

    function utm_seo_has_data($data)
    {
        if (!is_array($data) || empty($data)) {
            return false;
        }

        $keys = array(
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_term',
            'utm_content',
            'utm_id',
            'ad_type',
            'ad_id',
            'ad_id_param'
        );

        foreach ($keys as $key) {
            if (isset($data[$key]) && $data[$key] !== '' && $data[$key] !== null) {
                return true;
            }
        }

        if (isset($data['last_touch']) && is_array($data['last_touch'])) {
            return utm_seo_has_data($data['last_touch']);
        }

        return false;
    }

    function utm_seo_value($data, $key)
    {
        if (isset($data[$key]) && $data[$key] !== null && $data[$key] !== '') {
            return (string) $data[$key];
        }
        return '';
    }

    function utm_seo_email_html($data = null)
    {
        if ($data === null) {
            $data = utm_seo_collect();
        }

        if (!utm_seo_has_data($data)) {
            return '';
        }

        $rows = array(
            'UTM Source' => utm_seo_value($data, 'utm_source'),
            'UTM Medium' => utm_seo_value($data, 'utm_medium'),
            'UTM Campaign' => utm_seo_value($data, 'utm_campaign'),
            'UTM Term' => utm_seo_value($data, 'utm_term'),
            'UTM Content' => utm_seo_value($data, 'utm_content'),
            'UTM ID' => utm_seo_value($data, 'utm_id'),
            'Ad Type' => utm_seo_value($data, 'ad_type'),
            'Ad ID' => utm_seo_value($data, 'ad_id'),
            'Ad ID Param' => utm_seo_value($data, 'ad_id_param'),
            'Landing Page' => utm_seo_value($data, 'landing_page'),
            'Landing URL' => utm_seo_value($data, 'landing_url')
        );

        $html = '<br /><br /><strong>Marketing Attribution</strong><br />';
        foreach ($rows as $label => $value) {
            if ($value === '') {
                continue;
            }
            $html .= '<strong>' . htmlspecialchars($label, ENT_QUOTES) . ':</strong> ' .
                htmlspecialchars($value, ENT_QUOTES) . '<br />';
        }

        return $html;
    }

    function utm_seo_save($type, $relatedId, $email, $data = null)
    {
        if ($data === null) {
            $data = utm_seo_collect();
        }

        if (!utm_seo_has_data($data)) {
            return false;
        }

        if (!utm_seo_ensure_db()) {
            return false;
        }

        $type = utm_seo_read_string($type);
        if ($type === '') {
            $type = 'unknown';
        }

        $relatedId = ($relatedId === null || $relatedId === false) ? '' : (string) $relatedId;
        $email = utm_seo_read_string($email);
        $adType = utm_seo_value($data, 'ad_type');
        $adId = utm_seo_value($data, 'ad_id');
        $adIdParam = utm_seo_value($data, 'ad_id_param');

        $paramsJson = json_encode($data);
        if ($paramsJson === false) {
            $paramsJson = '{}';
        }

        $relatedSql = ($relatedId === '') ? 'NULL' : "'" . utm_seo_escape($relatedId) . "'";
        $emailSql = ($email === '') ? 'NULL' : "'" . utm_seo_escape($email) . "'";
        $adTypeSql = ($adType === '') ? 'NULL' : "'" . utm_seo_escape($adType) . "'";
        $adIdSql = ($adId === '') ? 'NULL' : "'" . utm_seo_escape($adId) . "'";
        $adIdParamSql = ($adIdParam === '') ? 'NULL' : "'" . utm_seo_escape($adIdParam) . "'";

        $sql = "INSERT INTO utm_seo_param
            (`type`, `related_id`, `email`, `ad_type`, `ad_id`, `ad_id_param`, `params_json`, `created_at`)
            VALUES (
                '" . utm_seo_escape($type) . "',
                " . $relatedSql . ",
                " . $emailSql . ",
                " . $adTypeSql . ",
                " . $adIdSql . ",
                " . $adIdParamSql . ",
                '" . utm_seo_escape($paramsJson) . "',
                '" . utm_seo_escape(date('Y-m-d H:i:s')) . "'
            )";

        $result = @mysql_query($sql);
        if (!$result) {
            error_log('utm_seo_param insert failed: ' . mysql_error());
            return false;
        }

        return true;
    }

    function utm_seo_track_exam_request($relatedId, $email)
    {
        return utm_seo_save('exam_request', $relatedId, $email);
    }
}
