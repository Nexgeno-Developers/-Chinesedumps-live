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
	$spram[1]	=	$_POST['page_title'];

	$qu1="update website set  menucolum='".db_esc($spram[1])."'";
	mysql_query($qu1);
	$str	=	"Data have been updated successfully";

}
		
	$q="select * from website";
	$rs=mysql_query($q);
	$row=mysql_fetch_array($rs);
	
	$spram[1]	=	$row['menucolum'];
	
	include ("html/menusetting.html");
?>