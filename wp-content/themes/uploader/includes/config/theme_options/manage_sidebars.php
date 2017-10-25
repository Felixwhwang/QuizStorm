<?php defined('ABSPATH') OR die('restricted access');

$this->html->load_js_args( 'exc-dynamic-fields', $this->system_url('views/js/fields/dynamic-fields.js'), array('jquery-ui-sortable') );

$options = array(
                'title'     => esc_html__('Mange Sidebars', 'exc-uploader-theme'),
                'db_name'   => 'mf_sidebars',
            );

$options['_config']['_settings'] =
                        array(
                            'settings' =>
                                array(
                                    'heading'       => esc_html__('Sidebars Manager', 'exc-uploader-theme'),
                                    'description'   => sprintf(
                                                            __('Extracoding Uploader Theme has the advanced functionality to add as many sidebars as required and they will be immediately visible in the sections where they are required e.g. widget area, layout settings and so on. You can further read on Wordpress codex that how programmatically the register_sidebar function works %s. <p><i><strong>Note: </strong>Some of the sidebars e.g. "Home Page Right Sidebar" are built-in and you cannot change their settings here.</i></p>', 'exc-uploader-theme' ),
                                                            '<a href="http://codex.wordpress.org/Function_Reference/register_sidebar" target="_blank">Read more</a>'
                                                        )
                                ),
                        );

$options['_config']['settings']['dynamic']['_settings']['sidebars'] = array(

                                'toolbar'   => array( 'delete' )
                            );

$options['_config']['settings']['dynamic']['sidebars']['name'] = array(
                                'label'         => __('Title / Name', 'exc-uploader-theme'),
                                'type'          => 'text',
                                'attrs'         => array(
                                                    'class' => 'form-control',
                                                    'data-primary_field' => true
                                                    ),

                                'validation'    => 'required|min_length[3]|stripslashes',
                                'help'          => __('Enter the title of this sidebar.', 'exc-uploader-theme'),
                            );

$options['_config']['settings']['dynamic']['sidebars']['id'] = array(
                                'label'         => __('Slug / id', 'exc-uploader-theme'),
                                'type'          => 'text',
                                'attrs'         => array(
                                                    'class' => 'form-control',
                                                    ),

                                'validation'    => 'alpha_dash|min_length[8]|strtolower|stripslashes',
                                'help'          => __('Enter the unqiue id of this sidebar, it must be all in lowercase, and use dashes instead of spaces.', 'exc-uploader-theme'),
                            );

$options['_config']['settings']['dynamic']['sidebars']['description'] = array(
                                'label'         => __('Description', 'exc-uploader-theme'),
                                'type'          => 'textarea',
                                'attrs'         => array(
                                                        'class' => 'form-control',
                                                    ),

                                'validation'    => 'stripslashes',
                                'help'          => __('Enter the text description about this sidebar, it will be visible in widgets section.', 'exc-uploader-theme'),
                            );

$options['_config']['settings']['dynamic']['sidebars']['before_title'] = array(
                                'label'         => __('Before Title', 'exc-uploader-theme'),
                                'type'          => 'textarea',
                                'attrs'         => array(
                                                        'class' => 'form-control',
                                                    ),

                                'default'       => '<div class="header"><h3>',
                                'validation'    => 'stripslashes',
                                'help'          => __('HTML to place before every widget of this sidebar.', 'exc-uploader-theme'),
                            );

$options['_config']['settings']['dynamic']['sidebars']['after_title'] = array(
                                'label'         => __('After Title', 'exc-uploader-theme'),
                                'type'          => 'textarea',
                                'attrs'         => array(
                                                        'class' => 'form-control',
                                                    ),

                                'default'       => '</h3></div>',
                                'validation'    => 'stripslashes',
                                'help'          => __('HTML to place after every widget of this sidebar.', 'exc-uploader-theme'),
                            );

$options['_config']['settings']['dynamic']['sidebars']['before_widget'] = array(
                                'label'         => __('Before Widget', 'exc-uploader-theme'),
                                'type'          => 'textarea',
                                'attrs'         => array(
                                                        'class' => 'form-control',
                                                    ),

                                'default'       => '<div id="%1$s" class="widget %2$s">',
                                'validation'    => 'stripslashes',
                                'help'          => __('HTML to place before every title of widgets visible into this sidebar.', 'exc-uploader-theme'),
                            );

$options['_config']['settings']['dynamic']['sidebars']['after_widget'] = array(
                                'label'         => __('After Widget', 'exc-uploader-theme'),
                                'type'          => 'textarea',
                                'attrs'         => array(
                                                        'class' => 'form-control',
                                                    ),

                                'default'       => '</div>',
                                'validation'    => 'stripslashes',
                                'help'          => __('HTML to place after every title of widgets visible into this sidebar.', 'exc-uploader-theme'),
                            );