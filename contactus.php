<?php
$this_page = "contact";
ob_start();
session_start();
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");

include("includes/shoppingcart.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);

$getPage	=	$objMain->getContent(8);

$quer	=	$objDBcon->Sql_Query_Exec('*','website','');
$exec	=	mysql_fetch_array($quer);

$copyright	=	$exec['copyright'];

$firstlink	=	" ".$getPage[1];
include("functions.php"); include ('includes/common/classes/classEmailvalidate.php');  

$emailValidator = new classEmailvalidate();

// --------------------
// FORM PROCESSING
// --------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contactus_form'])) {

    function sanitizeInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    $errors = [];

    $name     = sanitizeInput($_POST['name']);
    $email    = sanitizeInput($_POST['email']);
    $tel      = sanitizeInput($_POST['tel']);
    $message  = sanitizeInput($_POST['message']);
    $cCode    = isset($_POST['concode']) ? sanitizeInput($_POST['concode']) : '';
    $type     = isset($_POST['type']) ? sanitizeInput($_POST['type']) : 'contact';

    // --- Validation ---
    if (empty($name) || preg_match('/[^a-zA-Z0-9\s]/', $name)) {
        $errors[] = "Invalid name. No special characters allowed.";
    }
    if (empty($email)) {
        $errors[] = "Please enter your email address.";
    } else {
        // Then check if valid format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
    }
    if (!preg_match('/^\d{10,15}$/', $tel)) {
        $errors[] = "Enter a valid mobile number (10–15 digits).";
    }
    if (!empty($message) && preg_match('/[^a-zA-Z0-9\s.,!?]/', $message)) {
        $errors[] = "Message contains invalid characters.";
    }

    // reCAPTCHA check (optional)
    if (empty($_POST['g-recaptcha-response'])) {
        $errors[] = "Please complete the reCAPTCHA.";
    }

    // Email validity check (optional external API)
    $response = $emailValidator->email_validate_api($email);
    $data = json_decode($response, true);
    if ($data && isset($data['result']) && $data['result'] == 'undeliverable') {
        $errors[] = "Email address is not deliverable.";
    }

    // If errors → display
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    
    if (!empty($errors)) {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        }
    } else {
        // --- Send Email ---
        $to = from_email(); // umair.makent@gmail.com or support@chinesedumps.com
        // $to = "support@chinesedumps.com"; // umair.makent@gmail.com or support@chinesedumps.com
        $subject = 'Chinese Dumps ' . ucfirst($type) . ' Page Enquiry';
        $txt  = "<strong>Name:</strong> " . $name . "<br><br>";
        $txt .= "<strong>Email:</strong> " . $email . "<br><br>";
        $txt .= "<strong>Tel:</strong> +" . $cCode . "-" . $tel . "<br><br>";
        $txt .= "<strong>Message:</strong> " . $message . "<br><br>";

        $headers  = "From: " . $email . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8";

        $mail_send = sendEmail($to, $subject, $txt, $headers);

        header('Content-Type: application/json');
        if ($mail_send) {
            echo json_encode(array('success' => true, 'mail_sent' => true));
            exit;
        }

        echo json_encode(array(
            'success' => false,
            'mail_sent' => false,
            'message' => 'Failed to send email. Please try again later.'
        ));
        exit;
    }
}

include("html/login.html");
