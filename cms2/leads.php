<?php

ob_start();
session_start();

	include_once ("../includes/config/classDbConnection.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classUser.php");
	include("../includes/common/classes/classPagingAdmin.php");
	include("../functions.php");

//---------------------------------------------------------------
	$objDBcon    = new classDbConnection;
	$objUser	 = new classUser($objDBcon);

include_once 'functions.php';
$ccAdminData = get_session_data();
formLeadsEnsureTable();

	$show				=	"";
	$title				=	"";
	$resultset			=	"";
	$condition			=	"";
	$limist				=	"";
	$LIMIT				=	"";
	$where				=	"";
	$groupby			=	"";
	$orderby			=	"ORDER BY id DESC";
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

		if(!isset($pgObj) && empty($pgObj))
				$pgObj 		=  new classPaging ("leads.php",$rowsPerPage,$linkPerPage,"","","");

		if(isset($_GET['pgIndex']) && !empty($_GET['pgIndex']) && is_numeric($_GET['pgIndex']) && is_numeric($_GET['currentPage']))
				{
					$PageNo			 = $_GET['currentPage'];
					$PageIndex		 = $_GET['pgIndex'];
				}

					$LIMIT .= " LIMIT " . (($PageNo-1) * $rowsPerPage). " , " . $rowsPerPage;

    if(isset($_GET['action']) && $_GET['action']	== "up" )
	{
	$objUser->deleteFormLead($_GET['nid']);
	}

	if(isset($_POST['btn_delete']) &&	$_POST['btn_delete']	== "Delete Selected Items" )
	{
			$counter	=	$_POST['counter'];
			for($i=1; $i<=$counter; $i++)
				{
					if(isset($_POST['chkbox'.$i])){
					$objUser->deleteFormLead($_POST['chkbox'.$i]);
					}
				}
	}

	if(isset($_POST['Export']) && $_POST['form_leads'] == 'formLeads')
	{
		$qry1 = "SELECT * FROM tbl_form_leads ORDER BY id DESC";
		$result2 = mysql_query($qry1);
		$csv_output1 = "ID\tForm Type\tName\tEmail\tPhone\tExam Code\tMessage\tSource\tEmail Sent\tIP\tCreated At\n";
		while($row1=mysql_fetch_array($result2))
		{
			$csv_output1.= $row1['id']."\t";
			$csv_output1.= $row1['form_type']."\t";
			$csv_output1.= $row1['name']."\t";
			$csv_output1.= $row1['email']."\t";
			$csv_output1.= $row1['phone']."\t";
			$csv_output1.= $row1['exam_code']."\t";
			$csv_output1.= str_replace(array("\r","\n","\t"), ' ', $row1['message'])."\t";
			$csv_output1.= $row1['source_page']."\t";
			$csv_output1.= $row1['email_sent']."\t";
			$csv_output1.= $row1['ip']."\t";
			$csv_output1.= $row1['created_at']."\t";
			$csv_output1 .= "\n";
		}
		header("Content-Type: application/force-download\n");
		header("Cache-Control: cache, must-revalidate");
		header("Pragma: public");
		header("Content-Disposition: attachment; filename=form_leads_" . date("YmdHis") . ".xls");
		print $csv_output1;
		exit;
	}

	$query = "select * from tbl_form_leads";
	$today_datee = date("Y-m-d");
	$yesterday_datee = date('Y-m-d', strtotime($today_datee . ' - 1 day'));
	$allowedCriteria = array('email', 'name', 'exam_code', 'form_type', 'phone');
	if(isset($_POST['Search']))
	{
		$criteria = isset($_POST['criteria']) ? $_POST['criteria'] : 'email';
		if (!in_array($criteria, $allowedCriteria)) {
			$criteria = 'email';
		}
		$where = " where ".$criteria." like '%".mysql_real_escape_string($_POST['textSearch'])."%'";
	}
	if(isset($_GET['form_type']) && in_array($_GET['form_type'], array('enquiry', 'registration', 'exam_request')))
	{
		$typeWhere = " form_type = '".mysql_real_escape_string($_GET['form_type'])."'";
		$where = ($where == '') ? " WHERE ".$typeWhere : $where." AND ".$typeWhere;
	}
	if(isset($_GET['getSearch']))
	{
		if(isset($_GET['getSearch']) and $_GET['getSearch'] == "td"){
		$dateWhere = " created_at like '$today_datee%'";
		}
		if(isset($_GET['getSearch']) and $_GET['getSearch'] == "yd"){
		$dateWhere = " created_at like '$yesterday_datee%'";
		}
		if(isset($_GET['getSearch']) and $_GET['getSearch'] == "lw"){
		$dateWhere = " created_at BETWEEN DATE_SUB( CURDATE() ,INTERVAL 7 DAY ) AND CURDATE()";
		}
		if(isset($_GET['getSearch']) and $_GET['getSearch'] == "lm"){
		$dateWhere = " created_at BETWEEN DATE_SUB( CURDATE() ,INTERVAL 30 DAY ) AND CURDATE()";
		}
		if(isset($_GET['getSearch']) and $_GET['getSearch'] == "ly"){
		$dateWhere = " created_at BETWEEN DATE_SUB( CURDATE() ,INTERVAL 1 YEAR) AND CURDATE()";
		}
		if (isset($dateWhere)) {
			$where = ($where == '') ? " WHERE ".$dateWhere : $where." AND ".$dateWhere;
		}
	}
	if(isset($_GET['ord']) && in_array($_GET['ord'], array('form_type','name','email','phone','exam_code','email_sent','created_at')))
	{
		$ty = (isset($_GET['ty']) && strtoupper($_GET['ty']) == 'ASC') ? 'ASC' : 'DESC';
		$orderby = "ORDER BY ".$_GET['ord'] ." ". $ty;
	}

	$result = mysql_query("$query $where $groupby $orderby");
	if(!$result || mysql_num_rows($result) < 1){
	$emptyError	=	"No record exist.";
	$show = '<input name="counter" type="hidden" value="0" />';
	}else{
	$emptyError	=	'<input type="submit" name="btn_delete" id="btn_delete" class="button" value="Delete Selected Items" onclick="return Cofirm();"  />';
	$show		 =	$objUser->getFormLeads($result);
	}

$today_date = date("Y-m-d");
$yesterday_date = date('Y-m-d', strtotime($today_date . ' - 1 day'));
		$qry = "SELECT count(*) FROM  tbl_form_leads WHERE created_at like '$today_date%'";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$leads_today = $row[0];
		$qry = "SELECT count(*) FROM tbl_form_leads WHERE created_at like '$yesterday_date%'";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$leads_yesterday = $row[0];
		$qry = "SELECT count(*) FROM tbl_form_leads WHERE created_at BETWEEN DATE_SUB( CURDATE() ,INTERVAL 7 DAY ) AND CURDATE()";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$leads_last_week = $row[0];
		$qry = "SELECT count(*) FROM tbl_form_leads WHERE created_at BETWEEN DATE_SUB( CURDATE() ,INTERVAL 30 DAY ) AND CURDATE()";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$leads_last_month = $row[0];
		$qry = "SELECT count(*) FROM tbl_form_leads WHERE created_at BETWEEN DATE_SUB( CURDATE() ,INTERVAL 1 YEAR) AND CURDATE()";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$leads_last_year = $row[0];

		$enquiryCount = mysql_fetch_array(mysql_query("SELECT count(*) FROM tbl_form_leads WHERE form_type='enquiry'"));
		$registrationCount = mysql_fetch_array(mysql_query("SELECT count(*) FROM tbl_form_leads WHERE form_type='registration'"));
		$examCount = mysql_fetch_array(mysql_query("SELECT count(*) FROM tbl_form_leads WHERE form_type='exam_request'"));
		$leads_enquiry = $enquiryCount ? $enquiryCount[0] : 0;
		$leads_registration = $registrationCount ? $registrationCount[0] : 0;
		$leads_exam = $examCount ? $examCount[0] : 0;

include("html/leads.html");

?>
