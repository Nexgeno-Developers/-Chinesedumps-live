<?PHP 
require_once dirname(dirname(__FILE__)) . '/includes/common/sanitize.php';
require_once dirname(__FILE__) . '/../includes/common/app_env.php'; app_apply_error_settings();
ob_start();
session_start();

	include_once ("../includes/config/classDbConnection.php");
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
	$bottomid	=	isset($_GET['bottom_id']) ? san_int($_GET['bottom_id']) : 0;
	if(isset($_POST['Submit']) && $_POST['Submit']  == "Update")

	{
		$spram[1]		 =  $_POST['title'];
		$spram[2]		 =  $_POST['status'];
		
		
		$objValid->add_text_field("Manufacturer Name", $spram[1],"text", "y");
		
		if (!$objValid->validation())
		$strError = $objValid->create_msg();
		
		if(empty($strError))
		{
			
			$upd	=	mysql_query("UPDATE tbl_manufec set man_name='".db_esc($spram[1])."',man_status='".db_esc($spram[2])."' where man_id='".$bottomid."'");
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