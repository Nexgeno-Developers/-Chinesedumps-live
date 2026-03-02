<?php
ob_start();
session_start();
if(isset($_SESSION['uid'])){
}else{
header("location:login.html");
}
include("includes/config/classDbConnection.php");

include("includes/common/classes/classmain.php");
include("includes/common/classes/classProductMain.php");
include("includes/common/classes/classcart.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objPro		=	new classProduct($objDBcon);
$objCart	=	new classCart($objDBcon);

$getPage	=	$objMain->getContent(4);
$getPage[0]	=	"My Testing Engine";
$getPage[1]	=	"My Testing Engine";
$getPage[2]	=	"My Testing Engine";
$getPage[4]	=	"My Testing Engine";
$getPage[5]	=	"My Testing Engine";


function find_file ($dirname, $fname, &$file_path) {

  $dir = opendir($dirname);

  while ($file = readdir($dir)) {
    if (empty($file_path) && $file != '.' && $file != '..') {
      if (is_dir($dirname.'/'.$file)) {
        find_file($dirname.'/'.$file, $fname, $file_path);
      }
      else {
        if (file_exists($dirname.'/'.$fname)) {
          $file_path = $dirname.'/'.$fname;
          return;
        }
      }
    }
  }

} // find_file
    $quer	=	$objDBcon->Sql_Query_Exec('*','website','');
	$exec	=	mysql_fetch_array($quer);
	
	$copyright	=	$exec['copyright'];
	
	if(isset($_POST['ok'])){
		//header("location:".$_POST['cmb_subcategoryexam']."-free-download.htm");
		header("location:download.php?x=".$_POST['hiddenid']."&y=".$_POST['cmb_subcategoryexam']."");
	}
	

		$no_assigned=1;
		$str1 = "";
		$res1 = mysql_query("select E.* from tbl_exam_user T, tbl_exam E where T.exam_id = E.exam_id and T.status='1' and (T.PType='sp' or T.PType='both') and T.user_id = '".$_SESSION['uid']."'");
		$showTR	='';
		while($getuser1	=	mysql_fetch_array($res1))
		{
			$filenamename	=	basename($getuser1['exam_full']);
			$file_path = '';
			if($filenamename=='')
			{
				$filenamename = $getuser1['exam_name'].".zip";
			}
			find_file('devil/full/', $filenamename, $file_path);
			
			if (!is_file($file_path))
			{
				$download="Please contact support for any questions.(ENF)";
			}
			else
			{
				$download="<a href='download_assigned.php?y=".$getuser1['exam_id']."' style='text-decoration:none;color:#F75703;'>Download</a>";
			}
				$str1	.=' <table width="600" style="border-bottom:dashed 1px #333333;margin-bottom:8px;padding-bottom:8px;" border="0" >
				<tr>
			<td width="100" nowrap="nowrap" style=" color:#333333;" width:><strong>Exam Code</strong></td>
			<td width="166">'.$getuser1['exam_name'].'</td>
			<td width="100" style=" color:#333333;"><strong></strong></td>
			<td width="166"></td>
		  </tr>
		  <tr>
			 <td width="100" style=" color:#333333; " ><strong>Exam</strong></td>
			<td width="166">'.$download.'</td>
			<td width="100" style=" color:#333333;"  ><strong>Status</strong></td>
			<td width="166"><font style="color:#006600; font-weight:bold;">Active</font></td>
		  </tr>
		  <tr>
		   <td width="100" nowrap="nowrap" style=" color:#333333;" width:><strong></strong></td>
			<td width="166"></td>
		   <td width="100" style=" color:#333333;" ><strong></strong></td>
			<td width="166"></td>
		  </tr></table>
		 ';
			$no_assigned=0;
		}
		
	$userId = $_SESSION['uid'];
	$show_orders	=	"";
	$result			=	$objCart->getAllCurrentProducts($userId);
	if(mysql_num_rows($result)<=0){
		if($no_assigned=="1")
		{
			$show_orders	=	"<div class='redbold'>NO Products available for download in your account.</div>";
		}
	}else{
		$show_orders	=	$objCart->show_current_products($result);
	}	
	$show_orders	.=	$str1;

	$firstlink	=	" ".$getPage[1];
	
include("html/my-testing-engine.html");
?>