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

//**************************************************************************
// Top Module Links
//**************************************************************************
$top_mod_links = array();
$tmp_link = add_url_params("{$this->page_url}sync_sets/", array("action" => "add"), true);
$top_mod_links["links"][] = array(
	"link" => $tmp_link, 
	"desc" => "Add a Data Sync Set", 
	"image" => xml_escape($add_image),
	'class' => 'btn-success'
);

//***************************************************************************
// Build SQL and pull records
//***************************************************************************
$strsql = "select * from data_sync_sets order by set_name";
$recs = qdb_exec("", $strsql, array());

//**************************************************************************
// Format Data
//**************************************************************************
foreach ($recs as $key => &$rec) {
	extract($rec);
	$edit_link = add_url_params("{$mod_base_url}sync_sets/", array("action" => "edit", "id" => $id));
	$delete_link = add_url_params("{$mod_base_url}sync_sets/", array("action" => "confirm_delete", "id" => $id));
	$items_link = add_url_params("{$mod_base_url}sync_sets/", array("action" => "items", "id" => $id));
	$view_link = "{$mod_base_url}{$id}/";
	$rec["actions"] = anchor($view_link, icon('fa fa-refresh'), array('class' => 'btn btn-primary'));
	$rec["actions"] .= anchor($items_link, icon('fa fa-list'), array('class' => 'btn btn-purple'));
	$rec["actions"] .= anchor($edit_link, $edit_image, array('class' => 'btn btn-info'));
	$rec["actions"] .= anchor($delete_link, $delete_image, array('class' => 'btn btn-danger'));
	if (isset($change_id) && $id == $change_id) { $change_row = $key; }

	//===================================================================
	// Set Name
	//===================================================================
	$rec['set_name'] = anchor("{$mod_base_url}{$id}/", $set_name);
}

//**************************************************************************
// Data Order
//**************************************************************************
$data_order = array();
$data_order["set_name"] = "Set Name";
$data_order["datasource_1"] = "Data Source #1";
$data_order["datasource_2"] = "Data Source #2";
$data_order["actions"] = "Actions";

//**************************************************************************
// Record Set List
//**************************************************************************
$table = new rs_list($data_order, $recs);
$table->empty_message("--");
if (isset($change_row)) {
	$table->set_row_attr($change_row, "class", "hl_change");
}
$table->identify("", "table table-striped rs rs-striped");

ob_start();
$table->render();
print div(ob_get_clean(), array('class' => 'table-responsive'));

