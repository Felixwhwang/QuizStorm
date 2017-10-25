( function( window, $, undefined ){
	"use strict";

	var media_filter = function( container )
	{
		this.container = $( container );

		this.xhr = false;
		this.endResult = false;
		this.msnry;

		// Take all data parameters
		this.data = this.container.data();
		
		// @TODO: REMOVE ALL DATA ATTRIBUTE

		this.filterData = {
							action: this.data.action || 'exc_media_filter',
							security: this.data.security,
							pk: this.data.pk
						  };

		// @TODO: filtration class search
		this.content = this.container.find('.exc-media-content:first');

		// UL support
		if ( this.content.find( '.exc-media-list' ).length )
		{
			this.content = this.content.find('.exc-media-list:first');
		}

		this.loader = $('<div class="exc-media-loader loader"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>').hide();

		// All filter bars exists in this container
		this.filters =  this.container.find('.exc-media-filter');
		
		this.counter = this.container.find('.exc-media-count'); // All counters in this container
		this.searchBtn = $('#exc-media-search'); // @TODO: replace this code with search params
		
		// @TODO: send this information through data attribute
		this.isMasonry = this.data.masonry || false;

		this.emptyMarkup = $('<div />', { id: 'exc-empty-media' } ).hide();

		this.init();
	}

	$.extend( media_filter.prototype,
	{
		init: function()
		{
			if ( ! this.content.length ) {
				return;
			}

			this.filters.on( 'click', 'a', {self: this}, this.updateFilter );
		
			this.filters.on( 'submit', {self: this}, this.searchMedia );

			switch ( this.data.pagination )
			{
				case "load_on_scroll" :
					//this.pagination = $('<div class="exc-media-loader loader"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>').hide();
					this.pagination = $('<div/>');

					$( window ).on( 'scroll', {self: this}, this.pageScroll );
					$( window ).trigger( 'scroll' );

				break;

				case "navigation" :
					this.pagination = this.container.find('.wp-pagination');

					// Hide autoload filters
					this.filters.filter('.autoload-only').hide();
				break;

				case "loadmore_button" :
					this.pagination = this.container.find('.exc-load-more');

					// Trigger Load more on click
					this.pagination.on( 'click', {self: this}, this.loadMore );
					//this.loadMoreBtn.on( 'click', {self: this}, this.loadMore );

					// Hide autoload filters
					this.filters.filter('.autoload-only').hide();
				break;

				default :
					this.pagination = $('<div/>');
				break;
			}

			this.loader.insertAfter( this.content );

			// this.pagination = this.data.pagination;

			// if ( this.pagination === 'load_on_scroll' )
			// {
			// 	this.loader = $('<div class="exc-media-loader loader""><div class="double-bounce1"></div><div class="double-bounce2"></div></div>').hide();			
			// }
			
			// this.loadMoreBtn = this.container.find('.exc-load-more');

			// this.pagination	= this.container.find('.wp-pagination');

			// if ( this.data.pagination === 'load_on_scroll' )
			// {
			// 	this.filters.on( 'submit', {self: this}, this.searchMedia );

			// 	$( window ).on( 'scroll', {self: this}, this.pageScroll );
			// 	$( window ).trigger( 'scroll' );
				
			// } else
			// {
			// 	// Hide filters with support for autoload
			// 	$('.autoload-only').hide();
				
			// 	this.loadMoreBtn.on( 'click', {self: this}, this.loadMore );
			// }
			
			this.updateCounter();
			
			this.emptyMarkup.insertAfter( this.content );

			if ( this.isMasonry )
			{
				this.masonry();	
			}
		},
		
		searchMedia: function(e)
		{
			e.preventDefault();
			
			var self = e.data.self;

			self.endResult = false;
			
			self.filterData['s'] = self.searchBtn.find( 'input:first' ).val();
			self.applyFilter();
		},
		
		updateFilter: function( e )
		{
			e.preventDefault();
			
			var self = e.data.self;

			if( self.xhr && self.xhr.readyState !== 4 ) // abort previous request
			{
				self.xhr.abort();
			}
			
			var $this = $( this ),
				parent = $this.parents( '.exc-media-filter' ),
				type = parent.data('name');
			
			parent.children('a').removeClass('active');

			$this.addClass('active');
			
			if ( type )
			{
				self.filterData[ type ] = $this.data('id');
			}

			self.endResult = false; // Reset if we have no result
			
			self.applyFilter();
		},
		
		applyFilter: function()
		{
			if ( Masonry.data( this.content[0] ) )
			{
				this.content.masonry('destroy');
			}
			
			this.filterData['paged'] = 0;
			this.content.html( '' );
			
			this.loader.show();
			this.pagination.hide();

			this.fetchResult( true );
		},
		
		fetchResult: function( is_filter )
		{
			var self = this;

			this.filterData['offset'] = this.content.children().length;
			this.emptyMarkup.hide();
			
			this.xhr = $.post( ajaxurl, this.filterData, function(r)
			{
				if ( ! r.success )
				{			
					self.endResult = true;
					
					if ( is_filter )
					{
						self.updateCounter( 0 );
					}
					
					( r.data ) ? self.emptyMarkup.html( r.data ).show() : self.emptyMarkup.hide();

					self.loader.hide();
					self.pagination.hide();

				} else
				{
					var html = $( r.data.html );
					
					if ( true === is_filter )
					{
						self.content.html( html );

						if ( typeof r.data.class !== 'undefined' )
						{
							self.content.attr( 'class', r.data.class );
						}

						self.isMasonry = r.data.masonry
						
						if ( self.isMasonry )
						{
							self.masonry();
							
						} else if( Masonry.data( self.content[0] ) )
						{
							self.content.masonry('destroy');
						}
						
						self.loader.hide();
						self.pagination.show();

					} else
					{
						if ( ! Masonry.data( self.content[0] ) )
						{
							self.content.append( html );
							self.loader.hide();
							self.pagination.hide();

						} else
						{
							self.xhr.readyState = 1;

							html.imagesLoaded( function(){

								self.xhr = '';
								self.content.append( html );
		                    	self.content.masonry( 'appended', html, true );
		                    	self.loader.hide();
		                    	self.pagination.show();
		                    });
						}
					}
					
					if ( self.data.pagination === 'navigation' )
					{
						self.pagination.replaceWith( r.data.pagi );
					}

					self.updateCounter( r.data.counter );
				}
				
			}, 'json');
		},
		
		loadMore: function( e )
		{
			if ( e )
			{
				e.preventDefault();

				var self = e.data.self;
			} else
			{
				var self = this;
			}

			if ( ( self.xhr && self.xhr.readyState === 1 ) || !!self.endResult )
			{
				return;
			}
			
			self.loader.show();
			self.pagination.hide();
			
			self.fetchResult();
		},
		
		updateCounter: function( value )
		{
			if ( typeof value === 'undefined' )
			{
				value = this.data.counter;
			}
			
			this.counter.text( value );
		},
		
		pageScroll: function(e)
		{
			var self = e.data.self;

			if ( self.content.offset().top + self.content.height() <= $( window ).scrollTop() + $( window ).height() )
			{
				self.loadMore();
			}
		},
		
		masonry: function()
		{
			var self = this;

			// @TODO: add RTL support
			this.content.imagesLoaded( function() {
				self.msnry = self.content.masonry({ itemSelector: '.mason-item', isAnimated: true, animationOptions: { duration: 750, easing: 'linear', queue: false } });
			});
		}
	});
	
	$( document ).ready( function(){
		$('.exc-media-container').each( function(){
			new media_filter( this );
		});
	});

})( window, jQuery );