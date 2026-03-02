<?php
ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classUser.php");
	include("../includes/common/classes/classPagingAdmin.php");
	include("../functions.php");
//---------------------------------------------------------------
	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
	$objUser	 = new classUser($objDBcon);	// VALIDATION CLASS classCategory
	
include_once 'functions.php';
if(isset($_POST["ref"]))
{

$ref=$_POST['ref'];

		if($ref=="all")
		{
			$qry = "select * from tbl_user order by `user_id` DESC";
			$result1 = mysql_query($qry);
		}
		else if($ref=="p")
		{
			$qry = "SELECT * FROM tbl_user where user_id in (Select Cust_Id from order_master where Status='1') order by `user_id` DESC";
			$result1 = mysql_query($qry);
		}
		else if($ref=="np")
		{
			$qry = "SELECT * FROM tbl_user where user_id not in (Select Cust_Id from order_master where Status='1') order by `user_id` DESC";
			$result1 = mysql_query($qry);
		}


$delim =  "\t" ;
$csv_output = "User ID"."\t";
$csv_output .= "Name"."\t";
$csv_output .= "Email"."\t";
$csv_output .= "Status"."\t";
$csv_output .= "Joining Date"."\t";
$csv_output .= "\n";
while($row=mysql_fetch_array($result1))
{
	$csv_output.= $row['user_id']."\t" ;
	$csv_output.= $row['user_fname'].' '.$row['user_lname']."\t";
	$csv_output.= $row['user_email']."\t";
	$csv_output.= $row['user_status']."\t";
	$csv_output.= $row['creatDate']."\t";
	$csv_output .= "\n";
}//  while loop main first

//print
header("Content-Type: application/force-download\n");
header("Cache-Control: cache, must-revalidate");   
header("Pragma: public");
header("Content-Disposition: attachment; filename=users_" . date("YmdHis") . ".xls");
print $csv_output;
exit;
}


if(isset($_POST["demo_dne"]) and $_POST["demo_dne"] == "downloademail")
{
$qry1 = "SELECT * FROM tbl_demo_download_email order by `tbl_demo_id`";
$result2 = mysql_query($qry1);

$delim =  "\t" ;
$csv_output1 = "User ID"."\t";
$csv_output1 .= "Exam Code"."\t";
$csv_output1 .= "Email"."\t";
$csv_output1 .= "Download Date"."\t";
$csv_output1 .= "\n";
while($row1=mysql_fetch_array($result2))
{
	$csv_output1.= $row1['tbl_demo_id']."\t" ;
	$csv_output1.= $row1['exam_code']."\t";
	$csv_output1.= $row1['demo_download_email']."\t";
	$csv_output1.= $row1['demo_download_date']."\t";
	$csv_output1 .= "\n";
}//  while loop main first

//print
header("Content-Type: application/force-download\n");
header("Cache-Control: cache, must-revalidate");   
header("Pragma: public");
header("Content-Disposition: attachment; filename=demo_" . date("YmdHis") . ".xls");
print $csv_output1;
exit;
}
///////////////////////////Exam Request/////////////////////
if(isset($_POST["exam_req"]) and $_POST["exam_req"] == "examRequest")
{
$qry1 = "SELECT * FROM exam_request order by `examr_id`";
$result2 = mysql_query($qry1);

$delim =  "\t" ;
$csv_output1 = "User ID"."\t";
$csv_output1 .= "Exam Code"."\t";
$csv_output1 .= "Email"."\t";
$csv_output1 .= "Request Date"."\t";
$csv_output1 .= "\n";
while($row1=mysql_fetch_array($result2))
{
	$csv_output1.= $row1['examr_id']."\t" ;
	$csv_output1.= $row1['examr_code']."\t";
	$csv_output1.= $row1['examr_email']."\t";
	$csv_output1.= $row1['examr_date']."\t";
	$csv_output1 .= "\n";
}//  while loop main first

//print
header("Content-Type: application/force-download\n");
header("Cache-Control: cache, must-revalidate");   
header("Pragma: public");
header("Content-Disposition: attachment; filename=examRequest_" . date("YmdHis") . ".xls");
print $csv_output1;
exit;
}

?>