<?php defined('ABSPATH') OR die('restricted access');

//SUB, DYNAMIC
$options = array(
				'_layout' => 'widget',
			);
			

$options['_config']['title'] = array(
								'label'		=> __('Title', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
											'class' => 'widefat',
									),
									
								'default'	=> _x('Top Users', 'extracoding users widget', 'exc-uploader-theme'),
								'validation'=> 'required',
								'skin'		=> 'wp_widget',
								'help'		=> __('Enter the title of widget.', 'exc-uploader-theme'),
							);

$options['_config']['hide_empty'] = array(
								'label'		=> __('Hide Empty', 'exc-uploader-theme'),
								'type'		=> 'switch',
								'attrs'		=> array(
												'class'			=> 'widefat',
												'data-size'		=> 'small',
												),
									
								'default'	=> 'on',
								'skin'		=> 'wp_widget',
								'wrap'		=> '<br />{markup}',
								'help'		=> __('Skip the users with no posts.', 'exc-uploader-theme'),
							);

$options['_config']['role'] = array(
								'label' 		=> __('User Roles', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array(
													'class'		=> 'widefat',
													),

								'options'		=> array_merge( array('' => __('All Users', 'exc-uploader-theme') ) , (array) exc_get_roles() ),
								'selected'		=> 'contributor',
								'validation'	=> '',
								'skin'			=> 'wp_widget',
								'help'			=> __('Show users of selected roles.', 'exc-uploader-theme'),
							);

$options['_config']['number'] = array(
								'label' => __('Number of Users', 'exc-uploader-theme'),
								'type'	=> 'text',
								'attrs' => array(
											'class' => 'widefat',
									),
								'default' => '6',
								'validation' => 'required',
								'skin' => 'wp_widget',
								'help' => __('Enter the number of users you want to display by default.', 'exc-uploader-theme'),
							);

$options['_config']['exclude'] = array(
								'label' 		=> __('Exclude users', 'exc-uploader-theme'),
								'type'			=> 'text',
								'attrs' 		=> array(
													'class' => 'widefat',
													),
								'default'		=> '1',
								'validation' 	=> '',
								'skin' 			=> 'wp_widget',
								'help' 			=> __('Enter the comma separated user id\'s e.g 2, 3.', 'exc-uploader-theme'),
							);

$options['_config']['pagination'] = array(
								'label' 		=> __('Pagination', 'exc-uploader-theme'),
								'type'			=> 'switch',
								'attrs' 		=> array(
														'class'			=> 'widefat',
														'data-size'		=> 'small',
													),
									
								'default'		=> 'on',
								'skin'			=> 'wp_widget',
								'wrap'			=> '<br />{markup}',
								'help'			=> __('Load more button at the end of widget.', 'exc-uploader-theme'),
							);

/*
$options['_config']['post_type'] = array(
								'label'			=> __('Post Types', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control', 'multiple' => 'multiple' ),
								'skin'			=> 'wp_widget',
								'options'		=> array(
														'post'				=> __('Wordpress Blog Posts', 'exc-uploader-theme'),
														'exc_audio_post'	=> __('Extracoding Uploader Audio Posts', 'exc-uploader-theme'),
														'exc_video_post'	=> __('Extracoding Uploader Video Posts', 'exc-uploader-theme'),
														'exc_image_post'	=> __('Extracoding Uploader Images Posts', 'exc-uploader-theme'),
													),
													
								'selected'		=> array( 'post', 'exc_audio_post', 'exc_video_post', 'exc_image_post' ),
								'validation'	=> 'required',
								'help' 			=> __('Show posts from selected sections.', 'exc-uploader-theme'),
							);*/
							
$options['_config']['post_limit'] = array(
								'label' => __('Minimum Posts', 'exc-uploader-theme'),
								'type'	=> 'text',
								'attrs' => array(
											'class' => 'widefat',
									),
								'default' => '0',
								'validation' => 'required|is_natural',
								'skin' => 'wp_widget',
								'help' => __('The minimum posts a user must have.', 'exc-uploader-theme'),
							);

$options['_config']['orderby'] = array(
								'label' => __('Order By', 'exc-uploader-theme'),
								'type'	=> 'select',
								'attrs' => array(
											'class' => 'widefat',
									),
								
								'options' => array(
										'ID' => _x('ID', 'extracoding users widget', 'exc-uploader-theme'),
										'login' => _x('Login', 'extracoding users widget', 'exc-uploader-theme'),
										'nicename' => _x('Nice name', 'extracoding users widget', 'exc-uploader-theme'),
										'email' => _x('Email','extracodng users widget', 'exc-uploader-theme'),
										'url' => _x('URL', 'extracoding users widget', 'exc-uploader-theme'),
										'registered' => _x('Registered', 'extracoding users widget', 'exc-uploader-theme'),
										'display_name' => _x('Display Name', 'extracoding users widget', 'exc-uploader-theme'),
										'post_count' => _x('Post Count', 'extracoding users widget', 'exc-uploader-theme')
									),
									
								'selected' => 'post_count',
								'validation' => 'required',
								'skin' => 'wp_widget',
								'help' => __('Enable or Disable responsive functionality.', 'exc-uploader-theme'),
							);

$options['_config']['order'] = array(
								'label' => __('Order', 'exc-uploader-theme'),
								'type'	=> 'select',
								'attrs' => array(
											'class' => 'widefat',
									),
								
								'options' => array(
										'ASC' => _x('Ascending', 'extracoding users widget', 'exc-uploader-theme'),
										'DESC' => _x('Descending', 'extracoding users widget', 'exc-uploader-theme'),
									),
									
								'selected' => 'DESC',
								'validation' => 'required',
								'skin' => 'wp_widget',
								'help' => __('Ascending or Descending.', 'exc-uploader-theme'),
							);