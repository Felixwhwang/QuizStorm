<?php defined('ABSPATH') OR exit('restricted access');

class eXc_Radio_Widget extends eXc_Widgets
{
	protected $config = 'widgets/radio';
	protected $view = 'widgets/radio';

	function __construct()
	{
		parent::__construct();

		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_files' ), 1 );
	}

	function enqueue_files()
	{
		$this->eXc->radio->enqueue_files( true );
	}

	function widget( $args, $instance )
	{
		// Normalize Widget Instance

		$instance = shortcode_atts(
					array(
						'title'			=> '',
						'post_limit'	=> 10,
						'post_type'		=> 'exc_radio_post'
					),

					$instance, 'exc_radio_widget'
			);

		query_posts(
			array(
				'post_type'			=> $instance['post_type'],
				'posts_per_page'	=> $instance['post_limit']
			)
		);

		$this->eXc->load_view( $this->view, array('args' => $args, 'instance' => $instance ) );

		wp_reset_query();
	}
	
	function _settings()
	{
		$this->widget_settings =
			array(
				'id_base'	=> 'exc_radio_widget',
				'name'		=> __( 'Radio Widget', 'exc-uploader-theme' ),
				
				'widget_options' =>
					array(
						'description' => __( 'Uploader theme radio widget', 'exc-uploader-theme' )
					),
					
				'control_options' => array()
			);
	}
}