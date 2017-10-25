<?php defined('ABSPATH') OR die('restricted access');

//SUB, DYNAMIC
$options = array(
				'_layout' => 'widget',
			);
			

$options['_config']['title'] = array(
								'label'		=> __('Title', 'exc-uploader-theme'),
								'type'		=> 'text',
								'attrs'		=> array(
													'class' => 'widefat',
												),
									
								'default'	=> _x('Recent Posts', 'Extracoding Advanced Posts Widget', 'exc-uploader-theme'),
								'validation'=> 'required',
								'skin'		=> 'wp_widget',
								'help'		=> __('Enter a title for this widget.', 'exc-uploader-theme'),
							);

$options['_config']['template'] = array(
								'label'			=> __('Template', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control' ),
								'skin'			=> 'wp_widget',
								'options'		=> array(
														'style1' 	=> __( 'Style 1', 'exc-uploader-theme' ),
														'style2' 	=> __( 'Style 2', 'exc-uploader-theme' ),
													),

								'validation'	=> 'required',
								'help' 			=> __('Select a template for the widget posts.', 'exc-uploader-theme'),
							);
							

$options['_config']['category__in'] = array(
								'label'			=> __('Categories', 'exc-uploader-theme'),
								'type'			=> 'select',
								'attrs'			=> array( 'class' => 'form-control', 'multiple' => 'multiple' ),
								'skin'			=> 'wp_widget',
								'options'		=> exc_get_categories_list( array( 'hide_empty' => 0 ) ),
								'validation'	=> 'required',
								'help' 			=> __('Show posts from selected categories only.', 'exc-uploader-theme'),
							);
							
$options['_config']['posts_per_page'] = array(
								'label' => __('Posts Limit', 'exc-uploader-theme'),
								'type'	=> 'text',
								'attrs' => array(
											'class' => 'widefat',
									),
								'default' => '10',
								'validation' => 'required|is_natural',
								'skin' => 'wp_widget',
								'help' => __('Limit the number of posts to show.', 'exc-uploader-theme'),
							);

$options['_config']['orderby'] = array(
								'label' => __('Order By', 'exc-uploader-theme'),
								'type'	=> 'select',
								'attrs' => array(
											'class' => 'widefat',
									),
								
								'options' => array(
										'ID' 				=> _x('ID', 'Extracoding Advanced Posts Widget', 'exc-uploader-theme'),
										'author'			=> _x('Author', 'Extracoding Advanced Posts Widget', 'exc-uploader-theme'),
										'title'				=> _x('Title', 'Extracoding Advanced Posts Widget', 'exc-uploader-theme'),
										'name'				=> _x('Name', 'Extracoding Advanced Posts Widget', 'exc-uploader-theme'),
										'type'				=> _x('Post Type', 'Extracoding Advanced Posts Widget', 'exc-uploader-theme'),
										'date'				=> _x('Date', 'Extracoding Advanced Posts Widget', 'exc-uploader-theme'),
										'modified'			=> _x('Last Modified Date', 'Extracoding Advanced Posts Widget', 'exc-uploader-theme'),
										'parent'			=> _x('Parent', 'Extracoding Advanced Posts Widget', 'exc-uploader-theme'),
										'rand'				=> _x('Random', 'Extracoding Advanced Posts Widget', 'exc-uploader-theme'),
										'commment_count'	=> _x('Comment Count', 'Extracoding Advanced Posts Widget', 'exc-uploader-theme'),
									),
									
								'selected' => 'post_count',
								'validation' => 'required',
								'skin' => 'wp_widget',
								'help' => __('Change the posts order.', 'exc-uploader-theme'),
							);

$options['_config']['order'] = array(
								'label' => __('Order', 'exc-uploader-theme'),
								'type'	=> 'select',
								'attrs' => array(
											'class' => 'widefat',
									),
								
								'options' => array(
										'ASC' => _x('Ascending', 'Extracoding Advanced Posts Widget', 'exc-uploader-theme'),
										'DESC' => _x('Descending', 'Extracoding Advanced Posts Widget', 'exc-uploader-theme'),
									),
									
								'selected' => 'DESC',
								'validation' => 'required',
								'skin' => 'wp_widget',
								'help' => __('Show posts in ascending or descending order.', 'exc-uploader-theme'),
							);