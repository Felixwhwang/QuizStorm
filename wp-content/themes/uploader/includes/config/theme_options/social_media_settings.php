<?php defined('ABSPATH') OR die('restricted access');

$options = array(
				'title'		=> esc_html__('Social Media Settings', 'exc-uploader-theme'),
				'db_name'	=> 'mf_social_media',
			);

$options['_config']['_settings'] =
						array(
							'social' =>
								array(
									'heading'		=> esc_html__('Social Media', 'exc-uploader-theme'),
									'description'	=> esc_html__('This section is related to social media and their profile links.', 'exc-uploader-theme'),
								),
						);

// facebook
$options['_config']['social']['facebook'] = array(
								'label'		=> esc_html__('Facebook', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'valid_url',
								'default'	=> 'http://facebook.com/extracoding',
								'help'		=> esc_html__('Enter your Facebook profile URL.', 'exc-uploader-theme')
							);

// twitter
$options['_config']['social']['twitter'] = array(
								'label'		=> esc_html__('Twitter', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'valid_url',
								'default'	=> 'http://twitter.com/extracoding',
								'help'		=> esc_html__('Enter your Twitter profile URL.', 'exc-uploader-theme')
							);

// gplus
$options['_config']['social']['gplus'] = array(
								'label'		=> esc_html__('Google Plus', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'valid_url',
								'default'	=> 'http://plus.google.com/getstarted?fww=1',
								'help'		=> esc_html__('Enter your Google Plus profile URL.', 'exc-uploader-theme')
							);

// instagram
$options['_config']['social']['instagram'] = array(
								'label'		=> esc_html__('Instagram', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'valid_url',
								'default'	=> 'http://instagram.com',
								'help'		=> esc_html__('Enter your instagram profile URL.', 'exc-uploader-theme')
							);

// youtube
$options['_config']['social']['youtube'] = array(
								'label'		=> esc_html__('Youtube', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'valid_url',
								'default'	=> 'http://youtube.com',
								'help'		=> esc_html__('Enter your Youtube profile URL.', 'exc-uploader-theme')
							);

// vimeo
$options['_config']['social']['vimeo'] = array(
								'label'		=> esc_html__('Vimeo', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'valid_url',
								'default'	=> 'http://vimeo.com',
								'help'		=> esc_html__('Enter your Vimeo profile URL.', 'exc-uploader-theme')
							);

// soundcloud
$options['_config']['social']['soundcloud'] = array(
								'label'		=> esc_html__('Soundcloud', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'valid_url',
								'default'	=> 'http://soundcloud.com',
								'help'		=> esc_html__('Enter your soundcloud profile URL.', 'exc-uploader-theme')
							);

// flickr
$options['_config']['social']['flickr'] = array(
								'label'		=> esc_html__('Flickr', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'valid_url',
								'default'	=> 'http://www.flickr.com',
								'help'		=> esc_html__('Enter your Flickr profile URL.', 'exc-uploader-theme')
							);