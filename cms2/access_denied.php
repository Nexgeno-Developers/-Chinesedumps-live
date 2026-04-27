<?PHP
session_start();
include ("../includes/common/inc/sessionheader.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Access Denied</title>
<link href="new.css" rel="stylesheet" type="text/css" />
<link href="style1.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="topbar">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td valign="top"><img src="images/admin_icon.jpg" border="0" alt="Admin Header" /></td>
<td valign="top" align="right"><img src="images/admin_watermark.gif" border="0" alt="Admin Header" /></td>
</tr>
</table>
</div>
<table cellpadding="0" cellspacing="0" width="102%">
<tr>
<td width="190" class="leftside"><?php include ("menu.php"); ?></td>
<td width="810" class="rightside">
<h2>Access Denied</h2>
<p>You do not have permission to access this admin page.</p>
<p><a href="sum.php">Back to Summary</a></p>
</td>
</tr>
</table>
<? include("footer.php")?>
</body>
</html>
