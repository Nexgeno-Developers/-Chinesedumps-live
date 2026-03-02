<?PHP 
ini_set("display_errors","0");
ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classmain.php");
	
	

include_once 'functions.php';
$ccAdminData = get_session_data();


//--------------------Creat objects---------------------------

			$objDBcon   =  new classDbConnection; 			// VALIDATION CLASS OBJECT
			$objValid 	=  new Validate_fields;
			$objMain	=  new classMain($objDBcon);	// VALIDATION CLASS classCategory

	$strError	=	"";
	$bottomid	=	$_GET['bottom_id'];
	if(isset($_POST['Submit']) && $_POST['Submit']  == "Update")

	{
		$spram[1]		 =  $_POST['title'];
		$spram[2]		 =  $_POST['status'];
		
		
		$objValid->add_text_field("Manufacturer Name", $spram[1],"text", "y");
		
		if (!$objValid->validation())
		$strError = $objValid->create_msg();
		
		if(empty($strError))
		{
			
			$upd	=	mysql_query("UPDATE tbl_manufec set man_name='".$spram[1]."',man_status='".$spram[2]."' where man_id='".$_GET['bottom_id']."'");
		$strError	.= "Manufacturer has been updated successfully.";
		
		}
		//////////////////////////////////
		}else{
		$get	=	mysql_fetch_array(mysql_query("Select * from  tbl_manufec where man_id='".$bottomid."'"));
		$spram[1]	=	$get['man_name'];
		$spram[2]	=	$get['man_status'];
		
		
		}
		
//------------------------------------------------------------------------------------//

include "html/editmanu.html";

//------------------------------------------------------------------------------------//
?>