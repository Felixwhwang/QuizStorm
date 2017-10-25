<?php defined('ABSPATH') OR die('restricted access');

class eXc_Users_Widget extends eXc_Widgets
{
    protected $config = 'widgets/users';
    protected $view = 'widgets/users';

    function __construct()
    {
        parent::__construct();
        add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_files' ), 1 );
    }

    function enqueue_files()
    {
        $js = $this->eXc->get_file_url( 'views/js/users.js', 'local_dir' );

        wp_enqueue_script( 'exc-users-widget', $js, array('jquery'), true );
    }

    function widget( $args, $instance )
    {
        $atts = $this->_normalize( $instance );

        $user_query = new WP_User_Query( $atts );

        $this->eXc->load_view( $this->view, array('args' => $args, 'instance' => $instance, 'user_query' => $user_query ) );
    }

    private function _normalize( $atts = array() )
    {
        if( isset( $atts['exclude'] ) && ! is_array( $atts['exclude'] ) )
        {
            $atts['exclude'] = array_filter( (array) explode( ',', $atts['exclude'] ) );
        }

        if ( isset( $atts['role'] ) && is_array( $atts['role'] ) )
        {
            $atts['role'] = current( $atts['role'] );
        }

        return shortcode_atts(
            array(
                'number'        => 20, //limit the number of users per page
                'role'          => 'contributor',
                'exclude'       => array(), //by default don't include admin, force this option through theme option
                'orderby'       => 'post_count',
                'order'         => 'ASC', //must be ASC or DESC
                'hide_empty'    => true,
                'offset'        => 0,
                'post_limit'    => 3,
                'count_total'   => true

            ), $atts, 'exc_users_widget');
    }

    protected function _settings()
    {
        $this->widget_settings =
            array(
                'id_base' => 'exc_user_widget',
                'name' => __( 'Users Widget', 'exc-uploader-theme' ),

                'widget_options' =>
                    array(
                        'description' => __('With the help of this widget you can display twitter feeds', 'exc-uploader-theme')
                    ),

                'control_options' => array()
            );
    }
}