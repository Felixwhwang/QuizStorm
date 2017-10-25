jQuery(document).ready( function($){
	
	"use strict";
	
	var $switch = {};
	
	eXc.switch_field = $switch =
	{
		init: function()
		{
			$switch.refresh();
			
			$(document).on('ajaxComplete', $switch.refresh);
			$(document).on('eXc.switch_field', $switch.refresh);
			$( 'body' ).on('switchChange.bootstrapSwitch', 'input.exc-switch-field', $switch.onChange);
		},

		refresh: function(event, xhr, settings)
		{
			var switches = $(".exc-switch-field:not( .has-switch )");
			switches.bootstrapSwitch();
			switches.addClass('has-switch');
		},

		onChange: function(event, state)
		{
			$(this).attr('checked', state);
			
			if ( true === state )
			{
				$(this).val('on');
			} else {
				$(this).val('');
			}
		}
	};
	
	$switch.init();
});