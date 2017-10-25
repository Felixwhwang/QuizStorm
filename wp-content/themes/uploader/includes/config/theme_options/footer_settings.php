<?php defined('ABSPATH') OR die('restricted access');

$options = array(
				'title'		=> esc_html__('Footer Settings', 'exc-uploader-theme'),
				'db_name'	=> 'mf_footer_settings',
			);

$options['_config']['_settings'] =
						array(
							'settings' =>
								array(
									'heading'		=> esc_html__('Footer General Settings', 'exc-uploader-theme'),
									'description'	=> __('<p><i>In this section you can change or update footer area settings.</i></p>.', 'exc-uploader-theme'),
								),
							'footer_style' =>
								array(
									'heading'		=> esc_html__('Style Settings', 'exc-uploader-theme'),
									'description'	=> __('This section is related to footer style settings. <p><i>strong>Note: </strong>All the blanked fields will be automatically replaced with stylesheets value.</i></p>', 'exc-uploader-theme'),
								),
					);


// sticky footer enable or disable
$options['_config']['settings']['is_sticky'] = array(
								'label'		=> esc_html__('Sticky Footer', 'exc-uploader-theme'),
								'type'		=> 'switch',
								'attrs'		=> array(
													'class' => 'form-control'
												),

								'default'	=> '',
								'help'		=> esc_html__('Sticky Footer on the page.', 'exc-uploader-theme')
							);

// change the copy right content
$options['_config']['settings']['copyright'] = array(
								'label'		=> esc_html__('Copyright Text', 'exc-uploader-theme'),
								'type'		=> 'textarea',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'stripcslashes',
								'default'	=> 'Copyright ' . date('Y') . ' All rights reserved - Designed By Themebazaar',
								'help'		=> esc_html__('Enter the copyright text to display on footer bar.', 'exc-uploader-theme')
							);

// footer style settings
$options['_config']['footer_style']['bg_color'] = array(
								'label'			=> esc_html__('Background Color', 'exc-uploader-theme'),
								'type'			=> 'colorpicker',
								'attrs'			=> array(
														'class' => 'form-control'
													),
								
								'style_opt_key' => '',
								'css_selector'	=> '.footer',
								'prop_name'		=> 'background-color',

								'validation'	=> '',
								'default'		=> '#1b2126',
								'help'			=> esc_html__('Change the footer background color (default : #1b2126).', 'exc-uploader-theme')
							);

// footer right background color
$options['_config']['footer_style']['r_bg_color'] = array(
								'label'			=> esc_html__('Right Area Background Color', 'exc-uploader-theme'),
								'type'			=> 'colorpicker',
								'attrs'			=> array(
														'class' => 'form-control'
													),
								
								'style_opt_key' => '',
								'css_selector'	=> '.footer-menu-right',
								'prop_name'		=> 'background-color',

								'validation'	=> '',
								'default'		=> '#000000',
								'help'			=> esc_html__('Change the footer right side background color (default: #000000).', 'exc-uploader-theme')
							);

$options['_config']['footer_style']['text_color'] = array(
								'label'			=> esc_html__('Text Color', 'exc-uploader-theme'),
								'type'			=> 'colorpicker',
								'attrs'			=> array(
														'class' => 'form-control'
													),
								
								'style_opt_key' => '',
								'css_selector'	=> '.copyright',
								'prop_name'		=> 'color',

								'validation'	=> '',
								'default'		=> '#ffffff',
								'help'			=> esc_html__('Change the footer text color (default: #ffffff).', 'exc-uploader-theme')
							);

// footer menu color
$options['_config']['footer_style']['menu_text_color'] = array(
								'label'			=> esc_html__('Menu Text Color', 'exc-uploader-theme'),
								'type'			=> 'colorpicker',
								'attrs'			=> array(
														'class' => 'form-control'
													),
								
								'style_opt_key' => '',
								'css_selector'	=> '.footer-menu li a',
								'prop_name'		=> 'color',

								'validation'	=> '',
								'default'		=> '#ffffff',
								'help'			=> esc_html__('Change the footer menu text color (default: #ffffff).', 'exc-uploader-theme')
							);