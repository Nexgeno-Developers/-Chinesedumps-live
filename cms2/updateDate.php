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


//--------------------Creat objects---------------------------

			$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
			$objValid 	 = new Validate_fields;
			$objEvent	=	new classEvents($objDBcon);	// VALIDATION CLASS classCategory

	$strError	=	"";
	if(isset($_POST['Submit']) && $_POST['Submit']  == "Submit")
	{
		$vendor	=	$_POST['cmb_cate'];
		$date	=	$_POST['startdate'];
		if(isset($_POST['all_exams']))
		{
			mysql_query("update tbl_exam set exam_upddate = '".db_esc($date)."'");
		}
		else
		{
			mysql_query("update tbl_exam set exam_upddate = '".db_esc($date)."' where ven_id = '".san_int($vendor)."'");
		}
		$strError = "Exams have been updated successfully";
		syslog_write('Exams', 'BULK_EXAM_UPDATE', syslog_who().' set the "updated" date of '.(isset($_POST['all_exams']) ? 'all exams' : 'all exams of vendor ID '.san_int($vendor)).' to '.$date.'.', array('entity_type' => 'exam', 'details' => array('new_date' => $date, 'vendor_id' => san_int($vendor), 'all_exams' => isset($_POST['all_exams']))));
	}
	$category = $objEvent->fillComboCategory();
//------------------------------------------------------------------------------------//
include "html/updateDate.html";
//------------------------------------------------------------------------------------//
?>