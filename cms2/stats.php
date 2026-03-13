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

$query = "select * from order_master";

//$query = "select user.user_id, user.user_email, order.ID, order.Cust_ID, order.OrderDate, order.OrderDesc from order_master order, tbl_user user where user.user_id=order.Cust_ID ";

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

		$where = " WHERE OrderDate like '$today_datee%'";

		}

		////////////Yesterday/////////////////

		if(isset($_GET['getSearch']) and $_GET['getSearch'] == "yd"){

		$where = " WHERE OrderDate like '$yesterday_datee%'";

		}

		////////////1 Week /////////////////

		if(isset($_GET['getSearch']) and $_GET['getSearch'] == "lw"){

		$where = "  WHERE OrderDate BETWEEN DATE_SUB( CURDATE() ,INTERVAL 7 DAY ) AND CURDATE()";

		}

		////////////Last Month /////////////////

		if(isset($_GET['getSearch']) and $_GET['getSearch'] == "lm"){

		$where = " WHERE OrderDate BETWEEN DATE_SUB( CURDATE() ,INTERVAL 30 DAY ) AND CURDATE()";

		}

		////////////1 Year/////////////////

		if(isset($_GET['getSearch']) and $_GET['getSearch'] == "ly"){

		$where = " WHERE OrderDate BETWEEN DATE_SUB( CURDATE() ,INTERVAL 1 YEAR) AND CURDATE()";

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

	$show		 =	$objUser->getSaleinfo($result);














/////////////////////Hunain Code End///////////////////////////////////////

	

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


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">

<head>

<title><?=$websitetitle?></title>

<link href="new.css" rel="stylesheet" type="text/css" />

<link href="style1.css" rel="stylesheet" type="text/css" />

<style type="text/css">

<!--

.style1 {color: #FF0000}

-->

</style>

<script language="JavaScript" src="../includes/js/calendar.js"></script>

<script type="text/javascript" src="Editor3/scripts/innovaeditor.js"></script>

<script language="javascript" type="text/javascript">

function Cofirm()

{

var mes=window.confirm ("Are You Sure To Delete the Selected Item");

if (mes==true)

{

return true;

}

else

{

return false;

}

}

</script>

<script language="JavaScript" src="../includes/js/functions.js"></script></head>

</head>

<body>



<div class="topbar">

<table cellpadding="0" cellspacing="0" width="100%">

<tr>

<td valign="top"><img src="images/admin_icon.png" border="0" alt="Admin Header" /></td>

<td valign="top" align="right">
<?php if(isset($_SESSION['strAdmin'])): ?>
	<div style="display: inline-block; vertical-align: middle;">
		<span style="color: #ffffff; margin-right: 15px; font-weight: 500;">Welcome, <?php echo htmlspecialchars($_SESSION['strAdmin']); ?></span>
		<a href="logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Logout</a>
	</div>
<?php else: ?>
	<div style="display: inline-block; vertical-align: middle;">
		<a href="index.php" style="color: #ffffff; text-decoration: none; padding: 8px 16px; background-color: rgba(255, 255, 255, 0.2); border-radius: 4px; font-weight: 500; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='rgba(255, 255, 255, 0.3)'" onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.2)'">Login</a>
	</div>
<?php endif; ?>
</td>

</tr>

</table></div>



<table cellpadding="0" cellspacing="0" width="100%">

<tr>

<td width="190" class="leftside"><?php include ("menu.php"); ?></td>



<td width="610" valign="top" class="rightside">
<div style="margin-bottom: 32px;">
	<h2 style="margin: 0 0 8px 0; font-size: 28px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 12px;">
		<i class="fa-solid fa-chart-line" style="color: #3c85ba;"></i>
		Statistics Dashboard
	</h2>
	<p style="margin: 0; color: #64748b; font-size: 14px;">View comprehensive statistics about user registrations, sales, and manual assignments.</p>
</div>



<form id="frm1" name="frm1" method="post" action="" enctype="multipart/form-data">







<div style="background: #ffffff; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0;">
	<div style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 16px;">
		<div style="display: flex; align-items: center; gap: 8px;">
			<label style="font-weight: 600; color: #1e293b; font-size: 14px;">Filter by Date Range:</label>
		</div>
		<div style="display: flex; align-items: center; gap: 8px;">
			<span style="color: #64748b; font-weight: 500;">From:</span>
			<input name="txt_StartDate" type="text" class="clsTxtBox" id="txt_StartDate" readonly="readonly" value="<?=$_POST['txt_StartDate']?>" style="width: 120px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 14px;" />
			<img src="images/calendar-icon.gif" alt="select" width="22" height="18" align="absmiddle" class="calImg" style="cursor: pointer;" onclick="displayCalendarSe(document.frm1.txt_StartDate,'yyyy-mm-dd',this)" />
		</div>
		<div style="display: flex; align-items: center; gap: 8px;">
			<span style="color: #64748b; font-weight: 500;">To:</span>
			<input name="txt_StartDate2" type="text" class="clsTxtBox" id="txt_StartDate2" readonly="readonly" value="<?=$_POST['txt_StartDate2']?>" style="width: 120px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 14px;" />
			<img src="images/calendar-icon.gif" alt="select" width="22" height="18" align="absmiddle" class="calImg" style="cursor: pointer;" onclick="displayCalendarSe(document.frm1.txt_StartDate2,'yyyy-mm-dd',this)" />
		</div>
		<button type="submit" name="Search" value="Show" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 20px; font-size: 14px; font-weight: 600; color: #ffffff; background: linear-gradient(135deg, #3c85ba 0%, #2d6a9a 100%); border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(60, 133, 186, 0.2);">
			<i class="fa-solid fa-filter"></i>
			Show Stats
		</button>
	</div>
	<?php if (isset($str22) && $str22): ?>
	<div style="margin-top: 16px; padding: 12px 16px; background: #fef2f2; border-radius: 8px; border-left: 4px solid #dc2626;">
		<p style="margin: 0; color: #dc2626; font-weight: 600; font-size: 14px;"><? echo $str22; ?></p>
	</div>
	<?php endif; ?>
</div>

<table cellpadding='0' cellspacing='0' class='list' width='100%' style="margin-bottom: 24px;">

    <tr>
    <td width="136" align="center" class='header' colspan="4" style="font-size: 16px; padding: 20px;">
		<i class="fa-solid fa-users" style="margin-right: 8px;"></i>
		Users Report
	</td>
    </tr>

    <tr>
    <td width="136" align="center" class='header' style="padding: 16px 20px;">Period</td>
    <td class='header' width='150' align="center" style="padding: 16px 20px;">User Registered</td>
    <td width="127" class='header' align="center" style="padding: 16px 20px;">Got Sales</td>
    <td width="144" class='header' align="center" style="padding: 16px 20px;">Not Got Sales</td>
    </tr>

    <tr style="transition: background-color 0.2s ease;">
    <td width="136" align="center" class="item" style="padding: 16px 20px; font-weight: 500; color: #1e293b;">Today</td>
    <td width='80' align="center" class="item" style="padding: 16px 20px; font-size: 16px; font-weight: 600; color: #3c85ba;"><?=$reg_today?></td>
    <td width="127" align="center" class="item" style="padding: 16px 20px;">
		<a href="stats.php?getSearch=td" style="color: #16a34a; text-decoration: none; font-weight: 600; font-size: 16px; transition: color 0.2s ease;" onmouseover="this.style.color='#15803d';" onmouseout="this.style.color='#16a34a';"><?=$sold_today?></a>
	</td>
    <td width="144" align="center" class="item" style="padding: 16px 20px; font-size: 16px; font-weight: 500; color: #64748b;"><?php echo $reg_today - $sold_today?></td>
    </tr>

    <tr style="transition: background-color 0.2s ease;">
      <td align="center" class="item" style="padding: 16px 20px; font-weight: 500; color: #1e293b;">Yesterday</td>
      <td align="center" class="item" style="padding: 16px 20px; font-size: 16px; font-weight: 600; color: #3c85ba;"><?=$reg_yesterday?></td>
      <td align="center" class="item" style="padding: 16px 20px;">
		<a href="stats.php?getSearch=yd" style="color: #16a34a; text-decoration: none; font-weight: 600; font-size: 16px; transition: color 0.2s ease;" onmouseover="this.style.color='#15803d';" onmouseout="this.style.color='#16a34a';"><?=$sold_yesterday?></a>
	</td>
      <td align="center" class="item" style="padding: 16px 20px; font-size: 16px; font-weight: 500; color: #64748b;"><?php echo $reg_yesterday - $sold_yesterday?></td>
    </tr>

    <tr style="transition: background-color 0.2s ease;">
    <td width="136" align="center" class="item" style="padding: 16px 20px; font-weight: 500; color: #1e293b;">Last 7 days</td>
    <td width='80' align="center" class="item" style="padding: 16px 20px; font-size: 16px; font-weight: 600; color: #3c85ba;"><?=$reg_last_week?></td>
    <td width="127" align="center" class="item" style="padding: 16px 20px;">
		<a href="stats.php?getSearch=lw" style="color: #16a34a; text-decoration: none; font-weight: 600; font-size: 16px; transition: color 0.2s ease;" onmouseover="this.style.color='#15803d';" onmouseout="this.style.color='#16a34a';"><?=$sold_last_week?></a>
	</td>
    <td width="144" align="center" class="item" style="padding: 16px 20px; font-size: 16px; font-weight: 500; color: #64748b;"><?php echo $reg_last_week - $sold_last_week?></td>
    </tr>

    <tr style="transition: background-color 0.2s ease;">
    <td width="136" align="center" class="item" style="padding: 16px 20px; font-weight: 500; color: #1e293b;">Last 30 Days</td>
    <td width='80' align="center" class="item" style="padding: 16px 20px; font-size: 16px; font-weight: 600; color: #3c85ba;"><?=$reg_last_month?></td>
    <td width="127" align="center" class="item" style="padding: 16px 20px;">
		<a href="stats.php?getSearch=lm" style="color: #16a34a; text-decoration: none; font-weight: 600; font-size: 16px; transition: color 0.2s ease;" onmouseover="this.style.color='#15803d';" onmouseout="this.style.color='#16a34a';"><?=$sold_last_month?></a>
	</td>
    <td width="144" align="center" class="item" style="padding: 16px 20px; font-size: 16px; font-weight: 500; color: #64748b;"><?php echo $reg_last_month- $sold_last_month?></td>
    </tr>

    <tr style="transition: background-color 0.2s ease;">
    <td width="136" align="center" class="item" style="padding: 16px 20px; font-weight: 500; color: #1e293b;">Last 1 Year</td>
    <td width='80' align="center" class="item" style="padding: 16px 20px; font-size: 16px; font-weight: 600; color: #3c85ba;"><?=$reg_last_year?></td>
    <td width="127" align="center" class="item" style="padding: 16px 20px;">
		<a href="stats.php?getSearch=ly" style="color: #16a34a; text-decoration: none; font-weight: 600; font-size: 16px; transition: color 0.2s ease;" onmouseover="this.style.color='#15803d';" onmouseout="this.style.color='#16a34a';"><?=$sold_last_year?></a>
	</td>
    <td width="144" align="center" class="item" style="padding: 16px 20px; font-size: 16px; font-weight: 500; color: #64748b;"><?php echo $reg_last_year - $sold_last_year?></td>
    </tr>

    <tr style="background: #fef2f2; transition: background-color 0.2s ease;">
    <td width="136" align="center" class="item" style="padding: 16px 20px; font-weight: 600; color: #dc2626;">In Selected Period</td>
    <td width='80' align="center" class="item" style="padding: 16px 20px; font-size: 16px; font-weight: 700; color: #dc2626;"><?=$reg_sel?></td>
    <td width="127" align="center" class="item" style="padding: 16px 20px; font-size: 16px; font-weight: 700; color: #dc2626;"><?=$sold_sel?></td>
    <td width="144" align="center" class="item" style="padding: 16px 20px; font-size: 16px; font-weight: 700; color: #dc2626;"><?php echo $reg_sel - $sold_sel?></td>
    </tr>

    <tr>

    <td width="136" align="center" >&nbsp;</td>

    <td width='80' align="center">&nbsp;</td>

    <td width="127" align="center">&nbsp;</td>

    <td width="144" align="center">&nbsp;</td>

    </tr>

    </table>

    <table cellpadding='0' cellspacing='0' class='list' width='87%'>

    <tr>
    <td width="136" align="center" class='header' colspan="6" style="font-size: 16px; padding: 20px;">
		<i class="fa-solid fa-dollar-sign" style="margin-right: 8px;"></i>
		Sales Report
	</td>
    </tr>

    <tr>
    <td width="136" align="center" class='header' style="padding: 16px 20px;">Today</td>
    <td width="136" align="center" class='header' style="padding: 16px 20px;">Yesterday</td>
    <td width="136" align="center" class='header' style="padding: 16px 20px;">Last 7 Days</td>
    <td class='header' width='150' align="center" style="padding: 16px 20px;">Last 30 Days</td>
    <td width="127" class='header' align="center" style="padding: 16px 20px;">Last 1 Year</td>
    <td width="127" class='header' align="center" style="padding: 16px 20px;">In Selected Period</td>
    </tr>

    <tr style="transition: background-color 0.2s ease;">
    <td width="136" align="center" class="item" style="padding: 16px 20px; font-size: 18px; font-weight: 700; color: #16a34a;">$<?= number_format($sale_today, 2) ?></td>
    <td width="136" align="center" class="item" style="padding: 16px 20px; font-size: 18px; font-weight: 700; color: #16a34a;">$<?= number_format($sale_yesterday, 2) ?></td>
    <td width="136" align="center" class="item" style="padding: 16px 20px; font-size: 18px; font-weight: 700; color: #16a34a;">$<?= number_format($sale_week, 2) ?></td>
    <td width='80' align="center" class="item" style="padding: 16px 20px; font-size: 18px; font-weight: 700; color: #16a34a;">$<?= number_format($sale_month, 2) ?></td>
    <td width="127" align="center" class="item" style="padding: 16px 20px; font-size: 18px; font-weight: 700; color: #16a34a;">$<?= number_format($sale_year, 2) ?></td>
    <td width="144" align="center" class="item" style="padding: 16px 20px; font-size: 18px; font-weight: 700; color: #dc2626; background: #fef2f2;">$<?= number_format($sale_per, 2) ?></td>
    </tr>

    <tr>

    <td width="136" align="center" >&nbsp;</td>

    <td width="136" align="center" >&nbsp;</td>

    <td width="136" align="center" >&nbsp;</td>

    <td width='80' align="center">&nbsp;</td>

    <td width="127" align="center">&nbsp;</td>

    <td width="144" align="center">&nbsp;</td>

    </tr>

    <tr>
    <td width="136" align="center" class='header' colspan="6" style="font-size: 16px; padding: 20px;">
		<i class="fa-solid fa-hand-pointer" style="margin-right: 8px;"></i>
		Manual Assigned
	</td>
    </tr>

    <tr>
    <td width="136" align="center" class='header' style="padding: 16px 20px;">Today</td>
    <td width="136" align="center" class='header' style="padding: 16px 20px;">Yesterday</td>
    <td width="136" align="center" class='header' style="padding: 16px 20px;">Last 7 Days</td>
    <td class='header' width='150' align="center" style="padding: 16px 20px;">Last 30 Days</td>
    <td width="127" class='header' align="center" style="padding: 16px 20px;">Last 1 Year</td>
    <td width="127" class='header' align="center" style="padding: 16px 20px;">In Selected Period</td>
    </tr>

    <tr style="transition: background-color 0.2s ease;">
    <td width="136" align="center" class="item" style="padding: 16px 20px; font-size: 18px; font-weight: 700; color: #0369a1;">$<?= number_format($manual_today, 2) ?></td>
    <td width="136" align="center" class="item" style="padding: 16px 20px; font-size: 18px; font-weight: 700; color: #0369a1;">$<?= number_format($manual_yesterday, 2) ?></td>
    <td width="136" align="center" class="item" style="padding: 16px 20px; font-size: 18px; font-weight: 700; color: #0369a1;">$<?= number_format($manual_last_week, 2) ?></td>
    <td width='80' align="center" class="item" style="padding: 16px 20px; font-size: 18px; font-weight: 700; color: #0369a1;">$<?= number_format($manual_last_month, 2) ?></td>
    <td width="127" align="center" class="item" style="padding: 16px 20px; font-size: 18px; font-weight: 700; color: #0369a1;">$<?= number_format($manual_last_year, 2) ?></td>
    <td width="144" align="center" class="item" style="padding: 16px 20px; font-size: 18px; font-weight: 700; color: #dc2626; background: #fef2f2;">$<?= number_format($manual_sel, 2) ?></td>
    </tr>

</table>



<table>



<tr>  



<td valign="top" class="rightside"> 



	 </td>





</table></form>

<!-- Hunain -->

<div style="margin-top: 32px;">
	<h3 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
		<i class="fa-solid fa-list" style="color: #3c85ba;"></i>
		Order Details
	</h3>
</div>

<table cellpadding='0' cellspacing='0' class='list' width='100%' style="margin-bottom: 24px;">

	<tr>
	<td width="127" class='header' align="center" style="padding: 16px 20px;">Email</td>
	<td class='header' width='133' align="center" style="padding: 16px 20px;">Exam Code</td>
	<td width="136" align="center" class='header' style="padding: 16px 20px;">Date</td>
    </tr>



  <!-- LOOP THROUGH USERS -->



  <?php echo $show ;?>

</table></td>







</table>





<? include("footer.php")?>

</body>



</html>



