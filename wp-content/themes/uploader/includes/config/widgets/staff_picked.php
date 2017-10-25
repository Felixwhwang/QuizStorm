<?php defined('ABSPATH') OR die('restricted access');

//SUB, DYNAMIC
$options = array(
				'_layout' => 'widget',
			);
			

$options['_config']['title'] = array(
								'label' => __('Title', 'exc-uploader-theme'),
								'type'	=> 'text',
								'attrs' => array(
											'class' => 'widefat',
									),
									
								'default' => _x('Staff Picked', 'extracoding staff picked widget', 'exc-uploader-theme'),
								'validation' => 'required',
								'skin' => 'wp_widget',
								'help' => __('Enable or Disable responsive functionality.', 'exc-uploader-theme'),
							);

$options['_config']['post_id'] = array(
								'label' => __('Post IDs', 'exc-uploader-theme'),
								'type'	=> 'textarea',
								'attrs' => array(
											'class' => 'form-control',
									),
								'validation' => 'required',
								'skin' => 'wp_widget',
								'help' => __('Enter the comma seperated post IDs e.g 45, 98, 72.', 'exc-uploader-theme'),
							);