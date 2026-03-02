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
	$category = $objEvent->fillComboCategory_exam();
	$subcat	=	$objEvent->fillComboSubCategory('','');

//------------------------------------------------------------------------------------//
$strError = $_SESSION['displaytext'];
$_SESSION['displaytext']="";
include "html/bulk_update.html";

//------------------------------------------------------------------------------------//
?>