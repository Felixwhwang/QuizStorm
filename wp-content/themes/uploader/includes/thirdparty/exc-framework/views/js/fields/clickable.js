( function( window, $, undefined ){

	var clickable = function( container )
	{
		this.container = $( container );

		if ( ! this.container.length ){ return; }

		this.items = this.container.find('.exc-clickable');
		this.activeChild = this.items.filter('.active');

		this.inputField = this.container.find('input').eq(0);
		this.max_limit = this.inputField.data('max_limit') || 0;

		this.groupClass = '.exc-form-field';

		this.init();
	}

	$.extend( clickable.prototype,
	{
		init: function()
		{
			this.bindEvents();

			var inputValue = this.inputField.val();

			if ( ! this.activeChild.length && inputValue )
			{
				this.activeChild = this.items.filter( '[data-id="' + inputValue + '"]' );
				
				this.activeChild.trigger('click');

			} else 
			{
				this.items.eq(0).trigger('click');
			}
		},

		itemClicked: function(e)
		{
			var self = e.data.self,
				$this = $(this),
				siblings = $this.siblings('.exc-clickable'),
				linkedFields = self.parseSelectors( $this.data('linked_fields') );

			if ( $this.hasClass('active') )
			{
				e.preventDefault();
				return;
			}

			$this.addClass('active');

			if ( 'undefined' !== typeof linkedFields )
			{	
				$.each( siblings, function()
				{
					//var fields = $( this ).data('linked_fields').replace(/([a-z0-9-_]+)/ig, '#$&');
					var fields = self.parseSelectors( $( this ).data('linked_fields') );

					if ( fields.length )
					{
						var parents = $( fields ).parents( self.groupClass );
						
						if ( parents.length ) // Hide field group
						{
							parents.addClass('hide');
							
						} else // Hide Fields
						{
							$( fields ).addClass( 'hide' );
						}
					}
				});
				
				//linkedFields = linkedFields.replace(/([a-z0-9-_]+)/ig, '#$&');
				
				if ( linkedFields.length )
				{
					var parents = $( linkedFields ).parents( self.groupClass );
					
					if ( parents.length )
					{
						parents.removeClass('hide');

					} else 
					{
						$( linkedFields ).removeClass('hide');
					}
				}
			}

			//@TODO: Limit and alert the number of selection

			if ( self.max_limit === 1 )
			{
				siblings.removeClass('active');
			}
			
			// change radio
			self.inputField.val( $this.data('id') );
			siblings.children('input').removeAttr('checked');
		},

		bindEvents: function()
		{
			this.container.on( 'click', '.exc-clickable', {self: this}, this.itemClicked );
		},

		unbindEvents: function()
		{
			this.container.off( 'click', '.exc-clickable', this.itemClicked );
		},

		parseSelectors: function( str )
		{
			str = $.trim( str.replace(/\s+/g, ' ') );

			var arr = [];

			if ( str.length )
			{
				var selectors = str.split(',');

				str = '';

				if ( selectors.length )
				{
					$.each( selectors, function(id, selector ){
						selector = $.trim( selector );

						if ( selector.length )
						{
							var chr = selector.charAt(0);

							if ( chr != '.' && chr != '#' )
							{
								selector = '#' + selector;
							}

							arr.push( selector );
						}
					});
				}
			}

			return arr.join(', ');
		}
	});

	$( document ).ready( function(){
		
		// Initiailize
		$('.exc-clickable-wrapper').each( function(){
			new clickable( this );
		});

		// Dynamic Fields
		$( document ).on('exc-dynamic-add_row', function(e, row){

			row.find('.exc-clickable-wrapper').each( function(){
				new clickable( this );
			});
		});
	});

	window.eXc['clickable'] = clickable;
})( window, jQuery );