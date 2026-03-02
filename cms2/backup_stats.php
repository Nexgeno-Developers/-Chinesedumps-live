<?php
ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classUser.php");
	include("../includes/common/classes/classPagingAdmin.php");
	include("../functions.php");
//---------------------------------------------------------------
	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
	$objUser	 = new classUser($objDBcon);	// VALIDATION CLASS classCategory
	
include_once 'functions.php';
$ccAdminData = get_session_data();
//-----------------------------------------------------------------
$today_date = date("Y-m-d");
$yesterday_date = date('Y-m-d', strtotime($today_date . ' - 1 day'));


		$reg_last_year = 0;
		$reg_last_month = 0;
		$reg_last_week = 0;
		$reg_yesterday = 0;
		$reg_today = 0;
		$sold_sel = 0;
		$sale_per = 0;
		$sold_today = 0;
		$sale_today = 0;
		$sold_yesterday = 0;
		$sale_yesterday = 0;
		$sold_last_week = 0;
		$sale_week = 0;
		$sold_last_month = 0;
		$sale_month = 0;
		$sold_last_year = 0;
		$sale_year = 0;
		$reg_sel = 0;
//----------------------------------------------------------------------------------------------------	
// Register Today
		$qry = "SELECT count(*) FROM tbl_user WHERE creatDate like '$today_date%'";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$reg_today = $row[0];
// Register Yesterday
		$qry = "SELECT count(*) FROM tbl_user WHERE creatDate like '$yesterday_date%'";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$reg_yesterday = $row[0];
// Register last week
		$qry = "SELECT count(*) FROM tbl_user WHERE creatDate BETWEEN DATE_SUB( CURDATE() ,INTERVAL 7 DAY ) AND CURDATE()";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$reg_last_week = $row[0];
// Register last month
		$qry = "SELECT count(*) FROM tbl_user WHERE creatDate BETWEEN DATE_SUB( CURDATE() ,INTERVAL 30 DAY ) AND CURDATE()";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$reg_last_month = $row[0];
// Register last year
		$qry = "SELECT count(*) FROM tbl_user WHERE creatDate BETWEEN DATE_SUB( CURDATE() ,INTERVAL 1 YEAR) AND CURDATE()";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$reg_last_year = $row[0];
//----------------------------------------------------------------------------------------------------	
// Today users got sales
		$qry = "SELECT count(distinct(Cust_ID)),Sum(Net_Amount) FROM order_master WHERE Status='1' and Cust_ID in (SELECT user_id FROM tbl_user WHERE creatDate like '$today_date%')";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$sold_today = $row[0];
		$sale_today = $row[1];
// Yesterday users got sales
		$qry = "SELECT count(distinct(Cust_ID)),Sum(Net_Amount) FROM order_master WHERE Status='1' and Cust_ID in (SELECT user_id FROM tbl_user WHERE creatDate like '$yesterday_date%')";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$sold_yesterday = $row[0];
		$sale_yesterday = $row[1];
// last week users got sales
		$qry = "SELECT count(distinct(Cust_ID)),Sum(Net_Amount) FROM order_master WHERE Status='1' and Cust_ID in (SELECT user_id FROM tbl_user WHERE creatDate BETWEEN DATE_SUB( CURDATE() ,INTERVAL 7 DAY ) AND CURDATE())";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$sold_last_week = $row[0];
		$sale_week = $row[1];
// last month users got sales
		$qry = "SELECT count(distinct(Cust_ID)),Sum(Net_Amount) FROM order_master WHERE Status='1' and Cust_ID in (SELECT user_id FROM tbl_user WHERE creatDate BETWEEN DATE_SUB( CURDATE() ,INTERVAL 30 DAY ) AND CURDATE())";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$sold_last_month = $row[0];
		$sale_month = $row[1];
// last year users got sales
		$qry = "SELECT count(distinct(Cust_ID)),Sum(Net_Amount) FROM order_master WHERE Status='1' and Cust_ID in (SELECT user_id FROM tbl_user WHERE creatDate BETWEEN DATE_SUB( CURDATE() ,INTERVAL 1 YEAR) AND CURDATE())";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$sold_last_year = $row[0];
		$sale_year = $row[1];
		
//----------------------------------------------------------------------------------------------------	
// Today Manual sales
		$qry = "Select sum(E.exam_pri0) from tbl_exam_user EU Left Join tbl_exam as E on EU.exam_id = E.exam_id where EU.assign_date like '$today_date%'";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$manual_today = $row[0];
// Yesterday Manual sales
		$qry = "Select sum(E.exam_pri0) from tbl_exam_user EU Left Join tbl_exam as E on EU.exam_id = E.exam_id where EU.assign_date like '$yesterday_date%'";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$manual_yesterday = $row[0];
// last week Manual sales
		$qry = "Select sum(E.exam_pri0) from tbl_exam_user EU Left Join tbl_exam as E on EU.exam_id = E.exam_id where EU.assign_date BETWEEN DATE_SUB(CURDATE() ,INTERVAL 7 DAY ) AND CURDATE()";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$manual_last_week = $row[0];
// last month Manual sales
		$qry = "Select sum(E.exam_pri0) from tbl_exam_user EU Left Join tbl_exam as E on EU.exam_id = E.exam_id where EU.assign_date BETWEEN DATE_SUB(CURDATE() ,INTERVAL 30 DAY ) AND CURDATE()";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$manual_last_month = $row[0];
// last year Manual sales
		$qry = "Select sum(E.exam_pri0) from tbl_exam_user EU Left Join tbl_exam as E on EU.exam_id = E.exam_id where EU.assign_date BETWEEN DATE_SUB(CURDATE() ,INTERVAL 1 YEAR ) AND CURDATE()";
		$result1 = mysql_query($qry);
		$row = mysql_fetch_array($result1);
		$manual_last_year = $row[0];
		
	if(isset($_POST['Search']))
	{
		if($_POST['txt_StartDate']!='')
		{
			$stDate	= " creatDate  BETWEEN '".$_POST['txt_StartDate']."' and '";
			$m_stDate	= " EU.assign_date  BETWEEN '".$_POST['txt_StartDate']."' and '";
			if($_POST['txt_StartDate2']!='')
			{
				$stDate	.= $_POST['txt_StartDate2']."'";
				$m_stDate	.= $_POST['txt_StartDate2']."'";
			}
			else
			{
				$stDate	.= date("Y-m-d")."'";
				$m_stDate	.= date("Y-m-d")."'";
			}
	// Register selected period
			$qry = "SELECT count(*) FROM tbl_user WHERE $stDate";
			$result1 = mysql_query($qry);
			$row = mysql_fetch_array($result1);
			$reg_sel = $row[0];
	// Register selected period
			$qry = "SELECT count(distinct(Cust_ID)),Sum(Net_Amount) FROM order_master WHERE Status='1' and Cust_ID in (SELECT user_id FROM tbl_user WHERE $stDate)";
			$result1 = mysql_query($qry);
			$row = mysql_fetch_array($result1);
			$sold_sel = $row[0];
			$sale_per = $row[1];
	// Manual sales in period
			$qry = "Select sum(E.exam_pri0) from tbl_exam_user EU Left Join tbl_exam as E on EU.exam_id = E.exam_id where $m_stDate";
			$result1 = mysql_query($qry);
			$row = mysql_fetch_array($result1);
			$manual_sel = $row[0];
		}
	}
//------------------------html---------------------------------------

include("html/stats.html");

//------------------------html---------------------------------------

?>