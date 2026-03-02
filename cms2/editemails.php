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
	$spram[1]	=	stripslashes($_POST['FCKeditor']);
	//$spram[3]	=	$_POST['FCKeditor'];

	$qu1="update mailstable set  mailcontent ='".str_replace("'","\'",$spram[1])."' where mailid='".$_POST['selecttitle']."'";
	mysql_query($qu1);
	$str	=	"Data have been updated successfully";

}
	if(isset($_POST['selecttitle'])){
	$q="select * from mailstable where mailid='".$_POST['selecttitle']."'";
	}else{
	$q="select * from mailstable where mailid='1'";
	}
	$rs=mysql_query($q);
	$row=mysql_fetch_array($rs);
	
	$spram[1]	=	$row['mailcontent'];
		
	include ("html/editemails.html");
?>