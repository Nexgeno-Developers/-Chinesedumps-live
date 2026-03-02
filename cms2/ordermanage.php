<?PHP
ini_set("display_errors","0");
//----------------------------------------------------------------------------------------------------		
	session_start();
	error_reporting(0);
//----------------------------------------------------------------------------------------------------		
	$strTitle	=	"ShowNews";
//----------------------------------------------------------------------------------------------------		
include ("../includes/config/classDbConnection.php");
include ("../includes/common/classes/classmain.php");
include ("../includes/common/inc/sessionheader.php");
include("../includes/common/classes/classPagingAdmin.php");
include ("../includes/common/classes/classProduct.php");
include_once 'functions.php';
$ccAdminData = get_session_data();
//----------------------------------------------------------------------------------------------------		
						/*General Coding Area*/
//----------------------------------------------------------------------------------------------------		
	$objDBcon   = 	new classDbConnection; // VALIDATION CLASS OBJECT
	$objMain	=	new classMain($objDBcon);
	$objProduct	=	new classProduct($objDBcon);
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
	$rowsPerPage      	= 	20;											
	$linkPerPage      	= 	5;
	$of					=	"of";
//----------------------------------------------------------------------------------------------------		

		if(!isset($pgObj) && empty($pgObj))
				$pgObj 		=  new classPaging ("ordermanage.php",$rowsPerPage,$linkPerPage,"","","");
		
		if(isset($_GET['pgIndex']) && !empty($_GET['pgIndex']) && is_numeric($_GET['pgIndex']) && is_numeric($_GET['currentPage']))
				{
					$PageNo			 = $_GET['currentPage'];	# Shows # of Page			
					$PageIndex		 = $_GET['pgIndex'];		# Shows # of Page Index				
				}	

					$LIMIT .= " LIMIT " . (($PageNo-1) * $rowsPerPage). " , " . $rowsPerPage;
//----------------------------------------------------------------------------------------------------		
	if(isset($_GET['action'])&& $_GET['action'] == "up")
			{
			$objProduct->delete_ordertotal($_GET['nid']);
			}
		
		if(isset($_POST['btn_delete']) && $_POST['btn_delete']	== "Delete Selected Items" )
			{		
			$counter	=	$_POST['counter']; 
			
			for($i=1; $i<=$counter; $i++)
				{ 
				
					if(isset($_POST['chkbox'.$i]))
					{
					$objProduct->delete_ordertotal($_POST['chkbox'.$i]);
					}
				}
				
			}
//----------------------------------------------------------------------------------------------------		
	
			$result1		=	$objMain->get_all_Tabledate($limist,'tbl_order'," order by ord_id DESC");	
			$pgObj->SetNavigationalLinksNew($result1);
			$result		=	$objMain->get_all_Tabledate($LIMIT,'tbl_order'," order by  ord_id DESC");

//----------------------------------------------------------------------------------------------------		
		if(mysql_num_rows($result)<1)
			{
			$emptyError	=	"No record exist.";
			}
		else
			{
				$emptyError	=	'<input type="submit" name="btn_delete" id="btn_delete" class="button" value="Delete Selected Items" onclick= "return delete_pro();" />';
				$show	=	$objProduct->show_allorders($result);		
			}
//----------------------------------------------------------------------------------------------------			
	include("html/ordermanage.html");
//----------------------------------------------------------------------------------------------------
?>