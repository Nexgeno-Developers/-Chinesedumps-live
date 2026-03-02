<?php
ob_start();
session_start();
//header("location:swreg.html");
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");

include("includes/shoppingcart.php");
include("functions.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);
$objPricess = new classPrices();

if(!isset($_SESSION['uid']) || $_SESSION['uid']==''){
header("location:login.html");
}
$getPage	=	$objMain->getContent(11);
//mysql_query("DELETE from temp_master where Cust_ID='".$_SESSION['uid']."'");

    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
	
	$copyright	=	$exec['copyright'];
		
	$firstlink	=	" ".$getPage[1];
	
	
$query_cart		=	"select * from temp_order where UserID='".$_SESSION['uid']."' order by ID ASC";
$rs_cart		=	mysql_query($query_cart);
$num			=	mysql_num_rows($rs_cart);
if($num=='0'){
header("location:cart.html");
}

$gros	=	'';
$basepath	=	$base_path;


			if(!isset($_SESSION['uid']) && $_SESSION['uid']=="")
			{
			$_SESSION['check']='cart.html';
			$_SESSION['checkoutpage']	=	'checkoutpage';
			header("location:login.html");
			}
		
unset($_SESSION['checkoutpage']);
			
$sum	=	'';
//$cartPcode	=	$_SESSION['pcode'];
//$proCart	= 	$_SESSION['procartid']; 

$user_Id 	=	$_SESSION['uid']; 
$cart_Id	=	session_id();
$user_email	=	$_SESSION['email'];
$Expiredate	=	date('Y-m-d', strtotime( '+3 months' ) ); 
$date		=	date("Y-m-d");

$ip= $_SERVER['REMOTE_ADDR']; 

setcookie("UserId",$user_Id,strtotime("+90 days"));    
setcookie("CartId",$cart_Id,strtotime("+90 days"));

$discount=0;
//$getpaypal	=	mysql_fetch_array(mysql_query("SELECT * from website"));
if(isset($_POST['apply_coupon']))
{
	$coupon = $_POST['coupon'];
	if($coupon!="")
	{
		$_SESSION['coupon'] = "";
		$query = mysql_query("select coupon_value, coupon_type, coupon_code from coupon where coupon_code = '$coupon' and (curdate() between start_date and end_date) and status='1' and number_of_uses > already_used");
		if(mysql_num_rows($query)>0)
		{
			$discount = mysql_fetch_array($query);
			$_SESSION['coupon'] = $discount;
			$errstr = "Coupon code applied successfully.";
		}
		else
		{
			$errstr = "Invalid coupon code.";
		}
	}
	else
	{
		$errstr = "Invalid coupon code.";
	}
}
if(isset($_POST['Final']))
{  
	$qyery="select * from temp_order where UserID='".$_SESSION['uid']."'";
	$rs=mysql_query($qyery);

?>
<html> 
<body>
<form name="order" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<!--<form name="order" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">-->
<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="upload" value="1">
<input type="hidden" name="business" value="<?=$paypalID;?>">
<!--<input type="hidden" name="business" value="sb-xt24x40169786@business.example.com"> -->
<input type="hidden" name="custom" value=" ">
<input type="hidden" name="rm" value="2">
<?
$trtrid		=	'';
$product	=	'';
$ttprice	=	'';
$i='1';
$Products_Arr="";
while($row=mysql_fetch_array($rs))
 {	

$cart		=	$row['CartID'];	

$append = " (Secure PDC)";
if($row['Pid']!='0'){
$getproexam		=	mysql_fetch_array(mysql_query("SELECT * from tbl_exam where exam_id='".$row['Pid']."'"));
$numberidid	=	$row['Pid'];
$descrip = $getproexam['exam_fullname'] . ' ' . $getproexam['exam_name'];

$myexamId = $row['Pid'];
$myexamtype = $row['PType'];
$duration = $row['subscrip'];

					if($row['subscrip']=='3'){	$price = $objPricess->getProductPrice($myexamId,$myexamtype,'3'); $Expiredate	=	date('Y-m-d', strtotime( '+3 months' ) );	}
                    if($row['subscrip']=='6'){	$price = $objPricess->getProductPrice($myexamId,$myexamtype,'6'); $Expiredate	=	date('Y-m-d', strtotime( '+6 months' ) );	}
                    if($row['subscrip']=='12'){ $price = $objPricess->getProductPrice($myexamId,$myexamtype,'12'); $Expiredate	=	date('Y-m-d', strtotime( '+12 months' ) );	}
                    if($row['subscrip']=='0'){ $price = $objPricess->getProductPrice($myexamId,$myexamtype,'0'); $Expiredate	=	"0";	}
					
					if(isset($row['PType'])  and $row['PType']=='both'){ $append = " (Secure PDC + Testing Engine)";}
					
					elseif($row['PType'] =='sp'){ $append = " (Testing Engine only)";	}
					
					else{$append = " (Secure PDC)";}
					
					$descrip .= $append;
}
if($row['TrackID']!='0' && $row['TrackID']!=''){
$getproexam		=	mysql_fetch_array(mysql_query("SELECT * from tbl_cert where cert_id='".$row['TrackID']."'"));
$numberidid	=	$getproexam['cert_id'];
$descrip	=	$getproexam['cert_name'];
					if($row['subscrip']=='3'){	$price	=	 $getproexam['cert_pri3'];	}
                    if($row['subscrip']=='6'){	$price	=	 $getproexam['cert_pri6'];	}
                    if($row['subscrip']=='12'){ $price	=	 $getproexam['cert_pri12'];	}
}

if($row['VendorID']=='0' && $row['TrackID']=='0'&& $row['Pid']=='0'){
$getproexam		=	mysql_fetch_array(mysql_query("SELECT * from website"));
$numberidid	=	'';
if($row['Jumbo'] == 1 ){$descrip = "Unlimited Access for 1 Month"; $Expiredate	=	date('Y-m-d', strtotime( '+1 months' ) ); $numbid = "p1";}
if($row['Jumbo'] == 3 ){$descrip = "Unlimited Access for 3 Months"; $Expiredate	=	date('Y-m-d', strtotime( '+3 months' ) ); $numbid = "p3";}
if($row['Jumbo'] == 6 ){$descrip = "Unlimited Access for 6 Months"; $Expiredate	=	date('Y-m-d', strtotime( '+6 months' ) ); $numbid = "p6";}
if($row['Jumbo'] == 12){$descrip = "Unlimited Access for 12 Months"; $Expiredate	=	date('Y-m-d', strtotime( '+12 months' ) );
$numbid = "p12";}
//$descrip	=	'All Exams';
$numberidid = $numbid;
//$Expiredate	=	date('Y-m-d', strtotime( '+1 months' ) );
$price	=	$row['Price'];                   
//echo "111".$row['Price'];                   

}

				$product 	.= 	$descrip."<br/>";
				$product2 	= 	$descrip;
				
				$add_cart = $row['Quantity'] * $price;
				$newpaypal	=	$add_cart;
?>
<input type="hidden" name="item_name_<?=$i?>" value="<?=$product2?>">
<input type="hidden" name="item_number_<?=$i?>" value="<?=$numberidid?>">
<input type="hidden" name="quantity_<?=$i?>" value="<?=$row['Quantity']?>">
<input type="hidden" name="amount_<?=$i?>" value="<?=$price?>">
<?
$ttprice	=	$newpaypal+$ttprice;
$Products_Arr .= '<tr>
    <td width="136" nowrap="nowrap" valign="bottom" >'.$i.'</td>
    <td width="136" nowrap="nowrap" valign="bottom" >'.$descrip.'</td>
	<td width="80" nowrap="nowrap" valign="bottom">$'.$price.'</td>
    <td width="80" valign="bottom" align="right">'.$row['Quantity'].'</td>
    <td width="80" valign="bottom" align="right">$'.$add_cart.'</td>
  </tr>';					
$i++;
}
	$discounted_price=$ttprice;
	$discount_desc = '';
	if(@$_SESSION['coupon']['coupon_value']!=0)
	{
		$disc_type = $_SESSION['coupon']['coupon_type'];
		$disc_value = $_SESSION['coupon']['coupon_value'];
		if($disc_type=='2'){
			$disc_value = $ttprice * ($_SESSION['coupon']['coupon_value']/100);
		}
		$discounted_price = $ttprice - $disc_value;
		if($discounted_price != $ttprice && $discounted_price > 0)
		{
			$discount_desc = '<tr>
    <td width="354" nowrap="nowrap" colspan="3" valign="bottom">&nbsp;</td>
    <td width="80" valign="bottom"><strong>Discount</strong></td>
    <td width="80" valign="bottom" align="right"><strong>$'.$disc_value.'</strong></td>
  </tr>
  <tr>
    <td width="354" nowrap="nowrap" colspan="3" valign="bottom">&nbsp;</td>
    <td width="80" valign="bottom"><strong>Total Payable</strong></td>
    <td width="80" valign="bottom" align="right"><strong>$'.$discounted_price.'</strong></td>
  </tr>';
		}
	}else{
		$disc_value	 = 0;
	}
	
$qry_master =	"insert into temp_master (Cust_ID,OrderDate,ExpiryDate,Status,Net_Amount,Cart_ID,OrderDesc,IP,Affiliate_Id,Referer_URL,Sales_Person_Id,SiteID)values(".$user_Id.",'".$date."','".$Expiredate."','1','".$discounted_price."','".$cart_Id."','".$product."','','','','','0')";


mysql_query($qry_master);

// 1) Fetch user details
$user_qry = mysql_query("
    SELECT user_email, user_fname, user_lname, user_phone 
    FROM tbl_user 
    WHERE user_id = '".$_SESSION['uid']."'
");
$user = mysql_fetch_assoc($user_qry);


$userPhone  = isset($user['user_phone']) ? $user['user_phone'] : '';
$userEmail     = isset($user['user_email']) ? $user['user_email'] : '';
$userFirstName = isset($user['user_fname']) ? $user['user_fname'] : '';
$userLastName  = isset($user['user_lname']) ? $user['user_lname'] : '';
$fullName = trim($userFirstName . ' ' . $userLastName);

$phoneRow = '';
if ( $userPhone !== '' ) {
    $phoneRow = '
      <tr>
        <td width="93" nowrap valign="bottom" style="background-color:#93D7E6;"><strong>User Phone</strong></td>
        <td width="151" nowrap valign="bottom">'.htmlspecialchars($userPhone).'</td>
      </tr>';
}

$MsgHTML2	=	'<p>Hi Admin,<br />
    <br />A customer redirected to pay through Paypal with below details.<p><strong><br />
    Order detail:</strong></p>
<table border="1" cellspacing="0" cellpadding="4" width="254">
  <tr>
    <td width="93" nowrap="nowrap" valign="bottom" style="background-color:#93D7E6;"><strong>Date</strong></td>
    <td width="151" nowrap="nowrap" valign="bottom">'.date("F j, Y",strtotime($date)).'</td>
  </tr>
  <tr>
    <td width="93" nowrap="nowrap" valign="bottom" style="background-color:#93D7E6;"><strong>User ID</strong></td>
    <td width="151" nowrap="nowrap" valign="bottom">'.$user_Id.'</td>
  </tr>
  <tr>
    <td width="93" nowrap="nowrap" valign="bottom" style="background-color:#93D7E6;"><strong>User Name</strong></td>
    <td width="151" nowrap="nowrap" valign="bottom">'.htmlspecialchars($fullName).'</td>
  </tr>
  <tr>
    <td width="93" nowrap="nowrap" valign="bottom" style="background-color:#93D7E6;"><strong>User Email</strong></td>
    <td width="151" nowrap="nowrap" valign="bottom">'.htmlspecialchars($userEmail).'</td>
  </tr>'
  .$phoneRow.
'</table>
<p><br />
    <strong>Product detail: </strong></p>
<table border="1" cellspacing="0" cellpadding="4" width="583">
  <tr>
    <td width="69" valign="top" ><strong>Sr.#</strong></td>
    <td width="106" nowrap="nowrap" valign="bottom" ><strong>Code</strong></td>

    <td width="102" nowrap="nowrap" valign="bottom" ><strong>Unit Price</strong></td>
    <td width="78" valign="bottom" ><strong>Quantity </strong></td>
    <td width="90" valign="top" ><strong>Total</strong></td>
  </tr>';
  
$MsgHTML2 .= $Products_Arr.'<tr>
    <td width="354" nowrap="nowrap" colspan="3" valign="bottom">&nbsp;</td>
    <td width="80" valign="bottom"><strong>Cart Total</strong></td>
    <td width="80" valign="bottom" align="right"><strong>$'.$ttprice.'</strong></td>
  </tr>'.$discount_desc.'
</table>';
global $fromEmail;
  $subject	=	'Expected New Order - Swreg';
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $headers .= "From: Admin<".$fromEmail."> ". "\r\n" ;
  sendEmail($fromEmail, $subject, $MsgHTML2, $headers);
//   sendEmail("webdeveloper@nexgeno.in", $subject, $MsgHTML2, $headers);
//   @mail($fromEmail, $subject, $MsgHTML2, $headers);
$masterqyery = mysql_fetch_array(mysql_query("select * from temp_master where Cust_ID='".$_SESSION['uid']."'"));
	if($discounted_price != $ttprice && $discounted_price > 0)
	{
		mysql_query("insert into coupon_order set coupon_code = '".$_SESSION['coupon']['coupon_code']."', order_id='".$masterqyery['ID']."', order_type='0', computed_value='$disc_value'");
		mysql_query("update coupon set already_used = (already_used+1) where coupon_code = '".$_SESSION['coupon']['coupon_code']."'");
	}

	$SentNo=$_SESSION['uid']."-".$cart_Id;
	$SentNo=base64_encode($SentNo);
	
function encrypt_data($data, $key = null, $iv = null) {
    $cipher = "AES-128-CTR";
    $options = 0;
    if ($key === null) {
        $key = secret('APP_ENCRYPTION_KEY', '');
    }
    if ($iv === null) {
        $iv = secret('APP_ENCRYPTION_IV', '');
    }
    if ($key === '' || $iv === '') {
        return false;
    }
    return openssl_encrypt($data, $cipher, $key, $options, $iv);
}
$encrypted_uid = encrypt_data($_SESSION['uid']);

?>
<input type="hidden" name="custom" value="<?=$masterqyery['ID']?>">
<input type="hidden" name="return" value="<?php echo $websiteURL;?>thanks.html?uid=<?php echo urlencode($encrypted_uid); ?>">
<input type="hidden" name="cancel_return" value="<?php echo $websiteURL;?>cart.html?cancel_failed=1&uid=<?php echo urlencode($encrypted_uid); ?>">
<!--<input type="hidden" name="notify_url" value="<?php echo $websiteURL;?>ipn.php?pid=<?=$SentNo?>">-->
<input type="hidden" name="notify_url"value="<?= $websiteURL ?>paypal_ipn.php?pid=<?= urlencode($SentNo) ?>&amp;uid=<?= urlencode($_SESSION['uid']) ?>">
<input type="hidden" name="discount_amount_cart" value="<?=$disc_value?>">
</form>
You are redirecting to Paypal for Secure Payment.....
<script type="text/javascript" language="JavaScript">
  document.order.submit();
</script>
</body>
</html>
<?
}
$show_cart = $objCart->Checkout($cart_Id,$base_path,$discount);

include("html/paypal_payment.html");
?>
