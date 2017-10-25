<?php defined('ABSPATH') OR die('restricted access');

$config = array(
	'user_first_name'	=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'	=> 'text',
			'name'	=> 'first_name',
			'pk'	=> 'user_first_name',
			),

		'validation'	=> 'required|alpha_space|min_length[3]|max_length[30]',
		),

	'user_last_name'		=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'	=> 'text',
			'name'	=> 'last_name',
			'pk'	=> 'user_last_name',
			),

		'validation'	=> 'required|alpha_space|min_length[1]|max_length[30]'
		),


	
	'user_dob'		=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'		=> 'combodate',
			'name'		=> 'user_dob',
			'format'	=> 'DD/MM/YYYY',
			'viewformat'=> 'DD/MM/YYYY',
			'template'	=> 'DD/MM/YYYY', 
			
			'combodate'	=> array(
							'minYear'	=> 1940,
							'maxYear'	=> 2004,
							'minuteStep'=> 1,
					),

			'placement'	=> 'bottom',
			'pk'		=> 'user_gender',
			),

		'validation'	=> 'required|wp_strip_all_tags'
		),

	'user_gender'		=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'		=> 'select',
			'name'		=> 'user_gender',
			
			'prepend'	=> __( 'Select Gender', 'exc-uploader-theme' ),
			'source'	=> array(
								array( 'value' => 'male', 'text' => 'Male' ),
								array( 'value' => 'female', 'text' => 'Female' )
							),

			'pk'		=> 'user_gender',
			),

		'validation'	=> 'required|wp_strip_all_tags'
		),

	'user_about_us'	=> array(
		'section'		=> 'user_data',
		'editable'		=> array(
			'type'		=> 'textarea',
			'name'		=> 'description',
			'pk'		=> 'user_about_us',
			),

		'validation'	=> 'required|min_length[80]|prep_for_form',
		),

	'user_email'			=> array(
		'section'		=> 'user_data',
		'editable'		=> array(
			'type'		=> 'email',
			'name'		=> 'user_email',
			'pk'		=> 'user_email',
			),

		'validation'	=> 'required|valid_email',
		),

	'user_notify_email'	=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'		=> 'email',
			'name'		=> 'notification_email',
			'pk'		=> 'user_notify_email',
			),

		'validation'	=> 'valid_email',
		),

	'user_address'		=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'		=> 'textarea',
			'name'		=> 'address',
			'pk'		=> 'user_address',
			),

		'validation'	=> 'wp_strip_all_tags|stripslashes',
		),

	'user_password'		=> array(
		'section'		=> 'user_pass',
		'editable'		=> array(
			'type'		=> 'password',
			'name'		=> 'user_pass',
			'pk'		=> 'user_password',
			),

		'validation'	=> 'required|min_length[6]|max_length[30]|wp_strip_all_tags|stripslashes',
		),

	'user_public_email'	=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'		=> 'email',
			'name'		=> 'public_email',
			'pk'		=> 'user_public_email',
			),

		'validation'	=> 'valid_email',
		),

	'user_skype_id'		=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'		=> 'text',
			'name'		=> 'skype_id',
			'pk'		=> 'user_skype_id',
			),

		'validation'	=> 'wp_strip_all_tags|stripslashes',
		),

	'user_facebook'		=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'		=> 'url',
			'name'		=> 'facebook',
			'pk'		=> 'user_facebook',
			),

		'validation'	=> 'valid_url',
		),

	'user_twitter'		=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'		=> 'url',
			'name'		=> 'twitter',
			'pk'		=> 'user_twitter',
			),

		'validation'	=> 'valid_url',
		),

	'user_google-plus'			=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'		=> 'url',
			'name'		=> 'google-plus',
			'pk'		=> 'user_google-plus',
			),

		'validation'	=> 'valid_url',
		),

	'user_instagram'		=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'		=> 'url',
			'name'		=> 'instagram',
			'pk'		=> 'user_instagram',
			),

		'validation'	=> 'valid_url',
		),

	'user_youtube'		=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'		=> 'url',
			'name'		=> 'youtube',
			'pk'		=> 'user_youtube',
			),

		'validation'	=> 'valid_url',
		),

	'user_vimeo'			=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'		=> 'url',
			'name'		=> 'vimeo',
			'pk'		=> 'user_vimeo',
			),

		'validation'	=> 'valid_url',
		),

	'user_soundcloud'	=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'		=> 'url',
			'name'		=> 'soundcloud',
			'pk'		=> 'user_soundcloud',
			),

		'validation'	=> 'valid_url',
		),

	'user_mixcloud'		=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'		=> 'url',
			'name'		=> 'mixcould',
			'pk'		=> 'user_mixcloud',
			),

		'validation'	=> 'valid_url',
		),

	'user_flickr'		=> array(
		'section'		=> 'user_meta',
		'editable'		=> array(
			'type'		=> 'url',
			'name'		=> 'flickr',
			'pk'		=> 'user_flickr',
			),

		'validation'	=> 'valid_url',
		),

	);