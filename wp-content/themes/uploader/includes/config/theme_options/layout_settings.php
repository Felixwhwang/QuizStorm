<?php defined('ABSPATH') OR die('restricted access');

$this->html->load_js_args( 'exc-dynamic-fields', $this->system_url('views/js/fields/dynamic-fields.js'), array('jquery-ui-sortable') );

$options = array(
				'title'		=> esc_html__('Layout Settings', 'exc-uploader-theme'),
				'db_name'	=> 'mf_layout',
			);

$options['_config']['_settings'] =
						array(
							'default_settings' =>
								array(
									'heading'		=> esc_html__('Default Layout Structure', 'exc-uploader-theme'),
									'description'	=> esc_html__('The default layout setting will be used for the pages where their custom settings are missong.', 'exc-uploader-theme'),
								),
							'settings' =>
								array(
									'heading'		=> esc_html__('Wordpress Built-in Template Files Settings', 'exc-uploader-theme'),
									'description'	=> __('In Extracoding uploader theme you can even customize the default layout settings of wordpress buit-in template files.<p><i><strong>Note: </strong> The following settings are default settings of these pages, you can also change each category, tag and post  layout settings while them.</i></p>.', 'exc-uploader-theme'),
								),
							'custom_pages' => 
								array(
									'heading' 		=> esc_html__('Custom Pages Template Settings', 'exc-uploader-theme'),
									'description' 	=> esc_html__('The custom pages template settings are almost same as the above "Wordpress Built-in Template Files Settings" except these are custom created pages with some additional options e.g. Yoursite.com/users.', 'exc-uploader-theme'),
								),

							'pagination' => 
								array(
									'heading' 		=> esc_html__('Pagination Settings', 'exc-uploader-theme'),
									'description' 	=> esc_html__('This section is related to blog and comments pagination settings.', 'exc-uploader-theme'),
								),
						);

// Post Item
$options['_config']['default_settings']['default_header'] = array(
								'label'		=> esc_html__('Header', 'exc-uploader-theme'),
								'type'		=> 'select',
								'options'	=> array(
													'' 			=> __('Default Header', 'exc-uploader-theme'),
													'center' 	=> __('Header with logo in center', 'exc-uploader-theme'),
													'classic' 	=> __('Classic Header', 'exc-uploader-theme')
												),

								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> '',
								'default'	=> '',
								'help'		=> esc_html__('Select a header for default layout structure.', 'exc-uploader-theme')
							);

$options['_config']['default_settings']['default_slider'] = array(
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
																			'data-linked_fields'	=> 'revslider-id'
																		)
												),

								'option_skin'	=> 'withalpha',
								'default'		=> 'with_uploader',
								'help'			=> __( 'Select a slider for default layout structure.', 'exc-uploader-theme' )
							);

$options['_config']['default_settings']['default_revslider_id'] = array(
								'label'			=> __( 'Revolution Slider id', 'exc-uploader-theme' ),
								'type'			=> 'select',
								'options'		=> exc_revslider_list(),
								'attrs'			=> array(
														'class' => 'form-control',
														'id'	=> 'revslider-id'
													),

								'validation'	=> '',
								'help'			=> __( 'Select a revolution slider. <br /><br /><i> <strong>Note: </strong>You can manage this list by adding or removing sliders in revolution slider</i>.', 'exc-uploader-theme' )
							);

$options['_config']['default_settings']['default_header_sidebar_status'] = array(
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
															'data-linked_fields'	=> 'default-header-sidebar'
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

$options['_config']['default_settings']['default_header_sidebar'] = array(
								'label'		=> __('Header Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'default-header-sidebar'
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
//								'skin'		=> 'form_field',
								'help'		=> __('Select a sidebar to show as header sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['default_settings']['default_structure'] = array(
								'label'			=> __('Layout', 'exc-uploader-theme'),
								'type'			=> 'clickable',
								'attrs'			=> array(
														'data-max_limit' => '1',
													),

								'options'		=> array(
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

														'right-two-sidebars' =>
															array(
																'data-id'				=> 'right-two-sidebars',
																'data-linked_fields'	=> 'left_sidebar, right_sidebar',
																'class'					=> 'btn exc-clickable layout-right-twosidebar'
															),
													),

								'el_columns'	=> '7',
								'validation'	=> 'required',
								'option_skin'	=> 'with_css',
								'default'		=> 'right-sidebar',
								'help'			=> sprintf(
														__( 'Select a layout structure for default layout. <br /><br /><i><strong>Note: </strong>You can manage sidebars easily in %s.</i>', 'exc-uploader-theme' ),
														'<a href="' . esc_url( admin_url('themes.php?page=theme-options&section=manage_sidebars') ) . '">Manage Sidebars</a>'
													)														
							);

$options['_config']['default_settings']['default_left_sidebar'] = array(
								'label'		=> __('Left Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'left_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as left sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['default_settings']['default_right_sidebar'] = array(
								'label'		=> __('Right Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'right_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'default'	=> 'home-page-right-sidebar',
								'help'		=> __('Select a sidebar to show as right sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['default_settings']['default_columns'] =array(
								'label'			=> __('Grid View Columns', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control' ),
								'options'		=> array(
														2 => __( '2 Columns', 'exc-uploader-theme' ),
														3 => __( '3 Columns', 'exc-uploader-theme' ),
														4 => __( '4 Columns', 'exc-uploader-theme' )
													),
													
								'selected'		=> 3,
								'validation'	=> 'required',
								'help' 			=> __('Select the number of columns for grid view.', 'exc-uploader-theme'),
							);

$options['_config']['default_settings']['default_list_columns'] =array(
								'label'			=> __('List View Columns', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control' ),
								'options'		=> array(
														2 => __( '2 Columns', 'exc-uploader-theme' ),
														3 => __( '3 Columns', 'exc-uploader-theme' ),
														4 => __( '4 Columns', 'exc-uploader-theme' )
													),
													
								'selected'		=> 3,
								'validation'	=> 'required',
								'help' 			=> __('Select the number of columns for list view.', 'exc-uploader-theme'),
							);

$options['_config']['default_settings']['default_download_btn'] = array(
								'label' => __('Download Button', 'exc-uploader-theme'),
								'type'	=> 'switch',
								'attrs' => array(
											'class' => 'form-control',
									),
								
								'default'	=> 'on',
								'help'		=> __( 'Enable or Disable download audio video files functionality. <br /><br /><i><strong>Note: </strong> This option works with only uploaded files.</i>', 'exc-uploader-theme' ),
							);

//Templates settings
$options['_config']['settings']['tabs']['archives']['archives_header'] = array(
								'label'		=> __('Header', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array( 'class' => 'form-control' ),
								
								'options' 	=> array(
												'' 			=> __('Default Header', 'exc-uploader-theme'),
												'center' 	=> __('Header with logo in center', 'exc-uploader-theme'),
												'classic' 	=> __('Classic Header', 'exc-uploader-theme')
											),
								
								'help' 		=> __('Select a header for archive pages.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['archives']['archives_slider'] = array(
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
																			'data-linked_fields'	=> 'archives-revslider-id'
																		)
												),

								'option_skin'	=> 'withalpha',
								'help'			=> __( 'Select a slider for archive pages.', 'exc-uploader-theme' )
							);

$options['_config']['settings']['tabs']['archives']['archives_revslider_id'] = array(
								'label'			=> __( 'Revolution Slider id', 'exc-uploader-theme' ),
								'type'			=> 'select',
								'options'		=> exc_revslider_list(),
								'attrs'			=> array(
														'class' => 'form-control',
														'id'	=> 'archives-revslider-id'
													),

								'validation'	=> '',
								'help'			=> __( 'Select a revolution slider. <br /><br /><i> <strong>Note: </strong>You can manage this list by adding or removing sliders in revolution slider</i>.', 'exc-uploader-theme' )
							);

$options['_config']['settings']['tabs']['archives']['archives_header_sidebar_status'] = array(
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
															'data-linked_fields'	=> 'archives-header-sidebar'
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

$options['_config']['settings']['tabs']['archives']['archives_header_sidebar'] = array(
								'label'		=> __('Header Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'archives-header-sidebar'
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
//								'skin'		=> 'form_field',
								'help'		=> __('Select a sidebar to show as header sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['archives']['archives_structure'] = array(
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
														'data-linked_fields'	=> 'archives_left_sidebar',
														'class'					=> 'btn exc-clickable layout-left-sidebar'
													),

												'right-sidebar'	=>
													array(
														'data-id'				=> 'right-sidebar',
														'data-linked_fields'	=> 'archives_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-sidebar'
													),

												'two-sidebars'	=>
													array(
														'data-id'				=> 'two-sidebars',
														'data-linked_fields'	=> 'archives_left_sidebar, archives_right_sidebar',
														'class'					=> 'btn exc-clickable layout-three-column'
													),

												'left-two-sidebars'	=>
													array(
														'data-id'				=> 'left-two-sidebars',
														'data-linked_fields'	=> 'archives_left_sidebar, archives_right_sidebar',
														'class'					=> 'btn exc-clickable layout-left-twosidebar'
													),
												'right-two-sidebars'			=>
													array(
														'data-id'				=> 'right-two-sidebars',
														'data-linked_fields'	=> 'archives_left_sidebar, archives_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-twosidebar'
													),
												),
								'el_columns'	=> '7',
								'validation'	=> 'required',
								'option_skin'	=> 'with_css',
								'default'		=> 'full-width',
								'help'			=> sprintf(
														__( 'Select a layout structure for archive pages. <br /><br /><i><strong>Note: </strong>You can manage sidebars easily in %s.</i>', 'exc-uploader-theme' ),
														'<a href="' . admin_url('themes.php?page=theme-options&section=manage_sidebars') . '">Manage Sidebars</a>'
													)														
							);

$options['_config']['settings']['tabs']['archives']['archives_left_sidebar'] = array(
								'label'		=> __('Left Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'archives_left_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as left sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['archives']['archives_right_sidebar'] = array(
								'label'		=> __('Right Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'archives_right_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as right sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['archives']['archives_post_type'] = array(
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
								'help' 			=> __('Show posts from selected sections.', 'exc-uploader-theme'),
							);


$options['_config']['settings']['tabs']['archives']['archives_show_filtration'] = array(
								'label'			=> __('Filtration', 'exc-uploader-theme'),
								'type'			=> 'switch',
								'attrs'			=> array( 'class' => 'form-control' ),
								'default'		=> 'on',
								'help' 			=> __('Show or hide the filtration from archive pages.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['archives']['archives_active_view'] = array(
								'label'		=> __('Default View', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array( 'class' => 'form-control' ),
								
								'options' 	=> array(
												'grid' => __('Grid View', 'exc-uploader-theme'),
												'list' => __('List View', 'exc-uploader-theme'),
											),
								
								'help' 		=> __('Select the default posts view.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['archives']['archives_columns'] =array(
								'label'			=> __('Grid View Columns', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control' ),
								'options'		=> array(
														2 => __( '2 Columns', 'exc-uploader-theme' ),
														3 => __( '3 Columns', 'exc-uploader-theme' ),
														4 => __( '4 Columns', 'exc-uploader-theme' )
													),
													
								'selected'		=> 4,
								'validation'	=> 'required',
								'help' 			=> __('Select the number of columns for grid view.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['archives']['archives_list_columns'] =array(
								'label'			=> __('List View Columns', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control' ),
								'options'		=> array(
														1 => __( '1 Columns', 'exc-uploader-theme' ),
														2 => __( '2 Columns', 'exc-uploader-theme' ),
													),
													
								'selected'		=> 2,
								'validation'	=> 'required',
								'help' 			=> __('Select the number of columns for list view.', 'exc-uploader-theme'),
							);

// Categories Template settings
$options['_config']['settings']['tabs']['categories']['categories_header'] = array(
								'label'		=> __('Header', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array( 'class' => 'form-control' ),
								'options' 	=> array(
												'' 			=> __('Header Default', 'exc-uploader-theme'),
												'center' 	=> __('Header with logo in Center', 'exc-uploader-theme'),
												'classic' 	=> __('Header Classic', 'exc-uploader-theme')
												),
								
								'help' 		=> __('Select a header for categories pages.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['categories']['categories_slider'] = array(
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
																			'data-linked_fields'	=> 'categories-revslider-id'
																		)
												),

								'option_skin'	=> 'withalpha',
								'help'			=> __( 'Select a slider for categories pages.', 'exc-uploader-theme' )
							);

$options['_config']['settings']['tabs']['categories']['categories_revslider_id'] = array(
								'label'			=> __( 'Revolution Slider id', 'exc-uploader-theme' ),
								'type'			=> 'select',
								'options'		=> exc_revslider_list(),
								'attrs'			=> array(
														'class' => 'form-control',
														'id'	=> 'categories-revslider-id'
													),

								'validation'	=> '',
								'help'			=> __( 'Select a revolution slider. <br /><br /><i> <strong>Note: </strong>You can manage this list by adding or removing sliders in revolution slider</i>.', 'exc-uploader-theme' )
							);

$options['_config']['settings']['tabs']['categories']['categories_header_sidebar_status'] = array(
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
															'data-linked_fields'	=> 'categories-header-sidebar'
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

$options['_config']['settings']['tabs']['categories']['categories_header_sidebar'] = array(
								'label'		=> __('Header Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'categories-header-sidebar'
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
//								'skin'		=> 'form_field',
								'help'		=> __('Select a sidebar to show as header sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['categories']['categories_structure'] = array(
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
														'data-linked_fields'	=> 'categories_left_sidebar',
														'class'					=> 'btn exc-clickable layout-left-sidebar'
													),

												'right-sidebar'	=>
													array(
														'data-id'				=> 'right-sidebar',
														'data-linked_fields'	=> 'categories_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-sidebar'
													),

												'two-sidebars'	=>
													array(
														'data-id'				=> 'two-sidebars',
														'data-linked_fields'	=> 'categories_left_sidebar, categories_right_sidebar',
														'class'					=> 'btn exc-clickable layout-three-column'
													),

												'left-two-sidebars'	=>
													array(
														'data-id'				=> 'left-two-sidebars',
														'data-linked_fields'	=> 'categories_left_sidebar, categories_right_sidebar',
														'class'					=> 'btn exc-clickable layout-left-twosidebar'
													),
												'right-two-sidebars'			=>
													array(
														'data-id'				=> 'right-two-sidebars',
														'data-linked_fields'	=> 'categories_left_sidebar, categories_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-twosidebar'
													),
												),
								'el_columns'	=> '7',
								'validation'	=> 'required',
								'option_skin'	=> 'with_css',
								'default'		=> 'right-sidebar',
								'help'			=> sprintf(
														__( 'Select a layout structure for categories pages. <br /><br /><i><strong>Note: </strong>You can manage sidebars easily in %s.</i>', 'exc-uploader-theme' ),
														'<a href="' . admin_url('themes.php?page=theme-options&section=manage_sidebars') . '">Manage Sidebars</a>'
													)
							);

$options['_config']['settings']['tabs']['categories']['categories_left_sidebar'] = array(
								'label'		=> __('Left Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'categories_left_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as left sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['categories']['categories_right_sidebar'] = array(
								'label'		=> __('Right Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'categories_right_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'default'	=> 'home-page-right-sidebar',
								'help'		=> __('Select a sidebar to show as right sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['categories']['categories_post_type'] = array(
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
								'help' 			=> __('Show posts from selected sections.', 'exc-uploader-theme'),
							);


$options['_config']['settings']['tabs']['categories']['categories_show_filtration'] = array(
								'label'			=> __('Filtration', 'exc-uploader-theme'),
								'type'			=> 'switch',
								'attrs'			=> array( 'class' => 'form-control' ),
								'default'		=> 'on',
								'help' 			=> __('Show or hide the filtration from categories page.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['categories']['categories_active_view'] = array(
								'label'		=> __('Default View', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array( 'class' => 'form-control' ),
								
								'options' 	=> array(
												'grid' => __('Grid View', 'exc-uploader-theme'),
												'list' => __('List View', 'exc-uploader-theme'),
											),
								
								'help' 		=> __('Select the default posts view.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['categories']['categories_columns'] = array(
								'label'			=> __('Grid View Columns', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control' ),
								'options'		=> array(
														2 => __( '2 Columns', 'exc-uploader-theme' ),
														3 => __( '3 Columns', 'exc-uploader-theme' ),
														4 => __( '4 Columns', 'exc-uploader-theme' )
													),
													
								'selected'		=> 3,
								'validation'	=> 'required',
								'help' 			=> __('Select the number of columns for grid view.', 'exc-uploader-theme'),
							);				

$options['_config']['settings']['tabs']['categories']['categories_list_columns'] =array(
								'label'			=> __('List View Columns', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control' ),
								'options'		=> array(
														1 => __( '1 Columns', 'exc-uploader-theme' ),
														2 => __( '2 Columns', 'exc-uploader-theme' ),
													),
													
								'selected'		=> 2,
								'validation'	=> 'required',
								'help' 			=> __('Select the number of columns for list view.', 'exc-uploader-theme'),
							);

// Detail Page settings
$options['_config']['settings']['tabs']['detail_page']['detail_page_force_settings'] = array(
								'label'		=> esc_html__('Force Settings', 'exc-uploader-theme'),
								'type'		=> 'switch',
								'attrs'		=> array(
													'class' => 'form-control'
												),

								'default'	=> 'on',
								'help'		=> esc_html__('Force these settings on page settings.', 'exc-uploader-theme')
							);

$options['_config']['settings']['tabs']['detail_page']['detail_page_header'] = array(
								'label'		=> __('Header', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array( 'class' => 'form-control' ),
								
								'options' 	=> array(
												'' 			=> __('Default Header', 'exc-uploader-theme'),
												'center' 	=> __('Header with logo in center', 'exc-uploader-theme'),
												'classic' 	=> __('Classic Header', 'exc-uploader-theme')
											),
								
								'help' 		=> __('Select a header for archive pages.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['detail_page']['detail_page_slider'] = array(
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
																			'data-linked_fields'	=> 'detail_page-revslider-id'
																		)
												),

								'option_skin'	=> 'withalpha',
								'help'			=> __( 'Select a slider for archive pages.', 'exc-uploader-theme' )
							);

$options['_config']['settings']['tabs']['detail_page']['detail_page_revslider_id'] = array(
								'label'			=> __( 'Revolution Slider id', 'exc-uploader-theme' ),
								'type'			=> 'select',
								'options'		=> exc_revslider_list(),
								'attrs'			=> array(
														'class' => 'form-control',
														'id'	=> 'detail_page-revslider-id'
													),

								'validation'	=> '',
								'help'			=> __( 'Select a revolution slider. <br /><br /><i> <strong>Note: </strong>You can manage this list by adding or removing sliders in revolution slider</i>.', 'exc-uploader-theme' )
							);

$options['_config']['settings']['tabs']['detail_page']['detail_page_header_sidebar_status'] = array(
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
															'data-linked_fields'	=> 'detail_page-header-sidebar'
														),
												),

								'attrs'		=> array(
													'data-max_limit' => 1,
												),
								
								'option_skin'=> 'withalpha',
								'validation'=> '',
								'default'	=> 'show',
								'help'		=> esc_html__('Show or hide a sidebar in header section.', 'exc-uploader-theme')
							);

$options['_config']['settings']['tabs']['detail_page']['detail_page_header_sidebar'] = array(
								'label'		=> __('Header Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'detail_page-header-sidebar'
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'selected'	=> array('detail_page_header_sidebar'),
								'help'		=> __('Select a sidebar to show as header sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['detail_page']['detail_page_structure'] = array(
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
														'data-linked_fields'	=> 'detail_page_left_sidebar',
														'class'					=> 'btn exc-clickable layout-left-sidebar'
													),

												'right-sidebar'	=>
													array(
														'data-id'				=> 'right-sidebar',
														'data-linked_fields'	=> 'detail_page_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-sidebar'
													),

												'two-sidebars'	=>
													array(
														'data-id'				=> 'two-sidebars',
														'data-linked_fields'	=> 'detail_page_left_sidebar, detail_page_right_sidebar',
														'class'					=> 'btn exc-clickable layout-three-column'
													),

												'left-two-sidebars'	=>
													array(
														'data-id'				=> 'left-two-sidebars',
														'data-linked_fields'	=> 'detail_page_left_sidebar, detail_page_right_sidebar',
														'class'					=> 'btn exc-clickable layout-left-twosidebar'
													),
												'right-two-sidebars'			=>
													array(
														'data-id'				=> 'right-two-sidebars',
														'data-linked_fields'	=> 'detail_page_left_sidebar, detail_page_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-twosidebar'
													),
												),
								'el_columns'	=> '7',
								'validation'	=> 'required',
								'option_skin'	=> 'with_css',
								'default'		=> 'full-width',
								'help'			=> sprintf(
														__( 'Select a layout structure for archive pages. <br /><br /><i><strong>Note: </strong>You can manage sidebars easily in %s.</i>', 'exc-uploader-theme' ),
														'<a href="' . admin_url('themes.php?page=theme-options&section=manage_sidebars') . '">Manage Sidebars</a>'
													)														
							);

$options['_config']['settings']['tabs']['detail_page']['detail_page_left_sidebar'] = array(
								'label'		=> __('Left Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'detail_page_left_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as left sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['detail_page']['detail_page_right_sidebar'] = array(
								'label'		=> __('Right Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'detail_page_right_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as right sidebar.', 'exc-uploader-theme'),
							);

// Tags Template settings
$options['_config']['settings']['tabs']['tags']['tags_header'] = array(
								'label'		=> __('Header', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array( 'class' => 'form-control' ),
								
								'options' 	=> array(
												'' 			=> __('Header Default', 'exc-uploader-theme'),
												'center' 	=> __('Header with logo in Center', 'exc-uploader-theme'),
												'classic' 	=> __('Header Classic', 'exc-uploader-theme')
												),
								
								'help' 		=> __('Select a header for tags pages.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['tags']['tags_slider'] = array(
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
																			'data-linked_fields'	=> 'tags-revslider-id'
																		)
												),

								'option_skin'	=> 'withalpha',
								'help'			=> __( 'Select a slider for tags pages.', 'exc-uploader-theme' )
							);

$options['_config']['settings']['tabs']['tags']['tags_revslider_id'] = array(
								'label'			=> __( 'Revolution Slider id', 'exc-uploader-theme' ),
								'type'			=> 'select',
								'options'		=> exc_revslider_list(),
								'attrs'			=> array(
														'class' => 'form-control',
														'id'	=> 'tags-revslider-id'
													),

								'validation'	=> '',
								'help'			=> __( 'Select a revolution slider. <br /><br /><i> <strong>Note: </strong>You can manage this list by adding or removing sliders in revolution slider</i>.', 'exc-uploader-theme' )
							);

$options['_config']['settings']['tabs']['tags']['tags_header_sidebar_status'] = array(
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
															'data-linked_fields'	=> 'tags-header-sidebar'
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

$options['_config']['settings']['tabs']['tags']['tags_header_sidebar'] = array(
								'label'		=> __('Header Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'tags-header-sidebar'
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
//								'skin'		=> 'form_field',
								'help'		=> __('Select a sidebar to show as header sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['tags']['tags_structure'] = array(
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
														'data-linked_fields'	=> 'tags_left_sidebar',
														'class'					=> 'btn exc-clickable layout-left-sidebar'
													),

												'right-sidebar'	=>
													array(
														'data-id'				=> 'right-sidebar',
														'data-linked_fields'	=> 'tags_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-sidebar'
													),

												'two-sidebars'	=>
													array(
														'data-id'				=> 'two-sidebars',
														'data-linked_fields'	=> 'tags_left_sidebar, tags_right_sidebar',
														'class'					=> 'btn exc-clickable layout-three-column'
													),

												'left-two-sidebars'	=>
													array(
														'data-id'				=> 'left-two-sidebars',
														'data-linked_fields'	=> 'tags_left_sidebar, tags_right_sidebar',
														'class'					=> 'btn exc-clickable layout-left-twosidebar'
													),
												'right-two-sidebars'			=>
													array(
														'data-id'				=> 'right-two-sidebars',
														'data-linked_fields'	=> 'tags_left_sidebar, tags_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-twosidebar'
													),
												),
								'el_columns'	=> '7',
								'validation'	=> 'required',
								'option_skin'	=> 'with_css',
								'default'		=> 'full-width',
								'help'			=> sprintf(
														__( 'Select a layout structure for tags pages. <br /><br /><i><strong>Note: </strong>You can manage sidebars easily in %s.</i>', 'exc-uploader-theme' ),
														'<a href="' . admin_url('themes.php?page=theme-options&section=manage_sidebars') . '">Manage Sidebars</a>'
													)
							);

$options['_config']['settings']['tabs']['tags']['tags_left_sidebar'] = array(
								'label'		=> __('Left Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'tags_left_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as left sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['tags']['tags_right_sidebar'] = array(
								'label'		=> __('Right Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'tags_right_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as right sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['tags']['tags_post_type'] = array(
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
								'help' 			=> __('Show posts from selected sections.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['tags']['tags_show_filtration'] = array(
								'label'			=> __('Filtration', 'exc-uploader-theme'),
								'type'			=> 'switch',
								'attrs'			=> array( 'class' => 'form-control' ),
								'default'		=> 'on',
								'help' 			=> __('Show or hide the filtration from tags pages.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['tags']['tags_active_view'] = array(
								'label'		=> __('Default View', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array( 'class' => 'form-control' ),
								
								'options' 	=> array(
												'grid' => __('Grid View', 'exc-uploader-theme'),
												'list' => __('List View', 'exc-uploader-theme'),
											),
								
								'help' 		=> __('Select the default posts view.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['tags']['tags_columns'] =array(
								'label'			=> __('Grid View Columns', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control' ),
								'options'		=> array(
														2 => __( '2 Columns', 'exc-uploader-theme' ),
														3 => __( '3 Columns', 'exc-uploader-theme' ),
														4 => __( '4 Columns', 'exc-uploader-theme' )
													),
													
								'selected'		=> 3,
								'validation'	=> 'required',
								'help' 			=> __('Select the number of columns for grid view.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['tags']['tags_list_columns'] =array(
								'label'			=> __('List View Columns', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control' ),
								'options'		=> array(
														1 => __( '1 Columns', 'exc-uploader-theme' ),
														2 => __( '2 Columns', 'exc-uploader-theme' ),
													),
													
								'selected'		=> 2,
								'validation'	=> 'required',
								'help' 			=> __('Select the number of columns for list view.', 'exc-uploader-theme'),
							);
// Search Template settings
$options['_config']['settings']['tabs']['search']['search_header'] = array(
								'label'		=> __('Header', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array( 'class' => 'form-control' ),
								
								'options' 	=> array(
												'' 			=> __('Header Default', 'exc-uploader-theme'),
												'center' 	=> __('Header with logo in Center', 'exc-uploader-theme'),
												'classic' 	=> __('Header Classic', 'exc-uploader-theme')
												),
								
								'help' 		=> __('Select a header for search result pages.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['search']['search_header_sidebar_status'] = array(
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
															'data-linked_fields'	=> 'search-header-sidebar'
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

$options['_config']['settings']['tabs']['search']['search_header_sidebar'] = array(
								'label'		=> __('Header Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'search-header-sidebar'
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
//								'skin'		=> 'form_field',
								'help'		=> __('Select a sidebar to show as header sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['search']['search_structure'] = array(
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
														'data-linked_fields'	=> 'search_left_sidebar',
														'class'					=> 'btn exc-clickable layout-left-sidebar'
													),

												'right-sidebar'	=>
													array(
														'data-id'				=> 'right-sidebar',
														'data-linked_fields'	=> 'search_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-sidebar'
													),

												'two-sidebars'	=>
													array(
														'data-id'				=> 'two-sidebars',
														'data-linked_fields'	=> 'search_left_sidebar, search_right_sidebar',
														'class'					=> 'btn exc-clickable layout-three-column'
													),

												'left-two-sidebars'	=>
													array(
														'data-id'				=> 'left-two-sidebars',
														'data-linked_fields'	=> 'search_left_sidebar, search_right_sidebar',
														'class'					=> 'btn exc-clickable layout-left-twosidebar'
													),
												'right-two-sidebars'			=>
													array(
														'data-id'				=> 'right-two-sidebars',
														'data-linked_fields'	=> 'search_left_sidebar, search_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-twosidebar'
													),
												),
								'el_columns'	=> '7',
								'validation'	=> 'required',
								'option_skin'	=> 'with_css',
								'default'		=> 'full-width',
								'help'			=> sprintf(
														__( 'Select a layout structure for search result pages. <br /><br /><i><strong>Note: </strong>You can manage sidebars easily in %s.</i>', 'exc-uploader-theme' ),
														'<a href="' . admin_url('themes.php?page=theme-options&section=manage_sidebars') . '">Manage Sidebars</a>'
													)
							);

$options['_config']['settings']['tabs']['search']['search_left_sidebar'] = array(
								'label'		=> __('Left Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'search_left_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as left sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['search']['search_right_sidebar'] = array(
								'label'		=> __('Right Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'search_right_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as right sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['search']['search_post_type'] = array(
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
								'help' 			=> __('Show posts from selected sections.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['search']['search_show_filtration'] = array(
								'label'			=> __('Filtration', 'exc-uploader-theme'),
								'type'			=> 'switch',
								'attrs'			=> array( 'class' => 'form-control' ),
								'default'		=> 'on',
								'help' 			=> __('Show or hide the filtration from search result pages.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['search']['search_active_view'] = array(
								'label'		=> __('Default View', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array( 'class' => 'form-control' ),
								
								'options' 	=> array(
												'grid' => __('Grid View', 'exc-uploader-theme'),
												'list' => __('List View', 'exc-uploader-theme'),
											),
								
								'help' 		=> __('Select the default posts view.', 'exc-uploader-theme'),
							);

$options['_config']['settings']['tabs']['search']['search_columns'] =array(
								'label'			=> __('Grid View Columns', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control' ),
								'options'		=> array(
														2 => __( '2 Columns', 'exc-uploader-theme' ),
														3 => __( '3 Columns', 'exc-uploader-theme' ),
														4 => __( '4 Columns', 'exc-uploader-theme' )
													),
													
								'selected'		=> 4,
								'validation'	=> 'required',
								'help' 			=> __('Select the number of columns for grid view', 'exc-uploader-theme'),
							);


$options['_config']['settings']['tabs']['search']['search_list_columns'] =array(
								'label'			=> __('List View Columns', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control' ),
								'options'		=> array(
														1 => __( '1 Columns', 'exc-uploader-theme' ),
														2 => __( '2 Columns', 'exc-uploader-theme' ),
													),
													
								'selected'		=> 2,
								'validation'	=> 'required',
								'help' 			=> __('Select the number of columns for list view.', 'exc-uploader-theme'),
							);

// Post Details Template settings
$options['_config']['custom_pages']['tabs']['browse_categories']['browse_categories_header'] = array(
								'label'		=> __('Header', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array( 'class' => 'form-control' ),
								
								'options' 	=> array(
												'' 			=> __('Header Default', 'exc-uploader-theme'),
												'center' 	=> __('Header with logo in Center', 'exc-uploader-theme'),
												'classic' 	=> __('Header Classic', 'exc-uploader-theme')
												),
								
								'help' 		=> __('Select a header for browse categories page.', 'exc-uploader-theme'),
							);

$options['_config']['custom_pages']['tabs']['browse_categories']['browse_categories_slider'] = array(
								'label'		=> __( 'Slider', 'exc-uploader-theme' ),
								'type'		=> 'clickable',
								'attrs'		=> array(
													'data-max_limit' => '1',
												),

								'options'	=> array(
												'no-slider'		=>	array(
																		'label'					=> __('No Slider', 'exc-uploader-theme'),
																		'data-id'				=> ''
																	),

												'uploader' 		=>	array(
																		'label'					=> __('Uploader', 'exc-uploader-theme'),
																		'data-id'				=> 'with_uploader'
																	),

												'revslider' 	=> array(
																		'label'					=> __('Revolution Slider', 'exc-uploader-theme'),
																		'data-id'				=> 'revslider',
																		'data-linked_fields'	=> 'browse_categories-revslider-id'
																	)
												),

								'option_skin'	=> 'withalpha',
								'help'			=> __( 'Select a slider for browse categories page.', 'exc-uploader-theme' )
							);

$options['_config']['custom_pages']['tabs']['browse_categories']['browse_categories_revslider_id'] = array(
								'label'			=> __( 'Revolution Slider id', 'exc-uploader-theme' ),
								'type'			=> 'select',
								'options'		=> exc_revslider_list(),
								'attrs'			=> array(
														'class' => 'form-control',
														'id'	=> 'browse_categories-revslider-id'
													),

								'validation'	=> '',
								'help'			=> __( 'Select a revolution slider. <br /><br /><i> <strong>Note: </strong>You can manage this list by adding or removing sliders in revolution slider</i>.', 'exc-uploader-theme' )
							);

$options['_config']['custom_pages']['tabs']['browse_categories']['browse_categories_header_sidebar_status'] = array(
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
															'data-linked_fields'	=> 'browse_categories-header-sidebar'
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

$options['_config']['custom_pages']['tabs']['browse_categories']['browse_categories_header_sidebar'] = array(
								'label'		=> __('Header Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'browse_categories-header-sidebar'
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
//								'skin'		=> 'form_field',
								'help'		=> __('Select a sidebar to show as header sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['custom_pages']['tabs']['browse_categories']['browse_categories_structure'] = array(

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
														'data-linked_fields'	=> 'browse_categories_left_sidebar',
														'class'					=> 'btn exc-clickable layout-left-sidebar'
													),

												'right-sidebar'	=>
													array(
														'data-id'				=> 'right-sidebar',
														'data-linked_fields'	=> 'browse_categories_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-sidebar'
													),

												'two-sidebars'	=>
													array(
														'data-id'				=> 'two-sidebars',
														'data-linked_fields'	=> 'browse_categories_left_sidebar, browse_categories_right_sidebar',
														'class'					=> 'btn exc-clickable layout-three-column'
													),

												'left-two-sidebars'	=>
													array(
														'data-id'				=> 'left-two-sidebars',
														'data-linked_fields'	=> 'browse_categories_left_sidebar, browse_categories_right_sidebar',
														'class'					=> 'btn exc-clickable layout-left-twosidebar'
													),
												'right-two-sidebars'			=>
													array(
														'data-id'				=> 'right-two-sidebars',
														'data-linked_fields'	=> 'browse_categories_left_sidebar, browse_categories_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-twosidebar'
													),
												),
								'el_columns'	=> '7',
								'validation'	=> 'required',
								'option_skin'	=> 'with_css',
								'default'		=> 'right-sidebar',
								'help'			=> sprintf(
														__( 'Select a layout structure for browse categories page.  <br /><br /><i><strong>Note: </strong>You can manage sidebars easily in %s.</i>', 'exc-uploader-theme' ),
														'<a href="' . admin_url('themes.php?page=theme-options&section=manage_sidebars') . '">Manage Sidebars</a>'
													)
							);

$options['_config']['custom_pages']['tabs']['browse_categories']['browse_categories_left_sidebar'] = array(
								'label'		=> __('Left Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'browse_categories_left_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as left sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['custom_pages']['tabs']['browse_categories']['browse_categories_right_sidebar'] = array(
								'label'		=> __('Right Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'browse_categories_right_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'default'	=> 'home-page-right-sidebar',
								'help'		=> __('Select a sidebar to show as right sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['custom_pages']['tabs']['browse_categories']['browse_categories_columns'] = array(
								'label'			=> __('Columns', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control' ),
								'options'		=> array(
														2 => __( '2 Columns', 'exc-uploader-theme' ),
														3 => __( '3 Columns', 'exc-uploader-theme' ),
														4 => __( '4 Columns', 'exc-uploader-theme' )
													),
													
								'selected'		=> 3,
								'validation'	=> 'required',
								'help' 			=> __('Select the number of columns.', 'exc-uploader-theme'),
							);

// Radio Genre
$options['_config']['custom_pages']['tabs']['radio_genre']['genre_header'] = array(
								'label'		=> __('Header', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array( 'class' => 'form-control' ),
								
								'options' 	=> array(
												'' 			=> __('Header Default', 'exc-uploader-theme'),
												'center' 	=> __('Header with logo in Center', 'exc-uploader-theme'),
												'classic' 	=> __('Header Classic', 'exc-uploader-theme')
												),
								
								'help' 		=> __('Select a header for radio genre pages.', 'exc-uploader-theme'),
							);

$options['_config']['custom_pages']['tabs']['radio_genre']['genre_slider'] = array(
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
																			'data-linked_fields'	=> 'radio_genre-revslider-id'
																		)
												),

								'option_skin'	=> 'withalpha',
								'help'			=> __( 'Select a slider for radio genre pages.', 'exc-uploader-theme' )
							);

$options['_config']['custom_pages']['tabs']['radio_genre']['genre_revslider_id'] = array(
								'label'			=> __( 'Revolution Slider id', 'exc-uploader-theme' ),
								'type'			=> 'select',
								'options'		=> exc_revslider_list(),
								'attrs'			=> array(
														'class' => 'form-control',
														'id'	=> 'radio_genre-revslider-id'
													),

								'validation'	=> '',
								'help'			=> __( 'Select a revolution slider. <br /><br /><i> <strong>Note: </strong>You can manage this list by adding or removing sliders in revolution slider</i>.', 'exc-uploader-theme' )
							);

$options['_config']['custom_pages']['tabs']['radio_genre']['genre_header_sidebar_status'] = array(
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
															'data-linked_fields'	=> 'genre-header-sidebar'
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

$options['_config']['custom_pages']['tabs']['radio_genre']['genre_header_sidebar'] = array(
								'label'		=> __('Header Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'genre-header-sidebar'
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
//								'skin'		=> 'form_field',
								'help'		=> __('Select a sidebar to show as header sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['custom_pages']['tabs']['radio_genre']['genre_structure'] = array(
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
														'data-linked_fields'	=> 'radio_genre_left_sidebar',
														'class'					=> 'btn exc-clickable layout-left-sidebar'
													),

												'right-sidebar'	=>
													array(
														'data-id'				=> 'right-sidebar',
														'data-linked_fields'	=> 'radio_genre_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-sidebar'
													),

												'two-sidebars'	=>
													array(
														'data-id'				=> 'two-sidebars',
														'data-linked_fields'	=> 'radio_genre_left_sidebar, radio_genre_right_sidebar',
														'class'					=> 'btn exc-clickable layout-three-column'
													),

												'left-two-sidebars'	=>
													array(
														'data-id'				=> 'left-two-sidebars',
														'data-linked_fields'	=> 'radio_genre_left_sidebar, radio_genre_right_sidebar',
														'class'					=> 'btn exc-clickable layout-left-twosidebar'
													),
												'right-two-sidebars'			=>
													array(
														'data-id'				=> 'right-two-sidebars',
														'data-linked_fields'	=> 'radio_genre_left_sidebar, radio_genre_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-twosidebar'
													),
												),
								'el_columns'	=> '7',
								'validation'	=> 'required',
								'option_skin'	=> 'with_css',
								'default'		=> 'right-sidebar',
								'help'			=> sprintf(
														__( 'Select a layout structure for radio genre pages. <br /><br /><i><strong>Note: </strong>You can manage sidebars easily in %s.</i>', 'exc-uploader-theme' ),
														'<a href="' . admin_url('themes.php?page=theme-options&section=manage_sidebars') . '">Manage Sidebars</a>'
													)
							);

$options['_config']['custom_pages']['tabs']['radio_genre']['genre_left_sidebar'] = array(
								'label'		=> __('Left Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'radio_genre_left_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as left sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['custom_pages']['tabs']['radio_genre']['genre_right_sidebar'] = array(
								'label'		=> __('Right Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'radio_genre_right_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'default'	=> 'home-page-right-sidebar',
								'help'		=> __('Select a sidebar to show as right sidebar.', 'exc-uploader-theme'),
							);

// Contact Template Settings
$options['_config']['custom_pages']['tabs']['contact']['contact_header'] = array(
								'label'		=> __('Header', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array( 'class' => 'form-control' ),
								
								'options' 	=> array(
												'' 			=> __('Header Default', 'exc-uploader-theme'),
												'center' 	=> __('Header with logo in Center', 'exc-uploader-theme'),
												'classic' 	=> __('Header Classic', 'exc-uploader-theme')
												),
								
								'help' 		=> __('Select a header for contact page.', 'exc-uploader-theme'),
							);

$options['_config']['custom_pages']['tabs']['contact']['contact_header_sidebar_status'] = array(
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
															'data-linked_fields'	=> 'contact-header-sidebar'
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

$options['_config']['custom_pages']['tabs']['contact']['contact_header_sidebar'] = array(
								'label'		=> __('Header Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'contact-header-sidebar'
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
//								'skin'		=> 'form_field',
								'help'		=> __('Select a sidebar to show as header sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['custom_pages']['tabs']['contact']['contact_structure'] = array(
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
														'data-linked_fields'	=> 'contact_left_sidebar',
														'class'					=> 'btn exc-clickable layout-left-sidebar'
													),

												'right-sidebar'	=>
													array(
														'data-id'				=> 'right-sidebar',
														'data-linked_fields'	=> 'contact_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-sidebar'
													),

												'two-sidebars'	=>
													array(
														'data-id'				=> 'two-sidebars',
														'data-linked_fields'	=> 'contact_left_sidebar, contact_right_sidebar',
														'class'					=> 'btn exc-clickable layout-three-column'
													),

												'left-two-sidebars'	=>
													array(
														'data-id'				=> 'left-two-sidebars',
														'data-linked_fields'	=> 'contact_left_sidebar, contact_right_sidebar',
														'class'					=> 'btn exc-clickable layout-left-twosidebar'
													),
												'right-two-sidebars'			=>
													array(
														'data-id'				=> 'right-two-sidebars',
														'data-linked_fields'	=> 'contact_left_sidebar, contact_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-twosidebar'
													),
												),
								'el_columns'	=> '7',
								'validation'	=> 'required',
								'option_skin'	=> 'with_css',
								'default'		=> 'full-width',
								'help'			=> sprintf(
														__( 'Select a layout structure for contact page. <br /><br /><i><strong>Note: </strong>You can manage sidebars easily in %s.</i>', 'exc-uploader-theme' ),
														'<a href="' . admin_url('themes.php?page=theme-options&section=manage_sidebars') . '">Manage Sidebars</a>'
													)
							);

$options['_config']['custom_pages']['tabs']['contact']['contact_left_sidebar'] = array(
								'label'		=> __('Left Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'contact_left_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as left sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['custom_pages']['tabs']['contact']['contact_right_sidebar'] = array(
								'label'		=> __('Right Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'contact_right_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as right sidebar.', 'exc-uploader-theme'),
							);

// Users Page Settings
$options['_config']['custom_pages']['tabs']['users']['users_header'] = array(
								'label'		=> __('Header', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array( 'class' => 'form-control' ),
								
								'options' 	=> array(
												'' 			=> __('Header Default', 'exc-uploader-theme'),
												'center' 	=> __('Header with logo in Center', 'exc-uploader-theme'),
												'classic' 	=> __('Header Classic', 'exc-uploader-theme')
												),
								
								'help' 		=> __('Select a header for users page.', 'exc-uploader-theme'),
							);

$options['_config']['custom_pages']['tabs']['users']['users_slider'] = array(
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
																			'data-linked_fields'	=> 'users-revslider-id'
																		)
												),

								'option_skin'	=> 'withalpha',
								'help'			=> __( 'Select a slider for users page.', 'exc-uploader-theme' )
							);

$options['_config']['custom_pages']['tabs']['users']['users_revslider_id'] = array(
								'label'			=> __( 'Revolution Slider id', 'exc-uploader-theme' ),
								'type'			=> 'select',
								'options'		=> exc_revslider_list(),
								'attrs'			=> array(
														'class' => 'form-control',
														'id'	=> 'users-revslider-id'
													),

								'validation'	=> '',
								'help'			=> __( 'Select a revolution slider. <br /><br /><i> <strong>Note: </strong>You can manage this list by adding or removing sliders in revolution slider</i>.', 'exc-uploader-theme' )
							);

$options['_config']['custom_pages']['tabs']['users']['users_structure'] = array(
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
														'data-linked_fields'	=> 'users_left_sidebar',
														'class'					=> 'btn exc-clickable layout-left-sidebar'
													),

												'right-sidebar'	=>
													array(
														'data-id'				=> 'right-sidebar',
														'data-linked_fields'	=> 'users_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-sidebar'
													),

												'two-sidebars'	=>
													array(
														'data-id'				=> 'two-sidebars',
														'data-linked_fields'	=> 'users_left_sidebar, users_right_sidebar',
														'class'					=> 'btn exc-clickable layout-three-column'
													),

												'left-two-sidebars'	=>
													array(
														'data-id'				=> 'left-two-sidebars',
														'data-linked_fields'	=> 'users_left_sidebar, users_right_sidebar',
														'class'					=> 'btn exc-clickable layout-left-twosidebar'
													),
												'right-two-sidebars'			=>
													array(
														'data-id'				=> 'right-two-sidebars',
														'data-linked_fields'	=> 'users_left_sidebar, users_right_sidebar',
														'class'					=> 'btn exc-clickable layout-right-twosidebar'
													),
												),
								'el_columns'	=> '7',
								'validation'	=> 'required',
								'option_skin'	=> 'with_css',
								'default'		=> 'full-width',
								'help'			=> sprintf(
														__( 'Select a layout structure for users page. <br /><br /><i><strong>Note: </strong>You can manage sidebars easily in %s.</i>', 'exc-uploader-theme' ),
														'<a href="' . admin_url('themes.php?page=theme-options&section=manage_sidebars') . '">Manage Sidebars</a>'
													)
							);

$options['_config']['custom_pages']['tabs']['users']['users_left_sidebar'] = array(
								'label'		=> __('Left Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'users_left_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as left sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['custom_pages']['tabs']['users']['users_right_sidebar'] = array(
								'label'		=> __('Right Sidebar', 'exc-uploader-theme'),
								'type'		=> 'select',
								'attrs'		=> array(
													'class' => 'form-control',
													'id'	=> 'users_right_sidebar',
												),
												
								'options'	=> &$GLOBALS['exc_sidebars_list'],
								'help'		=> __('Select a sidebar to show as right sidebar.', 'exc-uploader-theme'),
							);

$options['_config']['custom_pages']['tabs']['users']['users_user_roles'] = array(
								'label' => __('User Roles', 'exc-uploader-theme'),
								'type'	=> 'select',
								'attrs' => array(
											'class'		=> 'widefat',
											'multiple'	=> 'multiple',
											'size'		=> 5,
									),
								'options' => exc_get_roles(),
								'selected' => 'subscriber',
								'validation' => 'required',
								'help' => __('Show users with selected wordpress roles.', 'exc-uploader-theme'),
							);

$options['_config']['custom_pages']['tabs']['users']['users_show_filtration'] = array(
								'label'			=> __('Filtration', 'exc-uploader-theme'),
								'type'			=> 'switch',
								'attrs'			=> array( 'class' => 'form-control' ),
								'default'		=> 'on',
								'help' 			=> __('Show or hide the filtration from users page.', 'exc-uploader-theme'),
							);

$options['_config']['custom_pages']['tabs']['users']['users_columns'] =array(
								'label'			=> __('Columns', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control' ),
								'options'		=> array(
														2 => __( '2 Columns', 'exc-uploader-theme' ),
														3 => __( '3 Columns', 'exc-uploader-theme' ),
														4 => __( '4 Columns', 'exc-uploader-theme' )
													),
													
								'selected'		=> 3,
								'validation'	=> 'required',
								'help' 			=> __('Select the number of columns.', 'exc-uploader-theme'),
							);

$options['_config']['pagination']['ajax_pagi'] = array(
								'label' => __('AJAX Pagination ', 'exc-uploader-theme'),
								'type'	=> 'switch',
								'attrs' => array(
											'class' => 'form-control',
									),
								
								'default' => 'on',
								'help' => __('Load next pages with ajax works for category, archives, search, tags pages.', 'exc-uploader-theme'),
							);
							
$options['_config']['pagination']['ajax_blog_pagi'] = array(
								'label' => __('AJAX Blog Pagination ', 'exc-uploader-theme'),
								'type'	=> 'switch',
								'attrs' => array(
											'class' => 'form-control',
									),
								
								'default' => 'on',
								'help' => __('Replace the default blog next and previous pagination with advanced ajax based load more button.', 'exc-uploader-theme'),
							);