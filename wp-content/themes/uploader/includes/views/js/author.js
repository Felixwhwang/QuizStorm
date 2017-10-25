( function( window, $, undefined ){

	var subscriber = function( target )
	{
		this.target = $( target );
		this.id = this.target.data('id');

		// Do nothing if the id is missed
		if ( 'undefined' === typeof this.id ) {
			return;
		}

		this.icon = this.target.children('.fa');
		this.orgIconClass = this.icon.attr('class');
		this.container = this.target.parents('.mason-item:first');

		if ( ! this.container.length )
		{
			this.container = this.target.parent('div');
		}

		this.loadingClass = 'fa fa-spinner fa-spin';

		// Make sure the request is not already in process
		//if ( ! this.element.children().length )
		if ( ! this.container.hasClass( 'inprocess' ) )
		{
			this.icon.attr( 'class', this.loadingClass );

			if ( this.target.hasClass( 'exc-follow-author' ) )
			{
				this.element = this.container.find( '.exc-follower-form-' + this.id );
				this.container.on( 'click', '.exc-follow-resend', {self: this}, this.resendMail );

				this.addSubscriber();
			} else 
			{
				this.infobar = $( '.exc-votes-info-' + this.id );
				this.counters = $( '.exc-votes-count-' + this.id );

				this.postLike();
			}
		}
	}

	$.extend( subscriber.prototype, {

		addSubscriber: function()
		{
			// Don't submit form if we have ajax request in process
			// if ( author.xhr && author.xhr.readyState !== 4 )
			// {
			// 	return false;
			// }

			this.container.addClass( 'inprocess' );

			var self = this,
				data = $.extend( data,
						{
							'action': 'exc_follow_author',
							'security': exc_author_js.security,
							'author_id': this.target.data('id')
						});

			if ( 'undefined' !== typeof this.form )
			{
				var formArr = this.form.children('form:first').serializeArray();

				$.each( formArr, function(k, v ){
					data[ v.name ] = v.value;
				});

				this.form.find('.alert').remove();
				this.form.find('.btn > i').attr( 'class', this.loadingClass );
			} else
			{
				//author._setup( this );
				this.icon.attr('class', this.loadingClass);
			}

			//author.xhr = 
			$.post( ajaxurl, data, function(r)
			{
				self.icon.attr( 'class', self.orgIconClass );

				if ( ! r.success )
				{
					if ( typeof r.data.html !== 'undefined' )
					{
						self.element.html( r.data.html );
					}

				} else
				{
					if ( 'undefined' !== typeof r.data.html )
					{
						self.target.addClass('hide');
						self.form = self.element.html( r.data.html );
						self.form.children('form').on( 'submit', {self: self}, self.subscriberForm );
					}

					if ( 'undefined' !== typeof r.data.i18n_str )
					{
						//self.target.contents().last().replaceWith( r.data.i18n_str );
						self.target.children('span').text( r.data.i18n_str );

						//self.target.text( r.data.i18n_str );
						self.target.removeClass('hide');
						//self = {};
					}
				}

				self.updateMasonry();
				self.container.removeClass( 'inprocess' );

			}, 'json');
		},

		subscriberForm: function(e)
		{
			e.preventDefault();

			this.form = $( this );

			e.data.self.addSubscriber();
		},

		resendMail: function(e)
		{
			e.preventDefault();

			var self = e.data.self;
			//author._setup( this );

			self.container.addClass( 'inprocess' );

			var ntf = eXc.notification({ message: exc_author_js.resend_i18n_str, insertion: 'replace', ttl: 0, layout: 'bar', effect: 'slidetop', type: 'success', 'id': 'resending-mail', icon: 'fa fa-spinner fa-spin' });

			$.post( ajaxurl, {action: 'resend_subscriber_email', security: exc_author_js.security}, function(r)
			{
				if ( ! r.success )
				{
					ntf = eXc.notification( {id: 'resending-mail', message: r.data.html, insertion: 'replace', ttl: 5000, layout: 'bar', effect: 'slidetop', type: 'error', 'icon': 'fa fa-times' } );
				} else 
				{
					ntf = eXc.notification( {id: 'resending-mail', message: r.data.html, insertion: 'replace', ttl: 5000, layout: 'bar', effect: 'slidetop', type: 'success', 'icon': 'fa fa-check' } );
					//uploader.loginBar.ntf.children('.ns-close').trigger('click');ഀ
				}

				self.container.removeClass( 'inprocess' );

			}, 'json');
		},

		postLike: function()
		{
			var self = this,
				data = {
							'action': 'exc_post_like',
							'security': exc_author_js.security,
							'ID': self.id
						};

			self.container.addClass( 'inprocess' );
			
			//author.el.icon.attr('class', author.loading_class )

			$.post( ajaxurl, data, function(r){

				//author.el.icon.attr( 'class', author.el.icon_classes );
				var icon = ( typeof r.data !== 'undefined' && typeof r.data.icon !== 'undefined' ) ? r.data.icon : 'fa fa-times';

				// Show Message
				if ( ! r.success )
				{
					var msg = ( typeof r.data.message !== 'undefined' ) ? r.data.message : r.data;
					eXc.notification({ message: msg, ttl: 3000, layout: 'bar', effect: 'slidetop', type: 'error', 'id': 'login-required', 'icon': icon });

				} else
				{
					if ( typeof r.data.message !== 'undefined')
					{
						eXc.notification({ 'id': 'user-like-' + self.id , message: r.data.message, layout: 'bar', effect: 'slidetop', type: 'success', icon: icon });
					}

					self.infobar.text( r.data.i18n_str );
					self.counters.text( r.data.votes );

					if ( typeof r.data.icon_class !== 'undefined' )
					{
						self.target.toggleClass( r.data.icon_class );
					} else {
						self.target.toggleClass( 'liked' );
					}
				}

				self.icon.attr( 'class', self.orgIconClass );
				self.container.removeClass( 'inprocess' );

			}, 'json');
		},

		updateMasonry: function()
		{
			var msnryContainer = this.container.hasClass('mason-item')
									? this.container.parent()
									: this.container.parents('.mason-item:first'),
				msnry = ( msnryContainer.length ) ? Masonry.data( msnryContainer[0] ) : '';

			if ( msnry )
			{
				msnry.layout();
			}
		},
	});
	
	$( document ).on( 'click', '.exc-follow-author, .exc-post-like', function(e){
		e.preventDefault();

		new subscriber( this );
	});

})( window, jQuery );