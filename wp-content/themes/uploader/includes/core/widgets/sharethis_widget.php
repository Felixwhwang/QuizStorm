<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_ShareThis_Widget' ) )
{
	class eXc_ShareThis_Widget extends eXc_Widgets
	{
		protected $config = 'widgets/sharethis';
		protected $view = 'widget_templates/sharethis';
		
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
					'title'		=> ''
				), $atts, 'exc_sharethis_widget');
		}
		
		protected function _settings()
		{
			$this->widget_settings =
				array(
					'id_base'	=> 'exc_sharethis_widget',
					'name'		=> esc_html__( 'Social Media Sharing Buttons', 'exc-uploader-theme' ),
					
					'widget_options' =>
						array(
							'description' => __('Display the social media sharing buttons.', 'exc-uploader-theme')
						),
						
					'control_options' => array()
				);
		}
	}
}