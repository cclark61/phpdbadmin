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
// Validate Parameters
//============================================================
if (empty($ds1) || empty($ds2) || empty($table1) || empty($table2)) {
	redirect($this->page_url);
}

//============================================================
// Back Link
//============================================================
$back_link = add_url_params($this->page_url, array(
	'action' => 'db_analyze',
	'ds1' => $ds1,
	'ds2' => $ds2,
));

//============================================================
// Analyze Table
//============================================================
$mfl = ds_ops::table_analyze($ds1, $table1, $ds2, $table2);

//============================================================
// Top Module Links
//============================================================
if ($mfl['mismatches'] > 0) {
	$top_mod_links = array();
	$tmp_link = add_url_params($this->page_url, array(
		'action' => 'resolve_table',
		'ds1' => $ds1,
		'ds2' => $ds2,
		'table1' => $table1,
		'table2' => $table2
	), true);
	$top_mod_links["links"][] = array(
		"link" => $tmp_link, 
		"desc" => "Resolve table differences", 
		"image" => xml_escape(icon('fa fa-refresh')),
		'class' => 'btn-info'
	);
}

//============================================================
// Create Data
//============================================================
$table_comp_data = array();
$table_comp_data["page_url"] = $this->page_url;
$table_comp_data["table"] = $table1;
$table_comp_data["table2"] = $table2;
$table_comp_data["data_sources"] = array($ds1, $ds2);
$table_comp_data["totals"] = $mfl["totals"];
$table_comp_data["fields"] = $mfl["field_list"];

//============================================================
// Transform / Output
//============================================================
$xml = array2xml("table_overview", $table_comp_data);
$xsl = $this->file_path . "/templates/phpdbadmin/db_compare.xsl";
xml_transform($xml, $xsl);
//print $xml;
