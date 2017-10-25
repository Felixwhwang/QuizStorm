<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Media_Query_Extension' ) )
{
    class eXc_Media_Query_Extension extends eXc_Extension_Abstract
    {
        /**
         * Extension Version ID
         *
         * @since 1.0
         * @var string
         */
        protected $version = '1.0.1';

        /**
         * Enable or Disable Mansonry Support
         *
         * @since 1.0
         * @var boolean
         */
        protected $masonry_support = true;

        protected function initialize_extension()
        {
            $this->local('functions')->load_file( 'exc.media.query' );

            add_action( 'wp_ajax_exc_media_filter', array( &$this, 'apply_filters' ) );
            add_action( 'wp_ajax_nopriv_exc_media_filter', array( &$this, 'apply_filters' ) );

            // Register Scripts
            add_action( 'wp_enqueue_scripts', array( &$this, 'register_scripts' ) );
        }

        public function apply_filters()
        {
            // @TODO: add condition in shortcode settings for extra security verification
            // if ( ! wp_verify_nonce( $_POST['security'], "exc-media-filter" ) )
            // {
            //  exc_page_error(
            //      __( 'Page Expired', 'exc-framework' ),
            //      __( 'Sorry, it seems that you may recently logged in or logged out and that\'s why the page was expired, please refresh it and try again.', 'exc-framework' )
            //  );
            // }

            $query_id = ( isset( $_POST['pk'] ) /*&& intval( $_POST['query_id'] )*/ ) ? $_POST['pk'] : '';

            // get user saved data
            $user_data = $this->exc()->load('core/session_class')
                                ->get_data( "exc_media_query_{$query_id}" );

            if ( empty( $user_data ) )
            {
                exc_page_error(
                    'Page Expired',
                    __( 'Sorry, it seems that you may recently logged in or
                        logged out and that\'s why the page was expired, please
                        refresh it and try again.', 'exc-framework' )
                );

            } elseif ( ! isset( $_POST['offset'] ) || ! is_numeric( $_POST['offset'] ) ) //offset
            {
                $_POST['offset'] = $user_data['offset'];
            }

            $user_data['offset'] = $_POST['offset'];

            do_action( 'exc_media_query_pre_filtration' );

            // Post type
            if ( isset( $_POST['post_type'] ) )
            {
                $post_type = preg_replace( '@exc_(\w+)_post@', '$1', $_POST['post_type'] );

                //if ( array_search( $post_type, array_merge( $this->types, array('any') ) ) !== false) {
                if ( TRUE === in_array( $post_type, array_merge( $this->types, array('any') ) ) )
                {
                    //reset offset
                    //if ( $user_data['post_type'] != $post_type )
                    //{
                        //$user_data['offset'] = 0;
                    //}

                    if ( $post_type == 'any' )
                    {
                        unset( $user_data['post_type'] );
                    } else
                    {
                        $user_data['post_type'] = 'exc_' . $post_type . '_post';
                    }

                } else {

                    exc_die( __("Invalid request, Possible hacking attempt!", 'exc-framework') );
                }
            }

            // Paged
            if ( isset( $_POST['paged'] ) )
            {
                $user_data['paged'] = ( is_numeric( $_POST['paged'] ) ) ? $_POST['paged'] : 0;
            }

            //category
            if ( isset( $_POST['cat'] ) ) {
                $user_data['cat'] = $_POST['cat'];
            }

            //template
            if ( isset( $_POST['active_view'] ) ) {
                $user_data['active_view'] = ( $_POST['active_view'] == 'list' ) ? 'list' : 'grid';
            }

            //search query
            if ( isset( $_POST['s'] ) ) {
                $user_data['s'] = esc_attr( $_POST['s'] );
            }

            $user_data = apply_filters( 'exc_media_query_filtration_vars', $user_data, $_POST );

            exc_media_query( $user_data );
        }

        public function register_scripts()
        {
            $deps = array();

            if ( $this->masonry_support &&
                    ( $imagesloaded_library = $this->exc()->get_file_url('views/js/imagesloaded.pkgd.min.js') ) )
            {
                wp_register_script( 'imagesloaded', $imagesloaded_library, array('jquery'), '3.1.6', true );

                $deps = array( 'masonry', 'imagesloaded' );
            }

            if ( $media_filter_js = apply_filters( 'exc_extension_media_query_js', $this->exc()->get_file_url( 'views/js/media_filter.js' ) ) )
            {
                wp_register_script( 'exc-media-query', $media_filter_js, $deps, '1.2', true );
            }
        }
    }
}