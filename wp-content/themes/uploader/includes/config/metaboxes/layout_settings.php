<?php defined('ABSPATH') OR die('restricted access');

$this->html->load_js_args( 'exc-dynamic-fields', $this->system_url('views/js/fields/dynamic-fields.js'), array('jquery-ui-sortable') );

$typenow = exc_kv( $GLOBALS, 'typenow' );

//SUB, DYNAMIC
$options = array(
                '_layout' => 'metabox',
                'db_name' => 'mf_layout',
            );

$options['_config']['_settings'] =
                            array(
                                'layout' => array(
                                    //'heading'     => __('Additional Settings', 'exc-uploader-theme'),
                                    'description'   => __('This section helps you to manage additional settings related to this post.', 'exc-uploader-theme'),
                                    'show_controls' => false,
                                ),
                            );

$options['_config']['layout']['tabs']['attachments']['dynamic']['_settings'] = array(
                    'post_attachments' => array(
                                            'toolbar' => array( 'delete', 'move', 'settings' ),
                                            )
                );

$options['_config']['layout']['tabs']['attachments']['dynamic']['post_attachments']['attachment_type'] = array(
                                    'label'     => esc_html__('Attachment Type', 'exc-uploader-theme'),
                                    'type'      => 'clickable',
                                    'options'   => array(
                                                        'attachment' =>
                                                            array(
                                                                'label'                 => esc_html__('Attach File', 'exc-uploader-theme'),
                                                                'data-id'               => 'attachment',
                                                                'data-mime-types'       => 'image, audio, video',
                                                                'data-upload-limit'     => 1,
                                                                'data-linked_fields'    => '{{{ i }}}_attachment_source'
                                                            ),

                                                        'audio_playlist' =>
                                                            array(
                                                                'label'                 => esc_html__('Audio Playlist', 'exc-uploader-theme'),
                                                                'data-id'               => 'audio_playlist',
                                                                'data-mime-types'       => 'audio',
                                                                'data-upload-limit'     => 100,
                                                                'data-linked_fields'    => ''
                                                            ),

                                                        'video_playlist' =>
                                                            array(
                                                                'label'                 => esc_html__('Video Playlist', 'exc-uploader-theme'),
                                                                'data-id'               => 'video_playlist',
                                                                'data-mime-types'       => 'video',
                                                                'data-upload-limit'     => 100,
                                                                'data-linked_fields'    => ''
                                                            ),
                                                    ),

                                    'attrs'     => array(
                                                        'data-max_limit' => 1,
                                                    ),

                                    'option_skin'=> 'withalpha',
                                    'validation'=> 'required',
                                    'default'   => 'attachment',
                                    'help'      => esc_html__('Select a type for this attachment.', 'exc-uploader-theme')
                                );

$options['_config']['layout']['tabs']['attachments']['dynamic']['post_attachments']['attachment_title'] = array(
                                        'label'         => __('Attachment Caption', 'exc-uploader-theme'),
                                        'type'          => 'text',
                                        'attrs'         => array(
                                                                'class'                 => 'form-control',
                                                                'placeholder'           => esc_html__('Enter Caption', 'exc-uploader-theme'),
                                                                'data-primary_field'    => 1
                                                            ),

                                        'validation'    => 'min_length[5]|wp_strip_all_tags|stripslashes'
                                    );

$options['_config']['layout']['tabs']['attachments']['dynamic']['post_attachments']['attachment_source'] = array(
                                        'label'         => __('Attachment Source', 'exc-uploader-theme'),
                                        'type'          => 'text',
                                        'attrs'         => array(
                                                                'class'         => 'form-control',
                                                                'placeholder'   => esc_html__('Enter Source', 'exc-uploader-theme'),
                                                                'id'            => '{{{ i }}}_attachment_source'
                                                            ),

                                        'validation'    => 'wp_strip_all_tags'
                                    );

$options['_config']['layout']['tabs']['attachments']['dynamic']['post_attachments']['attachment_content'] = array(
                                        'label'         => __('Attachment Content', 'exc-uploader-theme'),
                                        'type'          => 'textarea',
                                        'attrs'         => array(
                                                                'class'         => 'form-control',
                                                                'placeholder'   => esc_html__('Enter Content', 'exc-uploader-theme')
                                                            ),

                                        'validation'    => 'wp_strip_all_tags|stripslashes|strip_shortcodes'
                                        //'validation'  => 'wp_strip_all_tags|stripslashes'
                                    );

$options['_config']['layout']['tabs']['attachments']['dynamic']['post_attachments']['attachment_id'] = array(
                                'label'         => __('Select / Upload File', 'exc-uploader-theme'),
                                'type'          => 'media_uploader',
                                'attrs'         => array(
                                                        'class'         => 'form-control',
                                                        'placeholder'   => esc_html__('Enter Content', 'exc-uploader-theme'),
                                                    ),

                                'el_columns'    => 8,
                                'data'          => array(
                                                        'upload-limit'  => 1
                                                    )
                                //'validation'  => 'wp_strip_all_tags|stripslashes'
                            );

$options['_config']['layout']['tabs']['layout_settings']['exc_layout_header'] = array(
                                'label'     => __('Header', 'exc-uploader-theme'),
                                'type'      => 'select',
                                'attrs'     => array( 'class' => 'form-control' ),

                                'options'   => array(
                                                ''          => __('Header Default', 'exc-uploader-theme'),
                                                'center'    => __('Header with logo in Center', 'exc-uploader-theme'),
                                                'classic'   => __('Header Classic', 'exc-uploader-theme')
                                            ),

                                //'skin'        => 'form_field',
                                'help'      => __('Select a header for this page.', 'exc-uploader-theme'),
                            );

$options['_config']['layout']['tabs']['layout_settings']['exc_layout_slider'] = array(
                                'label'     => __( 'Slider', 'exc-uploader-theme' ),
                                'type'      => 'clickable',

                                'attrs'     => array(
                                                    'data-max_limit' => '1',
                                                ),

                                'options'   => array(
                                                'no-slider'         =>  array(
                                                                            'label'                 => __('No Slider', 'exc-uploader-theme'),
                                                                            'data-id'               => ''
                                                                        ),

                                                'uploader'          =>  array(
                                                                            'label'                 => __('Uploader', 'exc-uploader-theme'),
                                                                            'data-id'               => 'with_uploader'
                                                                        ),

                                                'revslider'         => array(
                                                                            'label'                 => __('Revolution Slider', 'exc-uploader-theme'),
                                                                            'data-id'               => 'revslider',
                                                                            'data-linked_fields'    => 'header-center-revslider-id'
                                                                        )
                                                ),

                                'option_skin'   => 'withalpha',
                                'help'          => __( 'Select a slider for this page.', 'exc-uploader-theme' )
                            );

$options['_config']['layout']['tabs']['layout_settings']['exc_layout_revslider_id'] = array(
                                'label'         => __( 'Revolution Slider id', 'exc-uploader-theme' ),
                                'type'          => 'select',
                                'options'       => exc_revslider_list(),
                                'attrs'         => array(
                                                        'class' => 'form-control',
                                                        'id'    => 'header-center-revslider-id'
                                                    ),

                                'validation'    => '',
                                'help'          => __( 'Select a revolution slider. <br /><br /><i> <strong>Note: </strong>You can manage this list by adding or removing sliders in revolution slider</i>.', 'exc-uploader-theme' )
                            );

$options['_config']['layout']['tabs']['layout_settings']['exc_layout_header_sidebar_status'] = array(
                                'label'     => esc_html__('Header Sidebar', 'exc-uploader-theme'),
                                'type'      => 'clickable',
                                'options'   => array(
                                                    'hide' =>
                                                        array(
                                                            'label'                 => esc_html__('Hide Sidebar', 'exc-uploader-theme'),
                                                            'data-id'               => ''
                                                        ),

                                                    'show' =>
                                                        array(
                                                            'label'                 => esc_html__('Show Sidebar', 'exc-uploader-theme'),
                                                            'data-id'               => 'show',
                                                            'data-linked_fields'    => 'header-sidebar'
                                                        ),
                                                ),

                                'attrs'     => array(
                                                    'data-max_limit' => 1,
                                                ),

                                'option_skin'=> 'withalpha',
                                'validation'=> '',
                                'default'   => '',
                                'help'      => esc_html__('Show or hide a sidebar in header section.', 'exc-uploader-theme')
                            );

$options['_config']['layout']['tabs']['layout_settings']['exc_layout_header_sidebar'] = array(
                                'label'     => __('Header Sidebar', 'exc-uploader-theme'),
                                'type'      => 'select',
                                'attrs'     => array(
                                                    'class' => 'form-control',
                                                    'id'    => 'header-sidebar'
                                                ),

                                'options'   => &$GLOBALS['exc_sidebars_list'],
//                              'skin'      => 'form_field',
                                'help'      => __('Select a sidebar to show as header sidebar.', 'exc-uploader-theme'),
                            );

$options['_config']['layout']['tabs']['layout_settings']['exc_layout_structure'] = array(

                            'label'     => __('Layout', 'exc-uploader-theme'),
                            'type'      => 'clickable',
                            'attrs'     => array(
                                                'data-max_limit' => '1',
                                            ),

                            'options'   => array(
                                            'full-width' =>
                                                array(
                                                    'data-id'               => 'full-width',
                                                    'class'                 => 'btn exc-clickable layout-full-width'
                                                ),

                                            'left-sidebar' =>
                                                array(
                                                    'data-id'               => 'left-sidebar',
                                                    'data-linked_fields'    => 'left_sidebar',
                                                    'class'                 => 'btn exc-clickable layout-left-sidebar'
                                                ),

                                            'right-sidebar' =>
                                                array(
                                                    'data-id'               => 'right-sidebar',
                                                    'data-linked_fields'    => 'right_sidebar',
                                                    'class'                 => 'btn exc-clickable layout-right-sidebar'
                                                ),

                                            'two-sidebars'  =>
                                                array(
                                                    'data-id'               => 'two-sidebars',
                                                    'data-linked_fields'    => 'left_sidebar, right_sidebar',
                                                    'class'                 => 'btn exc-clickable layout-three-column'
                                                ),

                                            'left-two-sidebars' =>
                                                array(
                                                    'data-id'               => 'left-two-sidebars',
                                                    'data-linked_fields'    => 'left_sidebar, right_sidebar',
                                                    'class'                 => 'btn exc-clickable layout-left-twosidebar'
                                                ),
                                            'right-two-sidebars'            =>
                                                array(
                                                    'data-id'               => 'right-two-sidebars',
                                                    'data-linked_fields'    => 'left_sidebar, right_sidebar',
                                                    'class'                 => 'btn exc-clickable layout-right-twosidebar'
                                                ),
                                            ),
                            'el_columns'    => '7',
                            'validation'    => 'required',
                            'option_skin'   => 'with_css',
                            'help'          => __( 'Select a layout structure.', 'exc-uploader-theme' ),
                        );


$options['_config']['layout']['tabs']['layout_settings']['exc_layout_left_sidebar'] = array(
                                'label'     => __('Left Sidebar', 'exc-uploader-theme'),
                                'type'      => 'select',
                                'attrs'     => array(
                                                    'class' => 'form-control',
                                                    'id'    => 'left_sidebar',
                                                ),

                                'options'   => &$GLOBALS['exc_sidebars_list'],
//                              'skin'      => 'form_field',
                                'help'      => __('Select a sidebar to show as left sidebar.', 'exc-uploader-theme'),
                            );

$options['_config']['layout']['tabs']['layout_settings']['exc_layout_right_sidebar'] = array(
                                'label'     => __('Right Sidebar', 'exc-uploader-theme'),
                                'type'      => 'select',
                                'attrs'     => array(
                                                    'class' => 'form-control',
                                                    'id'    => 'right_sidebar',
                                                ),

                                'options'   => &$GLOBALS['exc_sidebars_list'],
//                              'skin'      => 'form_field',
                                'help'      => __('Select a sidebar to show as right sidebar.', 'exc-uploader-theme'),
                            );

// Do not display attachments option on pages
if ( $typenow == 'page' )
{
    $options['_config'] = array(
                            '_settings' => $options['_config']['_settings'],
                            'layout'    => $options['_config']['layout']['tabs']['layout_settings']
                        );
}