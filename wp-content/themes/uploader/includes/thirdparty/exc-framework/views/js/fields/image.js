jQuery( document ).ready( function($){
	
	var uploader =
	{
		ins: {},

		init: function()
		{
			$('body').on( 'click', '.exc-image-field-remove', uploader.removeImage );
			$('body').on( 'click', '.exc-image-upload-btn', uploader.uploadImage );
			
			$( '.exc-image-field').on( 'change', uploader.changeImage );

			// Dynamic Fields
			$( document ).on('exc-dynamic-add_row', function(e, row){
				$('.exc-image-field', row).each(function(){
					var $this = $(this);

					uploader._setup( $this.parent() );
					uploader.changeImage();
				});
			});
		},
		
		_setup: function( el )
		{
			uploader.ins = {};

			uploader.ins.el = $( el ).parent().hasClass('input-group') ? 
									$( el ).parent().parent() : $( el ).parent();

			uploader.ins.group = uploader.ins.el.children('.input-group:first');

			if ( ! uploader.ins.group.length )
			{
				uploader.ins.group = uploader.ins.el;
			}

			uploader.ins.field = uploader.ins.group.find('input.exc-image-field');
			
			uploader.ins.button = uploader.ins.group.find('.exc-image-upload-btn:first');
			uploader.ins.icon = uploader.ins.button.children('.fa:first');
			uploader.ins.class = uploader.ins.icon.attr('class');
			uploader.ins.preview = uploader.ins.el.find('.exc-preview');
			uploader.ins.removeBtn = uploader.ins.el.find('.exc-image-field-remove');

			uploader.ins.el.children('.alert').remove();
		},
		
		changeImage: function( e )
		{
			if ( e )
			{
				uploader._setup( $( this ).parent() );
			}

			var settings = uploader.ins;

			value = settings.field.val();
			
			if ( ! value )
			{
				uploader.removeImage();

			} else
			{
				settings.icon.attr('class', 'fa fa-spinner fa-spin');

				var image = $('<img />', {'src': value} );

				image.load( function(){
					settings.preview.html( image );
					settings.icon.attr( 'class', settings.class );
					settings.removeBtn.removeClass('hide');
				});

				image.error( function()
				{
					settings.preview.empty();
					settings.icon.attr( 'class', settings.class );
					settings.removeBtn.addClass('hide');
				});
			}
		},
		
		removeImage: function( e )
		{
			if ( e )
			{
				e.preventDefault();
				uploader._setup( $( this ).parent() );
			}

			uploader.ins.preview.empty();
			uploader.ins.field.val('');
			uploader.ins.removeBtn.addClass('hide');
			
			eXc.focus( uploader.ins.el );
		},
		
		uploadImage: function(e)
		{
			e.preventDefault();	
			
			uploader._setup( $( this ).parent() );

			var width = $(window).width() - 100,
				height = $(window).height() - 100;
				
			tb_show( exc_image_upload.i18n_title,
					'media-upload.php?referer=exc-image-field&type=image&TB_iframe=true&width=' + width + '&height=' + height + '&post_id=0', false);
			
			var org_editor = window.send_to_editor;

			window.send_to_editor = function(html)
			{
				html = $( html );

				if ( html.prop('tagName').toLowerCase() === 'img' )
				{
					uploader.ins.field.val( html.attr('src') );	
				} else 
				{
					uploader.ins.field.val( $( 'img', html ).attr('src') );	
				}
				
				uploader.changeImage();
				
				tb_remove();

				window.send_to_editor = org_editor;
			}
		}
	};
	
	uploader.init();
	
});