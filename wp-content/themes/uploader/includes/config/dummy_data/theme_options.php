<?php
defined('ABSPATH') OR die('no direct file access allowed.');

$url = parse_url( site_url() );
$host = ( ! empty( $url['host'] ) ) ? $url['host'] : 'example.com';


$options = array(

    'mf_general_settings' => 
      array (
        'structure' => 'full-width',
        'bg_pattern' => '',
        'featured_image' => 'on',
      ),
      'mf_notifications' => 
      array (
        'status' => 'on',
        'random' => 'on',
        'close_btn' => 'on',
        'top_notifications' => 
        array (
          0 => 
          array (
            'title' => 'Extracoding Customization Services',
            'visible_to' => 'all',
            'content' => 'Did you know? that uploader theme has built-in functionality to restrict the direct access to wordpress admin panel for general site users.',
            'status' => 'on',
          ),
        ),
        'bg_color' => '#e74c3c',
        'text_color' => '#ffffff',
        'btn_bg' => '#ffffff',
        'btn_text_color' => '#e74c3c',
        'btn_text_hover_color' => '#e74c3c',
      ),
      'mf_header_settings' => 
      array (
        'logo' => site_url() . '/wp-content/themes/uploader/images/logo.png',
        'logo_width' => '',
        'logo_height' => '',
        'header_is_sticky' => '',
        'header_bg_image' => site_url() . '/wp-content/themes/uploader/images/header.jpg',
        'header_bg_color' => '#ffffff',
        'header_transparency' => '1',
        'header_menu_status' => 'on',
        'header_menu_text_color' => '#424242',
        'header_menu_hover_color' => '#e74c3c',
        'header_menu_padding_top' => '38',
        'header_menu_padding_right' => '25',
        'header_menu_padding_bottom' => '38',
        'header_menu_padding_left' => '25',
        'header_member_status' => 'on',
        'header_member_bg_color' => '#ffffff',
        'header_member_text_color' => '#424242',
        'header_topbar_status' => '',
        'header_topbar_email_addr' => get_option('admin_email'),
        'header_topbar_phone' => '+44 7777 110022',
        'header_topbar_social_icons' => 'on',
        'header_topbar_color' => '#ffffff',
        'header_topbar_bg_color' => '#1b2126',
        'header_topbar_bg_opacity' => '1',
        'hc_bg_image' => site_url() . '/wp-content/themes/uploader/images/header.jpg',
        'hc_bg_color' => '#ffffff',
        'hc_transparency' => '1',
        'hc_menu_status' => 'on',
        'hc_menu_text_color' => '#424242',
        'hc_menu_hover_color' => '#e74c3c',
        'hc_menu_padding_top' => '18',
        'hc_menu_padding_right' => '25',
        'hc_menu_padding_bottom' => '18',
        'hc_menu_padding_left' => '25',
        'hc_member_status' => 'on',
        'hc_member_bg_color' => '#ffffff',
        'hc_member_text_color' => '#424242',
        'hc_topbar_status' => '',
        'hc_topbar_email_addr' => get_option('admin_email'),
        'hc_topbar_phone' => '+44 7777 110022',
        'hc_topbar_social_icons' => 'on',
        'hc_topbar_color' => '#ffffff',
        'hc_topbar_bg_color' => '#1b2126',
        'hc_topbar_bg_opacity' => '1',
        'hcl_bg_image' => site_url() . '/wp-content/themes/uploader/images/header.jpg',
        'hcl_bg_color' => '#ffffff',
        'hcl_transparency' => '1',
        'hcl_menu_status' => 'on',
        'hcl_menu_text_color' => '#424242',
        'hcl_menu_text_hove_color' => '#e74c3c',
        'hcl_menu_padding_top' => '18',
        'hcl_menu_padding_right' => '25',
        'hcl_menu_padding_bottom' => '18',
        'hcl_menu_padding_left' => '25',
        'hcl_member_status' => 'on',
        'hcl_member_bg_color' => '#ffffff',
        'hcl_member_text_color' => '#424242',
        'hcl_contact_status' => 'on',
        'hcl_contact_email_addr' => get_option('admin_email'),
        'hcl_contact_phone' => '+44 7777 110022',
      ),
      'mf_member_settings' => 
      array (
        'status' => 'on',
        'frontend_login' => '',
        'admin_bar' => 
        array (
          0 => 'administrator',
        ),
        
        'register_as' => 'contributor',
        'bg_attachments' => '',
        'signin_btn' => 'Sign In to Uploader',
      ),
      'mf_uploader_settings' => 
      array (
        'status' => 'on',
        'posts_limit' => '100',
        'attachments_limit' => '20',
        'featured_image' => 'on',
        'allowed_post_types' => 
        array (
          0 => 'post',
          1 => 'exc_audio_post',
          2 => 'exc_video_post',
          3 => 'exc_image_post',
        ),
        'allowed_mime' => 
        array (
          0 => 'image',
          1 => 'video',
          2 => 'audio',
        ),
        'post_status' => 'pending',
        
        'rename' => 'on',
        'prevent_duplicates' => 'on',
        'max_file_size' => wp_max_upload_size(),
        'heading' => 'Share Your Media',
        'about' => 'Uploader is a powerful theme to share your media files with drag & drop functionality.',
        'btn' => 'Upload Media',
        'dropfiles' => 'Just Drop your files here',
      ),
      'mf_layout' => 
      array (
        'default_header' => '',
        'default_slider' => 'with_uploader',
        'default_revslider_id' => '',
        'default_header_sidebar_status' => '',
        'default_header_sidebar' => '',
        'default_structure' => 'right-sidebar',
        'default_left_sidebar' => '',
        'default_right_sidebar' => 'home-page-right-sidebar',
        'default_columns' => '3',
        'default_list_columns' => '3',
        'default_download_btn' => 'on',
        'archives_header' => '',
        'archives_slider' => '',
        'archives_revslider_id' => '',
        'archives_header_sidebar_status' => '',
        'archives_header_sidebar' => '',
        'archives_structure' => 'full-width',
        'archives_left_sidebar' => '',
        'archives_right_sidebar' => '',
        'archives_post_type' => 
        array (
          0 => 'post',
          1 => 'exc_audio_post',
          2 => 'exc_video_post',
          3 => 'exc_image_post',
        ),
        'archives_show_filtration' => 'on',
        'archives_active_view' => '',
        'archives_columns' => 4,
        'archives_list_columns' => 2,
        'categories_header' => '',
        'categories_slider' => '',
        'categories_revslider_id' => '',
        'categories_header_sidebar_status' => '',
        'categories_header_sidebar' => '',
        'categories_structure' => 'right-sidebar',
        'categories_left_sidebar' => '',
        'categories_right_sidebar' => 'home-page-right-sidebar',
        'categories_post_type' => 
        array (
          0 => 'post',
          1 => 'exc_audio_post',
          2 => 'exc_video_post',
          3 => 'exc_image_post',
        ),
        'categories_show_filtration' => 'on',
        'categories_active_view' => '',
        'categories_columns' => 3,
        'categories_list_columns' => 2,
        'tags_header' => '',
        'tags_slider' => '',
        'tags_revslider_id' => '',
        'tags_header_sidebar_status' => '',
        'tags_header_sidebar' => '',
        'tags_structure' => 'full-width',
        'tags_left_sidebar' => '',
        'tags_right_sidebar' => '',
        'tags_post_type' => 
        array (
          0 => 'post',
          1 => 'exc_audio_post',
          2 => 'exc_video_post',
          3 => 'exc_image_post',
        ),
        'tags_show_filtration' => 'on',
        'tags_active_view' => '',
        'tags_columns' => 3,
        'tags_list_columns' => 2,
        'search_header' => '',
        'search_header_sidebar_status' => '',
        'search_header_sidebar' => '',
        'search_structure' => 'full-width',
        'search_left_sidebar' => '',
        'search_right_sidebar' => '',
        'search_post_type' => 
        array (
          0 => 'post',
          1 => 'exc_audio_post',
          2 => 'exc_video_post',
          3 => 'exc_image_post',
        ),
        'search_show_filtration' => 'on',
        'search_active_view' => '',
        'search_columns' => 4,
        'search_list_columns' => 2,
        'browse_categories_header' => '',
        'browse_categories_slider' => '',
        'browse_categories_revslider_id' => '',
        'browse_categories_header_sidebar_status' => '',
        'browse_categories_header_sidebar' => '',
        'browse_categories_structure' => 'right-sidebar',
        'browse_categories_left_sidebar' => '',
        'browse_categories_right_sidebar' => 'home-page-right-sidebar',
        'browse_categories_columns' => 3,
        'genre_header' => '',
        'genre_slider' => '',
        'genre_revslider_id' => '',
        'genre_header_sidebar_status' => '',
        'genre_header_sidebar' => '',
        'genre_structure' => 'right-sidebar',
        'genre_left_sidebar' => '',
        'genre_right_sidebar' => 'home-page-right-sidebar',
        'contact_header' => '',
        'contact_header_sidebar_status' => '',
        'contact_header_sidebar' => '',
        'contact_structure' => 'full-width',
        'contact_left_sidebar' => '',
        'contact_right_sidebar' => '',
        'users_header' => '',
        'users_slider' => '',
        'users_revslider_id' => '',
        'users_structure' => 'full-width',
        'users_left_sidebar' => '',
        'users_right_sidebar' => '',
        'users_user_roles' => 'subscriber',
        'users_show_filtration' => 'on',
        'users_columns' => 3,
        'ajax_pagi' => 'on',
        'ajax_blog_pagi' => 'on',
        'ajax_comments_pagi' => 'on',
      ),
      'mf_sidebars' => array(),
      'mf_style_settings' => 
      array (
        'primary_bg' => '#e74c3c',
        'primary_color' => '#e74c3c',
        'primary_border_color' => '#e74c3c',
        'menu_border_color' => '#e74c3c',
      ),
      'mf_mail_settings' => 
      array (
        'to' => get_option('admin_email'),
        'from_name' => 'Wordpress',
        'from_email' => 'wordpress@' . $host,
        'smtp_status' => '',
        'smtp_auth' => 'on',
        'smtp_host' => '',
        'smtp_username' => '',
        'smtp_password' => '',
        'smtp_port' => '',
        'smtp_debug' => '0',
        'smtp_secure' => '',
        'user_status' => 'on',
        'user_subject' => '{blog_name} Welcome to our Community',
        'user_body' => 'Hello {user_login},

    Thank you for signing up on {$host}.

    Username: {user_login}
    Password: {user_password}
    {login_url}

    Thanks,
    {blog_name}',
        'recovery_subject' => 'Reset Password request',
        'recovery_body' => 'Hi {user_login},

    We received a request to change your password on {blog_name}.

    If you requested a reset for {user_login}, Click the link below to set a new password or If you didn\'t make this request, please ignore this email.

    {reset_password_link}
    Regards,
    {blog_name}',
        'like_status' => 'on',
        'like_content_type' => 'html',
        'like_subject' => 'A new like on {post_title}',
        'like_body' => 'Hi {author_name},

    A user {user_name} liked your post on {post_title}
    {post_url}

    Regards,
    {blog_name}',
        'follow_status' => '',
        'follow_content_type' => 'html',
        'follow_subject' => 'A new user is following you',
        'follow_body' => 'Hi {author_name},

    A user {user_name} is following you on {blog_name}
    {home_url}

    Regards,
    {blog_name}',
        'subscriber_status' => '',
        'subscriber_content_type' => 'text',
        'subscriber_subject' => '',
        'subscriber_body' => '',
      ),
      'mf_social_media' => 
      array (
        'facebook' => 'http://facebook.com/extracoding',
        'twitter' => 'http://twitter.com/extracoding',
        'gplus' => 'http://plus.google.com/getstarted?fww=1',
        'instagram' => 'http://instagram.com/',
        'youtube' => 'http://youtube.com',
        'vimeo' => 'http://vimeo.com',
        'soundcloud' => 'http://soundcloud.com',
        'flickr' => 'http://www.flickr.com',
      ),
      'mf_footer_settings' => 
      array (
        'is_sticky' => '',
        'copyright' => 'Copyright 2016 All rights reserved - Designed By Themebazaar',
        'bg_color' => '#1b2126',
        'r_bg_color' => '#000000',
        'text_color' => '#ffffff',
        'menu_text_color' => '#ffffff',
      ),
    );