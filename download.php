<?php

ob_start();

session_start();



if(isset($_SESSION['uid'])){

}else{

header("location:account.html");

}

include("includes/config/classDbConnection.php");

include("includes/common/classes/classProductMain.php");




$objDBcon   =   new classDbConnection;

$objPro		=	new classProduct($objDBcon);



$pid = $_GET['x'];



$SentNo=base64_decode($pid);

$parts=explode("-",$SentNo);



$parts["1"];

$parts["2"];





$ip= $_SERVER['REMOTE_ADDR'];

//$orderdate	=	mysql_fetch_array(mysql_query("SELECT * from order_detail where ID='".$parts["1"]."'"));

$orderqry	=	mysql_query("SELECT o.*, e.* from order_detail o, tbl_exam e where (o.ID='".$parts["1"]."' and (o.ProductID='".$parts["2"]."' || o.ProductID='0'))");



$orderdate	= mysql_fetch_array($orderqry);



if(mysql_num_rows($orderqry) == "0"){

		header("location:mydownloads.html");

	}



$currentdate=	date("Y-m-d");

//$currentfullydate	=	date("Y-m-d G:i:s");

$expdate	=	$orderdate['ExpireDate'];

if($orderdate['UserID']!=$_SESSION['uid']){

//header("location:account.html");

}



if(($currentdate<=$expdate) || $expdate==''){

$sql_pro = "select * from tbl_exam  where exam_id='".$parts["2"]."' ";

$rs_pro  = mysql_query($sql_pro);

$row_pro = mysql_fetch_array($rs_pro);

$filenamename	=	$row_pro['exam_full'];

 if($filenamename=='')

{

	$filenamename = $row_pro['exam_name'].".zip";

} 


$exmId = $row_pro['exam_name'];





}else{

	header("location:mydownloads.html");

	}





if($orderdate["PaymentType"] == "Sagepay"){

	



	

echo download_demo($exmId);



$qu ="INSERT INTO tbl_full_download(dnUID,dnTYPE,itemid,dndate,IP,IPcountry,statusdownload)VALUES('".$_SESSION['uid']."','0','".$parts["2"]."','".date("Y-m-d")."','".$ip."','','1')";

mysql_query($qu);



####################################################################

###  DO NOT CHANGE BELOW

####################################################################









} //////////////sagepay if

else{

	



define('ALLOWED_REFERRER', '');

define('BASE_DIR','devil/full/');



// log downloads?  true/false

define('LOG_DOWNLOADS',true);



// log file name

define('LOG_FILE','downloads.log');



// Allowed extensions list in format 'extension' => 'mime type'

// If myme type is set to empty string then script will try to detect mime type 

// itself, which would only work if you have Mimetype or Fileinfo extensions

// installed on server.

$allowed_ext = array (



  // archives

  'zip' => 'application/zip',

  'rar' => 'application/rar',



  // documents

  'pdf' => 'application/pdf',

  'doc' => 'application/msword',

  'xls' => 'application/vnd.ms-excel',

  'ppt' => 'application/vnd.ms-powerpoint',

  

  // executables

  'exe' => 'application/octet-stream',



  // images

  'gif' => 'image/gif',

  'png' => 'image/png',

  'jpg' => 'image/jpeg',

  'jpeg' => 'image/jpeg',



  // audio

  'mp3' => 'audio/mpeg',

  'wav' => 'audio/x-wav',



  // video

  'mpeg' => 'video/mpeg',

  'mpg' => 'video/mpeg',

  'mpe' => 'video/mpeg',

  'mov' => 'video/quicktime',

  'avi' => 'video/x-msvideo'

);







####################################################################

###  DO NOT CHANGE BELOW

####################################################################



// If hotlinking not allowed then make hackers think there are some server problems



if (ALLOWED_REFERRER !== '' && (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),strtoupper(ALLOWED_REFERRER)) === false)

) {

  die("Internal server error. Please contact system administrator.");

}





// Make sure program execution doesn't time out

// Set maximum script execution time in seconds (0 means no limit)

set_time_limit(0);



if (!isset($filenamename) || empty($filenamename)) {

 header("location:notfound.html");

  die("Please specify file name for download.");

}else{

$qu ="INSERT INTO tbl_full_download(dnUID,dnTYPE,itemid,dndate,IP,IPcountry,statusdownload)VALUES('".$_SESSION['uid']."','0','".$parts["2"]."','".date("Y-m-d")."','".$ip."','','1')";

mysql_query($qu);





}



// Get real file name.

// Remove any path info to avoid hacking by adding relative path, etc.

$fname = basename($filenamename);



// Check if the file exists

// Check in subfolders too

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



// get full file path (including subfolders)

$file_path = '';

find_file(BASE_DIR, $fname, $file_path);







if (!is_file($file_path)) {

echo $file_path;

	header("location:notfound.html");

	//die("File does not exist. Make sure you specified correct file name."); 

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



if (!isset($_GET['fc']) || empty($_GET['fc'])) {

  $asfname = $fname;

}

else {

  // remove some bad chars

  $asfname = str_replace(array('"',"'",'\\','/'), '', $_GET['fc']);

  if ($asfname === '') $asfname = 'NoName';

}



// set headers

header("Pragma: public");

header("Expires: 0");

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("Cache-Control: public");

header("Content-Description: File Transfer");

header("Content-Type: $mtype");

header("Content-Disposition: attachment; filename=\"$asfname\"");

header("Content-Transfer-Encoding: binary");

header("Content-Length: " . $fsize);





// download

// @readfile($file_path);

$file = @fopen($file_path,"rb");

if ($file) {

  while(!feof($file)) {

    print(fread($file, 1024*8));

    flush();

    if (connection_status()!=0) {

      @fclose($file);

	  

      die();

    }

  }

  @fclose($file);

 

}







} /////////////swreg else













	function download_demo($exmId)

    	{

        	$productId = $exmId;



        	$client = new ApiClient();



        	// this will automatically initiate the download unless there's an error.

        	$client->downloadProduct($productId,'PDF');



        	// Check if request succeeded. If not, display the error in a way that is appropriate

        	// for your website.

        	if($client->hasError())

        	{

            	echo($client->getError());

        	}

    	}

















// log downloads

/* if (!LOG_DOWNLOADS) die();



$f = @fopen(LOG_FILE, 'a+');

if ($f) {

  @fputs($f, date("m.d.Y g:ia")."  ".$_SERVER['REMOTE_ADDR']."  ".$fname."\n");

  @fclose($f);

} */





?>