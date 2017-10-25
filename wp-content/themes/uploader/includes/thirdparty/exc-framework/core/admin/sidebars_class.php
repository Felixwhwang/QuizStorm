<?php if ( ! defined('ABSPATH')) exit('restricted access');

class eXc_Sidebars_Class
{
    private $eXc;
    private $_data_query = array();
    private $wp_registered_sidebars;

    function __construct( &$_eXc )
    {
        $this->eXc = $_eXc;
        $this->wp_registered_sidebars =& $GLOBALS['wp_registered_sidebars'];
    }

    function register( )
    {
        $sidebars = func_get_args();

        $current_filter = current_filter();

        if ( empty( $this->_data_query['register'] ) && $current_filter != 'widgets_init' )
        {
            add_action( 'widgets_init', array( $this, '_register_sidebars' ) );
        }

        foreach ( $sidebars as $sidebar )
        {
            if ( ! is_array( $sidebar ) )
            {
                $sidebar = array( 'name' => $sidebar );
            }

            $this->_data_query['register'][] = $this->normalize_data( $sidebar );
        }

        // Automatically register widgets if current action is widgets_init
        if ( $current_filter == 'widgets_init' )
        {
            $this->_register_sidebars();
        }

        return $this;
    }

    function unregister()
    {
        $sidebars = func_get_args();

        if ( empty( $this->_data_query['remove_widgets'] ) )
        {
            add_action( 'widgets_init', array( $this, '_unregister_sidebars' ), 11 );
        }

        foreach ( $sidebars as $sidebar )
        {
            if ( strpos( $sidebar, ' ' ) !== false )
            {
                $sidebar = sanitize_title( $sidebar );
            }

            $this->_data_query['unregister'][] = $sidebar;
        }

        return $this;
    }

    public function _register_sidebars()
    {
        foreach ( $this->_data_query['register'] as $sidebar )
        {
            register_sidebar( $sidebar );
        }

        $GLOBALS['exc_sidebars_list'] = $this->get_array();

        unset( $this->_data_query['register'] );
    }

    public function _unregister_sidebars()
    {
        foreach ( $this->_data_query['unregister'] as $sidebar )
        {
            unregister_sidebar( $sidebar );
        }

        unset( $this->_data_query['unregister'] );
    }

    //Return the registered sidebars list
    public function get_list()
    {
        return $this->wp_registered_sidebars;
    }

    public function get_array()
    {
        $sidebars = array();

        foreach ( $this->wp_registered_sidebars as $k => $v )
        {
            $sidebars[ $k ] = $v['name'];
        }

        return $sidebars;
    }

    private function normalize_data( $data )
    {
        $data = wp_parse_args(
                        $data, array(
                                'id'            => sanitize_title($data['name']),
                                'name'          => __( 'Custom Sidebar Name', 'exc-framework' ),
                                'description'   => '',
                                'class'         => '',
                                'before_widget' => '<li id="%1$s" class="widget %2$s">',
                                'after_widget'  => '</li>',
                                'before_title'  => '<h2 class="widgettitle">',
                                'after_title'   => '</h2>'
                            )
                        );

        return $data;
    }
}