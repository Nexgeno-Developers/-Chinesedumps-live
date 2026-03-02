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
				$pgObj 		=  new classPaging ("exam_request.php",$rowsPerPage,$linkPerPage,"","","");
		
		if(isset($_GET['pgIndex']) && !empty($_GET['pgIndex']) && is_numeric($_GET['pgIndex']) && is_numeric($_GET['currentPage']))
				{
					$PageNo			 = $_GET['currentPage'];	# Shows # of Page			
					$PageIndex		 = $_GET['pgIndex'];		# Shows # of Page Index				
				}	

					$LIMIT .= " LIMIT " . (($PageNo-1) * $rowsPerPage). " , " . $rowsPerPage;
//----------------------------------------------------------------------------------------------------	
    if(isset($_GET['action']) && $_GET['action']	== "up" )
	{
	$objUser->deleteExamRequestcode($_GET['nid']);
	}

	if(isset($_POST['btn_delete']) &&	$_POST['btn_delete']	== "Delete Selected Items" )
	{		
			$counter	=	$_POST['counter']; 
			for($i=1; $i<=$counter; $i++)
				{ 
					if(isset($_POST['chkbox'.$i])){
					$objUser->deleteExamRequestcode($_POST['chkbox'.$i]);
					}
				}
	}
	$query = "select * from exam_request";
	/*$query = "SELECT 
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
	$orderby = "ORDER BY `tbl_full_download`.`dnID` DESC";*/
	$today_datee = date("Y-m-d");
	$yesterday_datee = date('Y-m-d', strtotime($today_datee . ' - 1 day'));
	if(isset($_POST['Search']))
	{
		$where = " where ".$_POST['criteria']." like '%".$_POST['textSearch']."%'";
	}
	if(isset($_GET['getSearch']))
	{	
		//////////////Today////////////////////
		if(isset($_GET['getSearch']) and $_GET['getSearch'] == "td"){
		$where = " WHERE examr_date like '$today_datee%'";
		}
		////////////Yesterday/////////////////
		if(isset($_GET['getSearch']) and $_GET['getSearch'] == "yd"){
		$where = " WHERE examr_date like '$yesterday_datee%'";
		}
		////////////1 Week /////////////////
		if(isset($_GET['getSearch']) and $_GET['getSearch'] == "lw"){
		$where = "  WHERE examr_date BETWEEN DATE_SUB( CURDATE() ,INTERVAL 7 DAY ) AND CURDATE()";
		}
		////////////Last Month /////////////////
		if(isset($_GET['getSearch']) and $_GET['getSearch'] == "lm"){
		$where = " WHERE examr_date BETWEEN DATE_SUB( CURDATE() ,INTERVAL 30 DAY ) AND CURDATE()";
		}
		////////////1 Year/////////////////
		if(isset($_GET['getSearch']) and $_GET['getSearch'] == "ly"){
		$where = " WHERE examr_date BETWEEN DATE_SUB( CURDATE() ,INTERVAL 1 YEAR) AND CURDATE()";
		}
	}
	if(isset($_GET['ord']))
	{
		$orderby = "ORDER BY ".$_GET['ord'] ." ". $_GET['ty'];
	}
	
//............................................
	$result = mysql_query("$query $where $groupby $orderby");
	if(mysql_num_rows($result) < 1){
	$emptyError	=	"No record exist.";
	}else{
	$emptyError	=	'<input type="submit" name="btn_delete" id="btn_delete" class="button" value="Delete Selected Items" onclick="return Cofirm();"  />';
	}
	$show		 =	$objUser->getUserexamRequest($result);

$today_date = date("Y-m-d");
$yesterday_date = date('Y-m-d', strtotime($today_date . ' - 1 day'));
// Demo Today
		$qry = "SELECT count(*) FROM  exam_request WHERE examr_date like '$today_date%'";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$eRequest_today = $row[0];
// Demo Yesterday
		$qry = "SELECT count(*) FROM exam_request WHERE examr_date like '$yesterday_date%'";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$eRequest_yesterday = $row[0];
// Demo last week
		$qry = "SELECT count(*) FROM exam_request WHERE examr_date BETWEEN DATE_SUB( CURDATE() ,INTERVAL 7 DAY ) AND CURDATE()";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$eRequest_last_week = $row[0];
// Demo last month
		$qry = "SELECT count(*) FROM exam_request WHERE examr_date BETWEEN DATE_SUB( CURDATE() ,INTERVAL 30 DAY ) AND CURDATE()";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$eRequest_last_month = $row[0];
// Demo last year
		$qry = "SELECT count(*) FROM exam_request WHERE examr_date BETWEEN DATE_SUB( CURDATE() ,INTERVAL 1 YEAR) AND CURDATE()";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$eRequest_last_year = $row[0];



//------------------------html---------------------------------------

include("html/exam_request.html");

//------------------------html---------------------------------------

?>