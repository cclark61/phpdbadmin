<?php

/**
* Main Application Controller
*
* @package		Controller
* @subpackage	Application
* @author 		Christian J. Clark
* @version 		Started: 3-15-2006, Last updated: 4-19-2006
**/

//*************************************************************************
//*************************************************************************
// Main Application Controller
//*************************************************************************
//*************************************************************************
define('MAIN_CONTROLLER', 1);

//*************************************************************************
// "Pseudo" Content Devlivery Network
//*************************************************************************
include('cdn.inc.php');

//*************************************************************************
// Start the session
//*************************************************************************
session_start();

//*************************************************************************
// Load the configuration if necessary
//*************************************************************************
if (!isset($_SESSION["frame_path"])) {
	include(__DIR__ . "/config.inc.php");
	$_SESSION["file_path"] = dirname(__FILE__);
	include("{$config_arr['frame_path']}/main_controller.php");
}
else {
	include("{$_SESSION['frame_path']}/main_controller.php");
}

?>
