<?PHP

//----------------------------------------------------------------------------------------------------		
	ini_set("display_errors","0");
	session_start();
	error_reporting(0);
	ob_start();
//----------------------------------------------------------------------------------------------------		
	$strTitle	=	"Show Sub Pages";
	
//----------------------------------------------------------------------------------------------------		
include ("../includes/config/classDbConnection.php");
include("../includes/common/classes/classmain.php");
include ("../includes/common/inc/sessionheader.php");

include ("../includes/common/classPaging.php");

//----------------------------------------------------------------------------------------------------		
						/*General Coding Area*/
//----------------------------------------------------------------------------------------------------		
	$objDBcon    = new classDbConnection; // VALIDATION CLASS OBJECT
	$objMain	 = new classMain($objDBcon);
	//$objCustUl	 = new classCustomerUl($objDBcon);
	
	include_once 'functions.php';
	$ccAdminData = get_session_data();
//----------------------------------------------------------------------------------------------------	
	$show	=	"";
	$LIMIT	=	"";
	$limist	=	"";
	$condition	=	"";
	$TotalRecs	      = 0;	# Shows total Records
	$NaviLinks        = "";	# Shows Navigational Links
	$BackNaviLinks	  = "";	# Shows Back Navigational Links	
	$ForwardNaviLinks = "";	# Shows Forward Navigational Links		
	$TotalPages	      = "";	# Shows Total # of Pages
	$PageNo		      = 1;	# Shows # of Page			
	$PageIndex	      = 1 ; # Shows # of Page Index				
	$rowsPerPage      = 10;	# Shows Rows Per Page											
	$linkPerPage      = 1;	# Used to show Links Per Page.
	$Condition		  = "";
	
	if(!isset($pgObj) && empty($pgObj))
			$pgObj 		= new classPaging ("mailmanage.php",$rowsPerPage,$linkPerPage,"","class=\"link\""); 
				
			if(isset($_GET['pgIndex']) && !empty($_GET['pgIndex']) && is_numeric($_GET['pgIndex']) && is_numeric($_GET['currentPage']))
			{
				$PageNo			 = $_GET['currentPage'];	# Shows # of Page			
				$PageIndex		 = $_GET['pgIndex'];		# Shows # of Page Index				
			}	
					$LIMIT .= " LIMIT " . (($PageNo-1) * $rowsPerPage). " , " . $rowsPerPage;
//----------------------------------------------------------------------------------------------------		
	if(isset($_GET['action'])&& $_GET['action'] == "up")
			{
			$objMain->delete_emailmailis($_GET['nid']);
			}
		
		if(isset($_POST['btn_delete']) &&	$_POST['btn_delete']	== "Delete Selected Items" )
			{		
			 $counter	=	$_POST['counter']; 
			for($i=1; $i<=$counter; $i++)
				{ 
					if(isset($_POST['chkbox'.$i]))
					{
				 	$objMain->delete_emailmailis($_POST['chkbox'.$i]);

					}
				}
				
			}
//----------------------------------------------------------------------------------------------------		
	
		
			$result1		=	$objMain->get_allmailslist($limist);	
			$pgObj->SetNavigationalLinksNew($result1);
			$result		 	=	$objMain->get_allmailslist($LIMIT);

//----------------------------------------------------------------------------------------------------		
		if(mysql_num_rows($result)<1)
			{
			$emptyError	=	"No record exist.";
			}
		else
			{
				$emptyError	=	'<input type="submit" name="btn_delete" id="btn_delete" class="button" value="Delete Selected Items" onclick= "return delete_cust();" />';
				
				$show	=	$objMain->show_maillist($result,$websiteURL);		
			}
			
//----------------------------------------------------------------------------------------------------			
	include("html/mailmanage.html");
//----------------------------------------------------------------------------------------------------
?>