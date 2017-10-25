<?php defined('ABSPATH') OR die('restricted access');

/**
 * eXc_Base_Class
 *
 * Core Class
 *
 * @package     Extracoding framework
 * @subpackage  Core
 * @category    Core
 * @since       Version 1.0
 * @path        includes/core/base_class.php
 * @author      Hassan r. Bhutta <info@extracoding.com>
 * @license     http://extracoding.com/framework/license.html
 * @website     http://extracoding.com
 * @copyright   Copyright 2014 © Extracoding - All rights reserved
 */

abstract class eXc_config_class
{
    /**
    *
    * Loaded configuration files data
    *
    * @since 1.0
    * @var object
    */
    private $_config = array();

    /**
    *
    * Last opened file path
    *
    * @since 1.0
    * @var string
    */
    private $_last_file = '';

    final function load_config( $files = array(), $args = array() )
    {
        if ( ! is_array( $files ) )
        {
            $files = array( $files );
        }

        foreach( $files as $file )
        {
            $options = $this->load_config_file( $file, $args );

            // dispatch global event to manipulate configurations
            //$options = apply_filters( 'exc_config_array', $options, $file );

            $this->_config[ $file ] = $options;
        }

        return $this;
    }

    final function load_config_file( $_file, $args = array(), $load_only = false )
    {
        $_file = wp_normalize_path( $_file );

        // @TODO: add additional security to make sure the file is in allowed locations
        $file_path = $_file . '.php';

        // Locate only the file if it's not exists
        if ( file_exists( $file_path ) )
        {
            $this->_last_file = $file_path;

        } elseif ( ! $this->_last_file = $this->locate_file( $file_path ) )
        {
            if ( ! $this->_last_file = $this->locate_file( 'config/' . $file_path, array('local_dir', 'fallback') ) )
            {
                exc_die(
                    sprintf(
                        esc_html__('The configuration file %s is not exists.', 'exc-framework'),
                        '<strong>' . $_file . '</strong>'
                    )
                );
            }
        }

        @extract( $args );
        require( $this->_last_file );

        $file_path_chunks = explode( '/', $_file );

        $file_form_name = implode( '_', array_slice( $file_path_chunks, -2 ) );

        $filter_name = 'exc_config_array_' . $file_form_name;

        if ( $load_only || ! isset( $options['_config'] ) )
        {
            return apply_filters( $filter_name, $options, $_file );
        }


        // Add the name of file for later use

        $options = wp_parse_args(
                        $options,
                        array(
                            '_name'         => $file_form_name,
                            '_form_name'    => 'exc-form-' . $file_form_name,
                            '_path'         => $this->_last_file,
                            '_nonce'        => exc_kv( $options, '_nonce', wp_create_nonce( $file_form_name ) ),
                            '_capabilities' => 'edit_posts'
                        )
                    );

        return apply_filters( $filter_name, $options, $_file );
    }

    private final function _default_config()
    {
        /** The very first load file will always be the default one */
        return current( $this->_config );
    }

    final function &get_config_array($file = '')
    {
        if(empty($this->_config)) return array();

        $file = ($file) ? $file : key($this->_config);

        return $this->_config[$file];
    }
}