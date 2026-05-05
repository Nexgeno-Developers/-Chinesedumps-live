<?php 
session_start();
include ("../includes/common/inc/sessionheader.php");
include_once ("../includes/config/classDbConnection.php");
include_once 'functions.php';
$ccAdminData = get_session_data();
$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT

$fetchf		=	mysql_fetch_array(mysql_query("SELECT *, count(*) as n FROM tbl_demo_download  group by itemid order by n desc"));
$Recordnum	=	$fetchf['n'];
$examcode	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_exam where exam_id='".$fetchf['itemid']."'"));

$fetchf2		=	mysql_fetch_array(mysql_query("SELECT *, count(*) as m FROM tbl_full_download  group by itemid order by m desc"));
$Recordnum2	=	$fetchf2['m'];
$examcode2	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_exam where exam_id='".$fetchf2['itemid']."'"));

$hotven		=	mysql_fetch_array(mysql_query("SELECT *, count(*) as hot FROM order_detail  group by VendorID order by hot desc"));
$highthot	=	$hotven['hot'];
$hotvendo2	=	mysql_fetch_array(mysql_query("SELECT * FROM tbl_vendors where ven_id='".$hotven['VendorID']."'"));

$date = date("Y-m-d",strtotime("-24 hours"));
$fetchu		=	mysql_fetch_array(mysql_query("SELECT count(*) as user FROM `tbl_user` where creatDate='$date'"));
$tot_user	=	$fetchu['user'];
$fetcho		=	mysql_fetch_array(mysql_query("SELECT count(*) as tot_ord, sum(Net_Amount) as tot_sale FROM `order_master` where OrderDate='$date'"));
$tot_order	=	$fetcho['tot_ord'];
$tot_sale	=	$fetcho['tot_sale'];

$summaryStaffs		= mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_count FROM tbladmin"));
$summaryVendors		= mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_count FROM tbl_vendors"));
$summaryCertificates= mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_count FROM tbl_cert"));
$summaryExams		= mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_count FROM tbl_exam"));
$summaryUsers		= mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_count FROM tbl_user"));
$summaryOrders		= mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_count FROM order_master"));

$currentMonthStart	= date("Y-m-01");
$currentMonthEnd	= date("Y-m-t");
$lastMonthStart		= date("Y-m-01", strtotime("-1 month"));
$lastMonthEnd		= date("Y-m-t", strtotime("-1 month"));

$summaryCurrentMonthSale = mysql_fetch_array(mysql_query("SELECT COALESCE(SUM(Net_Amount),0) AS total_amount FROM order_master WHERE DATE(OrderDate) BETWEEN '".$currentMonthStart."' AND '".$currentMonthEnd."'"));
$summaryLastMonthSale	 = mysql_fetch_array(mysql_query("SELECT COALESCE(SUM(Net_Amount),0) AS total_amount FROM order_master WHERE DATE(OrderDate) BETWEEN '".$lastMonthStart."' AND '".$lastMonthEnd."'"));

$totalStaffs			= isset($summaryStaffs['total_count']) ? (int)$summaryStaffs['total_count'] : 0;
$totalVendors			= isset($summaryVendors['total_count']) ? (int)$summaryVendors['total_count'] : 0;
$totalCertificates		= isset($summaryCertificates['total_count']) ? (int)$summaryCertificates['total_count'] : 0;
$totalExams				= isset($summaryExams['total_count']) ? (int)$summaryExams['total_count'] : 0;
$totalUsers				= isset($summaryUsers['total_count']) ? (int)$summaryUsers['total_count'] : 0;
$totalOrders			= isset($summaryOrders['total_count']) ? (int)$summaryOrders['total_count'] : 0;
$currentMonthSaleAmount	= isset($summaryCurrentMonthSale['total_amount']) ? (float)$summaryCurrentMonthSale['total_amount'] : 0;
$lastMonthSaleAmount	= isset($summaryLastMonthSale['total_amount']) ? (float)$summaryLastMonthSale['total_amount'] : 0;

$staffListUrl			= "manageadmin.php";
$vendorsListUrl			= "vendormanage.php";
$certificatesListUrl	= "certifmanage.php";
$examsListUrl			= "exammanage.php";
$usersListUrl			= "manageuser.php";
$ordersListUrl			= "masterorder.php";
$currentMonthSalesUrl	= "masterorder.php?".http_build_query(array("from_date" => $currentMonthStart, "to_date" => $currentMonthEnd));
$lastMonthSalesUrl		= "masterorder.php?".http_build_query(array("from_date" => $lastMonthStart, "to_date" => $lastMonthEnd));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$websitetitle?></title>
<link href="new.css" rel="stylesheet" type="text/css" />
<style>
.d-none{display:none;}
.dashboard-shell{

}
.dashboard-title{
	font-size: 32px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 8px 0;
    letter-spacing: -0.02em;
}
.dashboard-subtitle{
	font-size: 16px;
    color: #64748b;
    margin: 0;
    line-height: 1.6;
}
.visit-summary{
	margin-top:14px !important;
	border:1px solid #d7e4ef;
	border-radius:10px;
	overflow:hidden;
	background:#ffffff;
}
.visit-summary .stat0{
	padding:0;
}
.visit-summary-inner{
	width:100%;
}
.visit-metric{
	padding:14px 16px;
	border-right:1px solid #e4edf5;
	vertical-align:top;
}
.visit-metric:last-child{
	border-right:0;
}
.metric-label{
	display:block;
	font-size:11px;
	color:#6f859c;
	text-transform:uppercase;
	letter-spacing:0.3px;
	margin-bottom:4px;
}
.metric-value{
	display:block;
	font-size:20px;
	line-height:1.2;
	color:#163654;
	font-weight:bold;
}
.metric-value.small{
	font-size:12px;
	font-weight:normal;
	color:#3c5875;
}
.summary-grid{
	margin-top:14px !important;
	border-collapse:separate;
	border-spacing:10px;
}
.summary-card-cell{
	background: #ffffff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
    transition: transform 0.2s, box-shadow 0.2s;
}
.summary-card-cell:hover{
	transform:translateY(-2px);
	box-shadow:0 8px 18px rgba(23,58,88,0.12);
	border-color:#c8daea;
}
.summary-card-link{
	display:block;
	padding:14px 16px;
	text-decoration:none;
	color:#233f5b;
}
.summary-card-title{
	font-size:11px;
	font-weight:bold;
	text-transform:uppercase;
	letter-spacing:0.4px;
	color:#6e8299;
}
.summary-card-value{
	margin-top:8px;
	font-size:25px;
	line-height:1;
	font-weight:bold;
	color:#0f3c67;
}
.summary-card-hint{
	margin-top:8px;
	font-size:11px;
	color:#7d8ea1;
}
@media (max-width: 1100px){
	.summary-grid{
		border-spacing:8px;
	}
	.summary-card-value{
		font-size:22px;
	}
	.visit-metric{
		padding:12px;
	}
}
</style>
</head>
<body>

<div class="topbar">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td valign="top"><img src="images/admin_icon.png" alt="Admin Header" width="200" height="" border="0" /></td>
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
<td width="810" class="rightside">
<div class="dashboard-shell">
<h2 class="dashboard-title">Hello, Admin!</h2>
<p class="dashboard-subtitle">Welcome to your <?=$websitename?> control panel. Here you can manage and modify every aspect of your <?=$websitename?> website.</p>
<table width="100%" border="0" class="stats d-none">
  <tr>
    <td width="2%">&nbsp;</td>
    <td width="29%"><strong><?php echo $websitename;?> Admin System:</strong> 1.0</td>
    <td width="14%"><strong>Security : </strong>Enable </td>
    <td width="32%">
<?php
$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$start = $time;
$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$finish = $time;
$totaltime = ($finish - $start);
printf("This page took %f seconds to load.", $totaltime);
?></td>
    <td width="25%"><div align="left"><strong>Your IP</strong>: <?php
function getip() {
    if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
        return $_SERVER["HTTP_CLIENT_IP"];
    } elseif (isset($_SERVER["REMOTE_ADDR"])) {
        return $_SERVER["REMOTE_ADDR"];
    }
    return "";
}
echo htmlspecialchars(getip());
?>
      </td>
    </tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0' class='d-none stats visit-summary'>
<tr>
<td class='stat0'>
  <table width="100%" cellpadding='0' cellspacing='0' class="visit-summary-inner">
  <tr>

  <td width="33%" class="visit-metric"><span class="metric-label">Total Admin's</span><span class="metric-value">1</span></td>
  <td width="33%" align='left' class="visit-metric"><span class="metric-label">Total Visits</span><span class="metric-value"><?php

$filename = 'hitcount.txt';
$handle = fopen($filename, 'r');
$hits = trim(fgets($handle)) + 1;
fclose($handle);

$handle = fopen($filename, 'w');
fwrite($handle, $hits);
fclose($handle);

// Uncomment the next line (remove //) to display the number of hits on your page.
//echo $hits;
echo $hits;
?></span></td>
  <td width="33%" align='left' class="visit-metric"><span class="metric-label">Last Visit</span><span class="metric-value small"><script type="text/javascript">

/***********************************************
* Display time of last visit script- by JavaScriptKit.com
* This notice MUST stay intact for use
* Visit JavaScript Kit at http://www.javascriptkit.com/ for this script and more
***********************************************/

var lastvisit=new Object()
lastvisit.firstvisitmsg=" Welcome Your First Visit!" //Change first visit message here
lastvisit.subsequentvisitmsg=" [displaydate]" //Change subsequent visit message here

lastvisit.getCookie=function(Name){ //get cookie value
var re=new RegExp(Name+"=[^;]+", "i"); //construct RE to search for target name/value pair
if (document.cookie.match(re)) //if cookie found
return document.cookie.match(re)[0].split("=")[1] //return its value
return ""
}

lastvisit.setCookie=function(name, value, days){ //set cookei value
var expireDate = new Date()
//set "expstring" to either future or past date, to set or delete cookie, respectively
var expstring=expireDate.setDate(expireDate.getDate()+parseInt(days))
document.cookie = name+"="+value+"; expires="+expireDate.toGMTString()+"; path=/";
}

lastvisit.showmessage=function(){
if (lastvisit.getCookie("visitcounter")==""){ //if first visit
lastvisit.setCookie("visitcounter", 2, 730) //set "visitcounter" to 2 and for 730 days (2 years)
document.write(lastvisit.firstvisitmsg)
}
else
document.write(lastvisit.subsequentvisitmsg.replace("\[displaydate\]", new Date().toLocaleString()))
}

lastvisit.showmessage()

</script></span></td>
  </tr>
  <tr class="d-none">
    <td class="d-none">&nbsp;</td>
    <td class="d-none" style='padding-left: 60px;' align='right'>&nbsp;</td>
    <td class="d-none"  align='left'>&nbsp;</td>
  </tr>
  <tr class="d-none">
    <td class="d-none"><strong>Hot Demo Download: <?=$examcode['exam_name']?>&nbsp;(<?=$Recordnum?>)</strong></td>
    <td class="d-none" align='left'><strong>Hot Full Exam Download: <?=$examcode2['exam_name']?>&nbsp;(<?=$Recordnum2?>)</strong></td>
    <td class="d-none"  align='left'><strong>Hot Vendor Maximum Sold: <?=$hotvendo2['ven_name']?>&nbsp;(<?=$highthot?>)</strong></td>
  </tr>
  <tr class="d-none">
    <td class="d-none">&nbsp;</td>
    <td class="d-none" style='padding-left: 60px;' align='right'>&nbsp;</td>
    <td class="d-none"  align='left'>&nbsp;</td>
  </tr>
  <tr class="d-none">
    <td class="d-none"><strong>User Registered yesterday: <?=$tot_user?>&nbsp;</strong></td>
    <td class="d-none" align='left'><strong>Orders Received Yesterday: <?=$tot_order?></strong></td>
    <td class="d-none"  align='left'><strong>Yesterday's Sale: <?=$tot_sale?></strong></td>
  </tr>
  <tr class="d-none">
    <td class="d-none">&nbsp;</td>
    <td class="d-none" style='padding-left: 60px;' align='right'>&nbsp;</td>
    <td class="d-none"  align='left'>&nbsp;</td>
  </tr>
  </table></td>
</tr>
</table><!--<img src="http://www.yourdomain.com/worldmap_track.php" border="0" alt="" width="1" height="1">--><br>

<table width="100%" cellpadding="0" cellspacing="0" class="summary-grid">
<tr>
<td class="stat1 summary-card-cell" width="25%">
	<a href="<?=$staffListUrl;?>" class="summary-card-link">
		<div class="summary-card-title">Total Staffs</div>
		<div class="summary-card-value"><?=$totalStaffs;?></div>
		<div class="summary-card-hint">View staff listing</div>
	</a>
</td>
<td class="stat1 summary-card-cell" width="25%">
	<a href="<?=$vendorsListUrl;?>" class="summary-card-link">
		<div class="summary-card-title">Total Vendors</div>
		<div class="summary-card-value"><?=$totalVendors;?></div>
		<div class="summary-card-hint">Open vendors page</div>
	</a>
</td>
<td class="stat1 summary-card-cell" width="25%">
	<a href="<?=$certificatesListUrl;?>" class="summary-card-link">
		<div class="summary-card-title">Total Certificate</div>
		<div class="summary-card-value"><?=$totalCertificates;?></div>
		<div class="summary-card-hint">Open certificates page</div>
	</a>
</td>
<td class="stat2 summary-card-cell" width="25%">
	<a href="<?=$examsListUrl;?>" class="summary-card-link">
		<div class="summary-card-title">Total Exams</div>
		<div class="summary-card-value"><?=$totalExams;?></div>
		<div class="summary-card-hint">Open exams page</div>
	</a>
</td>
</tr>
<tr>
<td class="stat1 summary-card-cell" width="25%">
	<a href="<?=$usersListUrl;?>" class="summary-card-link">
		<div class="summary-card-title">Total Users</div>
		<div class="summary-card-value"><?=$totalUsers;?></div>
		<div class="summary-card-hint">Open users page</div>
	</a>
</td>
<td class="stat1 summary-card-cell" width="25%">
	<a href="<?=$ordersListUrl;?>" class="summary-card-link">
		<div class="summary-card-title">Total Orders</div>
		<div class="summary-card-value"><?=$totalOrders;?></div>
		<div class="summary-card-hint">Open orders page</div>
	</a>
</td>
<td class="stat1 summary-card-cell" width="25%">
	<a href="<?=$currentMonthSalesUrl;?>" class="summary-card-link">
		<div class="summary-card-title">Current Month Sale</div>
		<div class="summary-card-value">$<?=number_format($currentMonthSaleAmount, 2);?></div>
		<div class="summary-card-hint">View current month sales</div>
	</a>
</td>
<td class="stat2 summary-card-cell" width="25%">
	<a href="<?=$lastMonthSalesUrl;?>" class="summary-card-link">
		<div class="summary-card-title">Last Month Sale</div>
		<div class="summary-card-value">$<?=number_format($lastMonthSaleAmount, 2);?></div>
		<div class="summary-card-hint">View last month sales</div>
	</a>
</td>
</tr>
</table>

<div class="d-none">
<h2>Getting Started</h2>
If you are a new user and are ready to build your website, here are some helpful suggestions:

<br>
 <?php if(permission("Contents Management","Read")==TRUE){ ?>
<table cellpadding='0' cellspacing='0' style='margin-top: 5px;'>


<tr>
<td class='step'>1</td>

<td><b><a href='password.php'>Change Password</a></b><br>
Your password is most important for the protection of your web site. Here you can change your password. Your current password will be changed immediately.</td>
</tr>
<tr>
<td class='step'>2</td>
<td><b><a href='copyright.php'>Website Copyright</a></b><br>
  The website copyright is also important tag in the web site.This tag is gives us the information about the copyrighhts of the wes site.You can change your copyrights of the web site. </td>
</tr>
<!--<tr>
<td class='step'>2</td>
<td><b><a href='reviewmanage.php'>Reviews Management</a></b><br>
Reviews Management gives you full control of your members reviews. You can read the reviews. You can also edit and approve the title of every Review.</td>
</tr>
<tr>
<td class='step'>3</td>
<td><b><a href='sendnewsletter.php'>Send Newsletter</a></b><br>
This module is used to send the newsletter to the subscribers. You can change your content in the newsletter.</td>
</tr>-->
</table>
<?php } ?>
</div>
</div>
</td>
</tr>
</table>

<?php include("footer.php")?>
</body>
</html>
