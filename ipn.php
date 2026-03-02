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

$tt	='0';



$paypal_host = 'www.paypal.com';

$paypal_path = '/cgi-bin/webscr';

$ipn_data = array ();

$pid = htmlentities($_GET['pid']);

$data = array ();

$post_string = '';

foreach ($_POST as $field => $value)

{

	$ipn_data[$field] = $value;

	$post_string     .= $field.'='.urlencode ($value).'&';

}

$post_string .= "cmd=_notify-validate"; // append ipn command



// Open the connection to paypal

$fp = @ fsockopen ($paypal_host, '80', $err_num, $err_str, 30);

if (!$fp)

{

	// Could not open the connection.

	// If loggin is on, the error message will be in the log.

	$last_error = "fsockopen error no. {$errnum}: {$errstr}";

	log_ipn_results(false);

	exit();

}

else

{

	// Post the data back to paypal

	@ fputs ($fp, "POST {$paypal_path} HTTP/1.1\n");

	@ fputs ($fp, "Host: {$paypal_host}\n");

	@ fputs ($fp, "Content-type: application/x-www-form-urlencoded\n");

	@ fputs ($fp, "Content-length: ".strlen ($post_string)."\n");

	@ fputs ($fp, "Connection: close\n\n");

	@ fputs ($fp, $post_string."\n\n");



	// Loop through the response from the server and append to variable

	$ipn_response = '';

	while (!feof ($fp))

	{

		$ipn_response .= @ fgets ($fp, 1024);

	}

   // Close connection

   @ fclose ($fp);

}



if (eregi ("VERIFIED", $ipn_response))

{

	// Valid IPN transaction

#######################################################################

	$txn_id=$_POST["txn_id"];

	$tt='1';

}

	if($tt=='1')

	{

		$SentNo=base64_decode($pid);

		$parts=explode("-",$SentNo);

		$UserId=$parts[0];

		$CartId=$parts[1];

		

		$q		 =	"select * from tbl_user where user_id='".$UserId."'";

 		$rs1	 =	mysql_query($q);

		$row1	 =	mysql_fetch_array($rs1);

		$Username=  $row1['user_fname']." ".$row1['user_lname'];	

		

		$qy_cart_master	=	"select * from temp_master where Cust_ID='".$UserId."'";

  		$r_cart_master	=	mysql_fetch_array(mysql_query($qy_cart_master));

		

		if($r_cart_master['Net_Amount'] <= $_POST["mc_gross"])

		{

			$qry_temp = "insert into order_master(Cust_ID,Order_Id,OrderDate,Status,Net_Amount,Cart_ID,OrderDesc)values('".$UserId."','".$txn_id."','".$dayedate."','1','".$r_cart_master['Net_Amount']."','".$CartId."','".$r_cart_master['OrderDesc']."')";

			mysql_query($qry_temp);

			$lastordid	=	mysql_insert_id();

			

			@mysql_query("update coupon_order set order_id='$lastordid', order_type='1' where order_id = '".$r_cart_master['ID']."' and order_type='0'");

			

			$qy_cart		=	"select * from temp_order where UserID='".$UserId."'";

			$r_cart 		=	mysql_query($qy_cart);

			

			$tttttt	=	'1';

		 	while($row_cart=mysql_fetch_array($r_cart))

			{

			 	

			$price_one	=	$row_cart['Price'];

			

			if($row_cart['subscrip']=='3'){	$Expiredate	=	date('Y-m-d', strtotime( '+12 days' ) );	}

            if($row_cart['subscrip']=='6'){	$Expiredate	=	date('Y-m-d', strtotime( '+12 days' ) ); 	}

            if($row_cart['subscrip']=='12'){ $Expiredate	=	date('Y-m-d', strtotime( '+12 days' ) ); }

			if($row_cart['subscrip']=='1'){ $Expiredate	=	date('Y-m-d', strtotime( '+12 days' ) ); }

			//$totnetamout	=	$row_cart['Price'];

				

			$add_cart = $row_cart['Quantity'] * $price_one;

				

		$query_order="insert into order_detail(UserID,masterid,Cart_ID,VendorID,TrackID,ProductID,Description,ExpireDate,strdate,submonth,status, PTypeID)values('".$UserId."','".$lastordid."','".$row_cart['CartID']."','".$row_cart['VendorID']."','".$row_cart['TrackID']."','".$row_cart['Pid']."','".$row_cart['Product']."','".$Expiredate."','".$dayedate."','".$row_cart['subscrip']."','1','".$row_cart['PType']."')";

 				mysql_query($query_order);

				

				// Add an Instance for Test Engine

				if($row_cart['PType']=="sp" || $row_cart['PType']=="both")

				{

					$order_id = mysql_insert_id();

					$time = date("Y-m-d H:i:s");

					$serial_number = md5($order_id.$time);

					$expiry = date('Y-m-d', strtotime("+12 days"));

					$engine_arr = mysql_fetch_array(mysql_query("select id from tbl_package_engine where package_id = '{$row_cart['Pid']}' and type='Simulator' and os = 'Win';"));

					$engine_id = $engine_arr['id'];

					if(!empty($engine_id))

					{

						$insert="insert into tbl_package_instance (id, engine_id, order_number, serial_number, mboard_number, instance_expiry) Values('', '$engine_id', '{$order_id}', '$serial_number', '', '$expiry');";

						$query=mysql_query($insert);

					}

				}

				

				if($row_cart['VendorID']!=''){

				$getnameid	=	mysql_fetch_array(mysql_query("SELECT * from tbl_vendors where ven_id='".$row_cart['VendorID']."'"));

				$itemname	=	$getnameid['ven_name'].'&nbsp;Vendor';

				}

				if($row_cart['TrackID']!=''){

				$getnameid	=	mysql_fetch_array(mysql_query("SELECT * from tbl_cert where cert_id='".$row_cart['TrackID']."'"));

				$itemname	=	$getnameid['cert_name'].'&nbsp;Certification';

				}

				if($row_cart['Pid']!='' && $row_cart['Pid']!='0'){

				$getnameid	=	mysql_fetch_array(mysql_query("SELECT * from tbl_exam where exam_id='".$row_cart['Pid']."'"));

				//$itemname	=	$getnameid['exam_name'].'&nbsp;Exam'; //commented by nexgeno dev
				$itemname   = ($row_cart['PType']=='8' || $row_cart['PType']=='9') ? $getnameid['exam_name'] :	$getnameid['exam_fullname'].' '.$getnameid['exam_name'].'&nbsp;Exam'; //added by nexgeno dev

				}

				$priceloop[$tttttt]		=	$price_one;

				$itemloopquan[$tttttt]	=	$row_cart['Quantity'];

				$nameloop[$tttttt]		=	$itemname;

		$tttttt++;

		}

			log_ipn_results(true);

			include("finalorderemail.php");

			mysql_query("DELETE from temp_order where UserID='".$_SESSION['uid']."'");

			mysql_query("DELETE from temp_master where Cust_ID='".$_SESSION['uid']."'");

			// Start of Affiliate Sale code //

			include_once ("../affiliate/track.php");

			if(function_exists('DoTrackAff'))

			{

				DoTrackAff( $r_cart_master['Net_Amount'], $lastordid );

			}

			else

			{

				//mail("webmaster147@outlook.com","Affiliate commission in trouble $copyright","Paypal Affiliate commission in trouble $copyright");

			}

			// End of Affiliate Sale code //

			exit();

		}

		else

		{

			$paid = $_POST["mc_gross"];

			$due = $r_cart_master['Net_Amount'];

			//mail("webmaster147@outlook.com","Paypal - Wrong Payment report.","Customer $Username (ID - $UserId) paid $paid but the due amount was $due.");

			exit();

		}

	}

	else

	{

		$last_error = "payment not verified";

		log_ipn_results(false);

		exit();

	}



function log_ipn_results($success)

{

	global $ipn_data, $last_error, $ipn_response;



   // Timestamp

   $text = '['.date ('m/d/Y g:i A').'] - ';



   // Success or failure being logged?

   if ($success)

      $text .= "SUCCESS!\n";

   else

      $text .= 'FAIL: '.$last_error."\n";



   // Log the POST variables

   $text .= "IPN POST Vars from Paypal:\n";

   foreach ($ipn_data as $key=>$value)

   {

      $text .= "{$key}={$value}, ";

   }

   /*$data['email']    = $ipn_data['payer_email'];

   $data['name']     = $ipn_data['last_name'].' '.$ipn_data['first_name'];

   $data['link_id']  = $ipn_data['item_number'];

   $data['quantity'] = $ipn_data['quantity'];#mc_gross

   $data['total']    = $ipn_data['mc_gross'];*/

   // Log the response from the paypal server

   $text .= "\nIPN Response from Paypal Server:\n ".$ipn_response;



   // Write to log

   $fp = @ fopen ('ipn_log.txt','a');

   @ fwrite ($fp, $text . "\n\n");

   @ fclose ($fp);  // close file

}

?>