<?php defined('ABSPATH') OR die('restricted access');
$options = array(
				'title'		=> esc_html__('Mail Settings', 'exc-uploader-theme'),
				'db_name'	=> 'mf_mail_settings',
			);

$url = parse_url( site_url() );
$host = ( ! empty( $url['host'] ) ) ? $url['host'] : 'example.com';

//$options['_capabilities'] = '';
if( ! function_exists('mail') )
{	
	$this->html->inline_js('exc-custom-dialog', 'eXc.dialog.auto_open("PHP mail function warning", "<div role=\"alert\" class=\"alert alert-warning alert-dismissible\">The php mail is not enabled on your server, please activate it otherwise mails delivery will be failed</div>", ["ok"]);', '', true);
}

$options['_config']['_settings'] =
						array(
							'general_settings' =>
								array(
									'heading'		=> esc_html__('General Information', 'exc-uploader-theme'),
									'description'	=> esc_html__('Another advanced option by extracoding themes to give you ability to change the wordpress buit-in email settings.', 'exc-uploader-theme'),
								),
							'emails' =>
								array(
									'heading'		=> esc_html__('Emails Settings', 'exc-uploader-theme'),
									'description'	=> esc_html__('This section gives you ability to overwrite the default wordpress emails templates.', 'exc-uploader-theme'),
								),
							'user_emails' =>
								array(
									'heading'		=> esc_html__('User Emails Settings', 'exc-uploader-theme'),
									'description'	=> esc_html__('This section give you ability to change the theme custom emails templates.', 'exc-uploader-theme'),
								),
						);

$options['_config']['general_settings']['tabs']['general_settings']['to'] = array(
								'label'		=> esc_html__('Admin Email Address', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'required|esc_html',
								'default'	=> get_option('admin_email'),
								'help'		=> esc_html__('The default admin email address to receive emails.', 'exc-uploader-theme')
						);

$options['_config']['general_settings']['tabs']['general_settings']['from_name'] = array(
								'label'		=> esc_html__('System Sender Name', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'required|esc_html',
								'default'	=> $this->clear_query()->load('core/mail_class')->get_from_name(),
								'help'		=> esc_html__('The default system sender name used while sending mails from system e.g registration email.', 'exc-uploader-theme')
						);
													
$options['_config']['general_settings']['tabs']['general_settings']['from_email'] = array(
								'label'		=> esc_html__('System Sender Email Address', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'required|valid_email',
								'default'	=> $this->mail->get_from_addr(),
								'help'		=> __('The default email address used to send system emails <br /><br /> <i><strong>Important Note: </strong>To avoid your email being marked as spam, it is highly recommended that your "System Sender Email Address" domain must match with your website URL.</i>', 'exc-uploader-theme')
							);

$options['_config']['general_settings']['tabs']['smtp_settings']['smtp_status'] = array(
								'label'		=> esc_html__('Enable SMTP', 'exc-uploader-theme'),
								'type'		=> 'switch',
								'attrs'		=> array(
													'class' => 'form-control'
												),

								'default'	=> '',
								'help'		=> esc_html__('Enable or disable Enable SMTP.', 'exc-uploader-theme')
							);

$options['_config']['general_settings']['tabs']['smtp_settings']['smtp_auth'] = array(
								'label'		=> esc_html__('SMTP Authetication', 'exc-uploader-theme'),
								'type'		=> 'switch',
								'attrs'		=> array(
													'class' => 'form-control',
													'data-on-text'	=> 'True',
													'data-off-text'	=> 'False'
												),

								'default'	=> 'on',
								'help'		=> sprintf( 
													__('SMTP Authentication, often abbreviated SMTP AUTH, is an extension of the Simple Mail Transfer Protocol whereby an SMTP client may log in using an authentication mechanism chosen among those supported by the SMTP server. The authentication extension is mandatory for submission servers. %s', 'exc-uploader-theme' ),
													'<a href="//en.wikipedia.org/wiki/SMTP_Authentication" target="_blank">Read More</a>'
												)
							);

$options['_config']['general_settings']['tabs']['smtp_settings']['smtp_host'] = array(
								'label'		=> esc_html__('Host', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> '',
								'default'	=> '',
								//'help'		=> esc_html__('Enter the Host information.', 'exc-uploader-theme')
							);

$options['_config']['general_settings']['tabs']['smtp_settings']['smtp_username'] = array(
								'label'		=> esc_html__('Username', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> '',
								'default'	=> '',
								//'help'		=> esc_html__('Enter the Username information.', 'exc-uploader-theme')
							);

$options['_config']['general_settings']['tabs']['smtp_settings']['smtp_password'] = array(
								'label'		=> esc_html__('Password', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> '',
								'default'	=> '',
								//'help'		=> esc_html__('Enter the Password information.', 'exc-uploader-theme')
							);

$options['_config']['general_settings']['tabs']['smtp_settings']['smtp_port'] = array(
								'label'		=> esc_html__('Port', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'is_natural_no_zero',
								'default'	=> '',
								'help'		=> esc_html__('Enter the Port information.', 'exc-uploader-theme')
							);

$options['_config']['general_settings']['tabs']['smtp_settings']['smtp_debug'] = array(
								'label'		=> esc_html__('Debugging Level', 'exc-uploader-theme'),
								'type'		=> 'select',
								'options'	=> array(0, 1, 2,),
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'is_natural',
								'default'	=> '0',
								//'help'		=> esc_html__('Enter the Debugging Level information.', 'exc-uploader-theme')
							);

$options['_config']['general_settings']['tabs']['smtp_settings']['smtp_secure'] = array(
								'label'		=> esc_html__('Encryption', 'exc-uploader-theme'),
								'type'		=> 'select',
								'options'	=> array(
									                ''		=> __('None', 'exc-uploader-theme'),
									                'ssl'	=> __('SSL', 'exc-uploader-theme'),
									                'tls'	=> __('TLS', 'exc-uploader-theme'),
									            ),
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> '',
								'default'	=> '',
								'help'		=> esc_html__('Enter the Encryption information.', 'exc-uploader-theme')
							);

$options['_config']['emails']['tabs']['new_user']['user_status'] = array(
								'label'		=> esc_html__('Status', 'exc-uploader-theme'),
								'type'		=> 'switch',
								'attrs'		=> array(
													'class' => 'form-control'
												),

								'default'	=> 'on',
								'help'		=> esc_html__('Send notification on each like.', 'exc-uploader-theme')
							);

$options['_config']['emails']['tabs']['new_user']['user_subject'] = array(
								'label'		=> esc_html__('Email Subject', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'required|stripslashes',
								'default'	=> __('{blog_name} Welcome to our Community', 'exc-uploader-theme'),
								'help'		=> sprintf( __('Available Parameters: %s', 'exc-uploader-theme'), 
												'<br /> {blog_name}, {site_url}, {user_login}, {first_name}, {last_name}, {user_password}.'),
							);

$options['_config']['emails']['tabs']['new_user']['user_body'] = array(
								'label'			=> esc_html__('Email Body', 'exc-uploader-theme'),
								'type'			=> 'wysiwyg',
								'attrs'			=> array(
														'class' => 'form-control'
													),
								
								'wp_editor' 	=> array('textarea_rows' => 10),
								'skin' 			=> 'form_group',
								'el_columns'	=> 10,

								'validation'	=> 'required|stripslashes',
								'default'		=> 'Hello {user_login},

Thank you for signing up on {$host}.

Username: {user_login}
Password: {user_password}
{login_url}

Thanks,
{blog_name}',
								'help'			=> sprintf( 
														__('Available Parameters: %s', 'exc-uploader-theme'), 
														'<br /> {blog_name}, {site_url}, {login_url}, {user_login}, {first_name}, {last_name}, {user_password}.'
													)
							);

$options['_config']['emails']['tabs']['password_recovery']['recovery_subject'] = array(
								'label'		=> esc_html__('Email Subject', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'required|stripslashes',
								'default'	=> __('Reset Password request', 'exc-uploader-theme'),
								'help'		=> sprintf(
													 __('Available Parameters: %s', 'exc-uploader-theme'), 
													'<br /> {user_login}, {blog_name}, {first_name}, {last_name}.'),
							);

$options['_config']['emails']['tabs']['password_recovery']['recovery_body'] = array(
								'label'			=> esc_html__('Email Body', 'exc-uploader-theme'),
								'type'			=> 'wysiwyg',
								'attrs'			=> array(
														'class' => 'form-control'
													),
								
								'wp_editor' 	=> array('textarea_rows' => 10),
								'skin' 			=> 'form_group',
								'el_columns'	=> 10,

								'validation'	=> 'required|stripslashes',
								'default'		=> 'Hi {user_login},

We received a request to change your password on {blog_name}.

If you requested a reset for {user_login}, Click the link below to set a new password or If you didn\'t make this request, please ignore this email.

{reset_password_link}
Regards,
{blog_name}',

								'help'			=> sprintf( 
														__('Available Parameters: %s', 'exc-uploader-theme'), 
														'<br /> {user_login}, {blog_name}, {first_name}, {last_name}, {reset_password_link}, {data}, {time}.'),
							);

$options['_config']['user_emails']['tabs']['like_notification']['like_status'] = array(
								'label'		=> esc_html__('Status', 'exc-uploader-theme'),
								'type'		=> 'switch',
								'attrs'		=> array(
													'class' => 'form-control'
												),

								'default'	=> 'on',
								'help'		=> esc_html__('Send notification on each like.', 'exc-uploader-theme')
							);

$options['_config']['user_emails']['tabs']['like_notification']['like_content_type'] = array(
								'label'		=> esc_html__('Content Type', 'exc-uploader-theme'),
								'type'		=> 'select',
								'options'	=> array(
													'text'	=> 'Text',
													'html'	=> 'HTML'
												),
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> '',
								'default'	=> 'html',
								'help'		=> esc_html__('Send notification on each like.', 'exc-uploader-theme')
							);

$options['_config']['user_emails']['tabs']['like_notification']['like_subject'] = array(
								'label'		=> esc_html__('Email Subject', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'required',
								'default'	=> __('A new like on {post_title}', 'exc-uploader-theme'),
								'help'		=> sprintf( 
													__('Available Parameters: %s', 'exc-uploader-theme'), 
													'<br /> {blog_name}, {home_url}, {site_url}, {post_title}, {post_url}, {author_name}, {user_name}.')
							);

$options['_config']['user_emails']['tabs']['like_notification']['like_body'] = array(
								'label'			=> esc_html__('Email Body', 'exc-uploader-theme'),
								'type'			=> 'wysiwyg',
								'attrs'			=> array(
														'class' => 'form-control'
													),
								
								'wp_editor' 	=> array('textarea_rows' => 10),
								'skin' 			=> 'form_group',
								'el_columns'	=> 10,

								'validation'	=> 'required|stripslashes',
								'default'		=> __('Hi {author_name}, <br /><br />
														<p>A user {user_name} liked your post on {post_title}<br />{post_url}</p>
														<p>
															Regards,<br />
															{blog_name}
														</p>', 'exc-uploader-theme'),
								'help'			=> sprintf( 
														__('Available Parameters: %s', 'exc-uploader-theme'), 
														'<br /> {blog_name}, {home_url}, {site_url}, {post_title}, {post_url}, {author_name}, {user_name}.')
							);

// Follower Notifications
$options['_config']['user_emails']['tabs']['follow_notification']['follow_status'] = array(
								'label'		=> esc_html__('Status', 'exc-uploader-theme'),
								'type'		=> 'switch',
								'attrs'		=> array(
													'class' => 'form-control'
												),

								'default'	=> '',
								'help'		=> esc_html__('Send notification on following.', 'exc-uploader-theme')
							);

$options['_config']['user_emails']['tabs']['follow_notification']['follow_content_type'] = array(
								'label'		=> esc_html__('Content Type', 'exc-uploader-theme'),
								'type'		=> 'select',
								'options'	=> array(
													'text' => 'Text',
													'html' => 'HTML'
												),
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> '',
								'default'	=> 'html',
								'help'		=> esc_html__('Send notification on following.', 'exc-uploader-theme')
							);

$options['_config']['user_emails']['tabs']['follow_notification']['follow_subject'] = array(
								'label'		=> esc_html__('Email Subject', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> 'required',
								'default'	=> __('A new user is following you', 'exc-uploader-theme'),
								'help'		=> sprintf( 
													__('Available Parameters: %s', 'exc-uploader-theme'), 
													'<br /> {blog_name}, {home_url}, {site_url}, {author_name}, {user_name}.')
							);

$options['_config']['user_emails']['tabs']['follow_notification']['follow_body'] = array(
								'label'			=> esc_html__('Email Body', 'exc-uploader-theme'),
								'type'			=> 'wysiwyg',
								'attrs'			=> array(
														'class' => 'form-control'
													),
								
								'wp_editor' 	=> array('textarea_rows' => 10),
								'skin' 			=> 'form_group',
								'el_columns'	=> 10,

								'validation'	=> 'required|stripslashes',
								'default'		=> __('Hi {author_name}, <br /><br />
														<p>A user {user_name} is following you on {blog_name}<br />{home_url}</p>
														<p>
															Regards,<br />
															{blog_name}
														</p>', 'exc-uploader-theme'),
								'help'			=> sprintf( 
														__('Available Parameters: %s', 'exc-uploader-theme'), 
														'<br /> {blog_name}, {home_url}, {site_url}, {author_name}, {user_name}.')
							);

// Subscriber Email
$options['_config']['user_emails']['tabs']['subscriber_notification']['subscriber_status'] = array(
								'label'		=> esc_html__('Status', 'exc-uploader-theme'),
								'type'		=> 'switch',
								'attrs'		=> array(
													'class' => 'form-control'
												),

								'default'	=> '',
								'help'		=> esc_html__('Send notification on user subscription.', 'exc-uploader-theme')
							);

$options['_config']['user_emails']['tabs']['subscriber_notification']['subscriber_content_type'] = array(
								'label'		=> esc_html__('content Type', 'exc-uploader-theme'),
								'type'		=> 'select',
								'options'	=> array(
													'text' => 'Text',
													'html' => 'HTML'
												),
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> '',
								'default'	=> 'text',
								'help'		=> esc_html__('Send notification on user subscription.', 'exc-uploader-theme')
							);

$options['_config']['user_emails']['tabs']['subscriber_notification']['subscriber_subject'] = array(
								'label'		=> esc_html__('Email Subject', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'form-control'
												),
									
								'validation'=> '',
								'default'	=> '',
								'help'		=> sprintf(
													__('Available Parameters: %s', 'exc-uploader-theme'), 
													'<br /> {blog_name}, {home_url}, {site_url}, {subscriber_name}, {subscriber_email}, {subscriber_activation_url}.')
							);

$options['_config']['user_emails']['tabs']['subscriber_notification']['subscriber_body'] = array(
								'label'			=> esc_html__('Email Body', 'exc-uploader-theme'),
								'type'			=> 'wysiwyg',
								'attrs'			=> array(
														'class' => 'form-control'
													),
								
								'wp_editor' 	=> array('textarea_rows' => 10),
								'skin' 			=> 'form_group',
								'el_columns'	=> 10,

								'validation'	=> 'stripslashes',
								'default'		=> '',
								'help'			=> sprintf(
														__('Available Parameters: %s', 'exc-uploader-theme'), 
														'<br /> {blog_name}, {home_url}, {site_url}, {subscriber_name}, {subscriber_email}, {subscriber_activation_url}.')
							);