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

global $mysql_bind_types;

ob_start();

//================================================================
// Get Data Source
//================================================================
if (empty($ds)) {
	print "Failed: Unable to determine data source.";
	return false;
}

//================================================================
// Table Info
//================================================================
$table_info = get_mysql_table_info($ds, $table_name);

//================================================================
// Decode Field Data
//================================================================
$field_data2 = json_decode($field_data);

//================================================================
// Get Number of Record Matches
//================================================================
$matches = get_mysql_record_matches($ds, $table_name, $field_data2);

//================================================================
// Check for:
// 1) Valid Table/Data Structure
// 2) ONLY 1 record with this data already exists
//================================================================
if ($matches === false) {
	print "Failed: Passed field data does not match table structure.";
	return false;	
}
else if ($matches < 1) {
	print "Failed: No records that matched the passed data exist.";
	return false;
}
else if ($matches > 1) {
	print "Failed: More than 1 record that matched the passed data exists.";
	return false;
}

//================================================================
// Build DELETE SQL statement
//================================================================
$params = array('');
$strsql = "delete from {$table_name} where";
$count = 0;
foreach ($field_data2 as $field_name => $field_value) {
	$field_type = strtoupper($table_info[$field_name]['DATA_TYPE']);
	if ($count) { $strsql .= ' and'; }
	if ($field_value == '') {
		$strsql .= " ({$field_name} = '' or ISNULL({$field_name}))";
	}
	else {
		$strsql .= " {$field_name} = ?";
		$params[] = $field_value;
		$params[0] .= (isset($mysql_bind_types[$field_type])) ? ($mysql_bind_types[$field_type]) : ('s');
	}
	$count++;
}
//print $strsql; print_array($params);

//================================================================
// Execute Statement
//================================================================
$recs_affected = qdb_exec($ds, $strsql, $params);
if ($recs_affected !== false) {
	print "Success: {$recs_affected} deleted.";
}
else {
	print "Failed: Delete statement failed.";
}

//================================================================
// Output Status and Return
//================================================================
//print "Debug: ";
print ob_get_clean();

