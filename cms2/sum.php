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
<td valign="top" align="right">
<?php if(isset($_SESSION['strAdmin'])): ?>
	<div style="display: inline-block; vertical-align: middle;">
		<span style="color: #ffffff; margin-right: 15px; font-weight: 500;">Welcome, <?php echo htmlspecialchars($_SESSION['strAdmin']); ?></span>
		<a href="logout.php" style="color: #ffffff; text-decoration: none; padding: 8px 16px; background-color: rgba(255, 255, 255, 0.2); border-radius: 4px; font-weight: 500; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='rgba(255, 255, 255, 0.3)'" onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.2)'">Logout</a>
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
<style>
.dashboard-header {
  margin-bottom: 32px;
}
.dashboard-title {
  font-size: 32px;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 8px 0;
  letter-spacing: -0.02em;
}
.dashboard-subtitle {
  font-size: 16px;
  color: #64748b;
  margin: 0;
  line-height: 1.6;
}
.system-info-bar {
  border-radius: 12px;
  padding: 20px 24px;
  margin-bottom: 24px;
  color: #ffffff;
  background: linear-gradient(135deg, #3c85ba 0%, #2d6a9a 100%);
}
.system-info-bar table {
  width: 100%;
  border-collapse: collapse;
}
.system-info-bar td {
  padding: 8px 12px;
  font-size: 13px;
  border-right: 1px solid rgba(255, 255, 255, 0.2);
}
.system-info-bar td:last-child {
  border-right: none;
}
.system-info-bar strong {
  font-weight: 600;
  opacity: 0.9;
}
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
  margin-bottom: 32px;
}
.stat-card {
  background: #ffffff;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  border: 1px solid #e2e8f0;
  transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
.stat-label {
  font-size: 13px;
  color: #64748b;
  font-weight: 500;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.stat-value {
  font-size: 28px;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}
.stat-detail {
  font-size: 12px;
  color: #94a3b8;
  margin-top: 4px;
}
.getting-started {
  background: #ffffff;
  border-radius: 12px;
  padding: 28px;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  border: 1px solid #e2e8f0;
  margin-top: 32px;
}
.getting-started h2 {
  font-size: 24px;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 12px 0;
}
.getting-started p {
  color: #64748b;
  margin: 0 0 24px 0;
  font-size: 15px;
}
.step-item {
  display: flex;
  gap: 20px;
  padding: 20px;
  margin-bottom: 16px;
  background: #f8fafc;
  border-radius: 8px;
  border-left: 4px solid #3c85ba;
  transition: background 0.2s;
}
.step-item:hover {
  background: #f1f5f9;
}
.step-number {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #3c85ba 0%, #2d6a9a 100%);
  color: #ffffff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 18px;
  flex-shrink: 0;
}
.step-content h3 {
  margin: 0 0 8px 0;
  font-size: 18px;
  font-weight: 600;
}
.step-content h3 a {
  color: #0f172a;
  text-decoration: none;
}
.step-content h3 a:hover {
  color: #667eea;
}
.step-content p {
  margin: 0;
  color: #64748b;
  font-size: 14px;
  line-height: 1.6;
}
</style>

<div class="dashboard-header">
  <h1 class="dashboard-title">Hello, Admin!</h1>
  <p class="dashboard-subtitle">Welcome to your <?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?> website.</p>
</div>

<div class="system-info-bar">
  <table cellpadding="0" cellspacing="0">
    <tr>
      <td><strong><?php echo $websitename;?> Admin System:</strong> 1.0</td>
      <td><strong>Security:</strong> Enable</td>
      <td>
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
        printf("Load Time: %f seconds", $totaltime);
        ?>
      </td>
      <td>
        <strong>Your IP:</strong> <?php
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
        print getip();
        ?>
      </td>
    </tr>
  </table>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-label">Total Admins</div>
    <div class="stat-value">1</div>
  </div>
  
  <div class="stat-card">
    <div class="stat-label">Total Visits</div>
    <div class="stat-value"><?php
    $filename = 'hitcount.txt';
    $handle = fopen($filename, 'r');
    $hits = trim(fgets($handle)) + 1;
    fclose($handle);
    $handle = fopen($filename, 'w');
    fwrite($handle, $hits);
    fclose($handle);
    echo number_format($hits);
    ?></div>
  </div>
  
  <div class="stat-card">
    <div class="stat-label">Last Visit</div>
    <div class="stat-value" style="font-size: 16px;">
      <script type="text/javascript">
      var lastvisit=new Object()
      lastvisit.firstvisitmsg="Welcome Your First Visit!"
      lastvisit.subsequentvisitmsg="[displaydate]"
      lastvisit.getCookie=function(Name){
      var re=new RegExp(Name+"=[^;]+", "i");
      if (document.cookie.match(re))
      return document.cookie.match(re)[0].split("=")[1]
      return ""
      }
      lastvisit.setCookie=function(name, value, days){
      var expireDate = new Date()
      var expstring=expireDate.setDate(expireDate.getDate()+parseInt(days))
      document.cookie = name+"="+value+"; expires="+expireDate.toGMTString()+"; path=/";
      }
      lastvisit.showmessage=function(){
      if (lastvisit.getCookie("visitcounter")==""){
      lastvisit.setCookie("visitcounter", 2, 730)
      document.write(lastvisit.firstvisitmsg)
      }
      else
      document.write(lastvisit.subsequentvisitmsg.replace("\[displaydate\]", new Date().toLocaleString()))
      }
      lastvisit.showmessage()
      </script>
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-label">Hot Demo Download</div>
    <div class="stat-value"><?=$Recordnum?></div>
    <div class="stat-detail"><?=$examcode['exam_name']?></div>
  </div>
  
  <div class="stat-card">
    <div class="stat-label">Hot Full Exam Download</div>
    <div class="stat-value"><?=$Recordnum2?></div>
    <div class="stat-detail"><?=$examcode2['exam_name']?></div>
  </div>
  
  <div class="stat-card">
    <div class="stat-label">Hot Vendor Maximum Sold</div>
    <div class="stat-value"><?=$highthot?></div>
    <div class="stat-detail"><?=$hotvendo2['ven_name']?></div>
  </div>
  
  <div class="stat-card">
    <div class="stat-label">Users Registered Yesterday</div>
    <div class="stat-value"><?=$tot_user?></div>
  </div>
  
  <div class="stat-card">
    <div class="stat-label">Orders Received Yesterday</div>
    <div class="stat-value"><?=$tot_order?></div>
  </div>
  
  <div class="stat-card">
    <div class="stat-label">Yesterday's Sale</div>
    <div class="stat-value">$<?=number_format($tot_sale, 2)?></div>
  </div>
</div>

<div class="getting-started">
  <h2>Getting Started</h2>
  <p>If you are a new user and are ready to build your website, here are some helpful suggestions:</p>
  
  <?php if(permission("Contents Management","Read")==TRUE){ ?>
  <div class="step-item">
    <div class="step-number">1</div>
    <div class="step-content">
      <h3><a href='password.php'>Change Password</a></h3>
      <p>Your password is most important for the protection of your web site. Here you can change your password. Your current password will be changed immediately.</p>
    </div>
  </div>
  
  <div class="step-item">
    <div class="step-number">2</div>
    <div class="step-content">
      <h3><a href='copyright.php'>Website Copyright</a></h3>
      <p>The website copyright is also important tag in the web site. This tag gives us the information about the copyrights of the web site. You can change your copyrights of the web site.</p>
    </div>
  </div>
  <?php } ?>
</div>
</td>
</tr>
</table>

<? include("footer.php")?>
</body>
</html>
