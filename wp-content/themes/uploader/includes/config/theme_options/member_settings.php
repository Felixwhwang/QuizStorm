<?php if ( ! defined( 'ABSPATH' ) ) exit('restricted access');

//$this->clear_query()->load_file('functions/wp_helper');

$options = array(
                'title'     => esc_html__('Members Settings', 'exc-uploader-theme'),
                'db_name'   => 'mf_member_settings',
            );

$options['_config']['_settings'] =
                        array(
                            'member' =>
                                array(
                                    'heading'       => esc_html__('Member General Settings', 'exc-uploader-theme'),
                                    'description'   => sprintf(
                                                            __( 'This section is related to members of the site, here you can enable or disable the new user registration, force them to login from front-end and so on. <p><i><strong>Note: </strong>Extracoding Uploader Theme has the ability to customize email templates of new user registration and forgot password. You can find these settings in %s.</i></p>', 'exc-uploader-theme' ),
                                                            '<a href="' . admin_url('themes.php?section=mail_settings') . '">Mail Settings</a>'
                                                        )
                                ),
                            'backgrounds' =>
                                array(
                                    'heading'       => esc_html__('Member Pages Background Settings', 'exc-uploader-theme'),
                                    'description'   => __('This Section is related to the member login, signup and forget password pages background, here you can select or upload multiple videos and images for background. <p><i><strong>Note: </strong></i> it will automatically create a playlist for all the videos and they will be played one by one but in case of images it will be paused.</p>', 'exc-uploader-theme'),
                                ),
                            'strings' =>
                                array(
                                    'heading'       => esc_html__('Default Strings (text) Settings', 'exc-uploader-theme'),
                                    'description'   => esc_html__('With the help of this section you can change the text strings.', 'exc-uploader-theme'),
                                ),
                        );

$options['_config']['member']['status'] = array(
                                'label'     => esc_html__('Status', 'exc-uploader-theme'),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'default'   => 'on',
                                'help'      => esc_html__('Enable or disable front-end login and signup.', 'exc-uploader-theme')
                            );

$options['_config']['member']['frontend_login'] = array(
                                'label'     => esc_html__('Force Front-end Login', 'exc-uploader-theme'),
                                'type'      => 'switch',
                                'attrs'     => array(
                                                    'class' => 'form-control',
                                                    'data-size' => 'medium',
                                                ),

                                'default'   => '',
                                'help'      => __('Force all the users to login from front-end only. <br /><br /><i><strong>Note: </strong> this option basically restrict access to /wp-admin so all the users including admin is forced to login from front-end only but allowed members group / role can access backend after login, for further restiction please use the following "WP Admin Bar" option. <br /><br /> <strong>Very Important: </strong> Do not activate this option if the membership status and member controls options in header settings are off, otherwise after logout you will not be able to access backend anymore.</i>', 'exc-uploader-theme')
                            );

$options['_config']['member']['admin_bar'] = array(
                                'label'     => esc_html__('WP Admin Bar', 'exc-uploader-theme'),
                                'type'      => 'select',
                                'options'   => exc_get_roles(),
                                'attrs'     => array(
                                                    'class' => 'form-control',
                                                    'multiple' => 'multiple',
                                                    'size' => 5
                                                ),

                                'selected'  => 'administrator',
                                'validation'=> '',
                                'default'   => '',
                                'help'      => __('Restrict the access of logged in members to wordpress admin panel. <br /><br /> <i><strong>Note: </strong> this option will only work if the above option "force front-end login" is active and all the users with administrator role can access /wp-admin directly even if it\'s not selected in this list. furthermore please note that we are not using wordpress built-in functionality to hide admin bar as that option belongs to each user profile settings and we have option to show or hide it anytime.</i>.', 'exc-uploader-theme')
                            );

$options['_config']['member']['register_as'] = array(
                                'label'     => esc_html__('Register As', 'exc-uploader-theme'),
                                'type'      => 'select',
                                'options'   => exc_get_roles( array('administrator', 'editor') ),
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'selected'  => 'contributor',
                                'validation'=> 'required',
                                'help'      => sprintf(
                                                __('Register users with the selected role. <br /><br /> <i><strong>Note: </strong> roles are very important in wordpress so change it only if you know exactly what are you doing, you can further read about them on wordpress codex %s </i>.', 'exc-uploader-theme'),
                                                '<a href="http://codex.wordpress.org/Roles_and_Capabilities" target="_blank">Read more</a>')
                            );

$options['_config']['backgrounds']['bg_attachments'] = array(
                                'label'     => __('Select or Upload Backgrounds', 'exc-uploader-theme'),
                                'type'      => 'media_uploader',
                                'attrs'     => array(
                                                    'class' => 'form-control',
                                                ),
                                'el_columns'=> 8,
                                'data'      => array(
                                                    'mime-types'    => 'image, video',
                                                    'upload-limit'  => 100
                                                ),
                            );

$options['_config']['strings']['signin_btn'] = array(
                                'label'     => esc_html__('Sign In Button', 'exc-uploader-theme'),
                                'type'      => 'text',
                                'attrs'     => array(
                                                    'class' => 'form-control'
                                                ),

                                'validation'=> 'required|min_length[2]',
                                'default'   => __('Sign In to Uploader', 'exc-uploader-theme'),
                                'help'      => esc_html__('Change the sign in button text.', 'exc-uploader-theme')
                            );