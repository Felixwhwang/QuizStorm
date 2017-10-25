<?php defined('ABSPATH') OR die('restricted access');

$GLOBALS['exc_layout_args'] = array(
                        'header'            => '',
                        'footer'            => '',
                        'slider'            => '',
                        'left_sidebar'      => '',
                        'right_sidebar'     => '',
                        'structure'         => 'full-width',
                        'content_width'     => '',
                        'active_view'       => 'grid',
                        'columns'           => 4,
                        'list_columns'      => 2,
                        'show_filtration'   => 'on',
                        'post_type'         => array('post'),
                        'revslider_id'      => '',
                        'autoload'          => 'on'
                    );

if ( ! function_exists( 'exc_add_layout_args' ) )
{
    function exc_add_layout_args( $args = array(), $value = '' )
    {
        global $exc_layout_args;

        if ( ! is_array( $args ) )
        {
            $args = array( $args => $value );
        }

        $exc_layout_args = wp_parse_args(
                                $args,
                                $exc_layout_args
                            );

        return $exc_layout_args;
    }
}

if ( ! function_exists( 'exc_delete_layout_args' ) )
{
    function exc_delete_layout_args()
    {
        global $exc_layout_args;

        $args = func_get_args();

        if ( ! empty( $args ) )
        {
            foreach( $args as $arg )
            {
                if ( isset( $exc_layout_args[ $arg ] ) )
                {
                    unset( $exc_layout_args[ $arg ] );
                }
            }
        }

        return $exc_layout_args;
    }
}

// Update the default value
if ( ! function_exists('exc_update_layout_args') )
{
    function exc_update_layout_args( $args = array(), $value = '' )
    {
        global $exc_layout_args;

        if ( ! is_array( $args ) )
        {
            $args = array( $args => $value );
        }

        foreach ( $args as $k => $v )
        {
            $exc_layout_args[ $k ] = $v;
        }

        return $exc_layout_args;
    }
}

if ( ! function_exists('exc_get_layout') )
{
    function exc_get_layout( $key, $default = false )
    {
        global $exc_layout_args;

        if ( empty( $exc_layout_args[ $key ] ) )
        {
            return $default;
        }

        return $exc_layout_args[ $key ];
    }
}


if ( ! function_exists( 'exc_get_layout_settings' ) )
{
    function &exc_get_layout_settings()
    {
        return $GLOBALS['exc_layout_args'];
    }
}

// Layout structure
if ( ! function_exists( 'exc_layout_structure' ) )
{
    function exc_layout_structure( $prefix = '', $db_key = 'exc_layout', $additional_args = array() )
    {
        global $exc_layout_args;

        $db_key = apply_filters( 'exc_layout_default_db_key', $db_key );
        $default_theme_options_key = apply_filters( 'exc_layout_default_theme_options_key', 'exc_layout' );

        $layout = array();
        $force_default_settings = false;

        if ( is_singular() || is_preview() )
        {
            // Fetch Meta Information
            if ( ! $layout = get_post_meta( get_the_ID(), $db_key, TRUE ) )
            {
                $force_default_settings = TRUE;

            } else {

                foreach ( $layout as $k => $v )
                {
                    if ( strpos( $k, 'exc_layout_' ) === 0 )
                    {
                        $key = substr( $k, strlen( 'exc_layout_' ) );

                        $layout[ $key ] = $layout[ $k ];

                        unset( $layout[ $k ] );
                    }
                }
            }

            $prefix = ( $prefix ) ? $prefix : 'exc_layout';

        } else
        {
            if ( is_category() )
            {
                // Quick fix for key
                //$force_default_settings = ( exc_kv( $structure, 'categories/force_settings') == 'on' ) ? true : false;

                //if ( ! $force_default_settings )
                //{
                    $cat_id = get_query_var('cat');
                    $layout = get_option( 'taxonomy_meta_' . $cat_id, array() );
                //}

                if ( empty( $layout ) )
                {
                    $prefix = ( $prefix ) ? $prefix : 'categories';
                } else
                {
                    $prefix = ( $prefix ) ? $prefix : 'exc_layout';
                }

            } elseif ( is_tag() )
            {
                //$prefix = ( $prefix ) ? $prefix : 'tags';

                //$force_default_settings = ( exc_kv( $structure, $prefix . '/force_settings') == 'on' ) ? true : false;

                //if ( ! $force_default_settings )
                //{
                    $tag_id = get_query_var('tag_id');
                    $layout = get_option( 'taxonomy_meta_' . $tag_id, array() );
                //}

                if ( empty( $layout ) )
                {
                    $prefix = ( $prefix ) ? $prefix : 'tags';

                } else
                {
                    $prefix = ( $prefix ) ? $prefix : 'exc_layout';
                }

            } elseif ( is_search() )
            {
                $prefix = ( $prefix ) ? $prefix : 'search';

            } elseif ( $custom_page = get_query_var('exc_custom_page') )
            {
                $prefix = ( $prefix ) ? $prefix : str_replace( '-', '_', $custom_page );

            } elseif ( is_tax() )
            {
                //$force_default_settings = ( exc_kv( $structure, $prefix . '/force_settings') == 'on' ) ? true : false;

                //if ( ! $force_default_settings )
                //{
                    $term_id = get_queried_object_id();
                    $layout = get_option( 'taxonomy_meta_' . $term_id );
                //}

                // if ( empty ( $layout ) )
                // {
                //  // Fetch the values from theme options
                //  $prefix = 'default';
                // }

            } elseif ( is_author() )
            {
                $prefix = ( $prefix ) ? $prefix : 'authors';

            } elseif ( is_archive() )
            {
                $prefix = ( $prefix ) ? $prefix : 'archives';

            } elseif ( is_front_page() )
            {
                $prefix = 'default';
            }
        }

        $exc_layout_args = apply_filters( 'exc_layout_args', $exc_layout_args, $prefix, $db_key );

        if ( ! empty( $additional_args ) )
        {
            $exc_layout_args = array_merge( $exc_layout_args, $additional_args );
        }

        if ( ! empty( $prefix ) && substr( $prefix, -1 ) != '_' )
        {
            $prefix = $prefix . '_';
        }

        // Fetch the default theme option settings
        $layout_settings = get_option( $default_theme_options_key );

        // Prefer theme options force settings option
        if ( ! empty( $layout_settings[ $prefix . 'force_settings'] ) ) {
            $force_default_settings = TRUE;
        }

        if ( empty( $layout ) || $force_default_settings )
        {
            $layout = ( $layout ) ? $layout : $exc_layout_args;

            // Theme Option Settings are not available so return the built-in settings
            if ( ! empty( $layout_settings ) )
            {
                $prefix = ( isset( $prefix ) ) ? $prefix : '';

                //$layout = $exc_layout_args;

                if ( ! $prefix )
                {
                    foreach ( $exc_layout_args as $k => $v )
                    {
                        if ( isset( $layout_settings[ $k ] ) )
                        {
                            $layout[ $k ] = $layout_settings[ $k ];
                        }
                    }

                } else
                {
                    $prefix_length = strlen( $prefix );

                    foreach ( $layout_settings as $k => $v )
                    {
                        if ( strpos( $k, $prefix ) === 0 )
                        {
                            $key = substr( $k, strlen( $prefix ) );
                            $layout[ $key ] = $layout_settings[ $k ];
                        }
                    }
                }
            }

            $layout = wp_parse_args( $layout, $exc_layout_args );

        } else
        {
            if ( $prefix )
            {
                $prefix_length = strlen( $prefix );

                foreach ( $layout as $k => $v )
                {
                    if ( strpos( $k, $prefix ) === 0 )
                    {
                        $layout[ substr( $k, $prefix_length ) ] = $v;

                        unset( $layout[ $k ] );
                    }
                }
            }

            $layout = wp_parse_args( $layout, $exc_layout_args );
            //$exc_layout_args = apply_filters( 'exc_post_layout_args', wp_parse_args( $layout, $exc_layout_args ) );
        }

        // Automatically add content width
        $layout[ 'content_width' ] = exc_layout_content_width( exc_kv( $layout, 'structure', 'full-width' ) );

        $exc_layout_args = apply_filters( 'exc_post_layout_args', $layout );

        return $exc_layout_args;
    }
}

// Show BreadCrumbs
if ( ! function_exists('exc_the_breadcrumbs') )
{
    function exc_the_breadcrumbs( $args = array() )
    {
        echo exc_theme_instance()->load('core/breadcrumbs_class')->display( $args );
    }
}

if ( ! function_exists( 'exc_header_style' ) )
{
    function exc_header_style( $css, $opt_name = '' )
    {
        $header = exc_get_layout('header', 'default');

        // @TODO: remove THEME_PREFIX
        if ( $header )
        {
            $header_opt_name = THEME_PREFIX . 'header_' . $header . '_style';

            if ( $header_css = get_option( $header_opt_name ) )
            {
                $css .= "\n /* Custom Header Style */ \n" . $header_css;
            }
        }

        return $css;
    }

    add_filter( 'exc-onpage-style', 'exc_header_style', 10, 2 );
}

if ( ! function_exists( 'exc_grid_class' ) )
{
    function exc_grid_class( $column = '4', $base = 'col-md-{column}' )
    {
        if ( is_numeric( $column ) )
        {
            $column = '1/' . $column;
        }

        // Columns
        $columns = trim( str_replace( '1/', '', $column ) );

        if ( ! intval( $columns ) || $columns > 6 )
        {
            $columns = 6;
        }

        preg_match( '/(\d)\/(\d)/', $column, $matches );

        if ( ! empty( $matches ) )
        {
            $x = ( $matches[1] == 0 || $matches[1] > 6 ) ? 6 : $matches[1];
            $y = ( $matches[2] == 0 || $matches[2] > 6 ) ? 6 : $matches[2];

            return str_replace( '{column}', ceil( $x / $y * 12 ), $base );
        }
    }
}

if ( ! function_exists( 'exc_layout_content_width') )
{
    function exc_layout_content_width( $structure = '' )
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

if ( ! function_exists( 'exc_get_post_thumbnail_size' ) )
{
    function exc_get_post_thumbnail_size( $columns = 2, $masonry = true )
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

// Paginate links
if ( ! function_exists( 'exc_paginate_links' ) )
{
    function exc_paginate_links( $echo = true )
    {
        // Don't print empty markup if there's only one page.
        if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
            return;
        }

        $paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
        $pagenum_link = html_entity_decode( get_pagenum_link() );
        $query_args   = array();
        $url_parts    = explode( '?', $pagenum_link );

        if ( isset( $url_parts[1] ) ) {
            wp_parse_str( $url_parts[1], $query_args );
        }

        $pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
        $pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

        $format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
        $format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

        // Set up paginated links.
        $links = paginate_links( array(
            'base'     => $pagenum_link,
            'format'   => $format,
            'total'    => $GLOBALS['wp_query']->max_num_pages,
            'current'  => $paged,
            'mid_size' => 1,
            'add_args' => array_map( 'urlencode', $query_args ),
            'prev_text' => __( '&larr; Previous', 'exc-framework' ),
            'next_text' => __( 'Next &rarr;', 'exc-framework' ),
        ) );

        $pagination = '<div class="wp-pagination"> ' . $links . '</div>';

        if ( $echo )
        {
            echo $pagination;
        } else
        {
            return $pagination;
        }
    }
}

if ( ! function_exists( 'exc_icon_class' ) )
{
    function exc_icon_class( $class = '' )
    {
        if ( preg_match( '/^(\w+)/', $class, $matches ) && ! empty( $matches[1] ) )
        {
            switch( $matches[1] )
            {
                case "glyphicon" :
                        $class = "glyphicon " . $class;
                    break;

                case "fa" :
                        $class = "fa " . $class;
                    break;
            }
        }

        return $class;
    }
}

// Page title
if ( ! function_exists('exc_wp_title') )
{
    function exc_wp_title( $title )
    {
        if ( empty( $title ) && ( is_home() || is_front_page() ) )
        {
            $title = get_bloginfo( 'title' );
        }

        return apply_filters( 'exc_wp_title', $title );
    }

    add_filter( 'pre_get_document_title', 'exc_wp_title' );
}

if ( ! function_exists( 'exc_load_template' ) )
{
    function exc_load_template( $template, $args = array(), $return = false, &$instance = '' )
    {
        if ( ! is_a( $instance, 'exc_base_class' ) )
        {
            $instance =& exc_theme_instance();
        }

        return $instance->load_template( $template, $args, $return );
    }
}