<?php
//************************************************************
/**
* phpDBAdmin
*
* @package		phpDBAdmin
* @author 		Christian J. Clark
* @copyright	Copyright (c) Christian J. Clark
* @license		http://www.gnu.org/licenses/gpl-3.0.txt
* @link			http://www.emonlade.net/phpdbadmin/
**/
//************************************************************

//=========================================================================
//=========================================================================
// Post-module execution include file
//=========================================================================
//=========================================================================

//**************************************************************
// Authenticated OK?
//**************************************************************
if (!AUTH_OK) {
	$this->page_type = 'page_no_auth';
	$mod_icon_class = 'fa fa-exclamation-triangle';
	$mod_title = 'No Authentication';
}


//**************************************************************
// Build Navigations
//**************************************************************
if (AUTH_OK & !empty($_SESSION['modules_list'])) {

	//==========================================================	
	// Main Nav
	//==========================================================
	$main_menu = '';
	foreach ($_SESSION['modules_list']['modules']['sub-modules'] as $mod) {
		$link_attrs = array();
		if ($this->page_url == "/index.php/{$mod['index']}/") {
			$link_attrs['class'] = 'active';
		}

		$mod_url = '/index.php/' . $mod['index'] . '/';
		$main_menu .= li(
			anchor($mod_url, icon($mod['mod_icon_class']) . ' ' . $mod['mod_title']), 
			$link_attrs
		);
	}
	$this->add_xml('main_menu', xml_escape($main_menu));
	
}

//**************************************************************
// Segments
//**************************************************************
if (isset($segment_0)) { $this->add_xml("segment_0", xml_escape($segment_0)); }
if (isset($segment_1)) { $this->add_xml("segment_1", xml_escape($segment_1)); }
if (isset($segment_2)) { $this->add_xml("segment_2", xml_escape($segment_2)); }
if (isset($segment_3)) { $this->add_xml("segment_3", xml_escape($segment_3)); }
if (isset($segment_4)) { $this->add_xml("segment_4", xml_escape($segment_4)); }
if (isset($segment_5)) { $this->add_xml("segment_5", xml_escape($segment_5)); }

//**************************************************************
// Theme
//**************************************************************
$this->add_xml("theme", $this->theme);
$this->add_xml("theme_path", "{$this->html_path}/themes/{$this->theme}");

//**************************************************************
// Site Settings
//**************************************************************
if (isset($_SESSION['creator'])) { $this->add_xml("creator", xml_escape($_SESSION['creator'])); }
if (isset($_SESSION['app_url'])) { $this->add_xml("app_url", xml_escape($_SESSION['app_url'])); }
if (isset($_SESSION['app_name'])) { $this->add_xml("app_name", xml_escape($_SESSION['app_name'])); }
if (isset($_SESSION['app_code'])) { $this->add_xml("app_code", xml_escape($_SESSION['app_code'])); }
if (isset($_SESSION['header_title'])) { $this->add_xml("header_title", xml_escape($_SESSION['header_title'])); }
if (isset($site_title)) { $this->add_xml("site_title", xml_escape($site_title)); }
else if (isset($_SESSION['site_title'])) { $this->add_xml("site_title", xml_escape($_SESSION['site_title'])); }
if (isset($_SESSION['site_logo'])) { $this->add_xml("site_logo", xml_escape($_SESSION['site_logo'])); }
if (isset($_SESSION['site_logo_icon'])) { $this->add_xml("site_logo_icon", xml_escape($_SESSION['site_logo_icon'])); }
if (isset($_SESSION['touch_icon'])) { $this->add_xml("touch_icon", xml_escape($_SESSION['touch_icon'])); }
if (isset($_SESSION['fav_icon'])) { $this->add_xml("fav_icon", xml_escape($_SESSION['fav_icon'])); }

//**************************************************************
// Modules List
//**************************************************************
//$this->add_xml("modules_list", xml_escape_array($_SESSION['modules_list']));

//**************************************************************
// Module Title
//**************************************************************
if (!isset($mod_title)) { $mod_title = "???"; }
$this->add_xml("mod_title", xml_escape($mod_title));

//**************************************************************
// Module Images / Icon Classes
//**************************************************************
if (isset($mod_icon_class)) { $this->add_xml("mod_icon_class", xml_escape($mod_icon_class)); }

//**************************************************************
// Module Base URL
//**************************************************************
if (isset($mod_base_url)) { $this->add_xml("mod_base_url", xml_escape($mod_base_url)); }
if (isset($mod_base_url2)) { $this->add_xml("mod_base_url", xml_escape($mod_base_url2)); }

//**************************************************************
// Sub Title / Back Link
//**************************************************************
if (isset($sub_title)) { $this->add_xml("sub_title", xml_escape($sub_title)); }
if (isset($back_link)) { $this->add_xml("back_link", xml_escape($back_link)); }

//**************************************************************
// Links
//**************************************************************
if (isset($top_mod_links) && is_array($top_mod_links) && count($top_mod_links) > 0) { $this->add_xml("top_mod_links", $top_mod_links); }

//**************************************************************
// Current Path / Breadcrumbs
//**************************************************************
if (!empty($curr_path) && is_array($curr_path)) {
	$this->add_xml('current_path', xml_escape_array($curr_path));
}

//**************************************************************
// Convert Message strings into arrays
//**************************************************************
if (isset($error_message) && !is_array($error_message)) { $error_message = array($error_message); }
if (isset($warn_message) && !is_array($warn_message)) { $warn_message = array($warn_message); }
if (isset($action_message) && !is_array($action_message)) { $action_message = array($action_message); }
if (isset($gen_message) && !is_array($gen_message)) { $gen_message = array($gen_message); }

//**************************************************************
// Messages
//**************************************************************
$message_types = array(
	'error_message',
	'warn_message',
	'action_message',
	'gen_message',
	'page_message',
	'bottom_message',
	'timer_message'
);

foreach ($message_types as $msg_type) {
	if (!empty($$msg_type) || !empty($_SESSION[$msg_type])) {
		$formatted_messages = false;
		if (!empty($$msg_type) && !empty($_SESSION[$msg_type])) {
			$formatted_messages = format_page_messages($$msg_type, $_SESSION[$msg_type]);
			unset($_SESSION[$msg_type]);
		}
		else if (!empty($$msg_type)) {
			$formatted_messages = format_page_messages($$msg_type);
		}
		else if (!empty($_SESSION[$msg_type])) {
			$formatted_messages = format_page_messages($_SESSION[$msg_type]);
			unset($_SESSION[$msg_type]);
		}
		if ($formatted_messages) {
			$this->add_xml($msg_type, array2xml('messages', $formatted_messages));
		}
	}
}

//**************************************************************
//**************************************************************
// Format Page Messages function
//**************************************************************
//**************************************************************
function format_page_messages()
{
	$messages = array();
	$args = func_get_args();
	foreach ($args as $arg) {
		if (is_array($arg) && count($arg) > 0) {
			foreach ($arg as $key => $arg_msg) {
				$messages[] = xml_escape($arg_msg);
			}
		}
		else if ((string)$arg != '') {
			$messages[] = xml_escape($arg);
		}
	}

	return $messages;
}

//**************************************************************
// XML Output if flagged
//**************************************************************
if (!empty($_SESSION['ENV']) && $_SESSION['ENV'] == 'dev' && isset($_GET['xml_output'])) {
	$this->set_output_xml();
}
?>
