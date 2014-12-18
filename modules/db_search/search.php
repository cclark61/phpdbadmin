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

if (!isset($show_detail)) { $show_detail = 0; }

//============================================================
// Back Link
//============================================================
$back_link = $this->page_url;

//============================================================
// Extract Search Criteria to Local Scope
// (Failed Validation for Replace Form)
//============================================================
if (!empty($prev_action) && $prev_action == 'replace') {
	extract($_SESSION['search_criteria']);
}
//============================================================
// Normal Search
//============================================================
else {
	//------------------------------------------------------
	// Validate Form
	//------------------------------------------------------
	include('ssv-search.php');
	if (!$ssv_status) {
		$ssv->display_fail_messages();
		include("frm-search-criteria.php");
		return false;
	}

	//------------------------------------------------------
	// Save Search Criteria to Session
	//------------------------------------------------------
	$_SESSION['search_criteria'] = $_POST;
}

//============================================================
// Top Module Links
//============================================================
$top_mod_links = array();
$tmp_link = $this->page_url;
$top_mod_links["links"][] = array(
	"link" => $tmp_link, 
	"desc" => "Search Criteria", 
	"image" => xml_escape(icon('fa fa-arrow-circle-left')),
	'class' => 'btn-info'
);

//============================================================
// Search Expression
//============================================================
$search_value = addslashes($search_value);
switch ($search_exp) {

	case 0:
		$search_phrase = "= '{$search_value}'";
		break;

	case 1:
		$search_phrase = "!= '{$search_value}'";
		break;

	case 2:
		$search_phrase = "like '%{$search_value}%'";
		break;

	case 3:
		$search_phrase = "not like '%{$search_value}%'";
		break;

	case 4:
		$search_phrase = "like '{$search_value}%'";
		break;

	case 5:
		$search_phrase = "like '%{$search_value}'";
		break;
}

//============================================================
// Display Search Criteria
//============================================================
$disp_criteria = strong("Data Source: ") . em($ds1);
$disp_criteria .= br() . strong("Search Data Type: ") . em(ucwords($search_data_type));
$disp_criteria .= br() . strong("Search Criteria: ") . em("[field] {$search_phrase}");
print div($disp_criteria, array("class" => "well well-sm"));

//============================================================
// Pull Tables for given Data Source
//============================================================
$tables = ds_ops::get_tables($ds1);
$field_detail_keys = array("Null", "Key", "Default", "Extra");

//============================================================
// Total Variables
//============================================================
$db_ttl_matches = 0;
$db_match_fields = 0;
$db_match_tables = 0;

//============================================================
// Search Each Table
//============================================================
foreach ($tables as $key => $curr_table) {

	//------------------------------------------------------
	// Pull Table Fields
	//------------------------------------------------------
	$table_fields = ds_ops::get_table_fields($ds1, $curr_table);

	//------------------------------------------------------
	// Create Table
	//------------------------------------------------------
	$t = new table();
	$t->attr("class", "table table-striped");

	//------------------------------------------------------
	// Table Headers
	//------------------------------------------------------
	$t->th_header("Field");
	if ($show_detail) {
		$t->set_columns(9);
		$t->attr("class", "detail");
		$t->th_header("Raw Type");
		$t->th_header("Type");
		$t->th_header("Size");
		$t->th_header("Nullable?");
		$t->th_header("Key");
		$t->th_header("Default");
		$t->th_header("Extra");
	}
	else {
		$t->attr("class", "no_detail");
	}
	$t->th_header("Matches");

	//------------------------------------------------------
	// Total Matches for this table
	//------------------------------------------------------
	$tbl_ttl_matches = 0;
	$tbl_match_fields = 0;
	
	//------------------------------------------------------
	// Tally Field Matches
	//------------------------------------------------------
	foreach ($table_fields as $key2 => $field) {

		//------------------------------------------------------
		// Parse Type and Size
		//------------------------------------------------------
		$tmp_raw_type = $field["Type"];
		$tmp_type_arr = explode("(", $tmp_raw_type);
		if (count($tmp_type_arr) > 1) {
			$tmp_type = $tmp_type_arr[0];
			$tmp_size = substr($tmp_type_arr[1], 0, strlen($tmp_type_arr[1]) - 1);
		}
		else {
			$tmp_type = $tmp_raw_type;
			$tmp_size = "--";
		}

		//------------------------------------------------------
		// Check Data Type against $search_data_type
		//------------------------------------------------------
		if ($search_data_type == 'string' && !in_array($tmp_type, $data_types['string']) && !in_array($tmp_type, $data_types['blob'])) { continue; }
		else if ($search_data_type == 'numeric' && !in_array($tmp_type, $data_types['numeric'])) { continue; }
		else if ($search_data_type == 'date' && !in_array($tmp_type, $data_types['date'])) { continue; }

		//------------------------------------------------------
		// Set display field name
		//------------------------------------------------------
		$field_name = $field["Field"];
		$tmp_index = "{$curr_table}:{$field_name}";
		$tmp_attrs = array(
			'type' => 'checkbox', 
			'class' => 'matched-column',
			'name' => "selected_field[{$tmp_index}]", 
			'data-tbl-col' => $tmp_index,
			'value' => 1
		);
		if (isset($replace_selected[$tmp_index])) {
			$tmp_attrs['selected'] = 'selected';
		}
		$disp_field_name = input($tmp_attrs) . ' ' . $field_name;

		//------------------------------------------------------
		// Count Matches
		//------------------------------------------------------
		$strsql = "select count({$field_name}) as count from {$curr_table} where {$field_name} {$search_phrase}";
		$tmp_count = qdb_lookup($ds1, $strsql, "count");

		//------------------------------------------------------
		// Did we get any Matches?
		//------------------------------------------------------
		if ($tmp_count > 0) {

			//------------------------------------------------------
			// Update Counters
			//------------------------------------------------------
			$db_match_fields++;
			$tbl_match_fields++;
			$tbl_ttl_matches += $tmp_count;
			$db_ttl_matches += $tmp_count;

			//------------------------------------------------------
			// Display Field Name
			//------------------------------------------------------
			$t->td($disp_field_name);
			
			//------------------------------------------------------
			// Disaply Other Field Details
			//------------------------------------------------------
			if ($show_detail) {
				$t->td($field["Type"]);
				$t->td($tmp_type);
				$t->td($tmp_size);

				//------------------------------------------------------
				// Fill Empty Detail Value Columns
				//------------------------------------------------------
				foreach ($field_detail_keys as $fk) {
					$tmp_val = "--";
					if (isset($table_fields[$key2][$fk]) && trim($table_fields[$key2][$fk]) != '') {
						$tmp_val = $table_fields[$key2][$fk];
					}
					$t->td($tmp_val);
				}
			}
			
			//------------------------------------------------------
			// Display Matches
			//------------------------------------------------------
			$t->td($tmp_count);
		}
	}
	
	if ($tbl_match_fields) {
		$db_match_tables++;
		ob_start();
		$t->render();
		print fieldset(legend($curr_table) . ob_get_clean(), array("class" => "table-responsive db_table_search"));
	}
}

//============================================================
// Grand Total Table
//============================================================
ob_start();

$t = new table();
$t->attr("class", "table table-striped");
$t->set_columns(4);

$t->th_header("Tables");
$t->th_header("Fields");
$t->th_header("Total Matches");

$t->td($db_match_tables);
$t->td($db_match_fields);
$t->td($db_ttl_matches);

$t->render();

print fieldset(legend("Grand Totals") . ob_get_clean(), array("class" => "table-responsive db_table_search"));

//============================================================
// Replace Form
//============================================================
include('frm-replace.php');

