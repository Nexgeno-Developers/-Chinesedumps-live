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
		'Admin User Management' => 'staff',
		'Articles' => 'content',
		'Contents Management' => 'content',
		'Products Module' => 'products',
		'Users Module' => 'users',
		'Cart Mangement Modules' => 'orders',
		'Cart Management Modules' => 'orders',
		'Coupon Mangement Module' => 'coupons',
		'Coupon Management Module' => 'coupons'
	);
	return isset($map[$section]) ? $map[$section] : $section;
}

function cms2_role_permissions()
{
	return array(
		'super_admin' => array('*' => array('read', 'write', 'edit', 'delete')),
		'admin' => array(
			'dashboard' => array('read'),
			'settings' => array('read', 'write', 'edit'),
			'products' => array('read', 'write', 'edit', 'delete'),
			'banners' => array('read', 'write', 'edit', 'delete'),
			'content' => array('read', 'write', 'edit', 'delete'),
			'courses' => array('read', 'write', 'edit', 'delete'),
			'users' => array('read', 'write', 'edit', 'delete'),
			'orders' => array('read', 'write', 'edit', 'delete'),
			'coupons' => array('read', 'write', 'edit', 'delete'),
			'downloads' => array('read', 'write', 'edit', 'delete'),
			'support' => array('read', 'write', 'edit', 'delete'),
			'reports' => array('read')
		),
		'editor' => array(
			'dashboard' => array('read'),
			'products' => array('read', 'write', 'edit'),
			'banners' => array('read', 'write', 'edit'),
			'content' => array('read', 'write', 'edit'),
			'courses' => array('read', 'write', 'edit')
		),
		'support' => array(
			'dashboard' => array('read'),
			'users' => array('read', 'write', 'edit'),
			'orders' => array('read', 'write', 'edit'),
			'downloads' => array('read', 'write', 'edit'),
			'support' => array('read', 'write', 'edit'),
			'reports' => array('read')
		)
	);
}

function role_can($module, $action)
{
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
	return in_array($action, $permissions[$role][$module]);
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
		'sum.php' => array('dashboard', 'read'),
		'password.php' => array('dashboard', 'read'),
		'global.php' => array('settings', 'edit'),
		'copyright.php' => array('settings', 'edit'),
		'contactus.php' => array('settings', 'edit'),
		'bootomcontact.php' => array('settings', 'edit'),
		'website.php' => array('settings', 'edit'),
		'mainimg.php' => array('settings', 'edit'),
		'menusetting.php' => array('settings', 'edit'),
		'menumodules.php' => array('settings', 'edit'),
		'manageadmin.php' => array('staff', 'read'),
		'admin.php' => array('staff', 'read'),
		'addadmin.php' => array('staff', 'write'),
		'editadmin.php' => array('staff', 'edit'),
		'permission.php' => array('staff', 'edit'),
		'addvendors.php' => array('products', 'write'),
		'editvendor.php' => array('products', 'edit'),
		'vendormanage.php' => array('products', 'read'),
		'addcertif.php' => array('products', 'write'),
		'editcertif.php' => array('products', 'edit'),
		'certifmanage.php' => array('products', 'read'),
		'addexam.php' => array('products', 'write'),
		'editexam.php' => array('products', 'edit'),
		'exammanage.php' => array('products', 'read'),
		'bulk_update.php' => array('products', 'edit'),
		'bulk_update_date.php' => array('products', 'edit'),
		'updateDate.php' => array('products', 'edit'),
		'bannermanage.php' => array('banners', 'read'),
		'bannermanage_new.php' => array('banners', 'read'),
		'addslider.php' => array('banners', 'write'),
		'editslider.php' => array('banners', 'edit'),
		'editslider_new.php' => array('banners', 'edit'),
		'add_gallery.php' => array('content', 'write'),
		'galley_management.php' => array('content', 'read'),
		'gallery_new.php' => array('content', 'read'),
		'editpagetext.php' => array('content', 'edit'),
		'editemails.php' => array('content', 'edit'),
		'vendorgloble.php' => array('content', 'edit'),
		'editstylesheet.php' => array('content', 'edit'),
		'courses_status_manage.php' => array('courses', 'read'),
		'course_add.php' => array('courses', 'write'),
		'course_edit.php' => array('courses', 'edit'),
		'manageuser.php' => array('users', 'read'),
		'adduser.php' => array('users', 'write'),
		'edituser.php' => array('users', 'edit'),
		'download.php' => array('users', 'read'),
		'userstats.php' => array('reports', 'read'),
		'backup_stats.php' => array('reports', 'read'),
		'stats.php' => array('reports', 'read'),
		'masterorder.php' => array('orders', 'read'),
		'ordermanage.php' => array('orders', 'read'),
		'orderdetail.php' => array('orders', 'read'),
		'editorder.php' => array('orders', 'edit'),
		'odrproces.php' => array('orders', 'edit'),
		'reodrproces.php' => array('orders', 'edit'),
		'assign_user.php' => array('orders', 'edit'),
		'managecoupon.php' => array('coupons', 'read'),
		'addcoupon.php' => array('coupons', 'write'),
		'editcoupon.php' => array('coupons', 'edit'),
		'view_demoDownload.php' => array('support', 'read'),
		'exam_request.php' => array('support', 'read'),
		'managefull.php' => array('downloads', 'read'),
		'managedemo.php' => array('downloads', 'read'),
		'keys_action.php' => array('downloads', 'edit'),
		'multi_keys_action.php' => array('downloads', 'edit'),
		'manage_keys.php' => array('downloads', 'read'),
		'addreport.php' => array('reports', 'write'),
		'addsubscriber.php' => array('support', 'write'),
		'editsubscriber.php' => array('support', 'edit'),
		'mailmanage.php' => array('support', 'read'),
		'changestatus.php' => array('products', 'edit'),
		'changestatusall.php' => array('orders', 'edit'),
		'changestatusnew.php' => array('products', 'edit'),
		'changestatusnewnew.php' => array('products', 'edit'),
		'changemodule.php' => array('settings', 'edit'),
		'processsave.php' => array('orders', 'edit'),
		'processsavere.php' => array('orders', 'edit'),
		'processsavere.php' => array('orders', 'edit'),
		'getsubcategory.php' => array('products', 'read')
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
	if ($mappedModule != $section || in_array(strtolower($mappedModule), array('staff', 'content', 'products', 'users', 'orders', 'coupons', 'settings', 'banners', 'courses', 'downloads', 'support', 'reports', 'dashboard'))) {
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
