<?PHP 
require_once dirname(dirname(__FILE__)) . '/includes/common/sanitize.php';
require_once dirname(__FILE__) . '/../includes/common/app_env.php'; app_apply_error_settings();
ob_start();
session_start();

	include_once ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include ("../includes/common/classes/classmain.php");
	include ("../includes/common/classes/classUser.php");
	include ("../includes/common/classes/classProduct.php");
	

include_once 'functions.php';
$ccAdminData = get_session_data();


//--------------------Creat objects---------------------------

			$objDBcon    =  new classDbConnection; 			// VALIDATION CLASS OBJECT
			$objValid 	 =  new Validate_fields;
			$objMain	 =	new classMain($objDBcon);	// VALIDATION CLASS classCategory
			$objUser	 =	new classUser($objDBcon);
			$objProduct  =	new classProduct($objDBcon);
	$strError	=	"";
		$spram[1]		 =  "";
		$spram[2]		 =  "";
		$spram[3]		 =  "";
		$spram[0]     	 =	isset($_GET['order_id']) ? san_int($_GET['order_id']) : 0;
	
	if(isset($_POST['Submit']) && $_POST['Submit']  == "Submit")

	{
		$spram[0]		 =  isset($_GET['bottom_id']) ? san_int($_GET['bottom_id']) : 0;
		$spram[1]		 =  $_POST['hiddenproname'];
		$spram[2]		 =  $_POST['status'];
		$spram[3]		 =  date("Y-m-d");
		$spram[4]		 =  date("H-i-s");
		$spram[5]		 =  $_POST['FCKeditor'];
		

		//$objValid->add_text_field("Review Status", $spram[2],"text", "y");
		//$objValid->add_text_field("Product Name", $spram[1],"text", "y");
		
		
		//list($date,$month,$year)	=	explode("-",$arryPr[3]);
		//$arryPr[3]	=	$year."-".$month."-".$date;
		
		if (!$objValid->validation())
		$strError = $objValid->create_msg();
		
		if(empty($strError))
		{
			
			//$brandadd	=	$objProduct->updateReviews($spram);
		
		$strError	.= "Review has been added successfully";
		}
		}else{
		$get	=	mysql_fetch_array(mysql_query("Select * from tbl_reviews where rev_id='".(isset($_GET['bottom_id']) ? san_int($_GET['bottom_id']) : 0)."'"));
		$spram[1]	=	$get['rev_proid'];
		$spram[2]	=	$get['rev_status'];
		//$spram[3]	=	$get['album_id'];
		//$spram[4]	=	$get['pro_desc'];
		$spram[5]	=	$get['rev_desc'];
		}
		
		$getorder	=	mysql_fetch_array($objDBcon->Sql_Query_Exec("*","tbl_order","ord_id='".(isset($_GET['order_id']) ? san_int($_GET['order_id']) : 0)."'"));
		$spram[1]	=	$getorder['order_status'];
		$getuser	=	mysql_fetch_array($objDBcon->Sql_Query_Exec("*","tbl_user","user_id='".san_int($getorder['user_id'])."'"));
		
		$getpro	=	$objDBcon->Sql_Query_Exec("*","tbl_tmporder","user_id='".san_int($getorder['user_id'])."' and processord='".(isset($_GET['order_id']) ? san_int($_GET['order_id']) : 0)."'");
		if(isset($_POST['submit']) && $_POST['submit'] !=''){
		$spram[1]	=	$_POST['ordstatus'];
		mysql_query("UPDATE tbl_order set order_status='".db_esc($spram[1])."' where ord_id='".(isset($_GET['order_id']) ? san_int($_GET['order_id']) : 0)."'");
		$strError	=	"Order is successfully Updated.";
		}
//------------------------------------------------------------------------------------//

include "html/editorder.html";

//------------------------------------------------------------------------------------//
?>