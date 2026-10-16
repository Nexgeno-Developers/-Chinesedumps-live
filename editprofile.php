<?php
ob_start();
session_start();
if (!isset($_SESSION['uid'])) { header('Location: login.html'); exit; }
require_once __DIR__ . '/includes/common/auth.php';
include('includes/config/classDbConnection.php');
include('includes/common/classes/classmain.php');
include('includes/common/classes/classProductMain.php');
include('includes/common/classes/classcart.php');
include('functions.php');
include('includes/shoppingcart.php');

$objDBcon = new classDbConnection;
$objMain = new classMain($objDBcon);
$objPro = new classProduct($objDBcon);
$objCart = new classCart($objDBcon);
$getPage = array('Edit Profile', 'Edit Profile', '', '', 'Edit Profile', 'Edit Profile');
$quer = $objDBcon->Sql_Query_Exec('*', 'website', '');
$exec = mysql_fetch_array($quer);
$userId = (int)$_SESSION['uid'];
$copyright = $exec['copyright'];
$from_email = $exec['from_email'];
$firstlink = ' '.$getPage[1];
$msg = '';

if (isset($_POST['emails'], $_POST['fName'])) {
    if (!auth_csrf_is_valid(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '')) {
        $msg = 'Your session has expired. Please refresh the page and try again.';
    } else {
        $newPassword = isset($_POST['passwords']) ? $_POST['passwords'] : '';
        $confirm = isset($_POST['repassword']) ? $_POST['repassword'] : '';
        if ($newPassword !== '' && $newPassword !== $confirm) {
            $msg = 'Passwords do not match.';
        } elseif ($newPassword !== '' && !auth_password_is_valid($newPassword, $passwordError)) {
            $msg = $passwordError;
        } else {
            $name = mysql_real_escape_string($_POST['fName']);
            $lastName = mysql_real_escape_string(isset($_POST['lastname']) ? $_POST['lastname'] : '');
            $sql = "UPDATE tbl_user SET user_fname='".$name."', user_lname='".$lastName."'";
            if ($newPassword !== '') {
                $sql .= ", user_password='".mysql_real_escape_string(auth_password_hash($newPassword))."'";
            }
            $sql .= " WHERE user_id='".$userId."'";
            mysql_query($sql);
            $msg = 'Profile has been updated successfully.';
        }
    }
}

$rs2 = mysql_query("SELECT * FROM tbl_user WHERE user_id='".$userId."' LIMIT 1");
$fetch2 = mysql_fetch_array($rs2);
$sparm = array();
$sparm[1] = $fetch2['user_fname'];
$sparm[2] = $fetch2['user_lname'];
$sparm[5] = $fetch2['user_email'];
include('html/editprofile.html');
?>