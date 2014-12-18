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
	'action' => 'table_analyze',
	'ds1' => $ds1,
	'ds2' => $ds2,
	'table1' => $table1,
	'table2' => $table2
));

//============================================================
// Analyze Table
//============================================================
$mfl = ds_ops::table_analyze($ds1, $table1, $ds2, $table2);
if (!$mfl['mismatches']) {
	add_gen_message('This table is the same in both databases.');
	redirect($back_link);
}

//============================================================
// Create Data
//============================================================
$table_comp_data = array();
$table_comp_data["page_url"] = $this->page_url;
$table_comp_data["table"] = $table1;
$table_comp_data["table2"] = $table2;
$table_comp_data["data_sources"] = array($ds1, $ds2);
$table_comp_data["totals"] = array(0, 0);
$table_comp_data["fields"] = $mfl["field_list"];

//============================================================
// Remove Fields that Match
//============================================================
foreach ($mfl["field_list"] as $field => $data) {
	if ($data["both"]) {
		unset($table_comp_data["fields"][$field]);
	}
	else {
		if (isset($data[$ds1])) { $table_comp_data["totals"][1]++; }
		else { $table_comp_data["totals"][0]++; }
	}
}

//============================================================
// Transform / Output
//============================================================
$xml = array2xml("table_resolve", $table_comp_data);
$xsl = $this->file_path . "/templates/phpdbadmin/db_compare.xsl";
xml_transform($xml, $xsl);
//print $xml;
