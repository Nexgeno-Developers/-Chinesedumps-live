<?PHP 
error_reporting(0);
session_start();

//root path
$LinkPath="../";
//title of the page
$strTitle	=	"Change Follow Us";
/*include files*/

include ("../includes/config/classDbConnection.php");
include("../includes/common/classes/validation_class.php");
include ("../includes/common/inc/sessionheader.php");
include ("../includes/common/classes/classAdmin.php");

include_once 'functions.php';
$ccAdminData = get_session_data();

//-------------------------------------------------------------------------------------------
								/*General Coding Area*/
//-------------------------------------------------------------------------------------------
$strCatName	=	"";		//it will contain the category name in case of duplication
	$strError	=	"";		//it will contain the error message
	$catCombo	=	"";


	$objDBcon    = new classDbConnection; // VALIDATION CLASS OBJECT
	$objPassword = new classAdmin($objDBcon); // VALIDATION CLASS classCategory
	$objValid 	 = new Validate_fields;

	/*  local variables */
	$arrparm	=   array();
/*Rest of the coding area*/

	if (isset($_POST['changepassword']) && $_POST['changepassword']	==	"Change Settings")
	{
		if (!$objValid->validation())
			$strError = $objValid->create_msg();
			
		if(empty($strError))
		{
			$objPassword->changesalesoption($_POST);
			$strError	= "Configuration has been updated successfully";
		}
	}
	$getcontactus1	=	mysql_fetch_array($objDBcon->ExecQuery("SELECT * from website"));
	$spram	=	$getcontactus1;
	$objDBcon->CloseConnection();
//--------------------------------------------------------------------------------------

	include ("html/global.html");
//-------------------------------------------------------------------------------------
?>