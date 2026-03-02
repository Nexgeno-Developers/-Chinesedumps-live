<?php
require_once __DIR__ . '/includes/config/load_secrets.php';
session_start();
header('Content-Type: application/json');

// For development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get inputs
$mobile = isset($_POST['mobile']) ? preg_replace('/\D/', '', $_POST['mobile']) : '';
$mode   = isset($_POST['mode']) ? strtolower(trim($_POST['mode'])) : 'sms'; // default to SMS

if (!$mobile || !in_array($mode, ['sms', 'whatsapp'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

// Set credentials
$customerId = secret('MC_CUSTOMER_ID', '');
$authToken  = secret('MC_AUTH_TOKEN', '');
if ($customerId === '' || $authToken === '') {
    echo json_encode(['success' => false, 'error' => 'OTP service is not configured.']);
    exit;
}

// Determine flow type
$flowType = $mode === 'whatsapp' ? 'WHATSAPP' : 'SMS';

// Build URL
$url = 'https://cpaas.messagecentral.com/verification/v3/send'
     . '?countryCode=91'
     . '&customerId=' . urlencode($customerId)
     . '&flowType=' . urlencode($flowType)
     . '&mobileNumber=' . urlencode($mobile);

// cURL request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'authToken: ' . $authToken
));

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// Response handling
if (!empty($data['data']['verificationId'])) {
    $_SESSION['verificationId'] = $data['data']['verificationId'];
    $_SESSION['otp_mobile']     = $mobile;
    $_SESSION['otp_mode']       = $mode;

    echo json_encode(['success' => true, 'mode' => $mode, 'message' => 'OTP sent']);
} else {
    $error = isset($data['message']) ? $data['message'] : 'Failed to send OTP';
    echo json_encode(['success' => false, 'error' => $error]);
}
