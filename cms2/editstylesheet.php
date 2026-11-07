<?php
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

	$filename = "../style_sheet.css";
	$contents = is_file($filename) ? file_get_contents($filename) : '';
	
	$str	='';
	if(isset($_POST['csssubmit']) && $_POST['csssubmit']!=''){
	$contents = $_POST['css'];
	$fp = fopen($filename, 'w');
	fwrite($fp, $contents);
	fclose($fp);
	$str	=	'Stylesheet has been updated.';
	syslog_write('Content', 'STYLESHEET_UPDATED', syslog_who().' edited and saved the website style sheet (style_sheet.css, now '.strlen($contents).' characters).', array('entity_type' => 'file', 'entity_label' => 'style_sheet.css'));
	}
	
	include ("html/editstylesheet.html");
?>