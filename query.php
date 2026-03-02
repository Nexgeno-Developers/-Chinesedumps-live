<?php
ob_start();
session_start();
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");

include("includes/shoppingcart.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);
echo "OK";
//mysql_query("ALTER TABLE `tbl_vendors` ADD `swregid` VARCHAR( 55 ) NOT NULL ");
//mysql_query("ALTER TABLE `tbl_cert` ADD `swregid` VARCHAR( 55 ) NOT NULL");
//mysql_query("ALTER TABLE `tbl_exam` ADD `swregid` VARCHAR( 55 ) NOT NULL ");
//mysql_query("CREATE TABLE IF NOT EXISTS `tbl_full_download` (`dnID` int(11) NOT NULL AUTO_INCREMENT,`dnUID` int(11) NOT NULL,`dnTYPE` varchar(55) NOT NULL,`itemid` int(11) NOT NULL,`dndate` varchar(255) NOT NULL,`IP` varchar(55) NOT NULL,`IPcountry` varchar(255) NOT NULL,`statusdownload` int(11) NOT NULL,PRIMARY KEY (`dnID`)) ");
?>