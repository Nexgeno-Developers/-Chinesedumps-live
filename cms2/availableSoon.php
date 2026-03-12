<?PHP 
error_reporting(0);
session_start();

//root path
$LinkPath="../";
//title of the page
$strTitle	=	"Change Follow Us";
/*include files*/

include ("../includes/config/classDbConnection.php");
include("../includes/common/classes/validation_class.php");
include ("../includes/common/inc/sessionheader.php");
include ("../includes/common/classes/classAdmin.php");

include_once 'functions.php';
$ccAdminData = get_session_data();


//--------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$websitetitle?></title>
<link href="new.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../includes/js/password.js"></script>

<style type="text/css">
<!--
.style1 {color: #FF0000}
-->
</style>
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
<td width="810" class="rightside"><h2>Available Soon!</h2>
Welcome to your <?=$websitename?> Website control panel. Here you can manage and modify every aspect of your <?=$websitename?>.<br /><form name="catForm" id="catForm" method="post" action="" enctype="multipart/form-data"><br />

  <label></label>
  <table cellpadding='0' cellspacing='0' width='900' style="margin: 0 auto;">
    <tr>
      <td></td>
    </tr>
    <tr>
      <td class='header'>Available Soon: </td>
    </tr>
    <tr>
      <td class='setting1' style="font-size:13px;"><?php

$q = mysql_query("select * from tbl_vendors order by ven_name");
      for($i=0;$i<mysql_num_rows($q);$i++){
$vendor = mysql_fetch_array($q);


$k = mysql_query("select * from tbl_exam where ven_id='".$vendor["ven_id"]."' and QA='0' order by exam_name");
if(mysql_num_rows($k) == 0){}

	echo "<div style='width:100%; color:white; text-align:center; background-color:grey'>".$vendor["ven_name"]."</div>";

	for($l=0;$l<mysql_num_rows($k);$l++){
	$exam = mysql_fetch_array($k);

		echo "<div style='width:10%; float:left; color:red'>".$exam["exam_name"]."</div>";
	
}


echo "<div style='clear:both'></div>";



}
      
       ?></td>
    </tr>
    <tr>
      <td class='setting2'>&nbsp;</td>
    </tr>
  </table>
  		  </form>

<br /></td>
</tr>
</table>

</td>
</tr>
</table>

<? include("footer.php")?>

</body>
</html>
