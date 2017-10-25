<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Controller_Abstract' ) ) :

abstract class eXc_Controller_Abstract
{
    /**
     * Extracoding Framework Instance
     *
     * @since 1.0
     * @var object
     */
    protected $exc_product_instance_name;

    /**
     * Argments passed to this class
     *
     * @since 1.0
     * @var object
     */
    protected $arguments = array();

    /**
     * Name of the extension where class is initiated
     * must be passed as reference in arguments
     *
     * @since 1.0
     * @var object
     */
    protected $extension_name;

    /**
     * Abstract Method to Initialize Class
     *
     * @since 1.0
     * @return null
     */
    abstract protected function initialize_class();

    public function __construct( &$intance, $arguments = array() )
    {
        $this->exc_product_instance_name = $intance->get_product_name();

        $this->arguments = $arguments;

        if ( ! empty( $arguments['extension'] ) && is_a( $arguments['extension'], 'eXc_Extension_Abstract' ) ) {
            $this->extension =& $arguments['extension'];
        }

        // Initialize Class
        $this->initialize_class();
    }

    public final function &exc( $clear_query_path = true )
    {
        // Automatically clear the query path
        exc_set_product_instance_name( $this->exc_product_instance_name );

        $exc_instance =& exc_get_instance();

        if ( is_bool( $clear_query_path ) ) {
            $exc_instance->clear_query();
        }

        return $exc_instance;
    }

    public final function &wp_admin( $clear_query_path = true )
    {
        return $this->exc( $clear_query_path )->wp_admin;
    }

    public final function &extension()
    {
        return $this->extension;
    }
}

endif;