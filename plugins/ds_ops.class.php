<?php
//*****************************************************************************
//*****************************************************************************
/**
* Data Source Operations Plugin
*
* @package		phpDBAdmin
* @subpackage	Plugins
* @copyright	Copyright (c) Christian J. Clark
* @license		http://www.gnu.org/licenses/gpl-3.0.txt
* @link			http://www.emonlade.net/phpdbadmin/
**/
//*****************************************************************************
//*****************************************************************************
class ds_ops
{

	//*******************************************************************************
	/**
	* Get Database Tables
	* 
	* @param string Data Source
	**/
	//*******************************************************************************
	static public function get_tables($src)
	{
		//--------------------------------------------------------------
		// Set default result
		//--------------------------------------------------------------
		$db_tables = array();
		
		//--------------------------------------------------------------
		// Check if data source is valid
		//--------------------------------------------------------------
		if (!isset($_SESSION[$src])) {
			trigger_error("[db_tables] Error: Unknown Database Source!! ($src)");
		}
		else {
			//--------------------------------------------------------------
			// Database
			//--------------------------------------------------------------
			$db = $_SESSION[$src]['source'];
			
			//--------------------------------------------------------------
			// Schema
			//--------------------------------------------------------------
			$schema = (isset($_SESSION[$src]['schema'])) ? ($_SESSION[$src]['schema']) : (false);
			
			//--------------------------------------------------------------
			// Build SQL Statement based on DB Type
			//--------------------------------------------------------------
			if ($_SESSION[$src]['type'] == 'mysql' || $_SESSION[$src]['type'] == 'mysqli') {
				$strsql = "show tables from {$db}";
				$index = "Tables_in_{$db}";
				$value_index = "Tables_in_{$db}";
			}
			else if ($_SESSION[$src]['type'] == 'pgsql') {
				$strsql = "select DISTINCT(table_name) from information_schema.columns";
				$strsql .= " WHERE table_catalog = '{$db}'";
				if ($schema) {
					 $strsql .= " and table_schema = '{$schema}'";
				}
				$strsql .= " order by table_name";
				$index = "table_name";
				$value_index = "table_name";
			}
			
			//--------------------------------------------------------------
			// Get Tables
			//--------------------------------------------------------------
			if (isset($strsql)) {
				$data1 = new data_trans($src);
				$data1->data_query($strsql);
				$db_tables = $data1->data_key_val($index, $value_index);
			}
			else { $db_tables = array(); }
		}
		
		//--------------------------------------------------------------
		// Return Tables
		//--------------------------------------------------------------
		return $db_tables;
	}

	//*******************************************************************************
	/**
	* Get Table Fields
	* 
	* @param string Data Source
	* @param string Table
	**/
	//*******************************************************************************
	static public function get_table_fields($src, $table, $fields=false)
	{
		//--------------------------------------------------------------
		// Set default result
		//--------------------------------------------------------------
		$db_fields = false;
		
		//--------------------------------------------------------------
		// Check if data source is valid
		//--------------------------------------------------------------
		if (!isset($_SESSION[$src])) {
			trigger_error("[table_fields] Error: Unknown Database Source!! ($src)");
		}
		else {
			//--------------------------------------------------------------
			// Database
			//--------------------------------------------------------------
			$db = $_SESSION[$src]['source'];

			//--------------------------------------------------------------
			// Field List
			//--------------------------------------------------------------
			if (is_array($fields) && count($fields)) {
				$fields_in = "'" . implode("', '", $fields) . "'";
			}

			//--------------------------------------------------------------
			// Schema
			//--------------------------------------------------------------
			$schema = (isset($_SESSION[$src]['schema'])) ? ($_SESSION[$src]['schema']) : (false);
			
			//--------------------------------------------------------------
			// Build SQL Statement based on DB Type
			//--------------------------------------------------------------
			if ($_SESSION[$src]['type'] == 'mysql' || $_SESSION[$src]['type'] == 'mysqli') {
				$strsql = "show columns from {$table}";
				if (!empty($fields_in)) {
					$strsql .= " where Field IN ({$fields_in})";
				}
				$index = "Field";
			}
			else if ($_SESSION[$src]['type'] == 'pgsql') {
				$strsql = "SELECT * FROM information_schema.columns";
				$strsql .= " WHERE table_catalog = '{$db}'";
				if ($schema) {
					 $strsql .= " and table_schema = '{$schema}'";
				}
				$strsql .= " and table_name = '{$table}'";
				if (!empty($fields_in)) {
					$strsql .= " and column_name IN ({$fields_in})";
				}
				$strsql .= " order by column_name";
				$index = "column_name";
			}
			
			//--------------------------------------------------------------
			// Get fields
			//--------------------------------------------------------------
			$data1 = new data_trans($src);
			$data1->data_query($strsql);
			$db_fields = $data1->data_key_assoc($index);
		}
		
		//--------------------------------------------------------------
		// Return Fields
		//--------------------------------------------------------------
		return $db_fields;
	}

	//*******************************************************************************
	/**
	* Get Number Table Fields
	* 
	* @param string Data Source
	* @param string Table
	* @param array List of Fields (Optional)
	**/
	//*******************************************************************************
	static public function get_number_table_fields($src, $table, $fields=false)
	{
		$fields = self::get_table_fields($src, $table, $fields);
		if (is_array($fields)) {
			return count($fields);
		}
		return false;
	}

	//*******************************************************************************
	/**
	* Compare 2 Database Tables
	* 
	* @param string Data Source 1
	* @param string Table 1
	* @param string Data Source 2
	* @param string Table 2
	* @return Array Field Analysis
	*/
	//*******************************************************************************
	static public function table_analyze($src1, $table1, $src2, $table2)
	{
		// Table #1 Fields
		$db1_fields = self::get_table_fields($src1, $table1);
		$db1_type = (isset($_SESSION[$src1]['type'])) ? ($_SESSION[$src1]['type']) : (false);
			
		// Table #2 Fields
		$db2_fields = self::get_table_fields($src2, $table2);
		$db2_type = (isset($_SESSION[$src2]['type'])) ? ($_SESSION[$src2]['type']) : (false);
	
		// Set Database Specific Field Indices
		$field_indices = array();
		
		// PostgreSQL
		$field_indices['pgsql'] = array();
		$field_indices['pgsql']['type'] = 'udt_name';
		$field_indices['pgsql']['max_length'] = 'character_maximum_length';
		$field_indices['pgsql']['nullable'] = 'is_nullable';
		$field_indices['pgsql']['default'] = 'column_default';
	
		// MySQL
		$field_indices['mysql'] = array();
		$field_indices['mysql']['type'] = 'Type';
		$field_indices['mysql']['max_length'] = 'Type';
		$field_indices['mysql']['nullable'] = 'Null';
		$field_indices['mysql']['default'] = 'Default';
	
		// MySQLi
		$field_indices['mysqli'] = array();
		$field_indices['mysqli']['type'] = 'Type';
		$field_indices['mysqli']['max_length'] = 'Type';
		$field_indices['mysqli']['nullable'] = 'Null';
		$field_indices['mysqli']['default'] = 'Default';
	
		// Set Counter Defaults
		$master_field_list = array();
		$field_list = array();
		$totals = array(0, 0);
		$mismatches = 0;
	
		// DB #1 Tables
		foreach ($db1_fields as $key => $value) {
			if (isset($db2_fields[$key])) {
				$field_list[$key]['both'] = 1;
				$match = 1;
				
				// 1st Table
				$field_list[$key][$src1]['type'] = $value[$field_indices[$db1_type]['type']];
				$field_list[$key][$src1]['max_length'] = $value[$field_indices[$db1_type]['max_length']];
				$field_list[$key][$src1]['nullable'] = $value[$field_indices[$db1_type]['nullable']];
				$field_list[$key][$src1]['default'] = $value[$field_indices[$db1_type]['default']];
	
				// 2nd table
				$field_list[$key][$src2]['type'] = $db2_fields[$key][$field_indices[$db2_type]['type']];
				$field_list[$key][$src2]['max_length'] = $db2_fields[$key][$field_indices[$db2_type]['max_length']];
				$field_list[$key][$src2]['nullable'] = $db2_fields[$key][$field_indices[$db2_type]['nullable']];
				$field_list[$key][$src2]['default'] = $db2_fields[$key][$field_indices[$db2_type]['default']];
				
				if ($value[$field_indices[$db1_type]['type']] != $db2_fields[$key][$field_indices[$db2_type]['type']]) { $match = 0; }
				if ($value[$field_indices[$db1_type]['max_length']] != $db2_fields[$key][$field_indices[$db2_type]['max_length']]) { $match = 0; }
				if ($value[$field_indices[$db1_type]['nullable']] != $db2_fields[$key][$field_indices[$db2_type]['nullable']]) { $match = 0; }
                if ($value[$field_indices[$db1_type]['default']] != $db2_fields[$key][$field_indices[$db2_type]['default']]) {
					$def1 = $value[$field_indices[$db1_type]['default']];
					if (!is_null($def1)) {
	                    $def1 = strtolower($def1);
					}
					$def2 = $db2_fields[$key][$field_indices[$db2_type]['default']];
					if (!is_null($def2)) {
	                    $def2 = strtolower($def2);
					}
					if (!is_null($def1) && stripos($def1, 'current_timestamp') === false) {
						$match = 0;
					}
                    else if (!is_null($def2) && stripos($def2, 'current_timestamp') === false) {
                        $match = 0;
                    }
                }
	
				$field_list[$key]['match'] = $match;
				if ($match != 1) { $mismatches++; }
				unset($db2_fields[$key]);
				$totals[1]++;
			}
			else {
				$field_list[$key]['both'] = 0;
				$field_list[$key]['match'] = 0;
				$field_list[$key][$src1]['type'] = $value[$field_indices[$db1_type]['type']];
				$field_list[$key][$src1]['max_length'] = $value[$field_indices[$db1_type]['max_length']];
				$field_list[$key][$src1]['nullable'] = $value[$field_indices[$db1_type]['nullable']];
				$field_list[$key][$src1]['default'] = $value[$field_indices[$db1_type]['default']];
				$mismatches++; 
			}
			$totals[0]++;
		}
	
		// DB #2 Tables
		foreach ($db2_fields as $key => $value) {	
			$field_list[$key]['both'] = 0;
			$field_list[$key]['match'] = 0;
			$field_list[$key][$src2]['type'] = $value[$field_indices[$db2_type]['type']];
			$field_list[$key][$src2]['max_length'] = $value[$field_indices[$db2_type]['max_length']];
			$field_list[$key][$src2]['nullable'] = $value[$field_indices[$db2_type]['nullable']];
			$field_list[$key][$src2]['default'] = $value[$field_indices[$db2_type]['default']];
			$mismatches++;
			$totals[1]++;
		}
		
		$master_field_list['mismatches'] = $mismatches;
		$master_field_list['totals'] = $totals;
		$master_field_list['field_list'] = $field_list;
		
		ksort($master_field_list['field_list']);
		return $master_field_list;
	}

	//*******************************************************************************
	/**
	* Does Table Exist?
	* 
	* @param string Data Source
	* @param string Table
	**/
	//*******************************************************************************
	static public function does_table_exist($src, $table)
	{
		$table = addslashes($table);
		$strsql = "
			SELECT 
				count(table_name) as count
			FROM 
				INFORMATION_SCHEMA.TABLES
			WHERE
				table_name = '{$table}'
		";
		return qdb_lookup($src, $strsql, 'count');
	}
}

