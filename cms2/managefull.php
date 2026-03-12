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
				$pgObj 		=  new classPaging ("managefull.php",$rowsPerPage,$linkPerPage,"","","");
		
		if(isset($_GET['pgIndex']) && !empty($_GET['pgIndex']) && is_numeric($_GET['pgIndex']) && is_numeric($_GET['currentPage']))
				{
					$PageNo			 = $_GET['currentPage'];	# Shows # of Page			
					$PageIndex		 = $_GET['pgIndex'];		# Shows # of Page Index				
				}	

					$LIMIT .= " LIMIT " . (($PageNo-1) * $rowsPerPage). " , " . $rowsPerPage;
//----------------------------------------------------------------------------------------------------	
    if(isset($_GET['action']) && $_GET['action']	== "up" )
	{
	$objUser->deleteUserdemofull($_GET['nid']);
	}

	if(isset($_POST['btn_delete']) &&	$_POST['btn_delete']	== "Delete Selected Items" )
	{		
			$counter	=	$_POST['counter']; 
			for($i=1; $i<=$counter; $i++)
				{ 
					if(isset($_POST['chkbox'.$i])){
					$objUser->deleteUserdemofull($_POST['chkbox'.$i]);
					}
				}
	}
	$query = "SELECT
    `tbl_full_download`.`dnID`
    , `tbl_user`.`user_fname`
    , `tbl_user`.`user_lname`
    , `tbl_user`.`user_email`
    , `tbl_exam`.`exam_name`
FROM
    `testbells`.`tbl_full_download`
    INNER JOIN `testbells`.`tbl_user` 
        ON (`tbl_full_download`.`dnUID` = `tbl_user`.`user_id`)
    INNER JOIN `testbells`.`tbl_exam` 
        ON (`tbl_full_download`.`itemid` = `tbl_exam`.`exam_id`)";
	$where = " ";
	$groupby = "GROUP BY `tbl_full_download`.`dnID`";
	$orderby = "ORDER BY `tbl_full_download`.`dnID` DESC";
	if(isset($_POST['Search']))
	{
		$where = " where ".$_POST['criteria']." like '%".$_POST['textSearch']."%'";
	}
	if(isset($_GET['ord']))
	{
		$orderby = "ORDER BY ".$_GET['ord'] ." ". $_GET['ty'];
	}
//............................................
	$result = mysql_query("$query $where $groupby $orderby");
	if(mysql_num_rows($result) < 1){
	$emptyError	=	'<span style="color: #64748b; font-size: 14px;">No record exist.</span>';
	}else{
	$emptyError	=	'<input type="submit" name="btn_delete" id="btn_delete" class="btn-delete" value="Delete Selected Items" onclick="return Cofirm();"  />';
	}
	$show		 =	$objUser->getUserhtmldemofull($result);

//------------------------html---------------------------------------

include("html/managefull.html");

//------------------------html---------------------------------------

?>