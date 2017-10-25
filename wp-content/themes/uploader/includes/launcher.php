<?php defined('ABSPATH') OR die('restricted access');

define( 'THEME_PATH', get_template_directory() );

if ( ! class_exists( 'eXc_Base_Class' ) )
{
    require get_template_directory() . '/includes/thirdparty/exc-framework/exc-framework.php';
}

eXc_Base_Class::load_theme_abstract_class();

if ( class_exists( 'eXc_Base_Class' ) )
{
    class exc_Uploader_Theme extends eXc_Theme_Abstract
    {
        /**
         * Product Name
         *
         * @since 1.0
         * @var string
         */
        protected $product_name = 'exc-uploader-theme'; // Note: Do not change, otherwise this product will stop working with additional addons/plugins

        /**
         * Extracoding Framework minimum version compatibility
         *
         * @since 1.1
         * @var float
         */
        protected $exc_supported_version = '2.3.1';

        public function initialize_theme()
        {
            // Register Widgets and sidebars
            add_action( 'widgets_init', array( &$this, 'register_widgets' ) );

            // Page Builder for pages
            add_action( 'load-post.php', array( &$this, 'register_metabox' ) );
            add_action( 'load-post-new.php', array( &$this, 'register_metabox' ) );

            // Add Actions
            add_action( 'current_screen', array( &$this, 'extra_taxonomy_fields' ) );

            $this->load_abstract( 'core/abstracts/controller_abstract' );

            $this->load( 'core/html_class' );

            // $this->load_abstract( 'core/abstracts/options_abstract' );

            // $this->wp_admin->edit( "user_profile_fields" )->add_fields( array(
            //             "user_fields"
            //     ));

            // Register Custom Post Types
            $this->load('core/video_class');
            $this->load('core/audio_class');
            $this->load('core/image_class');
            $this->load('core/radio_class');

            // Members Class
            $this->load( 'core/author_class' );

            // Load media uploader class
            $this->load( 'core/uploader_class' );

            // Add custom pages
            $this->add_custom_pages();

            // Load post class for rating and other post related matters
            if ( exc_is_client_side() )
            {
                if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )
                {
                    $this->front_end();

                } else
                {
                    $this->display_session_message();

                    // Load BreadCrumbs Class
                    $this->load('core/breadcrumbs_class');

                    add_action( 'wp', array( &$this, 'front_end' ) );

                    // Overwrite css properties through theme options
                    add_action( 'wp_head', array( &$this, 'load_css' ), 20 );
                }
            }
        }

        protected function list_of_required_extensions()
        {
            $this->load_file( 'functions/wp_helper' );

            return array(
                'theme_options/theme_options',
                'media_query/media_query',
                'members_lite/members_lite'
            );
        }

        public function extra_taxonomy_fields()
        {
            $pagenow = exc_kv( $GLOBALS, 'pagenow' );

            if ( 'edit-tags.php' != $pagenow && 'term.php' != $pagenow )
            {
                return;
            }

            // Extend Layout for Taxonomy
            $this->html->load_bootstrap()
                    ->load_css( 'theme-options-style', $this->system_url('views/css/style.css') )
                    ->load_css( 'font-awesome', $this->system_url('views/css/font-awesome.min.css') );

            if ( get_current_screen()->id == 'edit-genre' )
            {
                $config = $this->load_config_file( 'taxonomy/genre', array() );
                $this->wp_admin->edit('taxonomy')->on('genre')->add_fields( $config );

            } else
            {
                $config = $this->load_config_file( 'taxonomy/category', array() );
                $this->wp_admin->edit('taxonomy')->on('category', 'post_tag')->add_fields( $config );
            }
        }

        public function front_end()
        {
            // Load post class for rating
            $this->load( 'core/post_class' );
        }

        public function register_widgets()
        {
            //register sidebars
            $this->register_sidebars();

            if ( exc_kv( $GLOBALS, 'pagenow' ) == 'widgets.php' )
            {
                $this->html->load_bootstrap()
                    ->load_css( 'theme-options-style', $this->system_url('views/css/style.css') )
                    ->load_css( 'font-awesome', $this->system_url('views/css/font-awesome.min.css') );
            }

            //@TODO: cache widgets output
            $this->load_widget( 'core/widgets/users_widget' )
                    ->load_widget( 'core/widgets/staff_picked_widget' )
                    ->load_widget( 'core/widgets/advanced_posts_widget' )
                    ->load_widget( 'core/widgets/radio_widget' )
                    ->load_widget( 'core/widgets/post_author_widget')
                    ->load_widget( 'core/widgets/post_author_about_widget')
                    ->load_widget( 'core/widgets/post_meta_data_widget')
                    ->load_widget( 'core/widgets/sharethis_widget');
        }

        public function load_css()
        {
            $opt_name = $this->product_name . '_style';

            if ( $css = apply_filters( 'exc-onpage-style', get_option( $opt_name ), $opt_name ) )
            {
                echo '<style type="text/css">' . $css . '</style>';
            }
        }

        private function add_custom_pages()
        {
            //Register custom pages
            $this->wp_admin->edit('custom_page')
                    ->login_url( site_url( '#login' ) )
                    ->add_rule(
                        array(
                            'title'     => esc_html_x( 'Dashboard', 'Uploader Dashboard Page Title', 'exc-uploader-theme' ),
                            'regex'     => '^dashboard/?$',
                            'template'  => 'users-media-files',
                            'protected' => true
                        )
                    )

                    ->add_rule(
                        array(
                            'title'     => esc_html_x( 'Media Files', 'Uploader Dashboard Page Title', 'exc-uploader-theme' ),
                            'regex'     => '^dashboard/media-files/?$',
                            'template'  => 'users-media-files',
                            'protected' => true
                        )
                    )

                    ->add_rule(
                        array(
                            'title'     => esc_html_x( 'My Profile', 'Uploader Dashboard Page Title', 'exc-uploader-theme' ),
                            'regex'     => '^dashboard/profile/?$',
                            'template'  => 'users-profile',
                            'protected' => true
                        )
                    )

                    ->add_rule(
                        array(
                            'title'     => esc_html_x( 'My Following', 'Uploader Dashboard Page Title', 'exc-uploader-theme' ),
                            'regex'     => '^dashboard/following/?$',
                            'template'  => 'users-following',
                            'protected' => true
                        )
                    )

                    ->add_rule(
                        array(
                            'title'     => esc_html_x( 'My Followers', 'Uploader Dashboard Page Title', 'exc-uploader-theme' ),
                            'regex'     => '^dashboard/followers/?$',
                            'template'  => 'users-followers',
                            'protected' => true
                        )
                    )

                    ->add_rule(
                        array(
                            'title'     => esc_html_x( 'Media Likes', 'Uploader Dashboard Page Title', 'exc-uploader-theme' ),
                            'regex'     => '^dashboard/likes/?$',
                            'template'  => 'users-media-likes',
                            'protected' => true
                        )
                    )

                    ->add_rule(
                        array(
                            'title'     => esc_html_x('Browse Users', 'extracoding browse users custom page title', 'exc-uploader-theme' ),
                            'regex'     => '^users/?$',
                            'template'  => 'users',
                        )
                    )

                    ->add_rule(
                        array(
                            'title'     => esc_html_x( 'Media Files', 'extracoding media files custom page title', 'exc-uploader-theme' ),
                            'regex'     => '^users/([a-z0-9_-]+)/media-files/?$',
                            'redirect'  => 'index.php?exc_custom_page=users-media-files&author_name=$matches[1]',
                            'template'  => 'users-media-files',
                        )
                    )

                    ->add_rule(
                        array(
                            'title'     => esc_html_x( 'User Following', 'extracoding media files custom page title', 'exc-uploader-theme' ),
                            'regex'     => '^users/([a-z0-9_-]+)/following/?$',
                            'redirect'  => 'index.php?exc_custom_page=users-following&author_name=$matches[1]',
                            'template'  => 'users-following'
                        )
                    )

                    ->add_rule(
                        array(
                            'title'     => esc_html_x( 'User Followers', 'extracoding media files custom page title', 'exc-uploader-theme' ),
                            'regex'     => '^users/([a-z0-9_-]+)/followers/?$',
                            'redirect'  => 'index.php?exc_custom_page=users-followers&author_name=$matches[1]',
                            'template'  => 'users-followers'
                        )
                    )

                    ->add_rule(
                        array(
                            'title'     => esc_html_x( 'Media Likes', 'extracoding media files custom page title', 'exc-uploader-theme' ),
                            'regex'     => '^users/([a-z0-9_-]+)/likes/?$',
                            'redirect'  => 'index.php?exc_custom_page=users-likes&author_name=$matches[1]',
                            'template'  => 'users-media-likes'
                        )
                    )

                    ->add_rule(
                        array(
                            'title'     => esc_html_x( 'Contact Us', 'extracoding custom contact page title', 'exc-uploader-theme' ),
                            'regex'     => '^contact/?$',
                            'template'  => 'contact',
                        )
                    )

                    ->add_rule(
                        array(
                            'regex'     => '^login/?$',
                            'template'  => 'login'
                        )
                    )

                    ->add_rule(
                        array(
                            'title'     => esc_html_x('Browse Categories', 'extracoding custom browser categories page title', 'exc-uploader-theme' ),
                            'regex'     => '^categories/?$',
                            'template'  => 'category-list',
                        )
                    )

                    ->rewrite_base('author_base', 'users');
        }

        private function register_sidebars()
        {
            //Load wp helper required for views count and rating
            $this->load_file('functions/wp_helper');

            $sidebars = exc_kv( exc_get_option('mf_sidebars'), 'sidebars' ) ;

            $default_sidebars = array(
                                    array(
                                        'name'          => __( 'Home Page Right Sidebar', 'exc-uploader-theme' ),
                                        'id'            => __( 'home-page-right-sidebar', 'exc-uploader-theme' ),
                                        'description'   => __( 'A built-in extracoding uploader sidebar for home page.', 'exc-uploader-theme' ),
                                        'before_title'  => '<div class="header"><h3>',
                                        'after_title'   => '</h3></div>',
                                        'before_widget' => '<div id="%1$s" class="widget %2$s">',
                                        'after_widget'  => '</div>'
                                    ),

                                    array(
                                        'name'          => __( 'Blog Page Sidebar', 'exc-uploader-theme' ),
                                        'id'            => __( 'blog-page-sidebar', 'exc-uploader-theme' ),
                                        'description'   => __( 'A built-in extracoding uploader sidebar for blog page.', 'exc-uploader-theme' ),
                                        'before_title'  => '<div class="header"><h3>',
                                        'after_title'   => '</h3></div>',
                                        'before_widget' => '<div id="%1$s" class="widget %2$s">',
                                        'after_widget'  => '</div>'
                                    ),

                                    array(
                                        'name'          => __( 'Page Header Advertisment', 'exc-uploader-theme' ),
                                        'id'            => __( 'exc-uploader-header-sidebar', 'exc-uploader-theme' ),
                                        'description'   => __( 'A built-in sidebar to display ads on detail page header section.', 'exc-uploader-theme' ),
                                        'before_title'  => '<div class="header"><h3>',
                                        'after_title'   => '</h3></div>',
                                        'before_widget' => '<div id="%1$s" class="widget %2$s">',
                                        'after_widget'  => '</div>'
                                    ),

                                    array(
                                        'name'          => __( 'Before Content Sidebar', 'exc-uploader-theme' ),
                                        'id'            => __( 'exc-uploader-before-content-sidebar', 'exc-uploader-theme' ),
                                        'description'   => __( 'A built-in sidebar to display ads before content on detail page.', 'exc-uploader-theme' ),
                                        'before_title'  => '<div class="header"><h3>',
                                        'after_title'   => '</h3></div>',
                                        'before_widget' => '<div id="%1$s" class="widget %2$s">',
                                        'after_widget'  => '</div>'
                                    ),

                                    array(
                                        'name'          => __( 'After Content Sidebar', 'exc-uploader-theme' ),
                                        'id'            => __( 'exc-uploader-after-content-sidebar', 'exc-uploader-theme' ),
                                        'description'   => __( 'A built-in sidebar to display ads after content on detail page.', 'exc-uploader-theme' ),
                                        'before_title'  => '<div class="header"><h3>',
                                        'after_title'   => '</h3></div>',
                                        'before_widget' => '<div id="%1$s" class="widget %2$s">',
                                        'after_widget'  => '</div>'
                                    )
                                );

            $sidebars = ( is_array( $sidebars ) ) ? array_merge( $sidebars, $default_sidebars ) : $default_sidebars;

            // @TODO: add ability to pass sidebars complete array at once
            foreach ( $sidebars as $sidebar )
            {
                $this->wp_admin->edit('sidebars')->register( $sidebar );
            }
        }

        public function register_metabox()
        {
            if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )
            {
                $this->html->load_bootstrap()
                    ->load_css( 'theme-options-style', $this->system_url('views/css/style.css') )
                    ->load_css( 'font-awesome', $this->system_url('views/css/font-awesome.min.css') );
            }

            $typenow = exc_kv( $GLOBALS, 'typenow' );

            if ( in_array( $typenow, array( 'post', 'page', 'exc_video_post', 'exc_audio_post', 'exc_image_post' ) ) )
            {
                $config = $this->load_config_file( 'metaboxes/layout_settings' );

                $this->wp_admin->edit( 'metabox' )->on( 'post', 'page', 'exc_video_post', 'exc_audio_post', 'exc_image_post' )->add_meta_box( __( 'Page Settings', 'exc-uploader-theme' ), $config );
            }
        }

        private function display_session_message()
        {
            // Display Messages
            //$session_message = $this->session->get_data( 'session_message' );
            $session_message = $this->session->flashdata( 'session_message' );

            if ( ! empty( $session_message ) )
            {
                $session_message = wp_parse_args(
                                        $session_message,
                                        array(
                                            'id'        => 'session-message',
                                            'type'      => 'error',
                                            'message'   => __('Unknown Message!!', 'exc-uploader-theme'),
                                            'tls'       => 10000,
                                            'layout'    => 'bar',
                                            'effect'    => 'slidetop',
                                            'icon'      => 'fa fa-times'
                                        )
                                    );

                $this->html->inline_js( $session_message['id'], 'eXc.notification( ' . json_encode( $session_message ) . ' );', 'notificationFx', true );

                // @TODO: add support to keep this message until the next refresh
                // Clear message
                //$this->session->unset_data('session_message');
            }
        }

        protected function upgrade_2_2()
        {
            return $this->set_product_option( 'version', $this->product_version );
        }

        protected function upgrade_2_2_1()
        {
            return $this->upgrade_2_2();
        }

        protected function upgrade_2_2_2()
        {
            return $this->upgrade_2_2();
        }

        protected function upgrade_2_2_3()
        {
            return $this->upgrade_2_2();
        }

        // Environment Setup
        protected function env_setup_backup()
        {
            // Define Unique Theme Prefix to avoide conflict between multiple themes settings
            define( 'THEME_PREFIX', $this->product_name . '_' );

            /** Theme directory URL */
            define( 'THEME_URL', get_template_directory_uri() );

            /** Child Theme directory Path */
            define( 'CHILD_THEME_PATH', get_stylesheet_directory() );

            /** Child Theme directory URL */
            define( 'CHILD_THEME_URL', get_stylesheet_directory_uri() );

            /** is Child theme active */
            define( 'IS_CHILD_THEME', (THEME_PATH != CHILD_THEME_PATH) ? true : false );

            /** Home URL */
            define( 'HOME_URL', home_url() );

            /** Define Base directory path */
            //define( 'BASEPATH', dirname( __FILE__ ) );

            // Set local directory path
            // Local system directory path
            $this->local_system_dir = THEME_PATH . '/includes/';

            // Local system directory URL
            $this->local_system_url = THEME_URL . '/includes/';

            $this->error_template = locate_template( 'modules/error_template.php' );
        }
    }

    new exc_Uploader_Theme( __FILE__ );
}