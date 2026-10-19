<?php
ob_start();
session_start();
require_once __DIR__ . '/includes/common/auth.php';
require_once dirname(__FILE__) . '/includes/common/sanitize.php';
include('includes/config/classDbConnection.php');
include('includes/common/classes/classmain.php');
include('includes/common/classes/classProductMain.php');
include('includes/common/classes/classcart.php');
include('includes/common/classes/classUser.php');
include('functions.php');
include('includes/shoppingcart.php');

$objDBcon = new classDbConnection;
$objMain = new classMain($objDBcon);
$objPro = new classProduct($objDBcon);
$objCart = new classCart($objDBcon);
$objUser = new classUser($objDBcon);
$getPage = $objMain->getContent(5);
$quer = $objDBcon->Sql_Query_Exec('*', 'website', '');
$exec = mysql_fetch_array($quer);
$copyright = $exec['copyright'];
$from_email = $exec['from_email'];
$firstlink = ' '.$getPage[1];
$msg = '';
$msg2 = '';

if (isset($_SESSION['uid'])) {
    header('Location: mydownloads.html');
    exit;
}

if (isset($_POST['Signin'])) {
    $email = isset($_POST['Email']) ? trim($_POST['Email']) : '';
    $password = isset($_POST['Password']) ? $_POST['Password'] : '';

    if (!auth_csrf_is_valid(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '')) {
        $msg2 = 'Your session has expired. Please refresh the page and try again.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $msg2 = 'Enter a valid email address and password.';
    } else {
        $emailSql = mysql_real_escape_string($email);
        $result = mysql_query("SELECT * FROM tbl_user WHERE user_email='".$emailSql."' AND user_status='Active' LIMIT 1");
        $user = $result ? mysql_fetch_assoc($result) : false;

        if (!$user || !auth_password_verify($password, $user['user_password'])) {
            /* Deliberately generic: does not reveal whether the account exists. */
            $msg2 = 'The email address or password is incorrect.';
        } else {
            /* Transparently migrate accounts which still have a legacy plaintext password. */
            if (auth_password_needs_upgrade($user['user_password'])) {
                $newHash = mysql_real_escape_string(auth_password_hash($password));
                mysql_query("UPDATE tbl_user SET user_password='".$newHash."' WHERE user_id='".(int)$user['user_id']."'");
            }

            /* Move the guest cart before changing the session ID. */
            mysql_query("UPDATE temp_order SET UserID='".(int)$user['user_id']."' WHERE CartID='".mysql_real_escape_string(session_id())."'");
            session_regenerate_id(true);
            $_SESSION['email'] = $user['user_email'];
            $_SESSION['uid'] = $user['user_id'];
            $_SESSION['Uname'] = $user['user_fname'];
            $_SESSION['uphone'] = $user['user_phone'];

            if (!empty($_COOKIE['redirect_after_login']) && is_string($_COOKIE['redirect_after_login'])
                && strpos($_COOKIE['redirect_after_login'], '://') === false
                && !preg_match('#^[/\\\\]{2}#', $_COOKIE['redirect_after_login'])
                && !preg_match('#[\x00-\x1F\x7F]#', $_COOKIE['redirect_after_login'])) {
                $redirect = $_COOKIE['redirect_after_login'];
                setcookie('redirect_after_login', '', time() - 3600, '/');
                header('Location: '.$redirect);
                exit;
            }
            if (isset($_SESSION['check'])) { header('Location: '.$base_path.'cart.html'); exit; }
            if (isset($_SESSION['checkout'])) { header('Location: '.$base_path.'checkout.php'); exit; }
            if (!empty($_SESSION['lasturl'])) { header('Location: '.$base_path.$_SESSION['lasturl'].'.html'); exit; }
            header('Location: mydownloads.html');
            exit;
        }
    }
}

if (isset($_POST['emails'], $_POST['fName'], $_POST['passwords'])) {
    $email = trim($_POST['emails']);
    $password = $_POST['passwords'];
    $confirm = isset($_POST['repassword']) ? $_POST['repassword'] : '';

    if (!auth_csrf_is_valid(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '')) {
        $msg = 'Your session has expired. Please refresh the page and try again.';
    } elseif (!isset($_SESSION['email_otp_verified_email']) || strcasecmp($_SESSION['email_otp_verified_email'], $email) !== 0) {
        $msg = 'Please verify your email address before registering.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = 'Enter a valid email address.';
    } elseif ($password !== $confirm) {
        $msg = 'Passwords do not match.';
    } elseif (!auth_password_is_valid($password, $passwordError)) {
        $msg = $passwordError;
    } else {
        include_once('securimage/securimage.php');
        $securimage = new Securimage();
        if (!$securimage->check(isset($_POST['captcha_code']) ? $_POST['captcha_code'] : '')) {
            $msg = 'Please enter the security code shown in the image.';
        } else {
            $emailSql = mysql_real_escape_string($email);
            $existing = mysql_query("SELECT user_id FROM tbl_user WHERE user_email='".$emailSql."' LIMIT 1");
            if ($existing && mysql_num_rows($existing) > 0) {
                $msg = 'An account already exists for that email address.';
            } else {
                $sparm = array();
                $sparm[1] = $_POST['fName'];
                $sparm[2] = isset($_POST['lastname']) ? $_POST['lastname'] : '';
                $sparm[5] = $email;
                $sparm[11] = isset($_POST['concode']) ? $_POST['concode'] : '';
                $sparm[12] = isset($_POST['pnumber']) ? $_POST['pnumber'] : '';
                $sparm[13] = $sparm[11].'-'.$sparm[12];
                $sparm[9] = auth_password_hash($password);
                $sparm[6] = date('Y-m-d');
                $sparm[7] = $_SERVER['REMOTE_ADDR'];
                $lastsignupd = $objUser->addUser($sparm);
                $row = $objUser->getUserIDsignup($lastsignupd);

                mysql_query("UPDATE temp_order SET UserID='".(int)$lastsignupd."' WHERE CartID='".mysql_real_escape_string(session_id())."'");
                session_regenerate_id(true);
                $_SESSION['email'] = $row['user_email'];
                $_SESSION['uid'] = $row['user_id'];
                $_SESSION['Uname'] = $row['user_fname'];
                $_SESSION['uphone'] = $row['user_phone'];
                unset($_SESSION['email_otp_verified_email']);

                $sparm[3] = ''; // Passwords must never be sent by email.
                if (function_exists('utm_seo_save')) { utm_seo_save('register', $lastsignupd, $email); }
                signupemail($sparm, $from_email);

                if (isset($_SESSION['check'])) { header('Location: '.$base_path.'checkout.html'); exit; }
                if (isset($_SESSION['checkout'])) { header('Location: '.$base_path.'checkout.php'); exit; }
                if (!empty($_SESSION['lasturl'])) { header('Location: '.$base_path.$_SESSION['lasturl'].'.html'); exit; }
                header('Location: myorders.html');
                exit;
            }
        }
    }
}

include('html/login.html');
?>