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
// Create Form
//**************************************************************************
$form = new form_too($mod_base_url);
$this->clear_mod_var("form_key");
$this->set_mod_var("form_key", $form->use_key());
$form->label($form_label);
$form->attr('.', 'form-horizontal'); // wide-labels

//==========================================================
// Hidden Variable
//==========================================================
$form->add_hidden("action", $next_action[$action]);
$form->add_hidden("id", $id);

//==========================================================
// Description
//==========================================================
$form->add_element(
	POP_TB::simple_control_group("Set Name", new textbox("set_name", $set_name, 40))
);

//==========================================================
// Data Sources
//==========================================================
$datasrc_arr = array('') + $_SESSION['data_sources'];
$datasrc_arr = array_combine($datasrc_arr, $datasrc_arr);

//--------------------------------------------------------
// Data Source #1
//--------------------------------------------------------
$ssa_ds1 = new ssa("datasource_1", $datasrc_arr);
$ssa_ds1->selected_value($datasource_1);
$form->add_element(
	POP_TB::simple_control_group("Data Source #1", $ssa_ds1)
);

//--------------------------------------------------------
// Data Source #2
//--------------------------------------------------------
$ssa_ds2 = new ssa("datasource_2", $datasrc_arr);
$ssa_ds2->selected_value($datasource_2);
$form->add_element(
	POP_TB::simple_control_group("Data Source #2", $ssa_ds2)
);

//**************************************************************************
// Save Button
// Render Form
//**************************************************************************
$form->add_element(POP_TB::save_button());
$form->render();

