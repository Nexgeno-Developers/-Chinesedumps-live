<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Admin System Homes LLC</title>
<link href="new.css" rel="stylesheet" type="text/css" />

</head>
<body>

<div class="topbar">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td valign="top"><img src="images/admin_icon.gif" border="0" alt="Admin Header" /></td>
<td valign="top" align="right"><img src="images/admin_watermark.gif" border="0" alt="Admin Header" /></td>
</tr>
</table></div>

<table cellpadding="0" cellspacing="0" width="102%">
<tr>
<td width="190" class="leftside"><?php include ("menu.php"); ?></td>
<td width="810" class="rightside"><h2>View Admin Users!</h2>
Welcome to your Source Homes Website control panel. Here you can manage and modify every aspect of your Source Homes.<br>
  
  <br><br>
  <div class='pages'></div>

  
  <table cellpadding='0' cellspacing='0' class='list' width='100%'>
  <tr>
  <td class='header' width='10'><input type='checkbox' name='select_all' onClick='javascript:doCheckAll()'></td>
  <td class='header' width='10' style='padding-left: 0px;'><a class='header' href='admin_viewusers.php?s=i&p=1&f_user=&f_email=&f_level=&f_subnet=&f_enabled='>ID</a></td>
  <td class='header'><a class='header' href='admin_viewusers.php?s=u&p=1&f_user=&f_email=&f_level=&f_subnet=&f_enabled='>Username</a></td>
  <td class='header'><a class='header' href='admin_viewusers.php?s=em&p=1&f_user=&f_email=&f_level=&f_subnet=&f_enabled='>Email</a></td>

  <td class='header'>User Level</td>
  <td class='header'>Subnetwork</td>
  <td class='header' align='center'>Enabled</td>
  <td class='header'><a class='header' href='admin_viewusers.php?s=sd&p=1&f_user=&f_email=&f_level=&f_subnet=&f_enabled='>Signup Date</a></td>
  <td class='header'>Options </td>
  </tr>

  
  <!-- LOOP THROUGH USERS -->
      <tr class='background1'>
    <td class='item' style='padding-right: 0px;'><input type='checkbox' name='item_2190' value='1'></td>
    <td class='item' style='padding-left: 0px;'>2190</td>
    <td class='item'><a href='http://demo.socialengine.net/profile.php?user=davidleo'>davidleo</a></td>
    <td class='item'><a href='#'>Hidden for demo.</a></td>
    <td class='item'><a href='admin_levels_edit.php?level_id=1'>Default Level</a></td>

    <td class='item'><a href='admin_subnetworks_edit.php?subnet_id='>Default</a></td>
    <td class='item' align='center'>Yes</td>
    <td class='item' nowrap='nowrap'>Sep. 18, 2008</td>
    <td class='item' nowrap='nowrap'><a href='admin_viewusers_edit.php?user_id=2190&s=id&p=1&f_user=&f_email=&f_level=&f_enabled='>edit</a> | <a href="javascript:alert('This feature is disabled in the demo.');">delete</a> | <a href="javascript:alert('This feature is disabled in the demo.');">login</a></td>
    </tr>
      <tr class='background2'>
    <td class='item' style='padding-right: 0px;'><input type='checkbox' name='item_2189' value='1'></td>
    <td class='item' style='padding-left: 0px;'>2189</td>
    <td class='item'><a href='http://demo.socialengine.net/profile.php?user=newtester'>newtester</a></td>
    <td class='item'><a href='#'>Hidden for demo.</a></td>
    <td class='item'><a href='admin_levels_edit.php?level_id=1'>Default Level</a></td>

    <td class='item'><a href='admin_subnetworks_edit.php?subnet_id='>Default</a></td>
    <td class='item' align='center'>Yes</td>
    <td class='item' nowrap='nowrap'>Sep. 18, 2008</td>
    <td class='item' nowrap='nowrap'><a href='admin_viewusers_edit.php?user_id=2189&s=id&p=1&f_user=&f_email=&f_level=&f_enabled='>edit</a> | <a href="javascript:alert('This feature is disabled in the demo.');">delete</a> | <a href="javascript:alert('This feature is disabled in the demo.');">login</a></td>
    </tr>
    </table>

  <table cellpadding='0' cellspacing='0' width='100%'>
  <tr>
  <td></td>
  <td align='right' valign='top'>
    <div class='pages2'></div>

  </td>
  </tr>
</table>


</td>
</tr></td>
</tr><br>

</body>
</html>
