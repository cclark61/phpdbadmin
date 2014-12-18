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

//***************************************************************************
// Set ID if blank
//***************************************************************************
if (!isset($id)) { $id = ""; }

//***************************************************************************
// Create object
//***************************************************************************
if ($pull_from_db) {
	$obj = new $mod_object();
	$obj->load($id);
	extract($obj->export());
}

//***************************************************************************
// Check for next action
//***************************************************************************
if (isset($next_action[$action])) {

	//---------------------------------------------------
    // Back Link
	//---------------------------------------------------
    $back_link = $this->page_url;

	//---------------------------------------------------
	// Flow Control
	//---------------------------------------------------
    switch ($action) {

		case "add":
			$form_label = "Add a {$item}";
			include("frm_main.php");
			break;

		case "edit":
			$form_label = "Edit a {$item}";
			include("frm_main.php");
			break;

		case "confirm_delete":
			$form_label = "Delete a {$item}";
			include("frm_confirm_delete.php");
			break;
    }
}
else {
    include("main.php");
}

