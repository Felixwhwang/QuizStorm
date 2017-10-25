<?php defined('ABSPATH') OR die('restricted access');

$options = array(
				'_layout' => 'widget',
			);

$options['_config']['author'] = array(
								'label'		=> __('Author ID', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'widefat',
												),

								'validation'=> 'is_natural_no_zero',
								'skin'		=> 'wp_widget',
								'help'		=> __('Enter the post author id, if left blank it will automatically get the current post author.', 'exc-uploader-theme'),
							);