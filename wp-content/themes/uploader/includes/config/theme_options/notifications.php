<?php defined('ABSPATH') OR die('restricted access');

$this->html->load_js_args( 'exc-dynamic-fields', $this->system_url('views/js/fields/dynamic-fields.js'), array('jquery-ui-sortable') );

$options = array(
				'title'		=> esc_html__('Top Notifications', 'exc-uploader-theme'),
				'db_name'	=> 'mf_notifications',
			);

$options['_config']['_settings'] =
						array(
							'settings' =>
								array(
									'heading'		=> esc_html__('Notifications General Settings', 'exc-uploader-theme'),
									'description'	=> esc_html__('Top notifications are the one which are visible at the very top of the page.', 'exc-uploader-theme'),
								),
							'notifications' =>
								array(
									'heading'		=> esc_html__('Mange Notifications', 'exc-uploader-theme'),
									'description'	=> __('In this section you can add as many notifications as required<p><i><strong>Note: </strong><ol><li>The sticky notification option will overwrite the display order and that notification will have priority over others.</li><li>You can easily sort, remove or add more rows by using the controls given at the right side of each row.</li></ul></i></p>.', 'exc-uploader-theme'),
								),
							'style_settings' =>
								array(
									'heading'		=> esc_html__('Notifications Style Settings', 'exc-uploader-theme'),
									'description'	=> esc_html__('The following settings are related to top notifications style settings, if you leave any field blank then it will be replaced with the default stylesheet value.', 'exc-uploader-theme'),
								),
						);

// top nofications section
$options['_config']['settings']['status'] = array(
								'label'		=> esc_html__('Status', 'exc-uploader-theme'),
								'type'		=> 'switch',
								'attrs'		=> array(
													'class' => 'form-control'
												),

								'default'	=> 'on',
								'help'		=> esc_html__('Show or hide the top notifications.', 'exc-uploader-theme')
							);

// random notifications status
$options['_config']['settings']['random'] = array(
								'label'		=> esc_html__('Random', 'exc-uploader-theme'),
								'type'		=> 'switch',
								'attrs'		=> array(
													'class' => 'form-control'
												),

								'default'	=> 'on',
								'help'		=> __('Show notifications randomly on each page refresh. <br /><br /> <i><strong>note: </strong>this option will stop working in case of any sticky notification.</i>', 'exc-uploader-theme')
							);

// close button status
$options['_config']['settings']['close_btn'] = array(
								'label'		=> esc_html__('Close Button', 'exc-uploader-theme'),
								'type'		=> 'switch',
								'attrs'		=> array(
													'class' => 'form-control'
												),

								'default'	=> 'on',
								'help'		=> esc_html__('Show or hide the close [x] button from nofication bar.', 'exc-uploader-theme')
							);

// manage notifications block settings

$options['_config']['notifications']['dynamic']['_settings']['top_notifications'] = array(
								'default_title' => esc_html__('[Notification]', 'exc-uploader-theme'),
							);
$options['_config']['notifications']['dynamic']['top_notifications']['title'] = array(
								'label'		=> esc_html__('Title', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control',
													'data-primary_field' => true
												),
									
								'validation'=> 'required|min_length[3]|stripcslashes',
								'default'	=> '',
								'help'		=> esc_html__('Enter the title for backend identification.', 'exc-uploader-theme')
							);

// Top Nofications sticky status
$options['_config']['notifications']['dynamic']['top_notifications']['sticky'] = array(
								'label'		=> esc_html__('Sticky', 'exc-uploader-theme'),
								'type'		=> 'switch',
								'attrs'		=> array(
													'class' => 'form-control'
												),

								'default'	=> 'on',
								'help'		=> esc_html__('Sticky nofication will always have priority on other notifications.', 'exc-uploader-theme')
							);

// top Notifications Visibility Settings
$options['_config']['notifications']['dynamic']['top_notifications']['visible_to'] = array(
								'label'		=> esc_html__('Visible To', 'exc-uploader-theme'),
								'type'		=> 'select',
								'options'	=> array(
													'all' 			=> __('All', 'exc-uploader-theme'),
													'registered'	=> __('Registered Members Only', 'exc-uploader-theme'),
													'unregistered'	=> __('Unregistered Members Only', 'exc-uploader-theme'),
												),
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> '',
								'default'	=> '',
								'help'		=> esc_html__('Limit the visiblity for registered or unregistered users.', 'exc-uploader-theme')
							);

// Notification Text Field
$options['_config']['notifications']['dynamic']['top_notifications']['content'] = array(
								'label'		=> esc_html__('Content', 'exc-uploader-theme'),
								'type'		=> 'textarea',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'required|min_length[20]|stripslashes',
								'default'	=> '',
								'help'		=> esc_html__('Write your notification text, the HTML tags are allowed.', 'exc-uploader-theme')
							);

//Top Notification style Settings
//Top Notification background-color
$options['_config']['style_settings']['bg_color'] = array(
								'label'		=> esc_html__('Background Color', 'exc-uploader-theme'),
								'type'		=> 'colorpicker',
								'attrs'		=> array(
													'class' => 'form-control'
												),
								
								'style_opt_key' => '',
								'css_selector'	=> '.top-bar',
								'prop_name'		=> 'background-color',

								'validation'=> '',
								'default'	=> '#e74c3c',
								'help'		=> esc_html__('Change the top notification background color (default: #e74c3c).', 'exc-uploader-theme')
							);

//Top Notification text-color
$options['_config']['style_settings']['text_color'] = array(
								'label'			=> esc_html__('Text Color', 'exc-uploader-theme'),
								'type'			=> 'colorpicker',
								'attrs'			=> array(
														'class' => 'form-control'
													),
								
								'style_opt_key' => '',
								'css_selector'	=> '.top-bar .topbar-inner, .top-bar .topbar-inner .close',
								'prop_name'		=> 'color',

								'validation'	=> '',
								'default'		=> '',
								'help'			=> esc_html__('Change the top notification text color (default: #fff).', 'exc-uploader-theme')
							);

//Top Notification button background-color
$options['_config']['style_settings']['btn_bg'] = array(
								'label'			=> esc_html__('Button Background', 'exc-uploader-theme'),
								'type'			=> 'colorpicker',
								'attrs'			=> array(
														'class' => 'form-control'
													),
								
								'style_opt_key' => '',
								'css_selector'	=> '.top-bar .btn',
								'prop_name'		=> 'background-color',

								'validation'	=> '',
								'default'		=> '#ffffff',
								'help'			=> esc_html__("Change the top notifications's button background color (default: #fff).", 'exc-uploader-theme')
							);

//Top Notification button text color
$options['_config']['style_settings']['btn_text_color'] = array(
								'label'			=> esc_html__('Button Text Color', 'exc-uploader-theme'),
								'type'			=> 'colorpicker',
								'attrs'			=> array(
														'class' => 'form-control'
													),
								
								'style_opt_key' => '',
								'css_selector'	=> '.top-bar .btn',
								'prop_name'		=> 'color',

								'validation'	=> '',
								'default'		=> '#e74c3c',
								'help'			=> esc_html__("Change the top notification's button text color (default: #e74c3c).", 'exc-uploader-theme')
							);

//Top Notification button text Hover color
$options['_config']['style_settings']['btn_text_hover_color'] = array(
								'label'			=> esc_html__('Button Hover Color', 'exc-uploader-theme'),
								'type'			=> 'colorpicker',
								'attrs'			=> array(
														'class' => 'form-control'
													),
								
								'style_opt_key' => '',
								'css_selector'	=> '.top-bar .btn:hover, .top-bar .btn:focus',
								'prop_name'		=> 'color',

								'validation'	=> '',
								'default'		=> '#e74c3c',
								'help'			=> esc_html__("Change the top notification's button hover color (default: #e74c3c).", 'exc-uploader-theme')
							);