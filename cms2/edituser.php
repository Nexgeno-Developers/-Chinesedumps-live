<?PHP 

ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classUser.php");

	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
	$objValid 	 = new Validate_fields;
	$objUser	 = new classUser($objDBcon);	// VALIDATION CLASS classCategory
	$strError	=	"";
//-------------------------------------------------------------------------------------------
include_once 'functions.php';
$ccAdminData = get_session_data();

		$spram	=   array();

if (isset($_POST['submit']) && $_POST['submit']	==	"submit")

	{
		   $spram[0]	= $_GET['nid'];
		   $spram[1]    = $_POST['fname'];
		   $spram[2]	= $_POST['surname'];
		   $spram[5]	= $_POST['email'];
		   $spram[9]	= $_POST['passuser'];
		   $spram[10]	=  $_POST['user_status'];
		  
//------------------------------------------validation--------------------------------//			
		$objValid->add_text_field("Last Name", $_POST['surname'], "text", "y");
		$objValid->add_text_field("First Name", $_POST['fname'], "text", "y");
//-----------------------------------------------------------------------------------//

		if (!$objValid->validation()) 

			$strError = $objValid->create_msg();
			if($_POST['passuser'] == "" && $_POST['user_cpassword'] == "")
			{
			$spram[9]		 =  $_POST['user_hpassword'];
			}elseif(isset($_POST['passuser']) and  $_POST['passuser'] != '' && $_POST['passuser'] != $_POST['user_cpassword'])
			$strError	.=	"The field Password and Confirm Password are not same.";
			
		if(empty($strError))
		{
				if($_POST['email']!=''){
				//$spram[5] 	= $_POST['email'];
				}
				else{
				//$spram[5] 	= $_POST['hiddenemail'];
				}
			$objUser->UpdateUserAdmin($spram);
			$strError	= "User has been Updated successfully";
	    }

	   }
			$spram[0]	    =	$_GET['nid'];
			
			
			$resultSet		 =	$objUser->getUser($spram[0]);
			
			while($row1		 =	mysql_fetch_array($resultSet))
			{
		 	$spram[1]		 =  $row1['user_fname'];
		 	$spram[2]		 =  $row1['user_lname'];
			$spram[5]		 =  $row1['user_email'];
			$spram[9]		 =  $row1['user_password'];
			$spram[10]		 =  $row1['user_status'];
			
			}
//--------------------------------------------------------------------------------------//

/*Html View Area*/

include "html/edituser.html";

//--------------------------------------------------------------------------------------//

?>