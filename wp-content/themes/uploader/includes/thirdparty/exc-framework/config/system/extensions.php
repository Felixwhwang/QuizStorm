<?php defined('ABSPATH') OR die('restricted access');

$current_directory_path = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;

$options = array(
                'pages'     => array(),
                //'baseurl'   => admin_url( 'admin.php?page=' . $GLOBALS['plugin_page'] ),
               '_layout'   => 'pages/index'
            );

$options['pages']['general_settings']   = $current_directory_path . 'auto_update';
$options['pages']['test']               = $current_directory_path . 'test';
$options['pages']['header_settings']    = 'theme_options/header_settings';
$options['pages']['member_settings']    = 'theme_options/member_settings';
$options['pages']['uploader_settings']  = 'theme_options/uploader_settings';
$options['pages']['layout_settings']    = 'theme_options/layout_settings';
$options['pages']['manage_sidebars']    = 'theme_options/manage_sidebars';
// $options['pages']['style_settings']     = 'theme_options/style_settings';

// $options = array(
//             'title'         => __( 'Auto Update', 'exc-player-plugin' ),
//             'db_name'       => 'exc_auto_update',
//             'action'        => 'exc-framework',
//             '_layout'       => 'settings',
//             '_active_form' => ''
//     );

// $options['_config']['block']['_settings'] =
//             array(
//                 'options' => array(
//                              'heading'       => __('Envato Information', 'exc-player-plugin'),
//                              'description'   => __('This section for auto update extension', 'exc-player-plugin'),
//                          ),
//             );

// $options['_config']['block']['options']['envato_username'] = array(
//                              'label'     => esc_html__('Envato Username', 'exc-player-plugin'),
//                              'type'      => 'text',
//                              'attrs'     => array(
//                                                  'class' => 'form-control'
//                                              ),
                                    
//                              'validation'=> '',
//                              'default'   => '',
//                              'help'      => esc_html__('Enter the Envato Username.', 'exc-player-plugin')
//                          );

// $options['_config']['block']['options']['envato_api'] = array(
//                              'label'     => esc_html__('Envato Api Key', 'exc-player-plugin'),
//                              'type'      => 'text',
//                              'attrs'     => array(
//                                                  'class' => 'form-control'
//                                              ),
                                    
//                              'validation'=> '',
//                              'default'   => '',
//                              'help'      => esc_html__('Enter the Envato Api Key.', 'exc-player-plugin')
//                          );
