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
// Add-in JavaScript / CSS
//============================================================
$this->add_js_file('db_search.js');

//============================================================
// Flow Control
//============================================================
switch ($this->action) {

	case "search":
		include("search.php");
		break;

	case "replace":
		include("replace.php");
		break;
	
	default:
		include("frm-search-criteria.php");
		break;
}

