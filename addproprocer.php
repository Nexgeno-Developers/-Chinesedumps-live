<?php
session_start();
include("includes/config/classDbConnection.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");

$objDBcon   =   new classDbConnection;
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);
//-----------------------------Base Path-----------------------------------------------
error_reporting(0);
$fieldStatus="";

$guest = session_id();
$_SESSION['guestId'] = $guest;

$getID    		=  $_GET['id']; ///pid
$tblName  		=  $_GET['tb']; //trackid
$subscr  		=  $_GET['subsr']; //trackid
$i		= 0;
		$proid	=	$_GET['id'];
		$_SESSION['procartid'] = $proid;
			
		$totalprice	=	$tblName ;

		$date=date("Y-m-d");
		if(isset($_SESSION['uid'])){
		$check_rows			=	mysql_query("select * from temp_order where UserID='".$_SESSION['uid']."' and TrackID='".$proid."' and Jumbo2='1'");
		}else{
		$check_rows			=	mysql_query("select * from temp_order where CartID='".$guest."' and UserID='0' and TrackID='".$proid."' and Jumbo2='1'");
		}
		$exe_check			=	mysql_num_rows($check_rows);
		$getCheck			=	mysql_fetch_array($check_rows);
		if($exe_check>='1'){
		
		$extraquant	=	$getCheck['Quantity']+1;
		if(isset($_SESSION['uid'])){
		$query_add	=	mysql_query("UPDATE temp_order set Price='".$totalprice."',subscrip='".$subscr."' where UserID='".$_SESSION['uid']."' and TrackID='".$proid."' and Jumbo2='1'");
		}else{
		$query_add	=	mysql_query("UPDATE temp_order set Price='".$totalprice."',subscrip='".$subscr."' where CartID='".$guest."' and UserID='0' and TrackID='".$proid."' and Jumbo2='1'");
		}
		}else{
		
		$getbundlesitem	=	$objPro->getAllItemsCerts($proid);
		
		if(isset($_SESSION['uid'])){
		$userid	=	$_SESSION['uid'];
		}else{
		$userid	=	'0';
		}
		$query_add			=	"insert into temp_order	(CartID,UserID,VendorID,TrackID,Pid,Jumbo2,Quantity,Price,Product,Date,subscrip)values('".$_SESSION['guestId']."','".$userid."','','".$_GET['id']."','','1','1','".$totalprice."','".$getbundlesitem."','".$date."','".$subscr."')";
								mysql_query($query_add);
					}			
		
									if(isset($_SESSION['uid'])){
									$query	=	"select * from temp_order where UserID='".$_SESSION['uid']."' order by ID ASC";
									}else{
									$query	=	"select * from temp_order where CartID='".session_id()."' and UserID='0' order by ID ASC";
									}
		$rs_left_cart		=	mysql_query($query);
		$num	=	mysql_num_rows($rs_left_cart);
		
		
		if($num>0 && $page_outQuery!='managecart.php'){ 
		 
								$msgpri	=' <table width="100%" border="0" style="border:solid 1px #d1d1d1;" cellspacing="0" cellpadding="0">
											  <tr>
												<td height="30" bgcolor="#565655" class="Shopping"><span class="gnheading">Shopping Cart</span></td>
											 </tr>
											  <tr>
											  
												 <td class="anytext">
											 <tr><td class="anytext" >
								 <div id="carthidshowafterdel">'; 
								$msgpri	.= $objCart->showd_cart_left($rs_left_cart,$base_path,$_SESSION['uid']);
								$msgpri	.='</div>					   
								  </td></tr>
									</td>
										 </tr>
										  
										</table>';
								
		}						
			
			echo $msgpri;
?>