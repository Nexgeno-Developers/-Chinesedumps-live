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

$getPage[0]	=	'Re-order';
$getPage[1]	=	'Re-order';
$getPage[2]	=	'';
$getPage[4]	=	'Re-order';
$getPage[5]	=	'Re-order';
    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
	$userId = $_SESSION['uid'];
	$copyright	=	$exec['copyright'];
		$show_cart	=	'';
	$firstlink	=	" ".$getPage[1];
		
	$roword	=	mysql_fetch_array(mysql_query("SELECT * FROM order_detail where ID='".$_GET['id']."' and UserID='".$_SESSION['uid']."'"));
	
	if($roword['VendorID']!=''){
	$getreitems	=	mysql_fetch_array(mysql_query("SELECT * from tbl_vendors where ven_id='".$roword['VendorID']."'"));
	$exmname	=	$getreitems['ven_name'];
	$numberidid	=	$getreitems['ven_id'];
		if($roword['submonth']=='3'){ $priceprice	=	$getreitems['ven_pri3']; }
		if($roword['submonth']=='6'){ $priceprice	=	$getreitems['ven_pri6']; }
		if($roword['submonth']=='12'){ $priceprice	=	$getreitems['ven_pri12']; }
	}
	if($roword['TrackID']!=''){
	$getreitems	=	mysql_fetch_array(mysql_query("SELECT * from tbl_cert where cert_id='".$roword['TrackID']."'"));
	$exmname	=	$getreitems['cert_name'];
	$numberidid	=	$getreitems['cert_id'];
		if($roword['submonth']=='3'){ $priceprice	=	$getreitems['cert_pri3']; }
		if($roword['submonth']=='6'){ $priceprice	=	$getreitems['cert_pri6']; }
		if($roword['submonth']=='12'){ $priceprice	=	$getreitems['cert_pri12']; }
	}
	if($roword['ProductID']!='' && $roword['ProductID']!='0'){
	$getreitems	=	mysql_fetch_array(mysql_query("SELECT * from tbl_exam where exam_id='".$roword['ProductID']."'"));
	$exmname	=	$getreitems['exam_name'];
	$numberidid	=	$getreitems['exam_id'];
		if($roword['submonth']=='3'){ $priceprice	=	$getreitems['exam_pri3']; }
		if($roword['submonth']=='6'){ $priceprice	=	$getreitems['exam_pri6']; }
		if($roword['submonth']=='12'){ $priceprice	=	$getreitems['exam_pri12']; }
	}
	
	$Expiredate	=	date('Y-m-d', strtotime( '+'.$roword['submonth'].' months' ) );
	
	$show_cart	.="<table width='600' border='0' align='centre' cellpadding='5' cellspacing='5' ><tr>
			<td style='' align='right'><strong>Total Price:&nbsp;&nbsp;</strong></td>";
	$show_cart	.= '<td style=""><strong>$ '.$priceprice.'</strong></td></tr><tr><td>&nbsp;</td><td align="left" valign="top" class="allvendor" ><input name="Final2" type="image" src="'.$base_path.'images/confirm_order.jpg" id="Final2" value="Final" />
	<input name="Final" type="hidden" id="Final" value="Final" /></td></tr></table>';
	
	$getpaypal	=	mysql_fetch_array(mysql_query("SELECT * from website"));
	
	if(isset($_POST['Final']))
{
	
?>
<html>
<body>
<form name="order" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="upload" value="1">
<input type="hidden" name="business" value="<?=$getpaypal['paypalid']?>">
<input type="hidden" name="custom" value=" ">
<input type="hidden" name="rm" value="2">
<?
$i='1';
?>
<input type="hidden" name="item_name_<?=$i?>" value="<?=$exmname?>">
<input type="hidden" name="item_number_<?=$i?>" value="<?=$numberidid?>">
<input type="hidden" name="quantity_<?=$i?>" value="1">
<input type="hidden" name="amount_<?=$i?>" value="<?=$priceprice?>">

<? 
$qry_master =	"insert into tbl_reorder (id_detailorder,price,startdate,enddate,status)values('".$_GET['id']."','".$priceprice."','".date("Y-m-d")."','".$Expiredate."','0')";
mysql_query($qry_master);
$_SESSION['reorderlastid']	=	mysql_insert_id();														
?>
<input type="hidden" name="custom" value="<?=$_GET['id']?>">
<input type="hidden" name="return" value="<?php echo $websiteURL;?>thanks_reorder.php">
<input type="hidden" name="cancel" value="<?php echo $websiteURL;?>account.html">

</form>
You are redirecting to Paypal for Secure Payment.....
<script type="text/javascript" language="JavaScript">
  document.order.submit();
</script>
</body>
</html>
<?
}
	
include("html/paypal_payment.html");	
	
?>