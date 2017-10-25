jQuery( document ).ready(function($)
{
	"use strict";

	var comment_page = 2, xhr = '';
	
	$('#exc-load-comments').on('click', function(e){
		
		e.preventDefault();
		
		if(xhr && xhr.readyState !== 4)
		{
			xhr = $.post(ajax_url, {'action': 'exc-load-comments', 'security': $(this).data('security'), 'page': comment_page}, function(r){
				
				if(r.success)
				{
					if( !!r.data.next )
					{
						comment_page++;
					}
				}
				
			}, 'json');
		}
		
	});

	$('#image-slider').carousel({
	    interval: 4000
	}).on('slide.bs.carousel', function (e) {
        var nextH = $( e.relatedTarget ).height();
        var slide = $( this ).find('.active').parent().animate({ height: nextH }, 500);

        var id = parseInt( $('.item.active').data('slide-number') );
		  $('[id^=carousel-selector-]').removeClass('selected');
		  $('[id=carousel-selector-' + id + ']').addClass('selected');
    });


	$("[id^=carousel-selector-]").click( function(){
		var id_selector = $( this ).attr( "id" ),
			id = parseInt( id_selector.replace(/carousel-selector-/, '') );

		$('#image-slider').carousel( id );

		$('[id^=carousel-selector-]').removeClass('selected');

		$(this).addClass( 'selected' );
	});
});