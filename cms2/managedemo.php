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
//-----------------------------------------------------------------
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
	if(isset($_POST['Search'])){
	$rowsPerPage      	= 	1000000000;
	}else{
	$rowsPerPage      	= 	100;
	}										
	$linkPerPage      	= 	25;
	$of					=	"of";
//----------------------------------------------------------------------------------------------------		

		if(!isset($pgObj) && empty($pgObj))
				$pgObj 		=  new classPaging ("managedemo.php",$rowsPerPage,$linkPerPage,"","","");
		
		if(isset($_GET['pgIndex']) && !empty($_GET['pgIndex']) && is_numeric($_GET['pgIndex']) && is_numeric($_GET['currentPage']))
				{
					$PageNo			 = $_GET['currentPage'];	# Shows # of Page			
					$PageIndex		 = $_GET['pgIndex'];		# Shows # of Page Index				
				}	

					$LIMIT .= " LIMIT " . (($PageNo-1) * $rowsPerPage). " , " . $rowsPerPage;
//----------------------------------------------------------------------------------------------------	
    if(isset($_GET['action']) && $_GET['action']	== "up" )
	{
	$objUser->deleteUserdemo($_GET['nid']);
	}

	if(isset($_POST['btn_delete']) &&	$_POST['btn_delete']	== "Delete Selected Items" )
	{		
			$counter	=	$_POST['counter']; 
			for($i=1; $i<=$counter; $i++)
				{ 
					
					if(isset($_POST['chkbox'.$i])){
					$objUser->deleteUserdemo($_POST['chkbox'.$i]);
					}
				}
	}
	
	if(isset($_POST['Search']))
	{
		$result1	=	mysql_query("SELECT * FROM tbl_demo_download INNER JOIN tbl_user ON (tbl_demo_download.dnUID=tbl_user.user_id) WHERE (tbl_user.user_email LIKE '%".$_POST['textSearch']."%' ) order by tbl_demo_download.dndate DESC".$limist);
		$pgObj->SetNavigationalLinksNew($result1);
		$result		= 	mysql_query("SELECT * FROM tbl_demo_download INNER JOIN tbl_user ON (tbl_demo_download.dnUID=tbl_user.user_id) WHERE (tbl_user.user_email LIKE '%".$_POST['textSearch']."%' ) order by tbl_demo_download.dndate DESC".$LIMIT);
	
	}else{
//............................................
	$qry = "select * from  tbl_demo_download order by `dnID` DESC".$limist;
	$result1 = mysql_query($qry);
	$pgObj->SetNavigationalLinksNew($result1);
	$qry2 = "select * from  tbl_demo_download order by `dnID` DESC".$LIMIT;
	$result = mysql_query($qry2);
	}
//............................................

	if(mysql_num_rows($result) < 1){
	$emptyError	=	"No record exist.";
	}else{
	$emptyError	=	'<input type="submit" name="btn_delete" id="btn_delete" class="button" value="Delete Selected Items" onclick="return Cofirm();"  />';
	}
	$show		 =	$objUser->getUserhtmldemo($result);

//------------------------html---------------------------------------

include("html/managedemo.html");

//------------------------html---------------------------------------

?>