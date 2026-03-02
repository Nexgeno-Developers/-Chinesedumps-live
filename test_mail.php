<?php
// Quick SMTP send test to verify Gmail/Workspace credentials are working.
// This uses the central sendEmail() helper.

// Load site config so $fromEmail is available.
include __DIR__ . '/includes/config/classDbConnection.php';
include __DIR__ . '/functions.php';

$to = 'umair.makent@gmail.com';
$subject = 'SMTP test from dev environment';
$body = '<p>Hello, this is a Gmail SMTP connectivity test.</p>';
$headers = "From: Test <{$fromEmail}>\r\nContent-Type: text/html; charset=UTF-8";

$result = sendEmail($to, $subject, $body, $headers);

if ($result) {
    echo "Sent OK\n";
} else {
    echo "Send failed. Check error_log for details.\n";
}
