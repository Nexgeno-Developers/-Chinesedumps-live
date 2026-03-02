<?php
ob_start();
session_start();
include("includes/config/classDbConnection.php");
include("includes/common/classes/classmain.php");
include("includes/common/classes/classcart.php");

include("functions.php");

$objDBcon   =   new classDbConnection; 
$objMain	=	new classMain($objDBcon);
$objCart	=	new classCart($objDBcon);

$todaydate	=	date("Y-m-d");
$after7days	=	date('Y-m-d',strtotime('+7 days' ) );


//Full Exam Download Query

$q = "select te.exam_id, te.ven_id, te.exam_name, te.exam_full, ve.ven_id, ve.ven_name from tbl_exam te, tbl_vendors ve where te.ven_id=ve.ven_id";
$qry = mysql_query($q);
for($i=0;$i<mysql_num_rows($qry);$i++){
			$res1 = mysql_fetch_object($qry);
			$myresult = $res1->ven_name."/".$res1->exam_name.".zip";
			mysql_query("update tbl_exam set exam_full='".$myresult."' where exam_id='".$res1->exam_id."'");
			}
			

/////////////////////////////////////////////////////////////////Update Date/////////////////////////////////////////////////////////



	
?>