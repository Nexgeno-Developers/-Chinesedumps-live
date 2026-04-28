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

function cms2_feature_modules()
{
	return array(
		'Configuration',
		'Summary',
		'Change Password',
		'Website Copyright',
		'Change Email Address',
		'Report',
		'Banners Module',
		'Exam Results Module',
		'Vendors Module',
		'Certifications Module',
		'Exams Module',
		'Demo Exams Module',
		'Orders Module',
		'Coupons Module',
		'Courses Module',
		'Global Content Module',
		'Users Module',
		'Admin Users Module'
	);
}

function cms2_module_alias_map()
{
	return array(
		'Dashboard' => 'Summary',
		'Settings' => 'Configuration',
		'Stats' => 'Report',
		'Reports' => 'Report',
		'Banners' => 'Banners Module',
		'Banners Management' => 'Banners Module',
		'Add Banner' => 'Banners Module',
		'Exam Results Management' => 'Exam Results Module',
		'Add Exam Results Image' => 'Exam Results Module',
		'Products Module' => 'Exams Module',
		'Add Vendors' => 'Vendors Module',
		'Vendors Management' => 'Vendors Module',
		'Add Certification' => 'Certifications Module',
		'Certifications Management' => 'Certifications Module',
		'Add Exam' => 'Exams Module',
		'Exams Management' => 'Exams Module',
		'Downloads' => 'Demo Exams Module',
		'Support' => 'Demo Exams Module',
		'View Demo Emails' => 'Demo Exams Module',
		'View Exam Request Email' => 'Demo Exams Module',
		'Full Download Management' => 'Demo Exams Module',
		'Cart Mangement Modules' => 'Orders Module',
		'Cart Management Modules' => 'Orders Module',
		'Final Orders' => 'Orders Module',
		'List Cart' => 'Orders Module',
		'Re-Order Cart' => 'Orders Module',
		'Coupon Mangement Module' => 'Coupons Module',
		'Coupon Management Module' => 'Coupons Module',
		'Manage Coupons' => 'Coupons Module',
		'Add new Coupon' => 'Coupons Module',
		'Courses' => 'Courses Module',
		'Manage Course' => 'Courses Module',
		'Add new Course' => 'Courses Module',
		'Articles' => 'Global Content Module',
		'Contents Management' => 'Global Content Module',
		'Edit Emails' => 'Global Content Module',
		'Vendor Global Content' => 'Global Content Module',
		'Certification Global Content' => 'Global Content Module',
		'Exam Global Content' => 'Global Content Module',
		'Add User' => 'Users Module',
		'User Management' => 'Users Module',
		'Staff' => 'Admin Users Module',
		'staff' => 'Admin Users Module',
		'Admin User Management' => 'Admin Users Module',
		'Add Admin User' => 'Admin Users Module'
	);
}

function cms2_legacy_section_module($section)
{
	$map = cms2_module_alias_map();
	return isset($map[$section]) ? $map[$section] : $section;
}

function cms2_admin_menu_item($label, $url, $module, $action, $icon)
{
	return array($label, $url, $module, $action, $icon);
}

function cms2_admin_panel_sections()
{
	return array(
		array('Global', array(
			cms2_admin_menu_item('Configuration', 'global.php', 'Configuration', 'read', 'images/News-Edit.gif'),
			cms2_admin_menu_item('Summary', 'sum.php', 'Summary', 'read', 'images/admin_summary16.gif'),
			cms2_admin_menu_item('Change Password', 'password.php', 'Change Password', 'read', 'images/admin_log16.gif'),
			cms2_admin_menu_item('Website Copyright', 'copyright.php', 'Website Copyright', 'read', 'images/News-Edit.gif'),
			cms2_admin_menu_item('Change Email Address', 'contactus.php', 'Change Email Address', 'read', 'images/Chart1-Edit.gif'),
			cms2_admin_menu_item('Logout', 'logout.php', '', '', 'images/admin_banning16.gif')
		)),
		array('Report', array(
			cms2_admin_menu_item('All Registered Users', 'manageuser.php', 'Users Module', 'read', 'images/News-Edit.gif'),
			cms2_admin_menu_item('Reports', 'backup_stats.php', 'Report', 'read', 'images/admin_summary16.gif')
		)),
		array('Banners', array(
			cms2_admin_menu_item('Banners Management', 'bannermanage.php', 'Banners Module', 'read', 'images/admin_summary16.gif'),
			cms2_admin_menu_item('Add Banner', 'addslider.php', 'Banners Module', 'write', 'images/News-Add.gif')
		)),
		array('Exam Results', array(
			cms2_admin_menu_item('Exam Results Management', 'galley_management.php', 'Exam Results Module', 'read', 'images/admin_summary16.gif'),
			cms2_admin_menu_item('Add Exam Results Image', 'add_gallery.php', 'Exam Results Module', 'write', 'images/News-Edit.gif')
		)),
		array('Products Module', array(
			cms2_admin_menu_item('Add Vendors', 'addvendors.php', 'Vendors Module', 'write', 'images/News-Add.gif'),
			cms2_admin_menu_item('Vendors Management', 'vendormanage.php', 'Vendors Module', 'read', 'images/Comments-edit.gif'),
			cms2_admin_menu_item('Add Certification', 'addcertif.php', 'Certifications Module', 'write', 'images/News-Add.gif'),
			cms2_admin_menu_item('Certifications Management', 'certifmanage.php', 'Certifications Module', 'read', 'images/Comments-edit.gif'),
			cms2_admin_menu_item('Add Exam', 'addexam.php', 'Exams Module', 'write', 'images/News-Add.gif'),
			cms2_admin_menu_item('Exams Management', 'exammanage.php', 'Exams Module', 'read', 'images/Comments-edit.gif')
		)),
		array('Demo & Exams Management Modules', array(
			cms2_admin_menu_item('View Demo Emails', 'view_demoDownload.php', 'Demo Exams Module', 'read', 'images/News-Edit.gif'),
			cms2_admin_menu_item('View Exam Request Email', 'exam_request.php', 'Demo Exams Module', 'read', 'images/News-Edit.gif'),
			cms2_admin_menu_item('Full Download Management', 'managefull.php', 'Demo Exams Module', 'read', 'images/News-Edit.gif')
		)),
		array('Cart Management Modules', array(
			cms2_admin_menu_item('Final Orders', 'masterorder.php', 'Orders Module', 'read', 'images/News-Edit.gif'),
			cms2_admin_menu_item('List Cart', 'odrproces.php', 'Orders Module', 'edit', 'images/News-Edit.gif'),
			cms2_admin_menu_item('Re-Order Cart', 'reodrproces.php', 'Orders Module', 'edit', 'images/News-Edit.gif')
		)),
		array('Coupon Management Module', array(
			cms2_admin_menu_item('Manage Coupons', 'managecoupon.php', 'Coupons Module', 'read', 'images/News-Edit.gif'),
			cms2_admin_menu_item('Add new Coupon', 'addcoupon.php', 'Coupons Module', 'write', 'images/News-Edit.gif')
		)),
		array('Stable/Unstable Course Management Module', array(
			cms2_admin_menu_item('Manage Course', 'courses_status_manage.php', 'Courses Module', 'read', 'images/News-Edit.gif'),
			cms2_admin_menu_item('Add new Course', 'course_add.php', 'Courses Module', 'write', 'images/News-Edit.gif')
		)),
		array('Global Content', array(
			cms2_admin_menu_item('Edit Emails', 'editemails.php', 'Global Content Module', 'edit', 'images/News-Edit.gif'),
			cms2_admin_menu_item('Vendor Global Content', 'vendorgloble.php?pid=1', 'Global Content Module', 'edit', 'images/News-Edit.gif'),
			cms2_admin_menu_item('Certification Global Content', 'vendorgloble.php?pid=2', 'Global Content Module', 'edit', 'images/News-Edit.gif'),
			cms2_admin_menu_item('Exam Global Content', 'vendorgloble.php?pid=3', 'Global Content Module', 'edit', 'images/News-Edit.gif')
		)),
		array('Users Module', array(
			cms2_admin_menu_item('Add User', 'adduser.php', 'Users Module', 'write', 'images/News-Add.gif'),
			cms2_admin_menu_item('User Management', 'manageuser.php', 'Users Module', 'read', 'images/News-Edit.gif')
		)),
		array('Admin Users', array(
			cms2_admin_menu_item('Admin User Management', 'manageadmin.php', 'Admin Users Module', 'read', 'images/News-Edit.gif'),
			cms2_admin_menu_item('Add Admin User', 'addadmin.php', 'Admin Users Module', 'write', 'images/News-Add.gif')
		))
	);
}

function cms2_required_legacy_modules()
{
	return cms2_feature_modules();
}

function cms2_permission_bits($actions)
{
	$bits = array('read' => 0, 'write' => 0, 'edit' => 0, 'delete' => 0);
	foreach ($actions as $action) {
		$action = strtolower($action);
		if (isset($bits[$action])) {
			$bits[$action] = 1;
		}
	}
	return $bits;
}

function cms2_role_permissions()
{
	return array(
		'super_admin' => array('*' => array('read', 'write', 'edit', 'delete')),
		'admin' => array(
			'configuration' => array('read', 'edit'),
			'summary' => array('read'),
			'change password' => array('read', 'edit'),
			'website copyright' => array('read', 'edit'),
			'change email address' => array('read', 'edit'),
			'report' => array('read', 'write'),
			'banners module' => array('read', 'write', 'edit', 'delete'),
			'exam results module' => array('read', 'write', 'edit', 'delete'),
			'vendors module' => array('read', 'write', 'edit', 'delete'),
			'certifications module' => array('read', 'write', 'edit', 'delete'),
			'exams module' => array('read', 'write', 'edit', 'delete'),
			'demo exams module' => array('read', 'write', 'edit', 'delete'),
			'orders module' => array('read', 'edit', 'delete'),
			'coupons module' => array('read', 'write', 'edit', 'delete'),
			'courses module' => array('read', 'write', 'edit', 'delete'),
			'global content module' => array('read', 'edit'),
			'users module' => array('read', 'write', 'edit', 'delete')
		),
		'editor' => array(
			'summary' => array('read'),
			'change password' => array('read', 'edit'),
			'banners module' => array('read', 'write', 'edit'),
			'exam results module' => array('read', 'write', 'edit'),
			'vendors module' => array('read', 'write', 'edit'),
			'certifications module' => array('read', 'write', 'edit'),
			'exams module' => array('read', 'write', 'edit'),
			'courses module' => array('read', 'write', 'edit'),
			'global content module' => array('read', 'edit')
		),
		'support' => array(
			'summary' => array('read'),
			'change password' => array('read', 'edit'),
			'report' => array('read'),
			'demo exams module' => array('read'),
			'orders module' => array('read', 'edit'),
			'users module' => array('read', 'write', 'edit')
		)
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
	$bitMap = array('read' => 'Read', 'write' => 'Write', 'edit' => 'Edit', 'delete' => 'Delete');
	$permissionKey = isset($bitMap[$action]) ? $bitMap[$action] : '';

	if ($permissionKey != '' && is_array($ccAdminData)) {
		foreach ($ccAdminData as $sectionName => $sectionPermissions) {
			if (strtolower((string)$sectionName) == $module && is_array($sectionPermissions) && isset($sectionPermissions[$permissionKey])) {
				return ($sectionPermissions[$permissionKey] == true);
			}
		}
	}

	if (isset($permissions[$role]['*'])) {
		return in_array($action, $permissions[$role]['*']);
	}
	if (!isset($permissions[$role][$module])) {
		return false;
	}
	if (!in_array($action, $permissions[$role][$module])) {
		return false;
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

function cms2_old_module_groups()
{
	return array(
		'Users Module' => array('User Management', 'Add User', 'All Registered Users'),
		'Courses Module' => array('Manage Course', 'Add new Course'),
		'Certifications Module' => array('Certifications Management', 'Add Certification'),
		'Vendors Module' => array('Vendors Management', 'Add Vendors'),
		'Exams Module' => array('Exams Management', 'Add Exam'),
		'Banners Module' => array('Banners Management', 'Add Banner'),
		'Exam Results Module' => array('Exam Results Management', 'Add Exam Results Image'),
		'Coupons Module' => array('Manage Coupons', 'Add new Coupon'),
		'Orders Module' => array('Final Orders', 'List Cart', 'Re-Order Cart'),
		'Demo Exams Module' => array('View Demo Emails', 'View Exam Request Email', 'Full Download Management'),
		'Global Content Module' => array('Edit Emails', 'Vendor Global Content', 'Certification Global Content', 'Exam Global Content'),
		'Configuration' => array('Configuration', 'Global Module'),
		'Summary' => array('Summary', 'Global Module'),
		'Change Password' => array('Change Password', 'Global Module'),
		'Website Copyright' => array('Website Copyright', 'Global Module'),
		'Change Email Address' => array('Change Email Address', 'Global Module'),
		'Report' => array('Reports', 'Stats Module'),
		'Admin Users Module' => array('Admin User Management', 'Add Admin User')
	);
}

function cms2_merge_old_permission_rows($adminId, $newModule, $oldRows, $role)
{
	$groups = cms2_old_module_groups();
	$oldNames = isset($groups[$newModule]) ? $groups[$newModule] : array();
	$hasLegacyRows = false;
	foreach ($oldNames as $oldName) {
		if (isset($oldRows[$oldName])) {
			$hasLegacyRows = true;
			break;
		}
	}
	if (isset($oldRows[$newModule])) {
		$bits = array(
			'Read' => (int)$oldRows[$newModule]['Read'],
			'Write' => (int)$oldRows[$newModule]['Write'],
			'Edit' => (int)$oldRows[$newModule]['Edit'],
			'Delete' => (int)$oldRows[$newModule]['Delete']
		);
	} else {
		$defaultBits = $hasLegacyRows ? array('Read' => 0, 'Write' => 0, 'Edit' => 0, 'Delete' => 0) : cms2_role_legacy_permission_bits($role, $newModule);
		$bits = array(
			'Read' => (int)$defaultBits['Read'],
			'Write' => (int)$defaultBits['Write'],
			'Edit' => (int)$defaultBits['Edit'],
			'Delete' => (int)$defaultBits['Delete']
		);
	}
	foreach ($oldNames as $oldName) {
		if (!isset($oldRows[$oldName])) {
			continue;
		}
		$row = $oldRows[$oldName];
		if ($row['Read'] == 1 || $row['Edit'] == 1 || $row['Delete'] == 1) {
			$bits['Read'] = 1;
		}
		if ($row['Write'] == 1) {
			$bits['Write'] = 1;
		}
		if ($row['Edit'] == 1) {
			$bits['Edit'] = 1;
		}
		if ($row['Delete'] == 1) {
			$bits['Delete'] = 1;
		}
	}
	return $bits;
}

function cms2_upsert_permission_bits($adminId, $moduleId, $bits)
{
	$adminId = (int)$adminId;
	$moduleId = (int)$moduleId;
	$read = (int)$bits['Read'];
	$write = (int)$bits['Write'];
	$edit = (int)$bits['Edit'];
	$delete = (int)$bits['Delete'];

	$permissionIdResult = mysql_query("SHOW COLUMNS FROM tblpermissions LIKE 'PermissionID'");
	$hasPermissionId = ($permissionIdResult && mysql_num_rows($permissionIdResult) > 0);

	if ($hasPermissionId) {
		$existingResult = mysql_query("SELECT PermissionID FROM tblpermissions WHERE AdminID=".$adminId." AND ModuleID=".$moduleId." ORDER BY PermissionID ASC LIMIT 1");
		$existing = $existingResult ? mysql_fetch_array($existingResult) : false;
		if ($existing && isset($existing['PermissionID'])) {
			mysql_query("UPDATE tblpermissions SET `Read`=".$read.", `Write`=".$write.", `Edit`=".$edit.", `Delete`=".$delete." WHERE PermissionID=".(int)$existing['PermissionID']);
			mysql_query("DELETE FROM tblpermissions WHERE AdminID=".$adminId." AND ModuleID=".$moduleId." AND PermissionID<>".(int)$existing['PermissionID']);
			return;
		}
		mysql_query("INSERT INTO `tblpermissions` (`AdminID`,`ModuleID`,`Read`,`Write`,`Edit`,`Delete`) VALUES (".$adminId.",".$moduleId.",".$read.",".$write.",".$edit.",".$delete.")");
		return;
	}

	mysql_query("DELETE FROM tblpermissions WHERE AdminID=".$adminId." AND ModuleID=".$moduleId);
	mysql_query("INSERT INTO `tblpermissions` (`AdminID`,`ModuleID`,`Read`,`Write`,`Edit`,`Delete`) VALUES (".$adminId.",".$moduleId.",".$read.",".$write.",".$edit.",".$delete.")");
}

function cms2_cleanup_duplicate_feature_modules_and_permissions()
{
	$modules = cms2_feature_modules();
	foreach ($modules as $moduleName) {
		$moduleNameSql = mysql_real_escape_string($moduleName);
		$moduleResult = mysql_query("SELECT ModuleID FROM tblmodules WHERE Name='".$moduleNameSql."' ORDER BY ModuleID ASC");
		if (!$moduleResult) {
			continue;
		}

		$canonicalModuleId = 0;
		$duplicateModuleIds = array();
		while ($moduleRow = mysql_fetch_array($moduleResult)) {
			$moduleId = (int)$moduleRow['ModuleID'];
			if ($canonicalModuleId == 0) {
				$canonicalModuleId = $moduleId;
			} else {
				$duplicateModuleIds[] = $moduleId;
			}
		}
		if ($canonicalModuleId == 0) {
			continue;
		}

		foreach ($duplicateModuleIds as $duplicateModuleId) {
			$permissionResult = mysql_query("SELECT AdminID, MAX(`Read`) AS R, MAX(`Write`) AS W, MAX(`Edit`) AS E, MAX(`Delete`) AS D FROM tblpermissions WHERE ModuleID=".$duplicateModuleId." GROUP BY AdminID");
			if ($permissionResult) {
				while ($permissionRow = mysql_fetch_array($permissionResult)) {
					$adminId = (int)$permissionRow['AdminID'];
					$existingResult = mysql_query("SELECT MAX(`Read`) AS R, MAX(`Write`) AS W, MAX(`Edit`) AS E, MAX(`Delete`) AS D FROM tblpermissions WHERE AdminID=".$adminId." AND ModuleID=".$canonicalModuleId);
					$existing = $existingResult ? mysql_fetch_array($existingResult) : array('R' => 0, 'W' => 0, 'E' => 0, 'D' => 0);
					$bits = array(
						'Read' => max((int)$permissionRow['R'], (int)$existing['R']),
						'Write' => max((int)$permissionRow['W'], (int)$existing['W']),
						'Edit' => max((int)$permissionRow['E'], (int)$existing['E']),
						'Delete' => max((int)$permissionRow['D'], (int)$existing['D'])
					);
					cms2_upsert_permission_bits($adminId, $canonicalModuleId, $bits);
				}
			}
			mysql_query("DELETE FROM tblpermissions WHERE ModuleID=".$duplicateModuleId);
			mysql_query("DELETE FROM tblmodules WHERE ModuleID=".$duplicateModuleId);
		}

		$permissionIdResult = mysql_query("SHOW COLUMNS FROM tblpermissions LIKE 'PermissionID'");
		$hasPermissionId = ($permissionIdResult && mysql_num_rows($permissionIdResult) > 0);
		$adminResult = mysql_query("SELECT AdminID, MAX(`Read`) AS R, MAX(`Write`) AS W, MAX(`Edit`) AS E, MAX(`Delete`) AS D".($hasPermissionId ? ", MIN(PermissionID) AS KeepID" : "")." FROM tblpermissions WHERE ModuleID=".$canonicalModuleId." GROUP BY AdminID HAVING COUNT(*) > 1");
		if ($adminResult) {
			while ($adminRow = mysql_fetch_array($adminResult)) {
				if ($hasPermissionId) {
					$keepId = (int)$adminRow['KeepID'];
					mysql_query("UPDATE tblpermissions SET `Read`=".(int)$adminRow['R'].", `Write`=".(int)$adminRow['W'].", `Edit`=".(int)$adminRow['E'].", `Delete`=".(int)$adminRow['D']." WHERE PermissionID=".$keepId);
					mysql_query("DELETE FROM tblpermissions WHERE AdminID=".(int)$adminRow['AdminID']." AND ModuleID=".$canonicalModuleId." AND PermissionID<>".$keepId);
				} else {
					cms2_upsert_permission_bits((int)$adminRow['AdminID'], $canonicalModuleId, array('Read' => (int)$adminRow['R'], 'Write' => (int)$adminRow['W'], 'Edit' => (int)$adminRow['E'], 'Delete' => (int)$adminRow['D']));
				}
			}
		}
	}
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

	$modules = cms2_feature_modules();
	foreach ($modules as $moduleName) {
		$moduleNameSql = mysql_real_escape_string($moduleName);
		$existingModule = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_count FROM `tblmodules` WHERE `Name`='".$moduleNameSql."'"));
		if (!$existingModule || (int)$existingModule['total_count'] == 0) {
			mysql_query("INSERT INTO `tblmodules` (`Name`) VALUES ('".$moduleNameSql."')");
		}
	}
	cms2_cleanup_duplicate_feature_modules_and_permissions();

	$adminResult = mysql_query("SELECT admin_id, admin_role FROM tbladmin");
	if (!$adminResult) {
		return;
	}

	$moduleResult = mysql_query("SELECT ModuleID, Name FROM tblmodules");
	if (!$moduleResult) {
		return;
	}

	$moduleRows = array();
	$moduleIdsByName = array();
	$featureNames = array();
	foreach ($modules as $moduleName) {
		$featureNames[strtolower($moduleName)] = $moduleName;
	}
	while ($moduleRow = mysql_fetch_array($moduleResult)) {
		$moduleRows[] = $moduleRow;
		$moduleIdsByName[$moduleRow['Name']] = (int)$moduleRow['ModuleID'];
	}

	while ($adminRow = mysql_fetch_array($adminResult)) {
		$adminId = (int)$adminRow['admin_id'];
		$role = isset($adminRow['admin_role']) && $adminRow['admin_role'] != '' ? $adminRow['admin_role'] : (($adminId == 1) ? 'super_admin' : 'admin');
		$oldRows = array();
		$oldResult = mysql_query("SELECT m.Name, p.`Read`, p.`Write`, p.`Edit`, p.`Delete` FROM tblmodules m INNER JOIN tblpermissions p ON m.ModuleID = p.ModuleID WHERE p.AdminID=".$adminId);
		if ($oldResult) {
			while ($oldRow = mysql_fetch_array($oldResult)) {
				$oldRows[$oldRow['Name']] = $oldRow;
			}
		}
		foreach ($modules as $moduleName) {
			if (!isset($moduleIdsByName[$moduleName])) {
				continue;
			}
			$moduleId = $moduleIdsByName[$moduleName];
			$bits = cms2_merge_old_permission_rows($adminId, $moduleName, $oldRows, $role);
			cms2_upsert_permission_bits($adminId, $moduleId, $bits);
		}
	}

	$featureSql = array();
	foreach ($modules as $moduleName) {
		$featureSql[] = "'".mysql_real_escape_string($moduleName)."'";
	}
	$featureWhere = implode(',', $featureSql);
	mysql_query("DELETE p FROM tblpermissions p INNER JOIN tblmodules m ON p.ModuleID = m.ModuleID WHERE m.Name NOT IN (".$featureWhere.")");
	mysql_query("DELETE FROM tblmodules WHERE Name NOT IN (".$featureWhere.")");
	cms2_cleanup_duplicate_feature_modules_and_permissions();
}

function cms2_apply_default_permissions_for_admin($adminId, $role)
{
	$adminId = (int)$adminId;
	$role = cms2_normalize_admin_role($role);
	cms2_ensure_legacy_modules_and_permissions();

	$moduleNames = cms2_feature_modules();
	$moduleNameSql = array();
	foreach ($moduleNames as $moduleName) {
		$moduleNameSql[] = "'".mysql_real_escape_string($moduleName)."'";
	}
	$moduleResult = mysql_query("SELECT ModuleID, Name FROM tblmodules WHERE Name IN (".implode(',', $moduleNameSql).")");
	if (!$moduleResult) {
		return;
	}

	while ($moduleRow = mysql_fetch_array($moduleResult)) {
		$moduleId = (int)$moduleRow['ModuleID'];
		$bits = cms2_role_legacy_permission_bits($role, $moduleRow['Name']);
		cms2_upsert_permission_bits($adminId, $moduleId, $bits);
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
		'password.php' => array('Change Password', 'read'),
		'global.php' => array('Configuration', 'read'),
		'copyright.php' => array('Website Copyright', 'read'),
		'contactus.php' => array('Change Email Address', 'read'),
		'bootomcontact.php' => array('Configuration', 'edit'),
		'website.php' => array('Configuration', 'edit'),
		'mainimg.php' => array('Configuration', 'edit'),
		'menusetting.php' => array('Configuration', 'edit'),
		'menumodules.php' => array('Configuration', 'edit'),
		'manageadmin.php' => array('Admin Users Module', 'read'),
		'admin.php' => array('Admin Users Module', 'read'),
		'addadmin.php' => array('Admin Users Module', 'write'),
		'editadmin.php' => array('Admin Users Module', 'edit'),
		'permission.php' => array('Admin Users Module', 'edit'),
		'addvendors.php' => array('Vendors Module', 'write'),
		'editvendor.php' => array('Vendors Module', 'edit'),
		'vendormanage.php' => array('Vendors Module', 'read'),
		'addcertif.php' => array('Certifications Module', 'write'),
		'editcertif.php' => array('Certifications Module', 'edit'),
		'certifmanage.php' => array('Certifications Module', 'read'),
		'addexam.php' => array('Exams Module', 'write'),
		'editexam.php' => array('Exams Module', 'edit'),
		'exammanage.php' => array('Exams Module', 'read'),
		'bulk_update.php' => array('Exams Module', 'edit'),
		'bulk_update_date.php' => array('Exams Module', 'edit'),
		'updateDate.php' => array('Exams Module', 'edit'),
		'bannermanage.php' => array('Banners Module', 'read'),
		'bannermanage_new.php' => array('Banners Module', 'read'),
		'addslider.php' => array('Banners Module', 'write'),
		'editslider.php' => array('Banners Module', 'edit'),
		'editslider_new.php' => array('Banners Module', 'edit'),
		'add_gallery.php' => array('Exam Results Module', 'write'),
		'galley_management.php' => array('Exam Results Module', 'read'),
		'gallery_new.php' => array('Exam Results Module', 'read'),
		'editpagetext.php' => array('Global Content Module', 'edit'),
		'editemails.php' => array('Global Content Module', 'edit'),
		'vendorgloble.php' => array('Global Content Module', 'edit'),
		'editstylesheet.php' => array('Global Content Module', 'edit'),
		'courses_status_manage.php' => array('Courses Module', 'read'),
		'course_add.php' => array('Courses Module', 'write'),
		'course_edit.php' => array('Courses Module', 'edit'),
		'manageuser.php' => array('Users Module', 'read'),
		'adduser.php' => array('Users Module', 'write'),
		'edituser.php' => array('Users Module', 'edit'),
		'download.php' => array('Report', 'read'),
		'userstats.php' => array('Report', 'read'),
		'backup_stats.php' => array('Report', 'read'),
		'stats.php' => array('Report', 'read'),
		'masterorder.php' => array('Orders Module', 'read'),
		'ordermanage.php' => array('Orders Module', 'read'),
		'orderdetail.php' => array('Orders Module', 'read'),
		'editorder.php' => array('Orders Module', 'edit'),
		'odrproces.php' => array('Orders Module', 'edit'),
		'reodrproces.php' => array('Orders Module', 'edit'),
		'assign_user.php' => array('Exams Module', 'edit'),
		'managecoupon.php' => array('Coupons Module', 'read'),
		'addcoupon.php' => array('Coupons Module', 'write'),
		'editcoupon.php' => array('Coupons Module', 'edit'),
		'view_demoDownload.php' => array('Demo Exams Module', 'read'),
		'exam_request.php' => array('Demo Exams Module', 'read'),
		'managefull.php' => array('Demo Exams Module', 'read'),
		'managedemo.php' => array('Demo Exams Module', 'read'),
		'keys_action.php' => array('Demo Exams Module', 'edit'),
		'multi_keys_action.php' => array('Demo Exams Module', 'edit'),
		'manage_keys.php' => array('Demo Exams Module', 'read'),
		'addreport.php' => array('Report', 'write'),
		'addsubscriber.php' => array('Demo Exams Module', 'write'),
		'editsubscriber.php' => array('Demo Exams Module', 'edit'),
		'mailmanage.php' => array('Demo Exams Module', 'read'),
		'changestatus.php' => array('Exams Module', 'edit'),
		'changestatusall.php' => array('Orders Module', 'edit'),
		'changestatusnew.php' => array('Exams Module', 'edit'),
		'changestatusnewnew.php' => array('Exams Module', 'edit'),
		'changemodule.php' => array('Configuration', 'edit'),
		'processsave.php' => array('Orders Module', 'edit'),
		'processsavere.php' => array('Orders Module', 'edit'),
		'getsubcategory.php' => array('Vendors Module', 'read')
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
	foreach (cms2_feature_modules() as $moduleName) {
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
	if (!isset($_SESSION['rbacPermissionsSynced']) || $_SESSION['rbacPermissionsSynced'] != 'feature-modules-v2') {
		cms2_ensure_legacy_modules_and_permissions();
		$_SESSION['rbacPermissionsSynced'] = 'feature-modules-v2';
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
