<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Page_Abstract' ) )
{
    abstract class eXc_Page_Abstract
    {
        /**
         * Extracoding Framework Instance
         *
         * @since 1.0
         * @var object
         */
        protected $eXc;

        /**
         * Current Section Slug
         *
         * @since 1.0
         * @var string
         */
        protected $section;

        /**
         * Current Sub-section Slug
         *
         * @since 1.0
         * @var string
         */
        protected $subsection;

        /**
         * Form Configuration file path
         *
         * @since 1.0
         * @var string
         */
        protected $config_file = 'theme_options/settings';

        /**
        * Form ajax action
        *
        * @since 1.0
        * @var string
        */
        protected $default_form_action = 'exc-theme_options';

        /**
         * Contain the form configuration file data
         *
         * @since 1.0
         * @var object
         */
        protected $form_settings = array();

        /**
         * Active Form Information
         *
         * @since 1.5
         * @var string
         */
        protected $active_form;

        /**
         * Layout file
         *
         * @since 2.3
         * @var string
         */
        protected $layout_file = 'theme_options/index';

        abstract protected function is_current_page();

        abstract protected function register_admin_page( $config_file, $active_form );

        function __construct( &$eXc )
        {
            $this->eXc = $eXc;

            if ( is_admin() )
            {
                // Load Dialog Template
                // exc_load_template( 'js/templates/dialog' );

                $active_form = array( 'menu_config' => '' );

                // if ( ( $is_ajax_request = exc_is_ajax_request( 'exc-theme-options' ) ) ||
                //     ( $GLOBALS['pagenow'] == 'themes.php' && exc_kv( $_REQUEST, 'page' ) == 'exc-theme-options' ) )
                if ( $this->is_current_page() )
                {
                    //add_filter( 'exc_config_array', array( &$this, 'append_menu' ), 10, 2 );
                    // Load the settings file

                    $this->form_settings = $this->eXc->load_config_file( $this->config_file, array(), true );

                    if ( $this->section = exc_kv( $_REQUEST, 'section' ) )
                    {
                        $this->active_form = $this->section;

                        if ( $this->subsection = exc_kv( $_REQUEST, 'subsection' ) )
                        {
                            $this->active_form .= '/menu_child/' . $this->subsection;
                        }

                    } /*elseif ( ! isset( $this->active_form ) //@TODO: REVIEW THIS CODE
                        && ( ! $this->active_form = $this->find_active_form( $this->form_settings['_config'] ) ) )
                    {
                        exc_die( _x('Unable to load active from settings for theme options', 'extracoding theme options', 'exc-framework' ) );
                    }*/
                    elseif ( empty( $this->active_form ) ) // Consider the very first as active
                    {
                        $this->active_form = exc_kv( $this->form_settings, '_active_form', key( $this->form_settings['_config'] ) );
                    }

                    // If active form has child then make sure the first child is active
                    $active_form = exc_kv( $this->form_settings['_config'], $this->active_form );

                    //@TODO: repeat code more to function
                    if ( ! is_array( $active_form ) )
                    {
                        $active_form = array( 'menu_config' => $active_form );
                    } elseif ( empty( $active_form['menu_config'] ) )
                    {
                        exc_die(
                            sprintf(
                                esc_html_x( 'The config_file information is required for %s in %s', 'Extracoding Framework Options', 'exc-framework' ),
                                $this->active_form,
                                $this->config_file . '.php'
                            )
                        );
                    }

                    // If child available then option the first child
                    if ( ! empty( $active_form['menu_child'] ) && empty( $this->subsection ) )
                    {
                        // Change the active child
                        $this->active_form = $this->active_form . '/menu_child/' . key( $active_form['menu_child'] );

                        $active_form = exc_kv( $this->form_settings['_config'], $this->active_form );

                        if ( ! is_array( $active_form ) )
                        {
                            $active_form = array( 'menu_config' => $active_form );
                        } elseif ( empty( $active_form['menu_config'] ) )
                        {
                            exc_die(
                                sprintf(
                                    esc_html_x( 'The config_file information is required for %s in %s', 'Extracoding Framework Theme Options', 'exc-framework' ),
                                    $this->active_form,
                                    $this->config_file . '.php'
                                )
                            );
                        }
                    }

                    add_filter( 'exc_config_array_' . exc_to_slug( $active_form['menu_config'] ), array( &$this, 'append_menu' ), 10, 2 );
                }

                // Register admin page
                $this->register_admin_page( $active_form['menu_config'], $active_form );

            } else
            {
                // Automatically destruct class
                $this->eXc->_load_status = false;
            }
        }

        public function append_menu( $options, $file )
        {
            if ( empty( $options['_layout'] ) )
            {
                $options['_layout'] = $this->layout_file;
            }

            if ( empty( $options['_capabilities'] ) )
            {
                $options['_capabilities'] = '';
            }

            if ( empty( $options['action'] ) )
            {
                $options['action'] = $this->default_form_action;
            }

            //$fields['action'] = 'exc-theme_options';
            //$fields['page'] = 'appearance_page_theme-options';

            if ( empty( $options['_active_form'] ) )
            {
                //$options['_active_form'] = $this->active_form;
            }

            $options['_menu_settings'] = apply_filters(
                        $this->default_form_action . '_menu_items',
                        $this->prepare_menus( $this->form_settings['_config'] )
                    );

            unset( $this->form_settings );

            add_filter( 'exc_form_settings', array( &$this, 'form_settings' ) );

            return $options;
        }

        public function form_settings( $fields = array() )
        {
            $fields['section'] = $this->section;
            $fields['subsection'] = $this->subsection;

            return $fields;
        }

        private function prepare_menus( $items )
        {
            $menu_items = array();

            foreach ( $items as $menu_key => $menu_settings )
            {
                $menu_key = exc_to_slug( $menu_key );

                if ( ! is_array( $menu_settings ) )
                {
                    $menu_settings = array( 'menu_name' => exc_to_text( $menu_key ) );
                }

                // Parent Menu Item
                $menu_items[ $menu_key ] =
                    wp_parse_args(
                        $menu_settings,
                        array(
                            'menu_name' => exc_kv( $menu_settings, 'menu_name', esc_html__('Undefined', 'exc-framework') ),
                            'menu_slug' => sanitize_key( 'menu-' . $menu_key ),
                            'menu_icon' => 'fa fa-angle-double-right',
                            'menu_link' => admin_url( 'themes.php?page=exc-theme-options&section=' . $menu_key )
                        )
                    );

                // Child Items
                if ( ! empty( $menu_settings['menu_child'] ) )
                {
                    $menu_items[ $menu_key ]['menu_child'] = $this->prepare_menus( $menu_settings['menu_child'] );
                }
            }

            return $menu_items;
        }
    }
}