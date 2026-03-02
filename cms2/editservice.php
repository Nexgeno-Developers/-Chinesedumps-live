<?php
session_start();
error_reporting(0);
//root path
$LinkPath="../";
//title of the page
$strTitle	=	"Add Sub Page";
/*include files*/

include ("../includes/config/classDbConnection.php");
include ("../includes/common/inc/sessionheader.php");
include("../includes/common/classes/validation_class.php");

$objDBcon    = new classDbConnection;
$objValid 	 = new Validate_fields; 
include_once 'functions.php';
$ccAdminData = get_session_data();

$str="";
$strError	=	"";
$farr	=	array("\"");
$aarr	=	array("&quot;");
$spram[1]	=	"";
$spram[2]	=	"";
$spram[3]	=	"";
$spram[4]	=	"";

if (isset($_POST['Submit']) && isset($_POST['Submit'])=="Update..")
{
	
	$spram[1]	=	$_POST['page_title'];
	$spram[2]	=	$_POST['content_title'];
	$spram[3]	=	$_POST['FCKeditor'];
	//$spram[4]	=	$_POST['status'];
	
	$objValid->add_text_field("Page Contents", $_POST['FCKeditor'], "text", "y");
	$objValid->add_text_field("Content Title", $_POST['content_title'], "text", "y");
	$objValid->add_text_field("Page Title", $_POST['page_title'], "text", "y");		
		
		if (!$objValid->validation()) 
			$strError = $objValid->create_msg();
			
		if(empty($strError))
			{
			
	$qu1	=	"UPDATE content_pages set page_title='".$spram[1]."',content_title='".$spram[2]."',page_contents='".$spram[3]."' where page_id='".$_GET['ser_id']."'";
	mysql_query($qu1);
	
	
	$strError	=	"Service has been updated successfully";
			}
}else{

	$quer	=	$objDBcon->Sql_Query_Exec('*','content_pages',"page_id='".$_GET['ser_id']."'");
	$exec	=	mysql_fetch_array($quer);
	$spram[1]	=	$exec['page_title'];
	$spram[2]	=	$exec['content_title'];
	$spram[3]	=	$exec['page_contents'];

}
	
	include ("html/editservice.html");

?>