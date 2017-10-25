<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Post_Author_About_Widget' ) )
{
	class eXc_Post_Author_About_Widget extends eXc_Widgets
	{
		protected $config = 'widgets/post_author_about';
		protected $view = 'widget_templates/post_author_about';
		
		function __construct()
		{
			parent::__construct();
		}

		function widget( $args, $instance )
		{
			global $wp_query;

			// Normalize the widget fields
			$instance = $this->_normalize( $instance );

			if ( ! intval( $instance['author'] ) ) {

				$current_post_object = get_queried_object();

				if ( is_single() && $current_post_object ) {

					$instance['author'] = $current_post_object->post_author;
				} else {
					return;
				}
			}
			
			exc_load_template( $this->view, array( 'args' => $args, 'instance' => $instance ) );
		}

		private function _normalize( $atts = array() )
		{
			return shortcode_atts(
				array(
					'title'		=> '',
					'author'	=> ''
					
				), $atts, 'exc_post_author_about_widget');
		}
		
		protected function _settings()
		{
			$this->widget_settings =
				array(
					'id_base'	=> 'exc_post_author_about_widget',
					'name'		=> esc_html__( 'Post Author About', 'exc-uploader-theme' ),
					
					'widget_options' =>
						array(
							'description' => __('Display post author about', 'exc-uploader-theme')
						),
						
					'control_options' => array()
				);
		}
	}
}