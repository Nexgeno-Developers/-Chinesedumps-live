<?php
require_once __DIR__ . '/../includes/config/load_secrets.php';
require_once __DIR__ . '/../includes/common/auth.php';
require_once dirname(dirname(__FILE__)) . '/includes/common/sanitize.php';
require_once dirname(dirname(__FILE__)) . '/includes/common/app_env.php'; app_apply_error_settings();

require_once '../vendor/google/vendor/autoload.php';
session_start();
include("../includes/config/classDbConnection.php");
include(__DIR__ . '/../functions.php');

$objDBcon   =   new classDbConnection; 
$quer	=	$objDBcon->Sql_Query_Exec('*','website','');
$exec	=	mysql_fetch_array($quer);
$from_email	=	$exec['from_email'];
	
$client = new Google_Client();
$client->setClientId(secret('GOOGLE_CLIENT_ID', ''));
$client->setClientSecret(secret('GOOGLE_CLIENT_SECRET', ''));
$client->setRedirectUri(secret('GOOGLE_REDIRECT_URI', base_url('google-login/callback.php')));

$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode(san_get('code'));

    if (isset($token['access_token'])) {
        $_SESSION['access_token'] = $token;
        $client->setAccessToken($token);

        $oauth = new Google_Service_Oauth2($client);
        $userData = $oauth->userinfo->get();

        $db = new classDbConnection; 
        $conn = $db->dbConn; // This is a mysql_connect() resource

        // Escape fields
        $fname = mysql_real_escape_string($userData->givenName);
        $lname = mysql_real_escape_string($userData->familyName);
        $email = mysql_real_escape_string($userData->email);
        $photo = mysql_real_escape_string($userData->picture);

        // Check if user exists
        $check = mysql_query("SELECT * FROM tbl_user WHERE user_email = '$email'", $conn);

        if (mysql_num_rows($check) > 0) {
            $row = mysql_fetch_assoc($check);
            $user_id   = $row['user_id'];
            $user_pass = $row['user_password'];
            $user_phone= $row['user_phone'];
            $user_fname= $row['user_fname'];
		
        } else {
            // Generate a 6-digit random password
            $user_pass = auth_password_hash(bin2hex(auth_random_bytes(24)));
            // 2) insert new user
            $insert_sql = "
              INSERT INTO tbl_user (
                user_fname, user_lname, user_email, user_user,
                user_password, user_phone, user_profile_photo,
                user_status, creatDate
              ) VALUES (
                '{$fname}', '{$lname}', '{$email}', '{$email}',
                '{$user_pass}', '', '{$photo}', 'Active', CURDATE()
              )";
            mysql_query($insert_sql, $conn);
            $user_id   = mysql_insert_id();
            $user_phone= '';   // none provided
            $user_fname= $fname;
            
            $spram = [];
            $spram[1]  = $user_fname;
            $spram[2]  = $lname;
            $spram[3]  = '';
            $spram[5]  = $email;
            $spram[13] = isset($phone) ? $phone : '';
            if (function_exists('utm_seo_save')) {
                utm_seo_save('register', $user_id, $email);
            }
            signupemail($spram, $from_email);
        }

        // 3) set the sessions
        $_SESSION['uid']      = $user_id;
        $_SESSION['email']    = $email;
        $_SESSION['Uname']    = $user_fname;
        $_SESSION['uphone']   = $user_phone;
            //   var_dump($_SESSION);
            //   exit;
        // 4) update any temp_order
        mysql_query(
          "UPDATE temp_order 
             SET UserID = '" . (int)$user_id . "' 
           WHERE CartID = '" . db_esc(session_id()) . "'",
           $conn
        );
        
        session_regenerate_id(true);
        // Redirect to cookie-based URL if set
        if (!empty($_COOKIE['redirect_after_login'])) {
            $safeRedirect = urldecode($_COOKIE['redirect_after_login']);
        
            // Optional: check it's from same domain (exact host match, no open redirect)
            $parsed = parse_url($safeRedirect);
            $siteHost = '';
            if (defined('BASE_URL')) { $siteHost = (string)parse_url(BASE_URL, PHP_URL_HOST); }
            if ($siteHost === '' && !empty($base_path)) { $siteHost = (string)parse_url($base_path, PHP_URL_HOST); }
            if ($siteHost === '' && !empty($_SERVER['HTTP_HOST'])) { $siteHost = preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST']); }
            $siteHost = strtolower($siteHost);
            $redirHost = (is_array($parsed) && isset($parsed['host'])) ? strtolower($parsed['host']) : '';
            $redirHostOk = ($redirHost !== '') && (
                ($siteHost !== '' && $redirHost === $siteHost)
                || $redirHost === 'chinesedumps.com'
                || substr($redirHost, -strlen('.chinesedumps.com')) === '.chinesedumps.com'
            );
            $redirClean = is_array($parsed)
                && strpos($safeRedirect, '\\') === false
                && !preg_match('#[\x00-\x1F\x7F]#', $safeRedirect)
                && !isset($parsed['user']) && !isset($parsed['pass'])
                && (!isset($parsed['scheme']) || in_array(strtolower($parsed['scheme']), array('http', 'https')));
            if ($redirClean && (
                ($redirHost === '' && !isset($parsed['scheme']) && !preg_match('#^/{2}#', $safeRedirect))
                || $redirHostOk
            )) {
                // Expire the cookie
                setcookie('redirect_after_login', '', time() - 3600, '/');
                header("Location: $safeRedirect");
                exit;
            }
        }

        // 5) redirect just like login.php does
        if (isset($_SESSION['check'])) {
            header("Location: {$base_path}cart.html");
        }
        elseif (isset($_SESSION['checkout'])) {
            header("Location: {$base_path}checkout.php");
        }
        elseif (!empty($_SESSION['lasturl'])) {
            header("Location: {$base_path}{$_SESSION['lasturl']}.html");
        }
        else {
            header("Location: {$base_path}mydownloads.html");
        }
        exit;
    } else {
        echo "Failed to fetch access token.";
        print_r($token);
        exit;
    }
}

?>
