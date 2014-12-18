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
// Breadcrumb Variables
//============================================================
$show_db = true;
$show_table = true;

//============================================================
// Add CSS File
//============================================================
$this->add_css_file("{$this->html_path}/themes/phpdbadmin/db_compare.css");

//============================================================
// Flow Control
//============================================================
switch ($this->action) {
	case "db_analyze":
		$show_db = false;
		include("db_analyze.php");
		break;
		
	case "table_analyze":
		$show_table = false;
		include("table_analyze.php");
		break;
	
	case "resolve":
		include("resolve.php");
		break;

	case "resolve_submit":
		include("resolve_submit.php");
		break;
	
	case "resolve_table":
		include("table_resolve.php");
		break;

	case "resolve_table_submit":
		include("table_resolve_submit.php");
		break;
	
	default:
		include("main.php");
		break;
}

//============================================================
// Breadcrumbs
//============================================================
$curr_path = array();
$compare_icon = icon('fa fa-arrows-h');
if (!empty($ds1) && !empty($ds2)) {

	//--------------------------------------------------------
	// Module Home
	//--------------------------------------------------------
	$curr_path[] = anchor($this->page_url, 'DB Select');

	//--------------------------------------------------------
	// Sub-sections
	//--------------------------------------------------------
	if ($show_db) {

		//--------------------------------------------------------
		// DB Analyze
		//--------------------------------------------------------
		$tmp_link = add_url_params($this->page_url, array(
			'action' => 'db_analyze',
			'ds1' => $ds1,
			'ds2' => $ds2
		));
		$curr_path[] = anchor($tmp_link, "Compare: {$ds1} {$compare_icon} {$ds2}");
	
		//--------------------------------------------------------
		// Table Analyze
		//--------------------------------------------------------
		if (!empty($table1) && !empty($table2) && $show_table) {
			$tmp_link = add_url_params($this->page_url, array(
				'action' => 'table_analyze',
				'ds1' => $ds1,
				'ds2' => $ds2,
				'table1' => $table1,
				'table2' => $table2
			));
			$curr_path[] = anchor($tmp_link, "Compare: {$table1} {$compare_icon} {$table2}");
		}
	}
}
