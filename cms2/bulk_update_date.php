<?PHP 
require_once dirname(dirname(__FILE__)) . '/includes/common/sanitize.php';
ob_start();
session_start();

	include_once ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classEvents.php");

	include_once 'functions.php';
	$ccAdminData = get_session_data();
	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
	$objValid 	 = new Validate_fields;
	$objEvent	=	new classEvents($objDBcon);	// VALIDATION CLASS classCategory
	$bulkAction	=	isset($_POST['action']) ? preg_replace('/[^A-Za-z0-9_]/', '', $_POST['action']) : '';

if(isset($_POST['action']) && $_POST['action'] == 'all_date')
{
	$date = $_POST['new_date'];
	if(!empty($date))
	{
		$sel = "update tbl_exam set exam_upddate = '".db_esc($date)."'";
		mysql_query($sel);
	}
	$_SESSION['displaytext']="Date is updated successfully for all exams.<br />";
	syslog_write('Exams', 'BULK_EXAM_UPDATE', syslog_who().' ran a bulk exam update: Date is updated successfully for all exams.', array('entity_type' => 'exam', 'details' => array('scope' => $_POST['action'], 'new_date' => isset($date) ? $date : '', 'new_price' => isset($price) ? $price : '')));
	header("Location:bulk_update.php?action=".$bulkAction."");
	exit();
}
if(isset($_POST['action']) && $_POST['action'] == 'cert_date')
{
	if(isset($_POST['cmb_subcategory']))
	{
		$date = $_POST['new_date'];
		foreach($_POST['cmb_subcategory'] as $val)
		{
			$sel = "update tbl_exam set exam_upddate = '".db_esc($date)."' where cert_id like '%,".san_int($val).",%'";
			mysql_query($sel);
		}
	}
	$_SESSION['displaytext']="Date is updated successfully for exams in selected Certificates.<br />";
	syslog_write('Exams', 'BULK_EXAM_UPDATE', syslog_who().' ran a bulk exam update: Date is updated successfully for exams in selected Certificates.', array('entity_type' => 'exam', 'details' => array('scope' => $_POST['action'], 'new_date' => isset($date) ? $date : '', 'new_price' => isset($price) ? $price : '')));
	header("Location:bulk_update.php?action=".$bulkAction."");
	exit();
}
if(isset($_POST['action']) && $_POST['action'] == 'bundle_date')
{
	if(isset($_POST['cmb_cate']))
	{
		$date = $_POST['new_date'];
		$bundleID = $_POST['cmb_cate'];
		$sel = "update tbl_exam set exam_upddate = '".db_esc($date)."' where ven_id = '".san_int($bundleID)."'";
		mysql_query($sel);
	}
	$_SESSION['displaytext']="Date is updated successfully for exams in selected Vendor.<br />";
	syslog_write('Exams', 'BULK_EXAM_UPDATE', syslog_who().' ran a bulk exam update: Date is updated successfully for exams in selected Vendor.', array('entity_type' => 'exam', 'details' => array('scope' => $_POST['action'], 'new_date' => isset($date) ? $date : '', 'new_price' => isset($price) ? $price : '')));
	header("Location:bulk_update.php?action=".$bulkAction."");
	exit();
}


if(isset($_POST['action']) && $_POST['action'] == 'all_price')
{
	$price = $_POST['new_price'];
	if(!empty($price))
	{
		$sel = "update tbl_exam set exam_pri0 = '".db_esc($price)."'";
		mysql_query($sel);
	}
	$_SESSION['displaytext']="Price is updated successfully for all exams.<br />";
	syslog_write('Exams', 'BULK_EXAM_UPDATE', syslog_who().' ran a bulk exam update: Price is updated successfully for all exams.', array('entity_type' => 'exam', 'details' => array('scope' => $_POST['action'], 'new_date' => isset($date) ? $date : '', 'new_price' => isset($price) ? $price : '')));
	header("Location:bulk_update.php?action=".$bulkAction."");
	exit();
}
if(isset($_POST['action']) && $_POST['action'] == 'cert_price')
{
	if(isset($_POST['cmb_subcategory']))
	{
		$price = $_POST['new_price'];
		foreach($_POST['cmb_subcategory'] as $val)
		{
			$sel = "update tbl_exam set exam_pri0 = '".db_esc($price)."' where cert_id like '%,".san_int($val).",%'";
			mysql_query($sel);
		}
	}
	$_SESSION['displaytext']="Price is updated successfully for exams in selected certificates.<br />";
	syslog_write('Exams', 'BULK_EXAM_UPDATE', syslog_who().' ran a bulk exam update: Price is updated successfully for exams in selected certificates.', array('entity_type' => 'exam', 'details' => array('scope' => $_POST['action'], 'new_date' => isset($date) ? $date : '', 'new_price' => isset($price) ? $price : '')));
	header("Location:bulk_update.php?action=".$bulkAction."");
	exit();
}

if(isset($_POST['action']) && $_POST['action'] == 'bundle_price')
{
	if(isset($_POST['cmb_cate']))
	{
		$price = $_POST['new_price'];
		$bundleID = $_POST['cmb_cate'];
		$sel = "update tbl_exam set exam_pri0 = '".db_esc($price)."' where ven_id = '".san_int($bundleID)."'";
		mysql_query($sel);
	}
	$_SESSION['displaytext']="Price is updated successfully for exams in selected bundle.<br />";
	syslog_write('Exams', 'BULK_EXAM_UPDATE', syslog_who().' ran a bulk exam update: Price is updated successfully for exams in selected bundle.', array('entity_type' => 'exam', 'details' => array('scope' => $_POST['action'], 'new_date' => isset($date) ? $date : '', 'new_price' => isset($price) ? $price : '')));
	header("Location:bulk_update.php?action=".$bulkAction."");
	exit();
}

	header("Location:bulk_update.php?action=".$bulkAction."");
	exit();
?>