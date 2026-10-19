<?php
// admin session guard: the asset manager must only be reachable from the logged-in CMS
if (session_id() === '') { session_start(); }
if (!isset($_SESSION['strAdmin']) || !isset($_SESSION['adminId'])) { header('HTTP/1.1 403 Forbidden'); exit('Forbidden'); }
?>
<?
include("settings.php");
$sMsg = "";

if(isset($_POST["inpNewFolderName"]))
	{	
	$sNewName = am_safe_name($_POST["inpNewFolderName"]);
	$sFolder = $_POST["inpCurrFolder"]."/".$sNewName;

	if($sNewName=="" || !am_folder_ok($_POST["inpCurrFolder"]))
		{//outside the allowed asset folders / empty name
		$sMsg = "<script>document.write(getTxt('Invalid input.'))</script>";
		}
	else if(is_dir($sFolder)==1)
		{//folder already exist
		$sMsg = "<script>document.write(getTxt('Folder already exists.'))</script>";
		}
	else
		{
		//if(mkdir($sFolder))
		if(mkdir($sFolder,0755))
			$sMsg = "<script>document.write(getTxt('Folder created.'))</script>";
		else
			$sMsg = "<script>document.write(getTxt('Invalid input.'))</script>";
		}
	}
?>
<base target="_self">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style.css" rel="stylesheet" type="text/css">
<script>
	if(navigator.appName.indexOf('Microsoft')!=-1)
		var sLang=dialogArguments.sLang;
	else
		var sLang=window.opener.sLang;
	document.write("<scr"+"ipt src='language/"+sLang+"/foldernew.js'></scr"+"ipt>");
</script>
<script>writeTitle()</script>
<script>
function doPreSubmit()
	{
	var Form1 = document.forms.Form1;
	if(navigator.appName.indexOf('Microsoft')!=-1)
		Form1.elements.inpCurrFolder.value=dialogArguments.selCurrFolder.value;
	else
		Form1.elements.inpCurrFolder.value=window.opener.document.getElementById("selCurrFolder").value;

	if(Form1.elements.inpNewFolderName.value=="")
		{
		alert(fgetTxt("Invalid input."));
		return false;
		}
	return true;
	}
function doSubmit()
	{
	if(doPreSubmit())document.forms.Form1.submit()
	}
</script>
</head>
<body onload="loadTxt()" style="overflow:hidden;margin:0;">

<table width=100% height=100% align=center style="" cellpadding=0 cellspacing=0>
<tr>
<td valign=top style="padding-top:5px;padding-left:15px;padding-right:15px;padding-bottom:12px;height=100%">

<form method=post action="foldernew.php" onsubmit="doPreSubmit()" name="Form1" id="Form1">
	<br>
	<input type="hidden" id="inpCurrFolder" name="inpCurrFolder">
	<span id="txtLang">New Folder Name</span>: <br>
	<input type="text" id="inpNewFolderName" name="inpNewFolderName" class="inpTxt" size=38>
	<div><b><? echo $sMsg ?>&nbsp;</b></div>
</form>

</td>
</tr>
<tr>
<td class="dialogFooter" style="height:40px;padding-right:10px;" align=right valign=middle>
	<input style="width:135" type="button" name="btnCloseAndRefresh" id="btnCloseAndRefresh" value="close & refresh" onclick="if(navigator.appName.indexOf('Microsoft')!=-1){dialogArguments.changeFolder()}else{window.opener.changeFolder()};self.close();" class="inpBtn" onmouseover="this.className='inpBtnOver';" onmouseout="this.className='inpBtnOut'">&nbsp;<input name="btnCreate" id="btnCreate" type="button" onclick="doSubmit()" value="create" class="inpBtn" onmouseover="this.className='inpBtnOver';" onmouseout="this.className='inpBtnOut'">
</td>
</tr>
</table>

</body>
</html>