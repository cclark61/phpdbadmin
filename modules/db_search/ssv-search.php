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

//=======================================================================
// New Server Side Validation
//=======================================================================
$ssv = new server_side_validation();
$ssv->add_check("ds1", "is_not_empty", "You must select a Data Source.");
$ssv->add_check("search_value", "is_not_empty", "You must enter a Search Value.");
$ssv->add_check("search_data_type", "is_not_empty", "You must select a Search Type.");
$ssv_status = $ssv->validate();

