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
//var_dump($_POST);

//=======================================================================
// New Server Side Validation
//=======================================================================
$ssv = new server_side_validation();

$ssv->add_check("selected_replace", "is_not_empty", "You must select a matched values to replace.");
$ssv->add_check("replace_value", "is_not_empty", "You must enter a Replace Value.");
$ssv->add_check("confirm", "is_not_empty", "You must confirm the first confirmation for replace complete.");
$ssv->add_check("confirm2", "is_not_empty", "You must confirm the second confirmation for replace complete.");

$ssv_status = $ssv->validate();
