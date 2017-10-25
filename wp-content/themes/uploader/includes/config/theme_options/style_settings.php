<?php defined('ABSPATH') OR die('restricted access');

$options = array(
				'title'		=> esc_html__('Style Settings', 'exc-uploader-theme'),
				'db_name'	=> 'mf_style_settings',
			);

$options['_config']['_settings'] =
						array(
							'colors' =>
								array(
									'heading'		=> esc_html__('Theme Color', 'exc-uploader-theme'),
									'description'	=> esc_html__('You Can change theme background and primary colors.', 'exc-uploader-theme'),
								),
						);

$options['_config']['colors']['primary_bg'] = array(
								'label'			=> esc_html__('Background Color', 'exc-uploader-theme'),
								'type'			=> 'colorpicker',
								'attrs'			=> array(
														'class' => 'form-control',
													),

								'default'		=> '#e74c3c',
 								'css_selector'	=> '.btn-primary, 
 													.media-types .btn:hover, 
 													.media-types .btn.active, 
 													.media-views .grid-view:hover span, 
 													.media-views .list-view:hover span, 
 													.media-views .grid-view.active span, 
 													.media-views .list-view.active span, 
 													.media-views .grid-view.active span:before, 
 													.media-views .grid-view.active span:after, 
 													.media-views .grid-view:hover span:before, 
 													.media-views .grid-view:hover span:after, 
 													.media-search .search .btn:hover, 
 													.media-search .search .form-control:focus ~ .btn, 
 													.media-search .search .form-control:hover ~ .btn, 
 													.post-type-icon, 
 													.mejs-controls .mejs-time-rail .mejs-time-current, 
 													.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current, 
 													.comment-reply-link, .comment-form input[type="submit"], 
 													.widget .header, 
 													.widget_exc_radio_widget .exc-play-station, 
 													.widget_exc_user_widget .status-open, .widget_tag_cloud > .tagcloud > a, 
 													.widget_nav_menu ul li a:hover .user-status, 
													.searchform .search input[type="submit"], 
													.wp-pagination span, 
													.pagination a li:hover, 
													.double-bounce1, 
													.double-bounce2, 
													.widget_exc_user_widget .user-info img, 
													.widget_exc_radio_widget .station-info img, 
													.entry-by .author-pic img, .content-button label.btn, 
													.page-banner, 
													.exc-attachment.featured .attachment-desc, .overlay-actions .exc-media-delete-btn',

 								'prop_name'		=> 'background-color',
								'help'			=> esc_html__('Change the background color of theme. Default is #e74c3c.', 'exc-uploader-theme'),
							);

$options['_config']['colors']['primary_color'] = array(
								'label'			=> esc_html__('Primary Color', 'exc-uploader-theme'),
								'type'			=> 'colorpicker',
								'attrs'			=> array(
														'class' => 'form-control',
													),

								'default'		=> '#e74c3c',
 								'css_selector'	=> 'a, a:hover, 
 													a:focus, h1 a:hover, h2 a:hover, h3 a:hover, 
 													h4 a:hover, h5 a:hover, 
 													h6 a:hover, 
 													.tags a, 
 													.welcome-btn .dropdown-menu li a:hover, 
 													.media-categories .dropdown-menu > li > a.active, 
 													.sort-by .dropdown-menu > li > a.active, 
 													.post-views li a:hover, 
 													.exc-user-statistics li a:hover, 
 													.related-entry:hover figcaption, 
 													.category-box:hover .catg-footer, 
 													.station-content p > a:hover, 
 													#wp-calendar td#today, #wp-calendar td#prev a, 
 													#wp-calendar td#next a, .widget_nav_menu ul li a:hover, 
 													.widget_recent_entries ul li a:hover, 
 													.widget_categories ul > li > a:hover, 
 													.widget_archive ul > li > a:hover, 
 													.widget_pages ul li.current_page_item a, 
 													.widget_pages ul li a:hover, 
 													.widget_meta ul li a:hover, 
 													.widget_recent_comments ul li a:hover, 
 													.widget_rss ul li a:hover, 
 													.error-page-text a, .wp-pagination a, .tags a:hover, 
 													.entry-by .author-profession a:hover, 
 													.entry-by .author-name a:hover',

 								'prop_name'		=> 'color',
								'help'			=> esc_html__('Change the primary color of theme. Default is #e74c3c.', 'exc-uploader-theme'),
							);