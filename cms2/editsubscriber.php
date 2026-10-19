<?PHP 
require_once dirname(dirname(__FILE__)) . '/includes/common/sanitize.php';
ini_set("display_errors","0");
ob_start();
session_start();

	include_once ("../includes/config/classDbConnection.php");
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
	
	if(isset($_POST['Submit']) && $_POST['Submit']  == "Update")

	{
		
		$spram[1]		 =  $_POST['maintitle'];
		//$spram[2]		 =  $_POST['maintitle'];
		//$objValid->add_text_field("Featured Project URL", $spram[1],"text", "y");
		$objValid->add_text_field("Newsletter Subscriber Email", $spram[1],"email", "y");
		
		if (!$objValid->validation())
		$strError = $objValid->create_msg();
		
		if(empty($strError))
		{
		$insert	=	mysql_query("Update tbl_maillist set mail_email='".db_esc($spram[1])."' where mail_id='".(isset($_GET['ser_id']) ? san_int($_GET['ser_id']) : 0)."'");
		$strError	.= "Newsletter Subscriber has been updated successfully";
		}
		}else{
		$quer	=	$objDBcon->Sql_Query_Exec('*','tbl_maillist',"mail_id='".(isset($_GET['ser_id']) ? san_int($_GET['ser_id']) : 0)."'");
		$exec	=	mysql_fetch_array($quer);
		$spram[1]	=	$exec['mail_email'];
		
		
		}
//------------------------------------------------------------------------------------//

include "html/editsubscriber.html";

//------------------------------------------------------------------------------------//
?>