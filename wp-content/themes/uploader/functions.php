<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

// Load extracoding framework
require get_template_directory() . '/includes/launcher.php';

// Add shortcode functions
require get_template_directory() . '/inc/shortcodes.php';

// Add support for featured image
add_theme_support( 'post-thumbnails' );

// Add feed link support
add_theme_support( 'automatic-feed-links' );

// Title Tag
add_theme_support( "title-tag" );

// Custom Header
add_theme_support( "custom-header" );

// Custom header background
add_theme_support( "custom-background" );

// Set the width of images and content. Should be equal to the width the theme
if ( ! isset( $content_width ) )
{
    $content_width = 980;
}

// Register Image sizes
if ( function_exists( 'add_image_size' ) )
{
    add_image_size( 'grid-thumb', 300, 198, true );
    add_image_size( 'masonry-thumb', 300 );
    //add_image_size( 'single-page-featured-image', 960, 340, true );
    //@TODO: Register rest of sizes
}

/**
 * Set default layout option name for uploader theme
 */
if ( ! function_exists( 'exc_uploader_layout_option_name' ) )
{
    function exc_uploader_layout_option_name( $option_name )
    {
        return 'mf_layout';
    }

    add_filter( 'exc_layout_default_theme_options_key', 'exc_uploader_layout_option_name' );
}

/**
 * Set default layout option name for uploader theme
 */
if ( ! function_exists( 'exc_uploader_layout_meta_key' ) )
{
    function exc_uploader_layout_meta_key( $meta_key )
    {
        return 'mf_layout';
    }

    add_filter( 'exc_layout_default_db_key', 'exc_uploader_layout_meta_key' );
}

// Media Query Settings
if ( ! function_exists( "exc_mf_media_query_atts" ) ) :

function exc_mf_media_query_atts ( $vars ) {

    return wp_parse_args( array(
        "template"          => "content-grid-view",
        "list_template"     => "content-list-view",
        "list_view"         => 1,
        "show_dropdown"     => 1,
        "list_show_dropdown"=> 1,
        "show_content"      => 1,
        "list_show_content" => 1,
        "show_author"       => 1,
        "list_show_author"  => 1,
        "show_date"         => 1,
        "list_show_date"    => 1,
        "show_tags"         => 0,
        "list_show_tags"    => 1,
        "show_stats"        => 1,
        "filtration"        => 1,
        "autoload"          => 1,
        "list_autoload"     => 1
    ), $vars );
}

add_filter( "exc_media_query_vars", "exc_mf_media_query_atts" );

endif;

if ( ! function_exists( "exc_mf_media_query_attributes" ) ) :

function exc_mf_media_query_attributes ( $atts ) {
    return wp_parse_args( array(
            //"autoload"      => 1,
            "counter-text"  => _x( "%d Media Files", "Uploader query filter counter", "exc-uploader-theme" ),
            "action"        => "exc_mf_media_filter"
        ), $atts );
}

add_filter( "exc_media_query_attributes", "exc_mf_media_query_attributes" );
endif;

if ( ! function_exists( "exc_mf_media_query_static_vars" ) ) :

function exc_mf_media_query_static_vars ( $vars ) {
    $allowed_post_types = array_keys( get_allowed_post_types_array() );

    return wp_parse_args( array(
        "post_type"         => $allowed_post_types,
        "container"         => "ul",
        "pagination"        => "navigation"
    ), $vars );
}

add_filter( "exc_media_query_static_vars", "exc_mf_media_query_static_vars" );
endif;

if ( ! function_exists( "exc_mf_media_query_listview_class" ) ) :

function exc_mf_media_query_listview_class( $class )
{
    return $class . ' home-listview';
}

add_filter( "exc_media_query_listview_class", "exc_mf_media_query_listview_class" );
endif;

if ( ! function_exists( "exc_mf_media_query_extend" ) ) :
function exc_mf_media_query_extend( $out, $pairs, $atts )
{
    if ( ! isset( $out["autoload"] ) ) {
        return $out;
    }

    $autoload = $out["autoload"];

    unset( $out["autoload"] );

    if ( ! $autoload ) {
        return $out;
    }

    $out["pagination"] = "load_on_scroll";

    return $out;

}

add_filter( "shortcode_atts_exc_media_query", "exc_mf_media_query_extend", 10, 3 );
endif;

if ( ! function_exists( "exc_mf_media_query_filtration" ) ) :

function exc_mf_media_query_filtration( $page, &$atts )
{
    $atts['exc_custom_page'] = array( 'page' => $page, 'author_id' => $atts["author"] );

    if ( ( is_user_logged_in() && ( $page == 'dashboard-likes' ) ) || $page == 'users-likes' )
    {
        unset( $atts['author'] );

        add_filter('posts_join', 'filter_media_likes_join', 10, 2);
        add_filter( 'posts_where', 'filter_media_likes_where', 10, 2);
    }
}

add_filter( "exc_media_query_filtration", "exc_mf_media_query_filtration", 10, 2 );
endif;

if ( ! function_exists( 'get_allowed_post_types_array' ) )
{
    function get_allowed_post_types_array()
    {
        $uploader_settings = get_option( 'mf_uploader_settings' );

        $available_post_types = array(
            'post' =>  array(
                'label' => __('Article', 'exc-uploader-theme'),
                'type' => 'post',
                'icon' => 'fa-file-text-o',
            ),

            'exc_audio_post' => array(
                'label' => __('Audio', 'exc-uploader-theme'),
                'type' => 'exc_audio_post',
                'icon' => 'fa-file-audio-o',
            ),

            'exc_video_post' => array(
                'label' => __('Video', 'exc-uploader-theme'),
                'type' => 'exc_video_post',
                'icon' => 'fa-file-video-o',
            ),

            'exc_image_post' => array(
                'label' => __('Image', 'exc-uploader-theme'),
                'type' => 'exc_image_post',
                'icon' => 'fa-file-image-o',
            )
        );

        if ( empty( $uploader_settings['allowed_post_types'] ) ) {
            return $available_post_types;
        }

        foreach ( $available_post_types as $post_type => $data ) {
            if ( FALSE === in_array( $post_type, $uploader_settings['allowed_post_types'] ) ) {
                unset( $available_post_types[ $post_type ]);
            }
        }

        return $available_post_types;
    }
}

// Social Media Login
if ( ! function_exists( 'exc_wsl_use_fontawsome_icons' ) ) :
function exc_wsl_use_fontawsome_icons( $provider_id, $provider_name, $authenticate_url )
{
    ?>
        <a rel="nofollow" href="<?php echo esc_url( $authenticate_url );?>" data-provider="<?php echo esc_attr( $provider_id );?>" class="wp-social-login-provider wp-social-login-provider-<?php echo strtolower( $provider_id ); ?>">
            <span>
                <i class="fa fa-<?php echo strtolower( $provider_id ); ?>"></i>
                <?php
                printf(
                    __( 'Sign in with %s', 'exc-uploader-theme' ),
                    $provider_name
                );?>
            </span>
        </a>
    <?php
}

add_filter( 'wsl_render_auth_widget_alter_provider_icon_markup', 'exc_wsl_use_fontawsome_icons', 10, 3 );
endif;

// DEPRECIATED IN FAVOUR OF exc_get_post_thumbnail_size
if ( ! function_exists( 'mf_get_post_thumbnail_size' ) )
{
    function mf_get_post_thumbnail_size( $columns = 2, $masonry = true )
    {
        switch( $columns )
        {
            case "1" :
                return ( $masonry ) ? 'large' : '';
            break;

            case "2" :
                return ( $masonry ) ? 'medium' : '';
            break;

            case "3" :
                return ( $masonry ) ? 'masonry-thumb' : 'grid-thumb';
            break;

            case "4" :
                return ( $masonry ) ? 'masonry-thumb' : 'grid-thumb';
            break;

            default :
                return ( $masonry ) ? 'large' : '';
            break;
        }
    }
}

// DEPRECIATED in favour of exc_wp_title
if ( ! function_exists('mf_wp_title') )
{
    function mf_wp_title( $title )
    {
        if ( empty( $title ) && ( is_home() || is_front_page() ) )
        {
            return get_bloginfo( 'title' );
        }

        return $title;
    }

    add_filter( 'pre_get_document_title', 'mf_wp_title' );
}

// Load Script and Styles
if ( ! function_exists( 'mf_scripts_styles' ) )
{
    function mf_scripts_styles()
    {
        get_template_part('inc/scripts_styles');
    }

    add_action( 'wp_enqueue_scripts', 'mf_scripts_styles' );
}

if ( ! function_exists('exc_page_error') )
{
    function exc_page_error( $heading, $message )
    {
        exc_die(
            exc_load_template( 'modules/content-error',
                        array(
                            'heading' => $heading,
                            'message' => $message
                        ), true
                    )
            );
    }
}

// Container Class
// DEPRECIATED
if( ! function_exists('mf_container_class') )
{
    function mf_container_class( $return = false )
    {
        $settings = get_option('mf_general_settings');

        $class = ( exc_kv( $settings, 'responsive' ) == 'on' )
                    ? 'container-fluid' : 'container-fixed';

        $class = "container-fluid";

        if ( $return )
        {
            return $class;
        }

        echo esc_attr( $class );
    }
}

if ( ! function_exists( 'exc_extend_avatar' ) ) {

    function exc_extend_avatar( $avatar, $id_or_email, $args ) {

        if ( is_numeric( $id_or_email ) && function_exists( 'exc_get_user_name' ) ) {

            $attachment_id = (int) get_the_author_meta( 'profile_image', $id_or_email );

            if ( $attachment_id ) {
                $attachment = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );

                $class = ( isset( $args['class'] ) ) ? $args['class'] : '';

                return '<img src="' . esc_url( $attachment[0] ) . '" alt="' . esc_attr( exc_get_user_name( $id_or_email, true ) ) . '" class="' . esc_attr( $class ) . '" />';
            }

        }
    }

    add_filter( 'pre_get_avatar', 'exc_extend_avatar', 10, 3 );
}

if ( ! function_exists( 'exc_comment_nav' ) )
{
    function exc_comment_nav() {
        // Are there comments to navigate through?
        if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :?>

        <nav class="navigation comment-navigation" role="navigation">
            <h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'twentyfifteen' ); ?></h2>
            <div class="nav-links">
                <?php
                    if ( $prev_link = get_previous_comments_link( __( 'Older Comments', 'twentyfifteen' ) ) ) :
                        printf( '<div class="nav-previous">%s</div>', $prev_link );
                    endif;

                    if ( $next_link = get_next_comments_link( __( 'Newer Comments', 'twentyfifteen' ) ) ) :
                        printf( '<div class="nav-next">%s</div>', $next_link );
                    endif;
                ?>
            </div><!-- .nav-links -->
        </nav><!-- .comment-navigation -->
        <?php
        endif;
    }
}

// Ajax Comment loading
// DEPRICIATED
if ( ! function_exists('exc_comment_style') )
{
    function exc_comment_style($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;

        extract($args, EXTR_SKIP);?>

        <li <?php comment_class( 'media' );?> id="comment-<?php comment_ID() ?>">

            <?php if ( $args['avatar_size'] != 0 ):?>
            <a class="pull-left" href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>">
                <?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
            </a>
            <?php endif;?>

            <div class="media-body">

                <div class="media-header">
                    <h4 class="media-heading"><?php comment_author();?></h4>

                    <span class="time">
                        <?php
                        /* translators: 1: date, 2: time */
                        printf( __('%1$s at %2$s', 'exc-uploader-theme'), get_comment_date(),  get_comment_time() ); ?>
                    </span>

                    <?php edit_comment_link( __( '(edit)', 'exc-uploader-theme' ), '  ', '' );?>
                </div>

                <?php if ( $comment->comment_approved == '0' ) :?>
                    <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'exc-uploader-theme' );?></em>
                    <br />
                <?php endif; ?>

                <?php comment_text();?>

                <?php //@TODO: control the button backgroundthrough parent?>
                <div class="reply">
                    <?php comment_reply_link( array_merge( $args, array( 'add_below' => true, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                </div>

            </div>
    <?php
    }
}

// User meta
// DEPRECIATED in favour of exc_get_user_meta
if ( ! function_exists( 'mf_get_user_meta' ) )
{
    function mf_get_user_data( $user_id = '' )
    {
        $user_id = $user_id ? $user_id : get_current_user_id();

        if ( ! $user_id )
        {
            return array();
        }

        $data = array();

        $data['user_data'] = get_userdata( $user_id );

        if ( empty( $data['user_data'] ) )
        {
            return array(); // Return if we don't have user data
        }

        $data['user_meta'] = array_map( function( $a ){ return $a[0]; }, get_user_meta( $user_id ) );

        return $data;
    }
}

// DEPRECIATED in favour of exc_layout_content_width
if ( ! function_exists( 'mf_layout_content_width') )
{
    function mf_layout_content_width( $structure = '' )
    {
        switch( $structure )
        {
            case "full-width" : return 'col-lg-12';
                break;

            case "left-sidebar" : return 'col-md-9 col-sm-8';
                break;

            case "right-sidebar" : return 'col-md-9 col-sm-8';
                break;

            default : return "col-md-6 col-sm-8";
                break;
        }
    }
}

// DEPRECIATED in favour of exc_layout_structure
if ( ! function_exists( 'mf_layout_structure' ) )
{
    function mf_layout_structure( $page_slug = '' )
    {
        $layout = array();
        $structure = array();
        $force_default_settings = false;

        if ( is_singular() || is_preview() )
        {
            if ( is_singular( 'exc_radio_post' ) )
            {
                $layout = get_post_meta( get_the_ID(), 'mf_radio', true );

            } elseif( ! $layout = get_post_meta( get_the_ID(), 'mf_layout', true ) )
            {
                $force_default_settings = true;
            }

        } else
        {
            if ( is_category() )
            {
                $slug   = 'categories';
                // Quick fix for key

                $structure = exc_get_option( 'mf_layout' );

                $force_default_settings = ( exc_kv( $structure, 'categories/force_settings') == 'on' ) ? true : false;

                if ( ! $force_default_settings )
                {
                    $cat_id = get_query_var('cat');
                    $layout = get_option( 'taxonomy_meta_' . $cat_id );
                }

            } elseif ( is_tag() )
            {
                $slug = 'tags';

                $structure = exc_get_option( 'mf_layout' );
                $force_default_settings = ( exc_kv( $structure, $slug . '/force_settings') == 'on' ) ? true : false;

                if ( ! $force_default_settings )
                {
                    $tag_id = get_query_var('tag_id');
                    $layout = get_option( 'taxonomy_meta_' . $tag_id );
                }

            } elseif( is_search() )
            {
                $slug = 'search';

            } elseif ( $custom_page = get_query_var('exc_custom_page') )
            {
                $slug = ( $page_slug ) ? $page_slug : str_replace( '-', '_', $custom_page );

            } elseif ( is_tax( 'genre' ) )
            {
                $slug   = 'radio_genre';

                // Quick fix for key
                $structure = exc_get_option( 'mf_layout' );
                $force_default_settings = ( exc_kv( $structure, 'radio_genre/force_settings') == 'on' ) ? true : false;

                if ( ! $force_default_settings )
                {
                    $term_id = get_queried_object_id();
                    $layout = get_option( 'taxonomy_meta_' . $term_id );
                }

            } elseif ( is_archive() )
            {
                $slug = 'archives';
            }
        }

        if ( empty( $layout ) || $force_default_settings )
        {
            $structure = exc_get_option( 'mf_layout' );

            $slug = ( isset( $slug ) ) ? $slug : 'default_settings';

            if ( $settings = exc_kv( $structure, $slug ) )
            {
                foreach ( $settings as $k => $v )
                {
                    $layout[ 'block-layout-' . $k ] = $v;
                }
            }

            $layout['block-layout-autoload'] = exc_kv( $structure, 'pagination/ajax_pagi' );
        }

        if ( strstr( key( $layout ), 'wpblock' ) )
        {
            // Normalize WPBLOCK
            foreach( $layout as $k => $v )
            {
                $new_key = str_replace( 'wpblock', 'block', $k );

                $layout[ $new_key ] = $v;
                unset( $layout[ $k ] );
            }
        }

        $layout = wp_parse_args(
                            $layout,
                            array(
                                'block-layout-header'           => '',
                                'block-layout-slider'           => '',
                                'block-layout-left_sidebar'     => '',
                                'block-layout-right_sidebar'    => '',
                                'block-layout-structure'        => 'full-width',
                                'block-layout-content_width'    => mf_layout_content_width( exc_kv( $layout, 'block-layout-structure', 'full-width' ) ),
                                'block-layout-active_view'      => 'grid',
                                'block-layout-columns'          => 4,
                                'block-layout-list_columns'     => 2,
                                'block-layout-show_filtration'  => 'on',
                                'block-layout-post_type'        => array( 'post' ),
                                'block-layout-slider'           => '',
                                'block-layout-revslider_id'     => '',
                                'block-layout-autoload'         => 'on'
                            )
                        );

        defined( 'EXC_THEME_HEADER' ) or define( 'EXC_THEME_HEADER', exc_kv( $layout, 'block-layout-header' ) );
        defined( 'EXC_THEME_SLIDER' ) or define( 'EXC_THEME_SLIDER', exc_kv( $layout, 'block-layout-slider' ) );
        defined( 'EXC_THEME_REVSLIDER_ID' ) or define( 'EXC_THEME_REVSLIDER_ID', exc_kv( $layout, 'block-layout-revslider_id' ) );

        return $layout;
    }
}

// DEPRECIATED in favour of exc_header_style
if ( ! function_exists( 'exc_mf_header_style' ) )
{
    function exc_mf_header_style( $css, $opt_name = '' )
    {
        //$header = constant( "EXC_THEME_HEADER" ) ? EXC_THEME_HEADER : 'default';
        $header = exc_get_layout('header') ? exc_get_layout('header') : 'default';

        if ( $header )
        {
            $header_opt_name = THEME_PREFIX . 'header_' . $header . '_style';

            if ( $header_css = get_option( $header_opt_name ) )
            {
                $css .= "\n /* Custom Header Style */ \n" . $header_css;
            }
        }

        // Remove layout helper will add the support in future
        remove_filter( 'exc-onpage-style', 'exc_header_style' );

        return $css;
    }

    add_filter( 'exc-onpage-style', 'exc_mf_header_style', 8, 2 );
}

if ( ! function_exists( 'exc_send_mail' ) )
{
    function exc_send_mail( $settings, $meta, $args = array() )
    {
        $settings = wp_parse_args(
                            $settings,
                            array(
                                'status'        => '',
                                'content_type'  => 'text',
                                'subject'       => '',
                                'body'          => ''
                            )
                        );

        if ( $settings['status'] == 'on' )
        {
            $_mf =& exc_theme_instance();
            $_mf->load('core/mail_class');

            $meta = wp_parse_args(
                    $meta,
                    array(
                        'to'            => '',
                        'from'          => '',
                        'from_name'     => '',
                        'contentType'   => $settings['content_type'],
                        'subject'       => $_mf->mail->parse_template( exc_kv( $settings, 'subject' ), $args ),
                        'message'       => $_mf->mail->parse_template( exc_kv( $settings, 'body' ), $args )
                    )
                );

            return $_mf->mail->send( $meta );
        }
    }
}

// Contact us page
if ( ! function_exists( 'exc_mf_contact_page' ) )
{
    function exc_mf_contact_page( $rule )
    {
        //reference of extracoding uploader object
        $_mf =& exc_theme_instance();

        //Contact us form and fields
        if ( exc_kv( $rule, 'slug' ) == 'contact')
        {
            $config = $_mf->load_config_file( 'widgets/contact' );
            $_mf->wp_admin->prepare_form( $config );

            //send email
            if ( count( $_POST ) )
            {
                $_mf->load('core/mail_class');

                if ( count( $_mf->validation->_error_array ) == 0 )
                {
                    if ( ! $_mf->form->is_nonce_verified('widgets_contact') )
                    {
                        exc_die( __('Page Expired!!', 'exc-uploader-theme' ) );
                    }

                    $mail_settings = get_option('mf_mail_settings');

                    $args = array(
                                'to'        => exc_kv( $mail_settings, 'to' ),
                                'subject'   => $_mf->validation->set_value('subject'),
                                'message'   => $_mf->validation->set_value('message'),
                            );

                    $user = array();

                    if( is_user_logged_in() )
                    {
                        $userinfo   = wp_get_current_user();
                        $user       = array( 'name' => exc_get_user_name( $userinfo, true ), 'email' => $userinfo->user_email );

                    } else
                    {
                        $user = array( 'name' => $_mf->validation->set_value('name'), 'email' => $_mf->validation->set_value('email') );
                    }

                    $args['from']           = $user['email'];
                    $args['from_name']      = $user['name'];
                    $args['reply-to']       = $user['email'];
                    $args['reply-to-name']  = $user['name'];

                    if ( $_mf->mail->send( $args ) )
                    {
                        $_mf->validation->add_success_msg( __( 'Thank you for contacting us, we will get back to you soon.', 'exc-uploader-theme' ) );

                        //Manaully clear if message sent successfully
                        $fields = $_mf->form->get_fields_list( 'widgets/contact' );

                        foreach ( $fields as $field )
                        {
                            $field->clear_value();
                        }
                    }
                }
            }
        }
    }

    //Callback hook for contact us page
    add_action( 'exc_custom_page', 'exc_mf_contact_page' );
}

if ( ! function_exists('exc_the_breadcrumbs') )
{
    function exc_the_breadcrumbs()
    {
        echo exc_theme_instance()->breadcrumbs->display();
    }
}

// Load the default functions after theme setup

if ( ! function_exists('exc_theme_setup') )
{
    function exc_theme_setup()
    {
        // Add languages support
        load_theme_textdomain( 'exc-uploader-theme', get_template_directory() . '/languages' );
        load_theme_textdomain( 'exc-framework', get_template_directory() . '/languages' );

        add_editor_style();

        register_nav_menus(
                array(
                    'main-menu'     => __( 'Main Menu', 'exc-uploader-theme' ),
                    'footer-menu'   => __( 'Footer Menu', 'exc-uploader-theme' )
                )
            );
    }
}

add_action('after_setup_theme', 'exc_theme_setup');

if ( ! function_exists('mf_exc_activation_hook') )
{
    function mf_exc_activation_hook()
    {
        global $wpdb;

        // Install theme options
        $charset_collate = $wpdb->get_charset_collate();

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $sql = "CREATE TABLE {$wpdb->prefix}exc_followers (
                  follower_id bigint(20) NOT NULL AUTO_INCREMENT,
                  follower_author_id bigint(20) NOT NULL,
                  follower_user_id bigint(20) NOT NULL DEFAULT '0',
                  follower_subscriber_id bigint(20) NOT NULL DEFAULT '0',
                  follower_status tinyint(1) NOT NULL DEFAULT '1',
                  PRIMARY KEY  (follower_id),
                  KEY following_user_id (follower_author_id)
                ) $charset_collate";

        dbDelta( $sql );

        $sql = "CREATE TABLE {$wpdb->prefix}exc_subscribers (
                  subscriber_id bigint(20) NOT NULL AUTO_INCREMENT,
                  subscriber_name varchar(50) NOT NULL,
                  subscriber_email varchar(100) NOT NULL,
                  subscriber_type enum('site','author') NOT NULL DEFAULT 'site',
                  subscriber_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  subscriber_activation_key varchar(60) NOT NULL,
                  subscriber_status tinyint(4) NOT NULL DEFAULT '0',
                  PRIMARY KEY  (subscriber_id),
                  KEY subscriber_email (subscriber_email)
                ) $charset_collate";

        dbDelta( $sql );

        $sql = "CREATE TABLE {$wpdb->prefix}exc_votes (
                  vote_id bigint(20) NOT NULL AUTO_INCREMENT,
                  author_id bigint(20) NOT NULL,
                  post_id bigint(20) NOT NULL,
                  user_id bigint(20) NOT NULL,
                  status tinyint(1) NOT NULL DEFAULT '1',
                  PRIMARY KEY  (vote_id),
                  KEY vote_post_id (post_id)
                ) $charset_collate";

        dbDelta( $sql );

        if ( ! get_option('mf_general_settings') ) {
            if ( $template = locate_template( 'includes/config/dummy_data/theme_options.php' ) )
            {
                require_once( $template );

                foreach( $options as $k => $v )
                {
                    add_option( $k, $v );
                }
            }

            $theme_data = wp_get_theme();
            $product_version = ( $theme_data->parent() ) ? $theme_data->parent()->get('Version') : $theme_data->get('Version');

            update_option( 'exc-uploader-theme-version', $product_version );
        }

        // Automatically Create and assign menu
        $theme_menus = array(
                    'main-menu'     => __( 'Main Menu', 'exc-uploader-theme' ),
                    'footer-menu'   => __( 'Footer Menu', 'exc-uploader-theme' )
                );

        $menu_locations = get_theme_mod( 'nav_menu_locations' );

        foreach ( $theme_menus as $menu_slug => $menuname ) {

            $menu_exists = wp_get_nav_menu_object( $menuname );

            if ( ! $menu_exists ) {

                $menu_id = ( $menu_exists ) ? $menu_exists->term_id : wp_create_nav_menu( $menuname );
                $menu_locations[ $menu_slug ] = $menu_id;
            }
        }

        if ( ! empty( $menu_locations ) ) {
            set_theme_mod( 'nav_menu_locations', $menu_locations );
        }
    }

    // Theme Activation Hook
    add_action( 'after_switch_theme', 'mf_exc_activation_hook' );
}

if ( ! function_exists( 'twentyfourteen_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_post_nav() {
    // Don't print empty markup if there's nowhere to navigate.
    $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
    $next     = get_adjacent_post( false, '', false );

    if ( ! $next && ! $previous ) {
        return;
    }

    ?>
    <nav class="navigation post-navigation" role="navigation">
        <h1 class="screen-reader-text"><?php _e( 'Post navigation', 'exc-uploader-theme' ); ?></h1>
        <div class="nav-links">
            <?php
            if ( is_attachment() ) :
                previous_post_link( '%link', __( '<span class="meta-nav">Published In</span> "%title"', 'exc-uploader-theme' ) );
            else :
                previous_post_link( '%link', __( '<span class="meta-nav previous">Previous Post</span> "%title"', 'exc-uploader-theme' ) );
                next_post_link( '%link', __( '<span class="meta-nav">Next Post</span> "%title"', 'exc-uploader-theme' ) );
            endif;
            ?>
        </div><!-- .nav-links -->
    </nav><!-- .navigation -->
    <?php
}
endif;