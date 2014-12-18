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
// Empty ID? Redirect...
//============================================================
if (empty($id)) { redirect($this->page_url); }

//============================================================
// Pull Data Sync Set
//============================================================
$strsql = 'select * from data_sync_sets where id = ?';
$rec = qdb_first_row('', $strsql, array('i', $id));
if (!$rec) {
	add_warn_message('Invalid Data Sync Set!');
	redirect($this->page_url);
}
//var_dump($rec);

//============================================================
// Pull Database Tables
//============================================================
$db1_tables = ds_ops::get_tables($rec['datasource_1']);
$db2_tables = ds_ops::get_tables($rec['datasource_2']);
if (!$db1_tables || !$db2_tables) {
	add_warn_message('One or more databases are empty.');
	redirect($this->page_url);
}

//============================================================
// Build a Master Table List
//============================================================
$master_table_list = array();
foreach ($db1_tables as $tbl) {
	if (isset($db2_tables[$tbl])) {
		$master_table_list[$tbl] = $tbl;
	}
}
//var_dump($master_table_list);

//============================================================
// Are there matching tables?
//============================================================
if (!$master_table_list) {
	add_warn_message('There are no matching table between the two selected databases.');
	redirect($this->page_url);
}

//============================================================
// Create Lists Fields
//============================================================
$table_fields1 = array();
$table_fields2 = array();
foreach ($master_table_list as $tbl) {
	$table_fields1[$tbl] = ds_ops::get_table_fields($rec['datasource_1'], $tbl);
	$table_fields2[$tbl] = ds_ops::get_table_fields($rec['datasource_2'], $tbl);
}
//var_dump($table_fields1, $table_fields2);

//============================================================
// Build a Master Table-Field List
//============================================================
$master_table_field_list = array();
$matching_fields = 0;
foreach ($table_fields1 as $tbl => $tbl_fields) {
	$master_table_field_list[$tbl] = array();
	foreach ($tbl_fields as $field => $field_data) {
		if (isset($table_fields2[$tbl][$field])) {
			$master_table_field_list[$tbl][] = $field;
			$matching_fields++;
		}
	}
}
//var_dump($master_table_field_list);

//============================================================
// Are there Matching Fields?
//============================================================
if (!$matching_fields) {
	add_warn_message('There are no matching table fields between the two selected databases.');
	redirect($this->page_url);	
}

//============================================================
// Header
//============================================================
print h3($rec['set_name'], array('class' => 'data-sync-header'));
print div(icon('fa fa-info-circle') . 'Please select the table fields to use for syncronization.', array('class' => 'well well-sm'));

//============================================================
// Back Link
//============================================================
$back_link = $this->page_url;

//============================================================
// Pull Previous Records
//============================================================
$strsql = 'select concat(ds_table, ":", ds_table_col) as chk_index from data_sync_table_cols where data_sync_set_id = ?';
$prev_selected = qdb_exec('', $strsql, array('i', $id), 'chk_index:chk_index');

//============================================================
// Start Buffering for Form
//============================================================
ob_start();

//============================================================
// Display Table Fields
//============================================================
foreach ($master_table_field_list as $tbl_name => $tbl_field_list) {
	if (!$tbl_field_list) { continue; }
	ob_start();
	foreach ($tbl_field_list as $field) {
		$tmp_index = "{$tbl_name}:{$field}";
		$chk_attrs = array(
			'type' => 'checkbox', 
			'name' => "selected_tbl_flds[$tmp_index]", 
			'value' => 1
		);
		if (isset($prev_selected[$tmp_index])) {
			$chk_attrs['checked'] = 'checked';
		}
		print li(input($chk_attrs) . ' ' . $field);
	}
	print fieldset(legend($tbl_name) . ul(ob_get_clean(), array('class' => 'field-list')));
}

//============================================================
// Submit Button
//============================================================
print div(button('Save', array('type' => 'submit', 'class' => 'btn btn-primary')), array('class' => 'well well-sm'));

//============================================================
// Output Form
//============================================================
print form(ob_get_clean(), array(
	'method' => 'post',
	'action' => add_url_params("{$mod_base_url}sync_sets/", array("action" => "save_items", "id" => $id))
));

