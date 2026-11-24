<?php
ob_start();
if (!function_exists('session_status') || session_status() !== PHP_SESSION_ACTIVE) { session_start(); } /* may already be started when blocked_urls.php sends us here */
include_once("includes/config/classDbConnection.php");

include_once("includes/common/classes/classmain.php");
include_once("includes/common/classes/classProductMain.php");
include_once("includes/common/classes/classcart.php");

include_once("includes/shoppingcart.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);

$getPage	=	$objMain->getContent(14);

    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
	
	$copyright	=	$exec['copyright'];
		
		$firstlink	=	" ".$getPage[1];
include("html/notfound.html");
?>