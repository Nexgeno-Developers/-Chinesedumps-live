<?php
if (!function_exists('role_can')) {
	include_once 'functions.php';
}

function cms2_menu_current_file()
{
	$path = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
	if($path == '' && isset($_SERVER['PHP_SELF']))
	{
		$path = $_SERVER['PHP_SELF'];
	}

	return basename(parse_url($path, PHP_URL_PATH));
}

function cms2_menu_current_query()
{
	$params = array();
	if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '')
	{
		parse_str($_SERVER['QUERY_STRING'], $params);
	}

	return $params;
}

function cms2_menu_active_target()
{
	$currentFile = cms2_menu_current_file();
	$currentQuery = cms2_menu_current_query();
	$map = array(
		'bootomcontact.php' => array('url' => 'global.php', 'label' => 'Configuration'),
		'website.php' => array('url' => 'global.php', 'label' => 'Configuration'),
		'mainimg.php' => array('url' => 'global.php', 'label' => 'Configuration'),
		'menusetting.php' => array('url' => 'global.php', 'label' => 'Configuration'),
		'menumodules.php' => array('url' => 'global.php', 'label' => 'Configuration'),
		'stats.php' => array('url' => 'backup_stats.php', 'label' => 'Reports'),
		'userstats.php' => array('url' => 'backup_stats.php', 'label' => 'Reports'),
		'download.php' => array('url' => 'backup_stats.php', 'label' => 'Reports'),
		'manageuser.php' => array('url' => 'manageuser.php', 'label' => 'User Management'),
		'edituser.php' => array('url' => 'manageuser.php', 'label' => 'User Management'),
		'editadmin.php' => array('url' => 'manageadmin.php', 'label' => 'Admin User Management'),
		'permission.php' => array('url' => 'manageadmin.php', 'label' => 'Admin User Management'),
		'editvendor.php' => array('url' => 'vendormanage.php', 'label' => 'Vendors Management'),
		'editcertif.php' => array('url' => 'certifmanage.php', 'label' => 'Certifications Management'),
		'editexam.php' => array('url' => 'exammanage.php', 'label' => 'Exams Management'),
		'bulk_update.php' => array('url' => 'exammanage.php', 'label' => 'Exams Management'),
		'bulk_update_date.php' => array('url' => 'exammanage.php', 'label' => 'Exams Management'),
		'updateDate.php' => array('url' => 'exammanage.php', 'label' => 'Exams Management'),
		'assign_user.php' => array('url' => 'exammanage.php', 'label' => 'Exams Management'),
		'editslider.php' => array('url' => 'bannermanage.php', 'label' => 'Banners Management'),
		'editslider_new.php' => array('url' => 'bannermanage.php', 'label' => 'Banners Management'),
		'gallery_new.php' => array('url' => 'galley_management.php', 'label' => 'Exam Results Management'),
		'orderdetail.php' => array('url' => 'masterorder.php', 'label' => 'Final Orders'),
		'editorder.php' => array('url' => 'masterorder.php', 'label' => 'Final Orders'),
		'processsave.php' => array('url' => 'odrproces.php', 'label' => 'List Cart'),
		'processsavere.php' => array('url' => 'reodrproces.php', 'label' => 'Re-Order Cart'),
		'editcoupon.php' => array('url' => 'managecoupon.php', 'label' => 'Manage Coupons'),
		'course_edit.php' => array('url' => 'courses_status_manage.php', 'label' => 'Manage Course')
	);

	if(isset($map[$currentFile]))
	{
		return $map[$currentFile];
	}

	return array('url' => $currentFile, 'query' => $currentQuery);
}

function cms2_menu_item_matches_active_target($item, $target)
{
	$itemLabel = isset($item[0]) ? (string)$item[0] : '';
	$itemUrl = isset($item[1]) ? (string)$item[1] : '';
	$targetUrl = isset($target['url']) ? (string)$target['url'] : '';

	if(isset($target['label']) && $target['label'] != $itemLabel)
	{
		return false;
	}

	$itemParts = parse_url($itemUrl);
	$targetParts = parse_url($targetUrl);
	$itemPath = isset($itemParts['path']) ? basename($itemParts['path']) : '';
	$targetPath = isset($targetParts['path']) ? basename($targetParts['path']) : '';

	if($itemPath == '' || $targetPath == '' || $itemPath != $targetPath)
	{
		return false;
	}

	$itemQuery = array();
	if(isset($itemParts['query']) && $itemParts['query'] != '')
	{
		parse_str($itemParts['query'], $itemQuery);
	}

	$targetQuery = array();
	if(isset($targetParts['query']) && $targetParts['query'] != '')
	{
		parse_str($targetParts['query'], $targetQuery);
	}
	elseif(isset($target['query']) && is_array($target['query']))
	{
		$targetQuery = $target['query'];
	}

	foreach($itemQuery as $key => $value)
	{
		if(!isset($targetQuery[$key]) || (string)$targetQuery[$key] !== (string)$value)
		{
			return false;
		}
	}

	return true;
}

function cms2_render_menu_section($title, $items, $first)
{
	$visible = array();
	$activeTarget = cms2_menu_active_target();
	foreach ($items as $item) {
		$module = isset($item[2]) ? trim((string)$item[2]) : '';
		$action = isset($item[3]) ? trim((string)$item[3]) : '';
		if (($module == '' && $action == '') || role_can($module, $action)) {
			$visible[] = $item;
		}
	}
	if (count($visible) == 0) {
		return;
	}
	$class = $first ? 'menu' : 'menu2';
	echo '<table width="250" cellPadding="0" cellSpacing="0" class="'.$class.'"><tbody>';
	echo '<tr><td width="198" class="menu_header">'.$title.'</td></tr>';
	echo '<tr><td><img src="images/admin_menu_bar.gif" border="0" /></td></tr>';
	foreach ($visible as $item) {
		$linkClass = cms2_menu_item_matches_active_target($item, $activeTarget) ? 'menu active' : 'menu';
		echo '<tr><td class="menu"><a class="'.$linkClass.'" href="'.$item[1].'"><img src="'.$item[4].'" width="16" height="16" border="0" class="icon2" />'.$item[0].'</a></td></tr>';
	}
	echo '</tbody></table>';
}

$cms2MenuSections = cms2_admin_panel_sections();
for ($cms2MenuIndex = 0; $cms2MenuIndex < count($cms2MenuSections); $cms2MenuIndex++) {
	cms2_render_menu_section($cms2MenuSections[$cms2MenuIndex][0], $cms2MenuSections[$cms2MenuIndex][1], $cms2MenuIndex == 0);
}
?>
