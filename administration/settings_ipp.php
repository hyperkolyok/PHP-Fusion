<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: settings_ipp.php
| Author: Hans Kristian Flaatten (Starefossen)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "../maincore.php";
pageAccess('S10');
require_once THEMES."templates/admin_header.php";
include LOCALE.LOCALESET."admin/settings.php";
add_to_breadcrumbs(array('link'=>ADMIN.'settings_ipp.php'.$aidlink, 'title'=>$locale['ipp_settings']));
if (isset($_POST['savesettings'])) {
	// why no check admin pass?
	$newsperpage = form_sanitizer($_POST['newsperpage'], 12, 'newsperpage');
	$result = (!defined('FUSION_NULL')) ? dbquery("UPDATE ".DB_SETTINGS." SET settings_value='$newsperpage' WHERE settings_name='newsperpage'") : '';
	$blogperpage = form_sanitizer($_POST['blogperpage'], 12, 'blogperpage');
	$result = (!defined('FUSION_NULL')) ? dbquery("UPDATE ".DB_SETTINGS." SET settings_value='$blogperpage' WHERE settings_name='blogperpage'") : '';
	$articles_per_page = form_sanitizer($_POST['articles_per_page'], 15, 'articles_per_page');
	$result = (!defined('FUSION_NULL')) ? dbquery("UPDATE ".DB_SETTINGS." SET settings_value='$articles_per_page' WHERE settings_name='articles_per_page'") : '';
	$downloads_per_page = form_sanitizer($_POST['downloads_per_page'], 15, 'downloads_per_page');
	$result = (!defined('FUSION_NULL')) ? dbquery("UPDATE ".DB_SETTINGS." SET settings_value='$downloads_per_page' WHERE settings_name='downloads_per_page'") : '';
	$links_per_page = form_sanitizer($_POST['links_per_page'], 15, 'links_per_page');
	$result = (!defined('FUSION_NULL')) ? dbquery("UPDATE ".DB_SETTINGS." SET settings_value='$links_per_page' WHERE settings_name='links_per_page'") : '';
	$comments_per_page = form_sanitizer($_POST['comments_per_page'], 10, 'comments_per_page');
	$result = (!defined('FUSION_NULL')) ? dbquery("UPDATE ".DB_SETTINGS." SET settings_value='$comments_per_page' WHERE settings_name='comments_per_page'") : '';
	$threads_per_page = form_sanitizer($_POST['threads_per_page'], 20, 'threads_per_page');
	$result = (!defined('FUSION_NULL')) ? dbquery("UPDATE ".DB_SETTINGS." SET settings_value='$threads_per_page' WHERE settings_name='threads_per_page'") : '';
	$posts_per_page = form_sanitizer($_POST['posts_per_page'], 20, 'posts_per_page');
	$result = (!defined('FUSION_NULL')) ? dbquery("UPDATE ".DB_SETTINGS." SET settings_value='$posts_per_page' WHERE settings_name='posts_per_page'") : '';
	if (!defined('FUSION_NULL')) {
		redirect(FUSION_SELF.$aidlink."&error=0");
	}
}
$settings2 = array();
$result = dbquery("SELECT * FROM ".DB_SETTINGS);
while ($data = dbarray($result)) {
	$settings2[$data['settings_name']] = $data['settings_value'];
}
opentable($locale['ipp_settings']);
if (isset($_GET['error']) && isnum($_GET['error']) && !isset($message)) {
	if ($_GET['error'] == 0) {
		$message = $locale['900'];
	}
	if (isset($message)) {
		echo admin_message($message);
	}
}
echo openform('settingsform', 'settingsform', 'post', FUSION_SELF.$aidlink, array('downtime' => 1));
echo "<div class='well'>".$locale['ipp_description']."</div>";
echo "<div class='row'><div class='col-xs-12 col-sm-12 col-md-6'>";
openside('');
echo form_text($locale['669b'], 'blogperpage', 'blogperpage', $settings2['blogperpage'], array('inline'=>1, 'required' => 1, 'error_text' => $locale['error_value'], 'number' => 1, 'width' => '250px'));
echo form_text($locale['669'], 'newsperpage', 'newsperpage', $settings2['newsperpage'], array('inline'=>1, 'required' => 1, 'error_text' => $locale['error_value'], 'number' => 1, 'width' => '250px'));
echo form_text($locale['910'], 'articles_per_page', 'articles_per_page', $settings2['articles_per_page'], array('inline'=>1, 'required' => 1, 'error_text' => $locale['error_value'], 'number' => 1, 'width' => '250px'));
echo form_text($locale['911'], 'downloads_per_page', 'downloads_per_page', $settings2['downloads_per_page'], array('inline'=>1, 'required' => 1, 'error_text' => $locale['error_value'], 'number' => 1, 'width' => '250px'));
closeside();
echo "</div><div class='col-xs-12 col-sm-12 col-md-6'>\n";
openside('');
echo form_text($locale['912'], 'links_per_page', 'links_per_page', $settings2['links_per_page'], array('inline'=>1, 'required' => 1, 'error_text' => $locale['error_value'], 'number' => 1, 'width' => '250px'));
echo form_text($locale['913'], 'comments_per_page', 'comments_per_page', $settings2['comments_per_page'], array('inline'=>1, 'required' => 1, 'error_text' => $locale['error_value'], 'number' => 1, 'width' => '250px'));
echo form_text($locale['914'], 'threads_per_page', 'threads_per_page', $settings2['threads_per_page'], array('inline'=>1, 'required' => 1, 'error_text' => $locale['error_value'], 'number' => 1, 'width' => '250px'));
echo form_text($locale['915'], 'posts_per_page', 'posts_per_page', $settings2['posts_per_page'], array('inline'=>1, 'required' => 1, 'error_text' => $locale['error_value'], 'number' => 1, 'width' => '250px'));
closeside('');
echo "</div>\n</div>\n";
echo form_button($locale['750'], 'savesettings', 'savesettings', $locale['750'], array('class' => 'btn-success'));

echo closeform();
closetable();
require_once THEMES."templates/footer.php";
?>