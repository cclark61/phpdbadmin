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
// Analyze Table
//============================================================
$mfl = ds_ops::table_analyze($ds1, $table1, $ds2, $table2);
//print_array($mfl);

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
// Add Checked Fields
//============================================================
foreach ($mfl["field_list"] as $field => $data) {
	if (!$data["both"] && isset($_POST[$field]) && $_POST[$field]) {
		if (isset($data[$ds1])) {
			$ds_to = $ds2;
			$ds_from = $ds1;
			$table_comp_data["totals"][1]++;
		}
		else {
			$ds_to = $ds1;
			$ds_from = $ds2;
			$table_comp_data["totals"][0]++;
		}
		
		$alter_sql = "alter table `{$table1}` add column `{$field}`";
		$alter_sql .= " " . $data[$ds_from]["type"];
		if (strtoupper($data[$ds_from]["nullable"]) == "YES") { $alter_sql .= " NULL"; }
		else { $alter_sql .= " NOT NULL"; }
		if (trim($data[$ds_from]["default"]) != "") {
			if ($data[$ds_from]["type"] == "timestamp" && $data[$ds_from]["default"] == "CURRENT_TIMESTAMP") {
				$alter_sql .= " default " . $data[$ds_from]["default"];
			}
			else { $alter_sql .= " default '" . $data[$ds_from]["default"] . "'"; }
		}
		
		$table_comp_data["fields"][$field]["direction"] = "[{$ds_from}] -> [{$ds_to}]";
		$table_comp_data["fields"][$field]["sql"] = $alter_sql;
		//print $alter_sql . " [$ds_to]<br/>\n";
		
		//-----------------------------------------------------
		// Alter Table
		//-----------------------------------------------------
		qdb_list($ds_to, $alter_sql);
		
	}
	else {
		unset($table_comp_data["fields"][$field]);
	}
}

//============================================================
// Transform / Output
//============================================================
$xml = array2xml("table_resolve_submit", $table_comp_data);
$xsl = $this->file_path . "/templates/phpdbadmin/db_compare.xsl";
xml_transform($xml, $xsl);
//print $xml;
