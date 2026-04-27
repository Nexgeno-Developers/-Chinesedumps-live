<?php
if (!function_exists('role_can')) {
	include_once 'functions.php';
}

function cms2_render_menu_section($title, $items, $first)
{
	$visible = array();
	foreach ($items as $item) {
		if ($item[1] == 'logout.php' || role_can($item[0], $item[2])) {
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
		echo '<tr><td class="menu"><a class="menu" href="'.$item[1].'"><img src="'.$item[3].'" width="16" height="16" border="0" class="icon2" />'.$item[0].'</a></td></tr>';
	}
	echo '</tbody></table>';
}

$cms2MenuSections = cms2_admin_panel_sections();
for ($cms2MenuIndex = 0; $cms2MenuIndex < count($cms2MenuSections); $cms2MenuIndex++) {
	cms2_render_menu_section($cms2MenuSections[$cms2MenuIndex][0], $cms2MenuSections[$cms2MenuIndex][1], $cms2MenuIndex == 0);
}
?>
