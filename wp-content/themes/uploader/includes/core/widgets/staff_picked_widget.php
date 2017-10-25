<?php defined('ABSPATH') OR die('restricted access');

class eXc_Staff_Picked_Widget extends eXc_Widgets
{
	protected $config = 'widgets/staff_picked';
	protected $view = 'widgets/staff_picked';
	
	function widget( $args, $instance )
	{
		if ( ! $atts = $this->_normalize( $instance ) )
		{
			echo '<div class="alert alert-danger" role="alert">' . __( 'The post ID required for staff picked widget.', 'exc-uploader-theme' ) . '</div>';
			return;
		}
		
		$query = new WP_Query( $atts );
		
		$this->eXc->load_view( $this->view, array('args' => $args, 'instance' => $instance, 'query' => $query ) );
		
		wp_reset_query();
	}

	private function _normalize($atts = array())
	{
		if( empty( $atts['post_id'] ) )
		{
			return;
		}
		
		$ids = explode( ', ', $atts['post_id'] );
		
		return array(
					'post__in' => $ids,
					'ignore_sticky_posts' => true,
					'post_type' => array('post', 'exc_audio_post', 'exc_video_post', 'exc_image_post'),
					'nopaging' => true,
					//'order_by'	=> 'post__in',
					//'order'	=> 'ASC'
				);
	}
	
	protected function _settings()
	{
		$this->widget_settings =
			array(
				'id_base'			=> 'exc_staff_picked_widget',
				'name'				=> __( 'Staff Picked Widget', 'exc-uploader-theme' ),
				
				'widget_options'	=>	array(
											'description' => __('A widget to show staff picked posts.', 'exc-uploader-theme')
										),
				'control_options'	=> array()
			);
	}
}