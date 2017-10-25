<?php defined('ABSPATH') OR die('restricted access');

class eXc_Advanced_Posts_Widget extends eXc_Widgets
{
    protected $config = 'widgets/advanced_posts';
    protected $view = 'widgets/advanced_posts/style2';

    function widget( $args, $instance )
    {
        $atts = $this->_normalize( $instance );

        query_posts( $atts );

        $this->eXc->load_view( $this->view, array('args' => $args, 'instance' => $instance ) );

        wp_reset_query();
    }

    private function _normalize( $atts = array() )
    {
        if ( isset( $atts['template'] ) )
        {
            $this->view = 'widgets/advanced_posts/' . $atts['template'];
        }

        return shortcode_atts(
            array(
                'post_type'         => array( 'post', 'exc_audio_post', 'exc_video_post', 'exc_image_post' ),
                'category__in'      => array(),
                'posts_per_page'    => 10,
                'orderby'           => 'post_count',
                'order'             => 'ASC',

            ), $atts, 'exc_advanced_posts_widget');
    }

    protected function _settings()
    {
        $this->widget_settings =
            array(
                'id_base' => 'exc_advanced_posts_widget',
                'name' => __( 'Advanced Posts Widget', 'exc-uploader-theme' ),

                'widget_options' =>
                    array(
                        'description' => __('With the help of this widget you can display posts', 'exc-uploader-theme')
                    ),

                'control_options' => array()
            );
    }
}