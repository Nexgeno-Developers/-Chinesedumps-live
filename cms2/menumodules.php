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

	$que1	=	mysql_fetch_array(mysql_query("select * from menumodule where menu_id='1'"));
	if($que1['status']=='1'){ $show1	=	'Enable';}else{ $show1	=	'<font color="red">Disable</font>'; }
	
	$que2	=	mysql_fetch_array(mysql_query("select * from menumodule where menu_id='2'"));
	if($que2['status']=='1'){ $show2	=	'Enable';}else{ $show2	=	'<font color="red">Disable</font>'; }
	
	$que3	=	mysql_fetch_array(mysql_query("select * from menumodule where menu_id='3'"));
	if($que3['status']=='1'){ $show3	=	'Enable';}else{ $show3	=	'<font color="red">Disable</font>'; }
	
	$que4	=	mysql_fetch_array(mysql_query("select * from menumodule where menu_id='4'"));
	if($que4['status']=='1'){ $show4	=	'Enable';}else{ $show4	=	'<font color="red">Disable</font>'; }
	
	include ("html/menumodules.html");
?>