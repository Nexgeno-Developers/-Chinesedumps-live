<?php
error_reporting(0);
include("includes/config/classDbConnection.php");
session_start();
unset($_SESSION['uid']);
session_destroy();
header("location:".$base_path."");
?>