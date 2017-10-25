<?php if ( ! defined('ABSPATH') ) exit('restricted access');

$html_layout = '<div class="form-group">
					<div class="col-sm-6">{markup} {error}</div>
					<span class="col-sm-6 help-block">{help}</span>
				</div>';

$options = array(
				'_layout'		=> 'widget',
				'_capabilities' => '',
			);

if( ! is_user_logged_in() )
{
	$options['_config']['name'] = array(
									'label' => _x('Name', 'extracoding uploader contact us name field', 'exc-uploader-theme'),
									'type'	=> 'text',
									'attrs' => array(
												'class' => 'form-control',
												'placeholder' => _x('Name', 'extracoding uploader contact us name field', 'exc-uploader-theme'),
										),
	
									'validation' => 'required|esc_html|stripslashes',
									'html' => $html_layout,
									'help' => __('*Required.', 'exc-uploader-theme'),
								);
	
	$options['_config']['email'] = array(
									'label' => _x('Email Address', 'extracoding uploader contact us email field', 'exc-uploader-theme'),
									'type'	=> 'email',
									'attrs' => array(
												'class' => 'form-control',
												'placeholder' => _x('Email Address', 'extracoding uploader contact us email field', 'exc-uploader-theme'),
										),
	
									'validation' => 'required|valid_email',
									'html' => $html_layout,
                                    'help' => __('*Required (We will never publish it publicly).', 'exc-uploader-theme'),
								);
}

$options['_config']['subject'] = array(
								'label' => _x('Subject', 'extracoding uploader contact us email field', 'exc-uploader-theme'),
								'type'	=> 'text',
								'attrs' => array(
											'class' => 'form-control',
											'placeholder' => _x('Subject', 'extracoding uploader contact us email field', 'exc-uploader-theme'),
									),

								'validation' => 'required|esc_html|stripslashes',
								'html' => $html_layout
							);

$options['_config']['message'] = array(
								'label' => _x('Message', 'extracoding uploader contact us email field', 'exc-uploader-theme'),
								'type'	=> 'textarea',
								'attrs' => array(
											'class' => 'form-control',
											'rows' => 6
									),

								'validation' => 'required|esc_html|stripslashes',
								'html' => '<div class="form-group"><div class="col-sm-12">{markup} {error}</div></div>',
							);