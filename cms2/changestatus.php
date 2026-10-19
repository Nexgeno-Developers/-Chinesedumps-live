<?php
session_start();

include_once ("../includes/config/classDbConnection.php");
include ("../includes/common/inc/sessionheader.php");
$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT

//-----------------------------Base Path-----------------------------------------------
error_reporting(0);
$fieldStatus="";
require_once dirname(dirname(__FILE__)) . '/includes/common/sanitize.php';
// whitelist of table => array(id field, status fields) used by the admin status toggles
$csMap = array(
	'order_master' => array('ID', array('Status')),
	'tbl_vendors'  => array('ven_id', array('ven_status', 'ven_home')),
	'tbl_cert'     => array('cert_id', array('cert_status', 'cert_home')),
	'tbl_exam'     => array('exam_id', array('exam_status', 'exam_home'))
);
$getID    		=  isset($_GET['id']) ? san_int($_GET['id']) : 0;
$tblName  		=  isset($_GET['tb']) ? san_text($_GET['tb']) : '';
$tblField 		=  isset($_GET['fld']) ? san_text($_GET['fld']) : '';
$fieldStatus    =  isset($_GET['flds']) ? san_text($_GET['flds']) : '';
if (!isset($csMap[$tblName]) || $csMap[$tblName][0] !== $tblField || !in_array($fieldStatus, $csMap[$tblName][1], true)) {
	exit;
}

$status="";
 
 	 $sql_cate    = "select * from ".$tblName."  where ".$tblField." = '".$getID."'";
 	$result_cate = mysql_query($sql_cate);
	$row_cate    = mysql_fetch_array($result_cate);
	
	if($row_cate[$fieldStatus]==1)
	{
	
	$sql = "update ".$tblName." set ".$fieldStatus." = 0 where ".$tblField." ='".$getID."'";
	mysql_query($sql);
	
	$status= "<a href='javascript:showHint(".$row_cate[$tblField].", \"$tblName\",\"$tblField\",\"$fieldStatus\")' style=\"color:#FF0000\">Disable</a>";
	}
	else
	{
	$sql = "update ".$tblName." set ".$fieldStatus." =1 where ".$tblField." ='".$getID."'";
	mysql_query($sql);
	$status= "<a href='javascript:showHint(".$row_cate[$tblField].", \"$tblName\",\"$tblField\",\"$fieldStatus\")'>Enable</a>";
	}
	
	echo $status;
	//echo $getID." ".$tblName." ".$tblField." ".$fieldStatus;
	
	
?>
