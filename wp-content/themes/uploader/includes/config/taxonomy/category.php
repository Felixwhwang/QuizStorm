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

$options['_config']['wpblock']['layout']['exc_layout_image'] = array(
								'label' => __('Category Image', 'exc-uploader-theme'),
								'type'	=> 'image',
								'attrs'	=> array('class' => 'form-control'),
								'validation' => 'valid_url',
								'help' => __('Upload or Enter a url of category image, the preferable size is ( 150 x 150 ).', 'exc-uploader-theme')
							);

$options['_config']['wpblock']['layout']['exc_layout_icon_class'] = array(
								'label' 		=> __('Icon Class', 'exc-uploader-theme'),
								'attrs'			=> array('class' => 'form-control'),
								'type'			=> 'text',							
								'validation' 	=> 'alpha_spaces',
								'help' 			=> __('Enter the font-awesome or CSS icon class.', 'exc-uploader-theme')
							);

$options['_config']['wpblock']['layout']['exc_layout_header'] = array(
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

$options['_config']['wpblock']['layout']['exc_layout_slider'] = array(
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

$options['_config']['wpblock']['layout']['exc_layout_revslider_id'] = array(
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

$options['_config']['wpblock']['layout']['exc_layout_structure'] = array(

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
								'help'			=> __( 'Select a layout structure.', 'exc-uploader-theme' ),
							);


$options['_config']['wpblock']['layout']['exc_layout_left_sidebar'] = array(
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

$options['_config']['wpblock']['layout']['exc_layout_right_sidebar'] = array(
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


$options['_config']['wpblock']['layout']['exc_layout_post_type'] = array(
								'label'			=> __('Show Posts', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control', 'multiple' => 'multiple' ),
								'options'		=> array(
														'post'				=> __('Wordpress Blog Posts', 'exc-uploader-theme'),
														'exc_audio_post'	=> __('Extracoding Uploader Audio Posts', 'exc-uploader-theme'),
														'exc_video_post'	=> __('Extracoding Uploader Video Posts', 'exc-uploader-theme'),
														'exc_image_post'	=> __('Extracoding Uploader Images Posts', 'exc-uploader-theme'),
													),
													
								'selected'		=> array( 'post', 'exc_audio_post', 'exc_video_post', 'exc_image_post' ),
								'validation'	=> 'required',
								'help' 			=> __('Show posts from selected sections.', 'exc-uploader-theme'),
							);

$options['_config']['wpblock']['layout']['exc_layout_show_filtration'] = array(
								'label'			=> __('Filtration', 'exc-uploader-theme'),
								'type'			=> 'switch',
//								'attrs'			=> array( 'class' => 'form-control' ),
								'default'		=> 'on',
								'help' 			=> __('Show or hide filtration on this page.', 'exc-uploader-theme'),
							);

$options['_config']['wpblock']['layout']['exc_layout_active_view'] = array(
								'label'		=> __('Default View', 'exc-uploader-theme'),
								'type'		=> 'select',
								//'attrs'		=> array( 'class' => 'form-control' ),
								
								'options' 	=> array(
												'grid' => __('Grid View', 'exc-uploader-theme'),
												'list' => __('List View', 'exc-uploader-theme'),
											),
								
								//'skin'		=> 'form_field',
								'help' 		=> __('Select the default posts view.', 'exc-uploader-theme'),
							);

$options['_config']['wpblock']['layout']['exc_layout_columns'] = array(
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
								'help' 			=> __('Select the number of columns for grid view.', 'exc-uploader-theme'),
							);

$options['_config']['wpblock']['layout']['exc_layout_list_columns'] = array(
								'label'			=> __('List View Columns', 'exc-uploader-theme'),
								'type'			=> 'select',
								//'attrs'			=> array( 'class' => 'form-control' ),
								'options'		=> array
													(
														1 => __( '1 Columns', 'exc-uploader-theme' ),
														2 => __( '2 Columns', 'exc-uploader-theme' ),
													),
													
								'selected'		=> 2,
								'validation'	=> 'required',
								'help' 			=> __('Select the number of columns for list view.', 'exc-uploader-theme'),
							);

if ( get_current_screen()->taxonomy == 'category' )
{
	$options['_config']['wpblock']['layout']['exc_layout_bg_color'] = array(
								'label' 		=> __('Background Color', 'exc-uploader-theme'),
								'type'			=> 'colorpicker',
								'attrs' 		=> array('class'=>'form-control'),
								'validation' 	=> '',
								'help' 			=> __('Select a background color for browse categories page.', 'exc-uploader-theme')
							);
}