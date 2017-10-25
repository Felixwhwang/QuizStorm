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

								'default' => '',
								'validation' => 'required',
								'skin'		=> 'wp_widget',
								'help' => __('Enter the title of the widget.', 'exc-uploader-theme'),
							);
							
$options['_config']['thumb'] = array(
								'label' => __('Thumbnail', 'exc-uploader-theme'),
								'type'	=> 'image',
								'attrs' => array(
											'class' => 'widefat',
									),
								
								'validation' => 'required|valid_url',
								'skin'		=> 'widget_image_upload',
								'help' => __('The thumbnail of POST.', 'exc-uploader-theme'),
							);

$options['_config']['post_content'] = array(
								'label' => __('Description', 'exc-uploader-theme'),
								'type'	=> 'textarea',
								'attrs' => array(
											'class' => 'widefat',
											'data-size' => 'medium',
									),
								'validation' => 'required',
								'skin'		=> 'wp_widget',
								'help' => __('All users will be forced to login from frontend only.', 'exc-uploader-theme'),
							);

$options['_config']['tags_input'] = array(
								'label' => __('Tags', 'exc-uploader-theme'),
								'type'	=> 'textarea',
								'attrs' => array(
											'class' => 'widefat',
											'data-size' => 'medium',
									),
								'skin'		=> 'wp_widget',
								'help' => __('All users will be forced to login from frontend only.', 'exc-uploader-theme'),
							);

$options['_config']['license'] = array(
								'label' => __('License', 'exc-uploader-theme'),
								'type'	=> 'text',
								'attrs' => array(
											'class' => 'widefat',
									),
								'skin'		=> 'wp_widget',
								'help' => __('All users will be forced to login from frontend only.', 'exc-uploader-theme'),
							);

$options['_config']['avatar'] = array(
								'label' => __('Avatar', 'exc-uploader-theme'),
								'type'	=> 'image',
								'attrs' => array(
											'class' => 'widefat',
											'data-size' => 'medium',
									),
									
								'skin'		=> 'wp_widget',
								'help' => __('All users will be forced to login from frontend only.', 'exc-uploader-theme'),
							);
														
$options['_config']['name'] = array(
								'label' => __('name', 'exc-uploader-theme'),
								'type'	=> 'text',
								'attrs' => array(
											'class' => 'widefat',
											'disabled' => true,
									),
								'skin'		=> 'wp_widget',
								'help' => __('All users will be forced to login from frontend only.', 'exc-uploader-theme'),
							);