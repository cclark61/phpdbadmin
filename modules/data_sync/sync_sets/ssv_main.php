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

$ssv = new server_side_validation();
$ssv->add_check("set_name", "is_not_empty", "You must enter a set name.");
$ssv->add_check("datasource_1", "is_not_empty", "You must select a first data source.");
$ssv->add_check("datasource_2", "is_not_empty", "You must select a second data source.");
if ($datasource_1 != '' && $datasource_2 != '') {
	$ssv->add_check("datasource_1", "fields_not_match", "Data Source #1 and Data Source #2 cannot be the same.", 'datasource_2');
}
$ssv_status = $ssv->validate();
