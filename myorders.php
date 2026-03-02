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
$userId = $_SESSION['uid'];
$getPage[0]	=	"Orders";
$getPage[1]	=	"Orders";
$getPage[2]	=	"";
$getPage[4]	=	"Orders";
$getPage[5]	=	"Orders";

    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
	$userId = $_SESSION['uid'];
	$copyright	=	$exec['copyright'];
		
		$firstlink	=	" ".$getPage[1];
	
		$result			=	$objPro->getAllOrder($userId);
		if(mysql_num_rows($result)=='0'){
		$show_orders	=	"<div><strong style='color:#565655;' >NO Order</strong></div>";
		}else{
		$show_orders	=	$objPro->showOrders($result);
		}
	
			
include("html/myorders.html");
?>