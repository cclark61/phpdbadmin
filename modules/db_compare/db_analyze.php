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
if (empty($ds1) || empty($ds2)) {
	redirect($this->page_url);
}

//============================================================
// Source #1 & #2 Tables
//============================================================
$db1_tables = ds_ops::get_tables($ds1);
$db2_tables = ds_ops::get_tables($ds2);
if (!$db1_tables && !$db2_tables) {
	add_gen_message('Both databases are empty.');
	return false;
}

//============================================================
// Start Master Table List / Totals
//============================================================
$master_table_list = array();
$totals = array(0, 0);

//============================================================
// DB #1 Tables
//============================================================
foreach ($db1_tables as $key => $value) {
	if (isset($db2_tables[$key])) {
		$mfl = ds_ops::table_analyze($ds1, $key, $ds2, $key);
		if ($mfl["mismatches"] > 0) { $master_table_list[$key] = array(2, 2); }
		else { $master_table_list[$key] = array(1, 1); }
		unset($db2_tables[$key]);
		$totals[1]++;
	}
	else {
		$master_table_list[$key] = array(1, 0);
	}
	$totals[0]++;
}

//============================================================
// DB #2 Tables
//============================================================
foreach ($db2_tables as $key => $value) {	
	$master_table_list[$key] = array(0, 1);
	$totals[1]++;
}

//============================================================
// Top Module Links
//============================================================
if ($totals[0] != $totals[1]) {
	$top_mod_links = array();
	$tmp_link = add_url_params($this->page_url, array(
		'action' => 'resolve',
		'ds1' => $ds1,
		'ds2' => $ds2
	), true);
	$top_mod_links["links"][] = array(
		"link" => $tmp_link, 
		"desc" => "Resolve database differences", 
		"image" => xml_escape(icon('fa fa-refresh')),
		'class' => 'btn-info'
	);
}

//============================================================
// Sort Tables
//============================================================
ksort($master_table_list);

//============================================================
// Set Data
//============================================================
$db_comp_data = array();
$db_comp_data["data_sources"] = array($ds1, $ds2);
$db_comp_data["totals"] = $totals;
$db_comp_data["page_url"] = $this->page_url;
$db_comp_data["tables"] = $master_table_list;

//============================================================
// Transform and Output
//============================================================
$xml = array2xml("db_overview", $db_comp_data);
$xsl = $this->file_path . "/templates/phpdbadmin/db_compare.xsl";
xml_transform($xml, $xsl);

