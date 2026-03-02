<?PHP 
session_start();
error_reporting(0);
//root path
$LinkPath="../";
//title of the page
$strTitle	=	"Add News";
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

	if (isset($_POST['changeCopyright']) && $_POST['changeCopyright']	==	"Change Copyright")
	{
	//print  $_FILES['txtImage']; exit();
		$spram[1]		 =  $_POST['copyright'];

		// check validations
	 	$objValid->add_text_field("Website Copyright", $_POST['copyright'], "text", "y");
		
		
		if (!$objValid->validation()) 
			$strError = $objValid->create_msg();
			
			
		if(empty($strError))
			{
		
			$objPassword->changeCopyright($spram);
			$strError	= "Data have been saved successfully";
			}
		
	}else{
	$quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
	$spram[1]	=	$exec['copyright'];
	}	
//--------------------------------------------------------------------------------------

	include ("html/copyright.html");
//-------------------------------------------------------------------------------------
?>