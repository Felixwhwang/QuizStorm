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
 * @link        http://extracoding.com
 * @since       Version 1.0
 */

// Load Config class if it's not loaded
if ( ! class_exists( 'eXc_Config_Class' ) ) {
    require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'config_class.php' );
}

/**
 * eXc_Base_Class
 *
 * This class provides the core functionality of extracoding framework
 *
 * @package     Extracoding framework
 * @subpackage  Core
 * @category    Core
 * @author      Hassan R. Bhutta
 * @path        includes/core/base_class.php
 * @license     Copyright 2014 © Extracoding. - All rights reserved
 */

if ( ! class_exists('eXc_Base_Class') )
{
    abstract class eXc_base_class extends eXc_Config_Class
    {
        /**
         * Autoload Classes On Load
         *
         * @since 1.0
         */
        private $_autoload = array();

        //The path query
        private $_path_query = array( 'path' => '', 'force_path' => '' );

        /**
         * Cache view variables
         *
         * @since 1.0
         */
        private $_cached_vars = array();

        private $fallback_paths = array();

        protected $type = '';

        //exc framework url
        protected $system_url = '';

        //exc framework directory path
        protected $system_dir = '';

        //Local plugin or theme system directory path
        protected $local_system_dir = '';

        /**
         * Framework Version ID
         *
         * @since 2.0
         */
        private $framework_version;

        /**
         * Product Name
         *
         * @since 1.0
         * @var string
         */
        protected $product_name;

        /**
         * Current Product Version
         *
         * @since 1.0
         * @var int
         */
        protected $product_version;

        /**
         * Current Product Type
         *
         * @since 2.0 [The type of the product possible values are theme or plugin]
         */
        protected $product_type = 'plugin';

        /**
         * Minimum Supported Version
         *
         * @since 2.0
         * @var int
         */
        protected $exc_supported_version;

        public $text_domain = '';

        // Local plugin or theme system directory URL
        public $local_system_url = '';

        //the status of class
        public $_load_status = true;

        /**
         * Contains the list of extensions
         *
         * @since 2.0
         * @var object
         */
        private $extensions = array();

        /**
         * Extensions Directory Map
         *
         * @since 2.0
         * @var array
         */
        private $extensions_map = array();

        /**
         * Active Extension Instance
         *
         * @since 2.3
         * @var string
         */
        public $active_extension = '';

        /**
         * Constructor
         *
         * The constructor of extracoding framework
         *
         */

        function __construct()
        {
            // Update current framework version
            $this->framework_version = EXTRACODING_FRAMEWORK_VERSION;

            // Local plugin and theme setup
            $this->env_setup();

            // Make extensions object
            $this->extensions = (object) array();

            // Global access
            if ( $this->product_type == 'theme' )
            {
                define( 'EXC_THEME_INSTANCE_NAME', $this->product_name );
            }

            $GLOBALS['_exc'][ $this->product_name ] =& $this;

            // Change the active product instance name
            exc_set_product_instance_name( $this->product_name );

            // Extracoding system directory path settings
            if ( defined( "EXC_BASE_DIRECTORY" ) && defined("EXC_BASE_DIRECTORY_URL") ) {

                $this->system_dir = EXC_BASE_DIRECTORY;
                $this->system_url = EXC_BASE_DIRECTORY_URL;

            } elseif ( strstr( dirname( __FILE__ ), 'thirdparty' ) ) {

                // Is loading from theme directory?
                $this->system_dir = trailingslashit( get_template_directory() . '/includes/thirdparty/exc-framework' );
                $this->system_url = get_template_directory_uri() . '/includes/thirdparty/exc-framework/';

            } else {

                $this->system_dir = trailingslashit( dirname( plugin_dir_path( __FILE__ ) ) );
                $this->system_url = plugin_dir_url( dirname( __FILE__ ) );

                if ( defined( 'EXTRACODING_FRAMEWORK_VERSION' ) && EXTRACODING_FRAMEWORK_VERSION ) {
                    $this->framework_version = EXTRACODING_FRAMEWORK_VERSION;
                }
            }

            // Check Framework Compatibility
            $this->compatibility_check();

            // Load Controller Class
            $this->load_abstract( 'core/abstracts/controller_abstract' );

            // Load Wordpress Admin Class
            $this->load( 'core/admin_class', 'wp_admin' );

            // Autoload classes
            foreach ( $this->_autoload as $class )
            {
                $this->load( $class );
            }
        }

        /**
         * Load PHP classes
         *
         * This method includes and auto initiate the constant of PHP classes from includes folder.
         * These classes are accessible locally through $this
         *
         * @access  public
         * @example $this->load($class_path, options); it will be accessible through exc_base_class::options
         * @param   string the path of class to load without .php e.g core/class_name
         * @param   string the class object name default is the same as class name
         * @param   boolen load and initialize class
         * @param   boolen create object of class on given object
         * @return  object
         */

        //@TODO: USE ReflectionMethod to get the arguments list dynamically
        public final function load( $class = '', $objectName = '', $loadonly = false, &$object = array(), $params = array() )
        {
            //@TODO: don't return instant if there are multiple files to load
            // Get the class name
            $classname = basename( $class );

            // guess system class name
            $system_class = 'eXc_' . $classname;

            /** Load File */
            $is_loaded = ( class_exists( $system_class ) || class_exists( $classname ) ) || $this->get_file_path( $class, true );

            /** clear temporary path and dependencies list */

            if ( ! $is_loaded)
            {
                $error_message = sprintf(
                                    __('The file %s is not exists.', 'exc-framework'),
                                    $class
                        );

                if ( WP_DEBUG && function_exists( 'debug_backtrace' ) ) {
                    echo $error_message . '<br /><pre>';

                    debug_print_backtrace();
                    exit;

                } else {
                    exc_die( $error_message );
                }
            }

            $this->clear_query();

            /** don't do anything if we just have to load file */
            if ( ! $loadonly )
            {
                //make sure load status is true, in case __constructor makes it false, then we will auto unset object
                $this->_load_status = true;

                /** check if it's system class */
                //@TODO: the code is lengthy, reduce it
                if ( class_exists( $system_class ) )
                {
                    $objectName = ( $objectName ) ? $objectName : str_ireplace( '_class', '', $classname );

                    if ( is_object( $object ) )
                    {
                        $object->{ $objectName } = ( isset( $object->{ $objectName } ) ) ? $object->{ $objectName } : new $system_class( $this, $params );

                        $object->{ $objectName }->_obj_name = $objectName;

                        if ( ! $this->_load_status )
                        {
                            return $this->destory( $objectName, $object );
                        }

                        return $object->{ $objectName };

                    } else
                    {
                        $this->{ $objectName } = ( isset( $this->{ $objectName } ) ) ? $this->{ $objectName } : new $system_class( $this, $params );

                        //hack & Pass the object name so we can reset it from class
                        $this->{ $objectName }->_obj_name = $objectName;

                        if ( ! $this->_load_status )
                        {
                            $this->destory( $objectName );
                            return;
                        }

                        return $this->{ $objectName };
                    }

                } elseif( class_exists( $classname ) )
                {
                    $objectName = ( $objectName ) ? $objectName : $classname;

                    if ( is_object( $object ) )
                    {
                        $object->{ $objectName } = ( $object->{ $objectName } ) ? $object->{ $objectName } : new $classname;

                        $object->{ $objectName }->_obj_name = $objectName;

                        if( ! $this->_load_status )
                        {
                            return $this->destory( $objectName, $object );
                        }

                        return $object->{ $objectName };

                    } else
                    {

                        $this->{ $objectName } = ( $this->{ $objectName } ) ? $this->{ $objectName } : new $classname;

                        $this->{ $objectName }->_obj_name = $objectName;


                        if ( ! $this->_load_status )
                        {
                            return $this->destory( $objectName );
                        }

                        return $this->{ $objectName };
                    }
                }
            }

            return true;
        }

        /**
         * Section settings
         *
         * A method to read the settings
         * if the section list is empty it will call the get_sections.
         *
         * @access  public
         * @param string section name (default is general settings)
         * @param string array key
         * @return array
         */

        // @DEPRECIATED use exc_get_option instead
        final function get_option( $key = '', $normalize = true, $depth = -2 )
        {
            if ( ! is_array( $key ) )
            {
                if ( ! $key || ! $settings = get_option( $key ) )
                {
                    return array();
                }

            } else
            {
                $settings = $key;
            }


            if( ! $normalize ) {
                return $settings;
            }

            //Normalize data
            return apply_filters( 'exc-option-' . $key, $this->normalize_data( $settings, $depth ) );
        }

        // Depreciated use exc_normalize_data
        final function normalize_data( $settings, $depth = 0 )
        {
            $data = array();

            foreach ( $settings as $k => $v )
            {
                $parts = explode( '-', $k );

                $structure = array_slice( $parts, $depth );

                exc_set_xpath_value( $data, implode( '/', $structure ), $v, 'set' );

            }

            return $data;
        }

        final function get_file_path( $file, $autoload = false, $ext = '.php' )
        {
            $fileName = $file . $ext;

            if ( isset( $this->_path_query['path']) )
            {
                $path = realpath( $this->_path_query['path'] . DIRECTORY_SEPARATOR . $fileName );

                if( ! $path && ! empty( $this->_path_query['force_path'] ) ) {
                    return false;
                }
            }

            if ( empty( $this->_path_query['path'] ) || empty( $this->_path_query['force_path'] ) )
            {
                $fileName = ( ! empty( $path ) ) ? $path : $fileName;

                // prefer the local path
                $path = $this->locate_file( $fileName );
            }

            return ( $autoload && $path ) ? require_once( $path ) : $path;
        }

        final function system_url( $path = '', $local_dir = false )
        {
            $system_url = ( $local_dir ) ? $this->local_system_url . $path : $this->system_url . $path;

            return apply_filters( 'exc_base_system_url', $system_url, $path, $local_dir );
        }

        final function system_path( $path, $file = '' )
        {
            $file_path = ( $file ) ? $path . '/' . $file . '.php' : $path;

            return apply_filters( 'exc_base_system_path', str_replace( $file . '.php', '', $this->locate_file( $file_path ) ), $path, $file );
        }

        final function set_path( $path = '', $force_path = true )
        {
            if ( ! realpath( $path ) && $force_path )
            {
                exc_die(
                    sprintf(
                        __('The directory path "%s" is not valid.', 'exc-framework'),
                        $path
                    )
                ); //@TODO: move string into lanuage file and support multilingual
            }

            $this->_path_query['path'] = wp_normalize_path( $path );
            $this->_path_query['force_path'] = $force_path;

            $this->_path_query = apply_filters( 'exc_base_set_path', $this->_path_query );

            return $this;
        }

        final function clear_query()
        {
            $this->_path_query =
                array(
                    'path'          => '',
                    'force_path'    => false
                );

            // @TODO: return all arguments if required
            if ( func_num_args() ) {
                return func_get_arg(0);
            }

            return $this;
        }

        final function load_widget( $file )
        {
            //make sure the abstract class is loaded
            if( ! class_exists( 'eXc_Widgets' ) )
            {
                $this->load_abstract( 'core/abstracts/widgets_abstract' );
            }

            $classname = 'eXc_' . basename( $file );

            $this->load( $file );

            register_widget( $classname );

            return $this;
        }

        final function load_abstract( $classes )
        {
            //@TODO: revalidate if the class loaded or not
            //@TODO: auto set path if it's not there
            $this->load( $classes, '', true );

            return $this;
        }

        final function load_with_args( $file, $args, $objectName = '' )
        {
            $obj = array();

            $this->load( $file, $objectName, false, $obj, $args );
        }

        final function load_file( $file )
        {
            return $this->load( $file, '', TRUE );
        }

        final function load_extension( $extension_name, $arguments = '' )
        {
            // Make sure the abstract class is loaded
            eXc_Base_Class::load_extension_abstract_class();

            do_action( 'exc_core_load_extension', $extension_name );

            $file_path = $extension_name . '.php';
            $extenion_path = '';
            $extension_url = '';

            if ( ! file_exists( $file_path )
                && ( ! $extension_path = $this->locate_file( $file_path ) ) )
            {
                if ( ! $extension_path = $this->locate_file( 'extensions/' . $file_path, array( 'local_dir', 'fallback' ) ) )
                {
                    exc_die(
                        sprintf(
                            esc_html__('The extension directory %s is not exists.', 'exc-framework'),
                            '<strong>' . $file_path . '</strong>'
                        )
                    );
                }

                $extension_url = $this->get_dir_url( 'extensions/' . dirname( $file_path ), array( 'local_dir', 'fallback' ) );

            } else
            {
                $extension_url = $this->get_dir_url( dirname( $file_path ) );
            }

            $extension_dir = wp_normalize_path( dirname( $extension_path ) );
            $extension_base_name = basename( $extension_name );

            $extension_class_name = ( ! empty( $arguments['classname'] ) )
                                    ? $arguments['classname']
                                    : 'eXc_' . $extension_base_name . '_Extension';

            $this->set_path( $extension_dir )->load( $extension_base_name, false );

            if ( ! empty( $arguments['autoload'] ) && class_exists( $extension_class_name ) )
            {
                unset( $arguments['classname'] );
                unset( $arguments['autoload'] );

                $arguments['extension_dir'] = $extension_dir . '/';
                $arguments['extension_url'] = $extension_url;
                $arguments['extension_base_name'] = $extension_base_name;
                $this->extensions_map[ $arguments['extension_dir'] ] = $extension_base_name;

                return $this->extensions->{ $extension_base_name } = new $extension_class_name( $this, $arguments );
            }
        }

        final function load_library( $file, $argument = '' )
        {
            if ( $argument )
            {
                exc_die( __("Arguments passed in load library", 'exc-framework' ) );
            }

            if ( empty ( $this->_path_query['path'] ) )
            {
                $path = $this->system_path( 'libraries', $file );
                $this->set_path( $path );
            }

            $this->load( $file, '', true );
        }

        final function load_template( $file, $vars = array(), $_view_file_return = FALSE )
        {
            if( ! $_view_file_path = locate_template( $file . '.php' ) ) {
                return;
            }

            return $this->load_view( basename( $file ), $vars, $_view_file_return, dirname( $_view_file_path ) );
        }

        final function load_view( $file, $vars = array(), $_view_file_return = FALSE, $_view_dir = '' )
        {
            // @TODO: add security to load files from specific directories only

            // convert object array to standard array
            $vars = ( is_object( $vars ) ) ? get_object_vars( $vars ) : $vars;

            $_view_file_path = '';

            $append_extension = '.php';

            if ( '.php' == strtolower( substr( $file, -4 ) ) ) {
                $append_extension = '';
            }

            if ( file_exists( $file . $append_extension ) )
            {
                $_view_file_path = $file . $append_extension;

            } elseif ( ! empty( $this->_path_query['path'] ) )
            {
                $_view_file_path = $this->get_file_path( $file );
            }

            if ( ! $_view_file_path )
            {
                $path = ( $_view_dir ) ? $_view_dir : $this->system_path( 'views', $file );
                $this->set_path( $path );

                $_view_file_path = $this->get_file_path( $file );
            }

            // Buffer the loaded views one by one
            if ( $_view_file_path )
            {
                $this->_cached_vars = array_merge( $this->_cached_vars, $vars );

                extract( $this->_cached_vars );

                ob_start();

                include( $_view_file_path );

                if ( $_view_file_return === TRUE )
                {
                    $buffer = ob_get_contents();
                    @ob_end_clean();

                    $this->clear_query();

                    return $buffer;
                }

                ob_end_flush();
            }

            $this->clear_query();
        }

        final function get_dir_url( $dir, $location = array( 'local_dir', 'system_dir' ) )
        {
            if ( ! is_array( $location ) )
            {
                $location = array( $location );
            }

            if ( in_array('local_dir', $location) && realpath( $this->local_system_dir . $dir ) )
            {
                return $this->local_system_url . $dir . '/';
            } elseif ( in_array('system_dir', $location) && realpath($this->system_dir . $dir) )
            {
                return $this->system_url . $dir . '/';

            }

            return apply_filters( 'exc_base_empty_dir_url', '', $dir, $location );
        }

        final function locate_dir( $dir, $location = array( 'local_dir', 'system_dir' ) )
        {
            if ( ! is_array( $location ) )
            {
                $location = apply_filters( 'exc_base_locate_dir_in', array( $location ) );
            }

            if ( in_array('local_dir', $location) && defined('CHILD_THEME_PATH') && is_dir( CHILD_THEME_PATH . '/includes/' . $dir ) )
            {
                return CHILD_THEME_PATH . '/includes/' . $dir . '/';

            } elseif ( in_array('local_dir', $location) && is_dir( $this->local_system_dir . $dir ) )
            {
                return $this->local_system_dir . $dir . '/';

            } elseif ( in_array('system_dir', $location) && is_dir( $this->system_dir . $dir ) )
            {
                return $this->system_dir . $dir . '/';
            }

            return apply_filters( 'exc_base_empty_dir_path', '', $location );
        }

        final function locate_file( $file, $location = array( 'local_dir', 'system_dir', 'fallback' ) )
        {
            if ( ! is_array( $location ) )
            {
                $location = apply_filters( 'exc_base_locate_file_in', array( $location ) );
            }

            $file = wp_normalize_path( $file );

            $locate_file = apply_filters( 'exc_file_' . exc_to_slug( $file ), '', wp_normalize_path( $file ) );

            if ( $locate_file ) {
                return $locate_file;
            }

            // The first priority is to check if the file is available on query path

            if ( ! empty( $this->_path_query['path'] ) )
            {
                if ( $file_path = realpath( $this->_path_query['path'] . $file ) )
                {
                    return $file_path;
                }

            } elseif( empty( $this->_path_query['path'] ) && ! $this->_path_query['force_path'] )
            {
                if ( in_array('local_dir', $location) && defined('CHILD_THEME_PATH') && realpath( CHILD_THEME_PATH . '/includes/' . $file ) )
                {
                    return CHILD_THEME_PATH . '/includes/' . $file;

                } elseif( in_array('local_dir', $location) && realpath( $this->local_system_dir . $file ) )
                {
                    return $this->local_system_dir . $file;

                } elseif ( in_array('system_dir', $location) && realpath( $this->system_dir . $file ) )
                {
                    return $this->system_dir . $file;

                } elseif ( in_array( 'fallback', $location ) )
                {
                    // Automatically check if we have file in fallback paths
                    if ( ! empty( $this->fallback_paths ) )
                    {
                        foreach ( $this->fallback_paths as $fallback )
                        {
                            if ( realpath( $fallback['path'] . $file ) )
                            {
                                return apply_filters( 'exc_base_fallback_path', $fallback['path'] . $file, $fallback, $file );
                            }
                        }
                    }
                }
            }

            return apply_filters( 'exc_base_empty_file_path', '', $file, $location );
        }

        final function get_file_url( $file, $location = array( 'local_dir', 'system_dir', 'fallback' ) )
        {
            if ( ! is_array( $location ) )
            {
                $location = array( $location );
            }

            $file = wp_normalize_path( $file );

            if ( ! empty( $this->_path_query['path'] ) )
            {
                if ( $file_path = realpath( $this->_path_query['path'] . $file ) )
                {
                    return exc_path_to_url( $this->_path_query['path'], $file );
                }

            } elseif( empty( $this->_path_query['path'] ) && ! $this->_path_query['force_path'] )
            {
                // @TODO: USE ABOVE CODE TO AUTOMATICALLY GENERATE THE URLS

                if ( in_array('local_dir', $location) && realpath( $this->local_system_dir . $file ) )
                {
                    return $this->local_system_url . $file;

                } elseif ( in_array('system_dir', $location) && realpath( $this->system_dir . $file ) )
                {
                    return $this->system_url . $file;

                } elseif ( in_array( 'fallback', $location ) )
                {
                    if ( ! empty( $this->fallback_paths ) )
                    {
                        foreach ( $this->fallback_paths as $fallback )
                        {
                            if ( ! empty( $fallback['url'] ) && realpath( $fallback['path'] . $file ) )
                            {
                                return apply_filters( 'exc_base_fallback_url', $fallback['url'] . $file, $fallback, $file );
                            }
                        }
                    }
                }
            }

            return apply_filters( 'exc_base_empty_file_url', '', $file, $location );
        }

        final function destory($object_name, &$obj = array())
        {
            if($obj && is_object($obj->{$object_name}))
            {
                unset($obj->{$object_name});

            }elseif( ! $obj && $this->{$object_name})
            {
                unset( $this->{$object_name} );
            }
        }

        // Check if extension is loaded
        final function is_extension( $extension_name )
        {
            return isset( $this->extensions->{ $extension_name } ) ? TRUE : FALSE;
        }

        final function &extension( $extension_name = '' )
        {
            if ( ! $extension_name && $this->active_extension ) {

                if ( is_a( $this->active_extension, 'eXc_Extension_Abstract' ) ) {
                    return $this->active_extension;
                }

                $extension_name = $this->active_extension;
            }

            if ( ! $extension_name || empty( $this->extensions->{ $extension_name } ) )
            {
                wp_die(
                    sprintf(
                        __( "The extension %s is not loaded.", 'exc-framework' ),
                        $extension_name
                     )
                );
            }

            return $this->extensions->{ $extension_name };
        }

        final function set_active_extension( $extension_name )
        {
            // Make sure that the old path settings are removed
            $this->clear_query();

            $this->active_extension = $extension_name;
        }

        final function set_fallback_path( $path, $url = '' )
        {
            $this->fallback_paths[ $path ] = array( 'path' => $path, 'url' => $url );
        }

        final function get_version()
        {
            if ( defined( 'EXTRACODING_FRAMEWORK_VERSION' ) && EXTRACODING_FRAMEWORK_VERSION )
            {
                return EXTRACODING_FRAMEWORK_VERSION;
            }

            return $this->framework_version;
        }

        final function get_supported_version()
        {
            return $this->exc_supported_version;
        }

        final function get_product_name()
        {
            return $this->product_name;
        }

        final function get_product_version()
        {
            return $this->product_version;
        }

        final function compatibility_check()
        {
            $this->upgrade_option_name = 'exc-notice-' . $this->product_name . '-' . sanitize_title( $this->exc_supported_version );

            $is_compatible = TRUE;

            if ( $this->exc_supported_version &&
                    version_compare ( $this->exc_supported_version, $this->get_version(), '>' ) ) {
                $is_compatible = FALSE;
            }

            if ( ! $is_compatible && 'off' != get_option( $this->upgrade_option_name ) )
            {
                add_action( 'wp_ajax_exc_dismiss_upgrade_notice', array( &$this, 'hide_upgrade_notice' ) );

                add_action( 'admin_notices', array( &$this, 'display_compatibility_notice' ) );
                add_action( 'admin_footer', array( &$this, 'print_inline_script' ) );

                // Set user-defined error handler function
                //set_error_handler( array( $this, 'error_handler' ) );
            }

            return $is_compatible;
        }

        final function hide_upgrade_notice()
        {
            if ( ! empty( $_POST['product'] ) )
            {
                $product_name = sanitize_title( $_POST['product'] );

                update_option( $product_name, 'off' );
            }
        }

        final function get_query_path( $path = '' )
        {
            $path = wp_normalize_path( $path );

            return $this->_path_query['path'] . $path;
        }

        final function get_query_url( $path )
        {
            $path = wp_normalize_path( $path );

            return exc_path_to_url( $this->_path_query['path'], $path );
        }

        final function display_compatibility_notice()
        {
            ?>
            <div class="notice error exc-dismiss-upgrade-notice is-dismissible">
                <p><?php printf(
                            __( 'WARNING: The %s requires minimum version of Extracoding Framework v%s, otherwise many options will not work properly and you may see PHP errors.', 'exc-framework' ),
                            '<strong>' . exc_to_text( str_replace( 'exc-', '', $this->product_name ) ) . ' ' . exc_to_text( $this->type ) . '</strong>',

                            $this->exc_supported_version
                        );?>
                </p>
            </div>
            <?php
        }

        public function error_handler( $errno, $errstr, $errfile, $errline )
        {
            //echo "<b>Custom error:</b> [$errno] $errstr<br>";
            //echo " Error on line $errline in $errfile<br>";
        }

        protected final function get_product_option_name( $option_name = '' )
        {
            if ( $option_name ) {
                return $this->product_name . '-' . $option_name;
            }

            return $this->product_name;
        }

        protected final function set_product_option( $name, $value )
        {
            $option_name = $this->get_product_option_name( $name );

            return update_option( $option_name, $value );
        }

        static public function load_extension_abstract_class()
        {
            if ( ! class_exists( 'eXc_Extension_Abstract' ) )
            {
                require_once 'abstracts/extension_abstract.php';
            }
        }

        static public function load_theme_abstract_class()
        {
            if ( ! class_exists( 'eXc_Theme_Abstract' ) )
            {
                require_once 'abstracts/theme_abstract.php';
            }
        }

        protected function env_setup()
        {
            $obj = new ReflectionClass( $this );
            $filename = $obj->getFileName();

            $this->product_name = ( ! empty( $this->product_name ) ) ? $this->product_name : basename( dirname( $filename ) );

            // is local theme directory
            // @TODO: add wp native check for theme directory
            if ( $this->product_type == 'theme' )
            {
                $this->local_system_dir = get_template_directory() . '/includes/';
                $this->local_system_url = get_template_directory_uri() . '/includes/';

                // Define Unique Theme Prefix to avoide conflict between multiple themes settings

                $name = ( $this->product_name ) ? $this->product_name : basename( dirname( $filename ) );

                defined( 'THEME_PREFIX' ) or define( 'THEME_PREFIX', $name . '_' );

                // Theme directory URL
                defined( 'THEME_URL' ) or define( 'THEME_URL', get_template_directory_uri() );

                // Child Theme directory Path
                defined( 'CHILD_THEME_PATH' ) or define( 'CHILD_THEME_PATH', get_stylesheet_directory() );

                // Child Theme directory URL
                defined( 'CHILD_THEME_URL' ) or define( 'CHILD_THEME_URL', get_stylesheet_directory_uri() );

                // is Child theme active
                defined( 'IS_CHILD_THEME' ) or define( 'IS_CHILD_THEME', (THEME_PATH != CHILD_THEME_PATH) ? true : false );

            } else
            {
                $this->local_system_dir = plugin_dir_path( $filename );
                $this->local_system_url = plugin_dir_url( $filename );
            }

            $this->view_path = $this->local_system_dir . 'views/';
        }

        public function print_inline_script()
        {?>
            <script type="text/javascript">
                ( function(window, $ ){
                    $('.exc-dismiss-upgrade-notice').on('click', function(e){
                        $.post( ajaxurl, {'action': 'exc_dismiss_upgrade_notice', 'product' : '<?php echo $this->upgrade_option_name;?>' }, function(r){

                        })
                    });
                })( window, jQuery );
            </script>

        <?php
        }
    }
}