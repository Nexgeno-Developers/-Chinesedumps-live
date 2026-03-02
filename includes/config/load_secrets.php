<?php
/**
 * Bootstrap for secret() and secret_js().
 * Include this once at the top of any file that uses secrets.
 * Idempotent: safe to require multiple times.
 */
if (!function_exists('secret')) {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
}
