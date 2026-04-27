<?PHP
ini_set("display_errors","0");
//----------------------------------------------------------------------------------------------------		
	session_start();
	error_reporting(0);
//----------------------------------------------------------------------------------------------------		
	$strTitle	=	"ShowNews";
//----------------------------------------------------------------------------------------------------		
include_once ("../includes/config/classDbConnection.php");
include ("../includes/common/classes/classmain.php");
include ("../includes/common/inc/sessionheader.php");
include("../includes/common/classes/classPagingAdmin.php");
include ("../includes/common/classes/classProduct.php");
include ("../includes/common/classes/classEvents.php");
include_once 'functions.php';
$ccAdminData = get_session_data();
//----------------------------------------------------------------------------------------------------		
						/*General Coding Area*/
//----------------------------------------------------------------------------------------------------		
	$objDBcon   = 	new classDbConnection; // VALIDATION CLASS OBJECT
	$objMain	=	new classMain($objDBcon);
	$objProduct	=	new classProduct($objDBcon);
	$objEvent	=	new classEvents($objDBcon);
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
	$rowsPerPage      	= 	1000000;	
	}else{
		$rowsPerPage      	= 	50;											
		}
	$linkPerPage      	= 	10;
	$of					=	"of";

	$allowedSortColumns = array(
		"ven_name" => "ven_name",
		"ven_hot" => "ven_hot",
		"ven_home" => "ven_home",
		"ven_status" => "ven_status",
		"vendor" => "ven_name"
	);

	$ord = "ven_name";
	if(isset($_GET['ord']) && array_key_exists($_GET['ord'], $allowedSortColumns)){
		$ord = $_GET['ord'];
	}

	$ty = "ASC";
	if(isset($_GET['ty']) && strtoupper($_GET['ty']) == "DESC"){
		$ty = "DESC";
	}

	$orderByClause = $allowedSortColumns[$ord];
	$pagingQueryString = "&ord=".$ord."&ty=".$ty;

	$vendorNameNextTy = ($ord == "ven_name" || $ord == "vendor") && $ty == "ASC" ? "DESC" : "ASC";
	$hotNextTy = ($ord == "ven_hot" && $ty == "ASC") ? "DESC" : "ASC";
	$homeNextTy = ($ord == "ven_home" && $ty == "ASC") ? "DESC" : "ASC";
	$statusNextTy = ($ord == "ven_status" && $ty == "ASC") ? "DESC" : "ASC";

	$vendorNameArrow = ($ord == "ven_name" || $ord == "vendor") ? (($ty == "ASC") ? " &uarr;" : " &darr;") : "";
	$hotArrow = ($ord == "ven_hot") ? (($ty == "ASC") ? " &uarr;" : " &darr;") : "";
	$homeArrow = ($ord == "ven_home") ? (($ty == "ASC") ? " &uarr;" : " &darr;") : "";
	$statusArrow = ($ord == "ven_status") ? (($ty == "ASC") ? " &uarr;" : " &darr;") : "";
//----------------------------------------------------------------------------------------------------		
		if(!isset($pgObj) && empty($pgObj))
				$pgObj 		=  new classPaging ("vendormanage.php",$rowsPerPage,$linkPerPage,$pagingQueryString,"","","");
		if(isset($_GET['pgIndex']) && !empty($_GET['pgIndex']) && is_numeric($_GET['pgIndex']) && is_numeric($_GET['currentPage']))
				{
					$PageNo			 = $_GET['currentPage'];	# Shows # of Page			
					$PageIndex		 = $_GET['pgIndex'];		# Shows # of Page Index				
				}	
					$LIMIT .= " LIMIT " . (($PageNo-1) * $rowsPerPage). " , " . $rowsPerPage;
//----------------------------------------------------------------------------------------------------		
	if(isset($_GET['action'])&& $_GET['action'] == "up")
			{
			$objEvent->delete_Vendor($_GET['nid']);
			}
		if(isset($_POST['btn_delete']) && $_POST['btn_delete']	== "Delete Selected Items" )
			{		
			$counter	=	$_POST['counter']; 
			for($i=1; $i<=$counter; $i++)
				{ 
					if(isset($_POST['chkbox'.$i]))
					{
					$objEvent->delete_Vendor($_POST['chkbox'.$i]);
					}
				}
			}
	if(isset($_POST['Search'])){
	if($_POST['textSearch']!=''){
				if($_POST['hothot']==''&& $_POST['homehome']==''&& $_POST['criteriacriteria']==''){
					$extra	=	"ven_name LIKE '%".$_POST['textSearch']."%' ";
					}else{
					$extra	=	"ven_name LIKE '%".$_POST['textSearch']."%' and ";
					}			
			}else{
				$extra	=	"";
			}
		if($_POST['criteriacriteria']!=''){
				if($_POST['hothot']=='' && $_POST['textSearch']=='' && $_POST['homehome']){
					$criteriasearch	=	"ven_status ='".$_POST['criteriacriteria']."'";
					}else{
						if($_POST['homehome']==''&& $_POST['hothot']==''){
							$criteriasearch	=	"ven_status='".$_POST['criteriacriteria']."'";
						}else{
							$criteriasearch	=	"ven_status='".$_POST['criteriacriteria']."' and ";
						}				
					}			
			}else{
				$criteriasearch	=	"";
			}
		if($_POST['homehome']!=''){
				if($_POST['hothot']==''&& $_POST['textSearch']=='' && $_POST['criteriacriteria']==''){
					$homesearch	=	"ven_home ='".$_POST['homehome']."'";
					}else{
						if($_POST['hothot']==''){
							$homesearch	=	"ven_home ='".$_POST['homehome']."'";
						}else{
							$homesearch	=	"ven_home ='".$_POST['homehome']."' and ";
						}				
					}			
			}else{
				$homesearch	=	"";
			}
			if($_POST['hothot']!=''){
				if($_POST['homehome']==''&& $_POST['textSearch']=='' && $_POST['criteriacriteria']==''){
					$hotsearch	=	"ven_hot ='".$_POST['hothot']."'";
					}else{
						$hotsearch	=	"ven_hot ='".$_POST['hothot']."'";				
					}			
			}else{
				$hotsearch	=	"";
			}
	}
	if(isset($_POST['Search']) )
	{
			if($_POST['textSearch']=='' && $_POST['hothot']==''&& $_POST['homehome']==''&& $_POST['criteriacriteria']==''){
				$result1		=	$objMain->get_all_Tabledate($limist,'tbl_vendors'," order by ".$orderByClause." ".$ty."");	
				$pgObj->SetNavigationalLinksNew($result1);
				$result		=	$objMain->get_all_Tabledate($LIMIT,'tbl_vendors'," order by ".$orderByClause." ".$ty."");
			}else{
		    $sql =   "select * from tbl_vendors where ".$extra." ".$criteriasearch." ".$homesearch." ".$hotsearch." order by ".$orderByClause." ".$ty."";
		    $result1=mysql_query($sql);
			$pgObj->SetNavigationalLinksNew($result1);
			$sql =   "select * from tbl_vendors where ".$extra." ".$criteriasearch." ".$homesearch." ".$hotsearch." order by ".$orderByClause." ".$ty."";
		    $result=mysql_query($sql);
	}
	}
	else
	{
			$result1		=	$objMain->get_all_Tabledate($limist,'tbl_vendors'," order by ".$orderByClause." ".$ty."");	
			$pgObj->SetNavigationalLinksNew($result1);
			$result		=	$objMain->get_all_Tabledate($LIMIT,'tbl_vendors'," order by ".$orderByClause." ".$ty."");
			}
//----------------------------------------------------------------------------------------------------		
//----------------------------------------------------------------------------------------------------		
		if(mysql_num_rows($result)<1)
			{
			$emptyError	=	"No record exist.";
			}
		else
			{
				$emptyError	=	'<input type="submit" name="btn_delete" id="btn_delete" class="button" value="Delete Selected Items" onclick= "return delete_pro();" />';
				$show	=	$objProduct->show_review($result);		
			}
//----------------------------------------------------------------------------------------------------			
	include("html/vendormanage.html");
//----------------------------------------------------------------------------------------------------
?>
