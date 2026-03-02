<?php
// lead-submit.php
declare(strict_types=1);
error_reporting(0);

require_once __DIR__ . '/includes/config/load_secrets.php';

function cfg() { static $c; return $c ?? $c = require __DIR__ . '/zoho/secrets.php'; }
function tokenCachePath(): string { return __DIR__ . '/zoho/token-cache.json'; }

// ------------------------
// Generic HTTP POST helper
// ------------------------
function http_post(string $url, array $headers, $fields): array {
  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => $headers,
    CURLOPT_POSTFIELDS     => is_array($fields) ? http_build_query($fields) : $fields,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 20,
  ]);
  $body = curl_exec($ch);
  $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $err  = curl_error($ch);
  curl_close($ch);
  return [$code, $body, $err];
}

// ------------------------
// 1) Verify reCAPTCHA
// ------------------------
if (!empty(cfg()['RECAPTCHA_SECRET'])) {
  $token = $_POST['g-recaptcha-response'] ?? '';
  [$rcCode, $rcBody] = http_post(
    "https://www.google.com/recaptcha/api/siteverify",
    ["Content-Type: application/x-www-form-urlencoded"],
    ["secret" => cfg()['RECAPTCHA_SECRET'], "response" => $token]
  );
  $json = @json_decode($rcBody, true);
  if ($rcCode !== 200 || empty($json['success'])) {
    http_response_code(400);
    exit('Recaptcha failed');
  }
}

// ----------------------------------------------
// Helper: get or refresh Zoho Access Token (IN)
// ----------------------------------------------
function getZohoAccessToken(): string {
  $cacheFile = tokenCachePath();

  // return cached if valid
  if (file_exists($cacheFile)) {
    $saved = @json_decode((string)file_get_contents($cacheFile), true);
    if (!empty($saved['access_token']) && ($saved['expires_at'] ?? 0) > time() + 60) {
      return $saved['access_token'];
    }
  }

  // refresh with refresh_token
  $fields = [
    'refresh_token' => cfg()['ZOHO_REFRESH_TOKEN'],
    'client_id'     => cfg()['ZOHO_CLIENT_ID'],
    'client_secret' => cfg()['ZOHO_CLIENT_SECRET'],
    'grant_type'    => 'refresh_token',
  ];
  [$code, $body] = http_post(
    cfg()['ZOHO_ACCOUNTS_BASE'].'/oauth/v2/token',
    ['Content-Type: application/x-www-form-urlencoded'],
    $fields
  );

  $j = @json_decode($body, true);
  if ($code !== 200 || empty($j['access_token'])) {
    error_log("Zoho token refresh failed: $code $body");
    http_response_code(500);
    exit('Auth error');
  }

  $ttl  = (int)($j['expires_in'] ?? 3600);
  $save = ['access_token' => $j['access_token'], 'expires_at' => time() + $ttl - 30];
  @file_put_contents($cacheFile, json_encode($save));
  return $save['access_token'];
}

// ------------------------
// Sanitize inputs
// ------------------------
function v($k){ return isset($_POST[$k]) ? trim((string)$_POST[$k]) : ''; }
$name    = v('name');
$email   = v('email');
$phone   = v('tel');
$course  = v('courses');
$message = v('message');

$thankYouUrl = base_url(cfg()['THANK_YOU_URL']);

// Split name to first/last
$first = $name; $last = '';
if (strpos($name, ' ') !== false) {
  $parts = preg_split('/\s+/', $name);
  $first = array_shift($parts);
  $last  = implode(' ', $parts);
}

// --------------------------------------------------------
// Build contactinfo — keys must match Zoho display names
// --------------------------------------------------------
$contactinfo = [
  "First Name"    => $first,
  "Last Name"     => $last,
  "Contact Email" => $email,
  "Phone"         => $phone,
  "Course"        => $course,
  "Message"       => $message,
];

// -----------------------------------------
// 2) Send to Zoho Campaigns listsubscribe
// -----------------------------------------
$endpoint = cfg()['ZOHO_CAMPAIGNS_BASE'].'/api/v1.1/json/listsubscribe';
$access   = getZohoAccessToken();

$payload = [
  "resfmt"      => "JSON",
  "listkey"     => cfg()['ZOHO_LIST_KEY'],
  "contactinfo" => json_encode($contactinfo, JSON_UNESCAPED_UNICODE),
  "source"      => cfg()['SOURCE_LABEL'],
];

[$zCode, $zBody, $zErr] = http_post(
  $endpoint,
  [
    "Authorization: Zoho-oauthtoken $access",
    "Content-Type: application/x-www-form-urlencoded",
  ],
  $payload
);

// Optional: log (mask email)
@file_put_contents(__DIR__.'/zoho/leads.log',
  date('c')." | RECAPTCHA | $rcCode | $rcBody\n", FILE_APPEND);



// -----------------------------------------
// 3) Redirect to your thank-you page
// -----------------------------------------



$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($isAjax) {
  // CF7 (or similar) expects JSON when it posts via AJAX
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode([
    'ok'         => ($zCode >= 200 && $zCode < 300),
    'redirect'   => $thankYouUrl,
    'zoho_code'  => $zCode,
  ]);
  exit;
} else {
  // normal post => do redirect
  header('Location: ' . $thankYouUrl);
  exit;
}
