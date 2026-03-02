<?PHP 
ini_set("display_errors","0");
ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classmain.php");
	
	include_once('easyphpthumbnail.class.php');
	$thumb = new easyphpthumbnail;

include_once 'functions.php';
$ccAdminData = get_session_data();


//--------------------Creat objects---------------------------

			$objDBcon   =  new classDbConnection; 			// VALIDATION CLASS OBJECT
			$objValid 	=  new Validate_fields;
			$objMain	=  new classMain($objDBcon);	// VALIDATION CLASS classCategory

	$strError	=	"";
	//$bottomid	=	$_GET['bottom_id'];
	if(isset($_POST['Submit']) && $_POST['Submit']  == "Update")

	{
		$spram[1]		 =  $_POST['front1'];
		$spram[2]		 =  $_POST['front2'];
		$spram[3]		 =  $_POST['front3'];
		
		if (!$objValid->validation())
		$strError = $objValid->create_msg();
		
		if(empty($strError))
		{
			$upd	=	"UPDATE tbl_updates set in_contnt='".$spram[1]."' where id_con='1'";
			$objDBcon->Dml_Query_Parser($upd);
			mysql_query("UPDATE tbl_updates set in_contnt='".$spram[2]."' where id_con='2'");
			mysql_query("UPDATE tbl_updates set in_contnt='".$spram[3]."' where id_con='3'");
		
		}
		//////////////////////////////////
		}else{
		$get1	=	mysql_fetch_array(mysql_query("Select * from tbl_updates where id_con='1'"));
		$spram[1]	=	$get1['in_contnt'];
		
		$get2	=	mysql_fetch_array(mysql_query("Select * from tbl_updates where id_con='2'"));
		$spram[2]	=	$get2['in_contnt'];
		
		$get3	=	mysql_fetch_array(mysql_query("Select * from tbl_updates where id_con='3'"));
		$spram[3]	=	$get3['in_contnt'];
		
		
		}
		
//------------------------------------------------------------------------------------//

include "html/mainimg.html";

//------------------------------------------------------------------------------------//
?>