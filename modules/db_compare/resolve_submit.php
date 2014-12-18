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

//============================================================
// Start Master Table List / Totals
//============================================================
$master_table_list = array();
$totals = array(0, 0);

//============================================================
// DB #1 Tables
//============================================================
foreach ($db1_tables as $key => $value) {
	if (!isset($db2_tables[$key]) && isset($_POST[$key]) && $_POST[$key]) {
		$master_table_list[$key] = array(1, 0);
		$totals[1]++;
		$strsql = "SHOW CREATE TABLE {$key}";
		$creates = qdb_list($ds1, $strsql);
		if (count($creates) > 0) {
			$create_sql = $creates[0]["Create Table"];
			//print "$create_sql<br/><br/>\n";
			qdb_list($ds2, $create_sql);
		}
	}
	else { unset($db2_tables[$key]); }
}

//============================================================
// DB #2 Tables
//============================================================
foreach ($db2_tables as $key => $value) {
	if (!isset($db1_tables[$key]) && isset($_POST[$key]) && $_POST[$key]) {
		$master_table_list[$key] = array(0, 1);
		$totals[0]++;
		$strsql = "SHOW CREATE TABLE {$key}";
		$creates = qdb_list($ds2, $strsql);
		if (count($creates) > 0) {
			$create_sql = $creates[0]["Create Table"];
			//print "$create_sql<br/><br/>\n";
			qdb_list($ds1, $create_sql);
		}
	}
}

//============================================================
// Sort Tables
//============================================================
ksort($master_table_list);

//============================================================
// Create Tables
//============================================================
$db_comp_data = array();
$db_comp_data["data_sources"] = array($ds1, $ds2);
$db_comp_data["totals"] = $totals;
$db_comp_data["page_url"] = $this->page_url;
$db_comp_data["tables"] = $master_table_list;

//============================================================
// Transform / Output
//============================================================
$xml = array2xml("resolve_submit", $db_comp_data);
$xsl = $this->file_path . "/templates/phpdbadmin/db_compare.xsl";
xml_transform($xml, $xsl);

