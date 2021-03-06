<?php
//****************************************************************
/**
* phpDBAdmin
*
* @package		phpDBAdmin
* @author 		Christian J. Clark
* @copyright	Copyright (c) Christian J. Clark
* @license		http://www.gnu.org/licenses/gpl-3.0.txt
* @link			http://www.emonlade.net/phpdbadmin/
**/
//****************************************************************

//****************************************************************
//****************************************************************
// Create data source status table
//****************************************************************
//****************************************************************
$t = new table();
$num_cols = 6;
$t->set_columns($num_cols);
$t->set_attribute('class', 'table table-striped');
//$t->fieldset('Data Sources');

//****************************************************************
// Column Headers
//****************************************************************
$t->th('Name');
$t->th('Type');
$t->th('Server');
$t->th('Port');
$t->th('Source');
$t->th('Status');

//****************************************************************
// Data source rows
//****************************************************************
foreach ($_SESSION['data_sources'] as $ds) {
	if (!isset($_SESSION[$ds])) {
		$t->td($ds);
		$t->td('Invalid Data Source', $num_cols - 1);
	}
	else {
		$port = (!empty($_SESSION[$ds]['port'])) ? ($_SESSION[$ds]['port']) : (0);
		$source = (!empty($_SESSION[$ds]['source'])) ? ($_SESSION[$ds]['source']) : ('--');
		$t->td($ds);
		$t->td($_SESSION[$ds]['type']);
		$t->td($_SESSION[$ds]['server']);
		$t->td($port);
		$t->td($source);
		$status = is_service_available($_SESSION[$ds]['server'], $port);
		$status_msg = ($status) ? ('Online') : ('Offline');
		$status_class = ($status) ? ('success') : ('danger');
		$t->td(span($status_msg, array('class' => "label label-{$status_class}")));
	}
}

//****************************************************************
// Render
//****************************************************************
ob_start();
$t->render();
print div(ob_get_clean(), array('class' => 'table-responsive'));

