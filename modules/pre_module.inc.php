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
// Authentication Check
//=========================================================================
$auth_ok = true;
if ($_SESSION['userid'] == 'none') {
	$auth_ok = false;
	$this->skip_module();
}
define('AUTH_OK', $auth_ok);

//=========================================================================
//=========================================================================
// Pre-module execution include file
//=========================================================================
//=========================================================================

load_plugin('ssv');
load_plugin('app_functions');
load_plugin("POP_bootstrap3");
load_plugin("phpdbadmin");
spl_autoload_register('load_plugin');

//**********************************************************
// New Version? Force Re-login
//**********************************************************
if (!isset($_SESSION['version'])) {
	redirect('/?mod=logout');
}
if ($_SESSION['version'] != file_get_contents($this->file_path . '/VERSION')) {
	redirect('/?mod=logout');
}

//**********************************************************
// Add-in CSS
//**********************************************************
$this->add_css_file('/bower_components/bootstrap/dist/css/bootstrap.min.css');
$this->add_css_file('/bower_components/fontawesome/css/font-awesome.min.css');
$this->add_css_file('/bower_components/AppJack/appjack.css');
$this->add_css_file("{$this->html_path}/themes/{$this->theme}/core.css");

//**********************************************************
// Add-in Javascript
//**********************************************************
$this->add_js_file('/bower_components/jquery/dist/jquery.min.js');
$this->add_js_file('/bower_components/bootstrap/dist/js/bootstrap.min.js');
$this->add_js_file('/bower_components/AppJack/appjack.js');
$this->add_js_file('main.js');

//**************************************************
// Reset Action
//**************************************************
//$this->action = (isset($this->mod_params[0])) ? ($this->mod_params[0]) : ('');
$action = $this->action;

//**************************************************
// Set Current Date
//**************************************************
$this->add_xml("current_date", date('l, M j, Y'));
$this->add_xml("current_year", date('Y'));

//**********************************************************
// Version
//**********************************************************
$this->add_xml('version', xml_escape($_SESSION['version']));

//**************************************************
// Message Arrays
//**************************************************
$error_message = array();
$warn_message = array();
$action_message = array();
$gen_message = array();

//**************************************************
// Nav Arrays
//**************************************************
$top_mod_links = array();
$breadcrumbs = array();
$curr_path = array();

//**********************************************************
// Module URLs
//**********************************************************
$page_url = $this->page_url;
if ($_SESSION['nav_xml_format'] == 'long_url' && $page_url == '/') {
	$page_url .= 'index.php/';
}
$GLOBALS['mod_base_url'] = $page_url;
$GLOBALS['mod_base_url2'] = $page_url;
$GLOBALS['mod_home_url'] = $page_url;
$mod_base_url =& $GLOBALS['mod_base_url'];
$mod_base_url2 =& $GLOBALS['mod_base_url2'];
$mod_home_url =& $GLOBALS['mod_home_url'];

//**********************************************************
// Base URL
//**********************************************************
$this->add_xml('base_url', xml_escape($page_url));

//**********************************************************
// Default Timestamp Format
//**********************************************************
define('DEFAULT_TIMESTAMP_FORMAT', "n/j/Y g:i a");
define('DEFAULT_TIMESTAMP', 'n/j/Y g:i a');

//**********************************************************
// Constants
//**********************************************************
//define('DATA_SRC', $_SESSION['default_data_source']);
define('BASE_URL', $page_url);

//**********************************************************
// Set Action / Next action
//**********************************************************
$next_action = array(
	"add" => "insert", 
	"edit" => "update", 
	"confirm_delete" => "delete",
	'view' => ''
);

//**********************************************************
// Modules Common Directory
//**********************************************************
$mod_common_dir = __DIR__ . '/common';
define('MOD_COMMON_DIR', $mod_common_dir);

//**********************************************************
// Default Module Icon
//**********************************************************
$mod_icon_class = 'fa fa-database';

//**********************************************************
// Icons
//**********************************************************
$add_image = icon('fa fa-plus');
$edit_image = icon('fa fa-pencil');
$open_image = icon('fa fa-folder-open');
$delete_image = icon('fa fa-times');
$view_image = icon('fa fa-search');
$info_image = icon('fa fa-info-circle');
$print_image = icon('fa fa-print');
$check_image = icon('fa fa-check');
$filter_image = icon('fa fa-filter');
$action_image = icon('fa fa-thumbs-up');
$cat_image = icon('fa fa-list-ul');
$arrowr_image = icon('fa fa-arrow-right');
$arrowl_image = icon('fa fa-arrow-left');
$report_image = icon('fa fa-file-o');
$notes_image = icon('fa fa-edit');
$clear_image = icon('fa fa-reply');
$clock_image = icon('fa fa-clock-o');

define('INFO_IMAGE', $info_image);
define('ARROWR_IMAGE', $arrowr_image);

//**********************************************************
// Set Segments
//**********************************************************
$segment_1 = (isset($this->mod_params[0])) ? ($this->mod_params[0]) : (false);
$segment_2 = (isset($this->mod_params[1])) ? ($this->mod_params[1]) : (false);
$segment_3 = (isset($this->mod_params[2])) ? ($this->mod_params[2]) : (false);
$segment_4 = (isset($this->mod_params[3])) ? ($this->mod_params[3]) : (false);
$segment_5 = (isset($this->mod_params[4])) ? ($this->mod_params[4]) : (false);

define('SEGMENT_1', $segment_1);
define('SEGMENT_2', $segment_2);
define('SEGMENT_3', $segment_3);
define('SEGMENT_4', $segment_4);
define('SEGMENT_5', $segment_5);

$segments = array(
	1 => $segment_1,
	2 => $segment_2,
	3 => $segment_3,
	4 => $segment_4,
	5 => $segment_5
);
//var_dump($segments);

//**********************************************************
// Debug
//**********************************************************
//$this->set_output_xml();
//print_array($_SESSION);
//print_array($_SESSION['modules_list']);
