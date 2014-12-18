//************************************************************************
//************************************************************************
// JQuery Initializer
//************************************************************************
//************************************************************************
$(document).ready(function()
{
	//****************************************************
	// Segments
	//****************************************************
	segment_0 = $('body').attr('data-segment_0');
	segment_1 = $('body').attr('data-segment_1');
	segment_2 = $('body').attr('data-segment_2');
	segment_3 = $('body').attr('data-segment_3');
	segment_4 = $('body').attr('data-segment_4');
	segment_5 = $('body').attr('data-segment_5');

	//****************************************************
	// Global Variables
	//****************************************************
	app_env = $('body').attr('env');
	curr_orientation = false;
	exact_orientation = false;
	loading = loading_message('Loading...');
	curr_url = cleanURL(window.location.href.toString().split(window.location.host)[1]);
	inner_dims = window.innerWidth + 'x' + window.innerHeight;

	//****************************************************
	// Detect Orientation Change (also does Device Check)
	//****************************************************
	window.addEventListener('orientationchange', doOnOrientationChange);
	doOnOrientationChange();

	//****************************************************
	// Date Picker
	//****************************************************
	if ( $(".datepicker").length ) {
		$( ".datepicker" ).datepicker();
	}

	//****************************************************
	// Selectable Category with Add New Ability
	//****************************************************
	$('select[name="category"]').change(function() {
		var this_val = $(this).val();
		if (this_val == '[+]') {
			$('input[name="new_category"]').show();
		}
		else {
			$('input[name="new_category"]').hide();
		}
	});

	//****************************************************
	// Filter Change Event
	//****************************************************
	if ( $(".filter").length ) {
		$(".filter").change(function() {
			window.location = $(this).val();
		});
	}

	//****************************************************
	// Update Form Markup Function
	//****************************************************
	update_form_markup();

});

//************************************************************************
//************************************************************************
// Functions
//************************************************************************
//************************************************************************

//=================================================================
// Initialize AJAX Forms Function
//=================================================================
function device_check()
{
	is_phone = ($('#device-check .visible-xs:visible').length) ? (true) : (false);
	is_tablet = ($('#device-check .visible-sm:visible').length) ? (true) : (false);
	is_desktop = ($('#device-check .visible-md:visible').length) ? (true) : (false);
	is_large_desktop = ($('#device-check .visible-lg:visible').length) ? (true) : (false);
}

//*********************************************************************
// Orientation Change Function
//*********************************************************************
function doOnOrientationChange()
{
	//----------------------------------------------------
	// Device Check
	//----------------------------------------------------
	device_check();

	//----------------------------------------------------
	// Set Orientation Variables
	//----------------------------------------------------
	switch (window.orientation) {  
    	case -90:
		case 90:
	  		curr_orientation = 'landscape';
	  		break; 

	  	default:
	        curr_orientation = 'portrait';
	        break; 
    }

	exact_orientation = window.orientation;

	//----------------------------------------------------
	// Secondary Orientation Change Function
	//----------------------------------------------------
	if (typeof orientationChange == 'function' ) { 
	    orientationChange(); 
	}
}

//************************************************************************
// Loading Message Function
//************************************************************************
function loading_message(msg)
{
	return '<div class="well"><strong>' + msg + '</strong><br/><img src="/img/ajax-loader.gif" /></div>';
}

//=================================================================
// Newline to Break Tag Function
//=================================================================
function nl2br (str, is_xhtml)
{
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

//************************************************************************
// Serialize Form fields Into JSON Function
//************************************************************************
(function( $ )
{
    $.fn.serializeJSON=function() {
        var json = {};
        jQuery.map($(this).serializeArray(), function(n, i){
            json[n['name']] = n['value'];
        });
        return json;
    };
})( jQuery );

//************************************************************************
// Update Form Markup
//************************************************************************
function update_form_markup()
{
	//****************************************************
	// Add "form-control" class to appropriate inputs
	//****************************************************
	$('input[type="text"], input[type="password"], select, textarea').addClass('form-control');

	//****************************************************
	// Change "control-group" to "form-group"
	//****************************************************
	$('form .control-group').each(function () {
		$(this).removeClass('control-group');
		$(this).addClass('form-group');
	});
}

