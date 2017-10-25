<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists('eXc_Db_Options_Class') )
{
    $this->clear_query()->load_abstract('core/abstracts/options_abstract');
}

if ( ! class_exists( 'eXc_Menu_Class' ) )
{
    class eXc_Menu_Class extends eXc_DB_options_Class
    {
        /**
         * Custom menu data
         *
         * @since 1.0
         * @var object
         */
        private $_meta_query = array();

        /**
        * Menu Pages Slug
        *
        * @since 1.5
        * @var object
        */
        private $registered_pages = array();

        /**
         * Current page form fields data
         *
         * @since 1.5
         * @var object
         */
        protected $form_settings = array();

        function add_page( $data, $fields = '', $type = 'menu', $parent_slug = '' )
        {
            $current_filter = current_filter();

            if ( ! is_admin() ) {
                return FALSE;

            } elseif ( ! is_array( $data ) ) {
                $data = array( 'menu_title' => $data );
            }

            if ( empty( $this->_meta_query['add_menu'] ) && $current_filter != 'admin_menu' ) {
                add_action( 'admin_menu', array( &$this, '_register_menu_items' ) );
            }

            $data['type'] = $type;
            $data['parent_slug'] = $parent_slug;
            $data['fields'] = $fields;

            $page_data = $this->normalize_data( $data );

            $this->registered_pages[ $page_data['menu_slug'] ] = $fields;

            $this->_meta_query['add_menu'][] = $page_data;

            if ( $current_filter == 'admin_menu' ) {
                $this->_register_menu_items();
            }

            return $this;
        }

        function add_subpage( $data = array(), $parent = 'options-general.php', $fields = '' )
        {
            return $this->add_page( $data, $fields, 'submenu', $parent );
        }

        function add_dashboard_page( $data, $fields = '' )
        {
            return $this->add_page( $data, $fields, 'dashboard_page');
        }

        function add_posts_page( $data, $fields = '' )
        {
            return $this->add_page( $data, $fields, 'posts_page');
        }

        function add_media_page( $data, $fields = '' )
        {
            return $this->add_page( $data, $fields, 'media_page');
        }

        function add_links_page( $data, $fields = '' )
        {
            return $this->add_page( $data, $fields, 'links_page');
        }

        function add_pages_page( $data, $fields = '' )
        {
            return $this->add_page( $data, $fields, 'pages_page');
        }

        function add_comments_page( $data, $fields = '' )
        {
            return $this->add_page( $data, $fields, 'comments_page');
        }

        function add_theme_page( $data, $fields = '' )
        {
            return $this->add_page( $data, $fields, 'theme_page', 'themes.php' );
        }

        function add_plugins_page( $data, $fields = '' )
        {
            return $this->add_page( $data, $fields, 'plugins_page');
        }

        function add_users_page( $data, $fields = '' )
        {
            return $this->add_page( $data, $fields, 'users_page');
        }

        function add_management_page( $data, $fields = '' )
        {
            return $this->add_page( $data, $fields, 'management_page');
        }

        function add_options_page( $data, $fields = '' )
        {
            return $this->add_page( $data, $fields, 'options_page');
        }

        function _register_menu_items()
        {
            if ( empty( $this->_meta_query['add_menu'] ) )
            {
                return;
            }

            // Wordpress Do not support position for submenu so we have to manually re-arrage the array
            // We must register parent menu items first
            $menu_items = array();
            $parent_menu_positions = array();
            $child_menus = array();

            foreach ( $this->_meta_query['add_menu'] as $index => $query )
            {
                if ( $query['type'] == 'submenu' )
                {
                    $child_menus[] = $query;
                } else
                {
                    $parent_menu_positions[ $query['menu_slug'] ] = $query['position'];
                    $menu_items[] = $query;
                }
            }

            foreach ( $child_menus as $index => $query ) {

                if ( ! intval( $query['position'] ) && ! empty( $query['parent_slug'] )
                        && ! empty( $parent_menu_positions[ $query['parent_slug'] ] ) ) {
                    // Make sure the child position is greator than parent
                    $query['position'] = ( intval( $parent_menu_positions[ $query['parent_slug'] ] ) )
                                            ? $parent_menu_positions[ $query['parent_slug'] ] + 1 : 60;
                }

                $query['position'] = ( intval( $query['position'] ) ) ? $query['position'] : 60;

                $position = $query['position'] + substr( base_convert( md5( $query['menu_slug'] . $query['menu_title'] ), 16, 10 ) , -5 ) * 0.00001;

                $menu_items[ "$position" ] = $query;
            }

            // Reduce memory load
            unset( $child_menus );
            unset( $parent_menu_positions );
            unset( $this->_meta_query['add_menu'] );

            ksort( $menu_items );

            foreach ( $menu_items as $index => $query ) {
                extract( $query );

                switch( $type ) {

                    case "submenu" :
                            $slug = add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
                        break;

                    case "dashboard_page" :
                            $slug = add_dashboard_page( $page_title, $menu_title, $capability, $menu_slug, $function );
                        break;

                    case "posts_page" :
                            $slug = add_posts_page( $page_title, $menu_title, $capability, $menu_slug, $function );
                        break;

                    case "media_page" :
                            $slug = add_media_page( $page_title, $menu_title, $capability, $menu_slug, $function );
                        break;

                    case "links_page":
                            $slug = add_links_page( $page_title, $menu_title, $capability, $menu_slug, $function );
                        break;

                    case "pages_page":
                            $slug = add_pages_page( $page_title, $menu_title, $capability, $menu_slug, $function );
                        break;

                    case "comments_page" :
                            $slug = add_comments_page( $page_title, $menu_title, $capability, $menu_slug, $function );
                        break;

                    case "theme_page" :
                            $slug = add_theme_page( $page_title, $menu_title, $capability, $menu_slug, $function );
                        break;

                    case "plugins_page" :
                            $slug = add_plugins_page( $page_title, $menu_title, $capability, $menu_slug, $function );
                        break;

                    case "users_page" :
                            $slug = add_users_page( $page_title, $menu_title, $capability, $menu_slug, $function);
                        break;

                    case "management_page" :
                            $slug = add_management_page( $page_title, $menu_title, $capability, $menu_slug, $function);
                        break;

                    case "options_page" :
                            $slug = add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
                        break;

                    default :
                            $slug = add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
                        break;
                }
            }
        }

        function remove_menu( $menu_slug, $parent_slug = '' )
        {
            $current_filter = current_filter();

            if ( ! is_admin() ) {
                return;

            } elseif ( empty( $this->_meta_query['remove_menu'] )
                    && $current_filter != 'admin_menu' ) {
                add_action( 'admin_menu', array( &$this, '_unregister_menu_items' ) );
            }

            $this->_meta_query['remove_menu'][] = ( $parent_slug ) ? array( $parent_slug, $menu_slug ) : $menu_slug;

            if ( $current_filter == 'admin_menu' ) {
                $this->_unregister_menu_items();
            }

            return $this;
        }

        function _unregister_menu_items()
        {
            foreach ( $this->_meta_query['remove_menu'] as $menu_item ) {

                if ( is_array( $menu_item ) ) {
                    remove_submenu_page( $menu_item[0], $menu_item[1] );

                } else {
                    remove_menu_page( $menu_item );
                }
            }

            unset( $this->_meta_query['remove_menu'] );
        }

        function page_html()
        {
            $this->form_settings = apply_filters( 'exc-admin-menu-' . sanitize_title( $GLOBALS['plugin_page'] ), $this->form_settings );

            if ( empty( $this->form_settings ) ) {
                exc_die( __("The page configuration file or callback function is required.", "exc-framework") );
            }

            $this->exc()->load_view( exc_kv( $this->form_settings, '_layout' ), $this->form_settings );
        }

        function normalize_data( $menu_data )
        {
            $menu_data =
                wp_parse_args(
                    $menu_data,
                    array(
                        "page_title"    =>  esc_html__( "Custom Menu Page Title", "exc-framework" ),
                        "menu_title"    =>  esc_html__( "Custom Menu Item", "exc-framework" ),
                        "capability"    =>  "manage_options",
                        "menu_slug"     =>  "exc-" . sanitize_title( $menu_data["menu_title"] ),
                        "function"      =>  ( isset( $menu_data["function"] ) )
                                                ? $menu_data["function"] : array( &$this, "page_html" ),
                        "icon_url"      =>  "",
                        "position"      => "",
                        "parent_slug"   =>  "",
                        "type"          =>  "menu",
                    )
                );

            if ( empty( $menu_data['parent_slug'] ) && empty( $menu_data['position'] ) ) {
                $menu_data['position'] = 22;
            }

            return $menu_data;
        }

        public function enqueue_files()
        {
            // Load Bootstrap on plugin pages
            if ( ! empty( $GLOBALS['plugin_page'] )
                    && isset( $this->registered_pages[ $GLOBALS['plugin_page'] ] ) ) {

                parent::enqueue_files();
            }
        }

        public function &get_active_form_settings()
        {
            return $this->form_settings;
        }

        public function load_settings()
        {
            if ( defined( 'DOING_AJAX' ) && DOING_AJAX && ! empty( $_POST['plugin_page'] ) ) {
                $GLOBALS['plugin_page'] = $_POST['plugin_page'];
                // Make sure the request is not related to this page
            }

            $plugin_page = exc_kv( $GLOBALS, 'plugin_page' );

            // Nothing to do
            if ( ! $plugin_page || ! isset( $this->registered_pages[ $plugin_page ] ) ) {
                return;
            }

            // Filter to load default settings of the form
            add_filter( 'exc-prepare-form', array( &$this, 'prepare_form' ) );

            $current_page_settings = $this->registered_pages[ $plugin_page ];

            // Load Page Style
            $is_ajax_request = exc_is_ajax_request();

            if ( ! $is_ajax_request ) {
                $this->enqueue_files();
            }

            if ( is_callable( $current_page_settings ) ) {

                $data = array( 'page' => &$this );
                call_user_func_array( $current_page_settings, $data );

            } elseif ( $current_page_settings ) {

                // Load the configuration file based on the page
                $this->config_file = $current_page_settings;

                // Make sure the HTML class is loaded
                $this->exc()->load('core/html_class');

                // Load the configuration file
                $this->form_settings = $this->exc()->load_config_file( $this->config_file );

                // We have multiple pages, so treat them accordingly
                if ( isset( $this->form_settings['pages'] ) ) {

                    $this->section = exc_kv( $_REQUEST, 'section' );
                    $this->subsection = '';

                    if ( $this->section ) {

                        $this->active_form = $this->section;
                        $this->subsection = exc_kv( $_REQUEST, 'subsection' );

                        if ( $this->subsection ) {
                            $this->active_form .= '/menu_child/' . $this->subsection;
                        }

                    } elseif ( empty( $this->active_form ) ) {
                        // Consider the very first as active
                        $this->active_form = exc_kv( $this->form_settings, '_active_form', key( $this->form_settings['pages'] ) );
                    }

                    // If active form has child then make sure the first child is active
                    $active_form = exc_kv( $this->form_settings['pages'], $this->active_form );

                    if ( ! is_array( $active_form ) ) {
                        $active_form = array( 'config_file' => $active_form );
                    } elseif ( empty( $active_form['config_file'] ) ) {
                        exc_die(
                            sprintf(
                                esc_html_x( 'The config_file information is required for %s in %s', 'Extracoding Framework Options', 'exc-framework' ),
                                $this->active_form,
                                $this->config_file . '.php'
                            )
                        );
                    }

                    // If child is available then use the first child
                    if ( ! empty( $active_form['menu_child'] ) && empty( $this->subsection ) ) {
                        // Change the active child
                        $this->active_form = $this->active_form . '/menu_child/' . key( $active_form['menu_child'] );

                        $active_form = exc_kv( $this->form_settings['pages'], $this->active_form );

                        if ( ! is_array( $active_form ) ) {
                            $active_form = array( 'config_file' => $active_form );
                        } elseif ( empty( $active_form['config_file'] ) ) {
                            exc_die(
                                sprintf(
                                    esc_html_x( 'The config_file information is required for %s in %s', 'Extracoding Framework Theme Options', 'exc-framework' ),
                                    $this->active_form,
                                    $this->config_file . '.php'
                                )
                            );
                        }
                    }

                    // Load Configuration file
                    $pages = $this->form_settings['pages'];
                    $default_layout = exc_kv( $this->form_settings, '_layout', 'theme_options/index' );

                    $this->config_file = $active_form['config_file'];

                    $this->form_settings = $this->exc()->load_config_file( $active_form['config_file'] );

                    if ( empty( $this->form_settings['_layout'] ) ) {
                        $this->form_settings['_layout'] = $default_layout;
                    }

                    if ( empty( $this->form_settings['action'] ) ) {
                        $this->form_settings['action'] = $this->action;
                    }

                    $this->form_settings['_menu_settings'] = apply_filters(

                            $GLOBALS['plugin_page'] . '_menu_items',
                            $this->prepare_menus( $pages )
                        );

                    $this->form_settings['_active_menu'] = $this->active_form;

                    add_filter( 'exc_form_settings', array( &$this, 'extend_form_settings' ), 10, 2 );
                }

                // Active form hack
                if ( isset( $_POST['_active_form'] ) && empty( $this->form_settings['_active_form'] )
                     && isset( $this->form_settings['_config'][ $_POST['_active_form'] ] ) ) {
                    $this->form_settings['_active_form'] = $_POST['_active_form'];
                }

                $this->exc()->wp_admin->prepare_form( $this->form_settings );

                if ( ! $is_ajax_request ) {
                    $this->form_settings['form_fields'] = 'plugin_page=' . $GLOBALS['plugin_page'];
                }

                parent::load_settings();
            }
        }

        public function extend_form_settings( $fields = array(), $form_name )
        {
            $fields['section'] = $this->section;
            $fields['subsection'] = $this->subsection;

            return $fields;
        }

        private function prepare_menus( $items )
        {
            $menu_items = array();

            foreach ( $items as $menu_key => $menu_settings ) {

                $menu_key = exc_to_slug( $menu_key );

                if ( ! is_array( $menu_settings ) ) {
                    $menu_settings = array( 'menu_name' => exc_to_text( $menu_key ) );
                }

                $pagenow = exc_kv( $GLOBALS, 'pagenow', 'admin.php' );

                // Parent Menu Item
                $menu_items[ $menu_key ] =
                    wp_parse_args(
                        $menu_settings,
                        array(
                            'menu_name' => exc_kv( $menu_settings, 'menu_name', esc_html__('Undefined', 'exc-framework') ),
                            'menu_slug' => sanitize_key( 'menu-' . $menu_key ),
                            'menu_icon' => 'fa fa-angle-double-right',
                            'menu_link' => admin_url( $pagenow . '?page=' . $GLOBALS['plugin_page'] . '&section=' . $menu_key )
                        )
                    );

                // Child Items
                if ( ! empty( $menu_settings['menu_child'] ) ) {
                    $menu_items[ $menu_key ]['menu_child'] = $this->prepare_menus( $menu_settings['menu_child'] );
                }
            }

            return $menu_items;
        }
    }
}