<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/includes/config/config.php';

// For development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get user input and session data
$code           = isset($_POST['code']) ? trim($_POST['code']) : '';
$mobile         = isset($_SESSION['otp_mobile']) ? $_SESSION['otp_mobile'] : '';
$verificationId = isset($_SESSION['verificationId']) ? $_SESSION['verificationId'] : '';
$mode           = isset($_SESSION['otp_mode']) ? $_SESSION['otp_mode'] : 'sms';

if (!$code || !$mobile || !$verificationId) {
    echo json_encode(['success' => false, 'error' => 'Missing data']);
    exit;
}

// API credentials
$customerId = secret('MC_CUSTOMER_ID', '');
$authToken  = secret('MC_AUTH_TOKEN', '');
if ($customerId === '' || $authToken === '') {
    echo json_encode(['success' => false, 'error' => 'OTP service is not configured.']);
    exit;
}

// Build verification URL
$url = 'https://cpaas.messagecentral.com/verification/v3/validateOtp'
     . '?countryCode=91'
     . '&mobileNumber=' . urlencode($mobile)
     . '&verificationId=' . urlencode($verificationId)
     . '&customerId=' . urlencode($customerId)
     . '&code=' . urlencode($code);

// cURL request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'authToken: ' . $authToken
));

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// Check verification status
if (!empty($data['data']['verificationStatus']) &&
    $data['data']['verificationStatus'] === 'VERIFICATION_COMPLETED') {
    
    $_SESSION['phone_verified'] = true;
    echo json_encode(['success' => true, 'mode' => $mode, 'message' => 'OTP verified']);
} else {
    echo json_encode(['success' => false, 'error' => 'OTP verification failed']);
}
