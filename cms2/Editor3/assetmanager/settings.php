<?php
// admin session guard: the asset manager must only be reachable from the logged-in CMS
if (session_id() === '') { session_start(); }
if (!isset($_SESSION['strAdmin']) || !isset($_SESSION['adminId'])) { header('HTTP/1.1 403 Forbidden'); exit('Forbidden'); }



$bReturnAbsolute=false;





//$sBaseVirtual0="http://shakoor/top1club/adminimages/";  



$sBaseVirtual0 = "http://www.conceptbeans.com/projects/testcerts/adminimages/";

//$sBase0="C:/apache2triad/htdocs/hair/adminimages"; 



//$sBase0= $_SERVER['DOCUMENT_ROOT']."/adminimages";

$sBase0= $_SERVER['DOCUMENT_ROOT']."/testcerts/adminimages";



//$sBase0="/home/yourserver/web/Editor/assets"; //example for Unix server

$sName0="adminimages";



$sBaseVirtual1="";

$sBase1="";

$sName1="";



$sBaseVirtual2="";

$sBase2="";

$sName2="";



$sBaseVirtual3="";

$sBase3="";

$sName3="";


/* ---- hardening helpers (shared by assetmanager.php, foldernew.php, folderdel_.php) ---- */
if (!function_exists('am_norm')) {
	function am_norm($p)
	{
		$p = str_replace('\\', '/', (string)$p);
		$p = rtrim($p, '/');
		if (DIRECTORY_SEPARATOR === '\\') { $p = strtolower($p); }
		return $p;
	}
}
if (!function_exists('am_folder_ok')) {
	// true when $folder is an existing folder inside one of the configured asset roots
	// (or, when no configured root exists, inside the web root). Never accepts "..".
	function am_folder_ok($folder, $forDelete = false)
	{
		global $sBase0, $sBase1, $sBase2, $sBase3;
		if (!is_string($folder) || $folder === '' || strpos($folder, "\0") !== false) { return false; }
		$folder = str_replace('\\', '/', $folder);
		foreach (explode('/', $folder) as $seg) { if ($seg === '..') { return false; } }
		$rf = realpath($folder);
		if ($rf === false) { return false; }
		$rf = am_norm($rf);
		$roots = array();
		foreach (array($sBase0, $sBase1, $sBase2, $sBase3) as $b) {
			if (is_string($b) && $b !== '') {
				$r = realpath($b);
				if ($r !== false) { $roots[] = am_norm($r); }
			}
		}
		if (empty($roots)) {
			$doc = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
			if ($doc === false) { return false; }
			$roots[] = am_norm($doc);
		}
		foreach ($roots as $r) {
			if ($forDelete) {
				if ($rf !== $r && strpos($rf . '/', $r . '/') === 0) { return true; }
			} elseif ($rf === $r || strpos($rf . '/', $r . '/') === 0) {
				return true;
			}
		}
		return false;
	}
}
if (!function_exists('am_safe_name')) {
	// strip path and special characters from a client supplied file / folder name
	function am_safe_name($name)
	{
		$name = str_replace("\0", '', (string)$name);
		$name = str_replace('\\', '/', $name);
		$name = basename($name);
		$name = preg_replace('/[^A-Za-z0-9._ -]/', '_', $name);
		$name = str_replace('..', '_', $name);
		$name = ltrim($name, '.');
		return trim($name);
	}
}
if (!function_exists('am_ext_allowed')) {
	// extension whitelist for uploads; also refuses names carrying an executable extension anywhere
	function am_ext_allowed($name)
	{
		$name = strtolower((string)$name);
		if ($name === '' || strpos($name, "\0") !== false || strpos($name, '..') !== false) { return false; }
		$parts = explode('.', $name);
		if (count($parts) < 2) { return false; }
		$allowed = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'swf', 'flv', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'txt');
		$bad = array('php', 'php3', 'php4', 'php5', 'php6', 'php7', 'phtml', 'pht', 'phps', 'phar', 'htaccess', 'asp', 'aspx', 'asa', 'cer', 'jsp', 'jspx', 'exe', 'sh', 'cgi', 'pl', 'py', 'shtml', 'shtm', 'bat', 'cmd', 'com', 'dll');
		if (!in_array(end($parts), $allowed, true)) { return false; }
		for ($i = 1; $i < count($parts); $i++) {
			if (in_array($parts[$i], $bad, true)) { return false; }
		}
		return true;
	}
}
?>