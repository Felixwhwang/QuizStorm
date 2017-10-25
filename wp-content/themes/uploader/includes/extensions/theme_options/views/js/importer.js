( function( window, $, undefined){

	var eXcDemoImporter = function( container )
	{
		// The parent Container
		this.container = $( container );

		// Bind Click Events
		this.bindEvents();
	}

	$.extend( eXcDemoImporter.prototype, {

		installDemo: function( e )
		{
			e.preventDefault();

			var target = $( this ),
				demoID = target.data('demo-id');

			if ( ! demoID )
			{
				return;
			}

			$.post( ajaxurl, { action: 'exc_theme_install_demo', demo_id: demoID }, function( r ){

			});

			console.log( e, target );
		},

		bindEvents: function()
		{
			this.container.on( 'click', 'a.exc-install-demo', this.installDemo );
		}
	});

	$( document ).ready( function(){
		$('.exc-theme-importer').each( function(){
			new eXcDemoImporter( this );
		});
	} );

})( window, jQuery);