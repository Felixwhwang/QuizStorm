<?php defined('ABSPATH') OR die('restricted access');

if ( ! function_exists( 'exc_mf_related_posts' ) )
{
    function exc_mf_related_posts( $atts = array() )
    {
        $atts = shortcode_atts(
                array (
                    'post_id'           => 0,
                    'columns'           => 4,
                    'posts_per_page'    => 4,
                    'template'          => 'modules/related_posts'
                ), $atts, 'mf_related_posts');

        $atts['post_id'] = ( (int) $atts['post_id'] ) ? $atts['post_id'] : get_the_ID();

        $tag_ids = array();

        foreach( ( array ) wp_get_post_tags( $atts['post_id'] ) as $tag )
        {
            $tag_ids[] = $tag->term_id;
        }

        if ( ! empty( $tag_ids ) )
        {
            $atts = array_merge( $atts, array( '_media_query' => false, 'tags__in' => $tag_ids, 'post__not_in' => array( $atts['post_id'] ) ) );
            echo exc_mf_media_query( $atts );
        }
    }

    add_shortcode('mf_related_posts', 'exc_mf_related_posts');
}

if ( ! function_exists( 'exc_mf_author_posts' ) )
{
    function exc_mf_author_posts( $atts = array() )
    {
        $atts = shortcode_atts(
                array(
                    'post_id'           => 0,
                    'author'            => 0,
                    'columns'           => 4,
                    'orderby'           => 'rand',
                    'posts_per_page'    => 4,
                    'template'          => 'modules/author_posts',
                ), $atts, 'mf_author_posts');

        $post_id = ( (int) $atts['post_id'] ) ? $atts['post_id'] : get_the_ID();
        $atts['author'] = ( ( int ) $atts['author'] ) ? $atts['author'] : get_post_field( 'post_author', $post_id );

        if ( ! empty( $atts['author'] ) )
        {
            $atts = array_merge( $atts, array( '_media_query' => false ) );
            echo exc_mf_media_query( $atts );
        }
    }

    add_shortcode('mf_author_posts', 'exc_mf_author_posts');
}

if ( ! function_exists( 'exc_mf_media_upload') )
{
    function exc_mf_media_upload( $atts = array() )
    {
        $_exc_uploader = eXc_theme_instance();

        $media_settings = get_option( 'mf_uploader_settings' );

        // Return if media uploader status is down
        if ( ! exc_kv( $media_settings, 'status' ) )
        {
            exc_system_log( __("Calling media uploader but it's down.", 'exc-uploader-theme') );
            return;
        }

        $atts = shortcode_atts(
                array(
                    'heading_str'   => exc_kv( $media_settings, 'heading' ),
                    'about_str'     => exc_kv( $media_settings, 'about' ),
                    'btn_str'       => exc_kv( $media_settings, 'btn' ),
                    'or_str'        => exc_kv( $media_settings, 'or' ),
                    'dropfiles_str' => exc_kv( $media_settings, 'dropfiles' ),
                    'class'         => '',
                    'enqueue_script'=> true,
                    'template'      => 'modules/media_uploader'

                ), $atts);

        if ( $atts['enqueue_script'] ) // Empty uploader
        {
            wp_enqueue_script( 'exc-plupload' );

            $_exc_uploader->form->load_settings( 'uploader_frontend_post' );

            // @TODO: add condition to load template only we have uploader on this page
            exc_load_template( 'modules/templates/uploader', array( 'config' => $_exc_uploader->form->get_config( 'uploader_frontend_post' ) ) );
        }

        //Load Script
        //$atts['enqueue_script'] = $enqueue_script;

        @extract( $atts );

        exc_load_template( $template, $atts );
    }

    add_shortcode('mf_media_uploader', 'exc_mf_media_upload');
}

if ( ! function_exists( 'exc_mf_contact_form') )
{
    function exc_mf_contact_form( $atts = array() )
    {
        $atts = shortcode_atts(
                array(
                    'template' => 'modules/contact_form'
                ), $atts, 'exc_mf_contact_form');

        if( ! $atts['config'] = exc_theme_instance()->form->get_config( 'widgets/contact' ) )
        {
            wp_die( _x('You must prepare contact form fields before using it', 'extracoding uploader contact form shortcode', 'exc-uploader-theme') );
        }

        exc_load_template( $atts['template'], $atts );
    }

    add_shortcode( 'mf_contact_form', 'exc_mf_contact_form' );
}

if ( ! function_exists( 'exc_mf_media_query' ) )
{
    //extracoding uploader query
    function exc_mf_media_query( $atts = array() )
    {
        return exc_media_query( $atts );
    }

    add_shortcode('mf_media_query', 'exc_mf_media_query');
}

if ( ! function_exists('mf_media_post') )
{
    function mf_media_post( $posts )
    {
        remove_filter( 'posts_results', 'mf_media_post' );

        foreach( $posts as $id => $post )
        {
            if ( ! empty( $post->post_password ) )
            {
                $protected_title_format = apply_filters( 'protected_title_format', __( 'Protected: %s', 'exc-uploader-theme' ), $post );
                $posts[ $id ]->post_title = sprintf( $protected_title_format, $posts[ $id ]->post_title );

            } else if ( isset( $post->post_status ) && 'private' == $post->post_status )
            {
                $private_title_format = apply_filters( 'private_title_format', __( 'Private: %s', 'exc-uploader-theme' ), $post );
                $posts[ $id ]->post_title = sprintf( $private_title_format, $posts[ $id ]->post_title );
            }
        }

        return $posts;
    }
}

if ( ! function_exists( 'filter_media_query') )
{
    function filter_media_query( $where )
    {
        global $wpdb;

        $where = str_replace( "OR {$wpdb->prefix}posts.post_status = 'future' OR {$wpdb->prefix}posts.post_status = 'draft' OR {$wpdb->prefix}posts.post_status = 'pending'", '', $where );

        remove_filter( 'posts_where', 'filter_media_query', 10 );
        return $where;
    }
}

if ( ! function_exists('filter_media_likes_where') )
{
    function filter_media_likes_where( $where, $query )
    {
        global $wpdb;

        $custom_page = $query->query_vars['exc_custom_page'];

        if ( ! empty( $custom_page['author_id'] ) ) {
            $user_id = $custom_page['author_id'];

        } elseif ( ! is_user_logged_in() ) {
            wp_die( __("Invalid Request"), "exc-uploader-theme" );
        } else {
            $user_id = get_current_user_id();
        }

        $where .= ' AND (' . $wpdb->prefix . 'exc_votes.user_id = ' . $user_id . ' AND ' . $wpdb->prefix . 'exc_votes.status = 1 ) ';

        remove_filter( 'posts_where', 'filter_media_likes_where', 10 );

        return $where;
    }
}

if ( ! function_exists('filter_media_likes_join') )
{
    function filter_media_likes_join( $join, $query )
    {
        global $wpdb;

        $join .= "LEFT JOIN {$wpdb->prefix}exc_votes ON $wpdb->posts.ID = {$wpdb->prefix}exc_votes.post_id ";

        remove_filter('posts_join', 'filter_media_likes_join', 10);

        return $join;
    }
}

if ( ! function_exists( 'exc_mf_user_query' ) )
{
    //extracoding uploader query
    //$user_follow_filters = 0;
    function exc_mf_user_query( $atts = array() )
    {
        $user_query = new WP_User_Query( array() );

        //'blog_id' => $GLOBALS['blog_id'],
        $shortcode_vars = array(
                            'columns'       => 3,
                            'post_limit'    => 3,
                            'words_limit'   => 10,
                            'show_about'    => true,
                            'show_address'  => true,
                            'template'      => 'users-list-view', //masonary
                            'masonry'       => true,
                            'autoload'      => true,
                        );

        $active_view = ( isset( $atts['active_view'] ) ) ? $atts['active_view'] : 'grid';

        foreach( $shortcode_vars as $var => $value )
        {
            $list_var_name = 'list_' . $var;

            if ( $var == 'template' && ! isset( $atts[ $list_var_name ] ) )
            {
                $shortcode_vars[ $list_var_name ] = 'users-list-view';

            } elseif( $var == 'columns' && ! isset( $atts[ $list_var_name ] ) )
            {
                $shortcode_vars[ $list_var_name ] = 2;
            } elseif( $var == 'words_limit' && ! isset( $atts[ $list_var_name ] ) )
            {
                $shortcode_vars[ $list_var_name ] = 30;
            } elseif ( $var == 'masonry' && ! isset( $atts[ $list_var_name ] ) )
            {
                $shortcode_vars[ $list_var_name ] = false;
            } else
            {
                $shortcode_vars[ $list_var_name ] = ( isset( $shortcode_vars[ $list_var_name ] ) ) ?
                                                    $shortcode_vars[ $list_var_name ] :
                                                    $value;
            }
        }

        $shortcode_vars = array_merge(
                            array(
                                'active_view'   => 'grid', //grid or list
                                '_media_query'  => true,
                                'query_id'      => '',
                                'page'          => '', // Private perameter
                                'author'        => '',
                                'hide_empty'    => true,
                                'post_type'     => array( 'post', 'exc_image_post', 'exc_video_post', 'exc_audio_post' ),
                                'role'          => '',
                                'meta_key'      => '',
                                'meta_value'    => '',
                                //'meta_type'       => '',
                                'meta_compare'  => '',
                                //'meta_query'  => array(),
                                'include'       => array(),
                                'exclude'       => array(),
                                'search'        => '',
                                'search_columns'=> array(),
                                //'orderby'     => 'post_count',
                                //'order'           => 'DESC',
                                'offset'        => 0,
                                'number'        => 20,
                                //'count_total' => 1,
                                'fields'        => 'all',
                                'who'           => ''

                            ), $shortcode_vars );

        $atts = shortcode_atts( $shortcode_vars, $atts, 'mf_user_query');

        // Normalize array values
        $arrs = array( 'include', 'exclude', 'search_columns', 'post_type' );

        foreach( $arrs as $arr )
        {
            if ( ! empty( $atts[ $arr ] ) && ! is_array( $atts[ $arr ] ) )
            {
                $atts[ $arr ] = explode(',', str_replace(', ', ',', $atts[ $arr ] ) );
            }
        }

        // Custom Pages Query update
        $page = ( $atts['page'] ) ? $atts['page'] : get_query_var( 'exc_custom_page' );

        if ( $page )
        {
            $is_user_logged_in = is_user_logged_in();

            if ( $is_user_logged_in && ( $page == 'dashboard-following' ) || $page == 'users-following' )
            {
                add_action( 'pre_user_query', 'filter_user_following' );

            } elseif ( $is_user_logged_in && ( $page == 'dashboard-followers' )  || $page == 'users-followers' )
            {
                add_action( 'pre_user_query', 'filter_user_followers' );
            }

            // @TODO: add stats in version 1.2
            //elseif ( $page == 'dashboard-appreciations' )
            //{
            //  add_action( 'pre_user_query', 'filter_user_appreciations' );
            //}
        }

        // Show only users with posts
        if ( $atts['hide_empty'] )
        {
            add_action( 'pre_user_query', 'hide_empty_post_users' );
        }

        $_exc_uploader = eXc_theme_instance();

        // Write query data
        // Write query data
        if ( $atts['_media_query'] )
        {
            if ( empty( $atts['query_id'] ) )
            {
                $atts['query_id'] = md5( serialize( $atts ) ); //current_time( 'timestamp', 1 );
            }

            $_exc_uploader->session->set_data( "mf_user_query_{$atts['query_id']}", $atts );
            //$_exc_uploader->session->set_data( "mf_media_query_{$atts['query_id']}", $atts );
        }

        //$user_query = new WP_User_Query( $atts );

        //Load Filtration
        //wp_enqueue_script('exc-media-filter');
        //if( ! $path = locate_template($atts['template'].'.php')) return;

        //if( ! $path = locate_template( $atts['template'].'.php' ) ) return;

        if ( $atts['active_view'] == 'grid' )
        {
            $columns  = $atts['columns'];
            $masonry  = $atts['masonry'];
            $autoload = $atts['autoload'];
            $template = $atts['template'];
            $class    = 'col col-' . $columns;

        } else
        {
            $columns  = $atts['list_columns'];
            $masonry  = $atts['list_masonry'];
            $autoload = $atts['list_autoload'];
            $template = $atts['list_template'];
            $class    = 'col col-' . $columns . ' users users-listview';
        }

        //$atts['offset'] = $atts['offset'] + 1;

        $users = new WP_User_Query( $atts );

        ob_start();

        if ( ! empty( $users->results ) )
        {
            foreach ( $users->results as $user )
            {
                // Fetch user posts
                $atts['user'] = $user;
                exc_load_template( $template, $atts );
            }
        } else
        {
            $data = array( 'heading' => esc_html__( 'No', 'exc-uploader-theme' ), 'message' => esc_html__( 'User Found', 'exc-uploader-theme' ) );

            if ( $page == 'dashboard-following' )
            {
                $data[ 'message' ] = esc_html__( 'You are not following anyone', 'exc-uploader-theme' );

            } elseif ( $page == 'users-following' )
            {
                $data[ 'message' ] = esc_html__( 'This user is not following anyone', 'exc-uploader-theme' );

            } elseif ( $page == 'dashboard-followers' )
            {
                $data[ 'message' ] = esc_html__( 'You are not following anyone', 'exc-uploader-theme' );
            } elseif ( $page == 'users-followers' )
            {
                $data[ 'message' ] = esc_html__( 'This user is not following anyone', 'exc-uploader-theme' );
            }

            exc_load_template('content-expired', $data );
        }

        $content = ob_get_contents();
        ob_end_clean();

        if ( defined('DOING_AJAX') && DOING_AJAX )
        {
            if ( ! empty( $users->results ) )
            {
                wp_send_json_success(
                    array(
                        'html'      => $content,
                        'autoload'  => $autoload,
                        'masonry'   => $masonry,
                        'class'     => $class,
                        'counter'   => $users->get_total(),
                        )
                    );

            } else
            {
                if ( $atts['offset'] )
                {
                    exc_die( );
                } else
                {
                    exc_die( $content );
                }
            }

        } else
        {
            if ( $users )
            {
                // Load Filtration Script
                wp_enqueue_script( 'exc-user-filter' );

                $_exc_uploader->html->localize_script('exc-user-filter', 'exc_user_filter',
                                array(
                                    'action'    => 'exc_user_filter',
                                    'security'  => wp_create_nonce( 'exc-user-filter' ),
                                    'autoload'  => $autoload,
                                    'masonry'   => $masonry,
                                    'query_id'  => $atts['query_id'], //@TODO: if cookies are disabled then pass session id
                                    'counter'       =>  $users->get_total(),
                                    'countString'   =>  __('%d users', 'exc-uploader-theme'),
                                    'masonry'       => $masonry
                                )
                            );

                if ( $atts['offset'] == 0 )
                {
                    $id = ( $autoload ) ? 'id="exc-user-container"' : 'id="exc-user-masonry"';
                    echo '<ul class="' . esc_attr( $class ) . '" ' . $id . '>' . $content . '</ul>';
                } else {
                    echo $content;
                }

            } else {
                get_template_part( 'content', 'none' );
            }
        }
    }

    add_shortcode( 'mf_user_query', 'exc_mf_user_query' );
}

//Function to hide empty post users and support for multiple post types count
if ( ! function_exists('hide_empty_post_users') )
{
    function hide_empty_post_users( $user_query )
    {
        global $wpdb;

        remove_action( 'pre_user_query', 'hide_empty_post_users' );

        $post_types = ( ! empty( $user_query->query_vars['post_type'] ) ) ?
                            "'" . implode( "', '", $user_query->query_vars['post_type'] ) . "'" : 'post';

        if ( $user_query->query_vars['orderby'] != 'post_count' )
        {
            $user_query->query_from .= " LEFT OUTER JOIN (
                                                    SELECT post_author, COUNT(*) as post_count
                                                    FROM {$wpdb->prefix}posts
                                                    WHERE post_type IN( $post_types ) AND (post_status = 'publish' OR post_status = 'private')
                                                    GROUP BY post_author
                                                ) p ON ({$wpdb->prefix}users.ID = p.post_author)";
        }

        // We are using following for integer value sorting as wordpress meta_query type numeric has bug
        if ( $user_query->query_vars['orderby'] == 'meta_value'
                && $user_query->query_vars['meta_key'] == '_exc_media_views' )
        {
            $user_query->query_orderby = str_replace( "{$wpdb->usermeta}.meta_value", "CAST({$wpdb->usermeta}.meta_value AS SIGNED)", $user_query->query_orderby );

        } elseif( $user_query->query_vars['orderby'] == 'appreciations' )
        {
            $user_query->query_from .= " LEFT OUTER JOIN (
                                                    SELECT author_id, COUNT(*) as vote_count
                                                    FROM {$wpdb->prefix}exc_votes
                                                    GROUP BY author_id
                                                ) v ON ({$wpdb->prefix}users.ID = v.author_id)";

            $sort = ( preg_match('/\sASC$/i', $user_query->query_orderby ) ) ? 'ASC' : 'DESC';

            $user_query->query_orderby = "ORDER BY v.vote_count " . $sort;
        }

        $user_query->query_where = $user_query->query_where . ' AND post_count > 0';

    }
}

if ( ! function_exists( 'filter_user_followers' ) )
{
    function filter_user_followers( $user_query )
    {
        $user_id = ( ( int ) $user_query->query_vars['author'] ) ? $user_query->query_vars['author'] : get_current_user_id();

        $query = 'FROM ' . $GLOBALS['wpdb']->prefix . 'exc_followers ';
        $query .= 'INNER JOIN(select ' . str_ireplace( 'SQL_CALC_FOUND_ROWS ', '', $user_query->query_fields ) . ' '.$user_query->query_from.' '.$user_query->query_where.') t ON t.ID = follower_user_id';

        $user_query->query_fields = 'SQL_CALC_FOUND_ROWS t.*';
        $user_query->query_from = $query;
        $user_query->query_where = 'WHERE follower_author_id = ' . $user_id . ' AND follower_status = 1';

        remove_action( 'pre_user_query', 'filter_user_followers' );

        return $user_query;
    }
}

if ( ! function_exists('filter_user_following') )
{
    function filter_user_following( $user_query )
    {
        $user_id = ( ( int ) $user_query->query_vars['author'] ) ? $user_query->query_vars['author'] : get_current_user_id();

        $query = 'FROM ' . $GLOBALS['wpdb']->prefix . 'exc_followers ';
        $query .= 'INNER JOIN(select ' . str_ireplace( 'SQL_CALC_FOUND_ROWS ', '', $user_query->query_fields ) . ' '.$user_query->query_from.' '.$user_query->query_where.') t ON t.ID = follower_author_id';

        $user_query->query_fields = 'SQL_CALC_FOUND_ROWS t.*';
        $user_query->query_from = $query;
        $user_query->query_where = 'WHERE follower_user_id = ' . $user_id . ' AND follower_status = 1';

        remove_action( 'pre_user_query', 'filter_user_following' );

        return $user_query;
    }
}