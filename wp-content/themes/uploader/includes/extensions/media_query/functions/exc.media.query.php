<?php defined('ABSPATH') OR die('restricted access');

$GLOBALS['exc_media_query_extension_instance'] =& $this;

if ( ! function_exists( 'exc_media_query' ) ) :

function exc_media_query( $atts = array() )
{
    global $exc_media_query_extension_instance;

    // @TODO: add pagination support navigation|loadmore_button|load_on_scroll

    if ( ! is_a( $exc_media_query_extension_instance, 'eXc_Base_Class' ) ) {
        // @TODO: LOG SYSTEM ERROR
        return;
    }

    $atts = exc_normalize_media_query_vars( $atts );

    // User can view max. 1000 results
    if ( $atts['offset'] > 1000 ) {
        exc_die(
            __(
                'Media search limit reached, please use filter to narrow search
                result.',
                'exc-framework'
            )
        );
    }

    // Add query id
    //$atts['query_id'] = exc_shortcode_id();

    $active_view = ( ! empty( $atts['active_view'] ) ) ? $atts['active_view'] : 'grid';

    $masonry = false;

    // Make sure that the autoload value is load_on_scroll when autoload is true
    // DEPRECIATED
    // if ( $atts['autoload'] )
    // {
    //     $atts['pagination'] = 'load_on_scroll';
    // }

    $pagination = $atts['pagination'];

    if ( $atts['active_view'] == 'grid' ) {
        $columns  = $atts['columns'];
        //$autoload = $atts['autoload'];
        $template = $atts['template'];
        $masonry  = $atts['masonry'];

        $container_class = ( ! empty( $atts['container_class'] ) ) ? ' ' . str_replace( '{columns}', $columns, $atts['container_class'] ) : '';

        $class    = apply_filters( 'exc_media_query_gridview_class', $container_class, $atts );

    } else {
        $columns  = $atts['list_columns'];
        $masonry  = $atts['list_masonry'];
        //$autoload = $atts['list_autoload'];
        $template = $atts['list_template'];

        $container_class = ( ! empty( $atts['list_container_class'] ) ) ? ' ' . str_replace( '{columns}', $columns, $atts['list_container_class'] ) : '';

        $class = apply_filters( 'exc_media_query_listview_class', $container_class, $atts );
    }

    if ( $atts['thumb_size'] == 'auto' ) {
        $atts['thumb_size'] =
                ( function_exists('exc_get_post_thumbnail_size') )
                    ? exc_get_post_thumbnail_size( $columns, $masonry )
                    : 'medium';
    }

    // Custom Pages Query update
    $page = ( ! empty( $atts['page'] ) ) ? $atts['page'] : get_query_var( 'exc_custom_page' );

    if ( $page ) {
        do_action_ref_array( 'exc_media_query_filtration', array( "page" => $page, "atts" => &$atts ) );
    }

    //@TODO: normalize WP_Query parameters
    // And all http://codex.wordpress.org/Class_Reference/WP_Query#Parameters

    // pass All variables to wordpress as it has functionality to filter the ones required
    if ( $is_ajax = exc_is_ajax_request() ) {
        add_filter( 'posts_results', 'exc_media_query_posts_results' );

        // Do not include protected statues
        add_filter( 'posts_where', 'exc_media_query_filter' );
    }

    // Search support
    if ( empty( $atts['s'] ) ) {
        unset( $atts['s'] );
    }

    if ( ! empty( $atts["paged"] ) ) {
        $atts["offset"] = "";
    }

    // Query post
    if ( ! $atts['_parent_query'] ) {
        query_posts( $atts );
    }

    // Write query data
    if ( $atts['_media_query'] && $atts["_cache_query"] ) {
        if ( empty( $atts['query_id'] ) ) {
            $atts['query_id'] = md5( serialize( $atts ) );
        }

        set_transient( "exc_media_query_{$atts['query_id']}", $atts );
    }

    ob_start();

    if ( have_posts() ) {
        exc_load_template( $template, array( 'settings' => $atts ), false, $exc_media_query_extension_instance );

    } else {
        get_template_part( 'content', 'none' );
    }

    $content = ob_get_contents();

    ob_end_clean();

    $output = '';

    if ( ! $atts['_media_query'] ) {
        // Reset Query
        wp_reset_query();

        return $content;

    } elseif ( $is_ajax ) {
        if ( have_posts() ) {

            wp_send_json_success(
                apply_filters(
                    'exc_media_query_js_vars',
                    array(
                        'html'      => $content,
                        'counter'   => $GLOBALS['wp_query']->found_posts,
                        'masonry'   => $masonry,
                        'class'     => $class,
                        'isRtl'     => is_rtl(),
                        'pagi'      => ( $atts['pagination'] ) ? exc_paginate_links( false ) : ''
                    )
                )
            );

        } else {
            if ( $atts['offset'] ) {
                exc_die();
            } else {
                exc_die( $content );
            }
        }

    } else {

        if ( have_posts() ) {
            // Automatically display pagination
            if ( $atts['offset'] == 0 ) {

                // @TODO: pass data attributes for additional functionalities
                $data_attributes = array( "data" => apply_filters( "exc_media_query_attributes", array(
                        "masonry"      => esc_attr( $masonry ),
                        "pagination"   => esc_attr( $pagination ),
                        "pk"           => esc_attr( $atts['query_id'] ),
                        "counter"      => esc_attr( $GLOBALS['wp_query']->found_posts ),
                        "security"     => wp_create_nonce( 'exc-media-filter' ),
                        "counter-text" => ""
                    ), $atts ) );

                $output = '<div class="exc-media-container" ' . exc_parse_atts( $data_attributes ) . '>';

                if ( $atts['filtration'] ) {
                    wp_enqueue_script( 'exc-media-query' );

                    if ( $filtration_tmpl = apply_filters( 'exc_media_query_filtration_tmpl', $atts['filtration_tmpl'], $atts ) ) {
                        $output .= exc_load_template( $filtration_tmpl, array(), TRUE, $exc_media_query_extension_instance );
                    }
                }

                $container = ( ! empty( $atts['container'] ) ) ? $atts['container'] : 'div';

                $output .= '<' . $container . ' class="exc-media-content' . esc_attr( $class ) . '">' . $content . '</' . $container . '>';

                if ( $atts['pagination'] == 'navigation' ) {
                    $output .= exc_paginate_links( false );

                } elseif ( $atts['pagination'] == 'loadmore_button' ) {
                    $loadmore_button_html = '<div class="more-btn"><a class="btn exc-load-more"><i class="fa fa-plus-circle"></i>' . esc_html__('Load More', 'exc-uploader-theme') . '</a></div>';
                    $output .= apply_filters( 'exc_media_query_loadmore_button', $loadmore_button_html, $atts );
                }

                $output .= '</div>';

            } else {
                $output = $content;
            }

        } else {
            get_template_part('content', 'none');
        }
    }

    // Reset Query
    if ( ! $atts['_parent_query'] ) {
        wp_reset_query();
    }

    if ( ! $atts['echo'] ) {
        return $output;
    }

    echo $output;
}

add_shortcode( 'exc_media_query', 'exc_media_query' );

endif;

if ( ! function_exists( 'exc_normalize_media_query_vars' ) ) :

function exc_normalize_media_query_vars( $atts = array() )
{
    // List of wordpress query supported vars, don't worry we have extra
    // security layer so frontend user will not able to change all parameters
    $wordpress_query = new WP_Query();
    $wp_vars = $wordpress_query->fill_query_vars( array() );

    // Unset Query
    unset( $wordpress_query );

    $exclude = array(
                'error', 'subpost', 'subpost_id', 'attachment',
                'attachment_id', 'static', 'feed', 'tb', 'comments_popup',
                'preview', 'sentence', 'menu_order'
            );

    // Exclude wordpress private vars
    foreach ( $exclude as $ex ) {

        if ( isset( $wp_vars[ $ex ] ) ) {
            unset( $wp_vars[ $ex ] );
        }
    }

    // Make sure the date_query is allowed
    if ( ! empty( $atts['date_query'] ) ) {
        $wp_vars['date_query'] = array();
    }

    //$active_shortcode_id = exc_shortcode_id();

    $paged = ( is_front_page() ) ? get_query_var("page") : get_query_var("paged");

    if ( $paged ) {
        $wp_vars['paged'] = $paged;
    }

    $wp_vars['tax_query'] = array();

    // Filter the wp vars
    $wp_vars = apply_filters( 'exc_media_query_wp_vars', $wp_vars );

    $default_media_query_template = apply_filters(
                'exc_media_query_default_template',
                'template-parts/content'
            );

    // @TODO: add advanced option in backend to manage additional parameters
    $shortcode_vars =
        apply_filters(
            'exc_media_query_vars',
            array(
                'template'          => $default_media_query_template,
                'masonry'           => TRUE,
                'columns'           => 3,
                'words_limit'       => 20,
                'show_thumb'        => TRUE,
                'show_content'      => TRUE, //content
                'content_type'      => 'excerpt',
                'show_author'       => TRUE,
                'show_tags'         => TRUE,
                'thumb_size'        => 'auto',
                'filtration'        => TRUE,
                //'autoload'          => FALSE, // DEPRECIATED
                'container_class'   => 'col col-{columns}',
            )
        );

    if ( ! empty( $atts["list_view"] ) || ! empty( $shortcode_vars["list_view"] ) ) {
        foreach ( $shortcode_vars as $var => $value )
        {
            $list_var_name = 'list_' . $var;

            if ( $var == 'template' && ! isset( $atts[ $list_var_name ] ) )
            {
                $shortcode_vars[ $list_var_name ] = 'content-list-view';

            } elseif ( $var == 'masonry' && ! isset( $atts[ $list_var_name ] ) )
            {
                $shortcode_vars[ $list_var_name ] = false;

            } elseif ( $var == 'columns' && ! isset( $atts[ $list_var_name ] ) )
            {
                $shortcode_vars[ $list_var_name ] = 2;

            } else
            {
                $shortcode_vars[ $list_var_name ] =
                            ( isset( $shortcode_vars[ $list_var_name ] ) )
                                ? $shortcode_vars[ $list_var_name ]
                                : $value;
            }
        }

        $shortcode_vars['list_view'] = TRUE;
        //$active_view = ( isset( $atts['active_view'] ) ) ? $atts['active_view'] : 'grid';

    } /*else
    {
        $active_view = 'grid';
    }*/

    // default limit = wordpress posts per page limit
    $wordpress_posts_limit = get_option('posts_per_page');

    // Static parameters
    /**
     * container [parent HTML element to contain the shortcode HTML]
     * filtration_tmpl [The shortcode filtration bar template file]
     * active_view [There are 2 possible values grid & list]
     * visible_to [whom to display this shortcode, all, registered, unregistered]
     * placeholder [Display placeholder image if featured image is not available]
     * pagination [navigation|loadmore_button|load_on_scroll, loadmore_link]
     */
    $static_vars =
        array_merge(
            $wp_vars,
            $shortcode_vars,
            apply_filters(
                'exc_media_query_static_vars',
                array(
                    '_media_query'          => true,
                    '_parent_query'         => false,
                    '_cache_query'          => true,
                    'echo'                  => false,
                    'has_password'          => false,
                    'active_view'           => 'grid', //grid or list
                    'post_type'             => array( 'post' ),
                    'offset'                => 0,
                    'page'                  => '',
                    'orderby'               => 'post_date',
                    'order'                 => 'DESC',
                    'extras'                => array(), // Pass additional Parameters
                    'ignore_sticky_posts'   => true,
                    'posts_per_page'        => $wordpress_posts_limit,
                    'container'             => 'div',
                    'filtration_tmpl'       => '',
                    'visible_to'            => 'all',
                    'placeholder'           => FALSE,
                    'pagination'            => 'loadmore_button'
                )
            )
        );

    // Add support for shortcode to pass comma seperated arrays
    $arrays = array();

    foreach ( $static_vars as $var => $value )
    {
        if ( gettype( $value ) == 'array' )
        {
            $arrays[] = $var;
        }
    }

    $atts = shortcode_atts( $static_vars, $atts, 'exc_media_query' );

    // Normalize Arrays
    foreach ( $arrays as $arr )
    {
        if ( ! empty( $atts[ $arr ] ) && ! is_array( $atts[ $arr ] ) )
        {
            $atts[ $arr ] = explode( ',', str_replace(', ', ',', $atts[ $arr ] ) );
        }
    }

    return $atts;
}

endif;

if ( ! function_exists('exc_media_query_posts_results') ) :

function exc_media_query_posts_results( $posts )
{
    remove_filter( 'posts_results', 'exc_media_query_posts_results' );

    foreach( $posts as $id => $post )
    {
        if ( ! empty( $post->post_password ) )
        {
            $protected_title_format = apply_filters( 'protected_title_format', __( 'Protected: %s', 'exc-travelhub' ), $post );
            $posts[ $id ]->post_title = sprintf( $protected_title_format, $posts[ $id ]->post_title );

        } else if ( isset( $post->post_status ) && 'private' == $post->post_status )
        {
            $private_title_format = apply_filters( 'private_title_format', __( 'Private: %s', 'exc-travelhub' ), $post );
            $posts[ $id ]->post_title = sprintf( $private_title_format, $posts[ $id ]->post_title );
        }
    }

    return $posts;
}

endif;

if ( ! function_exists( 'exc_media_query_filter') ) :

function exc_media_query_filter( $where )
{
    $where = str_replace( "
                OR wp_posts.post_status = 'future'
                OR wp_posts.post_status = 'draft'
                OR wp_posts.post_status = 'pending'
            ", '', $where );

    remove_filter( 'posts_where', 'exc_media_query_filter', 10 );
    return $where;
}

endif;