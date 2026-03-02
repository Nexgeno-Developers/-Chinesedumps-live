<?php
ob_start();
session_start();
include("includes/config/classDbConnection.php");
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors",0);

$objDBcon   =   new classDbConnection; 
$dayedate	=	date('Y-m-d');


if(!isset($_SESSION['uid']) || $_SESSION['uid']==''){
header("location:login.html");
}else{


if(isset($_GET['id']) && $_GET['id'] != ""){

$pid = $_GET['id'];

$SentNo=base64_decode($pid);
$parts=explode("-",$SentNo);

// echo $parts[0]." - ".$parts[1]." - ".$parts[2];

$usrtId=$parts[0];
$pkgId=$parts[1];

//print_r($parts);

	if($_GET["ddtype"] == "SP"){
		$qryprt1 = "tpi.engine_id=e.exam_id and e.exam_id = '".$pkgId."'";
	}else{
		$qryprt1 = "tpi.engine_id = '".$pkgId."'";
	}

$queryForFilename = "SELECT e.*, o.*, tpi.* FROM tbl_exam e, order_detail o, tbl_package_instance tpi WHERE ((o.ProductID=e.exam_id || o.ProductID='0') and o.UserID='".$_SESSION['uid']."') and (o.ID='".$parts[2]."' and ".$qryprt1.")";
$qryy = mysql_query($queryForFilename);



if(mysql_num_rows($qryy) != "0"){

$rowForFilename = mysql_fetch_array($qryy);






$documentroot = dirname(__FILE__)."/";
$sdata = 'devil/full/';

$exammID = $rowForFilename[0];

	$pathengine = mysql_fetch_array(mysql_query("select * from tbl_package_engine where id='".$pkgId."'"));

	$getfilename = $sdata.'/'.$pathengine["path"];



define('BASE_DIR','devil/full/');

// Allowed extensions list in format 'extension' => 'mime type'
// If myme type is set to empty string then script will try to detect mime type 
// itself, which would only work if you have Mimetype or Fileinfo extensions
// installed on server.
 $allowed_ext = array (

  // archives
  'zip' => $sdata.'/zip',
  'rar' => $sdata.'/rar',

  // documents
  'pdf' => $sdata.'/html',
  'pdf' => $sdata.'/pdf',
  'doc' => $sdata.'/msword',
  'docx' => $sdata.'/msword',
  'xls' => $sdata.'/vnd.ms-excel',
  'xlsx' => $sdata.'/vnd.ms-excel',
  'ppt' => $sdata.'/vnd.ms-powerpoint',
  //'php' => 'contactus_docs/php',

  // executables
  'exe' => $sdata.'/octet-stream',

  // images
  'gif' => $sdata.'/gif',
  'png' => $sdata.'/png',
  'jpg' => $sdata.'/jpeg',
  'jpeg' => $sdata.'/jpeg',

  // audio
  'mp3' => $sdata.'/mpeg',
  'wav' => $sdata.'/x-wav',

  // video
  'mpeg' => $sdata.'/mpeg',
  'mpg' => $sdata.'/mpeg',
  'mpe' => $sdata.'/mpeg',
  'mov' => $sdata.'/quicktime',
  'avi' => $sdata.'/x-msvideo'
  
);
####################################################################
###  DO NOT CHANGE BELOW
####################################################################

// Make sure program execution doesn't time out
// Set maximum script execution time in seconds (0 means no limit)
set_time_limit(0);

if (!isset($_GET['id']) || empty($_GET['id']) || empty($getfilename)) {
  die("Please specify file name for download.");
}

// Get real file name.
// Remove any path info to avoid hacking by adding relative path, etc.
 $fname = basename($getfilename);
 
$file_path = $documentroot.$getfilename;


// get full file path (including subfolders)
if (!is_file($file_path)) {
  die("File does not exist. Make sure you specified correct file name."); 
}


// file size in bytes
$fsize = filesize($file_path); 

// file extension
$fext = strtolower(substr(strrchr($fname,"."),1));

// check if allowed extension
if (!array_key_exists($fext, $allowed_ext)) {
  die("Not allowed file type."); 
}

// get mime type
if ($allowed_ext[$fext] == '') {
  $mtype = '';
  // mime type is not set, get from server settings
  if (function_exists('mime_content_type')) {
    $mtype = mime_content_type($file_path);
  }
  else if (function_exists('finfo_file')) {
    $finfo = finfo_open(FILEINFO_MIME); // return mime type
    $mtype = finfo_file($finfo, $file_path);
    finfo_close($finfo);  
  }
  if ($mtype == '') {
    $mtype = "application/force-download";
  }
}
else {
  // get mime type defined by admin
  $mtype = $allowed_ext[$fext];
}

// Browser will try to save file with this filename, regardless original filename.
// You can override it if needed.
if (!isset($_GET['id']) || empty($_GET['id']) || empty($getfilename)) {
 $asfname = $fname;
}
else {
  // remove some bad chars
  $asfname = str_replace(array('"',"'",'\\','/'), '', $fname);
  if ($asfname === '') $asfname = 'NoName';
}

// Download Manager

//mysql_query("insert into downloads values ('','".$_SESSION['email']."','".$rowForFilename[1]."', now())");

if(ini_get('zlib.output_compression'))
ini_set('zlib.output_compression', 'Off');

header("Content-Description: File Transfer");
header("Content-Type: $mtype");
header("Content-Disposition: attachment; filename=\"$asfname\"");
header("Content-Transfer-Encoding: binary");
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header("Content-Length: " . $fsize);

ob_clean();
flush();

readfile($file_path);
	
	
	///////////////////////Swreg Else
}else{

//header("location:my-testing-engine.html?error=notfound");

}  //// Check Exam






}
else{

}

}//////////////// check session ID



?>