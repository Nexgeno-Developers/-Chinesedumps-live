<?php
ob_start();
session_start();

include ("../includes/config/classDbConnection.php");
include("../includes/common/functions/func_uploadimg.php");
include ("../includes/common/inc/sessionheader.php");
include("../includes/common/classes/classUser.php");
include("../includes/common/classes/classPagingAdmin.php");

$objDBcon = new classDbConnection;
$objUser = new classUser($objDBcon);

include_once 'functions.php';
$ccAdminData = get_session_data();

function masterorder_normalize_date($value)
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

function masterorder_build_query($params)
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

function masterorder_redirect($params = array())
{
	$url = 'masterorder.php';
	$query = masterorder_build_query($params);
	if($query != '')
	{
		$url .= '?'.$query;
	}
	header('Location: '.$url);
	exit;
}

function masterorder_format_amount($amount)
{
	return '$'.number_format((float)$amount, 2);
}

function masterorder_format_date($value)
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

function masterorder_get_sort_url($column, $stateParams, $currentSortBy, $currentSortDir)
{
	$params = $stateParams;
	unset($params['currentPage'], $params['pgIndex']);
	$params['sort_by'] = $column;
	$params['sort_dir'] = ($currentSortBy == $column && strtoupper($currentSortDir) == 'ASC') ? 'DESC' : 'ASC';

	return 'masterorder.php?'.masterorder_build_query($params);
}

function masterorder_get_sort_indicator($column, $currentSortBy, $currentSortDir)
{
	if($currentSortBy != $column)
	{
		return '';
	}

	return (strtoupper($currentSortDir) == 'ASC') ? ' (ASC)' : ' (DESC)';
}

$show = '';
$TotalRecs = 0;
$NaviLinks = '';
$BackNaviLinks = '';
$ForwardNaviLinks = '';
$TotalPages = '';
$PageNo = 1;
$PageIndex = 1;
$rowsPerPage = 50;
$linkPerPage = 25;
$LIMIT = '';
$emptyError = '';
$pageNotice = '';
$totalSalesAmount = 0;
$hasRecords = false;

$allowedSorts = array(
	'user_email' => 'tbl_user.user_email',
	'user_password' => 'tbl_user.user_password',
	'Order_Id' => 'order_master.Order_Id',
	'OrderDesc' => 'order_master.OrderDesc',
	'OrderDate' => 'order_master.OrderDate',
	'Net_Amount' => 'order_master.Net_Amount',
	'coupon_code' => 'coupon_code',
	'computed_value' => 'computed_value'
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

$userIdFilter = '';
if(isset($_GET['user_id']) && ctype_digit((string)$_GET['user_id']))
{
	$userIdFilter = $_GET['user_id'];
}

$rawFromDate = isset($_GET['from_date']) ? trim($_GET['from_date']) : '';
$rawToDate = isset($_GET['to_date']) ? trim($_GET['to_date']) : '';
$fromDate = masterorder_normalize_date($rawFromDate);
$toDate = masterorder_normalize_date($rawToDate);

$sortBy = (isset($_GET['sort_by']) && isset($allowedSorts[$_GET['sort_by']])) ? $_GET['sort_by'] : 'OrderDate';
$sortDir = (isset($_GET['sort_dir']) && strtoupper($_GET['sort_dir']) == 'ASC') ? 'ASC' : 'DESC';

if($rawFromDate != '' && $fromDate == '')
{
	$pageNotice .= (($pageNotice != '') ? '<br />' : '').'The from date filter was ignored because it was not in YYYY-MM-DD format.';
}

if($rawToDate != '' && $toDate == '')
{
	$pageNotice .= (($pageNotice != '') ? '<br />' : '').'The to date filter was ignored because it was not in YYYY-MM-DD format.';
}

$filterParams = array();
if($textSearch != '')
{
	$filterParams['textSearch'] = $textSearch;
}
if($userIdFilter != '')
{
	$filterParams['user_id'] = $userIdFilter;
}
if($fromDate != '')
{
	$filterParams['from_date'] = $fromDate;
}
if($toDate != '')
{
	$filterParams['to_date'] = $toDate;
}
if($sortBy != 'OrderDate')
{
	$filterParams['sort_by'] = $sortBy;
}
if($sortDir != 'DESC')
{
	$filterParams['sort_dir'] = $sortDir;
}

$pagingQueryString = masterorder_build_query($filterParams);
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

$stateQueryString = masterorder_build_query($stateParams);

if(isset($_GET['action']) && $_GET['action'] == 'up' && isset($_GET['nid']) && ctype_digit((string)$_GET['nid']) && isset($_GET['cart']))
{
	$objUser->deleteTemordermaster($_GET['nid'], $_GET['cart']);
	$redirectParams = $stateParams;
	$redirectParams['msg'] = 'deleted';
	masterorder_redirect($redirectParams);
}

if(isset($_GET['msg']) && $_GET['msg'] == 'deleted')
{
	$pageNotice .= (($pageNotice != '') ? '<br />' : '').'Order has been deleted successfully.';
}

$pgObj = new classPaging("masterorder.php", $rowsPerPage, $linkPerPage, $pagingQuerySuffix, "", "", "", "", "", "", "", "");

$whereClauses = array();
if($textSearch != '')
{
	$escapedSearch = mysql_real_escape_string($textSearch);
	$whereClauses[] = "(
		order_master.Order_Id LIKE '%".$escapedSearch."%'
		OR tbl_user.user_email LIKE '%".$escapedSearch."%'
		OR CONCAT_WS(' ', tbl_user.user_fname, tbl_user.user_lname) LIKE '%".$escapedSearch."%'
		OR tbl_user.user_phone LIKE '%".$escapedSearch."%'
	)";
}

if($userIdFilter != '')
{
	$whereClauses[] = "order_master.Cust_ID = '".mysql_real_escape_string($userIdFilter)."'";
}

if($fromDate != '')
{
	$whereClauses[] = "DATE(order_master.OrderDate) >= '".$fromDate."'";
}

if($toDate != '')
{
	$whereClauses[] = "DATE(order_master.OrderDate) <= '".$toDate."'";
}

$whereSql = '';
if(!empty($whereClauses))
{
	$whereSql = ' WHERE '.implode(' AND ', $whereClauses);
}

$baseFromSql = "
	FROM order_master
	INNER JOIN tbl_user ON (order_master.Cust_ID = tbl_user.user_id)
	LEFT JOIN coupon_order ON (
		coupon_order.order_id = order_master.ID
		AND coupon_order.order_type = '1'
	)
";

$summaryQuery = "SELECT COALESCE(SUM(order_master.Net_Amount), 0) AS total_sales ".$baseFromSql.$whereSql;
$summaryResult = mysql_query($summaryQuery);
if($summaryResult && mysql_num_rows($summaryResult) > 0)
{
	$summaryRow = mysql_fetch_array($summaryResult);
	$totalSalesAmount = (float)$summaryRow['total_sales'];
}

$sortSql = $allowedSorts[$sortBy].' '.$sortDir.', order_master.ID DESC';
$selectQuery = "
	SELECT
		order_master.*,
		tbl_user.user_email,
		tbl_user.user_password,
		tbl_user.user_phone,
		tbl_user.user_fname,
		tbl_user.user_lname,
		COALESCE(coupon_order.coupon_code, 'N/A') AS coupon_code,
		COALESCE(coupon_order.computed_value, 0) AS computed_value
	".$baseFromSql.$whereSql." ORDER BY ".$sortSql;

$result1 = mysql_query($selectQuery);
$pgObj->SetNavigationalLinksNew($result1);
$result = mysql_query($selectQuery.$LIMIT);

$recordsOnPage = mysql_num_rows($result);
$hasRecords = ($recordsOnPage > 0);

if(!$hasRecords)
{
	$emptyError = 'No order records matched the current filters.';
}

$rowCounter = 1;
$showRows = '';
while($rows = mysql_fetch_array($result))
{
	$intColor = (($rowCounter % 2) != 0) ? '#FFFFFF' : '#f3eeee';

	if($rows['Status'] == 1)
	{
		$chek = 'Disable this';
		$colstatus = 'style="color:#FF0000"';
	}
	else
	{
		$chek = 'Enable this';
		$colstatus = 'style="color:#00FF00"';
	}

	$tableName = "order_master";
	$tablefield = "ID";
	$tablestatus = "Status";
	$statusOrder = "<div id='txtHint".$rows['ID']."'><a href='javascript:showHint(".$rows['ID'].", \"$tableName\",\"$tablefield\",\"$tablestatus\")' ".$colstatus.">".$chek." </a></div>";

	$deleteUrl = 'masterorder.php?action=up&nid='.$rows['ID'].'&cart='.$rows['Cart_ID'];
	if($stateQueryString != '')
	{
		$deleteUrl .= '&'.$stateQueryString;
	}

	$showRows .= '<tr bgcolor="'.$intColor.'">';
	$showRows .= '<td class="item" style="padding-left: 0px;">'.htmlspecialchars($rows['user_email'], ENT_QUOTES, 'UTF-8').'</td>';
	$showRows .= '<td class="item" align="left" valign="middle">'.htmlspecialchars($rows['user_password'], ENT_QUOTES, 'UTF-8').'</td>';
	$showRows .= '<td width="5" class="item" style="padding-right: 0px;">'.htmlspecialchars($rows['Order_Id'], ENT_QUOTES, 'UTF-8').'</td>';
	$showRows .= '<td class="item" style="padding-left: 0px;"><i>'.htmlspecialchars($rows['OrderDesc'], ENT_QUOTES, 'UTF-8').'</i></td>';
	$showRows .= '<td class="item" style="padding-left: 0px;">'.masterorder_format_date($rows['OrderDate']).'</td>';
	$showRows .= '<td class="item" style="padding-left: 0px;">'.masterorder_format_amount($rows['Net_Amount']).'</td>';
	$showRows .= '<td class="item" style="padding-left: 0px;">'.htmlspecialchars($rows['coupon_code'], ENT_QUOTES, 'UTF-8').'</td>';
	$showRows .= '<td class="item" style="padding-left: 0px;">'.masterorder_format_amount($rows['computed_value']).'</td>';
	$showRows .= '<td class="item" style="padding-left: 0px;">'.$statusOrder.'</td>';
	$showRows .= '<td class="item" nowrap="nowrap"><a href="orderdetail.php?ord='.$rows['ID'].'">Details</a></td>';
	$showRows .= '</tr>';

	$rowCounter++;
}

$show = $showRows;
$clearFiltersUrl = 'masterorder.php';

$sortUserEmailUrl = masterorder_get_sort_url('user_email', $stateParams, $sortBy, $sortDir);
$sortPasswordUrl = masterorder_get_sort_url('user_password', $stateParams, $sortBy, $sortDir);
$sortOrderIdUrl = masterorder_get_sort_url('Order_Id', $stateParams, $sortBy, $sortDir);
$sortOrderDescUrl = masterorder_get_sort_url('OrderDesc', $stateParams, $sortBy, $sortDir);
$sortOrderDateUrl = masterorder_get_sort_url('OrderDate', $stateParams, $sortBy, $sortDir);
$sortNetAmountUrl = masterorder_get_sort_url('Net_Amount', $stateParams, $sortBy, $sortDir);
$sortCouponUrl = masterorder_get_sort_url('coupon_code', $stateParams, $sortBy, $sortDir);
$sortDiscountUrl = masterorder_get_sort_url('computed_value', $stateParams, $sortBy, $sortDir);

$sortUserEmailLabel = 'User ID'.masterorder_get_sort_indicator('user_email', $sortBy, $sortDir);
$sortPasswordLabel = 'Password'.masterorder_get_sort_indicator('user_password', $sortBy, $sortDir);
$sortOrderIdLabel = 'OrderID'.masterorder_get_sort_indicator('Order_Id', $sortBy, $sortDir);
$sortOrderDescLabel = 'Product Desc.'.masterorder_get_sort_indicator('OrderDesc', $sortBy, $sortDir);
$sortOrderDateLabel = 'Date'.masterorder_get_sort_indicator('OrderDate', $sortBy, $sortDir);
$sortNetAmountLabel = 'Net Amount'.masterorder_get_sort_indicator('Net_Amount', $sortBy, $sortDir);
$sortCouponLabel = 'Coupon'.masterorder_get_sort_indicator('coupon_code', $sortBy, $sortDir);
$sortDiscountLabel = 'Discount'.masterorder_get_sort_indicator('computed_value', $sortBy, $sortDir);

include("html/masterorder.html");
?>
