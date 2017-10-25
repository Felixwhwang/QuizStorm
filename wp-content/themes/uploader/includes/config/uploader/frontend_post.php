<?php defined('ABSPATH') or die('restricted access.');

$post_id = ( isset( $post_id ) && intval( $post_id ) ) ? $post_id : 0;

$options = array(
                '_layout'       => 'uploader/form',
                '_js'           => 'linked_fields',
                '_capabilities' => '',
                'post_id'       => $post_id
            );

// Display only for new posts
if ( ! $post_id )
{
    //@TODO: Fetch the list of allowed mime types and display them
    $options['_config']['post_type'] = array(
                                    'label'     => esc_html__('Post Type', 'exc-uploader-theme'),
                                    'type'      => 'hidden',
                                    'default'   => 'post'
                                );
}

$options['_config']['post_title'] = array(
                                        'label'         => __('Title', 'exc-uploader-theme'),
                                        'type'          => 'text',
                                        'attrs'         => array(
                                                                'class' => 'form-control',
                                                            ),

                                        'layout'        => 'uploader/text',
                                        'validation'    => 'required|min_length[5]|max_length[300]|wp_strip_all_tags|stripslashes',
                                        // 'help'           => __('Enter the title of your media file.', 'exc-uploader-theme')
                                    );

$options['_config']['post_category'] = array(
                                        'label'         => __('Category', 'exc-uploader-theme'),
                                        'type'          => 'select',
                                        'attrs'         => array(
                                                                //'multiple'        => 'multiple',
                                                                'class' => 'form-control'
                                                            ),

                                        // 'select2'        => array(
                                        //                      'maximumSelectionSize'  => 3,
                                        //                  ),

                                        //'element'     => 'select',
                                        'options'       => exc_get_categories_list( array( 'hide_empty' => 0 ) ),
                                        'validation'    => 'required',
                                        'layout'        => 'uploader/dropdown',
                                        // 'help'           => __('Enter the description of your media file.', 'exc-uploader-theme')
                                    );

// fix for text / visual editor bug
//jQuery( '#post_content' ).val( tinyMCE.activeEditor.getContent() )
$options['_config']['post_content'] = array(
                                        'label'         => __('Description', 'exc-uploader-theme'),
                                        'type'          => 'wysiwyg',
                                        'validation'    => 'required|strip_shortcodes|wp_rel_nofollow|force_balance_tags|stripslashes',
                                        'layout'        => 'uploader/wysiwyg',
                                        'wp_editor'     => array('tinymce'  => false)
                                        // 'help'           => __('Enter the description of your media file.', 'exc-uploader-theme')
                                    );

$tags = exc_get_categories_list( array( 'hide_empty' => 0, 'taxonomy' => 'post_tag' ) );

$options['_config']['accordion_uploader']['additional_settings']['tags_input'] = array(
                                        'label'         => __('Tags', 'exc-uploader-theme'),
                                        'type'          => 'select2',
                                        'attrs'         => array(
                                                                'multiple'      => 'multiple',
                                                            ),

                                        'validation'    => '',
                                        'layout'        => 'uploader/tags',
                                        'select2'       => array(
                                                                'tags'      => array_values( (array) $tags )
                                                            ),

                                        'help'          => __( 'Enter the description of your media file.', 'exc-uploader-theme' )
                                );

$options['_config']['accordion_uploader']['additional_settings']['post_status'] = array(
                                        'label'         => __('Privacy', 'exc-uploader-theme'),
                                        'type'          => 'clickable',
                                        'attrs'         => array(
                                                            'data-max_limit' => '1',
                                                        ),

                                        'options'       => array(
                                                            'publish' =>
                                                                array(
                                                                    'label'     => __('Public', 'exc-uploader-theme'),
                                                                    'data-id'   => 'publish'
                                                                    ),

                                                            'private' =>
                                                                array(
                                                                    'label'     => __('Private', 'exc-uploader-theme'),
                                                                    'data-id'   => 'private'
                                                                ),

                                                            'protected' =>
                                                                array(
                                                                    'label'                 => __('Protected', 'exc-uploader-theme'),
                                                                    'data-linked_fields'    => 'post_password'
                                                                ),

                                                            ),

                                        'option_skin'   => 'withalpha',
                                        'layout'        => 'uploader/clickable',
                                        'default'       => 'publish',
                                        'validation'    => 'required'
                                    );


$options['_config']['accordion_uploader']['additional_settings']['post_password'] = array(
                                        'label'         => __('Password', 'exc-uploader-theme'),
                                        'type'          => 'text',
                                        'attrs'         => array(
                                                                'class' => 'form-control',
                                                                'id'    => 'post_password',
                                                            ),

                                        'layout'        => 'uploader/password_field',
                                        'validation'    => 'min_length[3]|max_length[50]',
                                        'help'          => __('Enter the title of your media file.', 'exc-uploader-theme')
                                    );

$options['_config']['accordion_uploader']['additional_settings']['comment_status'] = array(
                                        'label'         => __('Comments Status', 'exc-uploader-theme'),
                                        'type'          => 'clickable',
                                        'attrs'         => array
                                                            (
                                                                'data-max_limit' => '1',
                                                            ),

                                        'options'       => array(
                                                            'open' =>
                                                                array(
                                                                    'label' => _x( 'Open', 'extracoding uploader frontend post comment status', 'exc-uploader-theme' ),
                                                                ),

                                                            'closed' =>
                                                                array(
                                                                    'label' => _x( 'Closed', 'extracoding uploader frontend post comment status', 'exc-uploader-theme' ),
                                                                ),
                                                            ),

                                        'option_skin'   => 'withalpha',
                                        'layout'        => 'uploader/clickable',
                                        'help'          => 'The space for help note will goes here.',
                                        //'default'         => 'closed',
                                    );

$options['_config']['accordion_uploader']['additional_settings']['license'] = array(
                                    'label'             => __('License', 'exc-uploader-theme'),
                                    'type'              => 'clickable',
                                    'attrs'             => array(
                                                            'data-max_limit' => '1',
                                                            ),

                                    'options'           => array(
                                                                array(
                                                                    'label' => __('Standard License', 'exc-uploader-theme'),
                                                                ),

                                                                array(
                                                                    'label' => __('Creative Common License', 'exc-uploader-theme'),
                                                                ),
                                                            ),

                                    'option_skin'       => 'withalpha',
                                    'layout'            => 'uploader/clickable',
                                    'help'              => 'Select the license type of this media file.',
                                    //'validation'      => 'required'
                                );