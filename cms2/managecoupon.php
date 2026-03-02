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
$ccAdminData = get_session_data();
//-----------------------------------------------------------------
//----------------------------------------------------------------------------------------------------	
	$show				=	"";
	$title				=	"";
	$resultset			=	"";
	$condition			=	"";
	$limist				=	"";
	$LIMIT				=	"";
	$TotalRecs	     	= 	0;	
	$NaviLinks        	= 	"";	
	$BackNaviLinks	 	= 	"";		
	$ForwardNaviLinks 	= 	"";		
	$TotalPages	      	= 	"";
	$PageNo		      	= 	1;
	$PageIndex	      	= 	1 ;				
	if(isset($_POST['Search'])){
	$rowsPerPage      	= 	1000000000;
	}else{
	$rowsPerPage      	= 	100;
	}														
	$linkPerPage      	= 	25;
	$of					=	"of";
//----------------------------------------------------------------------------------------------------		

		if(!isset($pgObj) && empty($pgObj))
				$pgObj 		=  new classPaging ("managecoupon.php",$rowsPerPage,$linkPerPage,"","","");
		
		if(isset($_GET['pgIndex']) && !empty($_GET['pgIndex']) && is_numeric($_GET['pgIndex']) && is_numeric($_GET['currentPage']))
				{
					$PageNo			 = $_GET['currentPage'];	# Shows # of Page			
					$PageIndex		 = $_GET['pgIndex'];		# Shows # of Page Index				
				}	

					$LIMIT .= " LIMIT " . (($PageNo-1) * $rowsPerPage). " , " . $rowsPerPage;
//----------------------------------------------------------------------------------------------------	
function getCouponhtml($spram)
{
	$showTr 	=	"";
	$counter	=	"";
	$comments	=	"";
	$edit	=	"";
	$del	=	"";
	$counter	=	'1';
	while($rows=mysql_fetch_array($spram))
	{	
		if (($counter % 2)!=0 )
			$intColor = "";
		else
			$intColor = "bgcolor='#f3eeee'";
		
		$coupon_type="Total";
		if($rows['coupon_type']==2)
			$coupon_type="Percent";
		$edit	=	'<a href="editcoupon.php?nid='.$rows['id'].'">Edit </a>';
		$del	=	'| <a href="managecoupon.php?action=up&nid='.$rows['id'].'" onclick= "return delete_pro();">Delete</a>';
		
		$showTr		.='<tr '.$intColor.'>';
		$showTr		.=		'<td class=item align="center" valign="middle"><input type="checkbox" name="chkbox'.$counter.'" id="chkbox'.$counter.'" value="'.$rows['id'].'" /></td>';
$showTr		.='<td width=5 align="center" class=item style=padding-center: 0px;>'.$rows['coupon_title'].'</td>
<td class=item align="center" style=padding-center: 0px;>'.$rows['coupon_code'].'</td>
<td class=item align="center" style=padding-center: 0px;>'.$rows['start_date'].'</td>
<td align="center" class=item>'.$rows['end_date'].'</td>
<td class=item align="center" style=padding-center: 0px;>'.$rows['coupon_value'].'</td>
<td class=item align="center" style=padding-center: 0px;>'.$coupon_type.'</td>';
$showTr		.=		'<td align="center" class=item nowrap=nowrap>'.$edit.''.$del.' </td>';
		$showTr		.=		'  </tr>';	
		$counter++;
	}
	return $showTr.'<input name="counter" type="hidden" value="'.$counter.'" />';
}
    if(isset($_GET['action']) && $_GET['action']	== "up" )
	{
		$strSql='DELETE FROM coupon WHERE id="'.$_GET['nid'].'"';
		mysql_query($strSql);
	}

	if(isset($_POST['btn_delete']) &&	$_POST['btn_delete']	== "Delete Selected Items" )
	{		
			$counter	=	$_POST['counter']; 
			for($i=1; $i<=$counter; $i++)
				{ 
					if(isset($_POST['chkbox'.$i]))
					{
						$strSql='DELETE FROM coupon WHERE id="'.$_POST['chkbox'.$i].'"';
						mysql_query($strSql);
					}
				}
	}
	
	if(isset($_POST['Search']))
	{
		if($_POST['textSearch']!=''){
			if($_POST['txt_StartDate']=='' && $_POST['txt_StartDate2']==''){
				$cri	=	$_POST['criteria']." LIKE '%".$_POST['textSearch']."%' ";
			}else{
				$cri	=	$_POST['criteria']." LIKE '%".$_POST['textSearch']."%' and ";
				}
			}else{
			$cri	=	'';
			}
		if($_POST['txt_StartDate']!=''){
		if($_POST['txt_StartDate2']!=''){
		$stDate	=	" creatDate>='".$_POST['txt_StartDate']."' and ";
		}else{
		$stDate	=	"creatDate>='".$_POST['txt_StartDate']."'";
		}}else{
		$stDate	=	'';
		}
		
		if($_POST['txt_StartDate2']!=''){
		$enDate	=	"creatDate<='".$_POST['txt_StartDate2']."'";
		}else{
		$enDate	=	'';
		}
		if(isset($_POST['criteria']) && $_POST['criteria']=='Order_Id'){
		$que	=	"SELECT * FROM tbl_user INNER JOIN order_master ON (order_master.Cust_ID=tbl_user.user_id) WHERE order_master.Order_Id LIKE '%".$_POST['textSearch']."%'";
		$result1	=	mysql_query($que);
		$pgObj->SetNavigationalLinksNew($result1);
		$result= mysql_query($que);
		}else{		
		$result1	=	mysql_query("select * from tbl_user where ".$cri." ".$stDate." ".$enDate." order by creatDate DESC".$limist);
		$pgObj->SetNavigationalLinksNew($result1);
		$result= mysql_query("select * from tbl_user where ".$cri." ".$stDate." ".$enDate." order by creatDate DESC".$LIMIT);
		}
	
	}else{
		$qry = "select * from  coupon order by `id` DESC".$LIMIT;
		$result1 = mysql_query($qry);
		$qry2 = "select * from  coupon order by `id` DESC";
		$result2 = mysql_query($qry2);
		$pgObj->SetNavigationalLinksNew($result2);
	}
	
	
//............................................
	
//............................................

	if(mysql_num_rows($result2) < 1)
	{
		$emptyError	=	"No record exist.";
	}
	else
	{
		$emptyError	=	'<input type="submit" name="btn_delete" id="btn_delete" class="button" value="Delete Selected Items" onclick="return Cofirm();"  />';
	}
	$show =	getCouponhtml($result1);

//------------------------html---------------------------------------

include("html/managecoupon.html");

//------------------------html---------------------------------------

?>