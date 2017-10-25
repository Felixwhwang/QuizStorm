<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Plugin_Abstract' ) )
{
    abstract class eXc_Plugin_Abstract extends eXc_Base_Class
    {
        /**
         * Argments passed to this class
         *
         * @since 1.0
         * @var object
         */
        protected $plugin_args = array();

        /**
         * Plugin Directory Path
         *
         * @since 1.0
         * @var string
         */
        protected $plugin_path;

        /**
         * Plugin Directory URL
         *
         * @since 1.0
         * @var string
         */
        protected $plugin_url;

        /**
         * Database name to store the plugin settings
         *
         * @since 1.0
         * @var string
         */
        protected $db_name = '';

        /**
         * Hold the admin settings
         *
         * @since 1.0
         * @var object
         */
        protected $admin_settings = array();

        /**
         * Abstract Method to Initialize Plugin
         *
         * @since 1.0
         * @return null
         */
        abstract protected function initialize_plugin();

        public final function __construct( $arguments = array() )
        {
            // Check Plugin Compatiblity with framework
            $is_framework_compatible = ( ! empty( $this->exc_supported_version ) ) ? $this->compatibility_check() : TRUE;

            if ( ! $is_framework_compatible || empty( $arguments['file'] ) ) {
                // Display Error
                return;
            }

            parent::__construct();

            $this->plugin_dir = plugin_dir_path( $arguments['file'] );
            $this->plugin_url = plugin_dir_url( $arguments['file'] );

            $this->plugin_args = $arguments;

            // Register Hooks ( capabitlity to register hooks before init if required )
            if ( method_exists( $this, 'register_hooks' ) ) {
                $this->register_hooks();
            }

            if ( ! empty( $this->db_name ) ) {
                $this->admin_settings = get_option( $this->db_name, array() );
            }

            // Initialize Plugin
            add_action( 'init', array( &$this, 'initialize_plugin' ), 1 );

            // Initialize Plugin
            //add_action( 'plugins_loaded', array( &$this, 'initialize_plugin' ), 1 );
            //$this->initialize_plugin();
        }

        public function local( $path = '' )
        {
            if ( $path ) {
                $path = $path . '/';
            }

            $this->set_path( $this->plugin_dir . $path );

            return $this;
        }

        public function &get_admin_settings( $key = '' )
        {
            if ( ! empty( $key ) ) {
                // Return only the required information
                return exc_kv( $this->admin_settings, $key );
            }

            // Return Admin Settings
            return $this->admin_settings;
        }

        public function get_db_name()
        {
            return $this->db_name;
        }
    }
}