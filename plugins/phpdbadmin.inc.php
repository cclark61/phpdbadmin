<?php
//*****************************************************************************
//*****************************************************************************
/**
* phpDBAdmin General Functions Plugin
*
* @package		phpDBAdmin
* @subpackage	Plugins
* @copyright	Copyright (c) Christian J. Clark
* @license		http://www.gnu.org/licenses/gpl-3.0.txt
* @link			http://www.emonlade.net/phpdbadmin/
**/
//*****************************************************************************
//*****************************************************************************

//=========================================================================
//=========================================================================
// Get Data Sources List
//=========================================================================
//=========================================================================
function get_data_sources_list()
{
	if (empty($_SESSION['data_sources'])) {
		return false;
	}
	else if (!is_array($_SESSION['data_sources'])) {
		return false;
	}
	$datasrc_arr = $_SESSION['data_sources'];
	array_unshift($datasrc_arr, '');
	$datasrc_arr = array_combine($datasrc_arr, $datasrc_arr);
	return $datasrc_arr;
}

//=========================================================================
//=========================================================================
/**
* Return a Twitter Bootstrap CSS based Icon
*
* @param string Icon to use i.e. 'fa fa-check'
*
* @return string HTML CSS Icon
*/
//=========================================================================
//=========================================================================
function icon($i)
{
	if (empty($i)) { return false; }
	return "<i class=\"{$i}\"></i>";
}

//===================================================================
//===================================================================
// Display Module Information Function
//===================================================================
//===================================================================
function display_modules_list($dir, $base_url=false)
{
	if (is_dir($dir)) {
		ob_start();

		$items = scandir($dir);
		if ($items) {
			if ($base_url) { clean_dir($base_url); }
			foreach ($items as $item) {
				if ($item == '.' || $item == '..') { continue; }
				$tmp_item = "{$dir}/{$item}";
				$tmp_local_inc = "{$dir}/{$item}/local.inc.php";
				if (is_dir($tmp_item) && file_exists($tmp_local_inc)) {
					if (isset($mod_title)) { unset($mod_title); }
					$mod_desc = false;
					$link = false;
					$icon = false;
					include($tmp_local_inc);
					if (!empty($mod_title)) {
						if ($base_url !== false) {
							$link = "{$base_url}/{$item}/";
						}
						if (!empty($mod_icon_class)) {
							$icon = icon($mod_icon_class);
						}
						display_module_info($mod_title, $mod_desc, $link, $icon);
					}
				}
			}
		}

		print div(ob_get_clean(), array('class' => 'sub-modules-nav'));
		return true;
	}

	trigger_error('Invalid directory passed.');
	return false;
}

//===================================================================
//===================================================================
// Display Module Information Function
//===================================================================
//===================================================================
function display_module_info($title, $desc, $link=false, $icon=false)
{
	//--------------------------------------------------------
	// Title / Link
	//--------------------------------------------------------
	if ($icon) { $title = $icon . span($title); }
	if ($link) { $title = anchor($link, $title); }

	//--------------------------------------------------------
	// Content
	//--------------------------------------------------------
	ob_start();
	print xhe("div", $title, array('class' => 'title'));
	if (!empty($desc)) {
		print xhe("p", icon('fa fa-caret-right') . $desc); // fa-caret-right fa-info-circle
	}

	//--------------------------------------------------------
	// Output
	//--------------------------------------------------------
	print div(ob_get_clean(), array('class' => 'module-list-item'));
}

//===================================================================
//===================================================================
// Print New Test Header
//===================================================================
//===================================================================
function print_header($test_name)
{
	print xhe('div', "&raquo; {$test_name}", array('class' => 'header_title bold'));
}

//===================================================================
//===================================================================
// Print New Test Sub-Header
//===================================================================
//===================================================================
function print_sub_header($test_name)
{
	print xhe('div', "&raquo; {$test_name}", array('class' => 'sub_title bold'));
}

//====================================================================
//====================================================================
// Get MySQL Table Info Function
//====================================================================
//====================================================================
function get_mysql_table_info($ds, $table)
{
	$strsql = "
		SELECT * FROM 
			information_schema.columns
		WHERE 
			table_name = ?
	";
	return qdb_exec($ds, $strsql, array('s', $table), 'COLUMN_NAME');
}

//====================================================================
//====================================================================
// Get MySQL Record Matches Function
//====================================================================
//====================================================================
function get_mysql_record_matches($ds, $table, $field_data)
{
	global $mysql_bind_types;
	
	//-------------------------------------------
	// Table Info
	//-------------------------------------------
	$table_info = get_mysql_table_info($ds, $table);

	//-------------------------------------------
	// Build SQL
	//-------------------------------------------
	$params = array('');
	$strsql = "select count(*) as count from {$table} where";
	$count = 0;
	foreach ($field_data as $field_name => $field_value) {
		if (!isset($table_info[$field_name])) { return false; }
		$field_type = strtoupper($table_info[$field_name]['DATA_TYPE']);
		if ($count) { $strsql .= ' and'; }
		if ($field_value == '') {
			$strsql .= " ({$field_name} = '' or ISNULL({$field_name}))";
		}
		else {
			$strsql .= " {$field_name} = ?";
			$params[] = $field_value;
			$params[0] .= (isset($mysql_bind_types[$field_type])) ? ($mysql_bind_types[$field_type]) : ('s');
		}
		$count++;
	}
	//print 'Debug:' . $strsql; print_array($params);
	return qdb_lookup($ds, $strsql, 'count', $params);
}

