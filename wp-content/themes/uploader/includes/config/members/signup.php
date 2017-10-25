<?php defined('ABSPATH') OR die('restricted access');

//SUB, DYNAMIC
$options = array(
                '_layout' => 'members/signup',
                '_capabilities' => ''
            );

$options['_config']['user_email'] = array(
                                'label' => __('Email Address', 'exc-uploader-theme'),
                                'type'  => 'email',
                                'attrs' => array(
                                            'class' => 'form-control',
                                            'placeholder' => __('Email Address', 'exc-uploader-theme')
                                    ),

                                'layout' => 'members/text',
                                'validation' => 'required|valid_email',
                                );

$options['_config']['first_name'] = array(
                                'label' => __('First name', 'exc-uploader-theme'),
                                'type'  => 'text',
                                'attrs' => array(
                                            'class' => 'form-control',
                                            'placeholder' => __('First name', 'exc-uploader-theme')
                                    ),

                                'validation' => 'required|min_length[1]',
                                'layout' => 'members/text',
                                );

$options['_config']['last_name'] = array(
                                'label' => __('Last name', 'exc-uploader-theme'),
                                'type'  => 'text',
                                'attrs' => array(
                                            'class' => 'form-control',
                                            'placeholder' => __('Last name', 'exc-uploader-theme')
                                    ),

                                'validation' => 'required',
                                'layout' => 'members/text',
                                );

$options['_config']['user_login'] = array(
                                'label' => __('Username', 'exc-uploader-theme'),
                                'type'  => 'text',
                                'attrs' => array(
                                            'class' => 'form-control',
                                            'placeholder' => __('Username', 'exc-uploader-theme')
                                    ),

                                'validation' => 'required|min_length[4]',
                                'layout' => 'members/text',
                                );

$options['_config']['user_pass'] = array(
                                'label' => __('Password', 'exc-uploader-theme'),
                                'type'  => 'password',
                                'attrs' => array(
                                            'class' => 'form-control',
                                            'placeholder' => __('Password', 'exc-uploader-theme')
                                    ),

                                'validation' => 'required|min_length[6]',
                                'layout' => 'members/text',
                                );