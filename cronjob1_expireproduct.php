<?php
require_once dirname(__FILE__) . '/includes/common/system_log.php';
require_once dirname(__FILE__) . '/includes/common/sanitize.php';
ob_start();
session_start();
include("includes/config/classDbConnection.php");
include("includes/common/classes/classmain.php");
include("includes/common/classes/classcart.php");

include("functions.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objCart	=	new classCart($objDBcon);

$todaydate	=	date("Y-m-d");
$after7days	=	date('Y-m-d',strtotime('+7 days' ) );

    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
	$from_email	=	$exec['from_email'];
/////////////////////////////////////////////////////////////////Mail before 7 days expire/////////////////////////////////////////////////////////

	$getdetail		=	"SELECT * from order_detail where ExpireDate='".$after7days."'";
	$exegetdetail	=	mysql_query($getdetail);
	while($rowsendmail	=	mysql_fetch_array($exegetdetail)){
		$getuserdetail	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_user where user_id='".db_esc($rowsendmail['UserID'])."'"));
		$spram[5]	=	$getuserdetail['user_email'];
		$spram[1]	=	$getuserdetail['user_fname'];
		$spram[2]	=	$getuserdetail['user_lname'];
		if($rowsendmail['VendorID']!=''){
		$getuserdetail	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_vendors where ven_id='".db_esc($rowsendmail['VendorID'])."'"));
		$spram[4]	=	$getuserdetail['ven_name'].'&nbsp;Vendor';
		}
		if($rowsendmail['TrackID']!=''){
		$getuserdetail	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_cert where cert_id='".db_esc($rowsendmail['TrackID'])."'"));
		$spram[4]	=	$getuserdetail['cert_name'].'&nbsp;Certification';
		}
		if($rowsendmail['ProductID']!='' && $rowsendmail['ProductID']!='0'){
		$getuserdetail	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_exam where exam_id='".db_esc($rowsendmail['ProductID'])."'"));
		$spram[4]	=	$getuserdetail['exam_name'].'&nbsp;Exam';
		}
		
		$spram[6]	=	$after7days;
		expiremailbefore7days($spram,$from_email);
		syslog_write('Notifications', 'EXPIRY_REMINDER_SENT', 'The system emailed '.$spram[5].' a reminder that '.strip_tags(str_replace('&nbsp;', ' ', $spram[4])).' expires on '.$after7days.' (7 days left).', array('entity_type' => 'order item', 'entity_id' => $rowsendmail['ID'], 'entity_label' => strip_tags(str_replace('&nbsp;', ' ', $spram[4])), 'actor' => array('type' => 'system', 'name' => 'Daily expiry reminder job')));
	}
	
/////////////////////////////////////////////////////////////////Mail before 7 days expire/////////////////////////////////////////////////////////

	$getdetailexp		=	"SELECT * from order_detail where ExpireDate='".$todaydate."'";
	$exegetdetailexp	=	mysql_query($getdetailexp);
	while($rowsendmailexp	=	mysql_fetch_array($exegetdetailexp)){
		$getuserdetailexp	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_user where user_id='".db_esc($rowsendmailexp['UserID'])."'"));
		$spram1[5]	=	$getuserdetailexp['user_email'];
		$spram1[1]	=	$getuserdetailexp['user_fname'];
		$spram1[2]	=	$getuserdetailexp['user_lname'];
		if($rowsendmailexp['VendorID']!=''){
		$getuserdetailexp	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_vendors where ven_id='".db_esc($rowsendmailexp['VendorID'])."'"));
		$spram1[4]	=	$getuserdetailexp['ven_name'].'&nbsp;Vendor';
		}
		if($rowsendmailexp['TrackID']!=''){
		$getuserdetail	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_cert where cert_id='".db_esc($rowsendmailexp['TrackID'])."'"));
		$spram1[4]	=	$getuserdetailexp['cert_name'].'&nbsp;Certification';
		}
		if($rowsendmailexp['ProductID']!='' && $rowsendmail['ProductID']!='0'){
		$getuserdetailexp	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_exam where exam_id='".db_esc($rowsendmailexp['ProductID'])."'"));
		$spram1[4]	=	$getuserdetailexp['exam_name'].'&nbsp;Exam';
		}
		
		$spram1[6]	=	$todaydate;
		expiremail($spram1,$from_email);
		syslog_write('Notifications', 'EXPIRED_NOTICE_SENT', 'The system emailed '.$spram1[5].' that '.strip_tags(str_replace('&nbsp;', ' ', $spram1[4])).' expired today.', array('entity_type' => 'order item', 'entity_id' => $rowsendmailexp['ID'], 'entity_label' => strip_tags(str_replace('&nbsp;', ' ', $spram1[4])), 'actor' => array('type' => 'system', 'name' => 'Daily expiry reminder job')));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>