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
		$rowsPerPage      	= 	70;											
		}										
	$linkPerPage      	= 	10;
	$of					=	"of";

	$allowedSortColumns = array(
		"cert_name" => "cert_name",
		"vendor_name" => "(SELECT ven_name FROM tbl_vendors WHERE tbl_vendors.ven_id = tbl_cert.ven_id)",
		"cert_hot" => "cert_hot",
		"cert_home" => "cert_home",
		"cert_status" => "cert_status"
	);

	$ord = "vendor_name";
	if(isset($_GET['ord']) && array_key_exists($_GET['ord'], $allowedSortColumns)){
		$ord = $_GET['ord'];
	}

	$ty = "ASC";
	if(isset($_GET['ty']) && strtoupper($_GET['ty']) == "DESC"){
		$ty = "DESC";
	}

	$orderByClause = $allowedSortColumns[$ord];
	$pagingQueryString = "&ord=".$ord."&ty=".$ty;

	$certNameNextTy = ($ord == "cert_name" && $ty == "ASC") ? "DESC" : "ASC";
	$vendorNameNextTy = ($ord == "vendor_name" && $ty == "ASC") ? "DESC" : "ASC";
	$certHotNextTy = ($ord == "cert_hot" && $ty == "ASC") ? "DESC" : "ASC";
	$certHomeNextTy = ($ord == "cert_home" && $ty == "ASC") ? "DESC" : "ASC";
	$certStatusNextTy = ($ord == "cert_status" && $ty == "ASC") ? "DESC" : "ASC";

	$certNameArrow = ($ord == "cert_name") ? (($ty == "ASC") ? " &uarr;" : " &darr;") : "";
	$vendorNameArrow = ($ord == "vendor_name") ? (($ty == "ASC") ? " &uarr;" : " &darr;") : "";
	$certHotArrow = ($ord == "cert_hot") ? (($ty == "ASC") ? " &uarr;" : " &darr;") : "";
	$certHomeArrow = ($ord == "cert_home") ? (($ty == "ASC") ? " &uarr;" : " &darr;") : "";
	$certStatusArrow = ($ord == "cert_status") ? (($ty == "ASC") ? " &uarr;" : " &darr;") : "";
//----------------------------------------------------------------------------------------------------		

		if(!isset($pgObj) && empty($pgObj))
				$pgObj 		=  new classPaging ("certifmanage.php",$rowsPerPage,$linkPerPage,$pagingQueryString,"","");
		
		if(isset($_GET['pgIndex']) && !empty($_GET['pgIndex']) && is_numeric($_GET['pgIndex']) && is_numeric($_GET['currentPage']))
				{
					$PageNo			 = $_GET['currentPage'];	# Shows # of Page			
					$PageIndex		 = $_GET['pgIndex'];		# Shows # of Page Index				
				}	

					$LIMIT .= " LIMIT " . (($PageNo-1) * $rowsPerPage). " , " . $rowsPerPage;
//----------------------------------------------------------------------------------------------------		
	if(isset($_GET['action'])&& $_GET['action'] == "up")
			{
			$objEvent->delete_Certif($_GET['nid']);
			}
		
		if(isset($_POST['btn_delete']) && $_POST['btn_delete']	== "Delete Selected Items" )
			{		
			$counter	=	$_POST['counter']; 
			
			for($i=1; $i<=$counter; $i++)
				{ 
				
					if(isset($_POST['chkbox'.$i]))
					{
					$objEvent->delete_Certif($_POST['chkbox'.$i]);
					}
				}
				
			}
			
	if(isset($_POST['Search'])){
	
	if($_POST['textSearch']!=''){
				if($_POST['hothot']==''&& $_POST['homehome']==''&& $_POST['criteriacriteria']==''){
					$extra	=	"cert_name LIKE '%".$_POST['textSearch']."%' ";
					}else{
					$extra	=	"cert_name LIKE '%".$_POST['textSearch']."%' and ";
					}			
			}else{
				$extra	=	"";
			}
			
			
			if($_POST['criteriacriteria']!=''){
				if($_POST['hothot']==''&& $_POST['homehome']==''&& $_POST['textSearch']==''){
					$criteriasearch	=	"cert_status='".$_POST['criteriacriteria']."'";
					}else{
						if($_POST['feature']=='' && $_POST['hothot']==''){
							$criteriasearch	=	"cert_status ='".$_POST['criteriacriteria']."'";
						}else{
							$criteriasearch	=	"cert_status ='".$_POST['criteriacriteria']."' and ";
						}				
					}			
			}else{
				$criteriasearch	=	"";
			}
			
			
			
			if($_POST['homehome']!=''){
				if($_POST['hothot']==''&& $_POST['textSearch']=='' && $_POST['criteriacriteria']==''){
					$homesearch	=	"cert_home ='".$_POST['homehome']."'";
					}else{
						if($_POST['hothot']==''){
							$homesearch	=	"cert_home ='".$_POST['homehome']."'";
						}else{
							$homesearch	=	"cert_home ='".$_POST['homehome']."' and ";
						}				
					}			
			}else{
				$homesearch	=	"";
			}
			
			if($_POST['hothot']!=''){
				if($_POST['homehome']==''&& $_POST['textSearch']=='' && $_POST['criteriacriteria']==''){
					$hotsearch	=	"cert_hot='".$_POST['hothot']."'";
					}else{
						$hotsearch	=	"cert_hot='".$_POST['hothot']."'";
									
					}			
			}else{
				$hotsearch	=	"";
			}
			
	}
	
	
	if(isset($_POST['Search']) )
	{
			if($_POST['textSearch']=='' && $_POST['hothot']==''&& $_POST['homehome']==''&& $_POST['criteriacriteria']==''){
			
				$result1		=	$objMain->get_all_Tabledate($limist,'tbl_cert'," order by ".$orderByClause." ".$ty);	
				$pgObj->SetNavigationalLinksNew($result1);
				$result		=	$objMain->get_all_Tabledate($LIMIT,'tbl_cert'," order by ".$orderByClause." ".$ty);
			}else{
			$sql =   "select * from tbl_cert where ".$extra." ".$criteriasearch." ".$homesearch." ".$hotsearch." order by ".$orderByClause." ".$ty;
		    $result1=mysql_query($sql);
			$pgObj->SetNavigationalLinksNew($result1);
			$sql =   "select * from tbl_cert where ".$extra." ".$criteriasearch." ".$homesearch." ".$hotsearch." order by ".$orderByClause." ".$ty;
		    $result=mysql_query($sql);
			
	}
	}else{
			$result1		=	$objMain->get_all_Tabledate($limist,'tbl_cert'," order by ".$orderByClause." ".$ty);	
			$pgObj->SetNavigationalLinksNew($result1);
			$result		=	$objMain->get_all_Tabledate($LIMIT,'tbl_cert'," order by ".$orderByClause." ".$ty);
	
	
	}
//----------------------------------------------------------------------------------------------------		
	
			

//----------------------------------------------------------------------------------------------------		
		if(mysql_num_rows($result)<1)
			{
			$emptyError	=	"No record exist.";
			}
		else
			{
				$emptyError	=	role_can('Certifications Module', 'delete') ? '<input type="submit" name="btn_delete" id="btn_delete" class="button" value="Delete Selected Items" onclick= "return delete_pro();" />' : '';
				$show	=	$objProduct->show_certif($result);		
			}
			
//----------------------------------------------------------------------------------------------------			
	include("html/certifmanage.html");
//----------------------------------------------------------------------------------------------------
?>
