<?PHP 

ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classAdmin.php");

	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
	$objValid 	 = new Validate_fields;
	$objAdmin	 = new classAdmin($objDBcon);	// VALIDATION CLASS classCategory
	$strError	=	"";
//-------------------------------------------------------------------------------------------
include_once 'functions.php';
$ccAdminData = get_session_data();

		$spram	=   array();

if (isset($_POST['submit']) && $_POST['submit']	==	"submit")

	{
		$spram[0]		 =	$_GET['nid'];
		$spram[1]		 =  $_POST['user_fname'];
		$spram[2]		 =  $_POST['user_lname'];
		$spram[3]		 =  $_POST['user_email'];
		$spram[4]		 =  $_POST['user_status'];
		$spram[5]		 =  md5($_POST['user_password']);
		
//------------------------------------------validation--------------------------------//	

		$objValid->add_text_field("First Name", $_POST['user_fname'], "text", "y");
		$objValid->add_text_field("Last Name", $_POST['user_lname'], "text", "y");
		$objValid->add_text_field("Email", $_POST['user_email'], "email", "y");

//-----------------------------------------------------------------------------------//

		if (!$objValid->validation()) 

			$strError = $objValid->create_msg();
			if($_POST['user_password'] == "" && $_POST['user_cpassword'] == "")
			{
			$spram[5]		 =  $_POST['user_hpassword'];
			}elseif(isset($_POST['user_password']) and  $_POST['user_password'] != '' && $_POST['user_password'] != $_POST['user_cpassword'])
			$strError	.=	"The field Password and Confirm Password are not same.";
			
		if(empty($strError))
		{

			$objAdmin->UpdateAdmin($spram);
			$strError	= "Admin has been Updated successfully";

	   }

	   }
			$spram[0]	    =	$_GET['nid'];
			$spram[1]		 =  "";
			$spram[2]		 =  "";
			$spram[3]		 =  "";
			$spram[4]		 =  "";
			$spram[5]		 =  "";
			
			$resultSet		 =	$objAdmin->getAdminEdit($spram[0]);
			
			while($row1		 =	mysql_fetch_array($resultSet))
			{
		 	$spram[1]		 =  $row1['admin_fname'];
		 	$spram[2]		 =  $row1['admin_lname'];
			$spram[3]		 =  $row1['admin_email'];
			$spram[4]		 =  $row1['admin_password'];
			$spram[5]		 =  $row1['admin_status'];
			$spram[6]		 =  $row1['admin_name'];

			}

//--------------------------------------------------------------------------------------//

/*Html View Area*/

include "html/editadmin.html";

//--------------------------------------------------------------------------------------//

?>