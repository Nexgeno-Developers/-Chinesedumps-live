<?php
require_once dirname(dirname(__FILE__)) . '/includes/common/system_log.php';
require_once dirname(dirname(__FILE__)) . '/includes/common/sanitize.php';
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
$syslogRow = syslog_snapshot('temp_order', 'ID', san_int($ordId));
mysql_query("DELETE from temp_order where ID='".san_int($ordId)."'");
if ($syslogRow) {
	syslog_write('Cart', 'CART_ITEM_REMOVED', syslog_who().' removed '.syslog_cart_item_label($syslogRow).' from the shopping cart.', array('entity_type' => 'cart item', 'entity_id' => san_int($ordId), 'entity_label' => syslog_cart_item_label($syslogRow)));
}

if($sessId!='' && $sessId!='0'){
			$query	=	"select * from temp_order where UserID='".san_int($sessId)."' order by ID ASC";
		}else{
			$query	=	"select * from temp_order where CartID='".db_esc(session_id())."' and UserID='0' order by ID ASC";
		}
$rs		=	mysql_query($query);
$num	=	mysql_num_rows($rs);


if($num>0)
{
$show_cat 			= 	$objCart->showd_cart($rs,$base_path,$sessId);
}
else
{
$show_cat 			= 	$objCart->cart_manage($sessId);
}
echo $show_cat;


?>