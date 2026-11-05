<?php
require_once dirname(__FILE__) . '/includes/common/system_log.php'; $this_page = "stable-unstable" ?>
<?php
require_once dirname(__FILE__) . '/includes/common/sanitize.php';
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
		       $sql = "UPDATE tbl_course_subscription SET status_sbc = 1 WHERE email = '".db_esc($_GET['id'])."' AND course='".db_esc($_GET['course_name'])."'"; 
            $retval = mysql_query($sql);
            syslog_write('Subscriptions', 'COURSE_ALERT_UNSUBSCRIBED', syslog_who().' unsubscribed the email '.$_GET['id'].' from stable / unstable alerts for the course "'.$_GET['course_name'].'".', array('entity_type' => 'course', 'entity_label' => $_GET['course_name']));  
 
	} 
include("html/stable-unstable-unsubscribe.html");
?>
