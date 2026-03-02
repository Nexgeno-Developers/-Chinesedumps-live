<?php
error_reporting(0);
session_start();
//------------------------------------------------------------------------------------------------
include("../includes/config/classDbConnection.php");
include("../includes/common/classes/classcart.php");

//------------------------------------------------------------------------------------------------
$objDBcon   =   new classDbConnection;
$objCart	=	new classCart($objDBcon);

//-----------------------General Coding ---------------------------------------------------------

//$proCart	= 	$_SESSION['procartid']; 

$ordId	=	$_GET['pid'];
$sessId	=	$_GET['sesss'];
mysql_query("DELETE from temp_order where ID='".$ordId."'");

		if($sessId!='' && $sessId!='0'){
			$query	=	"select * from temp_order where UserID='".$sessId."' order by ID ASC";
			
		}else{
			$query	=	"select * from temp_order where CartID='".session_id()."' and UserID='0' order by ID ASC";
		}
$rs		=	mysql_query($query);
$num	=	mysql_num_rows($rs);

if($num>0)
{
$show_cat 			= 	$objCart->showd_cart_left($rs,$base_path,$sessId);
}
else
{
$show_cat 			= 	'<div style="padding:0px 0px 0px 0px;" align="center"><strong style="color:#1D6B7C;"> Cart is Empty.</strong></div>';
}
echo $show_cat;
?>