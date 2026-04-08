<?php
ob_start();
session_start();

	include ("../includes/config/classDbConnection.php");
	include("../includes/common/functions/func_uploadimg.php");
	include ("../includes/common/inc/sessionheader.php");
	include("../includes/common/classes/classUser.php");
	include("../includes/common/classes/classPagingAdmin.php");
	include("../functions.php");

	$objDBcon    = new classDbConnection;
	$objUser	 = new classUser($objDBcon);

include_once 'functions.php';
$ccAdminData = get_session_data();

function manageuser_normalize_date($value)
{
	$value = trim($value);
	if($value == '')
	{
		return '';
	}

	if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value))
	{
		return '';
	}

	list($year, $month, $day) = explode('-', $value);
	if(!checkdate((int)$month, (int)$day, (int)$year))
	{
		return '';
	}

	return $value;
}

function manageuser_build_query($params)
{
	$cleanParams = array();
	foreach($params as $key => $value)
	{
		if($value === '' || $value === null)
		{
			continue;
		}
		$cleanParams[$key] = $value;
	}

	return http_build_query($cleanParams);
}

function manageuser_redirect($params = array())
{
	$url = 'manageuser.php';
	$query = manageuser_build_query($params);
	if($query != '')
	{
		$url .= '?'.$query;
	}
	header('Location: '.$url);
	exit;
}

$show				=	"";
$LIMIT				=	"";
$TotalRecs	     	= 	0;
$NaviLinks        	= 	"";
$BackNaviLinks	 	= 	"";
$ForwardNaviLinks 	= 	"";
$TotalPages	      	= 	"";
$PageNo		      	= 	1;
$PageIndex	      	= 	1;
$rowsPerPage      	= 	100;
$linkPerPage      	= 	25;
$noticeMessages	=	array();
$noticeClass		=	'user-alert is-info';

$allowedCriteria = array(
	'Order_Id'		=> 'Transaction Id',
	'user_email'	=> 'Email Address',
	'user_fname'	=> 'First Name',
	'user_lname'	=> 'Last Name',
	'user_phone'	=> 'Phone Number'
);

	if(isset($_GET['pgIndex']) && ctype_digit((string)$_GET['pgIndex']) && (int)$_GET['pgIndex'] > 0)
	{
		$PageIndex = (int)$_GET['pgIndex'];
	}

	if(isset($_GET['currentPage']) && ctype_digit((string)$_GET['currentPage']) && (int)$_GET['currentPage'] > 0)
	{
		$PageNo = (int)$_GET['currentPage'];
	}

$LIMIT = " LIMIT " . (($PageNo-1) * $rowsPerPage). " , " . $rowsPerPage;

$criteria = (isset($_GET['criteria']) && isset($allowedCriteria[$_GET['criteria']])) ? $_GET['criteria'] : 'user_email';
$textSearch = isset($_GET['textSearch']) ? trim($_GET['textSearch']) : '';
$textSearch = preg_replace('/\s+/', ' ', $textSearch);

$rawStartDate = isset($_GET['txt_StartDate']) ? trim($_GET['txt_StartDate']) : '';
$rawEndDate = isset($_GET['txt_StartDate2']) ? trim($_GET['txt_StartDate2']) : '';
$txt_StartDate = manageuser_normalize_date($rawStartDate);
$txt_StartDate2 = manageuser_normalize_date($rawEndDate);

if($rawStartDate != '' && $txt_StartDate == '')
{
	$noticeMessages[] = 'The start date filter was ignored because it was not in YYYY-MM-DD format.';
	$noticeClass = 'user-alert is-warning';
}

if($rawEndDate != '' && $txt_StartDate2 == '')
{
	$noticeMessages[] = 'The end date filter was ignored because it was not in YYYY-MM-DD format.';
	$noticeClass = 'user-alert is-warning';
}

$filterParams = array();
if(isset($_GET['criteria']) || $textSearch != '')
{
	$filterParams['criteria'] = $criteria;
}
if($textSearch != '')
{
	$filterParams['textSearch'] = $textSearch;
}
if($txt_StartDate != '')
{
	$filterParams['txt_StartDate'] = $txt_StartDate;
}
if($txt_StartDate2 != '')
{
	$filterParams['txt_StartDate2'] = $txt_StartDate2;
}

$pagingQueryString = manageuser_build_query($filterParams);
$pagingQuerySuffix = ($pagingQueryString != '') ? '&'.$pagingQueryString : '';

$stateParams = $filterParams;
if($PageNo > 1)
{
	$stateParams['currentPage'] = $PageNo;
}
if($PageIndex > 1)
{
	$stateParams['pgIndex'] = $PageIndex;
}

$stateQueryString = manageuser_build_query($stateParams);
$searchActive = ($textSearch != '' || $txt_StartDate != '' || $txt_StartDate2 != '');

if(isset($_POST['btn_delete']) && $_POST['btn_delete'] == 'Delete Selected Items')
{
	$counter = isset($_POST['counter']) ? (int)$_POST['counter'] : 0;
	for($i=1; $i<=$counter; $i++)
	{
		if(isset($_POST['chkbox'.$i]) && ctype_digit((string)$_POST['chkbox'.$i]))
		{
			$objUser->deleteUser($_POST['chkbox'.$i]);
		}
	}

	$redirectParams = array();
	if(isset($_POST['return_query']) && $_POST['return_query'] != '')
	{
		parse_str($_POST['return_query'], $redirectParams);
	}
	if(!is_array($redirectParams))
	{
		$redirectParams = array();
	}
	unset($redirectParams['action'], $redirectParams['nid'], $redirectParams['mainsend'], $redirectParams['user'], $redirectParams['msg']);
	$redirectParams['msg'] = 'bulk_deleted';
	manageuser_redirect($redirectParams);
}

$actionRedirectParams = $stateParams;

if(isset($_GET['action']) && $_GET['action'] == 'up' && isset($_GET['nid']) && ctype_digit((string)$_GET['nid']))
{
	$objUser->deleteUser($_GET['nid']);
	$actionRedirectParams['msg'] = 'deleted';
	manageuser_redirect($actionRedirectParams);
}

if(isset($_GET['mainsend']) && $_GET['mainsend'] == 'send' && isset($_GET['user']) && ctype_digit((string)$_GET['user']))
{
	$userId = (int)$_GET['user'];
	$getuser = mysql_fetch_array(mysql_query("select * from tbl_user where user_id='".$userId."'"));
	if($getuser)
	{
		$spram = array();
		$spram[1] = $getuser['user_fname'];
		$spram[2] = $getuser['user_lname'];
		$spram[5] = $getuser['user_email'];
		$spram[13] = $getuser['user_phone'];

		signupemail($spram, $fromEmail);
	}
	$actionRedirectParams['msg'] = 'email_sent';
	manageuser_redirect($actionRedirectParams);
}

if(isset($_GET['msg']))
{
	if($_GET['msg'] == 'deleted')
	{
		$noticeMessages[] = 'User has been deleted successfully.';
		$noticeClass = 'user-alert is-success';
	}
	elseif($_GET['msg'] == 'bulk_deleted')
	{
		$noticeMessages[] = 'Selected users have been deleted successfully.';
		$noticeClass = 'user-alert is-success';
	}
	elseif($_GET['msg'] == 'email_sent')
	{
		$noticeMessages[] = 'Email has been successfully sent.';
		$noticeClass = 'user-alert is-success';
	}
}

$pgObj = new classPaging("manageuser.php", $rowsPerPage, $linkPerPage, $pagingQuerySuffix, "", "", "", "", "", "", "", "");

$whereClauses = array();
$escapedSearch = ($textSearch != '') ? mysql_real_escape_string($textSearch) : '';
$queryBase = " FROM tbl_user";
$selectClause = "SELECT tbl_user.*";

if($criteria == 'Order_Id' && $textSearch != '')
{
	$queryBase = " FROM tbl_user INNER JOIN order_master ON (order_master.Cust_ID=tbl_user.user_id)";
	$selectClause = "SELECT DISTINCT tbl_user.*";
	$whereClauses[] = "order_master.Order_Id LIKE '%".$escapedSearch."%'";
}
elseif($textSearch != '')
{
	$whereClauses[] = "tbl_user.".$criteria." LIKE '%".$escapedSearch."%'";
}

if($txt_StartDate != '')
{
	$whereClauses[] = "tbl_user.creatDate >= '".$txt_StartDate."'";
}

if($txt_StartDate2 != '')
{
	$whereClauses[] = "tbl_user.creatDate <= '".$txt_StartDate2."'";
}

$whereSql = '';
if(!empty($whereClauses))
{
	$whereSql = " WHERE ".implode(' AND ', $whereClauses);
}

$listQuery = $selectClause.$queryBase.$whereSql." ORDER BY tbl_user.user_id DESC";
$result1 = mysql_query($listQuery);
$pgObj->SetNavigationalLinksNew($result1);
$result = mysql_query($listQuery.$LIMIT);

$recordsOnPage = mysql_num_rows($result);
$hasRecords = ($recordsOnPage > 0);

if(!$hasRecords)
{
	$emptyMessage = $searchActive ? 'No users matched the current filters.' : 'No user record exists.';
}
else
{
	$emptyMessage = '';
}

$show = $objUser->getUserhtml($result, '', $stateQueryString);
$pageNotice = !empty($noticeMessages) ? implode('<br />', $noticeMessages) : '';
$clearFiltersUrl = 'manageuser.php';
$addUserUrl = 'adduser.php';

include("html/manageuser.html");
?>
