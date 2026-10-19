<?php
/*
 * Shared input sanitising helpers.
 * PHP 5.3+ compatible (no ??, no scalar type hints). Safe to include many times.
 *
 *   san_int($v)           -> integer (0 when not numeric)
 *   san_num($v)           -> float-safe numeric string ("0" when not numeric)
 *   db_esc($v)            -> string escaped for use INSIDE single/double quotes of a SQL literal
 *   san_get($k) / san_post($k) / san_req($k)  -> raw scalar from the request (arrays/missing => '')
 *   san_slug($v)          -> only letters, digits, space . _ - (for URL slugs / codes)
 *   san_email($v)         -> trimmed e-mail or '' when invalid
 *   san_html($v)          -> htmlspecialchars for echoing request data into HTML
 *   san_ident($v, $list)  -> returns $v only when it is in the whitelist array, else ''
 *   san_text($v)          -> trims, removes NUL / control characters
 */

if (!function_exists('san_text')) {
    function san_text($v)
    {
        if (is_array($v) || is_object($v)) {
            return '';
        }
        $v = (string)$v;
        // strip NUL and other control characters, keep tab / CR / LF
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $v);
    }
}

if (!function_exists('db_esc')) {
    function db_esc($v)
    {
        if (is_array($v) || is_object($v)) {
            return '';
        }
        $v = san_text($v);
        $r = false;
        if (function_exists('mysql_real_escape_string')) {
            $r = @mysql_real_escape_string($v);
        }
        if ($r === false || $r === null) {
            // no open mysql link: escape by hand (latin1/utf8 safe)
            $r = strtr($v, array(
                "\\"   => "\\\\",
                "\0"   => "\\0",
                "\n"   => "\\n",
                "\r"   => "\\r",
                "'"    => "\\'",
                '"'    => '\\"',
                "\x1a" => "\\Z"
            ));
        }
        return $r;
    }
}

if (!function_exists('san_int')) {
    function san_int($v)
    {
        if (is_array($v) || is_object($v)) {
            return 0;
        }
        // same leading-digits behaviour as intval(), so "12abc" => 12, "1 OR 1=1" => 1
        return (int)trim((string)$v);
    }
}

if (!function_exists('san_num')) {
    function san_num($v)
    {
        if (is_array($v) || is_object($v)) {
            return '0';
        }
        $v = trim((string)$v);
        return preg_match('/^-?[0-9]+(\.[0-9]+)?$/', $v) ? $v : '0';
    }
}

if (!function_exists('san_get')) {
    function san_get($k, $default = '')
    {
        return (isset($_GET[$k]) && !is_array($_GET[$k])) ? (string)$_GET[$k] : $default;
    }
}

if (!function_exists('san_post')) {
    function san_post($k, $default = '')
    {
        return (isset($_POST[$k]) && !is_array($_POST[$k])) ? (string)$_POST[$k] : $default;
    }
}

if (!function_exists('san_req')) {
    function san_req($k, $default = '')
    {
        if (isset($_POST[$k]) && !is_array($_POST[$k])) {
            return (string)$_POST[$k];
        }
        if (isset($_GET[$k]) && !is_array($_GET[$k])) {
            return (string)$_GET[$k];
        }
        return $default;
    }
}

if (!function_exists('san_slug')) {
    function san_slug($v)
    {
        if (is_array($v) || is_object($v)) {
            return '';
        }
        return preg_replace('/[^A-Za-z0-9 ._\-]/', '', (string)$v);
    }
}

if (!function_exists('san_email')) {
    function san_email($v)
    {
        if (is_array($v) || is_object($v)) {
            return '';
        }
        $v = trim((string)$v);
        return filter_var($v, FILTER_VALIDATE_EMAIL) ? $v : '';
    }
}

if (!function_exists('san_html')) {
    function san_html($v)
    {
        if (is_array($v) || is_object($v)) {
            return '';
        }
        return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('san_ident')) {
    function san_ident($v, $allowed)
    {
        return (is_string($v) && in_array($v, $allowed, true)) ? $v : '';
    }
}
