<?php if ( ! defined('ABSPATH')) exit('restricted access');

if( ! class_exists('eXc_Media_Post') )
{
	$this->load('core/abstracts/media_posts', '', true);
}

class eXc_Video_Class extends eXc_Media_Post
{
	protected $name = 'exc_video_post';
	protected $show_embed_btn = true;
	protected $config = array();
	
	function initialize()
	{
		if ( $this->is_download )
		{
			add_filter( 'shortcode_atts_video', array( &$this, 'extend_shortcode' ), 1, 3 );
			add_filter( 'wp_video_shortcode', array( &$this, 'add_download_button' ), 1, 2 );
		}
	}

	function supported_hosts(){}
	function register_metabox(){}
	
	function content_filter( $content )
	{
		if ( post_password_required() )
		{
			return $content;
			
		} elseif ( ! is_front_page() && is_singular() )
		{
			$post_id = get_the_ID();
			
			//if we have shortcode then prefer it over attachments
			if ( has_shortcode( $content, 'playlist' ) )
			{
				preg_match_all( '@\[(\[?)(playlist)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)@i', $content, $matches, PREG_SET_ORDER );

				foreach ( ( array ) $matches as $match )
				{
					$atts = shortcode_parse_atts( $match[3] );

					if ( exc_kv( $atts, 'type' ) == 'video' && ( $playlist = $this->skin_playlist( $match[0], $atts, $post_id ) ) )
					{
						$content .= preg_replace( '@' . preg_quote( $match[0] ) . '@i', $playlist, $content );
					}
				}
				
			} elseif ( has_shortcode( $content, 'video' ) )
			{
				// Depreciated
			} elseif ( get_post_type() == $this->name )
			{
				if ( $videos = get_attached_media( 'video' ) )
				{
					$poster = exc_kv( wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' ), '0' );
				
					if ( count( $videos ) > 1 )
					{
						$ids = implode( ', ', (array) array_keys( $videos ) );

						$content .= $this->skin_playlist( '[playlist type="video" ids="' . $ids . '"]', array( 'ids' => $ids ), $post_id );
						
					} else
					{
						$video = current( $videos );

						$content .= wp_video_shortcode(
								array(
									'url'		=> $video->guid,
									'loop'		=> '',
									'autoplay'	=> false,
									'poster'	=> $poster,
									'width'		=> 980,
									'height'	=> 735,
									'preload'	=> 'none'
								)
							);
					}
				}
			}
		}
		
		return $content;
	}

	function register_post_type()
	{
		$labels = array(
				'name'                => _x( 'Videos', 'Videos Post', 'exc-uploader-theme' ),
				'singular_name'       => _x( 'Video', 'Video Post', 'exc-uploader-theme' ),
				'menu_name'           => __( 'Videos', 'exc-uploader-theme' ),
				'parent_item_colon'   => __( 'Parent Video:', 'exc-uploader-theme' ),
				'all_items'           => __( 'All Videos', 'exc-uploader-theme' ),
				'view_item'           => __( 'View Video', 'exc-uploader-theme' ),
				'add_new_item'        => __( 'Add New Video', 'exc-uploader-theme' ),
				'add_new'             => __( 'Add Video', 'exc-uploader-theme' ),
				'edit_item'           => __( 'Edit Video', 'exc-uploader-theme' ),
				'update_item'         => __( 'Update Video', 'exc-uploader-theme' ),
				'search_items'        => __( 'Search Video', 'exc-uploader-theme' ),
				'not_found'           => __( 'Video Not found', 'exc-uploader-theme' ),
				'not_found_in_trash'  => __( 'Video Not found in Trash', 'exc-uploader-theme' ),
			);
			
		$args = array(
			'label'               => __( 'videos', 'exc-uploader-theme' ),
			'description'         => __( 'You can manage your video posts', 'exc-uploader-theme' ),
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
			'menu_icon'           => 'dashicons-video-alt2',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'rewrite'			  => array('slug' => 'videos')
		);
		
		register_post_type( $this->name, $args );
	}
}