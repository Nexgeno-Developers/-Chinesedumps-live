<?php

session_start();
ob_start();
$LinkPath="../";
require_once($LinkPath."includes/common/inc/sessionheader.php");
require_once('../includes/config/classDbConnection.php');
$objDBcon    = new classDbConnection; 

include_once("../includes/components/FCKeditor/fckeditor.php");




	$sBasePath="../includes/components/FCKeditor/";

	$objeditor 				= new FCKeditor('txtSalesLetter') ;
	
	$objeditor->BasePath	= $sBasePath ;
	$objeditor->Value		=  $name;//"Enter Sales Letter Text Here";	



?>
