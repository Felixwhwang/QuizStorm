jQuery(document).ready(function($) {
	"use strict";

	var members = 
	{
		xhr: '',
		history: '',
		template: $('#tmpl-exc-fs-dialog').html(),
		formID: '',
		dialog: '',
		refresh: true,
		BV: '',
		
		init: function()
		{
			$(window).on('hashchange', members.onhashChange);
			
			//manually call change we have hash in URL
			if( window.location.hash )
			{
				members.onhashChange();
			}
		},
		
		onhashChange: function(e)
		{	
			var $location = window.location.hash;
			
			if ( $location === '#login' )
			{
				members.user_signin();
			} else if( $location === '#signup' )
			{
				members.user_signup();
				
			} else if($location === '#logout' )
			{
				members.user_logout();
			} else if($location === '#forgot_password')
			{
				members.forgot_password();
			} else if( $location === '#reset_password' )
			{
				members.reset_password();
			}
			
			//Disable form submission event
			if ( members.dialog )
			{
				members.dialog.on('hidden.bs.modal', function(){
					window.location.hash = '';
					$('#' + members.formID).off('submit');

					members.dialog.off( 'hidden.bs.modal' );
				});
			}
		},
		
		form_submit: function(e)
		{
			e.preventDefault();
			
			if( members.xhr && members.xhr.readyState !== 4 )
			{
				return;
			}
			
			var	form 		= $( this ),
				id 			= form.attr('id'),
				submitBtn	= form.find('button[type="submit"]'),
				icon 		= submitBtn.children('i:first'),
				iconClass 	= icon.attr('class'),
				data 		= form.serializeArray(),
				redirect_to = form.find('input[name="redirect_to"]').val();

			members.formID = id;
			
			form.find('.alert').remove();
		
			icon.attr('class', 'fa fa-spinner fa-spin');
			
			members.xhr = $.post(ajaxurl, data, function(r){
				
				if( ! r.success )
				{
					if ( 'object' === typeof( r.data ) )
					{
						$.each( r.data, function(k, v){
							var field = form.find(':input[name="' + k + '"]');
							
							if ( field.length )
							{
								field.after( v );
							} else
							{
								form.prepend( eXc.error( v ) );
							}
						});
						
					} else
					{
						if ( r === 0 )
						{
							// User is already signin
							window.location = ( redirect_to ) ? redirect_to : window.location.href.replace( window.location.hash, '' );
						} else
						{
							form.prepend( eXc.error( r.data ) );
						}
					}
					
				}else
				{
					if( ! r.data )
					{
						var hash = new RegExp( window.location.hash, 'g' );
						window.location = ( redirect_to ) ? redirect_to : window.location.href.replace( window.location.hash, '' );
					}else
					{
						form.get(0).reset();
						form.prepend( r.data );
					}
				}
				
				icon.attr( 'class', iconClass );
			}, 'json');

		},
		
		user_signin: function()
		{
			var tmpl = $('#tmpl-exc-login');
			
			if( tmpl.length )
			{
				members.dialog = eXc.dialog.open({ body: tmpl.html(), family: 'user-login' }, members.template);
				
				members.background();
				
				$('#exc-signin-form').on('submit', members.form_submit);
			}
		},
		
		user_signup: function()
		{
			var tmpl = $('#tmpl-exc-signup');
			
			if(tmpl.length)
			{
				members.dialog = eXc.dialog.open( { body: tmpl.html(), family: 'user-login' }, members.template );
				
				members.background();

				$('#exc-signup-form').on( 'submit', members.form_submit );
			}
		},

		forgot_password: function()
		{
			var tmpl = $('#tmpl-exc-forgot-password');

			if ( tmpl.length )
			{
				members.dialog = eXc.dialog.open( { body: tmpl.html(), family: 'user-login' }, members.template );
				members.background();

				$('#exc-forgot-password-form').on( 'submit', members.form_submit );
			}
		},
		
		reset_password: function()
		{
			var tmpl = $('#tmpl-exc-reset-password');

			if ( tmpl.length )
			{
				members.dialog = eXc.dialog.open( { body: tmpl.html(), family: 'user-login' }, members.template );
				members.background();

				$('#exc-reset-password-form').on( 'submit', members.form_submit );
			} else 
			{
				window.location.hash = 'forgot_password';
			}
		},

		user_logout: function()
		{
			members.xhr = $.post( ajaxurl, {action: 'exc_user_logout', security: exc_login_check._nonce}, function(r){
				
				window.location = window.location.href.replace(/#logout/, '');

			}, 'json');
		},
		
		background: function( )
		{
			if ( ! exc_backgrounds.length )
			{
				return;
			}
			
			if ( members.BV )
			{
				members.BV.getPlayer().dispose();
			}

			members.BV = new $.BigVideo({container: $('.exc-popup > .full-page-bg'), useFlashForFirefox: false, controls: false });

			members.BV.init();
			members.BV.showPlaylist( members.shuffle( exc_backgrounds ), {ambient:true} )
		},

		shuffle: function(array)
		{
			var currentIndex = array.length, temporaryValue, randomIndex ;

			while (0 !== currentIndex)
			{
				randomIndex = Math.floor(Math.random() * currentIndex);
				currentIndex -= 1;

				temporaryValue = array[currentIndex];
				array[currentIndex] = array[randomIndex];
				array[randomIndex] = temporaryValue;
			}

			return array;
		}
	}
	
	members.init();

	window.members = members;
});