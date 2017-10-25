<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Theme_Abstract' ) ) :

abstract class eXc_Theme_Abstract extends eXc_Base_Class
{
    /**
     * Product Name
     *
     * @since 1.0
     * @var string
     */
    protected $product_name = '';

    /**
     * Extracoding Framework minimum version compatibility
     *
     * @since 1.1
     * @var float
     */
    protected $exc_supported_version = 1.0;

    /**
     * Theme version id
     *
     * @since 1.0
     * @var string Automatically Update Based on Stylesheet version ID
     */
    protected $product_version = '';

    /**
     * Product Type
     *
     * @since 1.1
     * @var string
     */
    protected $product_type = 'theme';

    /**
     * Argments passed to this class
     *
     * @since 1.0
     * @var object
     */
    protected $arguments = array();

    /**
     * Plugin Directory Path
     *
     * @since 1.0
     * @var string
     */
    protected $active_theme_dir;

    /**
     * Plugin Directory URL
     *
     * @since 1.0
     * @var string
     */
    protected $active_theme_url;

    /**
     * Parent theme directory
     *
     * @since 1.0
     * @var string
     */
    protected $parent_theme_dir;

    /**
     * Parent theme URL
     *
     * @since 1.0
     * @var string
     */
    protected $parent_theme_url;

    /**
     * Is child theme?
     *
     * @since 1.0
     * @var string
     */
    protected $is_child_theme;

    /**
     * Database version id
     *
     * @since 1.0
     * @var string
     */
    protected $db_version_id;

    /**
     * Contains the wordpress generated theme data
     *
     * @since 1.0
     * @var string
     */
    protected $theme_data = array();

    /**
     * Stylesheet theme name
     *
     * @since 1.0
     * @var string
     */
    protected $active_theme_name;

    /**
     * Abstract Method to Initialize Plugin
     *
     * @since 1.0
     * @return null
     */
    abstract protected function initialize_theme();

    /**
     * Return a list of required extension
     *
     * @since 1.0
     * @return array | null
     */
    abstract protected function list_of_required_extensions();

    /**
     * Return a list of supported extensions
     *
     * @since 1.0
     * @return array | null
     */
    //abstract public function list_of_supported_extensions();

    public final function __construct( $arguments = array() )
    {
        if ( ! is_array ( $arguments ) ) {
            $arguments = ( empty( $arguments ) ) ? array()
                            : array( 'filepath' => $arguments );
        }

        if ( ! isset( $arguments['filepath'] ) ) {
            $this->set_base_directory();
        }

        // Check Plugin Compatiblity with framework
        $is_framework_compatible = FALSE;

        if ( ! empty( $this->exc_supported_version ) ) {
            $is_framework_compatible = $this->compatibility_check();
        }

        if ( ! $is_framework_compatible ) {
            // Display Error
            // @TODO: Run the upgrade function
            return;
        }

        // Set parent theme version id
        $this->set_theme_version();

        $this->active_theme_dir =
        $this->parent_theme_dir = trailingslashit( get_template_directory() );

        $this->active_theme_url =
        $this->parent_theme_url = get_template_directory_uri();

        if ( is_child_theme() ) {
            $this->is_child_theme = TRUE;

            $this->active_theme_dir = trailingslashit( get_stylesheet_directory() );

            $this->active_theme_url = get_stylesheet_directory_uri();
        }

        $this->local_system_dir = trailingslashit( dirname( $arguments['filepath'] ) );

        $this->local_system_url = exc_path_to_url( $this->local_system_dir );

        // Cache the user passed arguments for later use
        $this->arguments = $arguments;

        // Register Hooks
        // ( capabitlity to register hooks before init if required )

        // Parent construct
        parent::__construct();

        // Make sure the database and current theme version is same
        // otherwise call the upgrade method
        if ( $this->product_version != $this->db_version_id ) {

            $theme_slug = exc_to_slug( $this->product_version  );
            $upgrade_method_name = 'upgrade_' . $theme_slug;

            if ( ! method_exists( $this, $upgrade_method_name ) ) {
                exc_die(
                    sprintf(
                        __('The "%s" theme requires upgrade for version %s
                            but the method %s is missing.', 'exc-framework'),
                        '<strong>' . $this->active_theme_name . '</strong>',
                        $this->product_version,
                        '<strong>' . $upgrade_method_name . '</strong>'
                    )
                );
            }

            // Announce the upgrade
            $upgrade_filter_name = $this->product_name . '-upgrade-' .
                                        $this->product_version;

            do_action( $upgrade_filter_name, $this );

            $upgrade_status = $this->{ $upgrade_method_name }();

            if ( ! $upgrade_status ) {
                // The upgrade is failed or in progress so do nothing
                return;
            }
        }

        // if ( method_exists( $this, 'register_hooks' ) ) {
        //     $this->register_hooks();
        // }

        // Session Library
        $this->load_with_args(
                'core/session_class',
                array( 'cookie_path' => SITECOOKIEPATH )
            );

        // Layout Helper
        $this->load_file('functions/layout_helper');

        // Automatically load required extensions
        $this->load_extensions();

        // Load Controller Abstract
        $this->load_abstract( 'core/abstracts/controller_abstract' );

        // Initialize Theme
        $this->initialize_theme();
        //add_action( 'init', array( &$this, 'initialize_theme' ), 1 );
    }

    public function local( $path = '' )
    {
        if ( $path ) {
            $path = $path . '/';
        }

        $this->set_path( $this->parent_theme_dir . $path );

        return $this;
    }

    protected function load_extensions()
    {
        $required_extensions_list = $this->list_of_required_extensions();
        $installed_extensions_list = $this->list_of_installed_extensions();

        if ( $installed_extensions_list ) {
            $required_extensions_list = array_merge(
                    $required_extensions_list,
                    $installed_extensions_list
                );
        }

        // Fetch the list of active extensions
        if ( empty( $required_extensions_list ) ) {
            return;
        }

        foreach ( (array) $required_extensions_list as $extension ) {
            $this->load_extension( $extension, array( 'autoload' => true ) );
        }
    }

    private function list_of_installed_extensions()
    {
        $option_name = $this->get_product_option_name( 'installed-extensions' );

        return get_option( $option_name, array() );
    }

    private function set_theme_version()
    {
        // Update Product Version
        $this->theme_data = $theme_data = wp_get_theme();

        $this->active_theme_name = $theme_data->get('Name');
        $this->product_version = $theme_data->get('Version');

        if ( $theme_data->parent() ) {
            $this->active_theme_name = $theme_data->parent()->get('Name');
            $this->product_version = $theme_data->parent()->get('Version');
        }

        // Database Version
        $db_version_option_name = $this->get_product_option_name('version');
        $this->db_version_id = get_option( $db_version_option_name );

        if ( ! $this->db_version_id ) {
            $this->db_version_id = 0;
        }
    }

    protected function upgrade_1_0_0()
    {
        // Automatically add the database version id on first launch
        return $this->set_product_option( 'version', $this->product_version );
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
        define( 'IS_CHILD_THEME', ( THEME_PATH != CHILD_THEME_PATH ) ? true : false );

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

endif;