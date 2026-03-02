<?php
// Base secrets template. Copy to secrets.local.php and fill with real values.
return [
    // SMTP
    'SMTP_HOST' => 'smtp.gmail.com',
    'SMTP_PORT' => 587,
    'SMTP_ENCRYPTION' => 'tls',
    'SMTP_USERNAME' => 'CHANGE_ME',
    'SMTP_PASSWORD' => 'CHANGE_ME',
    'SMTP_FROM_EMAIL' => 'sales@chinesedumps.com',
    'SMTP_FROM_NAME' => 'Chinese Dumps',
    'SMTP_HELO_DOMAIN' => 'chinesedumps.com',

    // reCAPTCHA
    'RECAPTCHA_SITE_KEY_DEFAULT' => 'CHANGE_ME',
    'RECAPTCHA_SITE_KEY_ALT' => 'CHANGE_ME',
    'RECAPTCHA_SITE_KEY_SHIELDSQUARE' => 'CHANGE_ME',
    'RECAPTCHA_SECRET_SHIELDSQUARE' => 'CHANGE_ME',
    'RECAPTCHA_SECRET_ZOHO' => 'CHANGE_ME',

    // Zoho
    'ZOHO_CLIENT_ID' => 'CHANGE_ME',
    'ZOHO_CLIENT_SECRET' => 'CHANGE_ME',
    'ZOHO_REFRESH_TOKEN' => 'CHANGE_ME',
    // Optional short-lived access token (not required; usually generated via refresh token)
    'ZOHO_ACCESS_TOKEN' => '',
    'ZOHO_LIST_KEY' => 'CHANGE_ME',
    'ZOHO_ACCOUNTS_BASE' => 'https://accounts.zoho.in',
    'ZOHO_CAMPAIGNS_BASE' => 'https://campaigns.zoho.in',
    'ZOHO_THANK_YOU_URL' => 'thanks-you.htm',


    // Google OAuth
    'GOOGLE_CLIENT_ID' => 'CHANGE_ME',
    'GOOGLE_CLIENT_SECRET' => 'CHANGE_ME',
    'GOOGLE_REDIRECT_URI' => 'CHANGE_ME',

    // Email validation
    'BOUNCIFY_API_KEY' => 'CHANGE_ME',

    // Brevo / Sendinblue
    'BREVO_CLIENT_KEY' => 'CHANGE_ME',
    'BREVO_API_KEY' => 'CHANGE_ME',

    // MessageCentral (OTP)
    'MC_CUSTOMER_ID' => 'CHANGE_ME',
    'MC_AUTH_TOKEN' => 'CHANGE_ME',

    // FTP
    'FTP_HOST' => 'CHANGE_ME',
    'FTP_USER' => 'CHANGE_ME',
    'FTP_PASS' => 'CHANGE_ME',

    // App crypto
    'APP_ENCRYPTION_KEY' => 'CHANGE_ME_16_BYTES',
    'APP_ENCRYPTION_IV' => 'CHANGE_ME_16_BYTES',

    // Database (if needed)
    'DB_HOST' => 'localhost',
    'DB_USER' => 'root',
    'DB_PASS' => '',
    'DB_NAME' => 'chinesed_db',
];
