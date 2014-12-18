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
$datasrc_arr = array('') + $_SESSION['data_sources'];
$datasrc_arr = array_combine($datasrc_arr, $datasrc_arr);

//============================================================
// Remove LDAP Data Sources
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
// Enough Data Sources?
//============================================================
if (count($datasrc_arr) <= 1) {
	add_gen_message('There are not enough available MySQL data sources available to use this module.');
	return false;
}

//============================================================
// Enough Valid Data Sources?
//============================================================
if (count($datasrc_arr) > 1) {

	//--------------------------------------------------------
	// Create Form
	//--------------------------------------------------------
	$form_link = add_url_params($this->page_url, array('action' => 'db_analyze'));
	$form = new form_too($form_link);
	$form->attr('.', 'form-horizontal'); // wide-labels
	$form->label("Please Select Data Sources to Compare");
	
	//--------------------------------------------------------
	// Data Source #1
	//--------------------------------------------------------
	$form->add_element(
		POP_TB::simple_control_group("Data Source #1", new ssa("ds1", $datasrc_arr))
	);

	//--------------------------------------------------------
	// Data Source #2
	//--------------------------------------------------------
	$form->add_element(
		POP_TB::simple_control_group("Data Source #2", new ssa("ds2", $datasrc_arr))
	);

	//--------------------------------------------------------
	// Submit Button
	// Render Form
	//--------------------------------------------------------
	$form->add_element(POP_TB::save_button('Compare'));
	$form->render();
	
}
else {
	$msg = "There are not enough valid data sources to perform a comparison!";
	add_gen_message($msg);
}
