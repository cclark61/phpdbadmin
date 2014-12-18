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

//=================================================================
// Add-in JavaScript / CSS
//=================================================================
$this->add_js_file('/bower_components/ace-builds/src-noconflict/ace.js');
$this->add_js_file('query-runner.js');

//=================================================================
// Clear Previous Queries?
//=================================================================
if ($this->action == 'clear_prev_queries') {
	if (isset($_SESSION['query_runner']['prev_queries'])) {
		unset($_SESSION['query_runner']['prev_queries']);
	}
	redirect($this->page_url);
}

//=================================================================
// Flow Control
//=================================================================
switch ($this->action) {
        
	default:
		include("query_runner.php");
		break;
}

?>