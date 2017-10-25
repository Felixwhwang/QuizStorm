<?php if ( ! defined('ABSPATH')) exit('restricted access');

if ( ! class_exists('eXc_Media_Post') )
{
	$this->load('core/abstracts/media_posts', '', true);
}

class eXc_Audio_Class extends eXc_Media_Post
{
	protected $name = 'exc_audio_post';
	protected $show_embed_btn = true;
	protected $config = array();
	
	function initialize()
	{
		if ( $this->is_download )
		{
			add_filter( 'shortcode_atts_audio', array( &$this, 'extend_shortcode' ), 1, 3 );
			add_filter( 'wp_audio_shortcode', array( &$this, 'add_download_button' ), 1, 2 );
		}
	}

	function supported_hosts(){}
	function register_metabox(){}
	
	function content_filter( $content )
	{
		if ( post_password_required() )
		{
			return $content;
			
		} elseif( ! is_front_page() && is_singular() )
		{
			// if we have shortcode then prefer it over attachments
			$post_id = get_the_ID();
			
			if ( has_shortcode( $content, 'playlist' ) )
			{
				preg_match_all( '@\[(\[?)(playlist)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)@i', $content, $matches, PREG_SET_ORDER );

				foreach( ( array ) $matches as $match )
				{
					$atts = shortcode_parse_atts( $match[3] );

					if ( exc_kv( $atts, 'type' ) != 'video' && ( $playlist = $this->skin_playlist( $match[0], $atts, $post_id ) ) )
					{
						$content .= preg_replace( '@' . preg_quote( $match[0] ) . '@i', $playlist, $content );
					}
				}
				
			} elseif ( has_shortcode( $content, 'audio' ) )
			{
				// Depreciated

			} elseif ( get_post_type() == $this->name )
			{
				if ( $audios = get_attached_media( 'audio' ) )
				{
					if ( count( $audios ) > 1 )
					{
						$content .= $this->skin_playlist( '[playlist type="audio" id="' . $post_id . '"]', array( 'ids' => $post_id ), $post_id );

					} else
					{
						$audio = current( $audios );

						$content .= wp_audio_shortcode(
								array(
									'url' => $audio->guid,
									'loop' => '',
									'autoplay' => false,
									'preload' => 'none'
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
				'name'                => _x( 'Audios', 'Audios Post', 'exc-uploader-theme' ),
				'singular_name'       => _x( 'Audio', 'Audio Post', 'exc-uploader-theme' ),
				'menu_name'           => __( 'Audios', 'exc-uploader-theme' ),
				'parent_item_colon'   => __( 'Parent Audio:', 'exc-uploader-theme' ),
				'all_items'           => __( 'All Audios', 'exc-uploader-theme' ),
				'view_item'           => __( 'View Audio', 'exc-uploader-theme' ),
				'add_new_item'        => __( 'Add New Audio', 'exc-uploader-theme' ),
				'add_new'             => __( 'Add Audio', 'exc-uploader-theme' ),
				'edit_item'           => __( 'Edit Audio', 'exc-uploader-theme' ),
				'update_item'         => __( 'Update Audio', 'exc-uploader-theme' ),
				'search_items'        => __( 'Search Audio', 'exc-uploader-theme' ),
				'not_found'           => __( 'Audio Not found', 'exc-uploader-theme' ),
				'not_found_in_trash'  => __( 'Audio Not found in Trash', 'exc-uploader-theme' ),
			);
			
		$args = array(
			'label'               => __( 'Audios', 'exc-uploader-theme' ),
			'description'         => __( 'You can manage your audio posts', 'exc-uploader-theme' ),
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
			'menu_icon'           => 'dashicons-format-audio',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'rewrite'			  => array('slug' => 'audios')
		);
		
		register_post_type($this->name, $args);
	}
}