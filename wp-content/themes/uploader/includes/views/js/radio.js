(function(window, $, _, Backbone) {
    "use strict";

    var radio = Backbone.View.extend({

        events :
        {
            'click .wp-playlist-next' : 'next',
            'click .wp-playlist-prev' : 'prev'
        },

        initialize : function (options)
        {
            this.el = this.$el.find('.exc-player:first');

            this.msgNode = this.$el.find('.exc-radio-msgs');

            this.bindEvents();

            this.$el.find('.exc-radio-station .exc-play-station:first').trigger('click');
        },

        bindEvents: function()
        {
            this.$el.on('click', '.exc-radio-station .exc-play-station', { instance: this }, this.loadStation );
            _.bindAll( this, 'bindPlayer', 'bindResetPlayer', 'setPlayer', 'ended', 'waiting', 'playing'/*, 'clickTrack'*/ );
        },

        playStation: function( data )
        {
            this.index = 0;
            this.settings = {};

            this.data = data;

            var setup = false;

            if ( typeof this.playerNode === 'undefined' )
            {
                this.playerNode = this.$( this.data.type );

                if ( 'audio' === this.data.type )
                {
                    var skin = this.$el.data('skin') || 'exc-radio-meta';

                    this.currentTemplate = wp.template( skin );
                    this.currentNode = this.$( '.exc-playlist-caption' );
                    this.animation = wp.template('exc-radio-loader');
                }

                if ( ! _.isUndefined( window._wpmejsSettings ) ) {
                    this.settings = _wpmejsSettings;
                }

                setup = true;
            }

            this.tracks = new Backbone.Collection( this.data.tracks );
            
            this.current = this.tracks.first();

            this.renderCurrent();

            this.playerNode.attr( 'src', this.current.get( 'src' ) );

            this.settings.success = this.bindPlayer;

            if ( this.msgNode.children().length )
            {
                this.msgNode.html('');
                this.player.container.removeClass('hide');
                this.currentNode.removeClass('hide');
            }

            if ( setup )
            {
                this.setPlayer();
            } else
            {
                this.player.play();
                eXc.focus( this.$el );
            }
        },

        loadStation: function( e )
        {
            e.preventDefault();

            var $this = $( this ),
                data = exc_radio_settings,
                radio = e.data.instance,
                poster = $this.data('poster'),
                icon = $this.find('.fa:first'),
                org_class = icon.attr('class');

            radio.poster = radio.$('.exc-radio-poster');

            icon.attr('class', 'fa fa-spinner fa-spin');

            data['station_id'] = $this.data('station-id');

            $.post( ajaxurl, data, function(r){
                
                radio.el.css('background-image', 'url(' + poster + ')');

                if ( radio.poster.length )
                {
                    radio.poster.html( $('<img/>', {src: poster} ) );
                }

                if ( r.success )
                {
                    radio.playStation( r.data );
                } else
                {
                    if ( typeof radio.player === 'object' )
                    {
                        radio.player.pause();
                        radio.player.container.addClass('hide');
                        radio.currentNode.addClass('hide');
                    }
                    
                    radio.msgNode.html( eXc.error( r.data ) );

                    eXc.focus( radio.$el );
                }

                icon.attr('class', org_class);
            }, 'json');
        },

        bindPlayer : function (mejs) {
            this.mejs = mejs;
            this.mejs.addEventListener( 'ended', this.ended );
            this.mejs.addEventListener( 'playing', this.playing );
            this.mejs.addEventListener( 'waiting', this.waiting );
        },

        bindResetPlayer : function (mejs) {
            this.bindPlayer( mejs );
            this.playCurrentSrc();
        },

        setPlayer: function( force )
        {
            if ( this.player )
            {
                this.player.pause();
                this.player.remove();
                this.playerNode = $('<audio/>').appendTo( this.el );
            }

            if (force)
            {
                this.playerNode.attr( 'src', this.current.get( 'src' ) );
                this.settings.success = this.bindResetPlayer;
            }

            this.settings['radio'] = this;
            this.settings['features'] = ['playpause','loop','current','progress','duration','volume', 'navigation'];

            this.player = new MediaElementPlayer( this.playerNode.get(0), this.settings );
            this.playBtn = this.player.container.find('.mejs-playpause-button:first');
        },

        playCurrentSrc : function()
        {
            this.renderCurrent();
            this.mejs.setSrc( this.playerNode.attr( 'src' ) );
            this.mejs.load();
            this.mejs.play();
        },

        renderCurrent : function()
        {
            this.currentNode.html( this.currentTemplate( this.current.toJSON() ) );
        },

        playing: function()
        {
            /*if ( ! this.playBtn.children('.circle').length )
            {
                this.currentNode.html( this.currentTemplate( this.current.toJSON() ) );
            }*/

            //this.playBtn.
            //this.playBtn.addClass('playing');
        },

        waiting: function()
        {
            //this.playBtn.removeClass('playing');
        },

        ended : function ()
        {
            if ( ! this.repeat() )
            {
                if ( this.index + 1 < this.tracks.length )
                {
                    this.next();
                } else
                {
                    this.index = 0;
                    this.setCurrent();
                }
            }
        },

        next : function()
        {
            this.index = this.index + 1 >= this.tracks.length ? 0 : this.index + 1;
            this.setCurrent();
        },

        prev : function()
        {
            this.index = this.index - 1 < 0 ? this.tracks.length - 1 : this.index - 1;
            this.setCurrent();
        },

        repeat: function()
        {
            if ( ! this.player.options.loop )
            {
                return false;
            }

            this.setCurrent();

            return true;
        },

        loadCurrent: function()
        {
            var last = this.playerNode.attr( 'src' ) && this.playerNode.attr( 'src' ).split('.').pop(),
                current = this.current.get( 'src' ).split('.').pop();

            this.mejs && this.mejs.pause();

            if ( last !== current ) {
                this.setPlayer( true );
            } else {
                this.playerNode.attr( 'src', this.current.get( 'src' ) );
                this.playCurrentSrc();
            }
        },

        setCurrent : function ()
        {
            this.current = this.tracks.at( this.index );
            this.loadCurrent();
        }
    });

    $.extend( MediaElementPlayer.prototype, {

        buildnavigation: function( player, controls, layers, media )
        {
            var previous = $('<div class="mejs-button mejs-prev-button">' +
                                '<span class="fa fa-fast-backward"></span>' +
                            '</div>').appendTo( controls );

            previous.on( 'click', function(){
                player.options.radio.prev();
            } );

            var next = $('<div class="mejs-button mejs-next-button">' +
                            '<span class="fa fa-fast-forward"></span>' +
                        '</div>').appendTo( controls );

            next.on( 'click', function(){
                player.options.radio.next();
            });
        },

        buildloop: function( player, controls, layers, media )
        {
            var loop = $('<div class="mejs-button mejs-loop-button ' + ((player.options.loop) ? 'mejs-loop-on' : 'mejs-loop-off') + '">' +
                            '<span class="fa fa-repeat"></span>' +
                        '</div>').appendTo(controls);

            loop.on( 'click', function()
            {
                player.options.loop = !player.options.loop;

                if (player.options.loop)
                {
                    loop.removeClass('mejs-loop-off').addClass('mejs-loop-on');
                } else
                {
                    loop.removeClass('mejs-loop-on').addClass('mejs-loop-off');
                }
            });
        }
    });

    $(document).ready(function () {
        $('.exc-radio').each( function() {
            return new radio({ el: this });
        } );
    });

    window.eXcRadio = radio;

}(window, jQuery, _, Backbone));