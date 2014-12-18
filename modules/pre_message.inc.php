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
// Pre-message execution include file
//=========================================================================

//**********************************************************
// Add-in CSS
//**********************************************************
$css_files = array(
	'/bower_components/bootstrap/dist/css/bootstrap.min.css',
	'/bower_components/fontawesome/css/font-awesome.min.css',
	//'/bower_components/AppJack/appjack.css',
	"{$this->html_path}/themes/{$this->theme}/msg_page.css"
);
$this->add_xml('css_files', $css_files);

//**********************************************************
// Add-in Javascript
//**********************************************************
$js_files = array(
	//'/bower_components/jquery/dist/jquery.min.js',
	//'/bower_components/bootstrap/dist/js/bootstrap.min.js',
	//'/bower_components/AppJack/appjack.js',
	//'main.js'
);
$this->add_xml('js_files', $js_files);

//**************************************************************************
// Set Current Date
//**************************************************************************
$this->add_xml("current_date", date('l, M j, Y'));
$this->add_xml("current_year", date('Y'));

//**************************************************************************
// Site Settings
//**************************************************************************
if (isset($_SESSION['creator'])) { $this->add_xml("creator", xml_escape($_SESSION['creator'])); }
if (isset($_SESSION['app_url'])) { $this->add_xml("app_url", xml_escape($_SESSION['app_url'])); }
if (isset($_SESSION['app_name'])) { $this->add_xml("app_name", xml_escape($_SESSION['app_name'])); }
if (isset($_SESSION['app_code'])) { $this->add_xml("app_code", xml_escape($_SESSION['app_code'])); }
if (isset($_SESSION['login_title'])) { $this->add_xml("login_title", xml_escape($_SESSION['login_title'])); }
if (isset($_SESSION['site_title'])) { $this->add_xml("site_title", xml_escape($_SESSION['site_title'])); }
if (isset($_SESSION['site_logo'])) { $this->add_xml("site_logo", xml_escape($_SESSION['site_logo'])); }
if (isset($_SESSION['site_logo_icon'])) { $this->add_xml("site_logo_icon", xml_escape($_SESSION['site_logo_icon'])); }
if (isset($_SESSION['fav_icon'])) { $this->add_xml("fav_icon", xml_escape($_SESSION['fav_icon'])); }
if (isset($_SESSION['touch_icon'])) { $this->add_xml("touch_icon", xml_escape($_SESSION['touch_icon'])); }

//**************************************************************************
// Version
//**************************************************************************
$_SESSION['version'] = file_get_contents('VERSION');
$this->add_xml("version", xml_escape($_SESSION["version"]));

//**************************************************************************
// Debug
//**************************************************************************
//$this->set_output_xml();

