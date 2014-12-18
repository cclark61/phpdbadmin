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

//var_dump($_POST);

//============================================================
// Empty Table-Field List? Redirect...
//============================================================
if (empty($selected_tbl_flds)) {
	redirect($this->page_url);
}

//============================================================
// Pull Data Sync Set
//============================================================
$strsql = 'select * from data_sync_sets where id = ?';
$rec = qdb_first_row('', $strsql, array('i', $id));
if (!$rec) {
	add_warn_message('Invalid Data Sync Set!');
	redirect($this->page_url);
}

//============================================================
// Remove Previous Records
//============================================================
$strsql = 'delete from data_sync_table_cols where data_sync_set_id = ?';
qdb_exec('', $strsql, array('i', $id));

//============================================================
// Insert Table Fields
//============================================================
$strsql = '
	insert into data_sync_table_cols 
		(data_sync_set_id, ds_table, ds_table_col) 
	values 
		(?, ?, ?)
';
foreach ($selected_tbl_flds as $tbl_fld => $value) {
	list($table, $field) = explode(':', $tbl_fld);
	if ($value) {
		qdb_exec('', $strsql, array('iss', $id, $table, $field));
	}
}

//============================================================
// Redirect
//============================================================
add_action_message('Table fields have been saved successfully.');
redirect($this->page_url);

