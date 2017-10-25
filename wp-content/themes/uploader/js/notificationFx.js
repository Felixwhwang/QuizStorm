/**
 * notificationFx.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2014, Codrops
 * http://www.codrops.com
 */

(function( $ ){
	"use strict";

	var notifications = {};

	var notification = function( options )
	{
		var defaults =
		{
			// element to which the notification will be appended
			// defaults to the document.body
			wrapper : document.body,
			// the message
			message : '',
			// layout type: growl|attached|bar|other
			layout : 'attached',
			// effects for the specified layout:
			// for growl layout: scale|slide|genie|jelly
			// for attached layout: flip|bouncyflip
			// for other layout: boxspinner|cornerexpand|loadingcircle|thumbslider
			// ...
			effect : 'bouncyflip',
			// notice, warning, error, success
			// will add class ns-type-warning, ns-type-error or ns-type-success
			type : 'notice',
			// if the user doesn´t close the notification then we remove it 
			// after the following time
			ttl : 5000,

			icon : '',
			id : '',
			insertion: 'replace',
			// callbacks
			onClose : function() { return false; },
			onOpen : function() { return false; }
		}, 

		$this = this;

		this.options = $.extend( defaults, options );
		
		var support = { animations : Modernizr.cssanimations },
						animEndEventNames = 'webkitAnimationEnd oAnimationEnd MSAnimationEnd animationend';
						
		$.extend( notification.prototype,
		{
			init: function()
			{
				this.ntf = $( '<div />' );
				this.ntf.attr('class', 'ns-box ns-' + this.options.layout + ' ns-effect-' + this.options.effect + ' ns-type-' + this.options.type );
				this.ntf.attr('id', 'ns-box-' + this.options.id );

				var dom = this.insertChild( this.options );

				//var strinner = $( '<div />', {class: "ns-box-inner", html: this.options.message } );//'<div class="ns-box-inner">';
				//this.strinner = strinner.prependTo( this.ntf );

				this.ntf.append('<span class="ns-close" data-id=""></span>');

				this.ntf.prependTo( this.options.wrapper );

				/*if ( this.options.ttl )
				{
					this.dismissttl = setTimeout( function() {
						if( $this.active )
						{
							$this.dismiss();
						}
						
					}, this.options.ttl );
				}*/

				this.bindEvents();

				// show the notification
				if ( this.options.id )
				{
					notifications[ this.options.id ] = this;
				}

				//console.log( this.ntf.attr('class') );
			},

			show: function()
			{
				this.active = true;

				this.ntf.removeClass('ns-hide');
				this.ntf.addClass('ns-show');

				this.options.onOpen();
			},

			insertChild: function( options )
			{
				var html = $( '<div />', {class: "ns-box-inner", html: '<p>' + options.message + '</p>'} ),
					dom;

				options = $.extend( {}, { 'insertion' : 'replace', ttl: this.options.ttl, icon: '' }, options );

				if ( this.ntf.children('.ns-box-inner').length === 0 )
				{
					dom = html.prependTo( this.ntf );
				} else if ( options['insertion'] === 'prepend')
				{
					dom = html.insertBefore( this.ntf.children('.ns-box-inner:first') );
				}
				else if ( options['insertion'] === 'append' )
				{
					dom = html.insertAfter( this.ntf.children('.ns-box-inner:last') );
				} else // default replace it
				{
					if ( $this.options.type !== options.type )
					{
						$this.ntf.removeClass('ns-type-' + $this.options.type);
						$this.ntf.addClass('ns-type-' + options.type);
					}
					
					notifications[ options.id ].ntf.children('.ns-box-inner:first').replaceWith( html );
					dom = notifications[ options.id ].ntf.children('.ns-box-inner:first');
				}

				if ( options.icon )
				{
					$('<span />', {class: options.icon} ).prependTo( dom );
				}

				if ( typeof options.ttl !== 'undefined' && options.ttl > 0 )
				{
					setTimeout( function( )
					{
						if ( $this.ntf.children('.ns-box-inner').length <= 1 )
						{
							$this.ntf.children('.ns-close').trigger('click');
							//self.dismiss( $this );
						} else
						{
							dom.remove();
						}
						
					}, options.ttl );
				}

				return dom;
			},

			dismiss: function( e )
			{
				$this.active = false;
				
				$this.ntf.removeClass( 'ns-show' );
				setTimeout( function() {

					$this.ntf.addClass( 'ns-hide' );
					
					// callback
					$this.options.onClose();

				}, 25 );

				// Bind this event like firefox
				function onAnimationEnd( e )
				{
					$this.ntf.off('click', '.ns-close');

					if ( $this.options.id )
					{
						delete notifications[ $this.options.id ];
					}

					if( support.animations )
					{
						if( e.target !== $this.ntf[0] )
						{
							return false;
						}

						$this.ntf.off( animEndEventNames );
					}

					$this.ntf.remove();
				}

				if( support.animations )
				{
					$this.ntf.one( animEndEventNames, onAnimationEnd );

				} else
				{
					onAnimationEnd();
				}
			},

			bindEvents: function()
			{
				this.ntf.on( 'click', '.ns-close', this.dismiss );
			}
		});

		this.init( );
	};

	eXc.notification = function( options )
	{
		var id = ( typeof options['id'] !== 'undefined' ) ? options['id'] : '';
		
		if ( typeof notifications[ id ] !== 'undefined' )
		{
			notifications[ id ].insertChild( options );
			return notifications[ id ];
		}

		var ntf = new notification( options );

		if ( $('#wpadminbar').length && 'bar' === ntf.options.layout )
		{
			$( ntf.ntf ).css('margin-top', $('#wpadminbar').height() );
		}
		
		ntf.show();

		return ntf;
	};

})(jQuery);