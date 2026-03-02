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

$str="";

if (isset($_POST['Submit']) && isset($_POST['Submit'])=="Update..")
{
	$farr	=	array("'");
	$aarr	=	array("\'");
	$spram[3]	=	stripslashes($_POST['FCKeditor']);
	//$spram[3]	=	$_POST['FCKeditor'];

	$qu1="update website set  bottom_contact ='".str_replace("'","\'",$spram[3])."'";
	mysql_query($qu1);
	$str	=	"Data have been updated successfully";

}

	$q="select * from website";
	$rs=mysql_query($q);
	$row=mysql_fetch_array($rs);
	
	
	$spram[3]	=	$row['bottom_contact'];
	
		
	include ("html/bootomcontact.html");

?>