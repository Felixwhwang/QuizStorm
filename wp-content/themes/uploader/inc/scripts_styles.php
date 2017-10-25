<?php defined( 'ABSPATH' ) or die( 'restricted access' );

if ( ! is_admin() || ( defined('DOING_AJAX') && DOING_AJAX ) )
{
    // Load Google Font
    $protocol = is_ssl() ? 'https' : 'http';
    wp_enqueue_style( 'exc-uploader-ptsans', "$protocol://fonts.googleapis.com/css?family=Poppins:400,600,300" );

    // Framework Object
    $_exc_uploader = exc_theme_instance();

    // Load Bootstrap from extracoding framework
    $_exc_uploader->html
        ->load_bootstrap()
        ->load_css( 'font-awesome', $_exc_uploader->system_url('views/css/font-awesome.min.css') );

    // Load Required Css files
    wp_enqueue_style( 'mf-style', get_template_directory_uri() . '/style.css', array('bootstrap') );
    wp_enqueue_style( 'mf-menu', get_template_directory_uri() . '/css/menu.css', array('bootstrap') );
    wp_enqueue_style( 'mf-color', get_template_directory_uri() . '/css/color.css', array('bootstrap') );

    if ( is_rtl() ) {
        wp_enqueue_style( 'exc-uploader-rtl', get_template_directory_uri() . '/rtl.css', array('mf-style') );
    }

    // Edit Profile Page
    if( is_single() || is_page() )
    {
        // Load General Scripts file
        wp_enqueue_script( 'mf-singe-page-script', get_template_directory_uri() . '/js/single-page.js', array( 'jquery' ), false, true );

        // Load wordpress comment reply js
        wp_enqueue_script( 'comment-reply' );

        //Share this Code
        if ( is_ssl() ) {
            wp_enqueue_script( 'sharethis', "https://ws.sharethis.com/button/buttons.js");
        } else {
            wp_enqueue_script( 'sharethis', "http://w.sharethis.com/button/buttons.js");
        }

        $_exc_uploader->html->inline_js( 'sharethis', 'if ( "undefined" !== typeof stLight ) { stLight.options({publisher: "85f03479-b986-4403-85ce-5e60724a1b87", doNotHash: false, doNotCopy: false, hashAddressBar: false}); }' );

    } elseif ( get_query_var( 'exc_custom_page' ) == 'dashboard-profile' ) {

        wp_enqueue_style( 'bootstrap-editable', get_template_directory_uri() . '/css/bootstrap-editable.css' );

        wp_enqueue_script( 'moment', get_template_directory_uri() . '/js/moment.min.js', array( 'bootstrap' ), '2.8.3', true );
        wp_enqueue_script( 'bootstrap-editable', get_template_directory_uri() . '/js/bootstrap-editable.min.js', array('jquery', 'bootstrap'), '1.5.1', true );
        wp_enqueue_script( 'exc-user-profile', get_template_directory_uri() . '/js/editable.js', array('bootstrap-editable'), '', true );

        // Load Configuration file
        if ( $path = locate_template( 'inc/edit_profile.php' ) ) {
            require( $path );

            $editable_config = array();

            foreach ($config as $key => $value) {
                $editable_config[ $key ] = $value['editable'];
            }

            wp_localize_script( 'exc-user-profile', 'exc_editable_settings', $editable_config );
        }
    }

    //register filtration script
    wp_register_script( 'exc-media-query', get_template_directory_uri() . '/js/media_filter.js', array( 'jquery'), '1.0', true );

    //register user filtration script
    wp_register_script( 'exc-user-filter', get_template_directory_uri() . '/js/user_filter.js', array(), '1.0', true );

    // Images Loaded
    wp_register_script( 'imagesloaded', get_template_directory_uri() . '/js/imagesloaded.pkgd.min.js', array(), '', true );

    // @TODO: add condition to load only if we have masonry view
    // General Scripts
    wp_enqueue_script( 'exc-uploader-scripts', get_template_directory_uri() . '/js/scripts.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_script( array( 'masonry', 'imagesloaded' ) );

    // Notification
    wp_enqueue_style( 'ns-style', get_template_directory_uri() . '/css/ns-style.css' );
    wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.custom.js' );
    wp_enqueue_script( 'notificationFx', get_template_directory_uri() . '/js/notificationFx.js', array( 'modernizr', 'exc-framework' ), '', true );

    wp_localize_script( 'notificationFx', 'exc_login_check',
                        array(
                            'security'      => wp_create_nonce( 'exc-login-check' ),
                            'redirecturl'   => home_url()
                        )
                    );
}