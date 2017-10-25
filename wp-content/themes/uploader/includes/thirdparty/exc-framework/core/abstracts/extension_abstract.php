<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Extension_Abstract' ) ) :

abstract class eXc_Extension_Abstract
{
    /**
     * Extracoding Framework Instance
     *
     * @since 1.0
     * @var object
     */
    protected $eXc;

    /**
     * Argments passed to this class
     *
     * @since 1.0
     * @var object
     */
    protected $args = array();

    /**
     * Extension Path
     *
     * @since 1.0
     * @var string
     */
    protected $extension_path;

    /**
     * Extension URL
     *
     * @since 1.0
     * @var string
     */
    protected $extension_url;

    /**
     * Database name to store the extension settings
     *
     * @since 1.0
     * @var string
     */
    protected $db_name = '';

    /**
     * Extension admin settings
     *
     * @since 1.0
     * @var array
     */
    protected $admin_settings = array();

    /**
     * Register Fallback Support
     *
     * @since 1.0
     * @var boolean
     */
    protected $fallback_support = false;

    /**
     * Abstract Method to Initialize Extension
     *
     * @since 1.0
     * @return null
     */
    abstract protected function initialize_extension();

    public function __construct( &$eXc, $args = array() )
    {
        $this->eXc = $eXc;

        $this->set_extension_path( $args['extension_dir'] );
        $this->set_extension_url( $args['extension_url'] );

        unset( $args['extension_dir'] );
        unset( $args['extension_url'] );

        $this->args = $args;

        // System will use extension files, if they are not available in core directories
        if ( $this->fallback_support )
        {
            $this->eXc->set_fallback_path( $this->extension_path, $this->extension_url );
        }

        $this->initialize_extension();
    }

    protected final function set_extension_path( $extension_path )
    {
        $this->extension_path = $extension_path;
    }

    protected final function set_extension_url( $extension_url )
    {
        $this->extension_url = $extension_url;
    }

    public final function get_extension_path( $path = '' )
    {
        return $this->extension_path . $path;
    }

    public final function get_extension_url( $path = '' )
    {
        return $this->extension_url . $path;
    }

    public final function load( $class = '', $objectName = '', $loadonly = false, $params = array() )
    {
        return $this->eXc->set_path( $this->extension_path )
                    ->load( $class = '', $objectName = '', $loadonly = false, $this, $params = array() );
    }

    public final function set_path()
    {
        $this->eXc->set_path( $this->extension_path );
    }

    public final function &exc( $clear_query_path = true )
    {
        // Automatically clear the query path
        if ( $clear_query_path ) {
            $this->eXc->clear_query();
        }

        return $this->eXc;
    }

    public final function &local( $path = '' )
    {
        if ( $path ) {
            $path = $path . '/';
        }

        // Change active extension settings
        $this->eXc->set_active_extension( $this->args['extension_base_name'] );
        $this->eXc->active_extension =& $this;

        // Set active directory
        $this->eXc->set_path( $this->extension_path . $path );

        return $this->eXc;
    }

    public function &get_admin_settings( $key = '' )
    {
        if ( ! empty( $key ) )
        {
            // Return only the required information
            return exc_kv( $this->admin_settings, $key );
        }

        // Return Admin Settings
        return $this->admin_settings;
    }

    public final function get_db_name()
    {
        return $this->db_name;
    }

    public final function get_extension_name()
    {
        return $this->args['extension_base_name'];
    }
}

endif;