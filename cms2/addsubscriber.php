<?PHP 
ini_set("display_errors","0");
ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include ("../includes/common/classes/classmain.php");
	
	

include_once 'functions.php';
$ccAdminData = get_session_data();


//--------------------Creat objects---------------------------

			$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
			$objValid 	 = new Validate_fields;
			$objMain	=	new classMain($objDBcon);	// VALIDATION CLASS classCategory

	$strError	=	"";
		$spram[1]		 =  "";
		$spram[2]		 =  "";
	
	if(isset($_POST['Submit']) && $_POST['Submit']  == "Submit")

	{
		
		$spram[1]		 =  $_POST['maintitle'];
		
		//$objValid->add_text_field("Featured Project URL", $spram[1],"text", "y");
		$objValid->add_text_field("Newsletter Subscriber Email", $spram[1],"email", "y");
		
		if (!$objValid->validation())
		$strError = $objValid->create_msg();
		
		if(empty($strError))
		{
		$insert	=	mysql_query("INSERT INTO tbl_maillist (mail_email)VALUES('".$spram[1]."')");
		$strError	.= "Newsletter Subscriber Email has been added successfully";
		}
		}
//------------------------------------------------------------------------------------//

include "html/addsubscriber.html";

//------------------------------------------------------------------------------------//
?>