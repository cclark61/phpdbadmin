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

//***************************************************
// Valid Data Sync Set?
//***************************************************
if (IS_VALID_SYNC_SET) {

	//============================================================
	// Pull Data Sync Set
	//============================================================
	$strsql = 'select * from data_sync_sets where id = ?';
	$sync_set = qdb_first_row('', $strsql, array('i', $sync_set_id));
	if (!$sync_set) {
		add_warn_message('Invalid Data Sync Set!');
		redirect($this->page_url);
	}
	//var_dump($sync_set);

	//============================================================
	// Pull Table Fields
	//============================================================
	$strsql = 'select * from data_sync_table_cols where data_sync_set_id = ?';
	$table_fields = qdb_exec('', $strsql, array('i', $sync_set_id));
	if (!$table_fields) {
		add_warn_message('There are no table fields defined for syncronization!');
		redirect($this->page_url);
	}
	//var_dump($table_fields);

	//============================================================
	// Build Table Fields List
	//============================================================
	$tables = array();
	foreach ($table_fields as $dss_data) {
		$tmp_table = $dss_data['ds_table'];
		$tmp_field = $dss_data['ds_table_col'];
		if (!isset($tables[$tmp_table])) {
			$tables[$tmp_table] = array();
		}
		$tables[$tmp_table][$tmp_field] = $tmp_field;
	}
	//var_dump($tables);

	//============================================================
	// Data Sources
	//============================================================
	$data_sources = array(
		$sync_set['datasource_1'] => $sync_set['datasource_1'], 
		$sync_set['datasource_2'] => $sync_set['datasource_2']
	);

}

