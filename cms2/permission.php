<?php

ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/classes/validation_class.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classAdmin.php");

	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT
	$objValid 	 = new Validate_fields;
	$objAdmin	 = new classAdmin($objDBcon);	// VALIDATION CLASS classCategory

//---------------------------------------------------------------
include_once 'functions.php';
$ccAdminData = get_session_data();
$display	=	"";
$message	=	"";

	if(isset($_POST['permission']) &&	$_POST['permission']	== "Update Now" )
	{		
	
	$qry = "select * from  tblmodules";
	
	$res = mysql_query($qry);
	
	$i=0;
	while($rows	=	mysql_fetch_array($res))
	{ 
		
		$read	=	"";
		$edit	=	"";
		$delete	=	"";
		$write	=	"";
		
		if(isset($_POST['read_'.$rows['ModuleID']]))$read	=	"1";
		if(isset($_POST['write_'.$rows['ModuleID']]))$write	=	"1";
		if(isset($_POST['edit_'.$rows['ModuleID']]))$edit	=	"1";
		if(isset($_POST['delete_'.$rows['ModuleID']]))$delete	=	"1";

					
  mysql_query("UPDATE `tblpermissions` SET `Read` = '".$read."',`Write` = '".$write."',	`Edit` = '".$edit."', `Delete` = '".$delete."'  WHERE  `ModuleID` = ".$rows['ModuleID']." and  `AdminID` = ".$_REQUEST['nid']."") ;
		
	}
	$message	=	"Data has been saved...";
}
//............................................

	

	$qry = "select * from  tblmodules a, tblpermissions b  where a.ModuleID = b.ModuleID and b.AdminID = ".$_REQUEST['nid']."";
	$read	=	"";
	$edit	=	"";
	$delete	=	"";
	$write	=	"";
	$res = mysql_query($qry);

	while($row	=	mysql_fetch_array($res))
	{
		$read	=	"";
		$edit	=	"";
		$delete	=	"";
		$write	=	"";
		if($row['Read']=="1") $read	= "checked=checked";
		if($row['Write']=="1") $write	= "checked=checked";
		if($row['Edit']=="1") $edit	= "checked=checked";
		if($row['Delete']=="1") $delete	= "checked=checked";
		
		$display	.=	'<tr height="20">

       <td align="left">'.$row['Name'].'</td>

       <td   align="center"><input name="read_'.$row['ModuleID'].'" '.$read.' type="checkbox" id="read_'.$row['ModuleID'].'" value="'.$row['Read'].'"  /></td>

       <td   align="center"><input name="write_'.$row['ModuleID'].'" '.$write.' type="checkbox" id="write_'.$row['ModuleID'].'" value="'.$row['Write'].'"  /></td>

       <td   align="center"><input name="edit_'.$row['ModuleID'].'" '.$edit.' type="checkbox" id="edit_'.$row['ModuleID'].'" value="'.$row['Edit'].'"  /></td>

       <td  align="center" ><input name="delete_'.$row['ModuleID'].'" '.$delete.' type="checkbox" id="delete_'.$row['ModuleID'].'" value="'.$row['Delete'].'"  /></td>
  
    </tr>';
	}

//------------------------html---------------------------------------

include("html/permission.html");

//------------------------html---------------------------------------

?>