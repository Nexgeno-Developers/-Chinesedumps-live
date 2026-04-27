<?php

function cms2_valid_admin_roles()
{
	return array('super_admin', 'admin', 'editor', 'support');
}

function cms2_normalize_admin_role($role)
{
	$role = strtolower(trim((string)$role));
	if (in_array($role, cms2_valid_admin_roles())) {
		return $role;
	}
	return 'admin';
}

function cms2_role_label($role)
{
	$labels = array(
		'super_admin' => 'Super Admin',
		'admin' => 'Admin',
		'editor' => 'Editor',
		'support' => 'Support'
	);
	$role = cms2_normalize_admin_role($role);
	return $labels[$role];
}

function current_admin_role()
{
	global $ccAdminData;

	if (isset($ccAdminData['admin_role']) && $ccAdminData['admin_role'] != '') {
		return cms2_normalize_admin_role($ccAdminData['admin_role']);
	}
	if (isset($_SESSION['adminRole']) && $_SESSION['adminRole'] != '') {
		return cms2_normalize_admin_role($_SESSION['adminRole']);
	}
	return 'admin';
}

function cms2_legacy_section_module($section)
{
	$map = array(
		'Dashboard' => 'Summary',
		'Settings' => 'Configuration',
		'Banners' => 'Banners Management',
		'Products Module' => 'Exams Management',
		'Users Module' => 'User Management',
		'Cart Mangement Modules' => 'Final Orders',
		'Cart Management Modules' => 'Final Orders',
		'Coupon Mangement Module' => 'Manage Coupons',
		'Coupon Management Module' => 'Manage Coupons',
		'Courses' => 'Manage Course',
		'Downloads' => 'Full Download Management',
		'Support' => 'View Exam Request Email',
		'Articles' => 'Edit Emails',
		'Contents Management' => 'Edit Emails',
		'Staff' => 'Admin User Management',
		'staff' => 'Admin User Management'
	);
	return isset($map[$section]) ? $map[$section] : $section;
}

function cms2_admin_panel_sections()
{
	return array(
		array('Global', array(
			array('Configuration', 'global.php', 'edit', 'images/News-Edit.gif'),
			array('Summary', 'sum.php', 'read', 'images/admin_summary16.gif'),
			array('Change Password', 'password.php', 'edit', 'images/admin_log16.gif'),
			array('Website Copyright', 'copyright.php', 'edit', 'images/News-Edit.gif'),
			array('Change Email Address', 'contactus.php', 'edit', 'images/Chart1-Edit.gif'),
			array('Logout', 'logout.php', 'read', 'images/admin_banning16.gif')
		)),
		array('Stats', array(
			array('All Registered Users', 'manageuser.php', 'read', 'images/News-Edit.gif'),
			array('Reports', 'backup_stats.php', 'read', 'images/admin_summary16.gif')
		)),
		array('Banners', array(
			array('Banners Management', 'bannermanage.php', 'read', 'images/admin_summary16.gif'),
			array('Add Banner', 'addslider.php', 'write', 'images/News-Add.gif')
		)),
		array('Exam Results', array(
			array('Exam Results Management', 'galley_management.php', 'read', 'images/admin_summary16.gif'),
			array('Add Exam Results Image', 'add_gallery.php', 'write', 'images/News-Edit.gif')
		)),
		array('Products Module', array(
			array('Add Vendors', 'addvendors.php', 'write', 'images/News-Add.gif'),
			array('Vendors Management', 'vendormanage.php', 'read', 'images/Comments-edit.gif'),
			array('Add Certification', 'addcertif.php', 'write', 'images/News-Add.gif'),
			array('Certifications Management', 'certifmanage.php', 'read', 'images/Comments-edit.gif'),
			array('Add Exam', 'addexam.php', 'write', 'images/News-Add.gif'),
			array('Exams Management', 'exammanage.php', 'read', 'images/Comments-edit.gif')
		)),
		array('Demo & Exams Management Modules', array(
			array('View Demo Emails', 'view_demoDownload.php', 'read', 'images/News-Edit.gif'),
			array('View Exam Request Email', 'exam_request.php', 'read', 'images/News-Edit.gif'),
			array('Full Download Management', 'managefull.php', 'read', 'images/News-Edit.gif')
		)),
		array('Cart Management Modules', array(
			array('Final Orders', 'masterorder.php', 'read', 'images/News-Edit.gif'),
			array('List Cart', 'odrproces.php', 'edit', 'images/News-Edit.gif'),
			array('Re-Order Cart', 'reodrproces.php', 'edit', 'images/News-Edit.gif')
		)),
		array('Coupon Management Module', array(
			array('Manage Coupons', 'managecoupon.php', 'read', 'images/News-Edit.gif'),
			array('Add new Coupon', 'addcoupon.php', 'write', 'images/News-Edit.gif')
		)),
		array('Stable/Unstable Course Management Module', array(
			array('Manage Course', 'courses_status_manage.php', 'read', 'images/News-Edit.gif'),
			array('Add new Course', 'course_add.php', 'write', 'images/News-Edit.gif')
		)),
		array('Global Content', array(
			array('Edit Emails', 'editemails.php', 'edit', 'images/News-Edit.gif'),
			array('Vendor Global Content', 'vendorgloble.php?pid=1', 'edit', 'images/News-Edit.gif'),
			array('Certification Global Content', 'vendorgloble.php?pid=2', 'edit', 'images/News-Edit.gif'),
			array('Exam Global Content', 'vendorgloble.php?pid=3', 'edit', 'images/News-Edit.gif')
		)),
		array('Users Module', array(
			array('Add User', 'adduser.php', 'write', 'images/News-Add.gif'),
			array('User Management', 'manageuser.php', 'read', 'images/News-Edit.gif')
		)),
		array('Admin Users', array(
			array('Admin User Management', 'manageadmin.php', 'read', 'images/News-Edit.gif'),
			array('Add Admin User', 'addadmin.php', 'write', 'images/News-Add.gif')
		))
	);
}

function cms2_required_legacy_modules()
{
	$modules = array();
	$sections = cms2_admin_panel_sections();
	foreach ($sections as $section) {
		foreach ($section[1] as $item) {
			$modules[] = $item[0];
		}
	}
	return $modules;
}

function cms2_actions_for_item($item)
{
	$readOnly = array('Summary', 'Logout', 'Reports', 'All Registered Users', 'View Demo Emails', 'View Exam Request Email', 'Full Download Management', 'Final Orders');
	$writeOnly = array('Add Banner', 'Add Exam Results Image', 'Add Vendors', 'Add Certification', 'Add Exam', 'Add new Coupon', 'Add new Course', 'Add User', 'Add Admin User');
	$editOnly = array('Configuration', 'Change Password', 'Website Copyright', 'Change Email Address', 'List Cart', 'Re-Order Cart', 'Edit Emails', 'Vendor Global Content', 'Certification Global Content', 'Exam Global Content');

	if (in_array($item, $readOnly)) {
		return array('read');
	}
	if (in_array($item, $writeOnly)) {
		return array('read', 'write');
	}
	if (in_array($item, $editOnly)) {
		return array('read', 'edit');
	}
	return array('read', 'edit', 'delete');
}

function cms2_permissions_for_items($items, $allowDelete)
{
	$permissions = array();
	foreach ($items as $item) {
		$actions = cms2_actions_for_item($item);
		if (!$allowDelete) {
			$filteredActions = array();
			foreach ($actions as $action) {
				if ($action != 'delete') {
					$filteredActions[] = $action;
				}
			}
			$actions = $filteredActions;
		}
		$permissions[strtolower($item)] = $actions;
	}
	return $permissions;
}

function cms2_role_permissions()
{
	$adminItems = array(
		'Configuration', 'Summary', 'Change Password', 'Website Copyright', 'Change Email Address', 'Logout',
		'All Registered Users', 'Reports', 'Banners Management', 'Add Banner', 'Exam Results Management',
		'Add Exam Results Image', 'Add Vendors', 'Vendors Management', 'Add Certification',
		'Certifications Management', 'Add Exam', 'Exams Management', 'View Demo Emails',
		'View Exam Request Email', 'Full Download Management', 'Final Orders', 'List Cart', 'Re-Order Cart',
		'Manage Coupons', 'Add new Coupon', 'Manage Course', 'Add new Course', 'Edit Emails',
		'Vendor Global Content', 'Certification Global Content', 'Exam Global Content', 'Add User',
		'User Management'
	);
	$editorItems = array(
		'Summary', 'Change Password', 'Logout', 'Banners Management', 'Add Banner',
		'Exam Results Management', 'Add Exam Results Image', 'Add Vendors', 'Vendors Management',
		'Add Certification', 'Certifications Management', 'Add Exam', 'Exams Management',
		'Manage Course', 'Add new Course', 'Edit Emails', 'Vendor Global Content',
		'Certification Global Content', 'Exam Global Content'
	);
	$supportItems = array(
		'Summary', 'Change Password', 'Logout', 'All Registered Users', 'Reports',
		'View Demo Emails', 'View Exam Request Email', 'Full Download Management',
		'Final Orders', 'List Cart', 'Re-Order Cart', 'Add User', 'User Management'
	);

	return array(
		'super_admin' => array('*' => array('read', 'write', 'edit', 'delete')),
		'admin' => cms2_permissions_for_items($adminItems, true),
		'editor' => cms2_permissions_for_items($editorItems, false),
		'support' => cms2_permissions_for_items($supportItems, false)
	);
}

function role_can($module, $action)
{
	global $ccAdminData;

	$role = current_admin_role();
	$module = cms2_legacy_section_module($module);
	$module = strtolower(trim((string)$module));
	$action = strtolower(trim((string)$action));
	$permissions = cms2_role_permissions();

	if (isset($permissions[$role]['*'])) {
		return in_array($action, $permissions[$role]['*']);
	}
	if (!isset($permissions[$role][$module])) {
		return false;
	}
	if (!in_array($action, $permissions[$role][$module])) {
		return false;
	}

	$bitMap = array('read' => 'Read', 'write' => 'Write', 'edit' => 'Edit', 'delete' => 'Delete');
	$permissionKey = isset($bitMap[$action]) ? $bitMap[$action] : '';
	if ($permissionKey != '' && is_array($ccAdminData)) {
		foreach ($ccAdminData as $sectionName => $sectionPermissions) {
			if (strtolower((string)$sectionName) == $module && is_array($sectionPermissions) && isset($sectionPermissions[$permissionKey])) {
				return ($sectionPermissions[$permissionKey] == true);
			}
		}
	}

	return false;
}

function cms2_role_legacy_permission_bits($role, $moduleName)
{
	$bits = array('Read' => 0, 'Write' => 0, 'Edit' => 0, 'Delete' => 0);
	if (role_can_for_role($role, $moduleName, 'read')) {
		$bits['Read'] = 1;
	}
	if (role_can_for_role($role, $moduleName, 'write')) {
		$bits['Write'] = 1;
	}
	if (role_can_for_role($role, $moduleName, 'edit')) {
		$bits['Edit'] = 1;
	}
	if (role_can_for_role($role, $moduleName, 'delete')) {
		$bits['Delete'] = 1;
	}
	return $bits;
}

function role_can_for_role($role, $module, $action)
{
	$role = cms2_normalize_admin_role($role);
	$module = cms2_legacy_section_module($module);
	$module = strtolower(trim((string)$module));
	$action = strtolower(trim((string)$action));
	$permissions = cms2_role_permissions();

	if (isset($permissions[$role]['*'])) {
		return in_array($action, $permissions[$role]['*']);
	}
	if (!isset($permissions[$role][$module])) {
		return false;
	}
	return in_array($action, $permissions[$role][$module]);
}

function cms2_ensure_legacy_modules_and_permissions()
{
	mysql_query("CREATE TABLE IF NOT EXISTS `tblmodules` (`ModuleID` int(11) NOT NULL AUTO_INCREMENT, `Name` varchar(255) NOT NULL, PRIMARY KEY (`ModuleID`), UNIQUE KEY `Name` (`Name`))");
	mysql_query("CREATE TABLE IF NOT EXISTS `tblpermissions` (`PermissionID` int(11) NOT NULL AUTO_INCREMENT, `AdminID` int(11) NOT NULL, `ModuleID` int(11) NOT NULL, `Read` tinyint(1) NOT NULL DEFAULT '0', `Write` tinyint(1) NOT NULL DEFAULT '0', `Edit` tinyint(1) NOT NULL DEFAULT '0', `Delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`PermissionID`), UNIQUE KEY `admin_module` (`AdminID`,`ModuleID`))");

	$modules = cms2_required_legacy_modules();
	foreach ($modules as $moduleName) {
		$moduleNameSql = mysql_real_escape_string($moduleName);
		$existingModule = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_count FROM `tblmodules` WHERE `Name`='".$moduleNameSql."'"));
		if (!$existingModule || (int)$existingModule['total_count'] == 0) {
			mysql_query("INSERT INTO `tblmodules` (`Name`) VALUES ('".$moduleNameSql."')");
		}
	}

	$adminResult = mysql_query("SELECT admin_id, admin_role FROM tbladmin");
	if (!$adminResult) {
		return;
	}

	$moduleResult = mysql_query("SELECT ModuleID, Name FROM tblmodules");
	if (!$moduleResult) {
		return;
	}

	$moduleRows = array();
	while ($moduleRow = mysql_fetch_array($moduleResult)) {
		$moduleRows[] = $moduleRow;
	}

	while ($adminRow = mysql_fetch_array($adminResult)) {
		$adminId = (int)$adminRow['admin_id'];
		$role = isset($adminRow['admin_role']) && $adminRow['admin_role'] != '' ? $adminRow['admin_role'] : (($adminId == 1) ? 'super_admin' : 'admin');
		foreach ($moduleRows as $moduleRow) {
			$bits = cms2_role_legacy_permission_bits($role, $moduleRow['Name']);
			$moduleId = (int)$moduleRow['ModuleID'];
			$existingPermission = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_count FROM `tblpermissions` WHERE `AdminID`=".$adminId." AND `ModuleID`=".$moduleId));
			if (!$existingPermission || (int)$existingPermission['total_count'] == 0) {
				mysql_query("INSERT INTO `tblpermissions` (`AdminID`,`ModuleID`,`Read`,`Write`,`Edit`,`Delete`) VALUES (".$adminId.",".$moduleId.",0,0,0,0)");
			}
		}
	}
}

function cms2_apply_default_permissions_for_admin($adminId, $role)
{
	$adminId = (int)$adminId;
	$role = cms2_normalize_admin_role($role);
	cms2_ensure_legacy_modules_and_permissions();

	$moduleResult = mysql_query("SELECT ModuleID, Name FROM tblmodules");
	if (!$moduleResult) {
		return;
	}

	while ($moduleRow = mysql_fetch_array($moduleResult)) {
		$moduleId = (int)$moduleRow['ModuleID'];
		$bits = cms2_role_legacy_permission_bits($role, $moduleRow['Name']);
		mysql_query("INSERT INTO `tblpermissions` (`AdminID`,`ModuleID`,`Read`,`Write`,`Edit`,`Delete`) VALUES (".$adminId.",".$moduleId.",".$bits['Read'].",".$bits['Write'].",".$bits['Edit'].",".$bits['Delete'].") ON DUPLICATE KEY UPDATE `Read`=".$bits['Read'].", `Write`=".$bits['Write'].", `Edit`=".$bits['Edit'].", `Delete`=".$bits['Delete']);
	}
}

function cms2_access_denied($ajax)
{
	if ($ajax) {
		header('HTTP/1.1 403 Forbidden');
		echo 'Access denied';
		exit;
	}
	header('Location: access_denied.php');
	exit;
}

function require_role_permission($module, $action, $ajax = false)
{
	if (!role_can($module, $action)) {
		cms2_access_denied($ajax);
	}
	return true;
}

function cms2_page_permissions()
{
	return array(
		'sum.php' => array('Summary', 'read'),
		'password.php' => array('Change Password', 'edit'),
		'global.php' => array('Configuration', 'edit'),
		'copyright.php' => array('Website Copyright', 'edit'),
		'contactus.php' => array('Change Email Address', 'edit'),
		'bootomcontact.php' => array('Configuration', 'edit'),
		'website.php' => array('Configuration', 'edit'),
		'mainimg.php' => array('Configuration', 'edit'),
		'menusetting.php' => array('Configuration', 'edit'),
		'menumodules.php' => array('Configuration', 'edit'),
		'manageadmin.php' => array('Admin User Management', 'read'),
		'admin.php' => array('Admin User Management', 'read'),
		'addadmin.php' => array('Add Admin User', 'write'),
		'editadmin.php' => array('Admin User Management', 'edit'),
		'permission.php' => array('Admin User Management', 'edit'),
		'addvendors.php' => array('Add Vendors', 'write'),
		'editvendor.php' => array('Vendors Management', 'edit'),
		'vendormanage.php' => array('Vendors Management', 'read'),
		'addcertif.php' => array('Add Certification', 'write'),
		'editcertif.php' => array('Certifications Management', 'edit'),
		'certifmanage.php' => array('Certifications Management', 'read'),
		'addexam.php' => array('Add Exam', 'write'),
		'editexam.php' => array('Exams Management', 'edit'),
		'exammanage.php' => array('Exams Management', 'read'),
		'bulk_update.php' => array('Exams Management', 'edit'),
		'bulk_update_date.php' => array('Exams Management', 'edit'),
		'updateDate.php' => array('Exams Management', 'edit'),
		'bannermanage.php' => array('Banners Management', 'read'),
		'bannermanage_new.php' => array('Banners Management', 'read'),
		'addslider.php' => array('Add Banner', 'write'),
		'editslider.php' => array('Banners Management', 'edit'),
		'editslider_new.php' => array('Banners Management', 'edit'),
		'add_gallery.php' => array('Add Exam Results Image', 'write'),
		'galley_management.php' => array('Exam Results Management', 'read'),
		'gallery_new.php' => array('Exam Results Management', 'read'),
		'editpagetext.php' => array('Edit Emails', 'edit'),
		'editemails.php' => array('Edit Emails', 'edit'),
		'vendorgloble.php' => array('Vendor Global Content', 'edit'),
		'editstylesheet.php' => array('Edit Emails', 'edit'),
		'courses_status_manage.php' => array('Manage Course', 'read'),
		'course_add.php' => array('Add new Course', 'write'),
		'course_edit.php' => array('Manage Course', 'edit'),
		'manageuser.php' => array('User Management', 'read'),
		'adduser.php' => array('Add User', 'write'),
		'edituser.php' => array('User Management', 'edit'),
		'download.php' => array('All Registered Users', 'read'),
		'userstats.php' => array('Reports', 'read'),
		'backup_stats.php' => array('Reports', 'read'),
		'stats.php' => array('Reports', 'read'),
		'masterorder.php' => array('Final Orders', 'read'),
		'ordermanage.php' => array('Final Orders', 'read'),
		'orderdetail.php' => array('Final Orders', 'read'),
		'editorder.php' => array('Final Orders', 'edit'),
		'odrproces.php' => array('List Cart', 'edit'),
		'reodrproces.php' => array('Re-Order Cart', 'edit'),
		'assign_user.php' => array('Final Orders', 'edit'),
		'managecoupon.php' => array('Manage Coupons', 'read'),
		'addcoupon.php' => array('Add new Coupon', 'write'),
		'editcoupon.php' => array('Manage Coupons', 'edit'),
		'view_demoDownload.php' => array('View Demo Emails', 'read'),
		'exam_request.php' => array('View Exam Request Email', 'read'),
		'managefull.php' => array('Full Download Management', 'read'),
		'managedemo.php' => array('Full Download Management', 'read'),
		'keys_action.php' => array('Full Download Management', 'edit'),
		'multi_keys_action.php' => array('Full Download Management', 'edit'),
		'manage_keys.php' => array('Full Download Management', 'read'),
		'addreport.php' => array('Reports', 'write'),
		'addsubscriber.php' => array('View Exam Request Email', 'write'),
		'editsubscriber.php' => array('View Exam Request Email', 'edit'),
		'mailmanage.php' => array('View Exam Request Email', 'read'),
		'changestatus.php' => array('Exams Management', 'edit'),
		'changestatusall.php' => array('Final Orders', 'edit'),
		'changestatusnew.php' => array('Exams Management', 'edit'),
		'changestatusnewnew.php' => array('Exams Management', 'edit'),
		'changemodule.php' => array('Configuration', 'edit'),
		'processsave.php' => array('List Cart', 'edit'),
		'processsavere.php' => array('Re-Order Cart', 'edit'),
		'getsubcategory.php' => array('Vendors Management', 'read')
	);
}

function can_view_admin_page($filename)
{
	$filename = basename((string)$filename);
	$map = cms2_page_permissions();
	if (!isset($map[$filename])) {
		return true;
	}
	return role_can($map[$filename][0], $map[$filename][1]);
}

function cms2_require_page_access($filename, $ajax)
{
	$filename = basename((string)$filename);
	$map = cms2_page_permissions();
	if (isset($map[$filename])) {
		require_role_permission($map[$filename][0], $map[$filename][1], $ajax);
		if ((isset($_GET['action']) && $_GET['action'] == 'up') || isset($_POST['btn_delete'])) {
			require_role_permission($map[$filename][0], 'delete', $ajax);
		}
	}
}

function permission($section, $permission, $halt = false)
{
	global $ccAdminData;

	if (role_can($section, $permission)) {
		return true;
	}

	$mappedModule = cms2_legacy_section_module($section);
	$knownModules = array();
	foreach (cms2_required_legacy_modules() as $moduleName) {
		$knownModules[] = strtolower($moduleName);
	}
	if ($mappedModule != $section || in_array(strtolower($mappedModule), $knownModules)) {
		if ($halt == true) {
			cms2_access_denied(false);
		}
		return false;
	}

	if (!isset($ccAdminData[$section][$permission])) {
		$ccAdminData[$section][$permission] = '';
	}

	$result = ($ccAdminData[$section][$permission] == true) ? true : false;

	if (!$result && $halt == true) {
		if (isset($GLOBALS['rootRel']) && isset($GLOBALS['glob']['adminFile'])) {
			httpredir($GLOBALS['rootRel'].$GLOBALS['glob']['adminFile']."?_g=401");
		}
		cms2_access_denied(false);
	}

	return $result;
}

function httpredir($target)
{
	header('Location: '.html_entity_decode(str_replace('amp;', '', $target)));
	exit;
}

function get_session_data()
{
	if (!isset($_SESSION['strAdmin']) || !isset($_SESSION['adminId'])) {
		header("location:index.php");
		exit;
	}

	$objDBcon = new classDbConnection();
	if (!isset($_SESSION['rbacPermissionsSynced']) || $_SESSION['rbacPermissionsSynced'] != 'menu-items-v1') {
		cms2_ensure_legacy_modules_and_permissions();
		$_SESSION['rbacPermissionsSynced'] = 'menu-items-v1';
	}
	$adminId = (int)$_SESSION['adminId'];
	$query = "SELECT * FROM tbladmin WHERE admin_id='".$adminId."' AND admin_status='Active'";
	$exeque = mysql_query($query);
	$row = mysql_fetch_array($exeque);

	if (!$row) {
		unset($_SESSION['adminId']);
		unset($_SESSION['strAdmin']);
		unset($_SESSION['adminRole']);
		header("location:index.php");
		exit;
	}

	if (!isset($row['admin_role']) || $row['admin_role'] == '') {
		$row['admin_role'] = ($adminId == 1) ? 'super_admin' : 'admin';
	}
	$row['admin_role'] = cms2_normalize_admin_role($row['admin_role']);
	$_SESSION['adminRole'] = $row['admin_role'];

	$query2 = mysql_query("SELECT tblmodules.ModuleID, Name, `Read`, `Write`, `Edit`, `Delete` FROM tblmodules LEFT JOIN tblpermissions ON tblmodules.ModuleID = tblpermissions.ModuleID WHERE AdminID ='".$adminId."'");
	if ($query2) {
		while ($row2 = mysql_fetch_array($query2)) {
			$masterKey = $row2['Name'];
			foreach ($row2 as $key => $value) {
				$row[$masterKey][$key] = $value;
			}
		}
	}

	return $row;
}
?>
