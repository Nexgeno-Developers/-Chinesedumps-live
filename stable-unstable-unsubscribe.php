<?php $this_page = "stable-unstable" ?>
<?php
ob_start();
session_start();
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");

include("includes/shoppingcart.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);

if(isset($_GET['id']) && isset($_GET['course_name']))
	{ 
		       $sql = "UPDATE tbl_course_subscription SET status_sbc = 1 WHERE email = '".$_GET['id']."' AND course='".$_GET['course_name']."'"; 
            $retval = mysql_query($sql);  
 
	} 
include("html/stable-unstable-unsubscribe.html");
?>
