jQuery( document ).ready( function($){

	$('.widget_exc_user_widget').on('click', 'li a[data-toggle="collapse"]', function(e){

		var parent = $( this ).parents('.users-list-item:first');

		parent.toggleClass('hide');
	});

	$('.widget_exc_user_widget').on('click', 'li a.status-close', function(e){
		e.preventDefault();

		var li = $( this ).parents('li:first'),
			div = li.children('.users-list-item'),
			container = li.children('.collapse:first');

		div.find('a[data-toggle="collapse"]').trigger('click');
	});
});