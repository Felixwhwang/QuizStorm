<?php defined('ABSPATH') OR die('restricted access');

//SUB, DYNAMIC
$options = array(
				'_layout' => 'wordpress',
				'db_name' => 'mf_layout',
			);

$options['_config']['wpblock']['_settings'] =
							array(
								'layout' => array(
									'heading'		=> __('Layout Settings', 'exc-uploader-theme'),
									'description'	=> __( 'Change the default layout structure.', 'exc-uploader-theme' ),
									'show_controls' => false,
								),
								
							);

$options['_config']['wpblock']['layout']['image'] = array(
								'label' => __('Genre Image', 'exc-uploader-theme'),
								'attrs'	=> array('class'=>'form-control'),
								'type'	=> 'image',							
								'validation' => 'valid_url',
								'help' => __('Upload or Enter a url of Genre image, the preferable size is ( 150 x 150 ).', 'exc-uploader-theme')
							);

$options['_config']['wpblock']['layout']['header'] = array(
								'label'		=> __('Header', 'exc-uploader-theme'),
								'type'		=> 'select',
//								'attrs'		=> array( 'class' => 'form-control' ),
								
								'options' 	=> array(
												'' 			=> __('Header Default', 'exc-uploader-theme'),
												'center' 	=> __('Header with logo in Center', 'exc-uploader-theme'),
												'classic' 	=> __('Header Classic', 'exc-uploader-theme')
												),
								

								'help' 		=> __('Select a header for this page.', 'exc-uploader-theme'),
							);

$options['_config']['wpblock']['layout']['slider'] = array(
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

$options['_config']['wpblock']['layout']['revslider_id'] = array(
								'label'			=> __( 'Revolution Slider id', 'exc-uploader-theme' ),
								'type'			=> 'select',
								'options'		=> exc_revslider_list(),
								'attrs'			=> array(
														'class' => 'form-control',
														'id'	=> 'header-center-revslider-id'
													),

								'validation'	=> '',
								'help'			=> __( 'Select a revolution slider. <br /><br /><i> <strong>Note: </strong>You can manage this list by adding or removing sliders in revolution slider</i>.', 'exc-uploader-theme' )
							);

$options['_config']['wpblock']['layout']['header_sidebar_status'] = array(
								'label'		=> esc_html__('Header Sidebar', 'exc-uploader-theme'),
								'type'		=> 'clickable',
								'options'	=> array(
													'hide' =>
														array(
															'label'					=> esc_html__('Hide Sidebar', 'exc-uploader-theme'),
															'data-id'				=> ''
														),

													'show' =>
														array(
															'label'					=> esc_html__('Show Sidebar', 'exc-uploader-theme'),
															'data-id'				=> 'show',
															'data-linked_fields'	=> 'header-sidebar'
														),
												),

								'attrs'		=> array(
													'data-max_limit' => 1,
												),
								
								'option_skin'=> 'withalpha',
								'validation'=> '',
								'default'	=> '',
								'help'		=> esc_html__('Show or hide a sidebar in header section.', 'exc-uploader-theme')
							);

$options['_config']['wpblock']['layout']['header_sidebar'] = array(
								'label'		=> __('Header Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'header-sidebar'
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
//								'skin'		=> 'form_field',
								'help'		=> __('Select a sidebar to show as header sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['wpblock']['layout']['structure'] = array(

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

								'validation'	=> 'required',
								'option_skin'	=> 'with_css',
//								'parent_skin'	=> 'form_field',
								'help'			=> __( 'Select the layout structure.', 'exc-uploader-theme' ),
							);


$options['_config']['wpblock']['layout']['left_sidebar'] = array(
								'label'		=> __('Left Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
//													'class' => 'form-control',
													'id'	=> 'left_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
//								'skin'		=> 'form_field',
								'help'		=> __('Select a sidebar to show as left sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['wpblock']['layout']['right_sidebar'] = array(
								'label'		=> __('Right Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
//													'class' => 'form-control',
													'id'	=> 'right_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
//								'skin'		=> 'form_field',
								'help'		=> __('Select a sidebar to show as right sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['wpblock']['layout']['columns'] = array(
								'label'			=> __('Grid View Columns', 'exc-uploader-theme'),
								'type'			=> 'select',
//								'attrs'			=> array( 'class' => 'form-control' ),
								'options'		=> array
													(
														2 => __( '2 Columns', 'exc-uploader-theme' ),
														3 => __( '3 Columns', 'exc-uploader-theme' ),
														4 => __( '4 Columns', 'exc-uploader-theme' )
													),
													
								'selected'		=> 2,
								'validation'	=> 'required',
								'help' 			=> __('Change the number of columns.', 'exc-uploader-theme'),
							);