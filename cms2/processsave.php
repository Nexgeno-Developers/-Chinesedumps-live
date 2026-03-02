<?php

include ("../includes/config/classDbConnection.php");
include("../includes/common/classes/classUser.php");

$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
	$objUser	 = new classUser($objDBcon);	// VALIDATION CLASS classCategory 
//-----------------------------Base Path-----------------------------------------------
error_reporting(0);
$fieldStatus="";
$getID    		=  $_GET['id'];
$txtorder		=  $_GET['ord'];

$status="";
 
 	 if($txtorder!=''){
	 
	 $qury		= 	"select * from  temp_master where ID='".$_GET['id']."'";
	 $rs_qury	=	mysql_query($qury);
	 $row		=	mysql_fetch_array($rs_qury);
	 $qry		=	"select * from  tbl_user where user_id='".$row['Cust_ID']."'";
	 $rs_q		=	mysql_query($qry);
	 $row_q		=	mysql_fetch_array($rs_q);
	
	    $arrnews[1]		 =  $_GET['id'];
		$arrnews[2]		 =  $txtorder;
		$arrnews[3]		 =  $row_q['user_id'];
		$arrnews[4]		 =  $row['Cart_ID'];
		$arrnews[5]		 =  $row_q['user_email'];
		$arrnews[6]		 =  '';
		$arrnews[7]		 =  $row_q['user_password'];
		$arrnews[8]		 =	$row['OrderDate'];
		$arrnews[9]		 =	'';	
	 
	 $objUser->addMasteDetail($arrnews);
	 
	 echo "Successfully Procssed.";
	 }else{
	 	echo "Error! Please enter the process code.";
		
		}
	
	
?>