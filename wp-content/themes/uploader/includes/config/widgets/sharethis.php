<?php defined('ABSPATH') OR die('restricted access');

$options = array(
				'_layout' => 'widget',
			);

$options['_config']['title'] = array(
								'label'		=> __('Title', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'widefat',
												),
									
								'default'	=> _x('Recent Posts', 'Extracoding Advanced Posts Widget', 'exc-uploader-theme'),
								'validation'=> 'required',
								'skin'		=> 'wp_widget',
								'help'		=> __('Enter a title for this widget.', 'exc-uploader-theme'),
							);