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

//============================================================
// Include Local Functions / Variables
//============================================================
$local_func = __DIR__ . '/local.func.php';
if (file_exists($local_func)) { include($local_func); }
$local_var = __DIR__ . '/local.var.php';
if (file_exists($local_var)) { include($local_var); }

//============================================================
// Load Plugins
//============================================================
if (!empty($plugins)) { foreach ($plugins as $plugin) { load_plugin($plugin); } }
if (!empty($plugins2)) { foreach ($plugins2 as $plugin) { load_module_plugin($plugin); } }

//============================================================
// Flow Control
//============================================================
switch ($this->action) {

    case "add":
    case "edit":
    case "confirm_delete":
    	$pull_from_db = true;
        include("form_controller.php");
        break;

	case 'items':
		include('frm_items.php');
		break;

    case "insert":
    case "update":
    case "delete":
        include("db_action.php");
        break;

	case 'save_items':
		include('save_items.php');
		break;

	default:
		redirect($this->page_url);
		break;
}

