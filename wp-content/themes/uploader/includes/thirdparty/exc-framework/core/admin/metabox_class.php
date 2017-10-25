<?php defined('ABSPATH') OR die('restricted access');

// if ( ! class_exists('eXc_Db_Options_Class') )
// {
//  $this->clear_query()->load_abstract('core/abstracts/options_abstract');
// }

// @TODO: extend this class with options_abstract
if ( ! class_exists( 'eXc_Metabox_Class' ) )
{
    class eXc_Metabox_Class extends eXc_Controller_Abstract
    {
        // /**
        //  * Extracoding Framework Instance
        //  *
        //  * @since 1.0
        //  * @var object
        //  */
        // protected $eXc;

        /**
         * Custom menu data
         *
         * @since 1.0
         * @var object
         */
        private $_meta_query = array();

        /**
         * MetaBoxes Configurations
         *
         * @since 1.0
         * @var array
         */
        private $boxes = array();

        /**
         * List of pages to display metabox
         *
         * @since 1.0
         * @var array
         */
        public $pages = array('post');

        function initialize_class()
        {
            //$this->exc() = $_eXc;

            //delete term meta on term deletion
            add_action( 'delete_term', array( &$this, 'delete_taxonomy_metadata' ), 10, 2 );
        }

        function on()
        {
            $this->pages = func_get_args();

            if ( empty( $this->pages ) )
            {
                exc_die( __( 'Please enter post type names to edit.', 'exc-framework' ) );
            }

            return $this;
        }

        function add_meta_box( $data, &$fields, $callback = '' )
        {
            // Active form hack
            if ( ! empty( $_POST['_active_form'] ) && empty( $fields['_active_form'] )
                 && isset( $fields['_config'][ $_POST['_active_form'] ] ) ) {
                $fields['_active_form'] = $_POST['_active_form'];
            }

            //@TODO: Apply condition that execute this script only on the pages where required
            if ( ! is_admin() )
            {
                return false;
            }

            if ( ! is_array( $data ) )
            {
                $data = array( 'title' => $data );
            }

            $this->boxes[] = $fields['_name'];

            if ( empty( $this->_meta_query ) )
            {
                add_action( 'add_meta_boxes', array( &$this, '_add_meta_boxes' ) );
                add_action( 'save_post', array( &$this, 'save_meta_box' ) );

                add_filter( 'exc-prepare-form', array( &$this, 'prepare_form' ) );

                $this->wp_admin()->prepare_form( $fields );
            }

            $data['fields'] = $fields['_name'];
            $this->_meta_query['add_meta'][] = $this->normalize_data( $data );

            return $this;
        }

        function prepare_form( $name = '' )
        {
            if ( in_array( $name, $this->boxes ) )
            {
                // Quick hack for saved data processing
                if ( count( $_POST ) == 0 )
                {
                    //check if we have db_name
                    if ( ! $db_name = exc_kv( $this->exc()->form->get_config( $name ), 'db_name' ) )
                    {
                        wp_die( sprintf( __('The db_name is not defined in %s', 'exc-framework' ), $this->form_settings['_path'] ) );
                    }

                    // fetch the user saved values
                    $post_id = ( isset( $_GET['post'] ) && intval( $_GET['post'] ) ) ? $_GET['post'] : '';

                    if ( $post_id && $settings = get_post_meta( $post_id, $db_name, true ) )
                    {
                        //Assign only the values we have in config file
                        $data = array();

                        foreach ( $this->exc()->form->get_fields_list( $name ) as $k => $v )
                        {
                            $field_name = ( ! empty( $v->config['dynamic_name'] ) ) ? $v->config['dynamic_name'] : $v->config['name'];
                            $field_value = exc_kv( $settings, $field_name );

                            if ( ! empty( $v->config['db_key'] ) ) {
                                $settings[ $field_name ] = get_post_meta( $post_id, exc_to_slug( $v->config['db_key'], $field_value ), true );
                            }

                            if ( isset( $settings[ $field_name ] ) )
                            {
                                if ( isset( $data[ $field_name ] ) )
                                {
                                    continue;
                                }

                                $data[ $field_name ] = $settings[ $field_name ];

                                if ( $v->is_dynamic )
                                {
                                    $dyn_data = apply_filters( 'exc_dynamic_fields_data', $settings[ $field_name ], $field_name );
                                    $this->exc()->html->localize_script( 'exc-dynamic-fields', 'exc_dynamic_fields', array( $v->is_dynamic => $dyn_data ) );
                                }
                            }
                        }

                        $this->exc()->validation->set_data( $data );
                        $this->exc()->form->apply_validation( $name );

                    } else //first time save values
                    {
                        $data = array();

                        foreach ( $this->exc()->form->get_fields_list() as $k => $v )
                        {
                            $field_name = ( ! empty( $v->config['dynamic_name'] ) ) ? $v->config['dynamic_name'] : $v->config['name'];

                            if ( isset( $data[ $field_name ] ) )
                            {
                                continue;
                            }

                            if ( $v->is_dynamic )
                            {
                                $this->exc()->html->localize_script( 'exc-dynamic-fields', 'exc_dynamic_fields', array( $v->is_dynamic => array() ) );
                                $data[ $field_name ] = array();

                            } else
                            {
                                $data[ $field_name ] = $v->set_value();
                            }
                        }

                        $this->exc()->validation->set_data( $data );
                    }
                }
            }

        }

        function _add_meta_boxes()
        {
            if ( empty( $this->_meta_query['add_meta'] ) )
            {
                return;
            }

            $this->load_bootstrap();

            foreach ( $this->_meta_query['add_meta'] as $index => $query )
            {
                extract( $query );

                $callback = ( isset( $callback ) ) ? $callback : array( $this, 'meta_html' );

                foreach ( $pages as $page )
                {
                    add_meta_box( $id, $title, $callback, $page, $context, $priority, array( 'fields' => $fields ) );
                }
            }

            unset( $this->_meta_query['add_meta'] );
        }

        function remove_meta_box( $id, $context = 'normal', $page = '' )
        {
            $did_action = did_action( 'admin_menu' );

            if ( ! is_admin() )
            {
                return;

            } elseif ( ! $did_action && empty( $this->_meta_query['remove'] ) )
            {
                add_action('admin_menu', array( &$this, '_remove_meta_boxes' ) );
            }

            if( ! is_array( $id ) )
            {
                $id = array( $id );
            }

            $page = ( $page ) ? $page : $this->pagenow;

            foreach ( $id as $pageID )
            {
                $this->_meta_query['remove'][] = array( 'id' => $pageID, 'page' => $page, 'context' => $context );
            }

            // Are we late? trigger it now
            if ( $did_action )
            {
                $this->_remove_meta_boxes();
            }

            return $this;
        }

        function _remove_meta_boxes()
        {
            if ( empty( $this->_meta_query['remove'] ) )
            {
                return;
            }

            foreach ( $this->_meta_query['remove'] as $index => $query )
            {
                extract( $query );

                remove_meta_box( $id, $page, $context );
            }

            unset( $this->_meta_query['remove'] );
        }

        function meta_html( $post, $data )
        {
            if ( ! $fields = exc_get_xpath_value( $data, 'args/fields' ) )
            {
                return false;
            }

            //@TODO: Process metabox_class code only if we have new input or data
            $post_id = $post->ID;

            if ( $post_data = get_transient( "exc_post_error_{$post_id}" ) )
            {
                delete_transient( "exc_post_error_{$post_id}" );
            }

            $this->exc()->form->load_settings( $fields );

            $config = $this->exc()->form->get_config( $fields );

            $this->exc()->load_view( exc_kv( $config, '_layout' ), $config );
        }

        /** save meta box */
        public function save_meta_box( $post_id )
        {
            /** verify the status of form nonce */
            //@TODO: replace this function with is_nonce_verified

            // If this is an autosave, our form has not been submitted, so we don't want to do anything.
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
                return $post_id;
            }

            // If _meta_query is empty then return post_id
            if ( empty( $this->boxes ) ) {
                return $post_id;
            }

            // Check the user's permissions.
            if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] )
            {
                if ( ! current_user_can( 'edit_page', $post_id ) ) {
                    return $post_id;
                }

            } else
            {
                if ( ! current_user_can( 'edit_post', $post_id ) ) {
                    return $post_id;
                }
            }

            $is_post = count( $_POST ) || false;
            $styles = array();

            foreach ( $this->boxes as $box )
            {
                $db_name = exc_kv( $this->exc()->form->get_config( $box ), 'db_name' );
                $settings = array();

                $fields =& $this->exc()->form->get_fields_list( $box );

                foreach ( ( array ) $fields as $field )
                {
                    $field_name = ( ! empty( $field->config['dynamic_name'] ) ) ? $field->config['dynamic_name'] : $field->config['name'];

                    //$value = ( $is_post ) ? exc_kv( $_POST, $field->config['name'], $field->config['default'] ) : $field->set_value();
                    $value = ( $is_post ) ? exc_kv( $_POST, $field_name ) : $field->set_value();

                    // Attach value
                    // @TODO: move in seperate function and add support for prepend
                    if ( $value && isset( $field->config['append'] ) )
                    {
                        if ( ! is_array( $field->config['append'] ) )
                        {
                            $field->config['append'] = ( array ) explode(', ', $field->config['append'] );
                        }

                        foreach ( $field->config['append'] as $append_key )
                        {
                            $append_value = ( ! isset( $fields->{ $append_key } ) ) ? $append_key : $fields->{ $append_key }->set_value();

                            //if ( $append_value )
                            //{
                            if ( is_array( $value ) )
                            {
                                $value[] = $append_value;
                            } elseif ( $append_value || is_numeric( $append_value ) )
                            {
                                $value = $value . ' ' . $append_value;
                            }
                            //}
                        }
                    }

                    if ( isset( $field->config['css_selector'] ) )
                    {
                        $group = ( ! empty( $field->config['style_opt_key'] ) ) ? $this->exc()->get_product_name() . '_' . $field->config['style_opt_key'] : $this->exc()->get_product_name();

                        if ( ! $prop_name = trim( exc_kv( $field->config, 'prop_name' ) ) )
                        {
                            continue;
                        }

                        $selector = preg_replace( '@\s+@', ' ', $field->config['css_selector'] );

                        if ( strpos( $prop_name, '%' ) )
                        {
                            $prop_value = $value;

                            if ( ! is_array( $prop_value ) )
                            {
                                $prop_value = array_filter( (array) explode( ' ', $prop_value ) );
                            }

                            $semicolon = ( substr( $prop_name, -1 ) != ';' ) ? ';' : '';

                            $styles[ $group ][ $selector ][ $prop_name ] = ( ! empty( $prop_value ) ) ? vsprintf( $prop_name, $prop_value ) . $semicolon : '';

                        } elseif ( ! empty( $value ) )
                        {
                            $prop_value = ( is_array( $value ) ) ? implode( ' ', $value ) : $value;
                            $styles[ $group ][ $selector ][ $prop_name ] = $prop_name . ': ' . $prop_value . ';';
                        } else
                        {
                            $styles[ $group ][ $selector ][ $prop_name ] = '';
                        }
                    }

                    // Add a standalone copy for easy access
                    if ( ! empty( $field->config['db_key'] ) )
                    {
                        update_post_meta( $post_id, exc_to_slug( $field->config['db_key'] ), $value );
                    }

                    $settings[ $field_name ] = $value;
                }

                //@TODO: keep the track of _active_form or form_key to automatically remove depreciated rules
                if ( ! empty( $styles ) )
                {
                    // Update Style
                    foreach ( $styles as $style_group => $style )
                    {
                        $opt_name = sanitize_title( $style_group . '_style' );
                        $opt_array_name = sanitize_title( $opt_name . '_array' );

                        $opt_value = get_option( $opt_array_name, array() );

                        $opt_value = array_replace_recursive( $opt_value, $style );

                        //create or update style and cache it
                        $css = '';
                        foreach ( $opt_value as $ok => $ov )
                        {
                            $has_value = false;

                            $props = '';

                            foreach ( $ov as $prop )
                            {
                                if ( $prop )
                                {
                                    $has_value = true;
                                    $props .= $prop;
                                }
                            }

                            if ( $has_value )
                            {
                                $css .= $ok.'{';
                                $css .= $props;
                                $css .= '}'."\n";

                            } else
                            {
                                //Value is removed so clear it
                                unset( $opt_value[ $ok ] );
                            }
                        }

                        update_post_meta( $post_id, $opt_name, $css );
                        update_post_meta( $post_id, $opt_array_name, $opt_value );
                    }
                }

                $settings = apply_filters( 'exc_before_meta_update', $settings, $post_id, $db_name );

                if ( $db_name )
                {
                    update_post_meta( $post_id, $db_name, $settings );

                } else
                {
                    foreach ( $data as $k => $v )
                    {
                        if ( isset( $_POST[ $k ] ) )
                        {
                            update_post_meta( $post_id, $k, $v );
                        }
                    }
                }
            }
        }

        private function normalize_data( $meta_box )
        {
            $meta_box = wp_parse_args( $meta_box, array(
                'id'             => sanitize_title( $meta_box['title'] ),
                'context'        => 'normal',
                'priority'       => 'high',
                'pages'         => $this->pages,
                'callback_args' => array(),
                'autosave'       => false,
                'default_hidden' => false,
                'type'           => 'add',
            ) );

            return $meta_box;
        }

        private function load_bootstrap()
        {
            //Load Bootstrap and style files on this page
            if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )
            {
                $this->exc()->load('core/html_class')->load_bootstrap()
                    ->load_css( 'theme-options-style', $this->exc()->system_url('views/css/style.css') )
                    ->load_css( 'font-awesome', $this->exc()->system_url('views/css/font-awesome.min.css') );
            }
        }
    }
}