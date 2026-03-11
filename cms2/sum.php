<?php 
session_start();
include ("../includes/common/inc/sessionheader.php");
include ("../includes/config/classDbConnection.php");
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$websitetitle?></title>
<link href="new.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div class="topbar">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td valign="top"><img src="images/admin_icon.png" alt="Admin Header" width="200" height="" border="0" /></td>
<td valign="top" align="right"><img src="images/admin_watermark.gif" border="0" alt="Admin Header" /></td>
</tr>
</table></div>

<table cellpadding="0" cellspacing="0" width="102%">
<tr>
<td width="190" class="leftside"><?php include ("menu.php"); ?></td>
<td width="810" class="rightside"><h2>Hello, Admin!</h2>

Welcome to your <?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?> website. 
<table width="100%" border="0" class="stats">
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
    if (isSet($_SERVER)) {
        if (isSet($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isSet($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
             $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
         if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
              $realip = getenv( 'HTTP_X_FORWARDED_FOR' );
         } elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
              $realip = getenv( 'HTTP_CLIENT_IP' );
         } else {
              $realip = getenv( 'REMOTE_ADDR' );
         }
    }
    return $realip; 
}

//print out the ip and browser information

print getip();
 
?> 
</div></td>
    </tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0' class='stats' style='margin-top: 10px;'>
<tr>
<td class='stat0'>
  <table width="100%" cellpadding='0' cellspacing='0'>
  <tr>

  <td width="33%"><b>Total Admin's:</b> 1 </td>
  <td width="33%" align='left' ><b>Total Visits:</b>&nbsp;<?php

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
?></td>
  <td width="33%" align='left' ><b>Last Visits :</b><script type="text/javascript">

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

</script></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td style='padding-left: 60px;' align='right'>&nbsp;</td>
    <td  align='left'>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Hot Demo Download: <?=$examcode['exam_name']?>&nbsp;(<?=$Recordnum?>)</strong></td>
    <td align='left'><strong>Hot Full Exam Download: <?=$examcode2['exam_name']?>&nbsp;(<?=$Recordnum2?>)</strong></td>
    <td  align='left'><strong>Hot Vendor Maximum Sold: <?=$hotvendo2['ven_name']?>&nbsp;(<?=$highthot?>)</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td style='padding-left: 60px;' align='right'>&nbsp;</td>
    <td  align='left'>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>User Registered yesterday: <?=$tot_user?>&nbsp;</strong></td>
    <td align='left'><strong>Orders Received Yesterday: <?=$tot_order?></strong></td>
    <td  align='left'><strong>Yesterday's Sale: <?=$tot_sale?></strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td style='padding-left: 60px;' align='right'>&nbsp;</td>
    <td  align='left'>&nbsp;</td>
  </tr>
  </table></td>
</tr>
</table><!--<img src="http://www.yourdomain.com/worldmap_track.php" border="0" alt="" width="1" height="1">--><br>

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
<? } ?>
</td>
</tr>
</table>

<? include("footer.php")?>
</body>
</html>
