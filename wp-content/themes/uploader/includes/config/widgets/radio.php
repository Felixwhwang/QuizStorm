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
									
								'default' => _x('Radio', 'extracoding radio widget', 'exc-uploader-theme'),
								'validation' => '',
								'skin' => 'wp_widget',
								'help' => __('Enter a title for this widget.', 'exc-uploader-theme'),
							);

$options['_config']['post_limit'] = array(
								'label' => __('Number of Stations', 'exc-uploader-theme'),
								'type'	=> 'text',
								'attrs' => array(
											'class' => 'widefat',
									),
								'default' => '10',
								'validation' => 'required|is_natural_no_zero',
								'skin' => 'wp_widget',
								'help' => __('Limit the number of radio channel.', 'exc-uploader-theme'),
							);