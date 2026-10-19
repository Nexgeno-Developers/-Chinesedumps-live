<?PHP 
ob_start();
session_start();

	include_once ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classUser.php");
	include("../functions.php");

include_once 'functions.php';
require_once dirname(dirname(__FILE__)) . '/includes/common/sanitize.php';
$ccAdminData = get_session_data();


//--------------------Creat objects---------------------------

	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT

	$strError	=	"";
	
	if(isset($_POST['Submit']) && $_POST['Submit']  == "Submit")
	{
		$title=$_POST['c_title'];
		$code=$_POST['c_code'];
		$start_date=$_POST['Start_Date'];
		$end_date=$_POST['End_Date'];
		$coupon_value=$_POST['Coupon_Value'];
		$coupon_type=$_POST['coupon_type'];
		$no_of_user=$_POST['Number_of_Uses'];
		$status=$_POST['status'];
		$comments=$_POST['comments'];
		$sql=("INSERT INTO coupon(coupon_title,coupon_code,start_date,end_date,coupon_value,coupon_type,number_of_uses,status,comments) VALUES 
		('".db_esc($title)."','".db_esc($code)."','".db_esc($start_date)."','".db_esc($end_date)."','".db_esc($coupon_value)."','".db_esc($coupon_type)."','".db_esc($no_of_user)."','".san_int($status)."','".db_esc($comments)."')") or die(mysql_error());
		$query=mysql_query($sql);
		$strError	.= "<span style='color: green; font-weight: 600;'>Coupon has been added successfully</span>";
	}
//------------------------------------------------------------------------------------//

include "html/addcoupon.html";

//------------------------------------------------------------------------------------//
?>