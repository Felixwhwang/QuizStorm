jQuery( function( $ )
{
	if(typeof(eXc) == 'undefined') eXc = {};
	
	eXc.jsEmbed = {
		init: function(){
			$('body').on('click', '.get-embed-code', eXc.jsEmbed.getCode);
			$('body').on('click', '.exc-clear-embed', eXc.jsEmbed.clearEmbed);
		},
		
		getCode: function(e){
			
			e.preventDefault();
			var group = $(this).parents('.input-group:first'),
				formGroup = $(group).parent(),
				input = $('input:first', group),
				data = {
						'action': 'exc_get_embed_code',
						'page': adminpage,
						'pagenow': pagenow,
						'typenow': typenow,
						//adminpage = 'post-php',
					};
				
				data[input.attr('name')] = input.val();
				
			$('.spinner', group).css('display', 'inline-block');
			
			$.post( ajaxurl, data, function(r)
			{
				//@TODO: change highlight effect
				$('.spinner', group).css('display', 'none');
				$('.alert', formGroup).remove();
				$('.exc-preview', formGroup).html(r.data);
			}, 'json');
		},
		
		clearEmbed: function(e){
			var parent = $(this).parent();
			//@TODO: delete highlight effect on parent
			$(parent).siblings('.input-group').children('input').val('');
			$(parent).empty();
		}
	};
	
	eXc.jsEmbed.init();
});