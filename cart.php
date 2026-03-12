<?php
require_once __DIR__ . '/includes/config/load_secrets.php';
ob_start();
session_start();
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



//$getPage	=	$objMain->getContent(10);

$getPage[0]	=	"Cart";

$getPage[1]	=	"Cart";

$getPage[2]	=	"Cart";

$getPage[4]	=	"Cart";

$getPage[5]	=	"Cart";



    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');

	$exec	=	mysql_fetch_array($quer);

	

	$copyright	=	$exec['copyright'];

		

	$firstlink	=	" ".$getPage[1];

	

	if(isset($_SESSION['uid'])){

			$query	=	"select * from temp_order where UserID='".$_SESSION['uid']."' order by ID ASC";

			$rt	=	$_SESSION['uid'];

		}else{

			$query	=	"select * from temp_order where CartID='".session_id()."' and UserID='0' order by ID ASC";

			$rt	=	'0';

			}

	$rs		=	mysql_query($query);

	$num	=	mysql_num_rows($rs);

	

	if($num>0)

	{

		$show_cat 	= 	$objCart->showd_cart(session_id(),$base_path,$rt);

	}

	else

	{

		$show_cat 	= 	$objCart->cart_manage(session_id());

	}

	

	if(isset($_POST['btn_update']) and $_POST['btn_update']=='Update')

	{

		$qunt		=	$_POST['txt'];

		$cat_id		=	$_POST['txt_id'];

		$cnt		=	$_POST['counter']; 

	

	//--------------------------------updation----------------------------

		$i=0;

	while($i<count($qunt))

		{

			if(isset($qunt[$i]))

			{



			 if($qunt[$i]==0 || !is_numeric($qunt[$i])){

			 $rr	=	'1';

			 }else{

			 $rr	=	$qunt[$i];

			 }

			 $query	=	"update temp_order set Quantity='".abs($rr)."'  where ID='".$cat_id[$i]."'";

			 mysql_query($query);

			}

			$i++;	

		}

		

	header('location:cart.html');	

	}



	

	if(isset($_POST['Checkout']) and $_POST['Checkout']=='Checkout')

	{

			if($_SESSION['uid']=="")

			{

			$_SESSION['check']='cart.html';

			header("location:myaccount.html");

			}

			else

			{

			

			header("location:checkout.html");

			}

		}



// Initialize email sent flag
if (!isset($_SESSION['email_sent'])) {
    $_SESSION['email_sent'] = false; // Flag to track if the email has been sent
}

function decrypt_data($data, $key = null, $iv = null) {
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
    return openssl_decrypt($data, $cipher, $key, $options, $iv);
}
if (isset($_GET['cancel_failed']) && $_GET['cancel_failed'] == '1' && isset($_GET['uid']) && !$_SESSION['email_sent']) {
    $decrypted_user_id = decrypt_data($_GET['uid']);
    if ($decrypted_user_id) {
        // echo "Welcome back, user ID: " . htmlspecialchars($decrypted_user_id);
        // Optional: restore session
        // $_SESSION['uid'] = $decrypted_user_id;
        
        // Create MySQLi connection using the properties from classDbConnection
        $mysqli = new mysqli($objDBcon->dbHost, $objDBcon->dbUser, $objDBcon->dbPass, $objDBcon->dbName);
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

       // Fetch user details
        $stmt_user = $mysqli->prepare("SELECT user_phone, user_email, user_fname, user_lname FROM tbl_user WHERE user_id = ?");
        $stmt_user->bind_param("i", $decrypted_user_id);
        $stmt_user->execute();
        $user_result = $stmt_user->get_result();
    
        if ($user_result && $user_result->num_rows > 0) {
            $user = $user_result->fetch_assoc();
            $user_email = $user['user_email'];
            $user_phone = $user['user_phone'];
            $user_name = trim($user['user_fname'] . ' ' . $user['user_lname']);
    
            // Fetch failed order cart items
            $stmt_cart = $mysqli->prepare("SELECT * FROM temp_order WHERE UserID = ? ORDER BY ID DESC");
            $stmt_cart->bind_param("i", $decrypted_user_id);
            $stmt_cart->execute();
            $cart_result = $stmt_cart->get_result();
    
            // Function to generate cart HTML table
            function getUserDetails($userID, $mysqli) {
                $stmt = $mysqli->prepare("SELECT user_email, user_fname, user_lname FROM tbl_user WHERE user_id = ?");
                if (!$stmt) {
                    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                    return false;
                }
            
                $stmt->bind_param("i", $userID);
                $stmt->execute();
                $result = $stmt->get_result();
            
                if ($result && $result->num_rows > 0) {
                    $userDetails = $result->fetch_assoc();
                    $stmt->close();
                    return $userDetails;
                }
            
                $stmt->close();
                return false;
            }
            
            function getdbPrice($pCode,$duration){
                $strSql 	=	"SELECT * FROM product_prices WHERE pri_type='".$pCode."' and pri_duration='".$duration."'";
                $result		=	mysql_query($strSql);
                $re 		=	mysql_fetch_array($result);
                return $re;
            }
            
            function getProductPrice($pCodeid,$ptype,$duration){
            
            	$strSql 	=	"SELECT * FROM tbl_exam WHERE exam_id='".$pCodeid."'";
                $result		=	mysql_query($strSql);
            	$re 		=	mysql_fetch_array($result);
            	if($re["ven_id"] == "16" || $re["ven_id"] == "107"){
            		$price_plus = "10.00";
            	}else{
            		$price_plus = "0.00";
            	}
            	if($ptype == "both"){
            		if($re["both_pri"] != "" && $re["both_pri"] != "0.00"){
            			$setPrice = $re["both_pri"];
            		}else{
            			$setPrice = $this->getdbPrice($ptype,$duration);
            			$setPrice = $setPrice["pri_value"]+$price_plus;
            		}
            	}elseif($ptype == "sp"){
            		if($re["engn_pri3"] != "" && $re["engn_pri3"] != "0.00"){
            			$setPrice = $re["engn_pri3"];
            		}else{
            			$setPrice = $this->getdbPrice($ptype,$duration);
            			$setPrice = $setPrice["pri_value"]+$price_plus;
            		}
            	}elseif($ptype == "9"){
            		if($re["both_pri"] != "" && $re["both_pri"] != "0.00"){
            			$setPrice = $re["both_pri"];
            		}else{
            			$setPrice = $this->getdbPrice($ptype,$duration);
            			$setPrice = $setPrice["pri_value"]+$price_plus;
            		}
            	}elseif($ptype == "8"){
            		if($re["engn_pri3"] != "" && $re["engn_pri3"] != "0.00"){
            			$setPrice = $re["engn_pri3"];
            		}else{
            			$setPrice = $this->getdbPrice($ptype,$duration);
            			$setPrice = $setPrice["pri_value"]+$price_plus;
            		}
            	}elseif($ptype == "7"){
            		if($re["exam_pri3"] != "" && $re["exam_pri3"] != "0.00"){
            			$setPrice = $re["exam_pri3"];
            		}else{
            			$setPrice = $this->getdbPrice($ptype,$duration);
            			$setPrice = $setPrice["pri_value"]+$price_plus;
            		}
            	}else{
            		if($re["exam_pri3"] != "" && $re["exam_pri3"] != "0.00"){
            			$setPrice = $re["exam_pri3"];
            		}else{
            			$setPrice = $this->getdbPrice($ptype,$duration);
            			$setPrice = $setPrice["pri_value"]+$price_plus;
            		}
            	}
                return $setPrice;
            }
            
            function showd_cart($result2) {
                // Running total and serial counter
                $totalPrice = 0;
                $sr         = 1;
            
                // Build table
                $show_cart  = '<table class="cart-table" border="1" cellpadding="5" cellspacing="0" width="100%">';
                $show_cart .= '<thead><tr>'
                            .  '<th>Sr. No.</th>'
                            .  '<th>Description</th>'
                            .  '<th>Unit Price</th>'
                            .  '<th>Quantity</th>'
                            .  '<th>Subtotal</th>'
                            .  '</tr></thead>'
                            .  '<tbody>';
            
                while ($row = $result2->fetch_assoc()) {
                    // Determine title & unit price
                    if (!empty($row['Pid']) && $row['Pid'] !== '0') {
                        $e = mysql_fetch_assoc(mysql_query(
                            "SELECT exam_fullname,exam_name 
                             FROM tbl_exam 
                             WHERE exam_id=" . intval($row['Pid'])
                        ));
                        switch ($row['PType']) {
                            case 'both': $app = ' (Secure PDC + Testing Engine)'; break;
                            case 'sp':   $app = ' (Testing Engine only)';        break;
                            case '7':    $app = ' (Real Lab Workbook + Racks)';   break;
                            case '8':    $app = ' (Real Lab Workbook + Racks)';   break;
                            case '9':    $app = ' (Real Labs + Bootcamp)';        break;
                            default:     $app = ' (Secure PDC)';                 break;
                        }
                        $title     = htmlspecialchars(
                                         ($row['PType'] >= '7' && $row['PType'] <= '9')
                                         ? $e['exam_name']
                                         : $e['exam_fullname'].' '.$e['exam_name']
                                     ) . $app;
                        $unitPrice = (float)getProductPrice(
                                         $row['Pid'],
                                         $row['PType'],
                                         $row['subscrip']
                                     );
                    }
                    elseif (!empty($row['TrackID']) && $row['TrackID'] !== '0') {
                        $c = mysql_fetch_assoc(mysql_query(
                            "SELECT cert_name,cert_pri3,cert_pri6,cert_pri12 
                             FROM tbl_cert 
                             WHERE cert_id=" . intval($row['TrackID'])
                        ));
                        $title     = htmlspecialchars($c['cert_name']);
                        $key       = 'cert_pri'. intval($row['subscrip']);
                        $unitPrice = isset($c[$key]) ? (float)$c[$key] : 0;
                    }
                    elseif (!empty($row['VendorID']) && $row['VendorID'] !== '0') {
                        $v = mysql_fetch_assoc(mysql_query(
                            "SELECT ven_name,ven_pri3,ven_pri6,ven_pri12 
                             FROM tbl_vendors 
                             WHERE ven_id=" . intval($row['VendorID'])
                        ));
                        $title     = htmlspecialchars($v['ven_name']);
                        $key       = 'ven_pri'. intval($row['subscrip']);
                        $unitPrice = isset($v[$key]) ? (float)$v[$key] : 0;
                    }
                    else {
                        // Jumbo fallback
                        $w = mysql_fetch_assoc(mysql_query("SELECT * FROM website"));
                        $map = ['1'=>'1 Month','3'=>'3 Months','6'=>'6 Months','12'=>'12 Months'];
                        $dsr = isset($map[$row['Jumbo']]) ? $map[$row['Jumbo']] : '';
                        $title     = "Unlimited Access Package {$dsr}"
                                   . '<br><a href="vendors.html">View All 3000+ Exams</a>';
                        $unitPrice = (float)$row['Price'];
                    }
            
                    $qty      = intval($row['Quantity']);
                    $subTotal = $unitPrice * $qty;
                    $totalPrice += $subTotal;
            
                    // Row HTML
                    $show_cart .= '<tr>'
                                .  "<td>{$sr}</td>"
                                .  "<td>{$title}</td>"
                                .  '<td>$'.number_format($unitPrice,2).'</td>'
                                .  "<td>{$qty}</td>"
                                .  '<td>$'.number_format($subTotal,2).'</td>'
                                .  '</tr>';
            
                    $sr++;
                }
            
                // Footer with grand total
                $show_cart .= '</tbody>'
                            .  '<tfoot>'
                            .    '<tr>'
                            .      '<td colspan="4" style="text-align:right;"><strong>Grand Total</strong></td>'
                            .      '<td>$'.number_format($totalPrice,2).'</td>'
                            .    '</tr>'
                            .  '</tfoot>'
                            .  '</table>';
            
                return $show_cart;
            }
               
    
            $cart_html = showd_cart($cart_result);
    
            // Common email headers
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
            $headers .= "From: chinesedumps <" . from_email() . ">\r\n";
    
            // ----------------------------
            // Email to USER
            // ----------------------------
            $subject_user = "Your Order Was Not Completed - ChineseDumps";
            $message_user = "<p>Hi {$user_name},</p>
                             <p>It looks like your order didn't complete successfully. Here are the items you selected:</p>
                             {$cart_html}
                             <p><a href='" . BASE_URL . "cart.php'>Click here to complete your purchase</a></p>
                             <p>Thanks,<br>Team ChineseDumps</p>";
    
            sendEmail($user_email, $subject_user, $message_user, $headers);
    
            // ----------------------------
            // Email to ADMIN
            // ----------------------------
            $admin_subject = "Chinese Dumps - Failed/Cancelled Order Detected";
            $admin_message = "<p>Hi Admin,</p>
                              <p>A user attempted to place an order but it failed/cancelled.</p>
                              <p><strong>User:</strong> {$user_name}<br>
                                 <strong>Email:</strong> {$user_email}<br>"
                                 . (!empty($user_phone) ? "<strong>Phone:</strong> {$user_phone}<br>" : "") .
                             "</p>
                              <p><strong>Cart Contents:</strong></p>
                              {$cart_html}";
                              
            // $admin_emails = [
            //     from_email(),
            //     'support@chinesedumps.com'
            // ];
            
            // $admin_emails = [
            //     'webdeveloper@nexgeno.in',
            //     'rashid.makent@gmail.com'
            // ];

            // $admin_emails = [from_email(), 'support@chinesedumps.com'];
            // foreach ($admin_emails as $admin_email) {
            //     sendEmail($admin_email, $admin_subject, $admin_message, $headers);
            // }
                sendEmail(from_email(), $admin_subject, $admin_message, $headers);
            
            // Mark the email as sent
            $_SESSION['email_sent'] = true;
            
            // echo "<div style='color:red; text-align:center; font-weight:bold;'>Your order was cancelled or failed. We've emailed you the details.</div>";
    
        } else {
            // echo "<div style='color:red; text-align:center;'>User details not found.</div>";
        }
    
        $mysqli->close();
    } else {
        // echo "Invalid or corrupted user ID.";
    }
} else {
    // echo "No user ID provided.";
}

?>
<?php
if (!isset($_SESSION['uid'])) {
    header("Location: login.html");
    exit();
}
?>
<? include("includes/header.php");?>

<style>

.text {

font-size: 12px !important;

font-family: Arial, Helvetica, sans-serif;

color: #000000;

}

</style>

<script language="JavaScript" type="text/javascript" src="js/leftmenu.js"></script>

<script type="text/javascript">

function pageChecktout()

{



	/*if (document.frm_cart.payoption[0].checked==false && document.frm_cart.payoption[1].checked==false)

	{ 

		alert("Select payment method to order "); 

		document.frm_cart.payoption[0].focus();	

		return false;

	}

	

	

	if(document.frm_cart.payoption[1].checked==true){

	var i=document.frm_cart.payoption[1].value;

	window.location='paypal_payment.html';

	}

	

	if(document.frm_cart.payoption[0].checked==true){

	var i=document.frm_cart.payoption[0].value;

	window.location='swreg.html';

	}*/

	

	if(document.frm_cart.payoption.checked==true){

	var i=document.frm_cart.payoption.value;

	window.location='paypal_payment.html';

	}else{

		var i=document.frm_cart.payoption.value;

	window.location='paypal_payment.html';

	}







    }

</script>

<style type="text/css">

<!--

.style2 {	font-family: Arial, Helvetica, sans-serif;

	color: #004A6F;

}

.style3 {color: #CC0000}

.style4 {color: #000000}

-->
li.item-quantity a {
    display: none !important;
}
</style>

<div class="content-box">

        <div class="certification-box certification-box03">

            <div class="max-width">

                <a href="<?php echo $websiteURL; ?>" class="blutext">Home</a> > <span class="blutext"><?php echo $getPage[1]; ?>

            </div>

        </div>

        <div class="about-chinesedumps">

            <div class="max-width">
                  <div class="main_heading text-center">Shopping <span>Cart Summary </span></div>

                <div class="black-heading">You are going to purchase the following exams</div>

                <div class="shopping-cart-main">

                    <div class="shopping-cart">

                        <ul class="cart-list-ver text-center">

                        <form name="frm_cart" id="frm_cart" action="" method="post" enctype="multipart/form-data">     

						<span  id="showallpro">

                        <?=$show_cat?>

						</span>

                        </form>

                        </ul>

                        

                                                    

                    </div>

                </div>

            </div>

        </div>

        <div class="exam-list-box we-accept">

            <div class="max-width paddbottom60 ">

                 <div class="main_heading text-center">We Accept</div>

                <div class="black-heading">Your purchase with Chinesedumps is safe and fast.</div>

                <p>Purchases on ourwesbite is handled by 3rd party Payment Gateway and it is 256-bit SSL Secured and your personal and financial information will not be disclosed to anyone.

ChineseDumps.com does not store any of your financial information.</p>

                <ul class="we-accept-icons">

                    <li><img src="images/paymeny-01.png" alt="" width="102" /></li>

                    <li><img src="images/paymeny-03.png" alt="" width="47" /></li>

                    <li><img src="images/paymeny-04.png" alt="" width="77" /></li>

                    <li><img src="images/paymeny-05.png" alt="" width="70" /></li>

                    <li><img src="images/paymeny-06.png" alt="" width="85" /></li>

                    <li><img src="images/paymeny-07.png" alt="" width="50" /></li>

                    <li><img src="images/paymeny-09.png" alt="" width="70" /></li>

                    <li><img src="images/paymeny-10.png" alt="" width="70" /></li>

                </ul>

        	</div>

        </div>

	</div>

              <? include("includes/footer.php");?>
