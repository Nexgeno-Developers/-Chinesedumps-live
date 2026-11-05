<?php
require_once dirname(__FILE__) . '/includes/common/system_log.php';
error_reporting(0);
include("includes/config/classDbConnection.php");
session_start();
if (!empty($_SESSION['uid'])) {
	syslog_connect();
	syslog_write('Authentication', 'USER_LOGOUT', syslog_who().' logged out of the website.', array('entity_type' => 'customer', 'entity_id' => (int)$_SESSION['uid']));
}
unset($_SESSION['uid']);
session_destroy();
header("location:".$base_path."");
?>