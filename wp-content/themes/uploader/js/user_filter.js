if ( 'undefined' === typeof window['exc'] ) {
    window['exc'] = {};
}

( function ( window, $ ) {

    "use strict";

    exc.user_filter = function( options ) {

        options = options || {};

        var xhr = false,
            endResult = false,
            msnry,
            container = options['container'] || $('#exc-user-container'),
            loader = '#exc-user-loader',
            counter = $('#exc-media-count'),
            searchBtn = $('#exc-user-search'), //@TODO: add supprt for users search
            filters = $('#exc-users-filter'),
            loadMoreBtn = $('#exc_load_more'),
            isMasonry = false,
            //loader = $('<div />', { class: 'loader', id: 'exc-user-loader' } ).hide(),
            loader = $('<div class="loader" id="exc-user-loader"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>').hide(),
            emptyMarkup = $('<div />', { id: 'exc-empty-media' } ).hide();

        function init()
        {
            if ( ! container.length ){
                return;
            }

            filters.on( 'click', '.exc-user-filter a', update_filter );
            filters.on( 'submit', user_search );

            if ( exc_user_filter.autoload === '1' )
            {
                $( window ).on( 'scroll', onScroll );
                $( window ).trigger( 'scroll' );

            } else
            {
                //@TODO: code for loading media manually
                loadMoreBtn.on( 'click', load_more );
            }

            update_counter();

            loader.insertAfter( container );
            emptyMarkup.insertAfter( container );

            isMasonry = exc_user_filter.masonry;

            if ( isMasonry )
            {
                masonry();
            }
        }

        function user_search( e )
        {
            e.preventDefault();

            endResult = false;

            exc_user_filter['s'] = searchBtn.find( 'input:first ').val();
            apply_filter();
        }

        function update_filter( e )
        {
            e.preventDefault();

            // Abort Previous Request
            if ( xhr && xhr.readyState !== 4 )
            {
                xhr.abort();
            }

            var $this   = $(this),
                parent  = $this.parents('.exc-user-filter:first'),
                type    = parent.data('name');

            parent.children('a').removeClass('active');

            //$this.addClass('active');

            if ( type === 'order' )
            {
                var id = $this.data('id');

                $this.addClass('hide');
                $this.siblings('a').removeClass('hide');

                exc_user_filter[ type ] = id;
            } else
            {
                exc_user_filter[ type ] = $this.data('id');
            }

            endResult = false // Reset if we have no result

            apply_filter();
        }

        function apply_filter()
        {
            if ( Masonry.data( container[0] ) )
            {
                container.masonry('destroy');
            }

            container.html( '' );
            loader.show();

            fetch_result( true );
        }

        function fetch_result( is_filter )
        {
            exc_user_filter['offset'] = container.children().length;
            emptyMarkup.hide();

            xhr = $.post( ajaxurl, exc_user_filter, function(r)
            {
                if( ! r.success )
                {
                    endResult = true;

                    if ( is_filter )
                    {
                        update_counter(0);
                    }

                    ( r.data ) ? emptyMarkup.html( r.data ).show() : emptyMarkup.hide();

                    loader.hide();
                } else
                {
                    var html = $( r.data.html );

                    if ( true === is_filter )
                    {
                        container.html( html );

                        if ( typeof r.data.class !== 'undefined' )
                        {
                            container.attr( 'class', r.data.class );
                        }

                        isMasonry = r.data.masonry;

                        if ( isMasonry )
                        {
                            masonry();

                        } else if( Masonry.data( container[0] ) )
                        {
                            container.masonry('destroy');
                        }

                        loader.hide();
                    } else
                    {
                        if ( ! Masonry.data( container[0] ) )
                        {
                            container.append( html );
                            loader.hide();
                        } else
                        {
                            xhr.readyState = 1;

                            html.imagesLoaded( function(){

                                xhr = '';
                                container.append( html );
                                container.masonry( 'appended', html, true );
                                loader.hide();
                            });
                        }
                    }

                    update_counter( r.data.counter );
                }

                //loader.hide();

            }, 'json');
        }

        function load_more( e )
        {
            if( e )
            {
                e.preventDefault();
            }

            if( ( xhr && xhr.readyState === 1 ) || !!endResult )
            {
                return;
            }

            loader.show();

            fetch_result();
        }

        function update_counter( value )
        {
            if( typeof value === 'undefined' )
            {
                value = exc_user_filter.counter;
            }

            counter.text( exc_user_filter.countString.replace(/%d/, value) );
        }

        function onScroll()
        {
            //if( $( document ).height() <= $( window ).scrollTop() + $( window ).height() )
            if ( container.height() <= $( window ).scrollTop() + $( window ).height() )
            {
                load_more();
            }
        }

        function masonry()
        {
            container.imagesLoaded( function() {
                msnry = container.masonry({ itemSelector: '.mason-item', transitionDuration : 0 });
                window.msnry = msnry;
                window.container = container;
            });
        }

        init();

        return this;
    }

    $( document ).ready( function () {

        if ( $('#exc-user-container').length ) {
            new exc.user_filter();
        }
    });

})( window, jQuery );