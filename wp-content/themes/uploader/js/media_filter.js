( function ( window, $ ) {
    "use strict";

    var media_filter = function( options )
    {
        this.container = options.container || "";

        delete options.container;
        // No need to keep the container

        // jQuery masonry bug hack
        var attributes = this.container[0].attributes,
            self = this,
            settings = {};

        for ( var attr in attributes ) {
            var nodeName = attributes[ attr ].nodeName;

            if ( nodeName && nodeName.match( /^data-/ ) ) {
                var attrName = nodeName.replace(/data-/, "");
                settings[ attrName ] = attributes[ attr ].nodeValue;
            }
        }

        this.content = this.container.children(".exc-media-content") || this.container;
        this.options = $.extend( {}, settings, options );

        this.initialize();
    }

    media_filter.prototype = {
        options: {},

        xhr: false,
        endResult: false,
        msnry: "",
        loader: '#exc-media-loader',
        filters:  $('#exc-media-filter'),
        counter: $('#exc-media-count'),
        searchForm: $('#exc-media-search'),
        catFilter: $('#all-fields'),
        loadMoreBtn: "",
        pagination: "",
        loader: $('<div class="loader" id="exc-media-loader"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>').hide(),
        isMasonry: true,
        emptyMarkup: $('<div />', { id: 'exc-empty-media' } ).hide(),

        initialize: function () {

            this.loadMoreBtn = this.container.parent().children('.exc-load-more');
            this.pagination = this.container.parent().children('.wp-pagination');

            this.catFilter.siblings('ul.exc-media-filter').prepend('<li><a class="active" data-id="" href="#">' + this.catFilter.text() + '</a></li>');

            if ( "load_on_scroll" === this.options.pagination ) {

                this.filters.on( 'click', '.exc-media-filter a:not(".skip-filter")', $.proxy( this.update_filter, this ) );
                this.searchForm.on( 'submit', $.proxy( this.searchMedia, this ) );

                $( window ).on( 'scroll', $.proxy( this.onScroll, this ) );
                $( window ).trigger( 'scroll' );

            } else {

                // Hide filters with support for autoload
                $('.autoload-only').hide();
                this.loadMoreBtn.on( 'click', $.proxy( this.load_more, this ) );
            }


            // Delete Post
            $('body').on( 'click', '.exc-delete-post', $.proxy( this.deletePost, this ) );

            this.update_counter();

            this.loader.insertAfter( this.content );
            this.emptyMarkup.insertAfter( this.content );

            this.isMasonry = this.options.masonry;

            if ( this.isMasonry ) {
                this.masonry();
            }
        },

        searchMedia: function ( e ) {
            e.preventDefault();
console.log("search me");
            this.endResult = false;

            this.options['s'] = this.searchForm.find( 'input:first' ).val();
            this.apply_filter();
        },

        update_filter: function( e ) {
            e.preventDefault();

            // abort previous request
            if ( this.xhr && this.xhr.readyState !== 4 ) {
                this.xhr.abort();
            }

            var $this = $( e.currentTarget ),
                parent = $this.parents( '.exc-media-filter' ),
                type = parent.data('name');

            if ( 'cat' === type ) {

                parent.find('a.active').removeClass('active');
                this.catFilter.text( $this.text() );

            } else if ( 'post_type' === type ) {
                parent.find('a.active').removeClass('active');
            }

            parent.children('a').removeClass('active');

            $this.addClass('active');

            if ( type ) {
                this.options[ type ] = $this.data('id');
            }

            this.endResult = false; // Reset if we have no result

            this.apply_filter();
        },

        apply_filter: function () {

            if ( Masonry.data( this.content[0] ) ) {
                this.content.masonry('destroy');
            }

            this.options['paged'] = 0;

            this.content.html('');
            this.loader.show();
            this.fetch_result( true );
        },

        fetch_result: function ( is_filter ) {

            // Wait until previous request is in que
            if ( this.xhr && "pending" === this.xhr.state() ) {
                return;
            }

            this.options['offset'] = this.content.children().length;
            this.emptyMarkup.hide();

            var self = this,
                params = this.options;
                //params = { pk: this.options.pk, security: this.options.security };

            this.xhr = wp.ajax.post( this.options.action, params ).done( function ( response ) {

                var html = $( response.html );

                if ( true === is_filter ) {

                    self.content.html( html );

                    if ( 'undefined' !== typeof response.class ) {
                        self.content.attr( 'class', response.class );
                    }

                    self.isMasonry = response.masonry

                    if ( self.isMasonry ) {

                        self.masonry();

                    } else if ( Masonry.data( self.content[0] ) ) {
                        self.content.masonry('destroy');
                    }

                    self.loader.hide();

                } else {

                    if ( ! Masonry.data( self.content[0] ) ) {
                        self.content.append( html );
                        self.loader.hide();
                    } else {

                        self.xhr.readyState = 1;

                        //self.content.append( html ).imagesLoaded( function(){
                        html.imagesLoaded( function(){

                            self.xhr = '';
                            self.content.append( html );
                            self.content.masonry( 'appended', html, true );
                            self.loader.hide();
                        });
                    }
                }

                self.pagination.replaceWith( response.pagi );

                //pagination.html( response.pagi );
                self.update_counter( response.counter );

            }).fail( function ( response, xhr ) {

                self.endResult = true;

                if ( is_filter ) {
                    self.update_counter( 0 );
                }

                self.loader.hide();

                if ( response.length ) {
                    return self.emptyMarkup.html( response ).show();
                }

                self.emptyMarkup.hide();
            });
        },

        load_more: function ( e ) {

            if ( e ) {
                e.preventDefault();
            }

            if ( ( this.xhr && this.xhr.readyState === 1 ) || !! this.endResult ) {
                return;
            }

            this.loader.show();
            this.fetch_result();
        },

        update_counter: function ( value ) {

            if ( 'undefined' === typeof value ) {
                value = this.options.counter;
            }

            this.counter.text( this.options["counter-text"].replace(/%d/, value ) );
        },

        onScroll: function () {

            if ( this.content.height() <= $( window ).scrollTop() + $( window ).height() ) {
                this.load_more();
            }
        },

        deletePost: function ( e ) {

            var $this = $( e.currentTarget ),
                item = $this.parents('li:first'),
                caption = $this.parents('.thumbnail:first');

            if ( item.find('.fa-spin').length || item.find('.confirm-delete').length ) {
                return;
            }

            var self = this,
                message = $( _.template( $('#tmpl-delete-post').html(), eXc.tmpl )() ).appendTo( caption );

            message.find( 'a.confirm-delete' ).on( 'click', function( e ) {

                e.preventDefault();

                var el = $( this ),
                    icon = el.find('i'),
                    icoClass = icon.attr('class');

                icon.attr( 'class', 'fa fa-gear fa-spin' );

                $.post( ajaxurl, {action: 'exc_delete_post', id: $this.data('id'), security: self.options.security }, function( r ) {

                    if ( ! r.success ) {
                        eXc.notification( {id: 'item-deleted', message: r.data, effect: 'slidetop', type: 'error', 'icon': 'icon fa fa-times', insertion: 'replace', ttl: 3000, layout: 'bar'} );
                    } else {
                        eXc.notification( {id: 'item-deleted', message: r.data, effect: 'slidetop', type: 'success', 'icon': 'icon fa fa-check', insertion: 'replace', ttl: 3000, layout: 'bar'} );
                        self.content.masonry( 'remove', item ).masonry('layout');
                    }

                    icon.attr('class', icoClass);
                });

                self.content.masonry('layout');
            });

            message.find('a.cancel-delete').on('click', function(e){
                e.preventDefault();

                message.remove();
            });
        },

        masonry: function () {
            var self = this;

            this.content.imagesLoaded( function() {
                self.msnry = self.content.masonry({
                    itemSelector: '.mason-item',
                    columnWidth: '.mason-item',
                    percentPosition: true,
                    isOriginLeft: self.options.isRtl || false,
                    isAnimated: true,
                    animationOptions: { duration: 750, easing: 'linear', queue: false } });
            });
        }
    }

    $( document ).ready( function () {

        $(".exc-media-container").each( function () {
            window["_exc_media_filter"] = new media_filter({
                container: $( this )
            });
        });
    });

}) ( window, jQuery );