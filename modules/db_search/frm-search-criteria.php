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
// Data Sources
//============================================================
$datasrc_arr = get_data_sources_list();

//============================================================
// Remove Non-MySQL Data Sources
//============================================================
foreach ($datasrc_arr as $key => $value) {
	if (empty($_SESSION[$datasrc_arr[$key]])) {
		continue;
	}
    if (stripos($_SESSION[$datasrc_arr[$key]]["type"], 'mysql') === false) {
	    unset($datasrc_arr[$key]);
	}
}

//============================================================
// Enough Valid Data Sources?
//============================================================
if (count($datasrc_arr) > 1) {

	//--------------------------------------------------------
	// Previous Search Citeria Used?
	//--------------------------------------------------------
	if (!isset($ssv_status) && isset($_SESSION['search_criteria'])) {
		extract($_SESSION['search_criteria']);
	}

	//--------------------------------------------------------
	// Load Form Defaults
	//--------------------------------------------------------
	if (!isset($ds1)) { $ds1 = ''; }
	if (!isset($search_exp)) { $search_exp = ''; }
	if (!isset($search_value)) { $search_value = ''; }
	if (!isset($search_data_type)) { $search_data_type = ''; }

	//--------------------------------------------------------
	// Create Form
	//--------------------------------------------------------
	$form = new form_too($this->page_url);
	$form->label("Please Select Search Criteria");
	$form->attr('.', 'form-horizontal wide-labels');
	$form->add_hidden("action", "search");

	//--------------------------------------------------------
	// Data Source #1
	//--------------------------------------------------------
	$ssa_ds = new ssa("ds1", $datasrc_arr);
	$ssa_ds->selected_value($ds1);
	$form->add_element(
		POP_TB::simple_control_group("Data Source #1", $ssa_ds)
	);

	//--------------------------------------------------------
	// Search Value
	//--------------------------------------------------------
	$ssa_search_exp = new ssa("search_exp", $search_types);
	$ssa_search_exp->selected_value($search_exp);
	$txt_search_value = new textbox("search_value", $search_value);
	$form->add_element(
		POP_TB::simple_control_group("Search Value", array($ssa_search_exp, $txt_search_value))
	);

	//--------------------------------------------------------
	// Search Type
	//--------------------------------------------------------
	$ssa_search_type = new ssa("search_data_type", $search_data_types);
	$ssa_search_type->selected_value($search_data_type);
	$ssa_search_type->add_blank();
	$form->add_element(
		POP_TB::simple_control_group("Search Type", $ssa_search_type)
	);

	//--------------------------------------------------------
	// Show Field Details?
	//--------------------------------------------------------
	$form->add_element(
		POP_TB::simple_control_group("Show Field Details", new checkbox("show_detail", 1, false))
	);

	//--------------------------------------------------------
	// Submit Button
	// Render Form
	//--------------------------------------------------------
	$form->add_element(POP_TB::save_button('Search'));
	$form->render();

}
else {
	$msg = "There are not enough valid data sources to perform a search!";
	add_gen_message($msg);
}

