<?php
if (!function_exists('role_can')) {
	include_once 'functions.php';
}

function cms2_menu_sections()
{
	return array(
		array('Global', array(
			array('Configuration', 'global.php', 'settings', 'edit', 'images/News-Edit.gif'),
			array('Summary', 'sum.php', 'dashboard', 'read', 'images/admin_summary16.gif'),
			array('Change Password', 'password.php', 'dashboard', 'read', 'images/admin_log16.gif'),
			array('Website Copyright', 'copyright.php', 'settings', 'edit', 'images/News-Edit.gif'),
			array('Change Email Address', 'contactus.php', 'settings', 'edit', 'images/Chart1-Edit.gif'),
			array('Logout', 'logout.php', 'dashboard', 'read', 'images/admin_banning16.gif')
		)),
		array('Stats', array(
			array('All Registered Users', 'manageuser.php', 'users', 'read', 'images/News-Edit.gif'),
			array('Reports', 'backup_stats.php', 'reports', 'read', 'images/admin_summary16.gif')
		)),
		array('Banners', array(
			array('Banners Management', 'bannermanage.php', 'banners', 'read', 'images/admin_summary16.gif'),
			array('Add Banner', 'addslider.php', 'banners', 'write', 'images/News-Add.gif')
		)),
		array('Exam Results', array(
			array('Exam Results Management', 'galley_management.php', 'content', 'read', 'images/admin_summary16.gif'),
			array('Add Exam Results Image', 'add_gallery.php', 'content', 'write', 'images/News-Edit.gif')
		)),
		array('Products Module', array(
			array('Add Vendors', 'addvendors.php', 'products', 'write', 'images/News-Add.gif'),
			array('Vendors Management', 'vendormanage.php', 'products', 'read', 'images/Comments-edit.gif'),
			array('Add Certification', 'addcertif.php', 'products', 'write', 'images/News-Add.gif'),
			array('Certifications Management', 'certifmanage.php', 'products', 'read', 'images/Comments-edit.gif'),
			array('Add Exam', 'addexam.php', 'products', 'write', 'images/News-Add.gif'),
			array('Exams Management', 'exammanage.php', 'products', 'read', 'images/Comments-edit.gif')
		)),
		array('Demo & Exams Management Modules', array(
			array('View Demo Emails', 'view_demoDownload.php', 'support', 'read', 'images/News-Edit.gif'),
			array('View Exam Request Email', 'exam_request.php', 'support', 'read', 'images/News-Edit.gif'),
			array('Full Download Management', 'managefull.php', 'downloads', 'read', 'images/News-Edit.gif')
		)),
		array('Cart Management Modules', array(
			array('Final Orders', 'masterorder.php', 'orders', 'read', 'images/News-Edit.gif'),
			array('List Cart', 'odrproces.php', 'orders', 'edit', 'images/News-Edit.gif'),
			array('Re-Order Cart', 'reodrproces.php', 'orders', 'edit', 'images/News-Edit.gif')
		)),
		array('Coupon Management Module', array(
			array('Manage Coupons', 'managecoupon.php', 'coupons', 'read', 'images/News-Edit.gif'),
			array('Add new Coupon', 'addcoupon.php', 'coupons', 'write', 'images/News-Edit.gif')
		)),
		array('Stable/Unstable Course Management Module', array(
			array('Manage Course', 'courses_status_manage.php', 'courses', 'read', 'images/News-Edit.gif'),
			array('Add new Course', 'course_add.php', 'courses', 'write', 'images/News-Edit.gif')
		)),
		array('Global Content', array(
			array('Edit Emails', 'editemails.php', 'content', 'edit', 'images/News-Edit.gif'),
			array('Vendor Global Content', 'vendorgloble.php?pid=1', 'content', 'edit', 'images/News-Edit.gif'),
			array('Certification Global Content', 'vendorgloble.php?pid=2', 'content', 'edit', 'images/News-Edit.gif'),
			array('Exam Global Content', 'vendorgloble.php?pid=3', 'content', 'edit', 'images/News-Edit.gif')
		)),
		array('Users Module', array(
			array('Add User', 'adduser.php', 'users', 'write', 'images/News-Add.gif'),
			array('User Management', 'manageuser.php', 'users', 'read', 'images/News-Edit.gif')
		)),
		array('Admin Users', array(
			array('Admin User Management', 'manageadmin.php', 'staff', 'read', 'images/News-Edit.gif'),
			array('Add Admin User', 'addadmin.php', 'staff', 'write', 'images/News-Add.gif')
		))
	);
}

function cms2_render_menu_section($title, $items, $first)
{
	$visible = array();
	foreach ($items as $item) {
		if ($item[1] == 'logout.php' || role_can($item[2], $item[3])) {
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
		echo '<tr><td class="menu"><a class="menu" href="'.$item[1].'"><img src="'.$item[4].'" width="16" height="16" border="0" class="icon2" />'.$item[0].'</a></td></tr>';
	}
	echo '</tbody></table>';
}

$cms2MenuSections = cms2_menu_sections();
for ($cms2MenuIndex = 0; $cms2MenuIndex < count($cms2MenuSections); $cms2MenuIndex++) {
	cms2_render_menu_section($cms2MenuSections[$cms2MenuIndex][0], $cms2MenuSections[$cms2MenuIndex][1], $cms2MenuIndex == 0);
}
?>
