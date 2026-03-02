<?php
require_once __DIR__ . '/../includes/config/load_secrets.php';

return [
  // OAuth (India DC)
  'ZOHO_CLIENT_ID'     => secret('ZOHO_CLIENT_ID', ''),
  'ZOHO_CLIENT_SECRET' => secret('ZOHO_CLIENT_SECRET', ''),
  'ZOHO_REFRESH_TOKEN' => secret('ZOHO_REFRESH_TOKEN', ''),

  // Base URLs for India
  'ZOHO_ACCOUNTS_BASE' => secret('ZOHO_ACCOUNTS_BASE', 'https://accounts.zoho.in'),
  'ZOHO_CAMPAIGNS_BASE'=> secret('ZOHO_CAMPAIGNS_BASE', 'https://campaigns.zoho.in'),

  // Your list + labeling
  'ZOHO_LIST_KEY'      => secret('ZOHO_LIST_KEY', ''),
  'SOURCE_LABEL'       => 'Website - ChineseDumps',

  // reCAPTCHA + redirect
  'RECAPTCHA_SECRET'   => secret('RECAPTCHA_SECRET_ZOHO', ''),
  'THANK_YOU_URL'      => secret('ZOHO_THANK_YOU_URL', 'thanks-you.htm'),
];
