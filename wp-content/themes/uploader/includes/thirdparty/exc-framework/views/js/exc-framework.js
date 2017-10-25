(function($){
    window.eXc =
    {
        tmpl: {
            evaluate:    /<#([\s\S]+?)#>/g,
            interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
            escape:      /\{\{([^\}]+?)\}\}(?!\})/g
        },

        log: function(msg){

            if ( 'undefined' !== typeof('console') )
            {
                console.warn(msg);
            }
        },

        error: function( str )
        {
            return '<div class="alert alert-danger" role="alert">' + str + '</div>';
        },

        focus: function( dom, offset )
        {
            offset = offset || 40;

            var scroll = ( $('#wpadminbar').length ) ?  dom.offset().top - ( $('#wpadminbar').height() + offset ) : dom.offset().top - offset;

            $('html, body').animate( {scrollTop: scroll} );
        },

        toSlug: function(str)
        {
            if ( ! str)
            {
                return;
            }

            str = $.trim( str.toLowerCase().replace( /([^a-z0-9])/gi, " ", str ) );

            return str.replace( /\s+/g, "_", str );
        },

        toString: function(str)
        {
            str = str.toLowerCase();
            return eXc.ucwords($.trim(str.replace(/([^a-z0-9])/i, " ", str)));
        },

        ucwords: function(str)
        {
            return (str + '').replace(/^([a-z])|\s+([a-z])/g, function($1){
                return $1.toUpperCase();
            });
        },

        length: function(obj)
        {
            var count = 0;
            for (var k in obj) {
                if (obj.hasOwnProperty(k)) {
                   ++count;
                }
            }

            return count;
        }
    };

    eXc.dialog = dialog =
    {
        //el: ,

        open: function( params, template )
        {
            template = template || $('#tmpl-exc-dialog').html();

            if( ! template)
            {
                eXc.log( "The dialog template is not loaded." );
                return;
            }

            var target = $('#exc-dialog'),
                family = ( typeof params['family'] !== 'undefined' ) ? params['family'] : '';

            if ( family && $('.exc-popup[data-family="' + family + '"]').length )
            {
                target = $('.exc-popup[data-family="' + family + '"]');
            }

            if ( ! target.length )
            {
                $('<div class="exc-bootstrap"> <div id="exc-dialog" class="exc-popup modal fade" data-family="' + family + '"></div> </div>').prependTo('body');
                target = $('#exc-dialog');

                if ( $('#wpadminbar').length )
                {
                    target.css('padding-top', $('#wpadminbar').height() + 'px');
                }

            } else if( target.hasClass('in') )
            {
                if ( family && family === target.data('family') )
                {
                    //return target.html( _.template( template, eXc.tmpl )(params) );
                } else
                {
                    target = $('<div id="exc-dialog-'+ target.parent().children().length +'" class="exc-popup modal fade"  data-family="' + family + '"></div>').insertAfter( target );
                }
            }

            target.html( _.template( template, eXc.tmpl )(params) );

            target.modal( 'show' );

            target.on( 'hidden.bs.modal', function(){

                target.off( 'hidden.bs.modal' );

                if ( target.siblings('.exc-popup').length === 0 )
                {
                    target.parent().remove();
                } else
                {
                    target.remove();
                }

            } );

            return target;
        },

        message: function( name, message, prependTo, delay )
        {
            var id = 'em-' + name;

            if( $( '#' + id ).length )
            {
                return;
            }

            if ( false !== delay )
            {
                delay = delay || 3000;
            }

            prependTo = prependTo || 'body';

            var bar = $("<div />", { class: 'exc-topbar', text: message, id: 'em-' + name } ).hide().prependTo( prependTo ).slideDown( 'fast' );

            if ( delay )
            {
                bar.delay( delay ).slideUp( function(){ $( this ).remove( ); } );
            }

            return bar;
        },

        alert: function( options, template )
        {
            options = $.extend({
                            text: "Are you sure?",
                            title: "",
                            buttons:
                            {
                                confirm: function(){},
                                cancel: function(){},
                            }

                        }, options);

            // Normalize Buttons
            var btnClass = 'btn-primary';

            for( var button in options.buttons )
            {
                var settings = {label: eXc.toString( button ), eventClass: '.' + button, class: button + ' ' + btnClass, callback: options.buttons[ button ] };

                if ( typeof options.buttons[ button ] == 'function' )
                {
                    options.buttons[ button ] = settings;
                } else
                {
                    options.buttons[ button ] = $.extend( {}, settings, options.buttons[ button ] );

                    if ( typeof options.buttons[ button ]['callback'] !== 'function' )
                    {
                        options.buttons[ button ]['callback'] = function(){};
                    }
                }

                btnClass = 'btn-default';
            }

            template = template || $('#tmpl-exc-confirm').html();

            var modal = eXc.dialog.open( options, template );

            // Event Binding
            $.each( options.buttons, function(id, button){
                modal.on( 'click', button.eventClass, {modal: modal}, button.callback );
            });
        }
    }

})(jQuery);