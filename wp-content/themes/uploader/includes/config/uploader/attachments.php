<?php defined('ABSPATH') or die('restricted access.');

$options = array(
				'db_name'		=> 'mf_layout',
				'_layout'		=> 'uploader/form',
				'_capabilities' => ''
			);

$options['_config']['attachment_title'] = array(
										'label'			=> __('Attachment Caption', 'exc-uploader-theme'),
										'type'			=> 'text',
										'attrs'			=> array(
																'class'					=> 'form-control',
																'placeholder'			=> esc_html__('Enter Caption', 'exc-uploader-theme')
															),

										'validation'	=> 'wp_strip_all_tags|stripslashes'
									);

$options['_config']['attachment_source'] = array(
										'label'			=> __('Attachment Source', 'exc-uploader-theme'),
										'type'			=> 'text',
										'attrs'			=> array(
																'class'			=> 'form-control',
																'placeholder'	=> esc_html__('Enter Source', 'exc-uploader-theme')
															),

										'validation'	=> 'wp_strip_all_tags'
									);

$options['_config']['attachment_content'] = array(
										'label'			=> __('Attachment Content', 'exc-uploader-theme'),
										'type'			=> 'textarea',
										'attrs'			=> array(
																'class'			=> 'form-control',
																'placeholder'	=> esc_html__('Enter Content', 'exc-uploader-theme')
															),

										//'validation'	=> 'wp_strip_all_tags|stripslashes|strip_shortcodes|wp_rel_nofollow|force_balance_tags',
										'validation'	=> 'wp_strip_all_tags|stripslashes|strip_shortcodes'
									);

// For admin view
if ( ! is_admin() )
{
	foreach ( $options['_config'] as $config_key => $config_field )
	{
		$options['_config'][ $config_key ]['html'] = '{markup}';
		$options['_config'][ $config_key ]['name'] = '{{{ attachment.id }}}_' . $config_key;
		//$options['_config'][ $config_key ]['attrs']['id'] = '{{{ attachment.id }}}_' . $config_key;
		$options['_config'][ $config_key ]['default'] = '{{{ attachment.' . $config_key . ' }}}';
	}
}