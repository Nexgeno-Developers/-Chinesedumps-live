<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>


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

function cms2_menu_icon_class($label, $url = '')
{
	$label = trim((string)$label);
	$url = (string)$url;

	$map = array(
		'Configuration' => 'fa-solid fa-gear',
		'Summary' => 'fa-solid fa-chart-line',
		'Change Password' => 'fa-solid fa-key',
		'Website Copyright' => 'fa-regular fa-copyright',
		'Change Email Address' => 'fa-solid fa-envelope',
		'Logout' => 'fa-solid fa-right-from-bracket',

		'All Registered Users' => 'fa-solid fa-users',
		'Reports' => 'fa-solid fa-file-lines',

		'Banners Management' => 'fa-solid fa-images',
		'Add Banner' => 'fa-solid fa-plus',

		'Exam Results Management' => 'fa-solid fa-image',
		'Add Exam Results Image' => 'fa-solid fa-plus',

		'Add Vendors' => 'fa-solid fa-store',
		'Vendors Management' => 'fa-solid fa-store',
		'Add Certification' => 'fa-solid fa-certificate',
		'Certifications Management' => 'fa-solid fa-certificate',
		'Add Exam' => 'fa-solid fa-plus',
		'Exams Management' => 'fa-solid fa-graduation-cap',

		'View Demo Emails' => 'fa-solid fa-envelope-open-text',
		'View Exam Request Email' => 'fa-solid fa-inbox',
		'Full Download Management' => 'fa-solid fa-download',

		'Final Orders' => 'fa-solid fa-cart-shopping',
		'List Cart' => 'fa-solid fa-list',
		'Re-Order Cart' => 'fa-solid fa-rotate-right',

		'Manage Coupons' => 'fa-solid fa-ticket',
		'Add new Coupon' => 'fa-solid fa-plus',

		'Manage Course' => 'fa-solid fa-book',
		'Add new Course' => 'fa-solid fa-plus',

		'Edit Emails' => 'fa-solid fa-pen-to-square',
		'Vendor Global Content' => 'fa-solid fa-globe',
		'Certification Global Content' => 'fa-solid fa-globe',
		'Exam Global Content' => 'fa-solid fa-globe',

		'Add User' => 'fa-solid fa-user-plus',
		'User Management' => 'fa-solid fa-users-gear',

		'Admin User Management' => 'fa-solid fa-user-shield',
		'Add Admin User' => 'fa-solid fa-user-plus'
	);

	if (isset($map[$label])) {
		return $map[$label];
	}

	// Light fallback based on URL keywords.
	$lowerUrl = strtolower($url);
	if (strpos($lowerUrl, 'manage') !== false) {
		return 'fa-solid fa-list';
	}
	if (strpos($lowerUrl, 'add') !== false) {
		return 'fa-solid fa-plus';
	}
	if (strpos($lowerUrl, 'edit') !== false) {
		return 'fa-solid fa-pen-to-square';
	}
	return 'fa-solid fa-circle';
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
		$iconClass = cms2_menu_icon_class(isset($item[0]) ? $item[0] : '', isset($item[1]) ? $item[1] : '');
		echo '<tr><td class="menu"><a class="'.$linkClass.'" href="'.$item[1].'"><i class="'.$iconClass.'" aria-hidden="true"></i>'.$item[0].'</a></td></tr>';
	}
	echo '</tbody></table>';
}

$cms2MenuSections = cms2_admin_panel_sections();
for ($cms2MenuIndex = 0; $cms2MenuIndex < count($cms2MenuSections); $cms2MenuIndex++) {
	cms2_render_menu_section($cms2MenuSections[$cms2MenuIndex][0], $cms2MenuSections[$cms2MenuIndex][1], $cms2MenuIndex == 0);
}
?>
