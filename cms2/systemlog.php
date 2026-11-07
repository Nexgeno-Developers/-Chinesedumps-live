<?php
/*
 * Admin page: Activity Log (system_log table)
 * Lists every action stored by includes/common/system_log.php with filters, paging and CSV export.
 * Read only: log rows cannot be edited or deleted from the admin panel.
 */
require_once dirname(dirname(__FILE__)) . '/includes/common/sanitize.php';
ob_start();
session_start();

include_once ("../includes/config/classDbConnection.php");
include ("../includes/common/inc/sessionheader.php");
include_once 'functions.php';
$ccAdminData = get_session_data();
$objDBcon = new classDbConnection;

//----------------------------------------------------------------------------------------------------
// helpers
//----------------------------------------------------------------------------------------------------
function syslog_view_h($value)
{
	return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function syslog_view_action_label($code)
{
	return ucwords(strtolower(str_replace('_', ' ', (string)$code)));
}

/* Rebuilds the current filters as a query string (for paging / export links). */
function syslog_view_query($filters, $extra = array())
{
	$params = array();
	foreach ($filters as $key => $value) {
		if ($value !== '' && $value !== 0) {
			$params[$key] = $value;
		}
	}
	foreach ($extra as $key => $value) {
		$params[$key] = $value;
	}
	return http_build_query($params);
}

/* Turns the JSON stored in `details` into readable HTML. */
function syslog_view_details_html($json)
{
	if ($json === null || $json === '') {
		return '<em>No extra details.</em>';
	}
	$data = json_decode($json, true);
	if (!is_array($data)) {
		return '<pre class="sl-pre">' . syslog_view_h($json) . '</pre>';
	}
	$html = '';
	if (isset($data['changes']) && is_array($data['changes'])) {
		$html .= '<table class="sl-changes"><tr><th>Field</th><th>Old value</th><th>New value</th></tr>';
		foreach ($data['changes'] as $c) {
			$html .= '<tr><td>' . syslog_view_h(isset($c['field']) ? $c['field'] : '') . '</td><td>' . syslog_view_h(isset($c['old']) ? $c['old'] : '') . '</td><td>' . syslog_view_h(isset($c['new']) ? $c['new'] : '') . '</td></tr>';
		}
		$html .= '</table>';
		unset($data['changes']);
	}
	if (app_count($data) > 0) {
		$html .= '<table class="sl-changes">';
		foreach ($data as $k => $v) {
			if (is_array($v)) {
				$v = json_encode($v);
			}
			$html .= '<tr><th>' . syslog_view_h(ucfirst(str_replace('_', ' ', $k))) . '</th><td>' . syslog_view_h($v) . '</td></tr>';
		}
		$html .= '</table>';
	}
	return $html;
}

//----------------------------------------------------------------------------------------------------
// does the table exist?
//----------------------------------------------------------------------------------------------------
$tableReady = false;
$chk = @mysql_query("SHOW TABLES LIKE '" . SYSLOG_TABLE . "'");
if ($chk && mysql_num_rows($chk) > 0) {
	$tableReady = true;
}

//----------------------------------------------------------------------------------------------------
// delete old log entries (POST only, needs the Delete right on "System Log Module")
//----------------------------------------------------------------------------------------------------
$canPurge = role_can('System Log Module', 'delete');
$purgeMessage = '';
$purgeError = '';
if (!empty($_SESSION['syslog_flash'])) {
	$purgeMessage = (string)$_SESSION['syslog_flash'];
	unset($_SESSION['syslog_flash']);
}
if (empty($_SESSION['syslog_csrf'])) {
	$_SESSION['syslog_csrf'] = function_exists('openssl_random_pseudo_bytes') ? bin2hex(openssl_random_pseudo_bytes(16)) : md5(uniqid(mt_rand(), true));
}

if ($tableReady && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['purge_mode'])) {
	$postedToken = isset($_POST['csrf']) && is_string($_POST['csrf']) ? $_POST['csrf'] : '';
	$purgeWhere = '';
	$purgeTitle = '';
	$purgeInfo = array();

	if (!$canPurge) {
		$purgeError = 'You do not have the Delete right for the System Log, so nothing was deleted.';
	} elseif (!function_exists('hash_equals') ? ($postedToken !== $_SESSION['syslog_csrf']) : !hash_equals($_SESSION['syslog_csrf'], $postedToken)) {
		$purgeError = 'Your session token has expired. Please reload the page and try again.';
	} elseif ($_POST['purge_mode'] == 'older') {
		$days = isset($_POST['purge_days']) ? (int)$_POST['purge_days'] : 0;
		if (!in_array($days, array(7, 30, 90), true)) {
			$purgeError = 'Please choose 7, 30 or 90 days.';
		} else {
			$cutoff = date('Y-m-d H:i:s', strtotime('-' . $days . ' days'));
			$purgeWhere = "created_at < '" . db_esc($cutoff) . "'";
			$purgeTitle = 'older than ' . $days . ' days (before ' . $cutoff . ')';
			$purgeInfo = array('mode' => 'older than N days', 'days' => $days, 'cutoff' => $cutoff);
		}
	} elseif ($_POST['purge_mode'] == 'range') {
		$from = isset($_POST['purge_from']) ? (string)$_POST['purge_from'] : '';
		$to = isset($_POST['purge_to']) ? (string)$_POST['purge_to'] : '';
		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
			$purgeError = 'Please choose both a From date and a To date.';
		} elseif ($from > $to) {
			$purgeError = 'The From date must not be after the To date.';
		} else {
			$purgeWhere = "created_at >= '" . db_esc($from) . " 00:00:00' AND created_at <= '" . db_esc($to) . " 23:59:59'";
			$purgeTitle = 'dated from ' . $from . ' to ' . $to;
			$purgeInfo = array('mode' => 'date range', 'date_from' => $from, 'date_to' => $to);
		}
	} else {
		$purgeError = 'Unknown delete option.';
	}

	if ($purgeWhere !== '') {
		/* what is about to be removed, for the audit entry */
		$stat = mysql_query("SELECT COUNT(*) AS c, MIN(created_at) AS oldest, MAX(created_at) AS newest, MIN(log_id) AS first_id, MAX(log_id) AS last_id FROM `" . SYSLOG_TABLE . "` WHERE " . $purgeWhere);
		$before = $stat ? mysql_fetch_assoc($stat) : false;

		$deleted = 0;
		if ($before && (int)$before['c'] > 0) {
			$ok = mysql_query("DELETE FROM `" . SYSLOG_TABLE . "` WHERE " . $purgeWhere);
			if ($ok) {
				$deleted = (int)mysql_affected_rows(); /* read before the audit insert below replaces it */
			} else {
				$purgeError = 'The entries could not be deleted (database error). Nothing was logged as deleted.';
			}
		}

		if ($purgeError === '') {
			/* written AFTER the delete, so this entry itself always survives */
			$text = syslog_who() . ' deleted ' . number_format($deleted) . ' activity log entr' . ($deleted == 1 ? 'y' : 'ies') . ' ' . $purgeTitle . '.';
			if ($deleted > 0) {
				$text .= ' The deleted entries were dated ' . $before['oldest'] . ' to ' . $before['newest'] . ' (log numbers ' . $before['first_id'] . ' to ' . $before['last_id'] . ').';
			} else {
				$text .= ' There was nothing to delete.';
			}
			syslog_write('System Log', 'ACTIVITY_LOG_PURGED', $text, array(
				'status' => $deleted > 0 ? 'success' : 'info',
				'entity_type' => 'activity log',
				'entity_label' => $purgeTitle,
				'details' => array_merge($purgeInfo, array(
					'deleted_count' => $deleted,
					'oldest_deleted' => $before ? $before['oldest'] : '',
					'newest_deleted' => $before ? $before['newest'] : '',
					'first_log_id' => $before ? $before['first_id'] : '',
					'last_log_id' => $before ? $before['last_id'] : ''
				))
			));
			$_SESSION['syslog_flash'] = $deleted > 0
				? number_format($deleted) . ' log entr' . ($deleted == 1 ? 'y' : 'ies') . ' ' . $purgeTitle . ' ' . ($deleted == 1 ? 'was' : 'were') . ' deleted. This action was recorded in the log.'
				: 'No log entries ' . $purgeTitle . ' were found, so nothing was deleted.';
			header('Location: systemlog.php');
			exit;
		}
	}
}

//----------------------------------------------------------------------------------------------------
// filters (GET so a filtered view can be bookmarked / shared)
//----------------------------------------------------------------------------------------------------
$filters = array(
	'date_from'  => '',
	'date_to'    => '',
	'actor_type' => '',
	'module'     => '',
	'action'     => '',
	'status'     => '',
	'q'          => '',
	'per_page'   => 50
);

if (isset($_GET['date_from']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['date_from'])) {
	$filters['date_from'] = $_GET['date_from'];
}
if (isset($_GET['date_to']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['date_to'])) {
	$filters['date_to'] = $_GET['date_to'];
}
$filters['actor_type'] = san_ident(isset($_GET['actor_type']) ? $_GET['actor_type'] : '', array('guest', 'user', 'admin', 'system'));
$filters['status']     = san_ident(isset($_GET['status']) ? $_GET['status'] : '', array('success', 'failed', 'info'));
$filters['module']     = isset($_GET['module']) && !is_array($_GET['module']) ? trim($_GET['module']) : '';
$filters['action']     = isset($_GET['action']) && !is_array($_GET['action']) ? trim($_GET['action']) : '';
$filters['q']          = isset($_GET['q']) && !is_array($_GET['q']) ? trim($_GET['q']) : '';
if (isset($_GET['per_page']) && in_array((int)$_GET['per_page'], array(25, 50, 100, 200), true)) {
	$filters['per_page'] = (int)$_GET['per_page'];
}

$where = array();
if ($filters['date_from'] !== '') {
	$where[] = "created_at >= '" . db_esc($filters['date_from']) . " 00:00:00'";
}
if ($filters['date_to'] !== '') {
	$where[] = "created_at <= '" . db_esc($filters['date_to']) . " 23:59:59'";
}
if ($filters['actor_type'] !== '') {
	$where[] = "actor_type = '" . db_esc($filters['actor_type']) . "'";
}
if ($filters['status'] !== '') {
	$where[] = "status = '" . db_esc($filters['status']) . "'";
}
if ($filters['module'] !== '') {
	$where[] = "module = '" . db_esc($filters['module']) . "'";
}
if ($filters['action'] !== '') {
	$where[] = "action = '" . db_esc($filters['action']) . "'";
}
if ($filters['q'] !== '') {
	$like = "'%" . db_esc(str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $filters['q'])) . "%'";
	$where[] = "(description LIKE $like OR actor_name LIKE $like OR actor_email LIKE $like OR entity_label LIKE $like OR entity_id LIKE $like OR ip_address LIKE $like OR request_url LIKE $like)";
}
$whereSql = app_count($where) > 0 ? ' WHERE ' . implode(' AND ', $where) : '';

//----------------------------------------------------------------------------------------------------
// CSV export (same filters, max 50 000 rows)
//----------------------------------------------------------------------------------------------------
if ($tableReady && isset($_GET['export']) && $_GET['export'] == 'csv') {
	$res = mysql_query("SELECT * FROM `" . SYSLOG_TABLE . "`" . $whereSql . " ORDER BY created_at DESC, log_id DESC LIMIT 50000");
	while (ob_get_level() > 0) {
		ob_end_clean();
	}
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename="activity_log_' . date('Ymd_His') . '.csv"');
	$out = fopen('php://output', 'w');
	fputs($out, "\xEF\xBB\xBF");
	fputcsv($out, array('Log ID', 'Date Time', 'Who (type)', 'Name', 'Email', 'Role', 'Module', 'Action', 'Result', 'Item Type', 'Item ID', 'Item Name', 'What happened', 'IP Address', 'Method', 'Page', 'Browser', 'Details'));
	if ($res) {
		while ($r = mysql_fetch_assoc($res)) {
			$line = array($r['log_id'], $r['created_at'], $r['actor_type'], $r['actor_name'], $r['actor_email'], $r['actor_role'], $r['module'], $r['action'], $r['status'], $r['entity_type'], $r['entity_id'], $r['entity_label'], $r['description'], $r['ip_address'], $r['request_method'], $r['request_url'], $r['user_agent'], $r['details']);
			foreach ($line as $i => $cell) {
				/* stop spreadsheet formula injection */
				if (is_string($cell) && $cell !== '' && strpos('=+-@', $cell[0]) !== false) {
					$line[$i] = "'" . $cell;
				}
			}
			fputcsv($out, $line);
		}
	}
	fclose($out);
	exit;
}

//----------------------------------------------------------------------------------------------------
// data for the page
//----------------------------------------------------------------------------------------------------
$totalRecs = 0;
$totalPages = 1;
$pageNo = 1;
$rows = array();
$statusCounts = array('success' => 0, 'failed' => 0, 'info' => 0);
$moduleList = array();
$actionList = array();

if ($tableReady) {
	$cnt = mysql_query("SELECT status, COUNT(*) AS c FROM `" . SYSLOG_TABLE . "`" . $whereSql . " GROUP BY status");
	if ($cnt) {
		while ($c = mysql_fetch_assoc($cnt)) {
			if (isset($statusCounts[$c['status']])) {
				$statusCounts[$c['status']] = (int)$c['c'];
			}
			$totalRecs += (int)$c['c'];
		}
	}
	$perPage = (int)$filters['per_page'];
	$totalPages = max(1, (int)ceil($totalRecs / $perPage));
	$pageNo = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
	if ($pageNo > $totalPages) {
		$pageNo = $totalPages;
	}
	$offset = ($pageNo - 1) * $perPage;

	$res = mysql_query("SELECT * FROM `" . SYSLOG_TABLE . "`" . $whereSql . " ORDER BY created_at DESC, log_id DESC LIMIT " . (int)$offset . ", " . $perPage);
	if ($res) {
		while ($r = mysql_fetch_assoc($res)) {
			$rows[] = $r;
		}
	}

	$m = mysql_query("SELECT DISTINCT module FROM `" . SYSLOG_TABLE . "` WHERE module <> '' ORDER BY module");
	if ($m) {
		while ($r = mysql_fetch_row($m)) {
			$moduleList[] = $r[0];
		}
	}
	$a = mysql_query("SELECT DISTINCT action FROM `" . SYSLOG_TABLE . "` WHERE action <> '' ORDER BY action");
	if ($a) {
		while ($r = mysql_fetch_row($a)) {
			$actionList[] = $r[0];
		}
	}
}

$pageLink = 'systemlog.php?' . syslog_view_query($filters);
$exportLink = 'systemlog.php?' . syslog_view_query($filters, array('export' => 'csv'));

include("html/systemlog.html");
?>
