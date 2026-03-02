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
		('".$title."','".$code."','".$start_date."','".$end_date."','".$coupon_value."','".$coupon_type."','".$no_of_user."','".$status."','".$comments."')") or die(mysql_error());
		$query=mysql_query($sql);
		$strError	.= "Coupon has been added successfully";
	}
//------------------------------------------------------------------------------------//

include "html/addcoupon.html";

//------------------------------------------------------------------------------------//
?>