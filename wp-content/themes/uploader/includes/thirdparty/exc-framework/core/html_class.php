<?php if ( ! defined('ABSPATH')) exit('restricted access');

/**
 * Extracoding Framework
 *
 * An open source extracoding framework
 *
 * @package     Extracoding framework
 * @author      Extracoding team <info@extracoding.com>
 * @copyright   Copyright 2014 © Extracoding - All rights reserved
 * @license     http://extracoding.com/framework/license.html
 * @link        http://extracoding.com
 * @since       Version 1.0
 */
/**
 * eXc_html_class
 *
 * This class handles the html of Extracoding Framework
 *
 * @package     Extracoding Framework
 * @subpackage  Core
 * @category    Models
 * @author      WPnukes Development Team
 * @path includes/core/base_class.php
 * @license Copyright 2012 © Fourthwave technologies, (PVT) Limited. - All rights reserved
 */

class eXc_html_class
{
    private $eXc;
    private $bootstrap = false;
    private $files = array(
                        'css'   => array(),
                        'js'    => array(),
                        'less'  => array(),
                        'templates' => array(),
                        'inline_js' => array()
                    );

    private $localize = array();
    //private $inline = array( 'css'=>array(), 'js'=>array() );

    function __construct( &$eXc )
    {
        $this->eXc = $eXc;

        add_action( 'admin_print_footer_scripts', array( $this, '_inline_js' ), 20 );
        add_action( 'wp_print_footer_scripts', array( $this, '_inline_js' ), 20 );

        // The benefit of using custom function is that we can load javascript and style files anytime
        $this->load_js_args( 'exc-framework', $this->eXc->system_url( 'views/js/exc-framework.js' ), array( 'jquery', 'underscore' ) );

        if ( ! is_admin() )
        {
            $this->localize_script( 'exc-framework', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
            $this->localize_script( 'exc-framework', 'exc_security', wp_create_nonce('exc-security') );
        }
    }

    function load_bootstrap()
    {
        if ( !!$this->bootstrap )
        {
            return $this;
        }

        if ( is_admin() )
        {
            $this->load_js(
                array(
                    'handle'    => 'less',
                    'src'       => $this->eXc->system_url( 'views/js/less.min.js' ),
                    'deps'      => array( 'jquery' ),
                    'version'   => '2.5.1',
                    'in_footer' => false
                    )
                );

            add_action( 'admin_print_styles', array( &$this, '_load_less_bootstrap' ), 5 );

        } else
        {
            $this->load_css( 'bootstrap', $this->eXc->system_url( 'views/css/bootstrap.min.css' ), '3.3.5' );
        }

        $this->load_js(
                    array(
                        'handle'    => 'bootstrap',
                        'src'       => $this->eXc->system_url( 'views/js/bootstrap.min.js' ),
                        'deps'      => array( 'jquery' ),
                        'version'   =>'3.3.5',
                        'in_footer' => true )
                    );

        //Mark to check the bootstrap is already loaded
        $this->bootstrap = false;

        return $this;
    }

    function _load_less_bootstrap()
    {
        echo '<style type="text/less">
                .exc-panel, .exc-bootstrap{ @import (less) url( "' . $this->eXc->system_url('views/css/bootstrap.css') . '"); }
            </style>';
    }

    function load_js( $name = array(), $src = '', $version = '' )
    {
        if ( ! is_array( $name ) )
        {
            $name = ( ! $src ) ? array('handle' => md5( $name ), 'src' => ($src) ? $src : $name)
                                    : $name = array('handle' => $name, 'src' => $src);
        }

        //Automatically clear scheme for ssl
        $name['src'] = preg_replace( '@^https?:@', '', $name['src'] );

        $data = wp_parse_args( $name,
                                array(
                                    'handle'    => '',
                                    'src'       => $src,
                                    'deps'      => array(),
                                    'ver'       => $version,
                                    'in_footer' => true,
                                    'type'      => 'js',
                                )
                            );

        if ( empty( $this->fields['js'] ) )
        {
            add_action( 'admin_print_scripts', array( $this, '_register_js_files' ), 11 );
            add_action( 'wp_enqueue_scripts', array( $this, '_register_js_files' ), 11 );
        }

        if ( ! isset( $this->files['js'][ $data['handle'] ] ) && ! empty( $data['src'] ) )
        {
            @extract( $data );

            $this->files['js'][ $data['handle'] ] = $data;
        }

        return $this;
    }

    function load_js_args( $handle, $src, $deps = array(), $ver = '', $in_footer = true )
    {
        return $this->load_js( array(
                            'handle' => $handle,
                            'src' => $src,
                            'deps' => $deps,
                            'ver' => $ver,
                            'in_footer' => $in_footer
                    ));
    }

    function load_css($name = array(), $src = '', $version = '')
    {
        if ( ! is_array( $name ) )
        {
            $name = ( $src ) ? array( 'handle' => $name, 'src' => $src )
                            : array( 'handle' => md5( $name ), 'src' => $name );
        }

        $data = wp_parse_args( $name, array(
                                        'handle'    => '',
                                        'src'       => '',
                                        'deps'      => array(),
                                        'ver'       => $version,
                                        'media'     => 'all',
                                        'type'      => 'css'
                                    )
                                );

        if ( empty( $this->fields['css']) )
        {
            add_action( 'admin_print_styles', array( $this, '_register_css_files'), 11 );
            add_action( 'wp_enqueue_scripts', array( $this, '_register_css_files'), 11 );
        }

        if ( ! isset( $this->files['css'][ $data['handle'] ] ) && ! empty( $data['src'] ) )
        {
            $this->files['css'][ $data['handle'] ] = $data;
        }

        return $this;
    }

    function load_css_args($handle, $src, $deps = array(), $ver = '', $media = 'all')
    {
        return $this->load_css(
                        array(
                            'handle'    => $handle,
                            'src'       => $src,
                            'deps'      => $deps,
                            'ver'       => $ver,
                            'media'     => $media
                        )
                    );
    }

    function _register_js_files()
    {
        foreach ( $this->files['js'] as $file )
        {
            @extract($file);
            wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
        }

        //clear memory
        unset( $this->files['js'] );
    }

    function _register_css_files()
    {
        foreach ( $this->files['css'] as $file )
        {
            @extract( $file );
            wp_enqueue_style( $handle, $src, $deps, $ver, $media );
        }

        //clear memory
        unset( $this->files['css'] );
    }

    // @NOTE: we are using wordpress built-in function wp_add_inline_style for inline_css
    function inline_js( $handle = '', $code, $dep = '', $jQuery = false, $append = false )
    {
        if ( isset( $this->files['inline_js'][ $handle ] ) )
        {
            if ( $append )
            {
                $this->files['inline_js'][ $handle ]['code'] .= "\n" . $code;
            }

            return;
        }

        $this->files['inline_js'][ $handle ] = array( 'dep' => $dep, 'code' => $code, 'jQuery' => $jQuery );

        return $this;
    }

    function _inline_js()
    {
        if ( empty( $this->files['inline_js'] ) )
        {
            return;
        }

        $js_code = $jQuery = '';

        foreach ( $this->files['inline_js'] as $id => $script )
        {
            if ( ! is_array( $script['dep'] ) )
            {
                $script['dep'] = array( $script['dep'] );
            }

            foreach( $script['dep'] as $dep )
            {
                if ( ! wp_script_is( $dep, 'done' ) )
                {
                    continue;
                }
            }

            //check if handle is loaded
            if ( $script['jQuery'] === TRUE ) {
                $jQuery .= $script['code'] . "\n";
            } elseif ( $script['jQuery'] == 'template' ) {
                echo '<script type="text/template" id="' . esc_attr( $id ) . '">' . $script['code'] . '</script>';
            } else {
                $js_code .= $script['code']."\n";
            }
        }

        if ( $js_code || $jQuery ) {
            echo '<script type="text/javascript">'."\n";
            echo $js_code;

            if ( $jQuery )
            {
                echo 'jQuery(document).ready(function($){'."\n";
                echo $jQuery;
                echo "});";
            }

            echo '</script>'."\n";
        }

        //clear Memory
        unset( $this->files['inline_js'] );
    }

    public function js_template( $template_id, $data, $handle = '' )
    {
        $this->files['inline_js'][ $template_id ] = array( 'dep' => array( $handle ), 'code' => $data, 'jQuery' => 'template' );

        return $this;
    }

    function localize_script( $handle, $name, $data, $action = '' )
    {
        //static $i = 0;

        if ( empty( $this->localize ) )
        {
            add_action( 'admin_print_footer_scripts', array( $this, '_localize_script' ), 1 );
            add_action( 'wp_print_footer_scripts', array( $this, '_localize_script' ), 1 );
        }

        $parts = explode( '/', $name );

        // Automatically consider the second part as index
        //$index = ( ! empty( $parts[1] ) ) ? $parts[1] : $i++;

        $name = $parts[0];
        $xpath = $handle . '/' . $name;

        //$name = str_replace( '/', '_', $name );

        if ( $action == 'not_exists' )
        {
            if ( ! exc_kv( $this->localize, $xpath ) )
            {
                exc_set_xpath_value( $this->localize, $xpath, array( 'handle' => $handle, 'name' => $name, 'data' => $data ), 'set' );
            }

        } elseif ( $action == 'replace' )
        {
            exc_set_xpath_value( $this->localize, $xpath, array( 'handle' => $handle, 'name' => $name, 'data' => $data ) );

        //} elseif ( $action == 'extend' || ! empty( $this->localize[ $handle ] ) )
        } elseif ( $action == 'merge' && ! empty( $this->localize[ $handle ] ) )
        {
            $this->localize[ $handle ][0]['data'] = array_merge( $this->localize[ $handle ][0]['data'], $data );

            // if ( empty( $this->localize[ $handle ] ) )
            // {
            //     $this->localize[ $handle ][] = array( 'handle' => $handle, 'name' => $name, 'data' => $data );
            // } elseif ( is_array( $data ) )
            // {
            //     $this->localize[ $handle ][0]['data'] = array_merge( $this->localize[ $handle ][0]['data'], $data );
            // } else
            // {
            //     $this->localize[ $handle ][] = array( 'handle' => $handle, 'name' => $name, 'data' => $data );
            // }

        } elseif( $action == 'extend' && ! empty( $this->localize[ $handle ] ) )
        {
            $first_key = key( $this->localize[ $handle ] );
            $index = ( ! empty( $parts[1] ) ) ? $parts[1] : $first_key + 1;

            $this->localize[ $handle ][ $first_key ]['data'][ $index ] = $data;

        } elseif( $action == 'extend' ) {

            $index = ( ! empty( $parts[1] ) ) ? $parts[1] : 0;

            $this->localize[ $handle ][] = array(
                                                'handle' => $handle,
                                                'name' => $name,
                                                'data' => array( $index => $data )
                                            );
        } else
        {
            $this->localize[ $handle ][] = array( 'handle' => $handle, 'name' => $name, 'data' => $data );
        }

        return $this;
    }

    function _localize_script( $scripts )
    {
        $global = ( empty( $scripts ) ) ? true : false;

        $scripts = ( ! $global ) ? $scripts : $this->localize;

        foreach ( $scripts as $script )
        {
            if ( ! isset( $script['handle'] ) && is_array( $script ) )
            {
                $this->_localize_script( $script );

            } else
            {
                wp_localize_script( $script['handle'], $script['name'], $script['data'] );
            }
        }

        // Clear variable to reduce memory load
        if ( $global )
        {
            $this->localize = array();
        }
    }

    function load_template( $template, $args = array() )
    {
        if ( empty( $this->files['templates'] ) )
        {
            if ( is_admin() )
            {
                add_action( 'admin_footer', array( &$this, '_load_template') );
            } else
            {
                add_action( 'wp_footer', array( &$this, '_load_template' ) );
            }
        }

        if ( isset( $this->files['templates'][ $template ] ) )
        {
            return;
        }

        if ( $args && ! is_array( $args ) )
        {
            $args = array( 'id' => $args );
        }

        $this->files['templates'][ $template ] = array(
                                                    'file' => $template,
                                                    'args' => $args
                                                );
        return $this;
    }

    function _load_template()
    {
        if ( empty( $this->files['templates'] ) )
        {
            return;
        }

        foreach ( $this->files['templates'] as $template )
        {
            $this->eXc->load_view( $template['file'], $template['args'] );
        }

        //clear Memory
        unset( $this->files['templates'] );
    }

    public function get_enqueued_files( $type = '' )
    {
        // if ( ! empty( $type ) ) {
        //     return exc_kv( $this->files, $type );
        // }

        // return $this->files;
        // @TODO: this condition
        $files = array();

        // @TODO: clear other javascript files
        $this->_register_js_files();
        $this->_register_css_files();

        $this->get_javascript_files( $files );
        $this->get_stylesheet_files( $files );

        return $files;
    }

    private function get_javascript_files( &$files )
    {
        ob_start();

        wp_print_head_scripts();
        wp_print_footer_scripts();
        $content = ob_get_contents();

        ob_end_clean();

        $dom = new DomDocument();
        $dom->loadHTML( $content );

        // Loop through each <a> tag in the dom and add it to the link array
        $scripts = $dom->getElementsByTagName('script');

        foreach ( $scripts as $index => $link ) {

            $thisNode = $scripts->item( $index );

            $nodeAttributes = array();

            if ( $thisNode->hasAttributes() ) {

                foreach ( $thisNode->attributes as $attr ) {
                    $nodeAttributes[ $attr->nodeName ] = $attr->nodeValue;
                }
            }

            if ( $link->hasAttribute('src') ) {

                $files['js'][] = array(
                        'type'      => 'js',
                        'source'    => $link->getAttribute( 'src' ),
                        'html'      => sprintf( '<script %s></script>', exc_parse_atts( $nodeAttributes ) )
                    );

                continue;
            }

            // @TODO: add attributes values
            $files['js'][] = array(
                    'type'      => 'inline',
                    'source'    => $link->nodeValue,
                    'html'      => sprintf( '<script %s>%s</script>', exc_parse_atts( $nodeAttributes ), $link->nodeValue )
                );
        }
    }

    private function get_stylesheet_files( &$files )
    {
        // @TODO: load stylesheets
    }
}