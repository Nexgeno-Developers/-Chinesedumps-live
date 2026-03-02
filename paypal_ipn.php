<?php
/**
 * paypal_ipn.php
 * PHP 5.6 compatible PayPal IPN listener with email notification on success or failure.
 */
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
global $fromEmail;

// === Configuration ===
define('PAYPAL_MODE', 'live'); // 'live' or 'sandbox'
// Your notification email address
// define('IPN_NOTIFY_EMAIL', 'umair.makent@gmail.com');
// Log file path
define('IPN_LOG_FILE', __DIR__ . '/ipn_log.txt');

// === Initialization ===
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Read raw POST data from PayPal
$raw_post = file_get_contents('php://input');
$raw_array = explode('&', $raw_post);
$post_data = [];
foreach ($raw_array as $kv) {
    list($key, $value) = explode('=', $kv);
    $post_data[$key] = urldecode($value);
}

// Build validation request
$req = 'cmd=_notify-validate';
foreach ($post_data as $key => $value) {
    $req .= "&{$key}=" . urlencode($value);
}

// Choose PayPal URL based on mode
if (PAYPAL_MODE === 'live') {
    $paypal_url = 'https://ipnpb.paypal.com/cgi-bin/webscr';
} else {
    $paypal_url = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
}

// Post back to PayPal for verification using cURL
$ch = curl_init($paypal_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
// SSL options
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
// Close connection when done
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: Close']);

$verified_response = curl_exec($ch);
$curl_errno = curl_errno($ch);
$curl_error = curl_error($ch);
curl_close($ch);

// Logging function
function log_ipn($status, $raw, $response) {
    date_default_timezone_set('Asia/Kolkata');
    $time = date('m/d/Y h:i A');
    $text = "[{$time}] - {$status}\n";
    $text .= "Raw POST: {$raw}\n";
    $text .= "PayPal Response: {$response}\n";
    $text .= str_repeat('-', 60) . "\n";
    file_put_contents(IPN_LOG_FILE, $text, FILE_APPEND);
}
// Prepare email
// $to = IPN_NOTIFY_EMAIL;
// $headers = 'From: no-reply@' . ($_SERVER['HTTP_HOST'] ?: 'yourdomain.com') . "\r\n";

// // Check cURL errors
if ($curl_errno || ! $verified_response) {
//     $subject = 'PayPal IPN cURL Error';
//     $message = "cURL error ({$curl_errno}): {$curl_error}\nRaw POST: {$raw_post}";
//     mail($to, $subject, $message, $headers);
    log_ipn('CURL_ERROR', $raw_post, $curl_error);
    exit;
}

// Handle verification outcome
if (stripos($verified_response, 'VERIFIED') !== false) {
		
    // Payment data
    $txn_id = isset($post_data['txn_id']) ? $post_data['txn_id'] : '';
    $payment_status = isset($post_data['payment_status']) ? $post_data['payment_status'] : '';

    $firstName = isset($post_data['first_name']) ? $post_data['first_name'] : '';
    $lastName  = isset($post_data['last_name']) ? $post_data['last_name'] : '';
    $buyerName = $firstName . ' ' . $lastName;
    // $email = 'umair.makent@gmail.com';
    $email     = isset($post_data['payer_email']) ? $post_data['payer_email'] : '';
    
    $address   = (isset($post_data['address_street']) ? $post_data['address_street'] : '') . ', ' .
                 (isset($post_data['address_city']) ? $post_data['address_city'] : '') . ', ' .
                 (isset($post_data['address_state']) ? $post_data['address_state'] : '') . ' ' .
                 (isset($post_data['address_zip']) ? $post_data['address_zip'] : '') . ', ' .
                 (isset($post_data['address_country_code']) ? $post_data['address_country_code'] : '');
    
    $paymentId      = isset($post_data['txn_id']) ? $post_data['txn_id'] : '';
    $paymentAmount  = isset($post_data['mc_gross']) ? $post_data['mc_gross'] : '';
    $paymentStatus  = isset($post_data['payment_status']) ? $post_data['payment_status'] : '';
    $paymentDateRaw = isset($post_data['payment_date']) ? $post_data['payment_date'] : '';
    $paymentDate    = !empty($paymentDateRaw) ? date("F j, Y, g:i a", strtotime($paymentDateRaw)) : '';
    
    // Send success/failure based on payment_status
    if ($payment_status === 'Completed') {
        // $subject = 'PayPal IPN Payment Completed';
        // $message = "Transaction ID: {$txn_id}\nStatus: COMPLETED\nFull Data: {$raw_post}";
        // mail($to, $subject, $message, $headers);
        
        // Prepare email content
        $adminMsg = '
        <p>Hi Admin,</p>
        <p>A new successful payment has been processed. Details are as follows:</p>
        <p><strong>Transaction ID:</strong> ' . $paymentId . '<br>
        <strong>Amount Paid:</strong> $' . $paymentAmount . ' ' . $post_data['mc_currency'] . '<br>
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
        
        $numItems = (int)$post_data['num_cart_items'];
        for ($i = 1; $i <= $numItems; $i++) {
            $itemName = $post_data["item_name$i"];
            $itemQty = $post_data["quantity$i"];
            $itemPrice = $post_data["mc_gross_$i"];
        
            $adminMsg .= "<tr>
                            <td style='padding: 10px; border: 1px solid #ddd;'>$i</td>
                            <td style='padding: 10px; border: 1px solid #ddd;'>$itemName</td>
                            <td style='padding: 10px; border: 1px solid #ddd;'>$itemQty</td>
                            <td style='padding: 10px; border: 1px solid #ddd;'>$$itemPrice</td>
                        </tr>";
        }
        
       
        $adminMsg .= '</tbody></table>';

        
        // $adminEmail = 'webdeveloper@nexgeno.in'; // Replace with the admin's email address sales@chinesedumps.com
        $adminEmail = $fromEmail; // Replace with the admin's email address sales@chinesedumps.com
        // var_dump($adminEmail);
        // $adminEmail2 = 'support@chinesedumps.com'; // Replace with the admin's email address
        
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
        $headers .= "From: Chinese Dumps <support@chinesedumps.com>\r\n";
        // $headers .= 'From: ' . $email . "\r\n";
        
        $subject = 'Chinese Dumps Product Payment Success Notification ';
        
        // Send email to the admin
        sendEmail($adminEmail, $subject, $adminMsg, $headers);
        // sendEmail($adminEmail2, $subject, $adminMsg, $headers);
                
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
                   . ' ' . htmlspecialchars($post_data['mc_currency'])
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
        
        // $numItems = (int)$post_data['num_cart_items'];
        $numItems = isset($post_data['num_cart_items']) ? (int)$post_data['num_cart_items'] : 0;
        for ($i = 1; $i <= $numItems; $i++) {
            $itemName  = htmlspecialchars($post_data["item_name{$i}"]);
            $itemQty   = (int)$post_data["quantity{$i}"];
            // mc_gross_i is total for that line; if you need per‐unit price, divide by qty
            $itemTotal = number_format((float)$post_data["mc_gross_{$i}"], 2);
        
            $buyerMsg .= '<tr>'
                       . '<td style="padding:8px; border:1px solid #ddd;">' . $i . '</td>'
                       . '<td style="padding:8px; border:1px solid #ddd;">' . $itemName . '</td>'
                       . '<td style="padding:8px; border:1px solid #ddd;">' . $itemQty . '</td>'
                       . '<td style="padding:8px; border:1px solid #ddd;">$' . $itemTotal . '</td>'
                       . '</tr>';
        }
        
        $buyerMsg .= '</tbody></table>';
        
        // $buyerMsg .= '<p>Your order is now being processed and you will receive a shipping confirmation soon.</p>';
        $buyerMsg .= '<p>If you have any questions, just reply to this email or reach out to us at support@chinesedumps.com.</p>';
        $buyerMsg .= '<p>Best regards,<br>The Chinese Dumps Team</p>';
        
        $buyerHeaders  = "MIME-Version: 1.0\r\n";
        $buyerHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";
        $buyerHeaders .= "From: Chinese Dumps <support@chinesedumps.com>\r\n";
        
        // ———————— NEW: clear out this user’s cart ————————
        // If you store carts in DB linked to user ID:
        // if (!empty($UserId)) {
        //     // Make sure your classCart has a method like this:
        //     $objCart->clearCartByUser($UserId);
        // }
        
        sendEmail($email, $buyerSubject, $buyerMsg, $buyerHeaders);
        
        log_ipn('SUCCESS - VERIFIED_COMPLETED', $raw_post, 'VERIFIED');
        
        // — right before you decode pid, make sure you have these:
        $dayedate = date('Y-m-d');
        $txn_id   = isset($_POST['txn_id']) 
                    ? mysql_real_escape_string($_POST['txn_id']) 
                    : '';
        
        // decode the “pid” you passed in the notify_url
        $pid     = isset($_GET['pid']) 
                    ? $_GET['pid'] 
                    : '';
        $SentNo  = base64_decode($pid);
        $parts   = explode('-', $SentNo, 2);
        $UserId  = mysql_real_escape_string($parts[0]);
        $CartId  = mysql_real_escape_string($parts[1]);
        
        // look up the user
        $q    = "SELECT user_fname, user_lname 
                  FROM tbl_user 
                 WHERE user_id = '{$UserId}' 
                 LIMIT 1";
        $rs1  = mysql_query($q);
        if (! $rs1) {
            error_log("IPN user lookup failed: " . mysql_error());
            exit;
        }
        $row1     = mysql_fetch_assoc($rs1);
        $Username = $row1['user_fname'] . ' ' . $row1['user_lname'];
        
        // look up the cart master
        $qy_cart_master = "SELECT ID, Net_Amount, OrderDesc
                              FROM temp_master
                             WHERE Cust_ID = '{$UserId}' 
                             LIMIT 1";
        $r_cm           = mysql_query($qy_cart_master);
        if (! $r_cm) {
            error_log("IPN cart_master lookup failed: " . mysql_error());
            exit;
        }
        $r_cart_master = mysql_fetch_assoc($r_cm);
        
        // Only proceed if they paid at least the Net_Amount
        $paid = isset($_POST['mc_gross'])
                  ? floatval($_POST['mc_gross'])
                  : 0;
        if ($r_cart_master['Net_Amount'] <= $paid) {
        
            // 1) insert into order_master
            $qry_temp = "
              INSERT INTO order_master
                (Cust_ID, Order_Id, OrderDate, Status, Net_Amount, Cart_ID, OrderDesc)
              VALUES
                ('{$UserId}', '{$txn_id}', '{$dayedate}', 1,
                 '{$r_cart_master['Net_Amount']}', '{$CartId}', 
                 '" . mysql_real_escape_string($r_cart_master['OrderDesc']) . "')
            ";
            if (! mysql_query($qry_temp)) {
                error_log("IPN insert order_master failed: " . mysql_error());
                exit;
            }
            $lastordid = mysql_insert_id();
        
            // 2) update any coupons
            $update_coupon = "
              UPDATE coupon_order
                 SET order_id   = '{$lastordid}',
                     order_type = '1'
               WHERE order_id   = '{$r_cart_master['ID']}'
                 AND order_type = '0'
            ";
            mysql_query($update_coupon);  // you can add error_log here if you like
        
            // 3) fetch each temp_order row and insert order_detail
            $qy_cart = "SELECT * FROM temp_order WHERE UserID = '{$UserId}'";
            $r_cart  = mysql_query($qy_cart);
            if (! $r_cart) {
                error_log("IPN temp_order lookup failed: " . mysql_error());
                exit;
            }
        
            // pre-compute one expire date
            $Expiredate = date('Y-m-d', strtotime('+12 days'));
        
            while ($row_cart = mysql_fetch_assoc($r_cart)) {
                $price_one = floatval($row_cart['Price']);
                $quantity  = intval($row_cart['Quantity']);
        
                $query_order = "
                  INSERT INTO order_detail
                    (UserID, masterid, Cart_ID, VendorID, TrackID,
                     ProductID, Description, ExpireDate, strdate,
                     submonth, status, PTypeID)
                  VALUES
                    ('{$UserId}', '{$lastordid}', '{$row_cart['CartID']}',
                     '{$row_cart['VendorID']}', '{$row_cart['TrackID']}',
                     '{$row_cart['Pid']}', '" . mysql_real_escape_string($row_cart['Product']) . "',
                     '{$Expiredate}', '{$dayedate}', '{$row_cart['subscrip']}',
                     1, '{$row_cart['PType']}')
                ";
                if (! mysql_query($query_order)) {
                    error_log("IPN insert order_detail failed: " . mysql_error());
                    // decide whether to continue or exit
                }
        
                // (your Test-Engine logic unchanged…)
                // …
        
                // Build loops for email:
                $priceloop[]     = $price_one;
                $itemloopquan[]  = $quantity;
                // name logic unchanged…
            }
                    
            // 4) clean up temp tables *using* $UserId, not $_SESSION
            mysql_query(
                "DELETE FROM temp_order  WHERE UserID = '" . mysql_real_escape_string($UserId) . "'"
            ) or error_log("IPN cleanup temp_order failed: " . mysql_error());
            
            mysql_query(
                "DELETE FROM temp_master WHERE Cust_ID = '" . mysql_real_escape_string($UserId) . "'"
            ) or error_log("IPN cleanup temp_master failed: " . mysql_error());
            
            exit;
        }
        else {
            // under-payment: log or alert
            error_log("IPN underpayment: UserID={$UserId}, paid={$paid}, due={$r_cart_master['Net_Amount']}");
            exit;
        }

    } else {
        // $subject = 'PayPal IPN Payment Not Completed';
        // $message = "Transaction ID: {$txn_id}\nStatus: {$payment_status}\nFull Data: {$raw_post}";
        // mail($to, $subject, $message, $headers);
        
        
        
        log_ipn('FAIL - VERIFIED_NOT_COMPLETED', $raw_post, $verified_response);
    }
} else {
    // Invalid response
    $subject = 'PayPal IPN Invalid Response';
    $message = "Invalid IPN: Response: {$verified_response}\nRaw POST: {$raw_post}";
    sendEmail($to, $subject, $message, $headers);
    // mail($to, $subject, $message, $headers);
    log_ipn('INVALID', $raw_post, $verified_response);
    exit;
}

// Done
exit;
?>
