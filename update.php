<?php

ob_start();
session_start();
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");

include("includes/shoppingcart.php");
include("functions.php");
//error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors",1);
$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);
$getPage	=	$objMain->getContent(13);
$dayedate	=	date('Y-m-d');


$documentroot = dirname(__FILE__)."/";

/*$new_vq = mysql_query("select * from vendor1");

for($v=0; $v < mysql_num_rows($new_vq); $v++){
	$fetchV = mysql_fetch_array($new_vq);
	$insertVendorQ = "insert into tbl_vendors (ven_id, ven_name, ven_url, ven_status) values ('".$fetchV['id']."','".$fetchV['name']."','".$fetchV['url']."','1')";
	$insertQuery = mysql_query($insertVendorQ);
	echo $insertQuery."<br>";
}*/

//////////////Cert//////////////////////////

/*$new_vq = mysql_query("select * from cert1");

for($v=0; $v < mysql_num_rows($new_vq); $v++){
	$fetchV = mysql_fetch_array($new_vq);
	$insertVendorQ = "insert into tbl_cert (cert_id, ven_id, cert_name, cert_url, cert_status) values ('".$fetchV['id']."','".$fetchV['vendor_id']."','".$fetchV['name']."','".$fetchV['url']."','1')";
	$insertQuery = mysql_query($insertVendorQ);
	echo $insertQuery."<br>";
}*/

////////////////Exam/////////////////
$new_vq = mysql_query("select * from exam1");

for($v=0; $v < mysql_num_rows($new_vq); $v++){
	$fetchV = mysql_fetch_array($new_vq);
	$insertVendorQ = "insert into tbl_exam (exam_id, ven_id, cert_id, exam_name, exam_url, version, exam_fullname, QA, exam_date, exam_upddate, exam_status) values ('".$fetchV['id']."','".$fetchV['vendor_id']."','".$fetchV['cert_id']."','".$fetchV['code']."','".$fetchV['url']."','".$fetchV['version']."','".$fetchV['name']."','".$fetchV['questions']."','2019-11-29','2019-11-29','1')";
	$insertQuery = mysql_query($insertVendorQ);
	echo $insertQuery."<br>";
}


?>