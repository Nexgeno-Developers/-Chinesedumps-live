<?php
ob_start();
session_start();
if(isset($_SESSION['uid'])){
}else{
header("location:login.html");
}
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");

include("includes/shoppingcart.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);

$getPage	=	$objMain->getContent(16);

    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
	
	$copyright	=	$exec['copyright'];
	$firstlink	=	" ".$getPage[1];
	$tt	='1';
	$lastorderid	=	$_SESSION['reorderlastid'];
	
		if($tt=='1' && isset($_SESSION['reorderlastid']))
			{
			
		$q="select * from  tbl_reorder where id_re='".$lastorderid."'";
 		$rs1=mysql_query($q);
		$row1=mysql_fetch_array($rs1);
		
		$qy_cart		=	"UPDATE order_detail set ExpireDate='".$row1['enddate']."' where ID='".$row1['id_detailorder']."'";
  		$r_cart 		=	mysql_query($qy_cart);
			
		$qy_tblor		=	"UPDATE tbl_reorder set order_id ='".$tt."',status ='1'  where id_re='".$lastorderid."'";
		mysql_query($qy_tblor);		
		}
	
	
	unset($_SESSION['reorderlastid']);
	
include("html/thanks_reorder.html");
?>