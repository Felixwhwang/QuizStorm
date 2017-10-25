jQuery( document ).ready( function ($) {
    "use strict";

    // Bootstrap Tooltip
    $('[data-toggle="tooltip"]').tooltip();

    // Top Notification bar Close
    $(".close-topbar").click( function () {
        $("#top-bar").slideUp(500);
    });

    // Main Menu Open submenu on small devices
    $('.main-nav .menu-item-has-children > a').on('click', function (e) {
        if ( $( window ).width() <= 991 ) {
            $(this).toggleClass('submenu-open').next('.sub-menu')
                .slideToggle(200).end().parent('.menu-item-has-children')
                .siblings('.menu-item-has-children').children('a')
                .removeClass('submenu-open').next('.sub-menu').slideUp(200);

            e.preventDefault();
        }
    });

    // Widget Nav Menu Submenu Open
    $('.widget_nav_menu .menu-item-has-children a').on('click', function (e) {
        $(this).toggleClass('submenu-open').next('.sub-menu').slideToggle(200)
            .end().parent('.menu-item-has-children')
            .siblings('.menu-item-has-children').children('a')
            .removeClass('submenu-open').next('.sub-menu').slideUp(200);

        e.preventDefault();
    });


    // Header Sticky
    if ( $('header').hasClass('sticky-header') )
    {
        var nav = $('.logo-bar'),
            body = $('body'),
            topPosition = nav.offset().top;

        $( window ).on( 'scroll', function () {

            if ( $( window ).scrollTop() > topPosition ) {
                body.addClass('header-fixed');

            } else {
                body.removeClass('header-fixed');
            }
        });
    }

    function isTouchDevice()
    {
        return true == ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch);
    }

    if ( isTouchDevice() ) {
        $('.post-control-bar').addClass('touch-device');
    }
});

( function( window, $ ) {

    $(document).on('click', '.wp-playlist-tracks > .wp-playlist-item', function (e) {
        e.stopPropagation();
    });

    $('audio[data-download="1"], video[data-download="1"]').each( function (i) {
        var $this = $( this );
        attachButton( $this );
    });

    $('.wp-playlist[data-download="1"]').each( function () {
        var $this = $(this);
        attachDownloadBtn( $this );
    });

    function attachButton( container )
    {
        var source = container.find('source:first').attr('src');

        if ( source.length &&
                ! container.parents('.mejs-container:first').length ) {
            setTimeout( function(){ attachButton( container ); }, 2000 );
        } else {
            container.parents('.mejs-container:first').after('<div class="exc-download-item"><a href="' + source + '" class="download-button" download><i class="fa fa-download"></i> Download</a></div>');
        }
    }

    function attachDownloadBtn( container )
    {
        var list = container.find('.wp-playlist-tracks');

        if ( ! list.length ) {
            setTimeout( function(){ attachDownloadBtn( container ); }, 2000 );
        } else {
            list.addClass('exc-download');

            list.find('.wp-playlist-item').each( function () {
                var $this = $(this),
                    source = $this.children('a').attr('href');

                if ( source ) {
                    $this.append('<div class="exc-download-item"><a href="' + source + '" class="download-btn" download><i class="fa fa-download"></i>Download</a></div>');
                }
            });
        }
    }

})( window, jQuery );