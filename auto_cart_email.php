<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1); // Log errors to a file


// ---------------------------------------------------------------------
// CONFIGURATION & DATABASE SETUP
// ---------------------------------------------------------------------
include("includes/config/classDbConnection.php");
include("functions.php");
$objDBcon   = new classDbConnection;

// Create MySQLi connection using the properties from classDbConnection
$mysqli = new mysqli($objDBcon->dbHost, $objDBcon->dbUser, $objDBcon->dbPass, $objDBcon->dbName);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// ---------------------------------------------------------------------
// CONFIG: Maximum number of notification attempts allowed and cutoff time
// ---------------------------------------------------------------------
$max_notify_attempts = 3; // Adjust the maximum attempts as needed
$cutoff = date('Y-m-d H:i:s', strtotime('-24 hour'));

// For demonstration, we assign these globals as they are used in your cart page.
global $fromEmail;
global $base_path;
// $fromEmail = "sales@chinesedumps.com";         // You may also retrieve this from your configuration
// $base_path  = "https://updated.chinesedumps.com/";

// ---------------------------------------------------------------------
// SELECT carts from temp_order for abandoned carts
// Only select carts with a valid user (UserID != 0) and with notify_attempt < max,
// where the cart Date is older than the cutoff. Group by CartID to process one cart per user.
// ---------------------------------------------------------------------
$query = "SELECT CartID, UserID, MIN(created_at) AS cart_date 
          FROM temp_order 
          WHERE notify_attempt < ? 
            AND STR_TO_DATE(created_at, '%Y-%m-%d %H:%i:%s') < ? 
            AND UserID != 0 
          GROUP BY CartID
          ORDER BY ID DESC";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("is", $max_notify_attempts, $cutoff);
$stmt->execute();
$result = $stmt->get_result();

var_dump($result->fetch_all(MYSQLI_ASSOC));
exit();

// $adminEmail = "webdeveloper@nexgeno.in";  // Admin notification email
// $adminEmail = "webdeveloper@nexgeno.in";  // Admin notification email
// $adminEmail2 = "umair.makent@gmail.com";  // Admin notification email
$adminEmail = "sales@chinesedumps.com";  // Admin notification email
// $adminEmail2 = "support@chinesedumps.com";  // Admin notification email

function getdbPrice($pCode,$duration)

	{

	$strSql 	=	"SELECT * FROM product_prices WHERE pri_type='".$pCode."' and pri_duration='".$duration."'";

    $result		=	mysql_query($strSql);

	$re 		=	mysql_fetch_array($result);

	return $re;

	}
	
function getUserDetails($userID, $mysqli) {
    $stmt = $mysqli->prepare("SELECT user_email, user_fname, user_lname, user_phone FROM tbl_user WHERE user_id = ?");
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
			$setPrice = getdbPrice($ptype,$duration);
			$setPrice = $setPrice["pri_value"]+$price_plus;
		}
	}elseif($ptype == "sp"){
		if($re["engn_pri3"] != "" && $re["engn_pri3"] != "0.00"){
			$setPrice = $re["engn_pri3"];
		}else{
			$setPrice = getdbPrice($ptype,$duration);
			$setPrice = $setPrice["pri_value"]+$price_plus;
		}
	}elseif($ptype == "9"){
		if($re["both_pri"] != "" && $re["both_pri"] != "0.00"){
			$setPrice = $re["both_pri"];
		}else{
			$setPrice = getdbPrice($ptype,$duration);
			$setPrice = $setPrice["pri_value"]+$price_plus;
		}
	}elseif($ptype == "8"){
		if($re["engn_pri3"] != "" && $re["engn_pri3"] != "0.00"){
			$setPrice = $re["engn_pri3"];
		}else{
			$setPrice = getdbPrice($ptype,$duration);
			$setPrice = $setPrice["pri_value"]+$price_plus;
		}
	}elseif($ptype == "7"){
		if($re["exam_pri3"] != "" && $re["exam_pri3"] != "0.00"){
			$setPrice = $re["exam_pri3"];
		}else{
			$setPrice = getdbPrice($ptype,$duration);
			$setPrice = $setPrice["pri_value"]+$price_plus;
		}
	}else{
		if($re["exam_pri3"] != "" && $re["exam_pri3"] != "0.00"){
			$setPrice = $re["exam_pri3"];
		}else{
			$setPrice = getdbPrice($ptype,$duration);
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
    

// ---------------------------------------------------------------------
// PROCESS EACH ABANDONED CART
// ---------------------------------------------------------------------

while ($row = $result->fetch_assoc()) {
    $cartID    = $row['CartID'];
    // $userID    = 109276;
    $userID    = $row['UserID'];
    $cart_date = $row['cart_date'];
    
    // Retrieve all items in this cart (ordered in reverse by primary key)
    $query2 = "SELECT * FROM temp_order WHERE UserID = ? AND notify_attempt < ? ORDER BY ID DESC";
    $stmt2  = $mysqli->prepare($query2);
    $stmt2->bind_param("si", $userID, $max_notify_attempts);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    // Call showd_cart and pass the result set
    $show_cart = showd_cart($result2);
    // echo $show_cart;
    // exit();
    
    // ---------------------------------------------------------------------
    // Email Headers Configuration
    // ---------------------------------------------------------------------
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $headers .= "From: chinesedumps <" . $fromEmail . ">\r\n";


    if ($userID != 0) {
        
        // Fetch user details from the database
        $userDetails = getUserDetails($userID, $mysqli);
            
        // echo '<pre>';
        // var_dump($userDetails);
        // echo '</pre>';
        // exit();

        // Check if user details are found
        if ($userDetails) {
            // Extract details from the returned array
            $userPhone     = isset($userDetails['user_phone']) ? $userDetails['user_phone'] : '';
            $userEmail     = isset($userDetails['user_email']) ? $userDetails['user_email'] : '';
            $userFirstName = isset($userDetails['user_fname']) ? $userDetails['user_fname'] : '';
            $userLastName  = isset($userDetails['user_lname']) ? $userDetails['user_lname'] : '';
            $fullName = trim($userFirstName . ' ' . $userLastName);
            
            // --------------------------------------------------
            // Build HTML Email for the Customer
            // --------------------------------------------------
            $customerMsg = '<p>Hi ' . htmlspecialchars($fullName) . ',</p>
            <p>You left the following items in your cart:</p>
            ' . 
            $show_cart 
            . '
            <p><a href="'.$base_path.'cart.php">Click here to return to your cart</a></p>';
            if ($userEmail) {
                // $userEmail = 'webdeveloper@nexgeno.in';
                // var_dump($userEmail);
                // @mail($userEmail, "Reminder – Abandoned Cart", $customerMsg, $headers);
                sendEmail($userEmail, "Reminder - Abandoned Cart", $customerMsg, $headers);
            }
            // exit();
        } else {
            // If no user found, handle the error accordingly
            echo "User not found!";
            // exit;
        }
    }

    // --------------------------------------------------
    // Build HTML Email for the Admin
    // --------------------------------------------------
    // $adminMsg = '<p>Hi Admin,</p>
    // <p>An abandoned cart has been detected. Details are as follows:</p>
    // <p><strong>Cart Date:</strong> ' . date("F j, Y, g:i a", strtotime($cart_date)) . '<br>
    // <strong>User Name:</strong> ' . htmlspecialchars($fullName) . '<br>
    // <strong>User Email:</strong> ' . $userEmail . '<br>
    // <p><strong>Product details:</strong></p>
    // ' .
    // $show_cart;
    
    $adminMsg = '<p>Hi Admin,</p>
    <p>An abandoned cart has been detected. Details are as follows:</p>
    <p><strong>Cart Date:</strong> ' . date("F j, Y, g:i a", strtotime($cart_date)) . '<br>
    <strong>User Name:</strong> ' . htmlspecialchars($fullName) . '<br>
    <strong>User Email:</strong> ' . $userEmail . '<br>';
    
    if (!empty($userPhone)) {
        $adminMsg .= '<strong>User Phone:</strong> ' . htmlspecialchars($userPhone) . '<br>';
    }
    
    $adminMsg .= '</p><p><strong>Product details:</strong></p>' . $show_cart;

    sendEmail($adminEmail, "Reminder - Abandoned Cart", $adminMsg, $headers);
    sendEmail($adminEmail2, "Reminder - Abandoned Cart", $adminMsg, $headers);
    // @mail($adminEmail, "Reminder – Abandoned Cart", $adminMsg, $headers);
    // @mail($adminEmail2, "Reminder – Abandoned Cart", $adminMsg, $headers);
    
    // ---------------------------------------------------------------------
    // Debug output for emails (var_dump before actually sending)
    // ---------------------------------------------------------------------
    
    /*
    if ($userID != 0) {
        // $custEmail = getUserEmail($userID, $mysqli);
        $custEmail = $userEmail;
        if ($custEmail) {
            echo "<h2>DEBUG: Email to Customer</h2>";
            var_dump([
                'to'      => $custEmail,
                'subject' => "Reminder – Abandoned Cart",
                'headers' => $headers,
                'message' => $customerMsg
            ]);
        }
    }

    echo "<h2>DEBUG: Email to Admin</h2>";
    var_dump([
        'to'      => $adminEmail,
        'subject' => "Reminder – Abandoned Cart",
        'headers' => $headers,
        'message' => $adminMsg
    ]);

    // Optionally display cart info too:
    echo "<h2>DEBUG: Cart Info</h2>";
    var_dump([
        'CartID'    => $cartID,
        'UserID'    => $userID,
        'Cart Date' => $cart_date,
        'Total Price' => $totalPrice,
    ]);
    */
    
    // Stop execution here so no emails are actually sent.
    // exit();
    

    // ---------------------------------------------------------------------
    // Update the notification fields for items in this cart.
    // Increase notify_attempt by 1 and set notify_date_time to the current time.
    // ---------------------------------------------------------------------
    $updateQuery = "UPDATE temp_order 
                    SET notify_attempt = notify_attempt + 1, 
                        notify_date_time = NOW() 
                    WHERE CartID = ?";
    $stmtUpdate = $mysqli->prepare($updateQuery);
    $stmtUpdate->bind_param("s", $cartID);
    $stmtUpdate->execute();
}

    // ---------------------------------------------------------------------
    // FUNCTION: Retrieve the user's data by user ID.
    // Modify the query to match your user table structure.
    // ---------------------------------------------------------------------


    $mysqli->close();
    ob_end_flush();
?>
