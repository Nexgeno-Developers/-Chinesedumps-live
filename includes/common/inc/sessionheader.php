<?
if (session_id() == '') {
	session_start();
}

if (!class_exists('classDbConnection')) {
	include_once dirname(__FILE__).'/../../config/classDbConnection.php';
}

if (!function_exists('get_session_data')) {
	include_once dirname(__FILE__).'/../../../cms2/functions.php';
}

if (!isset($_SESSION["strAdmin"]) || !isset($_SESSION["adminId"])) {
	header("location:index.php");
	exit;
}

$ccAdminData = get_session_data();

$cms2CurrentFile = basename($_SERVER['SCRIPT_NAME']);
$cms2AjaxRequest = (
	(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
	|| in_array($cms2CurrentFile, array('changestatus.php', 'changestatusall.php', 'changestatusnew.php', 'changestatusnewnew.php', 'changemodule.php', 'processsave.php', 'processsavere.php', 'getsubcategory.php'))
);

cms2_require_page_access($cms2CurrentFile, $cms2AjaxRequest);
?>
