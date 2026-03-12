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



$getPage	=	$objMain->getContent(13);

$dayedate	=	date('Y-m-d');



    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');

	$exec	=	mysql_fetch_array($quer);

	

	$copyright	=	$exec['copyright'];

	$firstlink	=	" ".$getPage[1];

// global $fromEmail;

// // Initialize email sent flag
// if (!isset($_SESSION['email_sent'])) {
//     $_SESSION['email_sent'] = false; // Flag to track if the email has been sent
// }

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

if (isset($_GET['uid'])) {
    $decrypted_user_id = decrypt_data($_GET['uid']);
    if ($decrypted_user_id) {
        // echo "Welcome back, user ID: " . htmlspecialchars($decrypted_user_id);
        // Optional: restore session
        $_SESSION['uid'] = $decrypted_user_id;
    }
}		



?>

<? include("includes/header.php");?>

<style type="text/css">

<!--

.style2 {	font-family: Arial, Helvetica, sans-serif;

	color: #004A6F;

}

.style3 {color: #CC0000}

.style4 {color: #000000}

-->


.more_infromation li {
    list-style: none;
    float: left;
    margin: 0px 10px;
}

.more_infromation {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-bottom: 32px;
    margin-top: -19px;
}

.more_infromation li img {
    width: 80px;
}
h4.connect_heading {
    text-align: center;
    margin-bottom: 40px;
    font-size: 24px;
    font-weight: 600;
}
.more_infromation li i {
    font-size: 40px;
    border: 1px solid #3c85ba;
    border-radius: 81px;
    height: 60px;
    width: 60px;
    text-align: center;
    padding-top: 9px;
}


</style>
<div class="container">
<tr>

                <td valign="top" colspan="2"><table width="100%" height="180" border="0" align="center" cellpadding="0" cellspacing="0">

                  

                  <tr>

                    <td width="12">&nbsp;</td>

                    <td height="146" align="left" valign="top">

                        <table width="100%" border="0" cellspacing="0" cellpadding="0">

                          


                          <tr>

                            <td align="left"><div>

                                <div>

                                  <h2 class="headingb text-center"><?php echo $getPage[1]; ?></h2>
                                  <br>

                                </div>

                            </div></td>

                          </tr>

                          <tr>

                            <td class=""><?php echo $getPage[2]; ?></td>

                          </tr>

                        </table>

                      <p class="style2">&nbsp;</p></td>

                    <td width="11">&nbsp;</td>

                  </tr>

                </table>

                </td>

                </tr>
                
                </div>
                
                <h4 class="connect_heading">Connect with Us</h4>
                
               <div class="more_infromation">
                   
                   </br>
                   <div class="">
                       
                       <li><a target="_blank" href="https://api.whatsapp.com/send/?phone=447591437400&amp;text&amp;type=phone_number&amp;app_absent=0&amp;text=Hi, I am contacting you through your website <?php echo BASE_URL; ?>." data-toggle="tooltip" data-placement="bottom" title="Whatsapp"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></li>
                       <li><a href="tel:+44 7591 437400"><i class="fa fa-phone" aria-hidden="true"></i></a></li>
                       <!--<li><a target="_blank" href="https://api.whatsapp.com/send/?phone=19513764336&amp;text&amp;type=phone_number&amp;app_absent=0&amp;text=Hi, I am contacting you through your website <?php echo BASE_URL; ?>." data-toggle="tooltip" data-placement="bottom" title="Whatsapp"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></li>-->
                       <!--<li><a href="tel:+1 951-376-4336"><i class="fa fa-phone" aria-hidden="true"></i></a></li>-->
                   </div>
               </div>
               
                <div class="container mt-5 mb-5">
                    
                    <div class="card shadow p-4">
                
                        <hr>
                
                        <?php
                            // Assume PayPal data came via POST or GET
                            $data = $_POST ?: $_GET;
                
                            // Buyer Info
                            // $buyerName = $data['first_name'] . ' ' . $data['last_name'];
                            // $email = 'umair.makent@gmail.com';
                            // // $email = $data['payer_email'];
                            // $address = $data['address_street'] . ', ' . $data['address_city'] . ', ' . $data['address_state'] . ' ' . $data['address_zip'] . ', ' . $data['address_country_code'];
                            // $paymentId = $data['txn_id'];
                            // $paymentAmount = $data['mc_gross'];
                            // $paymentStatus = $data['payment_status'];
                            // $paymentDate = date("F j, Y, g:i a", strtotime($data['payment_date']));
                            
                            $firstName = isset($data['first_name']) ? $data['first_name'] : '';
                            $lastName  = isset($data['last_name']) ? $data['last_name'] : '';
                            $buyerName = $firstName . ' ' . $lastName;
                            // $email = 'umair.makent@gmail.com';
                            $email     = isset($data['payer_email']) ? $data['payer_email'] : '';
                            
                            $address   = (isset($data['address_street']) ? $data['address_street'] : '') . ', ' .
                                         (isset($data['address_city']) ? $data['address_city'] : '') . ', ' .
                                         (isset($data['address_state']) ? $data['address_state'] : '') . ' ' .
                                         (isset($data['address_zip']) ? $data['address_zip'] : '') . ', ' .
                                         (isset($data['address_country_code']) ? $data['address_country_code'] : '');
                            
                            $paymentId      = isset($data['txn_id']) ? $data['txn_id'] : '';
                            $paymentAmount  = isset($data['mc_gross']) ? $data['mc_gross'] : '';
                            $paymentStatus  = isset($data['payment_status']) ? $data['payment_status'] : '';
                            $paymentDateRaw = isset($data['payment_date']) ? $data['payment_date'] : '';
                            $paymentDate    = !empty($paymentDateRaw) ? date("F j, Y, g:i a", strtotime($paymentDateRaw)) : '';
                            
                            // If payment status is successful and email hasn't been sent yet
                            /*if ($paymentStatus == 'Completed' && !$_SESSION['email_sent']) {
                    
                                // Prepare email content
                                $adminMsg = '
                                <p>Hi Admin,</p>
                                <p>A new successful payment has been processed. Details are as follows:</p>
                                <p><strong>Transaction ID:</strong> ' . $paymentId . '<br>
                                <strong>Amount Paid:</strong> $' . $paymentAmount . ' ' . $data['mc_currency'] . '<br>
                                <strong>Buyer Name:</strong> ' . htmlspecialchars($buyerName) . '<br>
                                <strong>Buyer Email:</strong> ' . htmlspecialchars($email) . '<br>
                                <strong>Buyer Address:</strong> ' . htmlspecialchars($address) . '<br>
                                <strong>Payment Date:</strong> ' . $paymentDate . '</p>';
                                
                                $adminMsg .= '<p><strong>Purchased Items:</strong></p>
                                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                                    <thead style="background-color: #343a40; color: white;">
                                        <tr>
                                            <th style="padding: 10px; border: 1px solid #ddd;">#</th>
                                            <th style="padding: 10px; border: 1px solid #ddd;">Item Name</th>
                                            <th style="padding: 10px; border: 1px solid #ddd;">Quantity</th>
                                            <th style="padding: 10px; border: 1px solid #ddd;">Price (USD)</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                
                                $numItems = (int)$data['num_cart_items'];
                                for ($i = 1; $i <= $numItems; $i++) {
                                    $itemName = $data["item_name$i"];
                                    $itemQty = $data["quantity$i"];
                                    $itemPrice = $data["mc_gross_$i"];
                                
                                    $adminMsg .= "<tr>
                                                    <td style='padding: 10px; border: 1px solid #ddd;'>$i</td>
                                                    <td style='padding: 10px; border: 1px solid #ddd;'>$itemName</td>
                                                    <td style='padding: 10px; border: 1px solid #ddd;'>$itemQty</td>
                                                    <td style='padding: 10px; border: 1px solid #ddd;'>$$itemPrice</td>
                                                </tr>";
                                }
                                
                               
                                $adminMsg .= '</tbody></table>';

                                
                                // $adminEmail = 'webdeveloper@nexgeno.in';
                                $adminEmail = $fromEmail;
                                // var_dump($adminEmail);
                                $adminEmail2 = 'support@chinesedumps.com'; // Replace with the admin's email address
                                
                                $headers = 'MIME-Version: 1.0' . "\r\n";
                                $headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
                                $headers .= "From: Chinese Dumps <support@chinesedumps.com>\r\n";
                                // $headers .= 'From: ' . $email . "\r\n";
                                
                                $subject = 'Chinese Dumps Product Payment Success Notification ';
                                
                                // Send email to the admin
                                sendEmail($adminEmail, $subject, $adminMsg, $headers);
                                sendEmail($adminEmail2, $subject, $adminMsg, $headers);
                                
                                
                                // 2) Send to Buyer
                                $buyerSubject = 'Thank you for your order at Chinese Dumps!';
                                
                                $buyerMsg  = '<p>Dear ' . htmlspecialchars($buyerName) . ',</p>';
                                
                                $buyerMsg .= "<p><strong>Thank You for Your Purchase!</strong></p>
                                        <p>We sincerely appreciate your trust in our IT certification study materials. Your order has been successfully processed.</p>
                                        <p><strong>Important Update on Workbook Downloads</strong></p>
                                        <p>To ensure you receive the most up-to-date study materials / dumps, we have temporarily disabled the instant download option. IT certification exams are conducted online post 2021 and updated frequently—sometimes daily. This makes it challenging to keep our workbooks current if uploaded weeks or months in advance. Our priority is to provide you with the latest, most accurate materials to maximize your chances of success.</p>
                                        <p><strong>How to Get Your Study Materials</strong></p>
                                        <p>We offer two convenient options to access your purchased workbook:</p>
                                        <ul>
                                            <li><strong>Wait for Our Team to Reach Out:</strong> Within 24 hours, one of our representatives will contact you to provide the latest version of your study material.</li>
                                            <li><strong>Need It Urgently?</strong> If you can’t wait 24 hours, please reach out to us directly:</li>
                                            <ul>
                                                <li><strong>Microsoft Teams:</strong> chinesexams@gmail.com (Available 16-20 hours daily)</li>
                                                <li><strong>WhatsApp:</strong> +44 7591 437400 (Available 16-20 hours daily)</li>
                                                <li><strong>Email:</strong> chinesexams@gmail.com (Response within 24 hours)</li>
                                            </ul>
                                        </ul>";
                                                   
                                $buyerMsg .= '
                                    <div style="margin-top: 20px;">
                                        <a href="https://wa.me/447591437400" style="display: inline-block; padding: 12px 20px; margin-right: 10px; font-size: 16px; color: #fff; background-color: #25D366; text-decoration: none; border-radius: 5px;">Contact via WhatsApp</a>
                                        <a href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjwE" style="display: inline-block; padding: 12px 20px; font-size: 16px; color: #fff; background-color: #464EB8; text-decoration: none; border-radius: 5px;">Contact via MS Teams</a>
                                    </div>
                                ';
                                
                                $buyerMsg .= "
                                       <p><strong>Please Don’t Panic!</strong></p>
                                       <p>If you don’t receive your workbook within 24 hours, there’s no need to open a PayPal dispute. We’re committed to your satisfaction and will happily issue a full refund if we’re unable to deliver your materials on time.</p>
                                       <p><strong>Additional Notes</strong></p>
                                       <ul>
                                          <li><strong>Check Your Spam/Junk Folder:</strong>Please check your spam or junk folder for our emails, including this confirmation and follow-up messages.</li>
                                          <li><strong>Save Your Order Details:</strong>Keep this email or your order number handy for quick reference when contacting us.</li>
                                          <li><strong>We Value Your Feedback:</strong>Once you receive your materials, we’d love to hear your thoughts! Reach out via email or WhatsApp.</li>
                                       </ul>
                                       <p>Thank you for choosing us to support your IT certification journey. We’re here to help you succeed!</p>";
                                       
                                $buyerMsg .= '<p>Thank you for shopping with us! We have received your payment of <strong>$'
                                           . number_format($paymentAmount, 2)
                                           . ' ' . htmlspecialchars($data['mc_currency'])
                                           . '</strong> (Transaction ID: <em>' . htmlspecialchars($paymentId) . '</em>).</p>';
                                
                                // Add order items table
                                $buyerMsg .= '<h4>Your Order Details</h4>';
                                $buyerMsg .= '<table style="width:100%; border-collapse: collapse; margin-bottom:20px;">'
                                           . '<thead style="background-color:#343a40; color:#fff;">'
                                           . '<tr>'
                                           . '<th style="padding:8px; border:1px solid #ddd;">#</th>'
                                           . '<th style="padding:8px; border:1px solid #ddd;">Item Name</th>'
                                           . '<th style="padding:8px; border:1px solid #ddd;">Quantity</th>'
                                           . '<th style="padding:8px; border:1px solid #ddd;">Price</th>'
                                           . '</tr>'
                                           . '</thead><tbody>';
                                
                                // $numItems = (int)$data['num_cart_items'];
                                $numItems = isset($data['num_cart_items']) ? (int)$data['num_cart_items'] : 0;
                                for ($i = 1; $i <= $numItems; $i++) {
                                    $itemName  = htmlspecialchars($data["item_name{$i}"]);
                                    $itemQty   = (int)$data["quantity{$i}"];
                                    // mc_gross_i is total for that line; if you need per‐unit price, divide by qty
                                    $itemTotal = number_format((float)$data["mc_gross_{$i}"], 2);
                                
                                    $buyerMsg .= '<tr>'
                                               . '<td style="padding:8px; border:1px solid #ddd;">' . $i . '</td>'
                                               . '<td style="padding:8px; border:1px solid #ddd;">' . $itemName . '</td>'
                                               . '<td style="padding:8px; border:1px solid #ddd;">' . $itemQty . '</td>'
                                               . '<td style="padding:8px; border:1px solid #ddd;">$' . $itemTotal . '</td>'
                                               . '</tr>';
                                }
                                
                                $buyerMsg .= '</tbody></table>';
                                
                                $buyerMsg .= '<p>Your order is now being processed and you will receive a shipping confirmation soon.</p>';
                                $buyerMsg .= '<p>If you have any questions, just reply to this email or reach out to us at support@chinesedumps.com.</p>';
                                $buyerMsg .= '<p>Best regards,<br>The Chinese Dumps Team</p>';
                                
                                $buyerHeaders  = "MIME-Version: 1.0\r\n";
                                $buyerHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";
                                $buyerHeaders .= "From: Chinese Dumps <support@chinesedumps.com>\r\n";
                                
                                // ———————— NEW: clear out this user’s cart ————————
                                // If you store carts in DB linked to user ID:
                                if (!empty($_SESSION['uid'])) {
                                    $userId = $_SESSION['uid'];
                                    // Make sure your classCart has a method like this:
                                    $objCart->clearCartByUser($userId);
                                }
                                
                                sendEmail($email, $buyerSubject, $buyerMsg, $buyerHeaders);

                                // Mark the email as sent
                                $_SESSION['email_sent'] = true;
                            }*/
                        ?>
                
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5>Buyer Information</h5>
                                <p><strong>Name:</strong> <?= $buyerName ?></p>
                                <p><strong>Email:</strong> <?= $email ?></p>
                                <p><strong>Address:</strong> <?= $address ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5>Payment Summary</h5>
                                <p><strong>Transaction ID:</strong> <?= $paymentId ?></p>
                                <p><strong>Amount Paid:</strong> $<?= $paymentAmount ?> <?= $data['mc_currency'] ?></p>
                                <p><strong>Status:</strong> <?= $paymentStatus ?></p>
                                <p><strong>Date:</strong> <?= $paymentDate ?></p>
                            </div>
                        </div>
                
                        <hr>
                        
                        <h5>Purchased Items</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Price (USD)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $numItems = (int)$data['num_cart_items'];
                                    for ($i = 1; $i <= $numItems; $i++) {
                                        $itemName = $data["item_name$i"];
                                        $itemQty = $data["quantity$i"];
                                        $itemPrice = $data["mc_gross_$i"];
                                        echo "<tr>
                                                <td>$i</td>
                                                <td>$itemName</td>
                                                <td>$itemQty</td>
                                                <td>$$itemPrice</td>
                                              </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-center">
                            <a href="/" class="btn btn-primary">Continue Shopping</a>
                        </div>
                        <br>
                    </div>
                </div>
                <? include("includes/footer.php");?>
