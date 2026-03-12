<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$websitetitle?></title>
<link href="new.css" rel="stylesheet" type="text/css" />
<link href="style1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../includes/js/functions.js"></script>

<script language="JavaScript" src="../includes/js/calendar.js"></script>
<script type="text/javascript" src="Editor3/scripts/innovaeditor.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
</head>
<body>

<div class="topbar">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
  <td valign="middle">
    <img src="images/admin_icon.png" alt="ChineseDumps Admin" style="height:38px;" />
  </td>
  <td valign="middle" align="right">
<?php if(isset($_SESSION['strAdmin'])): ?>
	<div style="display: inline-block; vertical-align: middle;">
		<span style="color: #ffffff; margin-right: 15px; font-weight: 500;">Welcome, <?php echo htmlspecialchars($_SESSION['strAdmin']); ?></span>
		<a href="logout.php" class="btn-logout">
			<i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
			Logout
		</a>
	</div>
<?php else: ?>
	<div style="display: inline-block; vertical-align: middle;">
		<a href="index.php" class="btn-logout" style="background-color: rgba(255, 255, 255, 0.1);">
			<i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
			Login
		</a>
	</div>
<?php endif; ?>
  </td>
</tr>
</table></div>