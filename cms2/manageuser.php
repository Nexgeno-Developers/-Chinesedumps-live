<?php
ob_start();
session_start();

include ("../includes/config/classDbConnection.php");
include("../includes/common/functions/func_uploadimg.php");
include ("../includes/common/inc/sessionheader.php");
include("../includes/common/classes/classUser.php");
include("../includes/common/classes/classPagingAdmin.php");
include("../functions.php");

$objDBcon = new classDbConnection;
$objUser = new classUser($objDBcon);

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

function manageuser_format_amount($amount)
{
	return '$'.number_format((float)$amount, 2);
}

function manageuser_format_date($value)
{
	$value = trim($value);
	if($value == '' || $value == '0000-00-00' || $value == '0000-00-00 00:00:00')
	{
		return '-';
	}

	$time = strtotime($value);
	if($time === false)
	{
		return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	}

	return date('d M Y', $time);
}

function manageuser_get_sort_url($column, $stateParams, $currentSortBy, $currentSortDir)
{
	$params = $stateParams;
	unset($params['currentPage'], $params['pgIndex']);
	$params['sort_by'] = $column;
	$params['sort_dir'] = ($currentSortBy == $column && strtoupper($currentSortDir) == 'ASC') ? 'DESC' : 'ASC';

	return 'manageuser.php?'.manageuser_build_query($params);
}

function manageuser_get_sort_indicator($column, $currentSortBy, $currentSortDir)
{
	if($currentSortBy != $column)
	{
		return '';
	}

	return (strtoupper($currentSortDir) == 'ASC') ? ' (ASC)' : ' (DESC)';
}

$show = '';
$LIMIT = '';
$TotalRecs = 0;
$NaviLinks = '';
$BackNaviLinks = '';
$ForwardNaviLinks = '';
$TotalPages = '';
$PageNo = 1;
$PageIndex = 1;
$rowsPerPage = 100;
$linkPerPage = 25;
$noticeMessages = array();
$noticeClass = 'user-alert is-info';
$userRowsHtml = '';

$allowedSorts = array(
	'name' => "CONCAT_WS(' ', tbl_user.user_fname, tbl_user.user_lname)",
	'email' => "tbl_user.user_email",
	'phone' => "tbl_user.user_phone",
	'password' => "tbl_user.user_password",
	'created' => "tbl_user.creatDate",
	'purchased' => "order_count",
	'total_purchase' => "total_purchase_amount",
	'last_purchase' => "last_purchase_date",
	'status' => "tbl_user.user_status"
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

$textSearch = isset($_GET['textSearch']) ? trim($_GET['textSearch']) : '';
$textSearch = preg_replace('/\s+/', ' ', $textSearch);

$rawStartDate = isset($_GET['txt_StartDate']) ? trim($_GET['txt_StartDate']) : '';
$rawEndDate = isset($_GET['txt_StartDate2']) ? trim($_GET['txt_StartDate2']) : '';
$txt_StartDate = manageuser_normalize_date($rawStartDate);
$txt_StartDate2 = manageuser_normalize_date($rawEndDate);

$sortBy = (isset($_GET['sort_by']) && isset($allowedSorts[$_GET['sort_by']])) ? $_GET['sort_by'] : 'created';
$sortDir = (isset($_GET['sort_dir']) && strtoupper($_GET['sort_dir']) == 'ASC') ? 'ASC' : 'DESC';

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
if($sortBy != 'created')
{
	$filterParams['sort_by'] = $sortBy;
}
if($sortDir != 'DESC')
{
	$filterParams['sort_dir'] = $sortDir;
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
if($textSearch != '')
{
	$escapedSearch = mysql_real_escape_string($textSearch);
	$whereClauses[] = "(
		CONCAT_WS(' ', tbl_user.user_fname, tbl_user.user_lname) LIKE '%".$escapedSearch."%'
		OR tbl_user.user_email LIKE '%".$escapedSearch."%'
		OR tbl_user.user_phone LIKE '%".$escapedSearch."%'
	)";
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

$sortSql = $allowedSorts[$sortBy].' '.$sortDir;
if($sortBy != 'created')
{
	$sortSql .= ", tbl_user.user_id DESC";
}

$selectClause = "
	SELECT
		tbl_user.*,
		COUNT(order_master.ID) AS order_count,
		COALESCE(SUM(order_master.Net_Amount), 0) AS total_purchase_amount,
		MAX(order_master.OrderDate) AS last_purchase_date
	FROM tbl_user
	LEFT JOIN order_master ON (order_master.Cust_ID = tbl_user.user_id)
";

$groupBySql = " GROUP BY tbl_user.user_id";

$listQuery = $selectClause.$whereSql.$groupBySql." ORDER BY ".$sortSql;
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

$rowCounter = 1;
while($rows = mysql_fetch_array($result))
{
	$rowClass = (($rowCounter % 2) == 0) ? ' class="user-row-alt"' : '';
	$fullName = trim($rows['user_fname'].' '.$rows['user_lname']);
	if($fullName == '')
	{
		$fullName = '-';
	}

	$userPhone = trim($rows['user_phone']);
	if($userPhone == '')
	{
		$userPhone = '-';
	}

	$statusValue = trim($rows['user_status']);
	if($statusValue == '')
	{
		$statusValue = 'Unknown';
	}
	$statusClass = (strcasecmp($statusValue, 'Active') == 0) ? 'is-active' : 'is-inactive';

	$orderCount = (int)$rows['order_count'];
	$purchasedHtml = 'No';
	if($orderCount > 0)
	{
		$ordersUrl = 'masterorder.php?'.manageuser_build_query(array('user_id' => $rows['user_id']));
		$purchasedHtml = '<a class="purchase-link" href="'.$ordersUrl.'">Yes</a>';
	}

	$edit = '<a class="action-link" href="edituser.php?nid='.$rows['user_id'].(($stateQueryString != '') ? '&'.$stateQueryString : '').'">Edit</a>';
	$sendemail = '<a class="action-link" href="manageuser.php?mainsend=send&user='.$rows['user_id'].(($stateQueryString != '') ? '&'.$stateQueryString : '').'">Send Email</a>';
	$del = '<a class="action-link action-link-danger" href="manageuser.php?action=up&nid='.$rows['user_id'].(($stateQueryString != '') ? '&'.$stateQueryString : '').'" onclick="return delete_pro();">Delete</a>';

	$userRowsHtml .= '<tr'.$rowClass.'>';
	$userRowsHtml .= '<td class="item" align="center" valign="middle"><input type="checkbox" name="chkbox'.$rowCounter.'" id="chkbox'.$rowCounter.'" value="'.$rows['user_id'].'" /></td>';
	$userRowsHtml .= '<td class="item user-name-cell" align="left">'.htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8').'</td>';
	$userRowsHtml .= '<td class="item" align="center">'.htmlspecialchars($rows['user_email'], ENT_QUOTES, 'UTF-8').'</td>';
	$userRowsHtml .= '<td class="item" align="center">'.htmlspecialchars($userPhone, ENT_QUOTES, 'UTF-8').'</td>';
	$userRowsHtml .= '<td class="item" align="center">'.htmlspecialchars($rows['user_password'], ENT_QUOTES, 'UTF-8').'</td>';
	$userRowsHtml .= '<td class="item" align="center">'.manageuser_format_date($rows['creatDate']).'</td>';
	$userRowsHtml .= '<td class="item" align="center">'.$purchasedHtml.'</td>';
	$userRowsHtml .= '<td class="item" align="center">'.manageuser_format_amount($rows['total_purchase_amount']).'</td>';
	$userRowsHtml .= '<td class="item" align="center">'.manageuser_format_date($rows['last_purchase_date']).'</td>';
	$userRowsHtml .= '<td class="item" align="center"><span class="user-status-badge '.$statusClass.'">'.htmlspecialchars($statusValue, ENT_QUOTES, 'UTF-8').'</span></td>';
	$userRowsHtml .= '<td align="center" class="item user-action-cell" nowrap="nowrap">'.$edit.$sendemail.$del.'</td>';
	$userRowsHtml .= '</tr>';

	$rowCounter++;
}

$show = $userRowsHtml.'<input name="counter" type="hidden" value="'.$rowCounter.'" />';
$pageNotice = !empty($noticeMessages) ? implode('<br />', $noticeMessages) : '';
$clearFiltersUrl = 'manageuser.php';
$addUserUrl = 'adduser.php';

$sortNameUrl = manageuser_get_sort_url('name', $stateParams, $sortBy, $sortDir);
$sortEmailUrl = manageuser_get_sort_url('email', $stateParams, $sortBy, $sortDir);
$sortPhoneUrl = manageuser_get_sort_url('phone', $stateParams, $sortBy, $sortDir);
$sortPasswordUrl = manageuser_get_sort_url('password', $stateParams, $sortBy, $sortDir);
$sortCreatedUrl = manageuser_get_sort_url('created', $stateParams, $sortBy, $sortDir);
$sortPurchasedUrl = manageuser_get_sort_url('purchased', $stateParams, $sortBy, $sortDir);
$sortTotalPurchaseUrl = manageuser_get_sort_url('total_purchase', $stateParams, $sortBy, $sortDir);
$sortLastPurchaseUrl = manageuser_get_sort_url('last_purchase', $stateParams, $sortBy, $sortDir);
$sortStatusUrl = manageuser_get_sort_url('status', $stateParams, $sortBy, $sortDir);

$sortNameLabel = 'Name'.manageuser_get_sort_indicator('name', $sortBy, $sortDir);
$sortEmailLabel = 'Email'.manageuser_get_sort_indicator('email', $sortBy, $sortDir);
$sortPhoneLabel = 'Phone'.manageuser_get_sort_indicator('phone', $sortBy, $sortDir);
$sortPasswordLabel = 'Password'.manageuser_get_sort_indicator('password', $sortBy, $sortDir);
$sortCreatedLabel = 'Created Date'.manageuser_get_sort_indicator('created', $sortBy, $sortDir);
$sortPurchasedLabel = 'Purchased'.manageuser_get_sort_indicator('purchased', $sortBy, $sortDir);
$sortTotalPurchaseLabel = 'Total Purchase Amount'.manageuser_get_sort_indicator('total_purchase', $sortBy, $sortDir);
$sortLastPurchaseLabel = 'Last Purchase Date'.manageuser_get_sort_indicator('last_purchase', $sortBy, $sortDir);
$sortStatusLabel = 'Status'.manageuser_get_sort_indicator('status', $sortBy, $sortDir);

include("html/manageuser.html");
?>
