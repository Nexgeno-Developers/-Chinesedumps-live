<?php 
session_start();
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Origin: https://chinesedumps.com");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("includes/config/classDbConnection.php");
include("functions.php");

$response = ['success' => false, 'message' => ''];

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file'])) {

    if (!isset($_SESSION['uid']) || !is_numeric($_SESSION['uid']) || $_SESSION['uid'] <= 0) {
        http_response_code(401); // Optional: inform AJAX with 401
        $response['message'] = 'You must be logged in to download this file.';
        echo json_encode($response);
        exit;
    }
    
    // if (!isset($_POST['user_id']) && !empty($_POST['user_id']) || !is_numeric($_POST['user_id'])) {
    //     $response['message'] = 'Invalid or missing user ID.';
    //     echo json_encode($response);
    //     exit;
    // }
    // $uid = intval($_POST['user_id']);
    
    $objDBcon   = new classDbConnection;
    
    // Create MySQLi connection using the properties from classDbConnection
    $mysqli = new mysqli($objDBcon->dbHost, $objDBcon->dbUser, $objDBcon->dbPass, $objDBcon->dbName);
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $ip       = $_SERVER['REMOTE_ADDR'];
    $country  = ''; // You can use GeoIP lookup if needed
    $now      = date('Y-m-d H:i:s');
    $uid      = intval($_SESSION['uid']);
    $file     = basename($_POST['file']);
    $cert_id  = intval($_POST['cert_id']);
    // Optional display name sent by client (the button text)
    $displayName = isset($_POST['displayName']) ? trim($_POST['displayName']) : '';
    
    // Build full URL to PDF (adjust domain to your site)
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
               "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
    $pdfUrl = rtrim($baseUrl, '/') . '/uploads/demo_pdfs/' . rawurlencode($file);

    $filepath = __DIR__ . '/uploads/demo_pdfs/' . $file;

    if (!file_exists($filepath)) {
        $response['message'] = 'File does not exist.';
        echo json_encode($response);
        exit;
    }
    
    // Determine extension
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $downloadName = $displayName . '.' . $ext;

    function getUserDetails($userID, $mysqli) {
        $stmt = $mysqli->prepare("SELECT user_email, user_fname, user_lname, user_phone FROM tbl_user WHERE user_id = ?");
        if (!$stmt) {
            error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
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

    $userDetails = getUserDetails($uid, $mysqli);
    
    if ($userDetails) {
        $userEmail     = $userDetails['user_email'];
        $userFirstName = $userDetails['user_fname'];
        $userLastName  = $userDetails['user_lname'];
        $userPhone     = isset($userDetails['user_phone']) ? $userDetails['user_phone'] : '';
        $fullName      = trim($userFirstName . ' ' . $userLastName);
        
        $fileName  = basename($_POST['file']);
        $certId    = intval($_POST['cert_id']);
        $ip        = $_SERVER['REMOTE_ADDR'];
        $dateTime  = date("Y-m-d H:i:s");
    
        // Optional display name sent by client (the button text)
    $displayNameRaw = isset($_POST['displayName']) ? trim($_POST['displayName']) : '';
    
        // --- Email Construction ---
        // $to = "webdeveloper@nexgeno.in"; // or "webdeveloper@nexgeno.in"
        $to = from_email(); // or "webdeveloper@nexgeno.in"
        $subject = "ChineseDumps Demo Download Alert By: $fullName";
        
        /*$message = "
            <strong>User ID:</strong> $uid downloaded the demo file:<br><br>
            <strong>File:</strong> $file<br>
            <strong>Cert ID:</strong> $cert_id<br>
            <strong>Date:</strong> $now<br>
            <strong>IP:</strong> $ip
        ";*/
        
        $message = "
        <html>
        <head><title>Demo Download Notification</title></head>
        <body>
            <p><strong>User Details:</strong></p>
            <ul>
                <li><strong>Name:</strong> " . htmlspecialchars($fullName) . "</li>
                <li><strong>Email:</strong> " . htmlspecialchars($userEmail) . "</li>" .
                (!empty($userPhone) ? "<li><strong>Phone:</strong> " . htmlspecialchars($userPhone) . "</li>" : '') . "
            </ul>
    
            <p><strong>Download Details:</strong></p>
            <ul>
                <li><strong>Stored File Name:</strong> " . htmlspecialchars($fileName) . "</li>
                <li><strong>Cert ID:</strong> $certId</li>
                <li><strong>IP Address:</strong> $ip</li>
                <li><strong>Timestamp:</strong> $dateTime</li>
            </ul>
            <p><strong>View PDF:</strong>
               <a href=\"$pdfUrl\" target=\"_blank\">" . htmlspecialchars($downloadName) . "</a>
            </p>
        </body>
        </html>";
    
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: noreply@chinesedumps.com\r\n";
    
        try {
            sendEmail($to, $subject, $message, $headers);
        } catch (Exception $e) {
            error_log("Brevo sendEmail failed: " . $e->getMessage());
            mail($to, $subject, $message, $headers);
        }

        // Send via your helper
        // sendEmail($to, $subject, $message, $headers);
        // mail($to, $subject, $message, $headers);
    }
    
    $escaped_file = mysqli_real_escape_string($mysqli, $file);
    $query = "INSERT INTO tbl_demo_download 
        (dnUID, dnTYPE, itemid, dndate, IP, IPcountry, statusdownload) 
        VALUES ('$uid', '$escaped_file', '$cert_id', '$now', '$ip', '$country', 1)";
    $inserted = mysqli_query($mysqli, $query);
    
    if ($inserted) {
        $response['success'] = true;
        $response['downloadUrl'] = "uploads/demo_pdfs/" . urlencode($file);
    } else {
        $response['message'] = 'MySQLi Error: ' . mysqli_error($mysqli);
    }
    error_log("SESSION: " . print_r($_SESSION, true));
    echo json_encode($response);
    exit;

}
?>
