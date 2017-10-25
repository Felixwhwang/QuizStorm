<?php defined('ABSPATH') OR die('restricted access');

$options = array(
                'title'     => esc_html__('Uploader Settings', 'exc-uploader-theme'),
                'db_name'   => 'mf_uploader_settings',
            );

$options['_config']['_settings'] =
                        array(
                            'settings' =>
                                array(
                                    'heading'       => esc_html__('Uploader General Settings', 'exc-uploader-theme'),
                                    'description'   => __('Extracoding Uploader Theme has the buitin functionality to upload media files directly from frontend, in this section you can adjust global settings of uploader e.g. status, number of uploads limit, attachment limits and so on.<p><i><strong>Note: </strong> The theme has the ability to automatically identify the files types of audio, video and images files and it can publish them in particular section so do not get confused as there are no related settings available</i></p>.', 'exc-uploader-theme'),
                                ),
                            'strings' =>
                                array(
                                    'heading'       => esc_html__('Default Strings (text) Settings', 'exc-uploader-theme'),
                                    'description'   => __('In this section you can change the global strings (text) of uploader. <p><i><strong>Note: </strong>You can also change them directly in shortcode setttings please read the documentation for further information.</i></p>.', 'exc-uploader-theme'),
                                ),
                        );

$options['_config']['settings']['status'] = array(
                                'label'     => esc_html__('Status', 'exc-uploader-theme'),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'default'   => 'on',
                                'help'      => esc_html__('Enable Or disable front-end uploading functionality.', 'exc-uploader-theme')
                            );

/*$options['_config']['settings']['popup'] = array(
                                'label'        => esc_html__('Popup', 'exc-uploader-theme'),
                                'type'        => 'switch',
                                'attrs'        => array(
                                                    'class' => 'form-control'
                                                ),

                                'default'    => 'on',
                                'help'        => esc_html__('Display Uploader window as popup.', 'exc-uploader-theme')
                            );*/

$options['_config']['settings']['posts_limit'] = array(
                                'label'     => esc_html__('Uploads Limit', 'exc-uploader-theme'),
                                'type'      => 'text',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'validation'=> 'required|is_natural',
                                'default'   => '100',
                                'help'      => esc_html__('Limit the number of maximum uploads for frontend users.', 'exc-uploader-theme')
                            );

$options['_config']['settings']['attachments_limit'] = array(
                                'label'     => esc_html__('Attachments Limit', 'exc-uploader-theme'),
                                'type'      => 'text',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'validation'=> 'required|is_natural_no_zero',
                                'default'   => '20',
                                'help'      => esc_html__('Limit the number of maxium attachments for each upload.', 'exc-uploader-theme')
                            );

$options['_config']['settings']['featured_image'] = array(
                                'label'     => esc_html__('Featured Image', 'exc-uploader-theme'),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'default'   => 'on',
                                'help'      => __('Restrict users to must upload feature image with each post. <br /> <br /> <i><strong>Note: </strong>Feature image will also counted as attachment, so in case you allowed users to upload 20 attachments and feature image option is also active then they will be able to upload 1 feature image and 4 attachments.</i>', 'exc-uploader-theme')
                            );

$options['_config']['settings']['allowed_post_types'] = array(
                                'label'     => esc_html__('Allowed Post Types', 'exc-uploader-theme'),
                                'type'      => 'select',
                                'options'   => array(
                                                    'post'              => __('Articles', 'exc-uploader-theme'),
                                                    'exc_audio_post'    => __('Audio Posts', 'exc-uploader-theme'),
                                                    'exc_video_post'    => __('Video Posts', 'exc-uploader-theme'),
                                                    'exc_image_post'    => __('Image Posts', 'exc-uploader-theme')
                                                ),
                                'attrs'     => array(
                                                    'class'     => 'form-control',
                                                    'multiple'  => 'multiple',
                                                    'size'      => 4
                                                ),

                                'selected'  => array(
                                                    'post',
                                                    'exc_audio_post',
                                                    'exc_video_post',
                                                    'exc_image_post'
                                                ),

                                'validation'=> 'required',
                                'default'   => '',
                                'help'      => __('Allow the users to upload files in selected section <br /><br /> <i><strong>Note: </strong>In case of one post type selection, user will not prompted to select type of the post.</i>.', 'exc-uploader-theme')
                            );

$options['_config']['settings']['allowed_mime'] = array(
                                'label'     => esc_html__('Allowed Files', 'exc-uploader-theme'),
                                'type'      => 'select',
                                'options'   => array(
                                                    'audio' => __('Audios', 'exc-uploader-theme'),
                                                    'video' => __('Videos', 'exc-uploader-theme'),
                                                    'image' => __('Images', 'exc-uploader-theme')
                                                ),
                                'attrs'     => array(
                                                    'class'     => 'form-control',
                                                    'multiple'  => 'multiple',
                                                    'size'      => 3
                                                ),

                                'selected'  => array(
                                                    'image',
                                                    'video',
                                                    'audio'
                                                ),
                                'validation'=> 'required',
                                'default'   => '',
                                'help'      => __('Restrict the users to upload selected file types only. <br /> <br /> <i><strong>Note: </strong> User will be able to upload only wordpress supported files for example in above option image type means all wordpress supported images mime types e.g image/jpg, image/png, image/gif and so on.</i>.', 'exc-uploader-theme')
                            );

$options['_config']['settings']['post_status'] = array(
                                'label'     => esc_html__('Default Status', 'exc-uploader-theme'),
                                'type'      => 'select',
                                'options'   => array(
                                                    'draft' => __('Draft', 'exc-uploader-theme'),
                                                    'pending' => __('Pending', 'exc-uploader-theme'),
                                                    'publish' => __('Publish', 'exc-uploader-theme')
                                                ),
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'selected'  => array('pending'),
                                'validation'=> 'required',
                                'default'   => '',
                                'help'      => __('Select the default wordpress post status of uploads, we strongly recommend that it must be pending so you can review them before it goes live.', 'exc-uploader-theme')
                            );

$options['_config']['settings']['rename'] = array(
                                'label'     => esc_html__('Rename Files', 'exc-uploader-theme'),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'default'   => 'on',
                                'help'      => esc_html__('System will automatically overwrite the user uploaded files name.', 'exc-uploader-theme')
                            );

$options['_config']['settings']['prevent_duplicates'] = array(
                                'label'     => __('Prevent Duplicates', 'exc-uploader-theme'),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control',
                                                ),

                                'default'   => 'on',
                                'help'      => __('Prevent the duplicate files upload for current que. <br /><br /> <i><strong>Note: </strong>This option will work only for current uploads and it will also restrict users from uploading the duplicate files for attachments.</i>', 'exc-uploader-theme'),
                            );

$options['_config']['settings']['form_save'] = array(
                                'label'      => esc_html__('On Success', 'exc-uploader-theme'),
                                'type'       => 'select',
                                'options'    => array(
                                                    'default'   => __( 'Default', 'exc-uploader-theme' ),
                                                    'close'     => __( 'Close popup', 'exc-uploader-theme' ),
                                                    'redirect'  => __( 'Redirect to post', 'exc-uploader-theme' )
                                                ),
                                'attrs'      => array(
                                                    'class' => 'form-control'
                                                ),

                                'validation' => 'required',
                                'default'    => '',
                                'help'       => esc_html__('Do the Following on successfull form submission.', 'exc-uploader-theme')
                            );

$options['_config']['settings']['upload_action'] = array(
                                'label'      => esc_html__('Upload Action', 'exc-uploader-theme'),
                                'type'       => 'switch',
                                'attrs'      => array(
                                                    'class' => 'form-control'
                                                ),

                                'default'    => 'on',
                                'help'       => esc_html__('Force users to upload files when click on upload button.', 'exc-uploader-theme')
                            );

$max_upload_size = wp_max_upload_size();

$options['_config']['settings']['max_file_size'] = array(
                                'label'         => __('Maximum Size Limit', 'exc-uploader-theme'),
                                'type'          => 'text',
                                'attrs'         => array(
                                                    'class' => 'form-control',
                                                ),

                                'validation'    => 'is_natural',
                                'default'       => $max_upload_size ? $max_upload_size : 0,
                                'help'          => sprintf( __('Restrict the maximum file size limit in kilobytes. <br /> <br /> <i><strong>Note: </strong> it should not exceed your current wordpress or PHP maximum upload size limit which is %s KB.</i> ', 'exc-uploader-theme'), $max_upload_size ),
                            );

$options['_config']['strings']['heading'] = array(
                                'label'         => __('Heading', 'exc-uploader-theme'),
                                'type'          => 'text',
                                'attrs'         => array(
                                                    'class' => 'form-control'
                                                ),

                                'validation'    => 'required',
                                'default'       => __('Share Your Media', 'exc-uploader-theme'),
                                'help'          => __('Change the main &lth2&gt heading of uploader.', 'exc-uploader-theme')
                            );

$options['_config']['strings']['about'] = array(
                                'label'         => __('Uploader Intro', 'exc-uploader-theme'),
                                'type'          => 'textarea',
                                'attrs'         => array(
                                                    'class' => 'form-control'
                                                    ),

                                'validation'    => 'required',
                                'default'       => __('Uploader is a powerful theme to share your media files with drag & drop functionality.', 'exc-uploader-theme'),
                                'help'          => __('Change the uploader introduction text.', 'exc-uploader-theme')
                            );

$options['_config']['strings']['btn'] = array(
                                'label'         => __('Button Text', 'exc-uploader-theme'),
                                'type'          => 'text',
                                'attrs'         => array(
                                                    'class' => 'form-control'
                                                    ),

                                'validation'    => 'required',
                                'default'       => __('Upload Media', 'exc-uploader-theme'),
                                'help'          => __('Change the upload button text.', 'exc-uploader-theme')
                            );

$options['_config']['strings']['dropfiles'] = array(
                                'label'         => __('Drop files', 'exc-uploader-theme'),
                                'type'          => 'text',
                                'attrs'         => array(
                                                    'class' => 'form-control'
                                                    ),

                                'validation'    => 'required',
                                'default'       => __('Just Drop your files here', 'exc-uploader-theme'),
                                'help'          => __('Change the uploader drop files text.', 'exc-uploader-theme')
                            );