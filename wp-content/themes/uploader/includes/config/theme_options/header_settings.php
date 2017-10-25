<?php defined('ABSPATH') OR die('restricted access');

$options = array(
                'title'     => esc_html__('Header Settings', 'exc-uploader-theme'),
                'db_name'   => 'mf_header_settings',
            );

$options['_config']['_settings'] =
                        array(
                            'logos' =>
                                array(
                                    'heading'       => esc_html__('Logo Settings', 'exc-uploader-theme'),
                                    'description'   => esc_html__('The Logo section helps you to easily relace or update website logo along with custom width or height settings.', 'exc-uploader-theme'),
                                ),
                            'header_default' =>
                                array(
                                    'heading'       => sprintf(
                                                        __('%s <hr /> Header Default Settings', 'exc-uploader-theme'),
                                                            '<img src=" ' . esc_url( $this->get_file_url('views/images/header1.jpg', 'local_dir') ) . '" class="clickable-image" />'
                                                        ),
                                    'description'   => esc_html__('The following settings are related to default header', 'exc-uploader-theme'),
                                ),
                            'header_center' =>
                                array(
                                    'heading'       => sprintf(
                                                        __('%s <hr /> Header Center Settings', 'exc-uploader-theme'),
                                                            '<img src=" ' . $this->get_file_url('views/images/header2.jpg', 'local_dir') . '" class="clickable-image" />'
                                                        ),
                                    'description'   => esc_html__('The following settings are related to the header center.', 'exc-uploader-theme')
                                ),
                            'header_classic' =>
                                array(
                                    'heading'       => sprintf(
                                                        __('%s <hr /> Header Classic Settings', 'exc-uploader-theme'),
                                                            '<img src=" ' . $this->get_file_url('views/images/header3.jpg', 'local_dir') . '" class="clickable-image" />'
                                                        ),

                                    'description'   => esc_html__('The following settings are related to the header classic.', 'exc-uploader-theme')
                                ),
                        );

// Logo Block
$options['_config']['logos']['logo'] = array(
                                'label'     => esc_html__('Logo', 'exc-uploader-theme'),
                                'type'      => 'image',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'validation'=> 'valid_url',
                                'default'   => get_template_directory_uri() . '/images/logo.png',
                                'help'      => esc_html__('Upload or enter a url for website logo (default logo size: 244x24)', 'exc-uploader-theme')
                            );

$options['_config']['logos']['logo_width'] = array(
                                'label'         => esc_html__('Logo Width', 'exc-uploader-theme'),
                                'type'          => 'text',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => '',
                                'css_selector'  => '.logo > img',
                                'prop_name'     => 'width: %spx;',

                                'validation'    => 'is_natural',
                                'default'       => '',
                                'help'          => esc_html__('Change the width of logo (default: auto).', 'exc-uploader-theme')
                            );

$options['_config']['logos']['logo_height'] = array(
                                'label'         => esc_html__('Logo Height', 'exc-uploader-theme'),
                                'type'          => 'text',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => '',
                                'css_selector'  => '.logo > img',
                                'prop_name'     => 'height: %spx;',

                                'validation'    => 'is_natural',
                                'default'       => '',
                                'help'          => esc_html__('Change the height of logo (defualt: auto).', 'exc-uploader-theme')
                            );

// Default Header settings
// Header Sticky Status
$options['_config']['header_default']['tabs']['general_settings']['header_is_sticky'] = array(
                                'label'     => esc_html__('Sticky', 'exc-uploader-theme'),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'help'      => esc_html__('Show the on page scroll.', 'exc-uploader-theme')
                            );

// Header Default Background Image
$options['_config']['header_default']['tabs']['general_settings']['header_bg_image'] = array(
                                'label'         => esc_html__('Background Image', 'exc-uploader-theme'),
                                'type'          => 'image',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => '',
                                'css_selector'  => '.header-bottom',
                                'prop_name'     => 'background-image: url(%s)',

                                'validation'    => 'valid_url',
                                'default'       => get_template_directory_uri() . '/images/header.jpg',
                                'help'          => esc_html__('Change the header background image.', 'exc-uploader-theme')
                            );

// Header Default Backgorund Color
$options['_config']['header_default']['tabs']['general_settings']['header_bg_color'] = array(
                                'label'         => esc_html__('Background color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => '',
                                'css_selector'  => '.logo-bar',
                                'prop_name'     => 'background-color: rgba(%1$d, %2$d, %3$d, %4$s)',
                                'append'        => 'header_transparency',

                                'validation'    => 'hex2rgb',
                                'default'       => '#ffffff',
                                'help'          => esc_html__('Change the header background color (default: #ffffff).', 'exc-uploader-theme')
                            );

// header default transparencey
$options['_config']['header_default']['tabs']['general_settings']['header_transparency'] = array(
                                'label'     => esc_html__('Transparency', 'exc-uploader-theme'),
                                'type'      => 'range_slider',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'field_args' => 'min=0.0&max=1&step=0.1',
                                'validation'=> '',
                                'default'   => '1',
                                'help'      => esc_html__('change the header transparency (default: 1).', 'exc-uploader-theme')
                            );

// menu tabs settings status
$options['_config']['header_default']['tabs']['menu_settings']['header_menu_status'] = array(
                                'label'     => esc_html__('Status', 'exc-uploader-theme'),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'default'   => 'on',
                                'help'      => esc_html__('Show or hide the main menu from the header.', 'exc-uploader-theme')
                            );

// Menu Tab Text Color
$options['_config']['header_default']['tabs']['menu_settings']['header_menu_text_color'] = array(
                                'label'         => esc_html__('Text Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_default',
                                'css_selector'  => '.main-nav .menu > li > a',
                                'prop_name'     => 'color',

                                'validation'    => '',
                                'default'       => '#424242',
                                'help'          => esc_html__('Change the main menu text color (default: #424242).', 'exc-uploader-theme')
                            );

// Menu Tab Text Hover Color
$options['_config']['header_default']['tabs']['menu_settings']['header_menu_hover_color'] = array(
                                'label'         => esc_html__('Text Hover Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_default',
                                'css_selector'  => '.main-nav .menu li:hover > a, .main-nav > .menu > li > li.current-menu-item > a',
                                'prop_name'     => 'color',

                                'validation'    => '',
                                'default'       => '#e74c3c',
                                'help'          => esc_html__('Change the main menu text hover color (default: #e74c3c).', 'exc-uploader-theme')
                            );

// Menu Tab Padding Top
$options['_config']['header_default']['tabs']['menu_settings']['header_menu_padding_top'] = array(
                                'label'         => esc_html__('Padding Top', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'field_args'    => 'max=100&postfix=px',
                                'style_opt_key' => 'header_default',
                                'css_selector'  => '.main-nav .menu > li > a',
                                'prop_name'     => 'padding-top: %dpx',

                                'default'       => '38',
                                'validation'    => 'is_natural',
                                'help'          => esc_html__( 'Change the top padding of the main menu items (default: 38px).', 'exc-uploader-theme' )
                            );

$options['_config']['header_default']['tabs']['menu_settings']['header_menu_padding_right'] = array(
                                'label'         => esc_html__('Padding Right', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'field_args'    => 'max=100&postfix=px',
                                'style_opt_key' => 'header_default',
                                'css_selector'  => '.main-nav .menu > li > a',
                                'prop_name'     => 'padding-right: %dpx',
                                'default'       => '25',
                                'validation'    => 'is_natural',
                                'help'          => esc_html__( 'Change the right padding of the main menu items (default: 25px).', 'exc-uploader-theme' )
                            );

$options['_config']['header_default']['tabs']['menu_settings']['header_menu_padding_bottom'] = array(
                                'label'         => esc_html__('Padding Bottom', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'field_args'    => 'max=100&postfix=px',
                                'style_opt_key' => 'header_default',
                                'css_selector'  => '.main-nav .menu > li > a',
                                'prop_name'     => 'padding-bottom: %dpx',

                                'default'       => '38',
                                'validation'    => 'is_natural',
                                'help'          => esc_html__( 'Change the bottom padding of the main menu items (default: 38px).', 'exc-uploader-theme' )
                            );

$options['_config']['header_default']['tabs']['menu_settings']['header_menu_padding_left'] = array(
                                'label'         => esc_html__('Padding Left', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'field_args'    => 'max=100&postfix=px',
                                'style_opt_key' => 'header_default',
                                'css_selector'  => '.main-nav .menu > li > a',
                                'prop_name'     => 'padding-left: %dpx',
                                'default'       => '25',
                                'validation'    => 'is_natural',
                                'help'          => esc_html__( 'Change the left padding of the main menu items (default: 25px).', 'exc-uploader-theme' )
                            );

// Header Default Memer Controls Tabs Status Button
$options['_config']['header_default']['tabs']['member_controls']['header_member_status'] = array(
                                'label'     => esc_html__('Status', 'exc-uploader-theme'),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control',
                                                    'id'    => 'header_user_ctrl_status'
                                                ),

                                'default'   => 'on',
                                'help'      => sprintf(
                                                    __( 'Show or hide the user controls from header section. <br /><br /><i> <strong>Note: </strong> this option will work only if members status is active in %s</i>.', 'exc-uploader-theme' ),
                                                    '<a href="' . admin_url('themes.php?section=member_settings') . '">Member Settings</a>'
                                                )
                            );

$options['_config']['header_default']['tabs']['member_controls']['header_member_bg_color'] = array(
                                'label'         => __('Background Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_default',
                                'css_selector'  => '.welcome-btn .btn',
                                'prop_name'     => 'background-color',
                                //'append'      => 'header_member_bg_opacity',

                                'default'       => '#ffffff',
                                'validation'    => '',
                                'help'          => __( 'Change the background color of the member controls. (default: #ffffff)', 'exc-uploader-theme' )
                            );

$options['_config']['header_default']['tabs']['member_controls']['header_member_text_color'] = array(
                                'label'         => __('Text Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_default',
                                'css_selector'  => '.welcome-btn .btn',
                                'prop_name'     => 'color',

                                'default'       => '#424242',
                                'validation'    => '',
                                'help'          => __( 'Change the background color of the member controls. (default: #424242)', 'exc-uploader-theme' )
                            );

// $options['_config']['header_default']['tabs']['member_controls']['header_member_bg_opacity'] = array(
//                              'label'         => __('Background Transparency', 'exc-uploader-theme'),
//                              'type'          => 'range_slider',
//                              'attrs'         => array(
//                                                      'class' => 'form-control',
//                                                  ),

//                              'field_args'    => 'min=0.0&max=1&step=0.1',

//                              //'style_opt_key' => 'header_default',
//                              //'css_selector'    => '.login .login-user .btn-group > .btn',
//                              //'prop_name'       => 'opacity',
//                              'default'       => '0.2',
//                              'validation'    => '',
//                              'help'          => __( 'Change the transparency of the member controls. (default: 0.2)', 'exc-uploader-theme' )
//                          );

$options['_config']['header_default']['tabs']['info_bar_settings']['header_topbar_status'] = array(
                                'label'     => __( 'Status', 'exc-uploader-theme' ),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control',
                                                ),

                                'help'      => __( 'Show or hide the top information bar.', 'exc-uploader-theme' )
                            );

$options['_config']['header_default']['tabs']['info_bar_settings']['header_topbar_email_addr'] = array(
                                'label'     => __( 'Email Address', 'exc-uploader-theme' ),
                                'type'      => 'text',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'validation'    => 'valid_email',
                                'default'       => get_option( 'admin_email' ),
                                'help'          => __( 'Change the email address of top information bar.', 'exc-uploader-theme' )
                            );

$options['_config']['header_default']['tabs']['info_bar_settings']['header_topbar_phone'] = array(
                                'label'     => __( 'Phone Number', 'exc-uploader-theme' ),
                                'type'      => 'text',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'default'   => '+44 7777 110022',
                                'help'      => __( 'Change the phone number of top information bar.', 'exc-uploader-theme' )
                            );

$options['_config']['header_default']['tabs']['info_bar_settings']['header_topbar_social_icons'] = array(
                                'label'     => __( 'Social Links', 'exc-uploader-theme' ),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'default'   => 'on',
                                'help'      => sprintf(
                                                    __( 'Show or hide the Social media icons. <br /><br /><i><strong>Note: </strong>You can manage them in %s.</i>', 'exc-uploader-theme' ),
                                                    '<a href="' . admin_url('themes.php?section=social_media_settings') . '">Social Media Settings</a>'
                                                )
                            );

$options['_config']['header_default']['tabs']['info_bar_settings']['header_topbar_color'] = array(
                                'label'         => __('Text Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_default',
                                'css_selector'  => '.exc-infobar, .exc-infobar a',
                                'prop_name'     => 'color',
                                'default'       => '#ffffff',
                                'help'          => __( 'Change the information bar text color. (default: #ffffff)', 'exc-uploader-theme' )
                            );

$options['_config']['header_default']['tabs']['info_bar_settings']['header_topbar_bg_color'] = array(
                                'label'         => __('Background Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_default',
                                'css_selector'  => '.exc-infobar',
                                'prop_name'     => 'background-color: rgba(%1$d, %2$d, %3$d, %4$s)',
                                'append'        => 'header_topbar_bg_opacity',

                                'default'       => '#1b2126',
                                'validation'    => 'hex2rgb',
                                'help'          => __( 'Change the information bar background color. (default: #1b2126)', 'exc-uploader-theme' )
                            );

$options['_config']['header_default']['tabs']['info_bar_settings']['header_topbar_bg_opacity'] = array(
                                'label'         => __('Background Transparency', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control',
                                                        // 'data-slider-min'    => 0.00,
                                                        // 'data-slider-max'    => 1,
                                                        // 'data-slider-step'   => 0.01
                                                    ),

                                'field_args'    => 'min=0.0&max=1&step=0.1',

                                'default'       => '1',
                                'validation'    => '',
                                'help'          => __( 'Change the information bar transparency. (default: 1)', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['general_settings']['hc_bg_image'] = array(
                                'label'         => __('Background Image', 'exc-uploader-theme'),
                                'type'          => 'image',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'css_selector'  => '.header-bottom',
                                'prop_name'     => 'background-image: url(%s)',
                                'style_opt_key' => 'header_center',
                                'validation'    => 'valid_url',
                                'default'       => get_template_directory_uri() . '/images/header.jpg',
                                'help'          => __( 'Change the header background image.', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['general_settings']['hc_bg_color'] = array(
                                'label'         => __('Background Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'css_selector'  => '.logo-bar',
                                'prop_name'     => 'background-color: rgba(%1$d, %2$d, %3$d, %4$s)',
                                'style_opt_key' => 'header_center',
                                'append'        => 'hc_transparency',

                                'validation'    => 'hex2rgb',
                                'default'       => '#ffffff',
                                'help'          => __( 'Change the header background color (default: #ffffff).', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['general_settings']['hc_transparency'] = array(
                                'label'         => __('Transparency', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control',
                                                    ),

                                'field_args'    => 'min=0.0&max=1&step=0.1',

                                //'style_opt_key' => 'header_center',
                                //'css_selector'    => '.logo-bar',
                                //'prop_name'       => 'opacity',

                                'default'       => '1',
                                //'validation'  => '',
                                'help'          => __( 'Change the header transparency (default: 1).', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['menu_settings']['hc_menu_status'] = array(
                                'label'     => __( 'Status', 'exc-uploader-theme' ),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'default'   => 'on',
                                'help'      => __( 'Show or hide the main menu from the header.', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['menu_settings']['hc_menu_text_color'] = array(
                                'label'         => __('Text Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_center',
                                'css_selector'  => '.main-nav .menu > li > a',
                                'prop_name'     => 'color',

                                'default'       => '#424242',
                                'validation'    => '',
                                'help'          => __( 'Change the main menu text color (default: #424242).', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['menu_settings']['hc_menu_hover_color'] = array(
                                'label'         => __('Text Hover Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_center',
                                'css_selector'  => '.main-nav > .menu > li:hover > a, .main-nav > .menu > li.current-menu-item > a',
                                'prop_name'     => 'color',
                                'default'       => '#e74c3c',
                                'validation'    => '',
                                'help'          => __( 'Change the main menu text hover color (default: #e74c3c).', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['menu_settings']['hc_menu_padding_top'] = array(
                                'label'         => __('Padding Top', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'field_args'    => 'max=100&postfix=px',
                                'style_opt_key' => 'header_center',
                                'css_selector'  => '.exc-header-center .main-nav > .menu > li > a',
                                'prop_name'     => 'padding-top: %dpx',

                                'default'       => '18',
                                'validation'    => 'is_natural',
                                'help'          => __( 'Change the top padding of the main menu items (default: 18px).', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['menu_settings']['hc_menu_padding_right'] = array(
                                'label'         => __('Padding Right', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'field_args'    => 'max=100&postfix=px',
                                'style_opt_key' => 'header_center',
                                'css_selector'  => '.exc-header-center .main-nav > .menu > li > a',
                                'prop_name'     => 'padding-right: %dpx',
                                'default'       => '25',
                                'validation'    => 'is_natural',
                                'help'          => __( 'Change the right padding of the main menu items (default: 25px).', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['menu_settings']['hc_menu_padding_bottom'] = array(
                                'label'         => __('Padding Bottom', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'field_args'    => 'max=100&postfix=px',
                                'style_opt_key' => 'header_center',
                                'css_selector'  => '.exc-header-center .main-nav > .menu > li > a',
                                'prop_name'     => 'padding-bottom: %dpx',

                                'default'       => '18',
                                'validation'    => 'is_natural',
                                'help'          => __( 'Change the bottom padding of the main menu items (default: 18px).', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['menu_settings']['hc_menu_padding_left'] = array(
                                'label'         => __('Padding Left', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'field_args'    => 'max=100&postfix=px',
                                'style_opt_key' => 'header_center',
                                'css_selector'  => '.exc-header-center .main-nav > .menu > li > a',
                                'prop_name'     => 'padding-left: %dpx',
                                'default'       => '25',
                                'validation'    => 'is_natural',
                                'help'          => __( 'Change the left padding of the main menu items (default: 25px).', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['member_controls']['hc_member_status'] = array(
                                'label'     => __( 'Status', 'exc-uploader-theme' ),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control',
                                                    'id'    => 'header_user_ctrl_status'
                                                ),

                                'default'   => 'on',
                                'help'      => sprintf(
                                                    __( 'Show or hide the user controls from header section <br /><br /><i> <strong>Note: </strong> this option will work only if members status is active in %s</i>.', 'exc-uploader-theme' ),
                                                    '<a href="' . admin_url('themes.php?section=member_settings') . '">Member Settings</a>'
                                                )
                            );

$options['_config']['header_center']['tabs']['member_controls']['hc_member_bg_color'] = array(
                                'label'         => __('Background Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_center',
                                'css_selector'  => '.welcome-btn .btn',
                                'prop_name'     => 'background-color',
                                //'append'      => 'hc_member_bg_opacity',

                                'default'       => '#ffffff',
                                'validation'    => '',
                                'help'          => __( 'Change the background color of the member controls. (default: #ffffff)', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['member_controls']['hc_member_text_color'] = array(
                                'label'         => __('Text Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_center',
                                'css_selector'  => '.welcome-btn .btn',
                                'prop_name'     => 'color',

                                'default'       => '#424242',
                                'validation'    => '',
                                'help'          => __( 'Change the background color of the member controls. (default: #424242)', 'exc-uploader-theme' )
                            );

// $options['_config']['header_center']['tabs']['member_controls']['hc_member_bg_opacity'] = array(
//                              'label'         => __('Background Transparency', 'exc-uploader-theme'),
//                              'type'          => 'range_slider',
//                              'attrs'         => array(
//                                                      'class' => 'form-control',
//                                                      'data-slider-min'   => 0.00,
//                                                      'data-slider-max'   => 1,
//                                                      'data-slider-step'  => 0.01
//                                                  ),

//                              'field_args'    => 'min=0.0&max=1&step=0.1',

//                              //'style_opt_key' => 'header_center',
//                              //'css_selector'    => '.login .login-user .btn-group > .btn',
//                              //'prop_name'       => 'opacity',
//                              'default'       => '0.2',
//                              'validation'    => '',
//                              'help'          => __( 'Change the transparency of the member controls. (default: 0.2)', 'exc-uploader-theme' )
//                          );

$options['_config']['header_center']['tabs']['info_bar_settings']['hc_topbar_status'] = array(
                                'label'     => __( 'Status', 'exc-uploader-theme' ),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control',
                                                ),

                                'help'      => __( 'Show or hide the top info bar.', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['info_bar_settings']['hc_topbar_email_addr'] = array(
                                'label'     => __( 'Email Address', 'exc-uploader-theme' ),
                                'type'      => 'text',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'validation'    => 'valid_email',
                                'default'       => get_option( 'admin_email' ),
                                'help'          => __( 'Change the email address from top information bar.', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['info_bar_settings']['hc_topbar_phone'] = array(
                                'label'     => __( 'Phone Number', 'exc-uploader-theme' ),
                                'type'      => 'text',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'default'   => '+44 7777 110022',
                                'help'      => __( 'Change the phone number from top information bar.', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['info_bar_settings']['hc_topbar_social_icons'] = array(
                                'label'     => __( 'Social Links', 'exc-uploader-theme' ),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'default'   => 'on',
                                'help'      => sprintf(
                                                    __( 'Show or hide the Social media icons. <br /><br /><i><strong>Note: </strong>You can manage them in %s.</i>', 'exc-uploader-theme' ),
                                                    '<a href="' . admin_url('themes.php?section=social_media_settings') . '">Social Media Settings</a>'
                                                )
                            );

$options['_config']['header_center']['tabs']['info_bar_settings']['hc_topbar_color'] = array(
                                'label'         => __('Text Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_center',
                                'css_selector'  => '.exc-header-center .exc-infobar, .exc-header-center .exc-infobar a',
                                'prop_name'     => 'color',
                                'default'       => '#ffffff',
                                'help'          => __( 'Change the information bar text color. (default: #ffffff)', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['info_bar_settings']['hc_topbar_bg_color'] = array(
                                'label'         => __('Background Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_center',
                                'css_selector'  => '.exc-header-center .exc-infobar',
                                'prop_name'     => 'background-color: rgba(%1$d, %2$d, %3$d, %4$s)',
                                'append'        => 'hc_topbar_bg_opacity',
                                'default'       => '#1b2126',
                                'validation'    => 'hex2rgb',
                                'help'          => __( 'Change the information bar background color. (default: #1b2126)', 'exc-uploader-theme' )
                            );

$options['_config']['header_center']['tabs']['info_bar_settings']['hc_topbar_bg_opacity'] = array(
                                'label'         => __('Background Transparency', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control',
                                                    ),

                                'field_args'    => 'min=0.0&max=1&step=0.1',

                                //'style_opt_key' => 'header_center',
                                //'css_selector'    => '.login .login-user .btn-group > .btn',
                                //'prop_name'       => 'opacity',
                                'default'       => '1',
                                'validation'    => '',
                                'help'          => __( 'Change the information bar transparency. (default: 1)', 'exc-uploader-theme' )
                            );

$options['_config']['header_classic']['tabs']['general_settings']['hcl_bg_image'] = array(
                                'label'         => __('Background Image', 'exc-uploader-theme'),
                                'type'          => 'image',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'css_selector'  => '.header-bottom',
                                'prop_name'     => 'background-image: url(%s)',
                                'style_opt_key' => 'header_classic',
                                'validation'    => 'valid_url',
                                'default'       => get_template_directory_uri() . '/images/header.jpg',
                                'help'          => __( 'Change the header background image.', 'exc-uploader-theme' )
                            );

$options['_config']['header_classic']['tabs']['general_settings']['hcl_bg_color'] = array(
                                'label'         => __('Background Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'css_selector'  => '.logo-bar',
                                'prop_name'     => 'background-color: rgba(%1$d, %2$d, %3$d, %4$s)',
                                'style_opt_key' => 'header_classic',
                                'append'        => 'hcl_transparency',
                                'validation'    => 'hex2rgb',
                                'default'       => '#ffffff',
                                'help'          => __( 'Change the header background color (default: #ffffff).', 'exc-uploader-theme' )
                            );

$options['_config']['header_classic']['tabs']['general_settings']['hcl_transparency'] = array(
                                'label'         => __('Transparency', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control',

                                                    ),

                                'field_args'    => 'min=0.0&max=1&step=0.1',

                                //'style_opt_key' => 'header_classic',
                                //'css_selector'    => '.logo-bar',
                                //'prop_name'       => 'opacity',

                                'default'       => '1',
                                //'validation'  => '',
                                'help'          => __( 'Change the header transparency (default: 1).', 'exc-uploader-theme' )
                            );

$options['_config']['header_classic']['tabs']['menu_settings']['hcl_menu_status'] = array(
                                'label'     => __( 'Status', 'exc-uploader-theme' ),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),
                                'default'   => 'on',
                                'help'      => __( 'Show or hide the main menu from the header.', 'exc-uploader-theme' )
                            );

$options['_config']['header_classic']['tabs']['menu_settings']['hcl_menu_text_color'] = array(
                                'label'         => __('Text Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_classic',
                                'css_selector'  => '.main-nav .menu > li > a, .exc-contact-info',
                                'prop_name'     => 'color',

                                'default'       => '#424242',
                                'validation'    => '',
                                'help'          => __( 'Change the main menu text color (default: #424242).', 'exc-uploader-theme' )
                            );

$options['_config']['header_classic']['tabs']['menu_settings']['hcl_menu_text_hove_color'] = array(
                                'label'         => __('Text Hover Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_classic',
                                'css_selector'  => '.main-nav > .menu > li:hover > a, .main-nav > .menu > li.current-menu-item > a',
                                'prop_name'     => 'color',
                                'default'       => '#e74c3c',
                                'validation'    => '',
                                'help'          => __( 'Change the main menu text hover color (default: #e74c3c).', 'exc-uploader-theme' )
                            );

$options['_config']['header_classic']['tabs']['menu_settings']['hcl_menu_padding_top'] = array(
                                'label'         => __('Padding Top', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'field_args'    => 'max=100&postfix=px',
                                'style_opt_key' => 'header_classic',
                                'css_selector'  => '.exc-header-3 .main-nav > .menu > li > a',
                                'prop_name'     => 'padding-top: %dpx',

                                'default'       => '18',
                                'validation'    => 'is_natural',
                                'help'          => __( 'Change the top padding of the main menu items (default: 18px).', 'exc-uploader-theme' )
                            );

$options['_config']['header_classic']['tabs']['menu_settings']['hcl_menu_padding_right'] = array(
                                'label'         => __('Padding Right', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'field_args'    => 'max=100&postfix=px',
                                'style_opt_key' => 'header_classic',
                                'css_selector'  => '.exc-header-3 .main-nav > .menu > li > a',
                                'prop_name'     => 'padding-right: %dpx',
                                'default'       => '25',
                                'validation'    => 'is_natural',
                                'help'          => __( 'Change the right padding of the main menu items (default: 25px).', 'exc-uploader-theme' )
                            );

$options['_config']['header_classic']['tabs']['menu_settings']['hcl_menu_padding_bottom'] = array(
                                'label'         => __('Padding Bottom', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'field_args'    => 'max=100&postfix=px',
                                'style_opt_key' => 'header_classic',
                                'css_selector'  => '.exc-header-3 .main-nav > .menu > li > a',
                                'prop_name'     => 'padding-bottom: %dpx',

                                'default'       => '18',
                                'validation'    => 'is_natural',
                                'help'          => __( 'Change the bottom padding of the main menu items (default: 18px).', 'exc-uploader-theme' )
                            );

$options['_config']['header_classic']['tabs']['menu_settings']['hcl_menu_padding_left'] = array(
                                'label'         => __('Padding Left', 'exc-uploader-theme'),
                                'type'          => 'range_slider',
                                'attrs'         => array(
                                                        'class' => 'form-control'

                                                    ),
                                'field_args' => 'max=100&postfix=px',
                                'style_opt_key' => 'header_classic',
                                'css_selector'  => '.exc-header-3 .main-nav > .menu > li > a',
                                'prop_name'     => 'padding-left: %dpx',
                                'default'       => '25',
                                'validation'    => 'is_natural',
                                'help'          => __( 'Change the left padding of the main menu items (default: 25px).', 'exc-uploader-theme' )
                            );

$options['_config']['header_classic']['tabs']['member_controls']['hcl_member_status'] = array(
                                'label'     => __( 'Status', 'exc-uploader-theme' ),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control',
                                                    'id'    => 'header_user_ctrl_status'
                                                ),

                                'default'   => 'on',
                                'help'      => sprintf(
                                                    __( 'Show or hide the user controls from header section <br /><br /><i> <strong>Note: </strong> this option will work only if members status is active in %s</i>.', 'exc-uploader-theme' ),
                                                    '<a href="' . admin_url('themes.php?section=member_settings') . '">Member Settings</a>'
                                                )
                            );

$options['_config']['header_classic']['tabs']['member_controls']['hcl_member_bg_color'] = array(
                                'label'         => __('Background Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_classic',
                                'css_selector'  => '.welcome-btn .btn',
                                'prop_name'     => 'background-color',
                                //'append'      => 'hcl_member_bg_opacity',

                                'default'       => '#ffffff',
                                'validation'    => '',
                                'help'          => __( 'Change the member controls background color. (default: #ffffff)', 'exc-uploader-theme' )
                            );

$options['_config']['header_classic']['tabs']['member_controls']['hcl_member_text_color'] = array(
                                'label'         => __('Text Color', 'exc-uploader-theme'),
                                'type'          => 'colorpicker',
                                'attrs'         => array(
                                                        'class' => 'form-control'
                                                    ),

                                'style_opt_key' => 'header_classic',
                                'css_selector'  => '.welcome-btn .btn',
                                'prop_name'     => 'color',
                                'default'       => '#424242',
                                'validation'    => '',
                                'help'          => __( 'Change the member controls text color. (default: #424242)', 'exc-uploader-theme' )
                            );

$options['_config']['header_classic']['tabs']['contact_details']['hcl_contact_status'] = array(
                                'label'     => __( 'Status', 'exc-uploader-theme' ),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control',
                                                ),

                                'default'   => 'on',
                                'help'      => __( 'Show or hide the contact details.', 'exc-uploader-theme' )
                            );

$options['_config']['header_classic']['tabs']['contact_details']['hcl_contact_email_addr'] = array(
                                'label'     => __( 'Email Address', 'exc-uploader-theme' ),
                                'type'      => 'text',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'validation'    => 'valid_email',
                                'default'       => get_option( 'admin_email' ),
                                'help'          => __( 'Show or hide the email address.', 'exc-uploader-theme' )
                            );

$options['_config']['header_classic']['tabs']['contact_details']['hcl_contact_phone'] = array(
                                'label'     => __( 'Phone Number', 'exc-uploader-theme' ),
                                'type'      => 'text',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'default'   => '+44 7777 110022',
                                'help'      => __( 'Show or hide the phone number.', 'exc-uploader-theme' )
                            );