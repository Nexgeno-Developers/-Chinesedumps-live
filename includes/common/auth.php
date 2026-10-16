<?php
/* Shared authentication helpers. Kept compatible with PHP 5.4 installations. */

if (!function_exists('auth_timing_safe_equals')) {
    function auth_timing_safe_equals($known, $user)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($known, $user);
        }
        if (!is_string($known) || !is_string($user) || strlen($known) !== strlen($user)) {
            return false;
        }
        $result = 0;
        for ($i = 0; $i < strlen($known); $i++) {
            $result |= ord($known[$i]) ^ ord($user[$i]);
        }
        return $result === 0;
    }
}

if (!function_exists('auth_password_hash')) {
    function auth_password_hash($password)
    {
        if (function_exists('password_hash')) {
            return password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
        }
        $salt = '$2y$12$' . substr(strtr(base64_encode(auth_random_bytes(18)), '+', '.'), 0, 22) . '$';
        return crypt($password, $salt);
    }
}

if (!function_exists('auth_password_verify')) {
    function auth_password_verify($password, $storedHash)
    {
        if (strpos($storedHash, '$2') === 0) {
            if (function_exists('password_verify')) {
                return password_verify($password, $storedHash);
            }
            return auth_timing_safe_equals($storedHash, crypt($password, $storedHash));
        }
        /* Transitional support: successful legacy logins are upgraded immediately. */
        return auth_timing_safe_equals((string)$storedHash, (string)$password);
    }
}

if (!function_exists('auth_password_needs_upgrade')) {
    function auth_password_needs_upgrade($storedHash)
    {
        if (strpos($storedHash, '$2') !== 0) {
            return true;
        }
        return function_exists('password_needs_rehash') && password_needs_rehash($storedHash, PASSWORD_BCRYPT, array('cost' => 12));
    }
}

if (!function_exists('auth_random_bytes')) {
    function auth_random_bytes($length)
    {
        if (function_exists('random_bytes')) {
            return random_bytes($length);
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length, $strong);
            if ($bytes !== false && $strong) {
                return $bytes;
            }
        }
        throw new Exception('Secure random number generation is unavailable.');
    }
}

if (!function_exists('auth_csrf_token')) {
    function auth_csrf_token()
    {
        if (empty($_SESSION['auth_csrf_token'])) {
            $_SESSION['auth_csrf_token'] = bin2hex(auth_random_bytes(32));
        }
        return $_SESSION['auth_csrf_token'];
    }
}

if (!function_exists('auth_csrf_is_valid')) {
    function auth_csrf_is_valid($token)
    {
        return !empty($_SESSION['auth_csrf_token']) && is_string($token)
            && auth_timing_safe_equals($_SESSION['auth_csrf_token'], $token);
    }
}

if (!function_exists('auth_password_is_valid')) {
    function auth_password_is_valid($password, &$error = null)
    {
        if (!is_string($password) || strlen($password) < 12) {
            $error = 'Password must contain at least 12 characters.';
            return false;
        }
        if (strlen($password) > 255) {
            $error = 'Password is too long.';
            return false;
        }
        return true;
    }
}
