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

//==================================================
// Is this a valid Sync Set?
//==================================================
$sync_set_id = (int)$segment_1;
$strsql = "select count(*) as count from data_sync_sets where id = ?";
$is_valid = qdb_lookup('', $strsql, 'count', array('i', $sync_set_id));
define('IS_VALID_SYNC_SET', (bool)$is_valid);

//==================================================
// Local Variables
//==================================================
include('local.var.php');

//==================================================
// Flow Control
//==================================================
switch ($segment_1) {

	case 'add_record':
		include('add_record.php');
		$this->page_type = 'page_ajax';
		break;

	case 'delete_record':
		include('delete_record.php');
		$this->page_type = 'page_ajax';
		break;

	case 'sync_sets':
		include('sync_sets/controller.php');
		break;
	
	default:
		if (IS_VALID_SYNC_SET) {
			include("main.php");
		}
		else {
			include('select_sync_set.php');
		}
		break;
}

