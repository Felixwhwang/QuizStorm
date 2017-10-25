<?php defined('ABSPATH') OR die('restricted access');

$options = array(
				'_layout' => 'members/signup',
				'_capabilities' => ''
			);

$options['_config']['city'] = array(
								'label' => __('City', 'exc-uploader-theme'),
								'type'	=> 'text',
								'attrs' => array(
											'class' => 'form-control',
											'placeholder' => __('City', 'exc-uploader-theme')
									),
								'validation' => '',
								'layout' => 'members/text',
								);

$options['_config']['state'] = array(
								'label' => __('State', 'exc-uploader-theme'),
								'type'	=> 'text',
								'attrs' => array(
											'class' => 'form-control',
											'placeholder' => __('State', 'exc-uploader-theme')
									),
									
								'validation' => 'required',
								'layout' => 'members/text',
								);

$options['_config']['country'] = array(
								'label' => __('Country', 'exc-uploader-theme'),
								'type'	=> 'select2',
								'attrs' => array(
											'class' => 'form-control',
											'placeholder' => __('Country', 'exc-uploader-theme')
									),
									
								'layout' => 'members/country',
								'validation' => 'required',
								);
								
$options['_config']['description'] = array(
								'label' => __('About', 'exc-uploader-theme'),
								'type'	=> 'textarea',
								'attrs' => array(
											'class' => 'form-control',
											'placeholder' => __('About', 'exc-uploader-theme')
									),
									
								'layout' => 'members/country',
								'validation' => 'required',
								);

$options['_config']['social_media']['facebook'] = array(
									'label' => __('Facebook', 'exc-uploader-theme'),
									'type'	=> 'text',
									'attrs' => array(
												'class' => 'form-control',
												//'placeholder' => __('About', 'exc-uploader-theme')
										),
									
									'layout' => 'members/socialmedia',
									);
									
$options['_config']['social_media']['twitter'] = array(
									'label' => __('Twitter', 'exc-uploader-theme'),
									'type'	=> 'text',
									'attrs' => array(
												'class' => 'form-control',
												//'placeholder' => __('About', 'exc-uploader-theme')
										),
									
									'layout' => 'members/socialmedia',
									);
									
$options['_config']['social_media']['google-plus'] = array(
									'label' => __('Goolge+', 'exc-uploader-theme'),
									'type'	=> 'text',
									'attrs' => array(
												'class' => 'form-control',
												//'placeholder' => __('About', 'exc-uploader-theme')
										),
									
									'layout' => 'members/socialmedia',
									);
									
$options['_config']['social_media']['youtube'] = array(
									'label' => __('Youtube', 'exc-uploader-theme'),
									'type'	=> 'text',
									'attrs' => array(
												'class' => 'form-control',
												//'placeholder' => __('About', 'exc-uploader-theme')
										),
									
									'layout' => 'members/socialmedia',
									);
									
$options['_config']['social_media']['vimeo'] = array(
									'label' => __('Vimeo', 'exc-uploader-theme'),
									'type'	=> 'text',
									'attrs' => array(
												'class' => 'form-control',
												//'placeholder' => __('About', 'exc-uploader-theme')
										),
									
									'layout' => 'members/socialmedia',
									);
									
$options['_config']['social_media']['soundcloud'] = array(
									'label' => __('SoundCloud', 'exc-uploader-theme'),
									'type'	=> 'text',
									'attrs' => array(
												'class' => 'form-control',
												//'placeholder' => __('About', 'exc-uploader-theme')
										),
									
									'layout' => 'members/socialmedia',
									);
									
$options['_config']['social_media']['behance'] = array(
									'label' => __('Behance', 'exc-uploader-theme'),
									'type'	=> 'text',
									'attrs' => array(
												'class' => 'form-control',
												//'placeholder' => __('About', 'exc-uploader-theme')
										),
									
									'layout' => 'members/socialmedia',
									);
									
$options['_config']['social_media']['dribbble'] = array(
									'label' => __('Dribbble', 'exc-uploader-theme'),
									'type'	=> 'text',
									'attrs' => array(
												'class' => 'form-control',
												//'placeholder' => __('About', 'exc-uploader-theme')
										),
									
									'layout' => 'members/socialmedia',
									);