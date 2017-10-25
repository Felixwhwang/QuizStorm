jQuery(document).ready( function($){
	"use strict";
	
	var $colorpicker = {};
	
	eXc.colorpicker = $colorpicker = 
	{
		colorScheme: 'dark',
		color: '#ffffff',
		
		init: function()
		{
			$colorpicker.refresh();
			$( document ).on('eXc.colorpicker', $colorpicker.refresh);
		},
		
		onChange: function(hsb, hex, rgb, el, bySetColor)
		{
			$( el ).css('border-color','#' + hex);
			
			if( ! bySetColor)
			{
				$(el).val('#' + hex);
			}
		},
		
		onkeyUp: function(e)
		{
			$(this).colpickSetColor( this.value );
		},
		
		onSubmit: function(hsb, hex, rgb, el)
		{
			$(el).css('border-color', '#'+hex);
			$(el).colpickHide();
		},
		
		refresh: function()
		{
			$.each( $('.exc-colorpicker:not( .has-color-picker )'), function(i, v){
				
				var field = $(v),
					color = field.val() || $colorpicker.color;
				
				field.css('border-color', color);
				field.addClass('has-color-picker');
				
				field.colpick( { colorScheme: $colorpicker.colorScheme, color: color,
								onChange: $colorpicker.onChange, onSubmit: $colorpicker.onSubmit } ).keyup( $colorpicker.keyup );
			});
		},
	}
	
	$colorpicker.init();
});