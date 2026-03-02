<?PHP 
ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classEvents.php");

	include_once 'functions.php';
	$ccAdminData = get_session_data();
	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
	$objValid 	 = new Validate_fields;
	$objEvent	=	new classEvents($objDBcon);	// VALIDATION CLASS classCategory

if(isset($_POST['action']) && $_POST['action'] == 'all_date')
{
	$date = $_POST['new_date'];
	if(!empty($date))
	{
		$sel = "update tbl_exam set exam_upddate = '$date'";
		mysql_query($sel);
	}
	$_SESSION['displaytext']="Date is updated successfully for all exams.<br />";
	header("Location:bulk_update.php?action=".$_POST['action']."");
	exit();
}
if(isset($_POST['action']) && $_POST['action'] == 'cert_date')
{
	if(isset($_POST['cmb_subcategory']))
	{
		$date = $_POST['new_date'];
		foreach($_POST['cmb_subcategory'] as $val)
		{
			$sel = "update tbl_exam set exam_upddate = '$date' where cert_id like '%,".$val.",%'";
			mysql_query($sel);
		}
	}
	$_SESSION['displaytext']="Date is updated successfully for exams in selected Certificates.<br />";
	header("Location:bulk_update.php?action=".$_POST['action']."");
	exit();
}
if(isset($_POST['action']) && $_POST['action'] == 'bundle_date')
{
	if(isset($_POST['cmb_cate']))
	{
		$date = $_POST['new_date'];
		$bundleID = $_POST['cmb_cate'];
		$sel = "update tbl_exam set exam_upddate = '$date' where ven_id = '$bundleID'";
		mysql_query($sel);
	}
	$_SESSION['displaytext']="Date is updated successfully for exams in selected Vendor.<br />";
	header("Location:bulk_update.php?action=".$_POST['action']."");
	exit();
}


if(isset($_POST['action']) && $_POST['action'] == 'all_price')
{
	$price = $_POST['new_price'];
	if(!empty($price))
	{
		$sel = "update tbl_exam set exam_pri0 = '$price'";
		mysql_query($sel);
	}
	$_SESSION['displaytext']="Price is updated successfully for all exams.<br />";
	header("Location:bulk_update.php?action=".$_POST['action']."");
	exit();
}
if(isset($_POST['action']) && $_POST['action'] == 'cert_price')
{
	if(isset($_POST['cmb_subcategory']))
	{
		$price = $_POST['new_price'];
		foreach($_POST['cmb_subcategory'] as $val)
		{
			$sel = "update tbl_exam set exam_pri0 = '$price' where cert_id like '%,".$val.",%'";
			mysql_query($sel);
		}
	}
	$_SESSION['displaytext']="Price is updated successfully for exams in selected certificates.<br />";
	header("Location:bulk_update.php?action=".$_POST['action']."");
	exit();
}

if(isset($_POST['action']) && $_POST['action'] == 'bundle_price')
{
	if(isset($_POST['cmb_cate']))
	{
		$price = $_POST['new_price'];
		$bundleID = $_POST['cmb_cate'];
		$sel = "update tbl_exam set exam_pri0 = '$price' where ven_id = '$bundleID'";
		mysql_query($sel);
	}
	$_SESSION['displaytext']="Price is updated successfully for exams in selected bundle.<br />";
	header("Location:bulk_update.php?action=".$_POST['action']."");
	exit();
}

	header("Location:bulk_update.php?action=".$_POST['action']."");
	exit();
?>