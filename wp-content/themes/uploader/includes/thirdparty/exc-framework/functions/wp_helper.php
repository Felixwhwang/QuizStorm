<?php defined('ABSPATH') OR die('restricted access');

if ( ! function_exists( 'exc_revslider_list' ) )
{
    function exc_revslider_list()
    {
        $arrSliders = array();

        if ( class_exists('RevSlider') )
        {
            try {

                $slider = new RevSlider();
                $arrSliders = $slider->getArrSlidersShort();

            } catch( Exception $e ){}
        }

        return $arrSliders;
    }
}

if ( ! function_exists( 'exc_vc_build_link' ) )
{
    function exc_vc_build_link( $value )
    {
        if ( function_exists( 'vc_build_link' ) )
        {
            $result = vc_build_link( $value );
        } else
        {
            $result = array( 'url' => '', 'title' => '', 'target' => '' );

            $params_pairs = explode( '|', $value );

            if ( ! empty( $params_pairs ) )
            {
                foreach ( $params_pairs as $pair )
                {
                    $param = explode( ':', $pair, 2 );

                    if ( ! empty( $param[0] ) && isset( $param[1] ) )
                    {
                        $result[ $param[0] ] = rawurldecode( $param[1] );
                    }
                }
            }
        }

        return array_map( 'trim', $result );
    }
}

// Get array of pages
if ( ! function_exists('exc_pages_list') )
{
    function exc_pages_list( $args = array() )
    {
        $pages = get_pages( $args );

        $list = array();

        if ( ! empty( $pages ) )
        {
            foreach ( $pages as $page )
            {
                $list[ $page->ID ] = $page->post_title;
            }
        }

        return $list;
    }
}

// Get array of posts
if ( ! function_exists('exc_posts_list') )
{
    function exc_posts_list( $args = array(), $show_all = true )
    {
        if ( $show_all = true )
        {
            $args['posts_per_page'] = -1;
        }

        $pages = get_posts( $args );

        $list = array();

        if ( ! empty( $pages ) )
        {
            foreach ( $pages as $page )
            {
                $list[ $page->ID ] = $page->post_title;
            }
        }

        wp_reset_query();

        return $list;
    }
}

if ( ! function_exists( 'exc_count_user_posts') )
{
    function exc_count_user_posts( $user_id, $post_type = 'post', $post_status = array( 'publish', 'private' ) )
    {
        global $wpdb;

        if ( ! is_array( $post_type ) )
        {
            $post_type = array( $post_type );
        }

        $types = array();

        foreach ( $post_type as $type )
        {
            $types[] = "post_type = '" . $type . "'";
        }

        $status = array();

        foreach ( $post_status as $state )
        {
            $status[] = "post_status = '" . $state . "'";
        }

        $where = "WHERE post_author = " . $user_id . " AND (" . implode( ' OR ', $types ) . ") AND (" . implode( ' OR ', $status ) . ")";

        $count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );

        return apply_filters( 'get_usernumposts', $count, $user_id );
    }
}

if ( ! function_exists('exc_get_categories_list') )
{
    function exc_get_categories_list( $args = array(), $label = '' )
    {
        $data = ( $label ) ? array( $label ) : array();

        $categories = get_categories( $args );

        // @BUG: is_wp_error is not working
        if ( ! empty( $categories['errors'] ) )
        {
            return $data;
        }

        foreach ( $categories as $category )
        {
            $data[ $category->term_id ] = $category->name;
        }

        return $data;
    }
}

if( ! function_exists('exc_get_tags_list') )
{
    function exc_get_tags_list( $args = array() )
    {
        $tags = get_tags( $args );

        $data = array();

        foreach ( $tags as $tag )
        {
            $data[ $tag->term_id ] = $tag->name;
        }

        return $data;
    }
}

if ( ! function_exists( 'exc_get_roles' ) )
{
    function exc_get_roles( $exclude = array() )
    {
        $roles = $GLOBALS['wp_roles']->get_names();

        foreach ( $exclude as $role )
        {
            unset( $roles[ $role ] );
        }

        return $roles;
    }
}

if( ! function_exists('exc_get_views') )
{
    function exc_get_views( $update = false, $post_id = '' )
    {
        $post_id = ( $post_id ) ? $post_id : get_the_ID();

        if ( ! $post_id )
        {
            return 0;
        }

        $count = ( $c = get_post_meta( $post_id, '_exc_views_count', true ) ) ? $c : 0;

        if ( $update )
        {
            $count = exc_set_views( $post_id );
        }

        return $count;
    }
}

if( ! function_exists('exc_set_views') )
{
    function exc_set_views( $post_id = '' )
    {
        $post_id = ( $post_id ) ? $post_id : get_the_ID();

        $count = ( $c = get_post_meta( $post_id, '_exc_views_count', true ) ) ? $c : 0;
        $count++;

        update_post_meta( $post_id, '_exc_views_count', $count );

        return $count;
    }
}

if( ! function_exists( 'exc_views_format' ) )
{
    function exc_views_format($views)
    {
        if ( $views >= 1000 )
        {
            return $views/1000 . "k";   // NB: you will want to round this
        } else
        {
            return $views;
        }
    }
}

/**
 * current_user_role
 *
 * Get the current wordpress user role
 *
 * @access  public
 * @example current_user_role();
 * @param   void
 * @return  string
 */

if ( ! function_exists( 'current_user_role' ) )
{
    function current_user_role()
    {
        return key( $GLOBALS['current_user']->caps );
    }
}

if ( ! function_exists( 'exc_get_user_name' ) )
{
    function exc_get_user_name( $user, $return = false )
    {
        if ( ! is_object( $user ) && intval( $user ) )
        {
            $user = get_userdata( $user );
        }

        if ( isset( $user->ID ) )
        {
            $name = array_filter( array( get_user_meta( $user->ID, 'first_name', true ), get_user_meta( $user->ID, 'last_name', true ) ) );

            $display_name = ( count( $name ) ) ? implode( ' ', $name ) : $user->display_name;

            if ( $return )
            {
                return esc_html( $display_name );
            }

            echo esc_html( $display_name );
        }
    }
}

if ( ! function_exists( 'exc_get_user_meta' ) )
{
    function exc_get_user_data( $user_id = '' )
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

if ( ! function_exists( 'exc_get_avatar' ) )
{
    function exc_get_avatar( $size, $wp_size = 'thumbnail', $user_id = '', $return = false, $class = '' )
    {
        $user_id = ( intval( $user_id ) ) ? $user_id : get_current_user_id();

        $attachment_id = (int) get_the_author_meta( 'profile_image', $user_id );

        $classname = ( isset( $class['class'] ) ) ? $class['class'] : '';

        if ( $attachment_id ) {
            $attachment = wp_get_attachment_image_src( $attachment_id, $wp_size );
            $image = '<img src="' . esc_url( $attachment[0] ) . '" alt="' . esc_attr( exc_get_user_name( $user_id, true ) ) . '" class="' . esc_attr( $classname ) . '" />';

        } else {
            $image = get_avatar( $user_id, $size, '', '', array( 'class' => $classname ) );
        }

        if ( $return ) {
            return $image;
        }

        echo $image;
    }
}

if ( ! function_exists( 'exc_get_image_sizes' ) )
{
    function exc_get_image_sizes( $size = '', $normalize = true )
    {
        global $_wp_additional_image_sizes;

        $sizes = array();
        $get_intermediate_image_sizes = get_intermediate_image_sizes();

        // Create the full array with sizes and crop info
        foreach( $get_intermediate_image_sizes as $_size )
        {
            if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) )
            {
                if ( $normalize )
                {
                    $cropped = (bool) get_option( $_size . '_crop' ) ? ' [cropped]' : ' [resized]';
                    $sizes[ $_size ] = exc_to_text( $_size ) . ' ' . get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . $cropped;
                } else
                {
                    $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
                    $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
                    $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
                }

            } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) )
            {
                if ( $normalize )
                {
                    $cropped = $_wp_additional_image_sizes[ $_size ]['crop'] ? ' [cropped]' : ' [resized]';
                    $sizes[ $_size ] = exc_to_text( $_size ) . ' ' . $_wp_additional_image_sizes[ $_size ]['width'] . 'x' . $_wp_additional_image_sizes[ $_size ]['height'] . $cropped;
                } else
                {
                    $sizes[ $_size ] = array(
                        'width' => $_wp_additional_image_sizes[ $_size ]['width'],
                        'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                        'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
                    );
                }
            }
        }

        // Add original Image support
        $sizes['full'] = esc_html__( 'Original Image [uncropped]', 'exc-framework' );

        // Get only 1 size if found
        if ( $size )
        {
            if ( isset( $sizes[ $size ] ) )
            {
                return $sizes[ $size ];
            } else {
                return false;
            }

        }

        return $sizes;
    }
}

if ( ! function_exists( 'exc_taxonomy_meta' ) )
{
    function exc_taxonomy_meta( $term_id )
    {
        if ( ! intval( $term_id ) )
        {
            return array();
        }

        $opt_key = 'taxonomy_meta_' . $term_id;

        return get_option( $opt_key, array() );
    }
}