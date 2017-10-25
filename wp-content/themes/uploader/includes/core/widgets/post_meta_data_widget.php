<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Post_Meta_Data_Widget' ) )
{
	class eXc_Post_Meta_Data_Widget extends eXc_Widgets
	{
		protected $config = 'widgets/post_meta_data';
		protected $view = 'widget_templates/post_meta_data';
		
		function __construct()
		{
			parent::__construct();
		}

		function widget( $args, $instance )
		{
			$atts = $this->_normalize( $instance );
			
			exc_load_template( $this->view, array('args' => $args, 'instance' => $instance ) );
		}

		private function _normalize( $atts = array() )
		{
			return shortcode_atts(
				array(
					'title' => ''
					
				), $atts, 'exc_post_meta_data_widget');
		}
		
		protected function _settings()
		{
			$this->widget_settings =
				array(
					'id_base'	=> 'exc_post_meta_data_widget',
					'name'		=> esc_html__( 'Post Meta Data', 'exc-uploader-theme' ),
					
					'widget_options' =>
						array(
							'description' => __('Display Post Meta Data information.', 'exc-uploader-theme')
						),
						
					'control_options' => array()
				);
		}
	}
}