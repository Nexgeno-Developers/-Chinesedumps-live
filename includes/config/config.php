<?php

// Central base URL for the whole website (must end with a trailing slash)
$defaultBaseUrl = !empty($websiteURL) ? $websiteURL : '/';
if (!defined('BASE_URL')) {
	define('BASE_URL', $defaultBaseUrl);
}

// Load secrets from includes/config/secrets.php with optional local overrides.
if (!function_exists('load_secrets')) {
	function load_secrets()
	{
		static $secrets = null;
		if ($secrets !== null) {
			return $secrets;
		}

		$secrets = [];
		$baseDir = __DIR__;
		$defaultFile = $baseDir . DIRECTORY_SEPARATOR . 'secrets.php';
		$localFile = $baseDir . DIRECTORY_SEPARATOR . 'secrets.local.php';

		if (is_file($defaultFile)) {
			$data = require $defaultFile;
			if (is_array($data)) {
				$secrets = $data;
			}
		}
		if (is_file($localFile)) {
			$data = require $localFile;
			if (is_array($data)) {
				$secrets = array_replace($secrets, $data);
			}
		}

		// Environment overrides (optional)
		foreach ($secrets as $key => $value) {
			$env = getenv($key);
			if ($env !== false && $env !== '') {
				$secrets[$key] = $env;
			}
		}

		return $secrets;
	}
}

if (!function_exists('secret')) {
	function secret($key, $default = null)
	{
		$secrets = load_secrets();
		if (array_key_exists($key, $secrets)) {
			return $secrets[$key];
		}
		return $default;
	}
}

// Central base path for filesystem operations (project root with trailing separator)
if (!defined('BASE_PATH')) {
	$root = realpath(__DIR__ . '/../../');
	if ($root === false) {
		$root = dirname(__DIR__, 2);
	}
	define('BASE_PATH', rtrim($root, "\\/") . DIRECTORY_SEPARATOR);
}

// Helper: always return a normalized base URL with trailing slash
if (!function_exists('base_url')) {
	function base_url($path = '')
	{
		$base = rtrim((string)BASE_URL, '/') . '/';
		$path = ltrim((string)$path, '/');
		return $base . $path;
	}
}

// Helper: always return a normalized base filesystem path
if (!function_exists('base_path')) {
	function base_path($path = '')
	{
		$base = rtrim((string)BASE_PATH, "\\/") . DIRECTORY_SEPARATOR;
		$path = ltrim((string)$path, "\\/");
		$path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, (string)$path);
		return $base . $path;
	}
}

// Helper: detect absolute URLs
if (!function_exists('is_absolute_url')) {
	function is_absolute_url($url)
	{
		return (bool)preg_match('#^https?://#i', (string)$url);
	}
}

// Helper: build asset URLs and paths safely
if (!function_exists('asset_url')) {
	function asset_url($path)
	{
		$path = (string)$path;
		if ($path === '') {
			return '';
		}
		if (is_absolute_url($path)) {
			return $path;
		}
		return base_url($path);
	}
}

if (!function_exists('asset_path')) {
	function asset_path($path)
	{
		$path = (string)$path;
		if ($path === '' || is_absolute_url($path)) {
			return '';
		}
		return base_path($path);
	}
}

// Optional: rewrite root-relative URLs in HTML output to use BASE_URL
if (!function_exists('rewrite_root_relative_urls')) {
	function rewrite_root_relative_urls($buffer)
	{
		if (!defined('BASE_URL')) {
			return $buffer;
		}
		$base = rtrim((string)BASE_URL, '/');
		if ($base === '') {
			return $buffer;
		}
		// href/src/action/poster/data-src/data-href="/path" => BASE_URL/path
		$buffer = preg_replace('/\\b(href|src|action|poster|data-src|data-href)=([\"\\\'])\\/(?!\\/)/i', '$1=$2' . $base . '/', $buffer);
		// url(/path) in inline styles
		$buffer = preg_replace('/url\\((["\\\']?)\\/(?!\\/)/i', 'url($1' . $base . '/', $buffer);
		return $buffer;
	}
}

if (!function_exists('start_root_url_rewrite')) {
	function start_root_url_rewrite()
	{
		static $started = false;
		if ($started) {
			return;
		}
		$started = true;
		if (php_sapi_name() === 'cli') {
			return;
		}
		ob_start('rewrite_root_relative_urls');
	}
}

// Alias for existing code patterns
if (!function_exists('site_url')) {
	function site_url($path = '')
	{
		return base_url($path);
	}
}
?>
