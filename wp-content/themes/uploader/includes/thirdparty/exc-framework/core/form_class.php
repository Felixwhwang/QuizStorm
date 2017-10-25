<?php defined('ABSPATH') OR die('restricted access');

/**
 * Extracoding Framework
 *
 * An open source extracoding framework
 *
 * @package     Extracoding framework
 * @author      Extracoding team <info@extracoding.com>
 * @copyright   Copyright 2014 © Extracoding - All rights reserved
 * @license     http://extracoding.com/framework/license.html
 * @website     http://extracoding.com
 * @since       Version 1.0
 */

if ( ! class_exists( 'eXc_Form_class' ) ) :
class eXc_Form_class
{
    private $eXc;
    private $nonce_status = false;
    private $fields;
    private $loaded; //keep the name of last loaded config file
    private $config_ref = array();

    function __construct( &$_eXc )
    {
        $this->eXc = $_eXc;
        $this->fields = (object) array();
        $this->eXc->load_abstract('core/abstracts/fields_abstract');

        // Load Validation Class
        $this->eXc->load('core/validation_class');

        // Load HTML class
        $this->eXc->load('core/html_class');
    }

    public function display_messages( $name, $includes = array( 'err_msgs', 'success_msgs' ) )
    {
        if ( ! empty( $name ) && empty( $this->fields->{ $name } ) )
        {
            return;

        } elseif ( ! is_array( $includes ) )
        {
            $includes = array( $includes );
        }

        $errors = $this->eXc->validation->errors_array();

        // Display fields messages only when requested
        $display_err_msgs = in_array( 'err_msgs', $includes );

        // Stop Field Messages
        $fields =& $this->fields->{ $name };

        foreach ( $errors as $field_key => $error )
        {
            if ( $display_err_msgs && isset( $fields->{ $field_key } ) )
            {
                $fields->{ $field_key }->display_error( FALSE );
            }

            echo $this->eXc->validation->error( $field_key );
        }

        if ( empty( $errors ) && in_array( 'success_msgs', $includes ) )
        {
            echo implode( "\n", (array) $this->eXc->validation->_success_messages );
        }
    }

    //@TODO: Clears fields data
    public function get_html( $config = array(), $path = '', $prefix = '' )
    {
        if ( $path == '_settings' ) {
            return;
        }

        $prefix = ( $prefix ) ? $prefix : str_replace('/', '-', $path);

        if ( ! empty( $path ) )
        {
            if ( ! $config = exc_get_xpath_value( $config, $path ) )
            {
                exc_die( __( 'Empty form settings', 'exc-framework' ) );
            }
        }

        foreach ( $config as $key => $val ) {

            if ( ! $type = exc_kv( $val, 'type' ) ) {

                //user can change layout anytime
                $prefix = ( $prefix ) ? $prefix . '-' . $key : ( ( $path ) ? $path . '-' . $key : $key );

                if ( $settings = exc_kv( $val, '_settings', array() ) ) {
                    unset( $val['_settings'] );
                }

                $layout = exc_kv( $settings, '_layout' );

                if ( $layout ) {
                    $this->eXc->load_view( $layout, array( '_config' => $val, '_settings' => $settings, '_prefix' => $prefix, 'parent_key' => $key ) );
                } else {
                    $this->eXc->load_view( $key, array('_config' => $val, '_settings' => $settings, '_prefix' => $prefix, 'parent_key' => $key) );
                }

            } else
            {
                $field_name = ( ! empty( $config[ $key ]['name'] ) )
                                ? $config[ $key ]['name'] : 'EMPTY_FIELD_NAME';

                if ( isset( $this->fields->{ $this->loaded }->{ $field_name } ) )
                {
                    $this->fields->{ $this->loaded }->{ $field_name }->html();

                } else
                {
                    exc_die(
                        sprintf(
                            __( '%s is not exists', 'exc-framework' ),
                            $field_name
                         )
                    );
                }
            }
        }
    }

    //@TODO: replace this method functionality in fields_abstract so it can pick automatically address from config
    public function prepare_fields( array &$config, $path = '')
    {
        // already prepared? load and return name
        if ( ( $name = exc_kv( $config, '_name') ) && isset( $this->config_ref[ $name ] ) ) {
            return $this->loaded = $name;
        }

        if ( isset( $_REQUEST[ $config['_form_name'] ] ) &&
            ( $_REQUEST[ $config['_form_name'] ] == $config['_nonce'] ) )
        {
            $this->nonce_status = TRUE;

            //@TODO: capability checker & security error message
            if ( $config['_capabilities'] && ! current_user_can( $config['_capabilities'] ) ) {
                exc_die( __('You don\'t have permission to access some of elements on this page.', 'exc-framework') );
            }

        // Don't prepare form if we have POST and active form
        } /*elseif ( isset( $_POST['_active_form'] ) && $_POST['_active_form'] != exc_kv( $config, '_active_form' ) || $this->nonce_status ) {
            return;
        }*/

        //store the name of last loaded file
        $this->loaded = $name;
        $this->config_ref[ $name ] =& $config; //@TODO: reference it with _name in case of conflicts

        $this->fields->{ $this->loaded } = ( object ) array();

        $config['_active_form'] = ( $this->nonce_status && isset( $_POST['_active_form'] ) && ( $active_form = exc_kv( $_POST, '_active_form') ) )
                                    ? str_replace('-', '/', $active_form) : exc_kv( $config, '_active_form');

        if ( ! empty( $config['_active_form'] ) ) {

            $path = str_replace('/', '-', $config['_active_form']);

            $active_form = exc_get_xpath_value( $config['_config'], $config['_active_form'] );

            if ( empty( $active_form ) ) {
                exc_die( __('You do not have sufficient permissions to access this page.', 'exc-framework' ) );
            } else {
                $this->_prepare_form_fields( $active_form, $path );
            }

        } else {
            $this->_prepare_form_fields( $config['_config'], $path );
        }

        apply_filters( 'exc-prepare-form', $config['_name'] );

        if ( $this->verify_nonce() ) {
            $this->eXc->validation->run();
        }

        $this->config_ref[ $name ]['nonce_status'] = $this->nonce_status;

        //@TODO: unset config and assign them to form
        return $config['_name'];
    }

    private function _prepare_form_fields( &$config, $path = array(), $is_dynamic = false, $depth = 0 )
    {
        if ( empty( $config ) || ! is_array( $config ) )
        {
            return;
        }

        if ( ! is_array( $path ) )
        {
            $path = ( ! $path ) ? array() : array( $path );
        }

        $keys = array_keys( $config );

        //$previousPath = $path;

        foreach ( $keys as $key )
        {
            // don't include special _settings key
            if ( $key == '_settings' )
            {
                continue;
            }

            $path[] = $key;

            //$path = ( $previousPath ) ? $previousPath . '-' . $key : $key;

            if ( ! isset( $config[ $key ]['type'] ) )
            {
                if ( $key == 'dynamic' || $key == 'inline_group' || $key == 'nestable' )
                {
                    $is_dynamic = true;
                    $depth = 0;

                    $default_toolbar_options = array( 'delete', 'move', 'settings', 'status' );

                    $disable_status_field = array();

                    // Automatically Append Status Field
                    foreach ( $config[ $key ] as $section_index => $section_array )
                    {
                        if ( $section_index == '_settings' )
                        {
                            foreach ( $section_array as $section_name => $section_settings )
                            {
                                $toolbar = exc_kv( $config[ $key ]['_settings'][ $section_name ], 'toolbar' );

                                if ( $toolbar === FALSE )
                                {
                                    $config[ $key ]['_settings'][ $section_name ]['toolbar'] = array();
                                    $disable_status_field[ $section_name ] = TRUE;

                                } elseif ( empty( $toolbar ) )
                                {
                                    $config[ $key ]['_settings'][ $section_name ]['toolbar'] = $default_toolbar_options;

                                } elseif ( is_array( $toolbar ) && ( ! in_array( 'status', $toolbar ) ) )
                                {
                                    $disable_status_field[ $section_name ] = TRUE;
                                }
                            }

                        } else
                        {
                            // Automatically Add _settings if missing
                            if ( ! isset( $config[ $key ]['_settings'][ $section_index ] ) )
                            {
                                $config[ $key ]['_settings'][ $section_index ] = array( 'toolbar' => $default_toolbar_options );
                            }

                            if ( ! isset( $section_array['status'] ) && ! isset( $disable_status_field[ $section_index ] ) )
                            {
                                $config[ $key ][ $section_index ]['status'] = array(

                                                            'label'     => esc_html__( 'Row Status', 'exc-framework' ),
                                                            'type'      => 'hidden',
                                                            'attrs'     => array(
                                                                            'class' => 'exc-row-status',
                                                                            ),

                                                            'default'   => 'on'
                                                        );
                            }
                        }
                    }

                } elseif ( $is_dynamic )
                {
                    // Developer can change the name from config file
                    if ( ! empty( $config['_settings'][ $key ]['tmpl_id'] ) )
                    {
                        //$is_dynamic = $config['_settings'][ $key ]['tmpl_id'];
                    } else // Automatically change the name
                    {
                        if ( ! $depth ){
                            $config['_settings'][ $key ]['tmpl_id'] = $is_dynamic = $key;
                        } elseif ( $depth & 2 )
                        {
                            $is_dynamic = $is_dynamic . '-' . $key;
                        }

                        $depth++;
                    }
                }

                $this->_prepare_form_fields( $config[ $key ], $path, $is_dynamic, $depth );

            } elseif ( ! $type = $config[ $key ]['type'] )
            {
                wp_die(
                    sprintf(
                        __("The field %s type must be defined.", 'exc-framework' ),
                        implode( '/', $path )
                     )
                );

            }

            // we will pass only address to avoid extra memory usage and also manipulate it for later usage
            else
            {
                $field_name = ( ! empty( $config[ $key ]['name'] ) )
                                ? $config[ $key ]['name'] : '';

                // Automatically Assign Shortnames
                $sections_tree = array_reverse( $path );

                foreach ( $sections_tree as $section )
                {
                    if ( empty( $field_name ) )
                    {
                        $field_name = ( $is_dynamic ) ? $is_dynamic . '-' . $section : $section;

                    } elseif ( ! empty( $this->fields->{ $this->loaded }->{ $field_name } ) )
                    {
                        $field_name = $section . '-' . $field_name;
                    } else
                    {
                        break;
                    }
                }

                $config[ $key ]['name'] = $field_name;

                $field_class = $type . '_field';

                $this->eXc->load( 'core/fields/' . $field_class, $field_name, false, $this->fields->{ $this->loaded } )->set_data( $field_name, $config[ $key ], $key, $is_dynamic );

                if ( !!$this->nonce_status )
                {
                    $this->fields->{ $this->loaded }->{ $field_name }->do_validation( $field_name );
                }
            }
        }
    }

    public function apply_validation( $form = '' )
    {
        //Apply validation on currently loaded fields
        $form = ( $form ) ? $form : $this->loaded;

        if ( ! isset( $this->fields->{ $form } ) )
        {
            exc_die( sprintf( __('You must load/prepare %s before validation', 'exc-framework'), $form ) );
        }

        foreach ( $this->fields->{ $form } as $object => $data )
        {
            $this->fields->{ $form }->{ $object }->do_validation();
        }

        return $this->eXc->validation->run();
    }

    public function is_form_loaded( $form_name )
    {
        return ! empty( $this->config_ref[ $form_name ] );
    }

    public function load_settings( $name )
    {
        // Old name support will be depreciated in future
        $name = str_replace( '/', '_', $name );

        if ( isset( $this->fields->{ $name } ) )
        {
            $this->loaded = $name;
        }

        return $this;
    }

    public function &get_fields_list( $name = '', $load = false)
    {
        // Old name support will be depreciated in future
        $name = str_replace( '/', '_', $name );

        if ( $load )
        {
            $this->load_settings( $name );
        }

        if ( $name )
        {
            return $this->fields->{ $name };

        } else
        {
            return $this->fields->{ $this->loaded };
        }
    }

    public function is_nonce_verified( $name )
    {
        // Old name support will be depreciated in future
        $name = str_replace( '/', '_', $name );

        if ( ! isset( $this->config_ref, $name ) )
        {
            exc_die( sprintf( __( 'The %s config file is not loaded.', 'exc-framework' ), $name ) );
        }

        return !! $this->config_ref[ $name ]['nonce_status'];
    }

    public function &get_config( $name )
    {
        // Old name support will be depreciated in future
        $name = str_replace( '/', '_', $name );

        if ( ! isset( $this->config_ref, $name ) )
        {
            wp_die( sprintf( __( 'The %s config file is not loaded.', 'exc-framework' ), $name ) );
        }

        return $this->config_ref[ $name ];
    }

    public function verify_nonce()
    {
        return !!$this->nonce_status;
    }

    public function get_form_settings( $name = '', $extra = array())
    {
        // Old name support will be depreciated in future
        $name = str_replace( '/', '_', $name );

        $html = '<input type="hidden" name="%s" value="%s" id="%s" />'."\n";

        if ( ! isset( $this->config_ref[ $name ]['form_fields'] ) )
        {
            $this->config_ref[ $name ]['form_fields'] = array();
        }

        if ( isset( $this->config_ref[ $name ] ) )
        {
            if ( ! is_array( $this->config_ref[ $name ]['form_fields'] ) )
            {
                parse_str( $this->config_ref[ $name ]['form_fields'], $this->config_ref[ $name ]['form_fields'] );
            }

            if ( ! empty( $extra) )
            {
                $this->config_ref[ $name ]['form_fields'] = array_merge( $this->config_ref[ $name ]['form_fields'], $extra );
            }

            echo sprintf( $html, $this->config_ref[ $name ]['_form_name'], $this->config_ref[ $name ]['_nonce'], 'exc-form-security');

            $fields = apply_filters( 'exc_form_settings', $this->config_ref[ $name ][ 'form_fields' ], $name );

            foreach ( (array) $fields as $k=>$v)
            {
                echo sprintf( $html, $k, $v, 'exc-form-' . $k );
            }
        }
    }
}

endif;