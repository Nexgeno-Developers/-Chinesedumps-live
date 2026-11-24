<?php
/*
 * CENTRAL LIST OF BLOCKED (DE-LISTED) PAGES
 * -----------------------------------------------------------------------------
 * Every page listed in blocked_urls_paths() below:
 *   - answers with a real HTTP 404 + the site's "File not found" page (vendor.php, items.php, ptype.php check it),
 *   - is sent with "X-Robots-Tag: noindex" so search engines drop it,
 *   - is hidden from the hard-coded menu / home page blocks that use blocked_url_vendor_is_blocked(),
 *   - is left out of the public lists: vendor lists, header search results (all_exams.php), "related" exam / certification
 *     lists, demo / video pages - through blocked_url_sql_exclude(). Demo links (<vendor>-demo-<code>.html, demo.php?cert_id=,
 *     download_demo.php) answer 404 too.
 * NOT touched on purpose: the logged-in customer area (My Downloads / My Testing Engine / orders), the cart and bundle
 * pricing - customers who already bought a blocked exam keep their access. Static files are served by Apache directly.
 *
 * HOW TO BLOCK ANOTHER PAGE / UNBLOCK ONE
 *   1. Add or remove the public path (without domain) in blocked_urls_paths().
 *   2. Add or remove the same path as a "Disallow:" line in robots.txt (static file, cannot read this list).
 *   That is all - no other file needs a change.
 *
 * PATH TYPES (they mirror the rewrite rules in .htaccess)
 *   <name>-certification-dumps.html  -> vendor page      (vendor.php?name=<name>)
 *   <name>-cert.htm                  -> certification    (items.php?name=<name>)
 *   <code>.htm / <code>_dumps.htm    -> exam page        (ptype.php?pcode=<code>)
 * The block also applies to every other way of reaching the same page
 * (e.g. ptype.php?pcode=<code>, exam-<name>.htm, different upper/lower case).
 *
 * PHP 5.4 .. 8.x compatible.
 */

if (!function_exists('blocked_urls_paths')) {
    function blocked_urls_paths()
    {
        return array(
            'comptia-certification-dumps.html',
            'linux-cert.htm',
            'xk0-006_dumps.htm',
            'ctt-cert.htm',
            'tk0-201_dumps.htm',
            'it-fundamentals-cert.htm',
            'fc0-u61_dumps.htm',
            'a-cert.htm',
            '220-1202_dumps.htm',
            '220-1101_dumps.htm',
            '220-1102_dumps.htm',
            '220-1201_dumps.htm',
            'comptia-cloud-essentials-cert.htm',
            'clo-002_dumps.htm',
            'comptia-cloud-cert.htm',
            'cv0-003_dumps.htm',
            'cv0-004_dumps.htm',
            'comptia-server-cert.htm',
            'sk0-005_dumps.htm',
            'project-cert.htm',
            'pk0-005_dumps.htm',
            'comptia-cysa-cert.htm',
            'cs0-003_dumps.htm',
            'comptia-security-cert.htm',
            'sy0-701_dumps.htm',
            'comptia-network-cert.htm',
            'n10-009_dumps.htm',
            'comptia-datasysp-cert.htm',
            'ds0-001_dumps.htm',
            'pentestp-cert.htm',
            'pt0-003_dumps.htm',
            'comptia-techp-cert.htm',
            'fc0-u71_dumps.htm',
            'comptia-securityx-cert.htm',
            'cas-005_dumps.htm',
            'comptia-datap-cert.htm',
            'da0-001_dumps.htm',
            'da0-002_dumps.htm',
            'sy0-501.htm',
            'pt0-001.htm',
        );
    }
}

if (!function_exists('blocked_url_normalize')) {
    /* lower case, trimmed, no control characters (the database compares case-insensitively and ignores trailing spaces) */
    function blocked_url_normalize($value)
    {
        if (!is_scalar($value)) {
            return '';
        }
        return strtolower(trim(preg_replace('/[\x00-\x1F\x7F]/', '', (string) $value)));
    }
}

if (!function_exists('blocked_urls_index')) {
    /* Splits the path list into vendor / certification / exam slugs (same rules as .htaccess). Built once per request. */
    function blocked_urls_index()
    {
        static $index = null;
        if ($index !== null) {
            return $index;
        }
        $index = array('paths' => array(), 'vendors' => array(), 'certs' => array(), 'exams' => array(), 'codes' => array());
        foreach (blocked_urls_paths() as $path) {
            $path = blocked_url_normalize(ltrim($path, '/'));
            if ($path === '') {
                continue;
            }
            $index['paths'][$path] = true;
            if (preg_match('/^(.+)-certification-dumps\.html$/', $path, $m)) {
                $index['vendors'][$m[1]] = true;
            } elseif (preg_match('/^(.+)-cert\.htm$/', $path, $m)) {
                $index['certs'][$m[1]] = true;
            } elseif (preg_match('/^(.+)\.htm$/', $path, $m)) {
                $index['exams'][$m[1]] = true;
                /* exam code as used by the demo links, e.g. 'fc0-u61_dumps' -> 'fc0-u61' */
                $index['codes'][preg_replace('/_dumps$/', '', $m[1])] = true;
            }
        }
        return $index;
    }
}

if (!function_exists('blocked_url_value_is_blocked')) {
    /* $type: 'vendors' | 'certs' | 'exams'; $value: the slug, e.g. 'comptia', 'linux', 'fc0-u61_dumps' */
    function blocked_url_value_is_blocked($type, $value)
    {
        $index = blocked_urls_index();
        $value = blocked_url_normalize($value);
        return $value !== '' && isset($index[$type]) && isset($index[$type][$value]);
    }
}

if (!function_exists('blocked_url_vendor_is_blocked')) {
    /* Used by hard-coded menu / home page blocks to hide a whole vendor, e.g. blocked_url_vendor_is_blocked('comptia') */
    function blocked_url_vendor_is_blocked($slug)
    {
        return blocked_url_value_is_blocked('vendors', $slug);
    }
}

if (!function_exists('blocked_url_demo_is_blocked')) {
    /* Demo pages (<vendor>-demo-<code>.html -> thankyou.php?vname=<vendor>&ecode=<code>): blocked vendor or blocked exam code */
    function blocked_url_demo_is_blocked($vendorSlug, $examCode)
    {
        $index = blocked_urls_index();
        $code  = blocked_url_normalize($examCode);
        $code  = preg_replace('/_dumps$/', '', $code);
        return blocked_url_value_is_blocked('vendors', $vendorSlug) || ($code !== '' && isset($index['codes'][$code]));
    }
}

if (!function_exists('blocked_url_cert_id_is_blocked')) {
    /* For requests that only carry a certification id (demo.php?cert_id=, download_demo.php): looks the slug up first */
    function blocked_url_cert_id_is_blocked($certId)
    {
        $certId = (int) $certId;
        if ($certId <= 0 || !function_exists('mysql_query')) {
            return false;
        }
        $res = mysql_query("SELECT cert_url FROM tbl_cert WHERE cert_id='" . $certId . "' LIMIT 1");
        $row = $res ? mysql_fetch_assoc($res) : false;
        return $row ? blocked_url_value_is_blocked('certs', isset($row['cert_url']) ? $row['cert_url'] : '') : false;
    }
}

if (!function_exists('blocked_url_sql_exclude')) {
    /*
     * SQL fragment that leaves blocked vendors / certifications / exams out of a list query, e.g.
     *   "SELECT * FROM tbl_vendors WHERE ven_status='1'" . blocked_url_sql_exclude('ven_url', 'vendors') . " ORDER BY ven_name"
     * The values come from the list above (not from the visitor), so this cannot be used for injection.
     */
    function blocked_url_sql_exclude($column, $type)
    {
        $index = blocked_urls_index();
        if (empty($index[$type])) {
            return '';
        }
        $values = array();
        foreach (array_keys($index[$type]) as $slug) {
            $values[] = "'" . addslashes((string) $slug) . "'";
        }
        return " AND (" . $column . " IS NULL OR " . $column . " NOT IN (" . implode(',', $values) . "))";
    }
}

if (!function_exists('blocked_url_request_is_blocked')) {
    /*
     * Call at the very top of vendor.php / items.php / ptype.php.
     * True when the requested page is on the blocked list - checks the public path (/fc0-u61_dumps.htm)
     * and the parameter the rewrite rule produced (pcode / name), so every URL form is covered.
     */
    function blocked_url_request_is_blocked()
    {
        $index = blocked_urls_index();

        $uri  = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
        $path = parse_url($uri, PHP_URL_PATH);
        if (!is_string($path)) {
            $cut  = strpos($uri, '?');
            $path = $cut === false ? $uri : substr($uri, 0, $cut);
        }
        $name = blocked_url_normalize(basename(rawurldecode($path)));
        if ($name !== '' && isset($index['paths'][$name])) {
            return true;
        }

        $script = strtolower(basename(isset($_SERVER['SCRIPT_NAME']) ? (string) $_SERVER['SCRIPT_NAME'] : ''));
        if ($script === 'vendor.php') {
            return blocked_url_value_is_blocked('vendors', isset($_GET['name']) ? $_GET['name'] : '');
        }
        if ($script === 'items.php') {
            return blocked_url_value_is_blocked('certs', isset($_GET['name']) ? $_GET['name'] : '');
        }
        if ($script === 'ptype.php') {
            return blocked_url_value_is_blocked('exams', isset($_GET['pcode']) ? $_GET['pcode'] : '');
        }
        return false;
    }
}

if (!function_exists('blocked_url_send_404')) {
    /* Sends the 404 status + noindex headers and drops anything already buffered. The caller then includes notfound.php. */
    function blocked_url_send_404()
    {
        while (ob_get_level() > 0) {
            @ob_end_clean();
        }
        if (!headers_sent()) {
            http_response_code(404);
            header('X-Robots-Tag: noindex, nofollow, noarchive');
            header('Cache-Control: no-store, no-cache, must-revalidate');
        }
    }
}
