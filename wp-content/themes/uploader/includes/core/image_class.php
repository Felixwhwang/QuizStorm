<?php if ( ! defined('ABSPATH')) exit('restricted access');

class eXc_Image_Class extends eXc_Media_Post
{
	protected $name = 'exc_image_post';
	protected $show_embed_btn = true;
	protected $config = array();

	function initialize(){}
	function supported_hosts(){}
	function register_metabox(){}
	
	function content_filter( $content )
	{
		if ( post_password_required() )
		{
			return $content;

		} elseif ( ! is_front_page() && is_singular() )
		{
			//if we have shortcode or images manually inserted then prefer it over attachments and keep wordpress behaviour
			if ( has_shortcode( $content, 'gallery' ) || ( strpos( $content, 'wp-image-' ) !== false ) )
			{
				preg_match_all( '@\[(\[?)(gallery)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)@i', $content, $matches, PREG_SET_ORDER );

				foreach( ( array ) $matches as $match )
				{
					$content .= $match[0];
				}

			} elseif( get_post_type() == $this->name )
			{
				if ( $images = get_attached_media( 'image' ) ) 
				{
					foreach( $images as $image )
					{
						$attachment = wp_get_attachment_image_src( $image->ID, 'large');

						$content .= '<p><img src="' . esc_url( $attachment[0] ). '" width="' . esc_attr( $attachment[1] ) . '" alt="" /></p>';
					}

				}

			} elseif ( is_attachment() )
			{
				$content .= '<p>' . wp_get_attachment_link( get_the_ID() , 'full' ) . '</p>';
			}
		}
		
		return $content;
	}

	function register_post_type()
	{
		$labels = array(
				'name'                => _x( 'Images', 'Images Post', 'exc-uploader-theme' ),
				'singular_name'       => _x( 'Image', 'Image Post', 'exc-uploader-theme' ),
				'menu_name'           => __( 'Images', 'exc-uploader-theme' ),
				'parent_item_colon'   => __( 'Parent Image:', 'exc-uploader-theme' ),
				'all_items'           => __( 'All Images', 'exc-uploader-theme' ),
				'view_item'           => __( 'View Image', 'exc-uploader-theme' ),
				'add_new_item'        => __( 'Add New Image', 'exc-uploader-theme' ),
				'add_new'             => __( 'Add Image', 'exc-uploader-theme' ),
				'edit_item'           => __( 'Edit Image', 'exc-uploader-theme' ),
				'update_item'         => __( 'Update Image', 'exc-uploader-theme' ),
				'search_items'        => __( 'Search Image', 'exc-uploader-theme' ),
				'not_found'           => __( 'Image Not found', 'exc-uploader-theme' ),
				'not_found_in_trash'  => __( 'Image Not found in Trash', 'exc-uploader-theme' ),
			);
			
		$args = array(
			'label'               => __( 'Images', 'exc-uploader-theme' ),
			'description'         => __( 'You can manage your images posts', 'exc-uploader-theme' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'comments', 'author', 'revisions'),
			'taxonomies'          => array( 'category', 'post_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-format-image',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'rewrite'			  => array('slug' => 'images')
		);
		
		register_post_type($this->name, $args);
	}
}

//new eXc_image_post;