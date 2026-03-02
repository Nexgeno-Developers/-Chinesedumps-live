<?PHP 
ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classUser.php");
	include("../functions.php");

include_once 'functions.php';
$ccAdminData = get_session_data();


//--------------------Creat objects---------------------------

			$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
			$objValid 	 = new Validate_fields;
			$objUser	 = new classUser($objDBcon);	// VALIDATION CLASS classCategory

	$strError	=	"";
		$spram[1]		 =  "";
		$spram[2]		 =  "";
		$spram[3]		 =  ""; 
		$spram[4]		 =  "";
		$spram[5]		 =  "";
		$spram[6]		 =  "";
	
	if(isset($_POST['Submit']) && $_POST['Submit']  == "Submit")

	{
		   $spram[1]	= $_POST['fname'];
		   $spram[2]	= $_POST['surname'];
		   $spram[5]	= $_POST['email'];
		   $spram[9]	= $_POST['passuser'];
		   $spram[6]	=  date("Y-m-d");
		   $spram[10]	= $_POST['user_status'];
		 

		$objValid->add_num_field("Email", $_POST['email'], "email", "y");
		$objValid->add_text_field("Last Name", $_POST['surname'], "text", "y");
		$objValid->add_text_field("First Name", $_POST['fname'], "text", "y");

		if (!$objValid->validation())
		$strError = $objValid->create_msg();
		
		if(!isset($_POST['passuser']) || $_POST['passuser'] == '')
		$strError	.=	"<b>The field Password is empty.</b>";
		elseif(isset($_POST['passuser']) and  $_POST['passuser'] != '' && $_POST['passuser'] != $_POST['user_cpassword'])
		$strError	.=	"<b>The field Password and Confirm Password are not same.</b>";
		
		if(!$objUser->checkUserDuplication($_POST['email']))
		$strError	.=	"<b><br/>The Login is not available.</b>";
		if(empty($strError))
		{
		$userid = $objUser->addUser($spram);
		
		$strError	.= "User has been added successfully";
		}
		}
//------------------------------------------------------------------------------------//

include "html/adduser.html";

//------------------------------------------------------------------------------------//
?>