<?php defined('ABSPATH') OR die('restricted access');

$options = array(
				'title'		=> esc_html__('General Settings', 'exc-uploader-theme'),
				'db_name'	=> 'mf_general_settings',
			);

$options['_config']['_settings'] =
						array(
							'layout_settings' =>
								array(
									'heading'		=> esc_html__('Change Website Layout', 'exc-uploader-theme'),
									'description'	=> esc_html__('Switch the default website layout between full-width and boxed-width.', 'exc-uploader-theme'),
								),
							'single_page' =>
								array(
									'heading'		=> esc_html__('Detail Page Settings', 'exc-uploader-theme'),
									'description'	=> esc_html__('The following is related to posts detail page settings.', 'exc-uploader-theme'),
								),
						);


$options['_config']['layout_settings']['structure'] = array(
								'label'		=> __('Layout', 'exc-uploader-theme'),
								'type'		=> 'clickable',
								'attrs'		=> array(
													'data-max_limit' => '1',
												),

								'options'	=> array(
												'full-width' =>
													array(
														'data-id'				=> 'full-width',
														'data-linked_fields'	=> 'auto-grid-adjustment',
														'class'					=> 'btn exc-clickable full-width',
													),

												'boxed-layout' =>
													array(
														'data-id'				=> 'boxed-layout',
														'data-linked_fields'	=> 'background_pattern',
														'class'					=> 'btn exc-clickable boxed-layout'
													)
												),
								//'el_columns'	=> '7',
								'validation'	=> 'required',
								'option_skin'	=> 'with_css',
								'default'		=> 'full-width',
								'help'			=> __('Change the website structure', 'exc-uploader-theme')
							);

$options['_config']['layout_settings']['auto_grid_adjustment'] = array(
								'label'		=> esc_html__('Auto Grid Adjustment', 'exc-uploader-theme'),
								'type'		=> 'switch',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'auto-grid-adjustment'
												),

								'default'	=> 'on',
								'help'		=> esc_html__('Automatically adjust the grid on large screens.', 'exc-uploader-theme')
							);

$options['_config']['layout_settings']['bg_pattern'] = array(
								'label'			=> esc_html__('Background Pattern', 'exc-uploader-theme'),
								'type'			=> 'image',
								'attrs'			=> array(
														'class' => 'form-control',
														'id'	=> 'background_pattern'
													),
								
								'style_opt_key' => '',
								'css_selector'	=> '.boxed-layout',
								'prop_name'		=> 'background-image: url( %s )',

								'validation'	=> 'valid_url',
								'default'		=> '',
								'help'			=> esc_html__('Upload image to use as background pattern.', 'exc-uploader-theme')
							);

$options['_config']['single_page']['featured_image'] = array(
								'label'		=> esc_html__('Featured Image', 'exc-uploader-theme'),
								'type'		=> 'switch',
								'attrs'		=> array(
													'class' => 'form-control'
												),

								'default'	=> 'on',
								'help'		=> esc_html__('Display featured image at the top of post content, This option will work only on articles and images posts.', 'exc-uploader-theme')
							);