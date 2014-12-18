//****************************************************
//****************************************************
// JQuery Initializer
//****************************************************
//****************************************************
$(document).ready(function()
{
	//****************************************************
	// Global Variables
	//****************************************************
	page_url = $('input[name="page_url"]').val();

	//****************************************************
	// Images
	//****************************************************
	add_image = '<i class="fa fa-plus"></i>';
	del_image = '<i class="fa fa-times"></i>';
	
	//****************************************************
	//****************************************************
	// Add Record to Data Source
	//****************************************************
	//****************************************************
	$(".add_rec_to_ds").click(function(e) {
		var ds = $(this).parent().attr('data-ds');
		var confirm1 = confirm('Are you sure you want to add this record to the "' + ds + '" data source?')

		if (confirm1) {

			//--------------------------------------------
			// Get field Data
			// Get Table Name
			//--------------------------------------------
			var field_data = get_record_data($(this));
			var table_name = get_table_name($(this));

			//--------------------------------------------
			// Add Record
			//--------------------------------------------
			if (field_data.length && table_name) {
				curr_elem = $(this);
				add_record(table_name, field_data, ds);
			}
		}

		return false;
	});

	//****************************************************
	//****************************************************
	// Delete Record from Data Source
	//****************************************************
	//****************************************************
	$(".del_rec_from_ds").click(function(e) {
		var ds = $(this).parent().attr('data-ds');
		var confirm1 = confirm('Are you sure you want to remove this record from the "' + ds + '" data source?')

		if (confirm1) {

			//--------------------------------------------
			// Get field Data
			// Get Table Name
			//--------------------------------------------
			var field_data = get_record_data($(this));
			var table_name = get_table_name($(this));

			//--------------------------------------------
			// Delete Record
			//--------------------------------------------
			if (field_data.length && table_name) {
				curr_elem = $(this);
				delete_record(table_name, field_data, ds);
			}
		}

		return false;
	});

});

//===================================================================================
//===================================================================================
// Defined Functions
//===================================================================================
//===================================================================================

//***************************************************************************
//***************************************************************************
// Flip Exists
//***************************************************************************
//***************************************************************************
function flip_exists(elem)
{
	var block = elem.parent();
	var ds = block.attr('data-ds');
	var exists = block.attr('data-exists');

	if (exists == 'yes') {
		block.attr('data-exists', 'no');
		block.removeClass('exists');
		block.addClass('not_exists');
		block.children('span').html('No');
		block.children('a').attr('class', 'add_rec_to_ds');
		block.children('a').html(add_image);
	}
	else {
		block.attr('data-exists', 'yes');
		block.removeClass('not_exists');
		block.addClass('exists');
		block.children('span').html('Yes');
		block.children('a').attr('class', 'del_rec_from_ds');
		block.children('a').html(del_image);
	}

	check_if_reconciled(block);
}

//***************************************************************************
//***************************************************************************
// Check If Row Has Been Reconciled Function
//***************************************************************************
//***************************************************************************
function check_if_reconciled(elem)
{
	var row = elem.parent().parent();
	var differences = 0;
	var first_text = '';

	row.children('.indicator').each(function(index) {
		var exists = $(this).children('div').attr('data-exists');
	    if (first_text == '') {
		    first_text = exists;
	    }
	    else {
		    if (first_text != exists) {
			    differences++;
		    }
	    }
	});

	if (differences == 0) {
		row.remove();
	}
}

//***************************************************************************
//***************************************************************************
// Get Record Data Function
//***************************************************************************
//***************************************************************************
function get_record_data(elem)
{
	var data_row = elem.parent().parent().parent();
	var headers_row = data_row.parent().parent().children('thead').children('tr');
	//alert(data_row.html() + headers_row.html());

	//--------------------------------------------------------
	// Build Data Elements
	//--------------------------------------------------------
	var data_elements = {};
	data_row.children('td').each(function(index) {
		if (!$(this).children('div').attr('data-ds')) {
			data_elements[index] = $.trim($(this).text());
		}
	});

	//--------------------------------------------------------
	// Build Header Elements
	//--------------------------------------------------------
	var header_elements = {};
	headers_row.children('th').each(function(index) {
		if (data_elements.hasOwnProperty(index)) {
			header_elements[index] = $.trim($(this).text());
		}
	});

	//--------------------------------------------------------
	// Merge Header and Data Elements into one object
	//--------------------------------------------------------
	var record_data = {};
	for (var key in data_elements) {
		record_data[header_elements[key]] = data_elements[key];
	}
	//print_array(record_data);
	var json_text = JSON.stringify(record_data, null, 2);

	return json_text;
}

//***************************************************************************
//***************************************************************************
// Get Table Name Function
//***************************************************************************
//***************************************************************************
function get_table_name(elem)
{
	return elem.parent().parent().parent().parent().parent().parent().parent().attr('table_name');
}

//***************************************************************************
//***************************************************************************
// Add Record Function
//***************************************************************************
//***************************************************************************
function add_record(table_name, record_data, ds)
{
	var params = {
		'table_name': table_name,
		'field_data': record_data,
		'ds': ds
	};
	var actions = {
		'success': function(return_data) {
			flip_exists(curr_elem);
		},
		'failed': function(return_data) {
			alert('The record could not be added due to the following reason: ' + return_data);
		}
	};
	ajax_trans(page_url + 'add_record/', params, actions);
}

//***************************************************************************
//***************************************************************************
// Delete Record Function
//***************************************************************************
//***************************************************************************
function delete_record(table_name, record_data, ds)
{
	var params = {
		'table_name': table_name,
		'field_data': record_data,
		'ds': ds
	};
	var actions = {
		'success': function(return_data) {
			flip_exists(curr_elem);
		},
		'failed': function(return_data) {
			alert('The record could not be deleted due to the following reason: ' + return_data);
		}
	};
	ajax_trans(page_url + 'delete_record/', params, actions);
	
}

