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

<table cellpadding="0" cellspacing="0" width="90%">

<tr>

<td valign="top"><img src="images/admin_icon.jpg" border="0" alt="Admin Header" /></td>

<td valign="top" align="right"><img src="images/admin_watermark.gif" border="0" alt="Admin Header" /></td>

</tr>

</table></div>



<table cellpadding="0" cellspacing="0" width="92%">

<tr>

<td width="190" class="leftside"><?php include ("menu.php"); ?></td>



<td width="610" valign="top" class="rightside"><h2>Stats !</h2>



Welcome to your <?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?>.



<br />



<form id="frm1" name="frm1" method="post" action="" enctype="multipart/form-data">







<br /><table cellpadding='0' cellspacing='0' class='list' width='87%'>



	 <tr>

    <td height="19" align="right" colspan="5">&nbsp;</td>

	</tr>

    

    <tr>

    <td colspan="4"  align="center">Stats :

	    &nbsp; FROM &nbsp; <input name="txt_StartDate" type="text" class="clsTxtBox"  id="txt_StartDate" readonly="readonly" value="<?=$_POST['txt_StartDate']?>"  style="width:100px; height:16px" />

           <img src="images/calendar-icon.gif" alt="select" width="22" height="18" align="absmiddle" class="calImg" 

				  style="cursor:hand" onclick="displayCalendarSe(document.frm1.txt_StartDate,'yyyy-mm-dd',this)" /> &nbsp; TO &nbsp; <input name="txt_StartDate2" type="text" class="clsTxtBox"  id="txt_StartDate2" readonly="readonly" value="<?=$_POST['txt_StartDate2']?>"  style="width:100px; height:16px" />

                  <img src="images/calendar-icon.gif" alt="select" width="22" height="18" align="absmiddle" class="calImg" 

				  style="cursor:hand" onclick="displayCalendarSe(document.frm1.txt_StartDate2,'yyyy-mm-dd',this)" />&nbsp;&nbsp;&nbsp;

      &nbsp;&nbsp;&nbsp;<input type="submit" name="Search" value="Show" />	  </td>

	  </tr>

  <tr>

    <td height="19" align="center" colspan="5" style="color:#FF0000; font-weight:bold;"><? echo $str22; ?></td>

	</tr>

    <tr>

    <td width="136" align="center"  class='header' colspan="4">Users Report</td>

    </tr>

    <tr>

    <td width="136" align="center"  class='header'>Period</td>

    <td class='header' width='150' align="center">User Registered</td>

    <td width="127" class='header' align="center">Got Sales</td>

    <td width="144" class='header' align="center">Not Got Sales</td>

    </tr>

    <tr>

    <td width="136" align="center" >Today</td>

    <td width='80' align="center"><?=$reg_today?></td>

    <td width="127" align="center"><a href="stats.php?getSearch=td"><?=$sold_today?></a></td>

    <td width="144" align="center"><?php echo $reg_today - $sold_today?></td>

    </tr>

    <tr>

      <td align="center" >Yesterday</td>

      <td align="center"><?=$reg_yesterday?></td>

      <td align="center"><a href="stats.php?getSearch=yd"><?=$sold_yesterday?></a></td>

      <td align="center"><?php echo $reg_yesterday - $sold_yesterday?></td>

    </tr>

    <tr>

    <td width="136" align="center" >Last 7 days</td>

    <td width='80' align="center"><?=$reg_last_week?></td>

    <td width="127" align="center"><a href="stats.php?getSearch=lw"><?=$sold_last_week?></a></td>

    <td width="144" align="center"><?php echo $reg_last_week - $sold_last_week?></td>

    </tr>

    <tr>

    <td width="136" align="center" >Last 30 Days</td>

    <td width='80' align="center"><?=$reg_last_month?></td>

    <td width="127" align="center"><a href="stats.php?getSearch=lm"><?=$sold_last_month?></a></td>

    <td width="144" align="center"><?php echo $reg_last_month- $sold_last_month?></td>

    </tr>

    <tr>

    <td width="136" align="center" >Last 1 Year</td>

    <td width='80' align="center"><?=$reg_last_year?></td>

    <td width="127" align="center"><a href="stats.php?getSearch=ly"><?=$sold_last_year?></a></td>

    <td width="144" align="center"><?php echo $reg_last_year - $sold_last_year?></td>

    </tr>

    <tr>

    <td width="136" align="center" class="error" >In Selected Period</td>

    <td width='80' align="center" class="error"><?=$reg_sel?></td>

    <td width="127" align="center" class="error"><?=$sold_sel?></td>

    <td width="144" align="center" class="error"><?php echo $reg_sel - $sold_sel?></td>

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

    <td width="136" align="center"  class='header' colspan="6">Sales Report</td>

    </tr>

    <tr>

    <td width="136" align="center"  class='header'>Today</td>

    <td width="136" align="center"  class='header'>Yesterday</td>

    <td width="136" align="center"  class='header'>Last 7 Days</td>

    <td class='header' width='150' align="center">Last 30 Days</td>

    <td width="127" class='header' align="center">Last 1 Year</td>

    <td width="127" class='header' align="center">In Selected Period</td>

    </tr>

    <tr>

    <td width="136" align="center">$<?=$sale_today?></td>

    <td width="136" align="center">$<?=$sale_yesterday?></td>

    <td width="136" align="center">$<?=$sale_week?></td>

    <td width='80' align="center"> $<?=$sale_month?></td>

    <td width="127" align="center">$<?=$sale_year?></td>

    <td width="144" align="center" class="error">$<?=$sale_per?></td>

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

    <td width="136" align="center"  class='header' colspan="6">Manual Assigned</td>

    </tr>

    <tr>

    <td width="136" align="center"  class='header'>Today</td>

    <td width="136" align="center"  class='header'>Yesterday</td>

    <td width="136" align="center"  class='header'>Last 7 Days</td>

    <td class='header' width='150' align="center">Last 30 Days</td>

    <td width="127" class='header' align="center">Last 1 Year</td>

    <td width="127" class='header' align="center">In Selected Period</td>

    </tr>

    <tr>

    <td width="136" align="center" >$<?=$manual_today?></td>

    <td width="136" align="center" >$

      <?=$sale_yesterday?></td>

    <td width="136" align="center" >$<?=$manual_last_week?></td>

    <td width='80' align="center">$<?=$manual_last_month?></td>

    <td width="127" align="center">$<?=$manual_last_year?></td>

    <td width="144" align="center" class="error">$<?=$manual_sel?></td>

    </tr>

</table>



<table>



<tr>  



<td valign="top" class="rightside"> 



	 </td>





</table></form>

<!-- Hunain -->

<table cellpadding='0' cellspacing='0' class='list' width='87%'>

 <tr>

    <td height="19" align="right" colspan="5">&nbsp;</td>

	</tr>

	<tr>

	<td width="127" class='header' align="center">Email</td>

	<td class='header' width='133' align="center">Exam Code </td>

	<td width="136" align="center"  class='header'>Date</td>

    

    </tr>



  <!-- LOOP THROUGH USERS -->



  <?php echo $show ;?>

</table></td>







</table>





<? include("footer.php")?>

</body>



</html>



