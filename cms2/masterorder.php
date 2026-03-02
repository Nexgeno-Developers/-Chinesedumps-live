<?php

ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classUser.php");
	include("../includes/common/classes/classPagingAdmin.php");

//---------------------------------------------------------------
	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
	$objUser	 = new classUser($objDBcon);	// VALIDATION CLASS classCategory
	
include_once 'functions.php';
$ccAdminData = get_session_data();
//----------------------------------------------------------------------------------------------------	
	$show				=	"";
	$title				=	"";
	$resultset			=	"";
	$condition			=	"";
	$limist				=	"";
	$LIMIT				=	"";
	$TotalRecs	     	= 	0;	
	$NaviLinks        	= 	"";	
	$BackNaviLinks	 	= 	"";		
	$ForwardNaviLinks 	= 	"";		
	$TotalPages	      	= 	"";
	$PageNo		      	= 	1;			
	$PageIndex	      	= 	1 ;				
	$rowsPerPage      	= 	100;											
	$linkPerPage      	= 	25;
	$of					=	"of";
//----------------------------------------------------------------------------------------------------		

		if(!isset($pgObj) && empty($pgObj))
				$pgObj 		=  new classPaging ("masterorder.php",$rowsPerPage,$linkPerPage,"","","");
		
		if(isset($_GET['pgIndex']) && !empty($_GET['pgIndex']) && is_numeric($_GET['pgIndex']) && is_numeric($_GET['currentPage']))
				{
					$PageNo			 = $_GET['currentPage'];	# Shows # of Page			
					$PageIndex		 = $_GET['pgIndex'];		# Shows # of Page Index				
				}	

					$LIMIT .= " LIMIT " . (($PageNo-1) * $rowsPerPage). " , " . $rowsPerPage;
//----------------------------------------------------------------------------------------------------	
    if(isset($_GET['action']) && $_GET['action']	== "up" )
	{
	$objUser->deleteTemordermaster($_GET['nid'],$_GET['cart']);
	}

	if(isset($_POST['btn_delete']) &&	$_POST['btn_delete']	== "Delete Selected Items" )
	{		
			
			for($i=1; $i<=$counter; $i++)
				{ 
					
					if(isset($_POST['chkbox'.$i])){
					$objUser->deleteTemordermaster($_POST['chkbox'.$i],$_GET['cart']);
					}
				}
	}

	$cri = "";
	if(isset($_POST['Search']) && $_POST['textSearch']!='')
	{
		if($_POST['criteria']=='user_email')
			$cri = " where tbl_user.".$_POST['criteria']." like '%".$_POST['textSearch']."%'";
		if($_POST['criteria']=='Order_Id')
			$cri = " where order_master.".$_POST['criteria']." = '".$_POST['textSearch']."'";
	}
	$qry = "select * from order_master INNER JOIN tbl_user ON (order_master.Cust_ID=tbl_user.user_id)  $cri order by OrderDate DESC";
	$result1 = mysql_query($qry);
	$pgObj->SetNavigationalLinksNew($result1);
	$qry2 = "select * from order_master INNER JOIN tbl_user ON (order_master.Cust_ID=tbl_user.user_id)  $cri order by OrderDate DESC ".$LIMIT;
	$result = mysql_query($qry2);
//............................................

	if(mysql_num_rows($result) < 1){
	$emptyError	=	"No record exist.";
	}else{
	$emptyError	=	'';
	}
	$show		 =	$objUser->showAllRealOrder($result);

//------------------------html---------------------------------------

include("html/masterorder.html");

//------------------------html---------------------------------------

?>