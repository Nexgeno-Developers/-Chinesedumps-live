<?php

include ("../includes/config/classDbConnection.php");
$objDBcon   = 	new classDbConnection; // VALIDATION CLASS OBJECT

//-----------------------------Base Path-----------------------------------------------
error_reporting(0);
$fieldStatus="";
$getID    		=  $_GET['id'];
$tblName  		=  $_GET['tb'];
$tblField 		=  $_GET['fld'];
$fieldStatus    =  $_GET['flds'];

$status="";
 
 	$sql_cate    = "select * from ".$tblName."  where ".$tblField." = '".$getID."'";
 	$result_cate = mysql_query($sql_cate);
	$row_cate    = mysql_fetch_array($result_cate);
	
	if($row_cate[$fieldStatus]==1){
		$sql = "update ".$tblName." set ".$fieldStatus." = 0 where ".$tblField." ='".$getID."'";
		mysql_query($sql);
			
		$status= "<a href='javascript:showHome(".$row_cate[$tblField].", \"$tblName\",\"$tblField\",\"$fieldStatus\")' style=\"color:#FF0000\">No</a>";
	}else{
		$sql = "update ".$tblName." set ".$fieldStatus." =1 where ".$tblField." ='".$getID."'";
		mysql_query($sql);
		$status= "<a href='javascript:showHome(".$row_cate[$tblField].", \"$tblName\",\"$tblField\",\"$fieldStatus\")'>Yes</a>";
	}
	
	echo $status;
	//echo $getID." ".$tblName." ".$tblField." ".$fieldStatus;
	
	
?>