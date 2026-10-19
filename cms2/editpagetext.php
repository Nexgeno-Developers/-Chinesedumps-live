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
//$qu1="insert into content_pages (page_id) values ('31')";
//mysql_query($qu1);
//$qu1="insert into content_pages (page_id) values ('32')";
//mysql_query($qu1);
if (isset($_POST['Submit']) && isset($_POST['Submit'])=="Update..")
{
	$spram[1]	=	stripslashes($_POST['page_title']);
	$spram[2]	=	stripslashes($_POST['content_title']);
	
	$farr	=	array("'");
	$aarr	=	array("\'");
	$spram[4]	=	stripslashes($_POST['meta_key']);
	$spram[5]	=	stripslashes($_POST['meta_descx']);
	$spram[3]	=	stripslashes($_POST['FCKeditor']);
	//$spram[3]	=	$_POST['FCKeditor'];

	$qu1="update content_pages set  page_title='".db_esc($spram[1])."',content_title='".db_esc($spram[2])."',page_contents ='".db_esc($spram[3])."',meta_keywords='".db_esc($spram[4])."',meta_descx='".db_esc($spram[5])."' where page_id='".(isset($_GET['pid']) ? san_int($_GET['pid']) : 0)."'";
	mysql_query($qu1);
	$str	=	"Data have been updated successfully";

}

	$q="select * from content_pages where page_id='".(isset($_GET['pid']) ? san_int($_GET['pid']) : 0)."'";
	$rs=mysql_query($q);
	$row=mysql_fetch_array($rs);
	
	$spram[1]	=	$row['page_title'];
	$spram[2]	=	$row['content_title'];
	$spram[3]	=	$row['page_contents'];
	$spram[4]	=	$row['meta_keywords'];
	$spram[5]	=	$row['meta_descx'];
		
	include ("html/pagemgmt.html");

?>