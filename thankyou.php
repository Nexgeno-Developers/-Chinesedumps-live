<?php
require_once dirname(__FILE__) . '/includes/common/blocked_urls.php';
if (blocked_url_demo_is_blocked(isset($_GET['vname']) ? $_GET['vname'] : '', isset($_GET['ecode']) ? $_GET['ecode'] : '')) { blocked_url_send_404(); include dirname(__FILE__) . '/notfound.php'; exit; }
ob_start();
session_start();
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");

include("includes/shoppingcart.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);

$getPage	=	$objMain->getContent(9);

    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
	
	$copyright	=	$exec['copyright'];
		
		$firstlink	=	" ".$getPage[1];
include("html/thankyou.html");
?>