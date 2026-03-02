<?php

ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classAdmin.php");

//---------------------------------------------------------------
	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
	$objAdmin	 = new classAdmin($objDBcon);	// VALIDATION CLASS classCategory
	
include_once 'functions.php';
$ccAdminData = get_session_data();
//-----------------------------------------------------------------

    if(isset($_GET['action']) && $_GET['action']	== "up" )
	{
	$objAdmin->deleteAdmin($_GET['nid']);
	}

	if(isset($_POST['btn_delete']) &&	$_POST['btn_delete']	== "Delete Selected Items" )
	{		
			for($i=1; $i<=$counter; $i++)
				{ 
					if(isset($_POST['chkbox'.$i])){
					$objAdmin->deleteAdmin($_POST['chkbox'.$i]);
					}
				}
	}

//............................................
	$qry = "select * from  tbladmin ";
	$res = mysql_query($qry);
//............................................

if(mysql_num_rows($res) < 1){
	$emptyError	=	"No record exist.";
	}else{
	$emptyError	=	'<input type="submit" name="btn_delete" id="btn_delete" class="button" value="Delete Selected Items" onclick="return Cofirm();"  />';
	}
	$show		 =	$objAdmin->getAdmin($res);

//------------------------html---------------------------------------

include("html/adminsmang.html");

//------------------------html---------------------------------------

?>