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
	if($tblName == "tbl_vendors")
	{
			if($row_cate[$fieldStatus]==1)
			{
			$sql = "update ".$tblName." set ".$fieldStatus." = 0 where ".$tblField." ='".$getID."'";
			mysql_query($sql);
			$sq2 = "update tbl_cert set cert_status = 0 where ".$tblField." ='".$getID."'";
			mysql_query($sq2);
			$sq3 = "update tbl_exam set exam_status = 0 where ".$tblField." ='".$getID."'";
			mysql_query($sq3);
			
			$status= "<a href='javascript:showHint(".$row_cate[$tblField].", \"$tblName\",\"$tblField\",\"$fieldStatus\")' style=\"color:#FF0000\">Disable</a>";
			}
			else
			{
			$sql = "update ".$tblName." set ".$fieldStatus." =1 where ".$tblField." ='".$getID."'";
			mysql_query($sql);
			$sq2 = "update tbl_cert set cert_status =1 where ".$tblField." ='".$getID."'";
			mysql_query($sq2);
			$sq3 = "update tbl_exam set exam_status =1 where ".$tblField." ='".$getID."'";
			mysql_query($sq3);
			$status= "<a href='javascript:showHint(".$row_cate[$tblField].", \"$tblName\",\"$tblField\",\"$fieldStatus\")'>Enable</a>";
			}
			
	}
	if($tblName == "tbl_cert")
	{
			if($row_cate[$fieldStatus]==1)
			{
			$sql = "update ".$tblName." set ".$fieldStatus." = 0 where ".$tblField." ='".$getID."'";
			mysql_query($sql);
			$sq3 = "update tbl_exam set exam_status = 0 where ".$tblField." =',".$getID.",'";
			mysql_query($sq3);
			
			$status= "<a href='javascript:showHint(".$row_cate[$tblField].", \"$tblName\",\"$tblField\",\"$fieldStatus\")' style=\"color:#FF0000\">Disable</a>";
			}
			else
			{
			$sql = "update ".$tblName." set ".$fieldStatus." =1 where ".$tblField." ='".$getID."'";
			mysql_query($sql);
			$sq3 = "update tbl_exam set exam_status = 1 where ".$tblField." =',".$getID.",'";
			mysql_query($sq3);
			$status= "<a href='javascript:showHint(".$row_cate[$tblField].", \"$tblName\",\"$tblField\",\"$fieldStatus\")'>Enable</a>";
			}
			
	}
	if($tblName == "tbl_exam")
	{
			if($row_cate[$fieldStatus]==1)
			{
			$sql = "update ".$tblName." set ".$fieldStatus." = 0 where ".$tblField." ='".$getID."'";
			mysql_query($sql);
			
			$status= "<a href='javascript:showHint(".$row_cate[$tblField].", \"$tblName\",\"$tblField\",\"$fieldStatus\")' style=\"color:#FF0000\">Disable</a>";
			}
			else
			{
			$sql = "update ".$tblName." set ".$fieldStatus." =1 where ".$tblField." ='".$getID."'";
			mysql_query($sql);
			$status= "<a href='javascript:showHint(".$row_cate[$tblField].", \"$tblName\",\"$tblField\",\"$fieldStatus\")'>Enable</a>";
			}
			
	}
	
	
	
	echo $status;
	//echo $getID." ".$tblName." ".$tblField." ".$fieldStatus;
	
	
?>