<?php
require_once __DIR__ . '/includes/config/load_secrets.php';

// Gmail / Google Workspace SMTP settings.
return [
    'host' => secret('SMTP_HOST', 'smtp.gmail.com'),
    'port' => (int)secret('SMTP_PORT', 587), // 465 for SSL
    'encryption' => secret('SMTP_ENCRYPTION', 'tls'), // tls or ssl
    'username' => secret('SMTP_USERNAME', ''),
    'password' => secret('SMTP_PASSWORD', ''),
    'from_email' => secret('SMTP_FROM_EMAIL', from_email()),
    'from_name' => secret('SMTP_FROM_NAME', 'Chinese Dumps'),
    'timeout' => 30,
    'helo_domain' => secret('SMTP_HELO_DOMAIN', 'chinesedumps.com'),
];
