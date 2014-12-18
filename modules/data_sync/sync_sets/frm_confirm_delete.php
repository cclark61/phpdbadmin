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

$message = "Are sure you want to delete the {$item} <em>{$set_name}</em> ?";

POP_TB::delete_form($this, array(
	'url' => $mod_base_url,
	'message' => $message,
	'hidden_vars' => array(
		'action' => $next_action[$action],
		'id' => $id
	),
	'form_label' => "Delete a Mileage Item"
));
