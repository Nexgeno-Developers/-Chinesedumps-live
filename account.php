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

$getPage	=	$objMain->getContent(4);
$getPage[0]	=	"My Account";
$getPage[1]	=	"My Account";
$getPage[2]	=	"My Account";
$getPage[4]	=	"My Account";
$getPage[5]	=	"My Account";


    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
	
	$copyright	=	$exec['copyright'];
	$firstlink	=	" ".$getPage[1];
	
		
		
include("html/account.html");
?>