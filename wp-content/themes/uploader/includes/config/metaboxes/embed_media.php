<?php defined('ABSPATH') OR die('restricted access');

//SUB, DYNAMIC
$options = array(
				'title' => __('Theme Options', 'exc-uploader-theme'),
				'_layout' => 'metabox',
			);
			

$options['_config']['block']['_settings'] =
							array(
								'post' => array(
									'heading' => __('Media Settings', 'exc-uploader-theme'),
									'description' => sprintf( __('Responsive Web design is the approach that suggests that design and development should respond to the user\'s behavior and environment based on screen size, platform and orientation. %s', 'exc-uploader-theme'),
																'<a href="http://www.smashingmagazine.com/2011/01/12/guidelines-for-responsive-web-design/" target="_blank">Read more</a>'),
								),
								
							);


$options['_config']['block']['post']['tabs']['general']['thumb'] = array(
								'label' => __('Thumbnail', 'exc-uploader-theme'),
								'type'	=> 'image',
								'attrs' => array(
											'class' => 'form-control',
									),
								
								'validation' => 'required|valid_url',
								'help' => __('The thumbnail of POST.', 'exc-uploader-theme'),
							);

$options['_config']['block']['post']['tabs']['general']['post_title'] = array(
								'label' => __('Title', 'exc-uploader-theme'),
								'type'	=> 'text',
								'attrs' => array(
											'class' => 'form-control',
									),

								'validation' => 'required',
								'help' => __('Enable or Disable responsive functionality.', 'exc-uploader-theme'),
							);

$options['_config']['block']['post']['tabs']['general']['post_content'] = array(
								'label' => __('Description', 'exc-uploader-theme'),
								'type'	=> 'textarea',
								'attrs' => array(
											'class' => 'form-control',
											'data-size' => 'medium',
									),
								'validation' => 'required',
								'help' => __('All users will be forced to login from frontend only.', 'exc-uploader-theme'),
							);

$options['_config']['block']['post']['tabs']['general']['tags_input'] = array(
								'label' => __('Tags', 'exc-uploader-theme'),
								'type'	=> 'textarea',
								'attrs' => array(
											'class' => 'form-control',
											'data-size' => 'medium',
									),
								
								'help' => __('All users will be forced to login from frontend only.', 'exc-uploader-theme'),
							);

$options['_config']['block']['post']['tabs']['general']['license'] = array(
								'label' => __('License', 'exc-uploader-theme'),
								'type'	=> 'text',
								'attrs' => array(
											'class' => 'form-control',
									),
								
								'help' => __('All users will be forced to login from frontend only.', 'exc-uploader-theme'),
							);

$options['_config']['block']['post']['tabs']['uploader']['author__avatar'] = array(
								'label' => __('Avatar', 'exc-uploader-theme'),
								'type'	=> 'image',
								'attrs' => array(
											'class' => 'form-control',
											'data-size' => 'medium',
									),
									
									
								'help' => __('All users will be forced to login from frontend only.', 'exc-uploader-theme'),
							);
														
$options['_config']['block']['post']['tabs']['uploader']['author__name'] = array(
								'label' => __('name', 'exc-uploader-theme'),
								'type'	=> 'text',
								'attrs' => array(
											'class' => 'form-control',
											'disabled' => true,
									),
								
								'help' => __('All users will be forced to login from frontend only.', 'exc-uploader-theme'),
							);
							
if( exc_kv($args, 'isList') )
{

$options['_config']['block']['post']['tabs']['sync']['status'] = array(
								'label' => __('Sync', 'exc-uploader-theme'),
								'type'	=> 'switch',
								'attrs' => array(
											'class' => 'form-control',
									),
								
								'help' => __('All users will be forced to login from frontend only.', 'exc-uploader-theme'),
							);
							
$options['_config']['block']['post']['tabs']['sync']['interval'] = array(
								'label' => __('Interval', 'exc-uploader-theme'),
								'type'	=> 'text',
								'attrs' => array(
											'class' => 'form-control',
									),
								
								'default' => (60 * 60 * 24),
								'help' => __('In second default is 1 day.', 'exc-uploader-theme'),
							);

}
