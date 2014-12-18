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

//************************************************************************
//************************************************************************
// Query Runner
//************************************************************************
//************************************************************************

//==============================================================
// Page Links
//==============================================================
$link = $this->page_url;
$link2 = add_url_params($this->page_url, array('action' => 'clear_prev_queries'));

//==============================================================
// Data Sources
//==============================================================
$datasrc_arr = array('') + $_SESSION['data_sources'];
$datasrc_arr = array_combine($datasrc_arr, $datasrc_arr);

//**************************************************************************
// Top Module Links
//**************************************************************************
$top_mod_links = array();
$top_mod_links["links"][] = array(
	"link" => $this->page_url, 
	"desc" => "Start Over", 
	"image" => xml_escape(icon('fa fa-undo')),
	'class' => 'btn-info'
);
$tmp_link = add_url_params($this->page_url, array('action' => 'clear_prev_queries'));
$top_mod_links["links"][] = array(
	"link" => $tmp_link, 
	"desc" => "Clear Previous Queries", 
	"image" => xml_escape(icon('fa fa-times')),
	'class' => 'btn-warning'
);

//**************************************************************************
// Run Query
//**************************************************************************
ob_start();
if (!empty($query) && $query != $base_query && isset($_POST['query'])) {
	if (!empty($ds)) {
		$results = qdb_list($ds, $query);
		//print_array($results);
		if ($results) {
			$results = rs_trim($results, true, true);
		}
		$_SESSION['query_runner']['prev_queries'][] = $query;
	}
	else {
		add_warn_message('No data source selected!');
	}
}
$query_output = ob_get_clean();

//**************************************************************************
// Previous Queries
//**************************************************************************
if (!empty($_SESSION['query_runner']['prev_queries'])) {
	ob_start();
	
	//===============================================================
	// Legend
	//===============================================================
	print legend('Previous Queries (Double click to Use)');

	//===============================================================
	// Display Previous Queries
	//===============================================================
	foreach (array_reverse($_SESSION['query_runner']['prev_queries']) as $prev_query) {
		print pre($prev_query);
	}
	print fieldset(ob_get_clean(), array('id' => 'prev-queries'));
}

//**************************************************************************
// Form
//**************************************************************************
$form = new form_too($this->page_url);
$form->attr('.', 'form-horizontal'); // wide-labels
$form->attr('class', 'query-runner');
$form->label('Current Query');

//--------------------------------------------------------
// Query
//--------------------------------------------------------
if (!isset($query)) {
	$curr_query = $base_query;
}
else {
	$curr_query = $query;
}
$ta_query = new textarea('query', $curr_query, 80, 10);
//$ta_query->attr('id', 'editor_ta');
$editor_ta = div($ta_query, array('style' => 'display: none;'));
$form->add_element(
	POP_TB::simple_control_group(false, div($curr_query, array('id' => 'editor')) . $editor_ta)
);

//--------------------------------------------------------
// Data Source
//--------------------------------------------------------
$datasrc_sel = new ssa("ds", $datasrc_arr);
if (isset($ds)) { $datasrc_sel->selected_value($ds); }
$form->add_element(
	POP_TB::simple_control_group("Data Source", $datasrc_sel)
);

//--------------------------------------------------------
// Save Button
// Render Form
//--------------------------------------------------------
$form->add_element(POP_TB::save_button('Run'));
$form->render();

//**************************************************************************
// Display Record Set
//**************************************************************************
ob_start();
if (isset($results)) {
	if (!$results) {
		print div("No results returned.", array('class' => 'alert alert-warning'));
		print $query_output;
	}
	else {

		//--------------------------------------------------
		// Number of Results
		//--------------------------------------------------
		$num_recs = count($results);
		print div("{$num_recs} records returned.", array('class' => 'alert alert-info'));

		//--------------------------------------------------
		// Print Results
		//--------------------------------------------------
		$t = new table();
		$t->set_attribute('class', 'table table-striped');
		$t->set_columns(count($results[0]));
		foreach ($results[0] as $col_name => $not_using) {
			$t->th($col_name);
		}

		foreach ($results as $row_key => $row) {
			foreach ($row as $col_key => $col_data) {
				$t->td($col_data);
			}
		}

		$t->render();
	}
}
else {
	print div("--", array('class' => 'well'));
}
print div(ob_get_clean(), array('id' => 'qr-records', 'class' => 'table-responsive')); // padding: 10px 0; overflow: scroll; width: 100%; height: 500px;

