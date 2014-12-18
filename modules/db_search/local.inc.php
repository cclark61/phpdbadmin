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
// Module Settings
//============================================================
$mod_title = "Search / Replace";
$mod_icon_class = 'fa fa-search';
$mod_desc = 'Search all tables in a data source for a given value. Optionally, you can replace matched values with another value.';
$mod_object = "";

//============================================================
// Search Data Types
//============================================================
$search_data_types = array(
	'string' => "Strings Only", 
	'numeric' => "Numeric Only", 
	'date' => 'Dates / Times Only'
);

//============================================================
// Search Types
//============================================================
$search_types = array(
	"IS", 
	"IS NOT", 
	"IS LIKE", 
	"IS NOT LIKE", 
	"STARTS WITH", 
	"ENDS WITH"
);

//============================================================
// Data Types
//============================================================
$data_types = array(

	//-------------------------------------------------------
	// String
	//-------------------------------------------------------
	'string' => array(
		"char",
		"varchar",
		"binary",
		"varbinary",
		'tinytext',
		'text',
		'mediumtext',
		'longtext',
		"enum",
		"set"
	),

	//-------------------------------------------------------
	// Numeric
	//-------------------------------------------------------
	'numeric' => array(
		"tinyint",
		"smallint",
		"int",
		"mediumint",
		"bigint",
		'decimal',
		'numeric',
		'float',
		'double',
		'bit'	
	),

	//-------------------------------------------------------
	// Date
	//-------------------------------------------------------
	'date' => array(
		"date",
		"datetime",
		"timestamp",
		"time",
		"year"
	),

	//-------------------------------------------------------
	// Blob
	//-------------------------------------------------------
	'blob' => array(
		'tinyblob',
		'blob',
		'mediumblob',
		'longblob'
	)

);

