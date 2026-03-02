<?php
// zoho_ajax.php
// PHP 5.6 compatible endpoint to accept AJAX form POST and subscribe to Zoho Campaigns.
// Place at: /home2/chinesedumps/public_html/zoho_ajax.php

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/includes/config/load_secrets.php';

// ---------- CONFIG - EDIT ----------
$CLIENT_ID = secret('ZOHO_CLIENT_ID', '');
$CLIENT_SECRET = secret('ZOHO_CLIENT_SECRET', '');
$ZOHO_ACCOUNTS_HOST = secret('ZOHO_ACCOUNTS_BASE', 'https://accounts.zoho.in');
$ZOHO_CAMPAIGNS_SUBSCRIBE = rtrim(secret('ZOHO_CAMPAIGNS_BASE', 'https://campaigns.zoho.in'), '/') . '/api/v1.1/json/listsubscribe';
$ZOHO_LIST_KEY = secret('ZOHO_LIST_KEY', '');
$REDIRECT_AFTER = secret('ZOHO_THANK_YOU_URL', '/thankyou-page.php'); // optional redirect returned to frontend
// ---------- END CONFIG ----------

// helper - echo json and exit
function json_die($arr) {
    echo json_encode($arr, JSON_UNESCAPED_SLASHES);
    exit;
}

// only POST allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_die(array('success' => false, 'errors' => array('Invalid request method')));
}

// read and sanitize inputs
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$tel = isset($_POST['tel']) ? trim($_POST['tel']) : '';
$courses = isset($_POST['courses']) ? trim($_POST['courses']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// server-side validation
$errors = array();
if ($name === '' || preg_match('/[^a-zA-Z0-9@,?.\s-]/', $name) || strlen($name) > 50) {
    $errors[] = 'Enter a valid name without special characters';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Enter a valid email address';
}
if ($tel === '' || !preg_match('/^[\d+ ]+$/', $tel)) {
    $errors[] = 'Enter a valid mobile number';
}
// if ($courses === '') {
//     $errors[] = 'Please select a course';
// }
if (strlen($message) > 200 || preg_match('/[^a-zA-Z0-9@,?.\s-]/', $message)) {
    $errors[] = 'Message is invalid or too long';
}
if (!empty($errors)) {
    json_die(array('success' => false, 'errors' => $errors));
}

// refresh token: prefer config, fallback to file if provided
$refresh_token = secret('ZOHO_REFRESH_TOKEN', '');
if (!$refresh_token) {
    error_log('Zoho AJAX: refresh token not configured');
    json_die(array('success' => false, 'errors' => array('Server error: invalid refresh token')));
}

// 1) Mint access token using refresh token
$token_url = rtrim($ZOHO_ACCOUNTS_HOST, '/') . '/oauth/v2/token';
$post_fields = http_build_query(array(
    'grant_type' => 'refresh_token',
    'client_id' => $CLIENT_ID,
    'client_secret' => $CLIENT_SECRET,
    'refresh_token' => $refresh_token
));

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
$token_resp = curl_exec($ch);
$curl_err = curl_error($ch);
$token_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($token_resp === false || $curl_err) {
    error_log('Zoho AJAX: token curl error: ' . $curl_err);
    json_die(array('success' => false, 'errors' => array('Server error: failed to get access token')));
}

$token_json = json_decode($token_resp, true);
if (json_last_error() !== JSON_ERROR_NONE || empty($token_json['access_token'])) {
    error_log('Zoho AJAX: invalid token response: ' . $token_resp);
    json_die(array('success' => false, 'errors' => array('Server error: invalid token response')));
}

$access_token = $token_json['access_token'];

// 2) Prepare contactinfo as JSON - keys must match exact Zoho field labels
$contact_pairs = array(
    'First Name' => $name,
    'Contact Email' => $email,
    'Phone' => $tel,
    'Course' => $courses,
    'Message' => $message
);

// remove empty values
foreach ($contact_pairs as $k => $v) {
    if ($v === '' || $v === null) {
        unset($contact_pairs[$k]);
    }
}

// encode to JSON string (Zoho expects JSON or XML here)
$contactinfo_json = json_encode($contact_pairs);

// build POST body - contactinfo is JSON string
$post_sub = array(
    'resfmt' => 'JSON',
    'listkey' => $ZOHO_LIST_KEY,
    'contactinfo' => $contactinfo_json,
    'source' => 'Website-LeadForm'
);

$ch = curl_init($ZOHO_CAMPAIGNS_SUBSCRIBE);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_sub)); // will urlencode JSON properly
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Zoho-oauthtoken ' . $access_token,
    'Content-Type: application/x-www-form-urlencoded'
));
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
$sub_resp = curl_exec($ch);
$sub_err = curl_error($ch);
$sub_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($sub_resp === false || $sub_err) {
    error_log('Zoho AJAX: subscribe curl error: ' . $sub_err);
    json_die(array('success' => false, 'errors' => array('Server error: failed to add contact')));
}

// decode Zoho response if JSON
$sub_json = json_decode($sub_resp, true);
// log raw response for debugging (server-side only)
error_log('Zoho AJAX - subscribe response (' . $sub_http_code . '): ' . $sub_resp);

// treat 2xx as success, but also check Zoho status field if present
$ok = ($sub_http_code >= 200 && $sub_http_code < 300);
if ($ok && !(is_array($sub_json) && isset($sub_json['status']) && strtolower($sub_json['status']) === 'error')) {
    json_die(array(
        'success' => true,
        'message' => 'Subscribed',
        'zoho' => $sub_json,
        'redirect' => $REDIRECT_AFTER
    ));
} else {
    $msg = 'Zoho API error';
    if (is_array($sub_json) && isset($sub_json['message'])) {
        $msg = $sub_json['message'];
    }
    json_die(array('success' => false, 'errors' => array($msg), 'zoho' => $sub_json));
}
?>
