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

//**************************************************************************
// Check for form key
//**************************************************************************
$do_trans = check_and_clear_form_key($this, "form_key", $form_key);

//**************************************************************************
// Server Side Validation
//**************************************************************************
if ($action == "delete") { $ssv_status = true; }
else { include("ssv_main.php"); }

//**************************************************************************
// Successful Validation
//**************************************************************************
if ($do_trans && $ssv_status) {

	//=========================================================
	// Create new object
	//=========================================================
	if (!isset($id)) { $id = ""; }
	$obj = new $mod_object();
	$obj->import();
	$obj->no_save("id");
	//$obj->print_only();

	//=========================================================
	// Flow Control
	//=========================================================
	switch ($action) {

	    case "insert":
	        $change_id = $obj->save();
	        add_action_message("The {$litem} was added successfully!");
	        break;

	    case "update":
	        $obj->save($id);
	        $change_id = $id;
	        add_action_message("The {$litem} was updated successfully!");
	        break;

	    case "delete":
	    	if (isset($button_1) && $button_1 == "Delete") {
	        	$obj->delete($id);
    	    	add_action_message("The {$litem} was deleted successfully!");
	        }
        	else {
        		add_warn_message("The {$litem} was not deleted.");
        	}
        	break;
	}

}
//**************************************************************************
// Failed Server Side Validation
//**************************************************************************
else if (!$ssv_status) {
	$action = ($this->action == "insert") ? ("add") : ("edit");
	$this->action = $action;
	$ssv->display_fail_messages();
	$pull_from_db = false;
	include("form_controller.php");
	return 0;
}

//**************************************************************************
// Everything else, Redirect
//**************************************************************************
$redirect_url = $this->page_url;
if (!empty($change_id)) {
	$redirect_url = add_url_params($redirect_url, array('change_id' => $change_id));
}
redirect($redirect_url);
