<?php
session_start();
header('Content-Type: application/json');

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
    logOtpError('verify-email-otp: invalid method ' . $_SERVER['REQUEST_METHOD']);
    http_response_code(405);
    echo json_encode(array('success' => false, 'error' => 'Method not allowed.'));
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$code  = isset($_POST['code']) ? trim($_POST['code']) : '';

if (!$email || !preg_match('/^[0-9]{6}$/', $code)) {
    logOtpError('verify-email-otp: invalid email or code provided');
    http_response_code(400);
    echo json_encode(array('success' => false, 'error' => 'Email and 6-digit OTP are required.'));
    exit;
}

if (!isset($_SESSION['email_otp_code'], $_SESSION['email_otp_email'], $_SESSION['email_otp_expires'])) {
    logOtpError('verify-email-otp: session data missing for ' . $email);
    echo json_encode(array('success' => false, 'error' => 'No OTP request found. Please request a new code.'));
    exit;
}

if (time() > (int) $_SESSION['email_otp_expires']) {
    logOtpError('verify-email-otp: otp expired for ' . $email);
    unset($_SESSION['email_otp_code'], $_SESSION['email_otp_email'], $_SESSION['email_otp_expires']);
    echo json_encode(array('success' => false, 'error' => 'OTP expired. Please request a new code.'));
    exit;
}

if ($email !== $_SESSION['email_otp_email']) {
    logOtpError('verify-email-otp: email mismatch. expected ' . $_SESSION['email_otp_email'] . ' got ' . $email);
    echo json_encode(array('success' => false, 'error' => 'Email does not match the requested OTP.'));
    exit;
}

if ($code !== (string) $_SESSION['email_otp_code']) {
    logOtpError('verify-email-otp: invalid code for ' . $email);
    echo json_encode(array('success' => false, 'error' => 'Invalid OTP.'));
    exit;
}

// OTP is valid
$_SESSION['email_otp_verified_email'] = $email;
unset($_SESSION['email_otp_code'], $_SESSION['email_otp_email'], $_SESSION['email_otp_expires']);

echo json_encode(array('success' => true));
