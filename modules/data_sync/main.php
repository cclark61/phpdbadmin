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
// Add Page URL to Data
//==================================================
print input(array(
	'type' => 'hidden',
	'name' => 'page_url',
	'value' => $this->page_url
));

//==================================================
// Header
//==================================================
print div(strong($sync_set['set_name'] . ': ') . em($sync_set['datasource_1']) . ' / ' . em($sync_set['datasource_2']), array('class' => 'well'));

//==================================================
// Back Link
//==================================================
$back_link = $this->page_url;

//==================================================
// Add-in Javascript
//==================================================
$this->add_js_file('data-sync.js');
$jquery_ui = true;

//==================================================
// Pull Tables foreach Data Source
//==================================================
foreach ($data_sources as $key => $ds) {
	$db_tables[$key] = ds_ops::get_tables($ds);
	if (!$db_tables[$key]) {
		add_warn_message('One or both databases are empty.');
		return false;
	}
}

//****************************************************************
// Loop through Tables
//****************************************************************
foreach ($tables as $table => $fields) {
	
	//*******************************************************
	// Start Output Buffering
	//*******************************************************
	ob_start();
	//print_array($fields);

	//*******************************************************
	// Check Table Existance and Field Count
	//*******************************************************
	$proceed = true;
	foreach ($data_sources as $key => $ds) {
		if (isset($db_tables[$ds][$table])) {
			$num_fields = ds_ops::get_number_table_fields($data_sources[$key], $table, $fields);
			if ($num_fields != count($fields)) {
				print div("The fields in table '{$table}' in data source '{$ds}' do not match the list of configured fields.", array('class' => 'alert alert-warning'));
				$proceed = false;
			}
		}
		else {
			print div("Table '{$table}' in data source '{$ds}' does not exist.", array('class' => 'alert alert-warning'));
			$proceed = false;
		}
	}
	if (!$proceed) {
		print fieldset(legend($table) . ob_get_clean());
		continue;
	}

	//*******************************************************
	// Pull Data for current table for each data source
	//*******************************************************
	$field_list = implode(', ', $fields);
	$strsql = "SELECT {$field_list} FROM {$table}";
	$ds_data = array();
	foreach ($data_sources as $key => $ds_config) {
		$ds_data[$key] = qdb_list($ds_config, $strsql);
	}
	//print 'ds_data: '; print_array($ds_data);

	//*******************************************************
	// Build Hashed Data
	//*******************************************************
	$ds_hash_hashed = array();
	$ds_hash_hashed2 = array();
	foreach ($ds_data as $key => $tmp_data) {
		$ds_hash_hashed[$key] = array();
		$ds_hash_hashed2[$key] = array();

		foreach ($tmp_data as $key2 => $rec) {
			$tmp_hash = sha1(implode('', $rec));
			$ds_hash_hashed[$key][$key2] = $tmp_hash;
			$ds_hash_hashed2[$key][$tmp_hash] = $key2;
		}
	}
	//print 'ds_hash_hashed: '; print_array($ds_hash_hashed);
	//print 'ds_hash_hashed2: '; print_array($ds_hash_hashed2);
	
	if (count($ds_hash_hashed) > 1) {
		$common_entries = call_user_func_array('array_intersect', $ds_hash_hashed);
		//print 'common_entries: '; print_array($common_entries);

		foreach ($ds_data as $key => $tmp_data) {
			foreach ($common_entries as $ce_key) {
				unset($ds_hash_hashed2[$key][$ce_key]);
			}			
		}
		//print 'ds_hash_hashed2: '; print_array($ds_hash_hashed2);

		//-----------------------------------------------------------
		// Build Compare Data
		//-----------------------------------------------------------
		$compare_data = array();
		foreach ($ds_hash_hashed2 as $ds_key => $tmp_ds_data) {
			foreach ($tmp_ds_data as $hash => $rec_id) {
				$compare_data[$hash][$ds_key] = $rec_id;
			}
		}
		//print 'compare_data: '; print_array($compare_data);

		//-----------------------------------------------------------
		// Build Records to display
		//-----------------------------------------------------------
		$recs = array();
		foreach ($compare_data as $hash_key => $hash_rec) {
			$data_set = false;
			$rec = array();
			foreach ($data_sources as $ds_key => $ds_config) {
				if (isset($hash_rec[$ds_key])) {
					$rec_num = $hash_rec[$ds_key];
					if (!$data_set) {
						$rec = array_merge($ds_data[$ds_key][$rec_num], $rec);
						$data_set = true;
					}
					$rec[$ds_key] = 1;
				}
				else { $rec[$ds_key] = 0; }
			}
			$recs[] = $rec;
		}
		//print 'recs: '; print_array($recs);

	}
	else if (count($ds_hash_hashed) == 1) {
		$good_ds = array_keys($ds_hash_hashed);
		$good_ds = $good_ds[0];
		$recs = $ds_data[$good_ds];

		foreach ($recs as $key => &$rec) {
			foreach ($data_sources as $ds_key => $ds_config) {
				$rec[$ds_key] = ($ds_key == $good_ds) ? (1) : (0);
			}
		}
	}
	else {
		print xhe('p', 'No Records.', array('class' => 'msg_no_recs'));
		print div(ob_get_clean(), array('class' => 'well well-sm'));
		continue;
	}

	//*******************************************************
	// Display records
	//*******************************************************
	foreach ($recs as $key => &$rec) {
		foreach ($data_sources as $ds_key => $ds_config) {
			$tmp_class = (!$rec[$ds_key]) ? ('not_exists') : ('exists');
			$disp = (!$rec[$ds_key]) ? ('No') : ('Yes');
			$exists = strtolower($disp);
			$disp = span($disp);
			if ($exists == 'no') {
				$disp .= anchor('#', $add_image, array('class' => 'btn btn-success add_rec_to_ds'));
			}
			else if ($exists == 'yes') {
				$disp .= anchor('#', $delete_image, array('class' => 'btn btn-danger del_rec_from_ds'));
			}
			$rec[$ds_key] = div($disp, 
				array(
					'data-ds' => $ds_key, 
					'data-exists' => $exists,
					'class' => "ds_rec_status {$tmp_class}"
				)
			);
		}
	}

	//*******************************************************
	// Set Data Order (Programmatically)
	//*******************************************************
	$data_order = array();
	foreach ($fields as $field) {
		$data_order[$field] = $field;
	}
	foreach ($data_sources as $ds_key => $ds_config) {
		$data_order[$ds_key] = $ds_config;
	}

	//*******************************************************
	// Create Data Comparison Table
	//*******************************************************
	$rs_list = new rs_list($data_order, $recs);
	$rs_list->empty_message("--");
	$rs_list->label($table);
	$rs_list->identify("", "table table-striped data-sync-table");
	$rs_list->set_col_attr($sync_set['datasource_1'], 'class', 'indicator rs-col-10');
	$rs_list->set_col_attr($sync_set['datasource_2'], 'class', 'indicator rs-col-10');
	$rs_list->render();

	//*******************************************************
	// End Output Buffering, Display Output
	//*******************************************************
	print div(ob_get_clean(), array('class' => 'table-responsive', 'table_name' => $table));
}

