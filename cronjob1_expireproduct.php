<?php
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
		$getuserdetail	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_user where user_id='".$rowsendmail['UserID']."'"));
		$spram[5]	=	$getuserdetail['user_email'];
		$spram[1]	=	$getuserdetail['user_fname'];
		$spram[2]	=	$getuserdetail['user_lname'];
		if($rowsendmail['VendorID']!=''){
		$getuserdetail	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_vendors where ven_id='".$rowsendmail['VendorID']."'"));
		$spram[4]	=	$getuserdetail['ven_name'].'&nbsp;Vendor';
		}
		if($rowsendmail['TrackID']!=''){
		$getuserdetail	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_cert where cert_id='".$rowsendmail['TrackID']."'"));
		$spram[4]	=	$getuserdetail['cert_name'].'&nbsp;Certification';
		}
		if($rowsendmail['ProductID']!='' && $rowsendmail['ProductID']!='0'){
		$getuserdetail	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_exam where exam_id='".$rowsendmail['ProductID']."'"));
		$spram[4]	=	$getuserdetail['exam_name'].'&nbsp;Exam';
		}
		
		$spram[6]	=	$after7days;
		expiremailbefore7days($spram,$from_email);
	}
	
/////////////////////////////////////////////////////////////////Mail before 7 days expire/////////////////////////////////////////////////////////

	$getdetailexp		=	"SELECT * from order_detail where ExpireDate='".$todaydate."'";
	$exegetdetailexp	=	mysql_query($getdetailexp);
	while($rowsendmailexp	=	mysql_fetch_array($exegetdetailexp)){
		$getuserdetailexp	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_user where user_id='".$rowsendmailexp['UserID']."'"));
		$spram1[5]	=	$getuserdetailexp['user_email'];
		$spram1[1]	=	$getuserdetailexp['user_fname'];
		$spram1[2]	=	$getuserdetailexp['user_lname'];
		if($rowsendmailexp['VendorID']!=''){
		$getuserdetailexp	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_vendors where ven_id='".$rowsendmailexp['VendorID']."'"));
		$spram1[4]	=	$getuserdetailexp['ven_name'].'&nbsp;Vendor';
		}
		if($rowsendmailexp['TrackID']!=''){
		$getuserdetail	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_cert where cert_id='".$rowsendmailexp['TrackID']."'"));
		$spram1[4]	=	$getuserdetailexp['cert_name'].'&nbsp;Certification';
		}
		if($rowsendmailexp['ProductID']!='' && $rowsendmail['ProductID']!='0'){
		$getuserdetailexp	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_exam where exam_id='".$rowsendmailexp['ProductID']."'"));
		$spram1[4]	=	$getuserdetailexp['exam_name'].'&nbsp;Exam';
		}
		
		$spram1[6]	=	$todaydate;
		expiremail($spram1,$from_email);
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>