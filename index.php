<?php
require_once __DIR__ . '/includes/config/load_secrets.php';
//Report all errors
error_reporting(E_ALL);
ob_start();
session_start();
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classcart.php");
include("includes/shoppingcart.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objCart	=	new classCart($objDBcon);
$getPage	=	$objMain->getContent(1);
    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
 	$copyright	=	$exec['copyright'];
	$phonephone	=	$exec['phone'];
	
	
 	
include("html/index.html");
?>
