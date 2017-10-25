<?php defined('ABSPATH') OR die('restricted access');

$options = array(
            'title'         => __( 'Auto Update', 'exc-player-plugin' ),
            'db_name'       => 'exc_auto_update',
            'action'        => 'exc_auto_update',
            //'_layout'       => 'theme_options/index',
            '_active_form' => ''
    );

$options['_config']['block']['_settings'] =
            array(
                'options' => array(
                    			'heading' 		=> __('Envato Information', 'exc-player-plugin'),
                    			'description' 	=> __('This section for auto update extension', 'exc-player-plugin'),
                			),
            );

$options['_config']['block']['options']['envato_username'] = array(
								'label'		=> esc_html__('Envato Username', 'exc-player-plugin'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> '',
								'default'	=> '',
								'help'		=> esc_html__('Enter the Envato Username.', 'exc-player-plugin')
							);

$options['_config']['block']['options']['envato_api'] = array(
								'label'		=> esc_html__('Envato Api Key', 'exc-player-plugin'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> '',
								'default'	=> '',
								'help'		=> esc_html__('Enter the Envato Api Key.', 'exc-player-plugin')
							);
