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

if (!isset($replace_value)) { $replace_value = ''; }

//============================================================
// Create Form
//============================================================
$form = new form_too($this->page_url);
$this->clear_mod_var("form_key");
$this->set_mod_var("form_key", $form->use_key());
$form->label("Replace Matched Values");
$form->attr('.', 'form-horizontal wide-labels');
$form->attr('id', 'frm-replace');

//============================================================
// Hiddens
//============================================================
$form->add_hidden("action", "replace");
$form->add_hidden("ds1", $ds1);
$form->add_hidden("search_phrase", $search_phrase);
$form->add_hidden("search_value", $search_value);
$form->add_hidden("selected_replace", '');

//============================================================
// Validation Messages
//============================================================
$form->add_element(
	POP_TB::simple_control_group(false,
		div(
			'
				<strong>Please ensure the following:</strong><br/>
				&bull; You have selected on or more Table Columns to replace values in<br/>
				&bull; You have entered a value to replace matched values with<br/>
				&bull; You have checked BOTH confirmation checkboxes
			'
			, array(
				'class' => 'alert alert-warning validation-messages',
				'style' => 'display: none;'
			)
		)
	)
);

//============================================================
// Replace Value
//============================================================
$form->add_element(
	POP_TB::simple_control_group('Replace Matched Values with', new textbox("replace_value", $replace_value))
);

//============================================================
// Test Run Only
//============================================================
$txt_confirm = new checkbox("test_run", 1, true);
$form->add_element(
	POP_TB::simple_control_group(false, $txt_confirm . span("Perform test run only?", array('style' => 'color: blue;')))
);

//============================================================
// Confirmation #1
//============================================================
$txt_confirm = new checkbox("confirm", 1, false);
$form->add_element(
	POP_TB::simple_control_group(false, $txt_confirm . span("Do you want to perform this replace?", array('style' => 'color: red;')))
);


//============================================================
// Confirmation #2
//============================================================
$txt_confirm2 = new checkbox("confirm2", 1, false);
$form->add_element(
	POP_TB::simple_control_group(false, $txt_confirm2 . span("ARE YOU REALLY SURE?", array('style' => 'color: red;')))
);

//============================================================
// Render Form
//============================================================
$form->add_element(
	POP_TB::simple_control_group(false,
		array(
			button('Cancel', array('class' => 'btn btn-default', 'name' => 'button_0', 'value' => 'Cancel')),
			button('Replace', array('class' => 'btn btn-danger', 'name' => 'button_1', 'value' => 'Replace'))
		)
	)
);
$form->render();

