<?php
session_start();
header('Content-Type: application/json');
include("functions.php");

// PHP 5.6 compatibility helpers
if (!function_exists('random_int')) {
    /**
     * Best-effort random int for PHP 5.6 (not cryptographically strong).
     */
    function random_int($min, $max)
    {
        return mt_rand($min, $max);
    }
}

/**
 * Append a line to the OTP error log.
 */
function logOtpError($message)
{
    $logDir = __DIR__ . '/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0777, true);
    }
    $line = sprintf("[%s] %s\n", date('Y-m-d H:i:s'), $message);
    @file_put_contents($logDir . '/email_otp.log', $line, FILE_APPEND | LOCK_EX);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    logOtpError('send-email-otp: invalid method ' . $_SERVER['REQUEST_METHOD']);
    http_response_code(405);
    echo json_encode(array('success' => false, 'error' => 'Method not allowed.'));
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if (!$email) {
    logOtpError('send-email-otp: invalid email provided');
    http_response_code(400);
    echo json_encode(array('success' => false, 'error' => 'Valid email is required.'));
    exit;
}

try {
    $otp = random_int(100000, 999999);
} catch (Exception $e) {
    logOtpError('send-email-otp: otp generation failed - ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(array('success' => false, 'error' => 'Could not generate OTP.'));
    exit;
}

unset($_SESSION['email_otp_verified_email']);

$_SESSION['email_otp_code']    = $otp;
$_SESSION['email_otp_email']   = $email;
$_SESSION['email_otp_expires'] = time() + 300; // 5 minutes

// $host      = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'no-reply.local';
$fromEmail = secret('SMTP_FROM_EMAIL', 'sales@chinesedumps.com');

$subject = 'Your verification code';
// $message = "Your verification code is: {$otp}\n\nThe code expires in 5 minutes.\n\nIf you did not request this, you can ignore this email.";
$message  = "Your verification code is: " . $otp . "<br><br>";
$message .= "The code expires in 5 minutes.<br><br>";
$message .= "If you did not request this, you can ignore this email.<br><br>";
        
$headers = "From: {$fromEmail}\r\nReply-To: {$fromEmail}\r\nX-Mailer: PHP/" . phpversion();

$sent = sendEmail($email, $subject, $message, $headers);

if (!$sent) {
    logOtpError('send-email-otp: mail() failed for ' . $email);
    http_response_code(500);
    echo json_encode(array('success' => false, 'error' => 'Unable to send the verification email right now.'));
    exit;
}

echo json_encode(array('success' => true));
