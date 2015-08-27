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

//***************************************************************************
//***************************************************************************
// Application Configuration
//***************************************************************************
//***************************************************************************

//=======================================================
// Path to Framework Directory
//=======================================================
$config_arr["frame_path"] = __DIR__ . "/vendor/cclark61/phpOpenFW/framework";
//$config_arr["frame_path"] = "phar://" . __DIR__ . '/vendor/cclark61/phpOpenFW/phpOpenFW.phar';

//=======================================================
// XML Nav Format (numeric (default), rewrite, long_url)
//=======================================================
$config_arr["nav_xml_format"] = "long_url";

//=======================================================
// Version
//=======================================================
$config_arr['version'] = file_get_contents('VERSION');

//=======================================================
// Settings: Theme, Creator, Titles, Headers, etc.
//=======================================================
$config_arr["creator"] = "Christian J. Clark";
$config_arr["app_url"] = "http://www.emonlade.net/phpdbadmin/";
$config_arr["theme"] = "phpdbadmin";
$config_arr["login_title"] = "phpDBAdmin";
$config_arr["site_title"] = "phpDBAdmin";
$config_arr["header_title"] = 'phpDBAdmin';
$config_arr['site_logo'] = '/img/logos/emonlade_logo.png';
$config_arr['site_logo_icon'] = '/img/logos/emonlade_logo.png';
$config_arr['touch_icon'] = '/img/logos/emonlade_logo_white_128.png';
$config_arr['fav_icon'] = '/img/logos/emonlade_logo.png';

//***************************************************************************
//***************************************************************************
// Authentication
// Login Settings
//***************************************************************************
//***************************************************************************

//=======================================================
// Data Source to use for authentication
// ** Must be a valid data source handle specified below or "none"
//=======================================================
$config_arr["auth_data_source"] = "none";

//=======================================================
// Database Authentication Parameters
//=======================================================
$config_arr["auth_user_table"] = "users";
$config_arr["auth_user_field"] = "userid";
$config_arr["auth_pass_field"] = "password";
$config_arr["auth_fname_field"] = "first_name";
$config_arr["auth_lname_field"] = "last_name";

//=======================================================
// Password Security
// ** Options: [ clear (default), md5, sha1, sha256 ]
//=======================================================
$config_arr["auth_pass_security"] = "sha1";

//***************************************************************************
//***************************************************************************
// Data Sources
//***************************************************************************
//***************************************************************************
if (file_exists(__DIR__ . '/config/data_sources.php')) {
	include(__DIR__ . '/config/data_sources.php');
}

//***************************************************************************
// phpDBAdmin Database
//***************************************************************************
$i = "phpdbadmin";
$data_arr[$i]["type"] = '[%_DS_TYPE_%]';
$data_arr[$i]["server"] = '[%_SERVER_%]';
$data_arr[$i]["port"] = '[%_PORT_%]';
$data_arr[$i]["source"] = '[%_DB_NAME_%]';
$data_arr[$i]["user"] = '[%_DB_USER_%]';
$data_arr[$i]["pass"] = '[%_DB_PASS_%]';

//***************************************************************************
// Set Default Data Source
//***************************************************************************
$config_arr['default_data_source'] = 'phpdbadmin';
