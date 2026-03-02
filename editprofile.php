<?php
ob_start();
session_start();
if(isset($_SESSION['uid'])){
}else{
header("location:login.html");
}
include("includes/config/classDbConnection.php");
include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");
include("functions.php");
include("includes/shoppingcart.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);

$getPage[0]	=	"Edit Profile";
$getPage[1]	=	"Edit Profile";
$getPage[2]	=	"";
$getPage[4]	=	"Edit Profile";
$getPage[5]	=	"Edit Profile";

    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
	$userId = $_SESSION['uid'];
	$copyright	=	$exec['copyright'];
	$from_email	=	$exec['from_email'];
		
	$firstlink	=	" ".$getPage[1];
	$sparm[0]	=	$_SESSION['uid'];
	///////////////////////////////////////sign up portion//////////////////////////////////
	if(isset($_POST['emails']) && isset($_POST['fName']) && isset($_POST['passwords']))
	{
	
$sparm[1]	=	$_POST['fName'];
$sparm[2]	=	$_POST['lastname'];
$sparm[5]	=	$_POST['emails'];
$sparm[9]	=	$_POST['passwords'];

$sparm[6]	=   date('m/d/Y');
$sparm[7]	=   $_SERVER['REMOTE_ADDR'];
if($sparm[9]==''){
$sparm[9]	=	$_POST['hiddenpassword'];
}else{
$sparm[9]	=	$sparm[9];
}
		mysql_query("UPDATE tbl_user set user_fname='".$_POST['fName']."',user_lname='".$_POST['lastname']."',user_password='".$sparm[9]."' where user_id='".$sparm[0]."'");
		$sparm[3]	=	$_POST['passwords'];
		editprofileemail($sparm,$from_email);
		$msg="Profile has been updated successfully.";
	}
	
	$sql2	= "select * from tbl_user where user_id='".$sparm[0]."'";
	$rs2	= mysql_query($sql2);
	$fetch2	= mysql_fetch_array($rs2);
	$sparm[1]	=	$fetch2['user_fname'];
	$sparm[2]	=	$fetch2['user_lname'];
	$sparm[5]	=	$fetch2['user_email'];
	$sparm[9]	=	$fetch2['user_password'];
	
	//////////////////////////////////////////////////////////////////////////
				
include("html/editprofile.html");
?>