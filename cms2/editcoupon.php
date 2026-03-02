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

	$strError	=	"";
	$id = @$_GET['nid'];
	if(isset($_POST['Submit']) && $_POST['Submit']  == "Submit")
	{
		$id=$_POST['id'];
		$title=$_POST['c_title'];
		$code=$_POST['c_code'];
		$start_date=$_POST['Start_Date'];
		$end_date=$_POST['End_Date'];
		$coupon_value=$_POST['Coupon_Value'];
		$coupon_type=$_POST['coupon_type'];
		$no_of_user=$_POST['Number_of_Uses'];
		$status=$_POST['status'];
		$comments=$_POST['comments'];
		$sql=mysql_query("UPDATE coupon SET coupon_title='".$title."',coupon_code='".$code."',start_date='".$start_date."',end_date='".$end_date."', coupon_value='".$coupon_value."',coupon_type='".$coupon_type."',number_of_uses='".$no_of_user."',status='".$status."',comments='".$comments."' WHERE id='".$id."'") or die(mysql_error());
		$strError	.= "Coupon has been updated successfully";
	}
//------------------------------------------------------------------------------------//
	$sql_1=("SELECT * FROM coupon WHERE id='".$id."'") or die(mysql_error()); 
	$result_1=mysql_query($sql_1);
	if(mysql_num_rows($result_1)>0)
	{
		$row1=mysql_fetch_array($result_1);
	}
include "html/editcoupon.html";

//------------------------------------------------------------------------------------//
?>