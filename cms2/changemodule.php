<?php

include ("../includes/config/classDbConnection.php");
$objDBcon   = 	new classDbConnection; // VALIDATION CLASS OBJECT

//-----------------------------Base Path-----------------------------------------------
error_reporting(0);
$fieldStatus="";
$getID    		=  $_GET['id'];

$status="";
 
 	 $sql_cate    = "select * from menumodule  where menu_id = '".$getID."'";
 	$result_cate = mysql_query($sql_cate);
	$row_cate    = mysql_fetch_array($result_cate);
	
	if($row_cate['status']==1)
	{
	
	$sql = "update menumodule set status='0' where  menu_id='".$getID."'";
	mysql_query($sql);
	
	$status= "<a href='javascript:showHintmodule(".$getID.")' style=\"color:#FF0000\">Disable</a>";
	}
	else
	{
	$sql = "update menumodule set status='1' where menu_id='".$getID."'";
	mysql_query($sql);
	$status= "<a href='javascript:showHintmodule(".$getID.")'>Enable</a>";
	}
	
	echo $status;
?>