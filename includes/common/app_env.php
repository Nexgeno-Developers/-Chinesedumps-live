<?php
/*
 * Environment / debug / PayPal mode, all controlled from includes/config/secrets.local.php
 * PHP 5.4+ compatible. Safe to require many times.
 *
 * Keys read from secrets (defaults are the SAFE production values):
 *   APP_ENV                 'production' | 'development'
 *   APP_DEBUG               true  = show PHP errors and SQL errors on screen
 *                           false = hide them from visitors (they are still written to the error log)
 *   APP_LOG_ERRORS          true (default) = write errors to the server error log
 *   SMTP_DEBUG              true = log the SMTP conversation (includes the login in base64, keep false)
 *   PAYPAL_MODE             'live' | 'sandbox'
 */
require_once dirname(dirname(__FILE__)) . '/config/config.php';

if (!function_exists('app_bool')) {
    function app_bool($value, $default = false)
    {
        if (is_bool($value)) {
            return $value;
        }
        if ($value === null || $value === '') {
            return $default;
        }
        $v = strtolower(trim((string)$value));
        if (in_array($v, array('1', 'true', 'yes', 'on'), true)) {
            return true;
        }
        if (in_array($v, array('0', 'false', 'no', 'off'), true)) {
            return false;
        }
        return $default;
    }
}

if (!function_exists('app_env')) {
    function app_env()
    {
        $env = strtolower(trim((string)secret('APP_ENV', 'production')));
        return ($env === 'development' || $env === 'dev' || $env === 'local') ? 'development' : 'production';
    }
}

if (!function_exists('app_debug')) {
    function app_debug()
    {
        return app_bool(secret('APP_DEBUG', false), false);
    }
}

if (!function_exists('app_smtp_debug')) {
    function app_smtp_debug()
    {
        return app_bool(secret('SMTP_DEBUG', false), false);
    }
}

if (!function_exists('app_apply_error_settings')) {
    /* Call once per request. Same error level the site already used (no notices / strict / deprecated). */
    function app_apply_error_settings()
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
        if (app_debug()) {
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
            error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED); /* debug also shows notices */
        } else {
            ini_set('display_errors', '0');
            ini_set('display_startup_errors', '0');
        }
        ini_set('log_errors', app_bool(secret('APP_LOG_ERRORS', true), true) ? '1' : '0');
    }
}

if (!function_exists('app_db_error')) {
    /* Text used by die() when a SQL query fails. Details only when APP_DEBUG is on. */
    function app_db_error($prefix)
    {
        $err = function_exists('mysql_error') ? mysql_error() : '';
        if (app_debug()) {
            return $prefix . ' Following error has occured <br>' . $err;
        }
        error_log('SQL error: ' . $prefix . ' ' . $err);
        return 'Sorry, something went wrong. Please try again later.';
    }
}

/* ---------------- PayPal ---------------- */
if (!function_exists('paypal_mode')) {
    function paypal_mode()
    {
        return strtolower(trim((string)secret('PAYPAL_MODE', 'live'))) === 'sandbox' ? 'sandbox' : 'live';
    }
}

if (!function_exists('paypal_form_url')) {
    /* Where the checkout form posts the buyer to. */
    function paypal_form_url()
    {
        return paypal_mode() === 'sandbox'
            ? 'https://www.sandbox.paypal.com/cgi-bin/webscr'
            : 'https://www.paypal.com/cgi-bin/webscr';
    }
}

if (!function_exists('paypal_ipn_verify_url')) {
    /* Where the IPN listener sends the message back for verification. */
    function paypal_ipn_verify_url()
    {
        return paypal_mode() === 'sandbox'
            ? 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr'
            : 'https://ipnpb.paypal.com/cgi-bin/webscr';
    }
}
