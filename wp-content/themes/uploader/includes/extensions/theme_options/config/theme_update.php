<?php defined('ABSPATH') OR die('restricted access');

$this->html->load_css_args( 'exc-theme-upgrader', $this->get_file_url('extensions/theme_options/views/css/upgrade.css', 'local_dir'), array( 'theme-options-style' ) );

$options = array(
				'title'		=> esc_html__('Automatic Theme Update', 'exc-autolove'),
				'db_name'	=> 'exc_buyer_api_key',
				'action'	=> 'exc_buyer_api_key',
				'_layout'	=> 'settings',
				//'_layout'	=> 'demo_importer/index'
			);

// Fetch the saved data
$api_settings = exc_get_option( 'exc_buyer_api_key' );

if ( empty( $api_settings['api_settings']['user_name'] )
		|| empty( $api_settings['api_settings']['api_key'] )
		|| isset( $_GET['update-api-settings'] ) )
{
	$options['_config']['block']['_settings'] =
							array(
								'api_settings' =>
									array(
										'heading'		=> esc_html__('Envato API settings', 'exc-autolove'),
										'description'	=> esc_html__('Enter the themeforest API settings.', 'exc-autolove'),
									),
							);

$options['_config']['block']['api_settings']['user_name'] = array(
								'label'		=> esc_html__('User Name', 'exc-autolove'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'required',
								'default'	=> '',
								'help'		=> esc_html__('Enter your envato username.', 'exc-autolove')
							);

$options['_config']['block']['api_settings']['api_key'] = array(
								'label'		=> esc_html__('API Key', 'exc-autolove'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'required', //|exc_verify_envato_key
								'default'	=> '',
								'help'		=> esc_html__('Enter your envato account API key.', 'exc-autolove')
							);
} else 
{
	$options['_layout'] = str_replace( '.php', '', $this->extension( 'theme_options' )->exc()->get_file_path( 'views/theme-upgrade' ) );

	$this->clear_query();
}