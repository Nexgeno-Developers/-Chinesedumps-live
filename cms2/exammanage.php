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
		$rowsPerPage      	= 	100;											
		}												
	$linkPerPage      	= 	25;
	$of					=	"of";
//----------------------------------------------------------------------------------------------------		
		if(!isset($pgObj) && empty($pgObj))
				$pgObj 		=  new classPaging ("exammanage.php",$rowsPerPage,$linkPerPage,"","","");
		if(isset($_GET['pgIndex']) && !empty($_GET['pgIndex']) && is_numeric($_GET['pgIndex']) && is_numeric($_GET['currentPage']))
				{
					$PageNo			 = $_GET['currentPage'];	# Shows # of Page			
					$PageIndex		 = $_GET['pgIndex'];		# Shows # of Page Index				
				}	
					$LIMIT .= " LIMIT " . (($PageNo-1) * $rowsPerPage). " , " . $rowsPerPage;
//----------------------------------------------------------------------------------------------------		
	if(isset($_GET['action'])&& $_GET['action'] == "up"){
        $objEvent->delete_exam($_GET['nid']);
	}
	if(isset($_POST['btn_delete']) && $_POST['btn_delete']	== "Delete Selected Items" ){		
		$counter	=	$_POST['counter']; 
		for($i=1; $i<=$counter; $i++){ 
			if(isset($_POST['chkbox'.$i])){
			    $objEvent->delete_exam($_POST['chkbox'.$i]);
			}
		}
	}
	if(isset($_POST['Search'])){
	    $conditions = [];
        
        if (!empty($_POST['textSearch'])) {
            $conditions[] = "tbl_exam.exam_name LIKE '%" . mysql_real_escape_string($_POST['textSearch']) . "%'";
        }
        
        if (!empty($_POST['criteriacriteria'])) {
            $conditions[] = "tbl_exam.exam_status = '" . mysql_real_escape_string($_POST['criteriacriteria']) . "'";
        }
        
        if (!empty($_POST['homehome'])) {
            $conditions[] = "tbl_exam.exam_home = '" . mysql_real_escape_string($_POST['homehome']) . "'";
        }
        
        if (!empty($_POST['hothot'])) {
            $conditions[] = "tbl_exam.exam_hot = '" . mysql_real_escape_string($_POST['hothot']) . "'";
        }
        
        if (!empty($_POST['vendor_id'])) {
            $conditions[] = "tbl_exam.ven_id = '" . intval($_POST['vendor_id']) . "'";
        }
        
        $whereClause = '';
        if (!empty($conditions)) {
            $whereClause = ' WHERE ' . implode(' AND ', $conditions);
        }


/*
	    if($_POST['textSearch']!=''){
			if($_POST['hothot']==''&& $_POST['homehome']==''&& $_POST['criteriacriteria']==''){
				$extra	=	"tbl_exam.exam_name LIKE '%".$_POST['textSearch']."%' ";
			}else{
				    $extra	=	"tbl_exam.exam_name LIKE '%".$_POST['textSearch']."%' and ";
			}		
		}else{
			$extra	=	"";
		}
		if($_POST['criteriacriteria']!=''){
            if($_POST['hothot']==''&& $_POST['homehome']==''&& $_POST['textSearch']==''){
                $criteriasearch	=	"tbl_exam.exam_status='".$_POST['criteriacriteria']."'";
            }else{
				if($_POST['feature']=='' && $_POST['hothot']==''){
					$criteriasearch	=	"tbl_exam.exam_status ='".$_POST['criteriacriteria']."'";
				}else{
					$criteriasearch	=	"tbl_exam.exam_status ='".$_POST['criteriacriteria']."' and ";
				}
            }
		}else{
			$criteriasearch	=	"";
		}
		if($_POST['homehome']!=''){
			if($_POST['hothot']==''&& $_POST['textSearch']=='' && $_POST['criteriacriteria']==''){
				$homesearch	=	"tbl_exam.exam_home ='".$_POST['homehome']."'";
				}else{
					if($_POST['hothot']==''){
						$homesearch	=	"tbl_exam.exam_home ='".$_POST['homehome']."'";
					}else{
						$homesearch	=	"tbl_exam.exam_home ='".$_POST['homehome']."' and ";
					}				
				}			
		}else{
			$homesearch	=	"";
		}
		if($_POST['hothot']!=''){
			if($_POST['homehome']==''&& $_POST['textSearch']=='' && $_POST['criteriacriteria']==''){
				$hotsearch	=	"tbl_exam.exam_hot='".$_POST['hothot']."'";
				}else{
					$hotsearch	=	"tbl_exam.exam_hot='".$_POST['hothot']."'";
				}			
		}else{
			$hotsearch	=	"";
		}
*/
	}
	if(isset($_GET['ord'])){
		if($_GET['ord']=='vendor'){
			$ordertype	=	"tbl_vendors";
			$ordersort	=	"ven_name";
			$ordersortord	=	$_GET['ty'];
			}else{
			$ordertype	=	"tbl_exam";
			$ordersort	=	"exam_name";
			$ordersortord	=	$_GET['tyexam'];
			}
	}else{
			$ordertype	=	"tbl_exam";
			$ordersort	=	"exam_name";
			$ordersortord	=	"ASC";
	}
	if(isset($_POST['Search']) )
	{
		if (empty($conditions)) {
// 		if($_POST['textSearch']=='' && $_POST['hothot']==''&& $_POST['homehome']==''&& $_POST['criteriacriteria']==''){
			$queryall	=	"SELECT * FROM tbl_exam order by ".$ordertype.".".$ordersort."  ".$ordersortord."".$limist;
			$result1		=	mysql_query($queryall);
			$pgObj->SetNavigationalLinksNew($result1);
			$queryall2	=	"SELECT * FROM tbl_exam order by ".$ordertype.".".$ordersort."  ".$ordersortord."".$LIMIT;
			$result		=	mysql_query($queryall2);
		}else{
            $queryall = "SELECT * FROM tbl_exam" . $whereClause . " ORDER BY " . $ordertype . "." . $ordersort . " " . $ordersortord . $limist;
            $result1 = mysql_query($queryall);
            $pgObj->SetNavigationalLinksNew($result1);
            
            $queryall2 = "SELECT * FROM tbl_exam" . $whereClause . " ORDER BY " . $ordertype . "." . $ordersort . " " . $ordersortord . $LIMIT;
            $result = mysql_query($queryall2);

            /*
			$queryall	=	"SELECT * FROM tbl_exam where ".$extra." ".$criteriasearch." ".$homesearch." ".$hotsearch."  order by ".$ordertype.".".$ordersort."  ".$ordersortord."".$limist;
			$result1		=	mysql_query($queryall);
			$pgObj->SetNavigationalLinksNew($result1);
			$queryall2	=	"SELECT * FROM tbl_exam where ".$extra." ".$criteriasearch." ".$homesearch." ".$hotsearch."  order by ".$ordertype.".".$ordersort."  ".$ordersortord."".$LIMIT;
			$result		=	mysql_query($queryall2);
			*/
			
            //$sql =   "select * from tbl_exam where ".$extra." ".$criteriasearch." ".$homesearch." ".$hotsearch." ";
            //  $result1=mysql_query($sql);
            //$pgObj->SetNavigationalLinksNew($result1);
            //$sql =   "select * from tbl_exam where ".$extra." ".$criteriasearch." ".$homesearch." ".$hotsearch." ";
            // $result=mysql_query($sql);
	    }
	}else{
			$queryall	=	"SELECT * FROM tbl_exam order by ".$ordertype.".".$ordersort."  ".$ordersortord."".$limist;
			$result1		=	mysql_query($queryall);
			$pgObj->SetNavigationalLinksNew($result1);
			$queryall2	=	"SELECT * FROM tbl_exam order by ".$ordertype.".".$ordersort."  ".$ordersortord."".$LIMIT;
			$result		=	mysql_query($queryall2);
			//$result		=	$objMain->get_all_Tabledate($LIMIT,'tbl_exam'," order by exam_id  DESC");
	}
//----------------------------------------------------------------------------------------------------		
//----------------------------------------------------------------------------------------------------		
	if(mysql_num_rows($result)<1){
        $emptyError	=	"No record exist.";
	}else{
        $emptyError	=	'<input type="submit" name="btn_delete" id="btn_delete" class="button" value="Delete Selected Items" onclick= "return delete_pro();" />';
        $show	=	$objProduct->show_allExams($result);		
	}
//----------------------------------------------------------------------------------------------------			
	include("html/exammanage.html");
//----------------------------------------------------------------------------------------------------
?>