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
//print_array($_POST);

//============================================================
// "Cancel" Pressed?
//============================================================
if (isset($button_0) && $button_0 == "Cancel") {
	redirect($this->page_url);
	return false;
}

//============================================================
// Validate Form Key
//============================================================
$do_trans = check_and_clear_form_key($this, "form_key", $form_key);
//$do_trans = true;

//============================================================
// Server Side Validation
//============================================================
include("ssv-replace.php");

//============================================================
// Successful Validation
//============================================================
if ($do_trans && $ssv_status) {

	//--------------------------------------------------------
	// Back Link
	//--------------------------------------------------------
	$back_link = $this->page_url;

	//--------------------------------------------------------
	// Check for Confirmations
	//--------------------------------------------------------
	if (!isset($confirm)) { $confirm = 0; }
	if (!isset($confirm2)) { $confirm2 = 0; }
	$confirm3 = (isset($button_1) && $button_1 == "Replace") ? (1) : (0);

	//--------------------------------------------------------
	// If all Confirmations Passed
	//--------------------------------------------------------
	if ($confirm && $confirm2 && $confirm3) {

		//--------------------------------------------------------
		// Escape Values
		//--------------------------------------------------------
		$sql_replace_value = addslashes($replace_value);

		//--------------------------------------------------------
		// Status Messages
		//--------------------------------------------------------
		//$msg = "Please Note: The following replace operations have been performed!";
		//add_gen_message($msg);
		$msg = "Replace Criteria: <em>[field] {$search_phrase}</em>, Replace with: <em>{$replace_value}</em>";
		add_gen_message($msg);

		//--------------------------------------------------------
		// Decode Selected Table-Column Pairs
		//--------------------------------------------------------
		$selected_replace_dec = json_decode($selected_replace);
		if ($selected_replace_dec) {
			ob_start();
			$curr_table = null;
			foreach ($selected_replace_dec as $key => $val) {
	
				//--------------------------------------------------------
				// Dissect Table / Field from Key
				//--------------------------------------------------------
				list($table, $field) = explode(":", $key);
	
				//--------------------------------------------------------
				// Check if Current Table has changed
				//--------------------------------------------------------
				if ($curr_table != $table) {
					if (!is_null($curr_table)) {
						print fieldset(legend($curr_table) . ob_get_clean(), array("class" => "table-responsive db_table_search"));
						ob_start();
					}
					$curr_table = $table;
				}
				
				//--------------------------------------------------------
				// Field
				//--------------------------------------------------------
				print p(icon('fa fa-caret-right') . strong($field));
				$strsql = "update {$table} set {$field} = '{$sql_replace_value}' where {$field} {$search_phrase}";
				print p(em($strsql), array("style" => "padding-left: 20px; margin: 5px;"));
	
				//--------------------------------------------------------
				// Perform SQL Query
				//--------------------------------------------------------
				if (!empty($test_run)) {
					add_warn_message(strong('Please Note:') . ' This was a Test run only! The below queries WERE NOT actually run.');
				}
				else {
					add_action_message(strong('Please Note:') . ' This was a Live run! The below queries WERE actually run.');
					qdb_list($ds1, $strsql);
				}
			}
		}
		print fieldset(legend($curr_table) . ob_get_clean(), array("class" => "table-responsive db_table_search"));
		
	}
	//--------------------------------------------------------
	// Failed Confirmations
	//--------------------------------------------------------
	else {
		redirect($this->page_url);
	}
}
//============================================================
// Page Refreshafter Initial Run
//============================================================
else if (!$do_trans) {
	redirect($this->page_url);
}
//============================================================
// Failed Server Side Validation
//============================================================
else if (!$ssv_status) {
	$prev_action = $action;
	$action = 'search';
	$this->action = $action;
	$ssv->display_fail_messages();
	include("search.php");
	return 0;
}

//============================================================
// Everything else, Redirect
//============================================================
//redirect($this->page_url);

