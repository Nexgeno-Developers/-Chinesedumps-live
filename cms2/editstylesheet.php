<?php
session_start();
error_reporting(0);
//root path
$LinkPath="../";
//title of the page
$strTitle	=	"Add Contents";
/*include files*/

include ("../includes/config/classDbConnection.php");
include ("../includes/common/inc/sessionheader.php");
$objDBcon    = new classDbConnection; 

include_once 'functions.php';
$ccAdminData = get_session_data();

	$filename = "../style_sheet.css";
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	
	$str	='';
	if(isset($_POST['csssubmit']) && $_POST['csssubmit']!=''){
	$contents = $_POST['css'];
	$fp = fopen($filename, 'w');
	fwrite($fp, $contents);
	fclose($fp);
	$str	=	'Stylesheet has been updated.';
	}
	
	include ("html/editstylesheet.html");
?>