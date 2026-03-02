<?php
ob_start();
session_start();
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");

include("includes/shoppingcart.php");
include("functions.php");
error_reporting(E_ALL);
ini_set("display_errors",1);
$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);
$getPage	=	$objMain->getContent(13);
$dayedate	=	date('Y-m-d');

$quer	=	$objDBcon->Sql_Query_Exec('*','website','');
$exec	=	mysql_fetch_array($quer);

$copyright	=	$exec['copyright'];
$firstlink	=	" ".$getPage[1];

if(!isset($_SESSION['uid']) || $_SESSION['uid']==''){
header("location:login.html");
}
	// Add an Instance for Test Engine
	if($_GET['ord']!="")
	{
		$order_id = $_GET['ord'];
		$sql_order = "SELECT *, count(*) as cnt FROM tbl_package_instance where order_number='$order_id'";
//		echo $sql_order;
		$res = mysql_query($sql_order);
		$ordered_package = mysql_fetch_array($res);
		if($ordered_package['cnt']==1)
		{
			$engine_id = $ordered_package['engine_id'];
			
			$time = date("Y-m-d H:i:s");
			$serial_number = md5($order_id.$time);
			$expiry = $ordered_package['instance_expiry'];
			
			$insert="insert into tbl_package_instance (id, engine_id, order_number, serial_number, mboard_number, instance_expiry) Values('', '{$engine_id}', '{$order_id}', '$serial_number', '', '$expiry');";
			$query=mysql_query($insert);
			header("Location: my-testing-engine.php");
			exit();
		}
	}
	header("Location: my-testing-engine.php");
	exit();
?>