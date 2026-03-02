<?PHP 
error_reporting(0);
session_start();

//root path
$LinkPath="../";
//title of the page
$strTitle	=	"Change Contact Us";
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

	if (isset($_POST['changepassword']) && $_POST['changepassword']	==	"Change Email")
	{
	//print  $_FILES['txtImage']; exit();
		$spram[1]		 =  $_POST['link1'];
		$spram[2]		 =  $_POST['link2'];
		
				
		// check validations
		$objValid->add_text_field("Email Address", $_POST['link1'], "email", "y");
		$objValid->add_text_field("From Email Address", $_POST['link2'], "email", "y");
		
		
		if (!$objValid->validation()) 
			$strError = $objValid->create_msg();
			
		if(empty($strError))
		{
	
			$objPassword->changeContactUs($spram);
			$strError	= "Email Addresses saved successfully";
		}
		//$objCat->addNewCategory($spram);
	}else{	
	$quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
	$spram[1]	=	$exec['contactus_email'];
	$spram[2]	=	$exec['from_email'];
	
	}
//--------------------------------------------------------------------------------------

	include ("html/contactus.html");
//-------------------------------------------------------------------------------------
?>