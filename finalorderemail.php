<?php
include_once __DIR__ . '/functions.php';
	$to			=	$row1['user_email'];	

	$getmasterdet	=	mysql_fetch_array(mysql_query("SELECT * from order_master where ID='".$lastordid."'"));

    $getusercartdetails	=	mysql_query("SELECT * from order_detail where UserID='".$UserId."' and masterid='".$lastordid."'");	

	$MsgHTML2	=	'<p>Dear '.$row1['user_fname'].' '.$row1['user_lname'].',<br />

    <br /><p><br />
  Thankyou, We have received your funds.</p>
<p>We know that you have not received the files yet as team will be emailing you in 8 to 10 hrs time. Whenever they will see the transaction/order confirmation email. Nothing to worry about you will get your workbooks/dumps.</p>
<p>For faster response we are available on WhatsApp : 19512570551 please add us on skype : mailto:chinesexams@gmail.com for faster response.</p>

<p>Request you do not open PAYPAL CASE and wait for our team to respond we might be in different time zone so things might delay but our 
team will respond you 100% within 8 to 10 hrs time and send you the workbooks/dumps. </p>
<p>CHINESEDUMPS.COM</p>

    Order detail:</strong></p><br />

<table border="1" cellspacing="0" cellpadding="4" width="254">

  <tr>

    <td width="93" nowrap="nowrap" valign="bottom" style="background-color:#93D7E6;"><strong>Date</strong></td>

    <td width="151" nowrap="nowrap" valign="bottom">'.date("F j, Y",strtotime($getmasterdet['OrderDate'])).'</td>

  </tr>

  </table>

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

   for($itemloop='1';$itemloop<$tttttt;$itemloop++){

   $MsgHTML2 .= '<tr>

    <td width="69" valign="bottom">'.$itemloop.'.</td>

    <td width="136" nowrap="nowrap" valign="bottom" >'.$nameloop[$itemloop].'</td>

	<td width="80" nowrap="nowrap" valign="bottom">$'.$priceloop[$itemloop].'</td>

    <td width="80" valign="bottom" align="right">'.$itemloopquan[$itemloop].'</td>

    <td width="80" valign="bottom" align="right">$'.$priceloop[$itemloop]*$itemloopquan[$itemloop].'</td>

  </tr>';

    }

	

	$row_c['computed_value']=0;

	$query_c	="select coupon_code, computed_value from coupon_order where order_id='".$getmasterdet['ID']."' and order_type='1'";

	$rs_c		=mysql_query($query_c);

	if(mysql_num_rows($rs_c)>0)

	{

		$row_c	=mysql_fetch_array($rs_c);

	}

  	$MsgHTML2 .= '<tr>

    <td width="354" nowrap="nowrap" colspan="3" valign="bottom">&nbsp;</td>

    <td width="80" valign="bottom"><strong>Discount</strong></td>

    <td width="80" valign="bottom" align="right"><strong>$'.$row_c['computed_value'].'</strong></td>

  </tr><tr>

    <td width="354" nowrap="nowrap" colspan="3" valign="bottom">&nbsp;</td>

    <td width="80" valign="bottom"><strong>TOTAL</strong></td>

    <td width="80" valign="bottom" align="right"><strong>$'.$getmasterdet['Net_Amount'].'</strong></td>

  </tr>

</table>';

  $subject	=	'Thanks for Order.';

  $headers  = 'MIME-Version: 1.0' . "\r\n";

  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

  $headers .= "From: Admin<".$fromEmail."> ". "\r\n" ;

  sendEmail( $to, $subject, $MsgHTML2, $headers);
//   mail( $to, $subject, $MsgHTML2, $headers);

?>