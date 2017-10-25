(function($){
	
	var xhr = '';
	window.embed_media = 
	{
		init: function()
		{
			$('#exc-embed-btn').on('click', embed_media.fetch);
			$(document).on('exc-form-submit', '.exc-form', embed_media.submit);
		},
		
		submit: function(e, data)
		{
			data.push({"name": "action", "value": "exc_embed_media_post"});
			data.push({"name": "security", "value": exc_embed_media.security});
			data.push({"name": "page", "value": adminpage});
			data.push({"name": "typenow", "value": typenow});
			data.push({"name": "pagenow", "value": pagenow});
			data.push({"name": "post_ID", "value": $('#post_ID').val()});
		},
		
		fetch: function(e)
		{
			e.preventDefault();
 			
			if(xhr && xhr.readyState !== 4) return;
			
			var $this = $(this),
				container = $this.parents('.exc-bootstrap:first'),
				icon = $this.children('i.fa'),
				data =
				{
					action: 'exc_embed_media',
					page: adminpage,
					typenow: typenow,
					pagenow: pagenow,
					security: exc_embed_media.security,
					post_ID: $('#post_ID').val(),
					code: $('#exc-embed-code').val()
				};

			container.find('.alert').remove();
			icon.addClass('fa-spin');

			xhr = $.post(ajaxurl, data, function(r){

				if( ! r.success)
				{
					$('#exc-embed-code').after( eXc.error(r.data) );
					//$( eXc.error(r.data) ).prependTo( container );
					
				}else
				{
					//container.children('.exc-embed-media').addClass('hide');
					$('#exc-embed-body').html( r.data )
				}
				
				icon.removeClass('fa-spin');
			}, 'json');

		}
	}
	
	$('#embed-media-button').on('click', function(e){
		
		e.preventDefault();
		
		eXc.dialog.open( { title: exc_embed_media.title, body: exc_embed_media.body } );
		
		embed_media.init();
	});
	
})(jQuery);