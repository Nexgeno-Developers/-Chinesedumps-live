<?php
require_once dirname(dirname(__FILE__)) . '/includes/common/sanitize.php';
session_start();

include_once ("../includes/config/classDbConnection.php");
include ("../includes/common/inc/sessionheader.php");
include("../includes/common/classes/classUser.php");

$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
	$objUser	 = new classUser($objDBcon);	// VALIDATION CLASS classCategory 
//-----------------------------Base Path-----------------------------------------------
error_reporting(0);
$fieldStatus="";
$getID    		=  isset($_GET['id']) ? san_int($_GET['id']) : 0;
$txtorder		=  isset($_GET['ord']) ? $_GET['ord'] : '';

$status="";
 
 	 if($txtorder!=''){
	 
	 	$q="select * from  tbl_reorder where id_re='".$getID."'";
 		$rs1=mysql_query($q);
		$row1=mysql_fetch_array($rs1);
		
		$qy_cart		=	"UPDATE order_detail set ExpireDate='".db_esc($row1['enddate'])."' where ID='".san_int($row1['id_detailorder'])."'";
  		$r_cart 		=	mysql_query($qy_cart);
	 
		 $qury		= 	"UPDATE tbl_reorder set  order_id='".db_esc($txtorder)."',status ='1' where id_re='".$getID."'";
	 	$rs_qury	=	mysql_query($qury);
	 
	 echo "Successfully Procssed.";
	 }else{
	 	echo "Error! Please enter the process code.";
		
		}
	
	
?>
