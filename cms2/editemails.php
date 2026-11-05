<?php
require_once dirname(dirname(__FILE__)) . '/includes/common/sanitize.php';
session_start();
error_reporting(0);
//root path
$LinkPath="../";
//title of the page
$strTitle	=	"Add Contents";
/*include files*/

include_once ("../includes/config/classDbConnection.php");
include ("../includes/common/inc/sessionheader.php");
$objDBcon    = new classDbConnection; 

include_once 'functions.php';
$ccAdminData = get_session_data();

$str="";

if (isset($_POST['Submit']) && isset($_POST['Submit'])=="Update..")
{
	$farr	=	array("'");
	$aarr	=	array("\'");
	$spram[1]	=	stripslashes($_POST['FCKeditor']);
	//$spram[3]	=	$_POST['FCKeditor'];

	$syslogBefore = syslog_snapshot('mailstable', 'mailid', $_POST['selecttitle']);
	$qu1="update mailstable set  mailcontent ='".db_esc($spram[1])."' where mailid='".db_esc($_POST['selecttitle'])."'";
	mysql_query($qu1);
	syslog_log_updated('Content', 'EMAIL_TEMPLATE_UPDATED', 'email template', $_POST['selecttitle'], syslog_pick($syslogBefore, 'mailtitle', 'ID '.$_POST['selecttitle']), $syslogBefore, syslog_snapshot('mailstable', 'mailid', $_POST['selecttitle']));
	$str	=	"Data have been updated successfully";

}
	if(isset($_POST['selecttitle'])){
	$q="select * from mailstable where mailid='".db_esc($_POST['selecttitle'])."'";
	}else{
	$q="select * from mailstable where mailid='1'";
	}
	$rs=mysql_query($q);
	$row=mysql_fetch_array($rs);
	
	$spram[1]	=	$row['mailcontent'];
		
	include ("html/editemails.html");
?>