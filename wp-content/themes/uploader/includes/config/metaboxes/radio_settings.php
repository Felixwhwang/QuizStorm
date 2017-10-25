<?php defined('ABSPATH') OR die('restricted access');

//SUB, DYNAMIC
$options = array(
				'_layout' => 'metabox',
				'db_name' => 'mf_radio',
			);

$options['_config']['_settings'] =
							array(
								'layout' => array(
									'heading'		=> __('Page Settings', 'exc-uploader-theme'),
									//'description'	=> __('Upload or Select the list of audio files you want to play in this radio station. <br /><br /><i><strong>Important Note:</strong> you can also use default wordpress uploader from the above section so in case of empty following list system will automatically load all audio files which are uploaded to this station.</i>', 'exc-uploader-theme' ),
									'description'	=> __('This section is related to station settings, here you can manage the list of audio\'s you want to play along with page layout settings.', 'exc-uploader-theme' ),
									'show_controls' => false,
								),
							);

$options['_config']['layout']['tabs']['manage_playlist']['playlist'] = array(
								'label'		=> __('Select Audios', 'exc-uploader-theme'),
								'type'		=> 'media_uploader',
								'attrs'		=> array( 'class' => 'form-control' ),
								'data'		=> array(
													'mime-types'	=> 'audio',
													'upload-limit'	=> 100,
												),
								'el_columns'=> '8',
								//'help' 		=> __('Upload or Select the list of audio files you want to play in this radio station.', 'exc-uploader-theme'),
							);

/*$options['_config']['layout']['tabs']['layout_settings']['header'] = array(
								'label'		=> __('Header', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array( 'class' => 'form-control' ),
								
								'options' 	=> array(
												'' 			=> __('Header Default', 'exc-uploader-theme'),
												'center' 	=> __('Header with logo in Center', 'exc-uploader-theme'),
												'classic' 	=> __('Header Classic', 'exc-uploader-theme')
												),
								
								//'skin'		=> 'form_field',
								'help' 		=> __('Select the header for this page.', 'exc-uploader-theme'),
							);

$options['_config']['layout']['tabs']['layout_settings']['slider'] = array(
								'label'		=> __( 'Slider', 'exc-uploader-theme' ),
								'type'		=> 'clickable',

								'attrs'		=> array(
													'data-max_limit' => '1',
												),

								'options'	=> array(
												'no-slider'			=>	array(
																			'label'					=> __('No Slider', 'exc-uploader-theme'),
																			'data-id'				=> ''
																		),

												'uploader' 			=>	array(
																			'label'					=> __('Uploader', 'exc-uploader-theme'),
																			'data-id'				=> 'with_uploader'
																		),

												'revslider' 		=> array(
																			'label'					=> __('Revolution Slider', 'exc-uploader-theme'),
																			'data-id'				=> 'revslider',
																			'data-linked_fields'	=> 'header-center-revslider-id'
																		)
												),

								'option_skin'	=> 'withalpha',
								'help'			=> __( 'Select a slider for this page.', 'exc-uploader-theme' )
							);

$options['_config']['layout']['tabs']['layout_settings']['revslider_id'] = array(
								'label'			=> __( 'Revolution Slider id', 'exc-uploader-theme' ),
								'type'			=> 'text',
								'attrs'			=> array(
														'class' => 'form-control',
														'id'	=> 'header-center-revslider-id'
													),

								'validation'	=> '',
								'help'			=> __( 'Enter the revolution slider post id.', 'exc-uploader-theme' )
							);

$options['_config']['layout']['tabs']['layout_settings']['structure'] = array(

								'label'		=> __('Layout', 'exc-uploader-theme'),
								'type'		=> 'clickable',
								'attrs'		=> array(
													'data-max_limit' => '1',
												),

								'options'	=> array(
												'full-width' =>
													array(
														'data-id'				=> 'full-width',
														'class'					=> 'btn exc-clickable layout-full-width'
													),

												'left-sidebar' =>
													array(
														'data-id'				=> 'left-sidebar',
														'data-linked_fields'	=> 'left_sidebar',
														'class'					=> 'btn exc-clickable layout-left-sidebar'
													),

												'right-sidebar'	=>
													array(
														'data-id'				=> 'right-sidebar',
														'data-linked_fields'	=> 'right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-sidebar'
													),

												'two-sidebars'	=>
													array(
														'data-id'				=> 'two-sidebars',
														'data-linked_fields'	=> 'left_sidebar, right_sidebar',
														'class'					=> 'btn exc-clickable layout-three-column'
													),

												'left-two-sidebars'	=>
													array(
														'data-id'				=> 'left-two-sidebars',
														'data-linked_fields'	=> 'left_sidebar, right_sidebar',
														'class'					=> 'btn exc-clickable layout-left-twosidebar'
													),
												'right-two-sidebars'			=>
													array(
														'data-id'				=> 'right-two-sidebars',
														'data-linked_fields'	=> 'left_sidebar, right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-twosidebar'
													),
												),
								'el_columns'	=> '7',
								'validation'	=> 'required',
								'option_skin'	=> 'with_css',
								'help'			=> __( 'Select the layout structure.', 'exc-uploader-theme' ),
							);


$options['_config']['layout']['tabs']['layout_settings']['left_sidebar'] = array(
								'label'		=> __('Left Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'left_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as left sidebar, you can add more sidebars in theme options.', 'exc-uploader-theme'),
							);

$options['_config']['layout']['tabs']['layout_settings']['right_sidebar'] = array(
								'label'		=> __('Right Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'right_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show in right sidebar, you can add more sidebars in theme options.', 'exc-uploader-theme'),
							);*/

$options['_config']['layout']['tabs']['style_settings']['icon_class'] = array(
								'label' 		=> __('Icon Class', 'exc-uploader-theme'),
								'attrs'			=> array('class' => 'form-control'),
								'type'			=> 'text',							
								'validation' 	=> 'alpha_spaces',
								'help' 			=> __('Enter the font-awesome or CSS icon class.', 'exc-uploader-theme')
							);

$options['_config']['layout']['tabs']['style_settings']['bg_color'] = array(
								'label' 		=> __('Background Color', 'exc-uploader-theme'),
								'type'			=> 'colorpicker',
								'attrs' 		=> array('class'=>'form-control'),
								'validation' 	=> '',
								'default' 		=> '#ccc999',
								'help' 			=> __('Select the item background color, default is #ccc999.', 'exc-uploader-theme')
							);