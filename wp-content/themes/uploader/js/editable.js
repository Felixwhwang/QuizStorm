jQuery( document ).ready( function($){

  "use strict";

  var exc_editable = function( container )
  {
      container = ( container ) ? $( container ) : $( '#exc-editable' );

      if ( ! container.length || eXc.length( exc_editable_settings ) === 0 )
      {
          return;
      }

      var settings = exc_editable_settings,
          action = container.data('action'),
          security = container.data('security');

      // Change URL to WP
      $.fn.editable.defaults.url = ajaxurl;
      $.fn.editable.defaults.params = {action: action, security: security};

      $.fn.editable.defaults.success = function( response, newValue )
      {
          if ( ! response.success )
          {
              return response.data;
          } else if( $( this ).attr('id') === 'user_password' )
          {
              window.location = response.data;
          }
      }

      // Prepare Fields
      for ( var field_id in settings )
      {
          var field = $('#' + field_id );

          if ( field.length )
          {
            field.editable( settings[ field_id ] );
          }
      }
  }
   
  new exc_editable( '#exc-editable' );

  // Update Profile Image
  $('#profile-image').on( 'click', 'a.profile-upload-btn', function(e){
      e.preventDefault();

      var $this = $( this ),
          input = $this.siblings('input[type="file"]'),
          icon = $this.children('i'),
          label;


      if ( input.hasClass('hide') ) {
        label = input.data('cancel-text');
        icon.attr('class', input.data('cancel-class') );
      } else {
        label = input.data('edit-text');
        icon.attr('class', input.data('edit-class') );
      }

      input.val('');
      $this.children('.profile-btn-label').text( label );
      input.toggleClass('hide');
  });

  var xhr = '';
  $('#profile-img').on('change', function()
  {
      $('#profile-image-loader').remove();
      var container = $('#user_details'),
          form = $('#profile-image'),
          btn = $('.profile-upload-btn', form),
          img  = $('#user_details img:first'),
          input = btn.siblings('input[type="file"]'),
          loader = $( '<div class="profile-image-spinner"><i class="' + input.data('loading-class') + '"></i></div>').insertAfter( img );
      
      form.addClass('hide');

      $.ajax({
              url: ajaxurl,
              type: "POST",
              data: new FormData( form[0] ),
              contentType: false,
              cache: false,
              processData: false,
              complete: function(r)   // A function to be called if request succeeds
              {
                  loader.remove();

                  if ( r.success )
                  {                    
                      img.replaceWith( r.responseJSON.data );
                      $('#exc-user-ctrls .user-img > img').replaceWith( r.responseJSON.data );
                      btn.trigger('click');

                  } else
                  {
                      $('#profile-image').append( r.data );
                  }

                  form.removeClass('hide');
              }
        });
  });

});
