<?php if ( ! defined('ABSPATH')) exit('restricted access');

if( ! class_exists('eXc_Media_Post') )
{
	$this->load('core/abstracts/media_posts', '', true);
}

class eXc_Radio_Class extends eXc_Media_Post
{
	protected $name = 'exc_radio_post';
	//protected $show_embed_btn = true;
	protected $config = array();
	
	function initialize()
	{
		add_action( 'init', array( &$this, 'register_taxonomy'), 0 );
		add_action( 'wp_ajax_exc_radio_widget', array( &$this, 'play_station' ) );
		add_action( 'wp_ajax_nopriv_exc_radio_widget', array( &$this, 'play_station' ) );

		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_files' ), 1 );

		add_shortcode( 'exc_radio', array( &$this, 'radio_shortcode' ) );
		//add_action( 'wp_enqueue_script', array( &$this, 'enqueue_script' ) );
	}

	function enqueue_files( $isWidget = false )
	{
		if ( is_singular( $this->name ) || $isWidget )
		{
			$library = apply_filters( 'wp_audio_shortcode_library', 'mediaelement' );

			if ( 'mediaelement' === $library )
			{
				wp_enqueue_style( 'wp-mediaelement' );
				wp_enqueue_script( 'wp-mediaelement' );
			}

			$radio_js = $this->eXc->get_file_url('views/js/radio.js', 'local_dir');

			wp_enqueue_script( 'exc-radio', $radio_js, array( 'wp-util', 'backbone', 'wp-mediaelement' ), '', true );

			$this->eXc->html->localize_script( 'exc-radio', 'exc_radio_settings', array( 'security' => wp_create_nonce('exc-radio-widget'), 'action' => 'exc_radio_widget' ), 'not_exists' );

			$this->eXc->html->load_template( 'widgets/templates/radio' );
			//wp_localize_script( 'exc-radio', 'exc_radio_settings', array( 'security' => wp_create_nonce('exc-radio-widget'), 'action' => 'exc_radio_widget' ) );
		}
	}

	function load_template()
	{
		add_action( 'wp_footer', array( &$this, '_load_template' ) );
	}

	function _load_template()
	{
		$this->eXc->load_template( 'modules/templates/radio' );
	}

	function play_station()
	{
		if ( ! wp_verify_nonce( $_POST['security'], 'exc-radio-widget' ) )
		{
			exc_die(
				__( 'Page Expired: Sorry, it seems that you may recently logged in or logged out and that\'s why the page was expired, please refresh it and try again.', 'exc-uploader-theme' )
			);
		}

		$station_id = ( int ) exc_kv( $_POST, 'station_id' );

		$data = $this->get_post_data( $station_id );

		if ( empty( $data ) )
		{
			exc_die( __('The radio playlist is empty.', 'exc-uploader-theme' ) );
		} else
		{
			exc_success( $data );
		}
	}

	public function get_post_data( $post_id = '' )
	{
		$post_id = ( ! intval( $post_id ) ) ? get_the_id() : $post_id;

		//@TODO: check post password
		$post = get_post( $post_id );

		if ( ! $post || $this->name != $post->post_type 
				|| 'publish' != $post->post_status )
		{
			exc_die( __("Invalid station id, please try again", 'exc-uploader-theme' ) );
		}

		// Load Upload Files
		$station_info = get_post_meta( $post_id, 'mf_radio', true );
		$ids = exc_kv( $station_info, 'playlist' );

		if ( ! empty( $ids ) )
		{
			$args = array(
						'post_status' 		=> 'inherit',
						'post_type' 		=> 'attachment',
						'post_mime_type' 	=> 'audio',
						'include'			=> $ids,
						'order' 			=> 'ASC',
						'orderby' 			=> 'post__in'
					);

			$_attachments = get_posts( $args );

			$attachments = array();

			foreach ( $_attachments as $key => $val )
			{
				$attachments[ $val->ID ] = $_attachments[ $key ];
			}

			if ( empty( $attachments ) )
			{
				return '';
			}

			$url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );

			$data = array(
						'type' 			=> 'audio',
						'tracklist' 	=> false,
						'tracknumbers' 	=> false,
						'images'		=> false,
						'artists'		=> true,
						'poster'		=> exc_kv( $url, 0 )
					);

			$tracks = array();

			foreach ( $attachments as $attachment )
			{
				$url = wp_get_attachment_url( $attachment->ID );
				$ftype = wp_check_filetype( $url, wp_get_mime_types() );

				$track = array(
					'src' => $url,
					'type' => $ftype['type'],
					'title' => $attachment->post_title,
					'caption' => $attachment->post_excerpt,
					'description' => $attachment->post_content
				);

				$track['meta'] = array();
				$meta = wp_get_attachment_metadata( $attachment->ID );

				if ( ! empty( $meta ) )
				{
					foreach ( wp_get_attachment_id3_keys( $attachment ) as $key => $label )
					{
						if ( ! empty( $meta[ $key ] ) )
						{
							$track['meta'][ $key ] = $meta[ $key ];
						}
					}
				}

				// Use only station featured image
				$tracks[] = $track;
			}

			$data['tracks'] = $tracks;
			
			wp_reset_query();

			return $data;
		}
	}
	
	public function supported_hosts(){}

	public function register_metabox()
	{
		$typenow = exc_kv( $GLOBALS, 'typenow' );

		if ( $this->name == $typenow )
		{
			$config = $this->eXc->load_config_file( 'metaboxes/radio_settings' );
			$this->eXc->wp_admin->edit( 'metabox' )->on( $this->name )->add_meta_box( __( 'Radio Settings', 'exc-uploader-theme' ), $config );
		}
	}

	public function content_filter( $content )
	{
		/*if ( is_singular( $this->name ) )
		{
			$station_id = get_the_ID();

			$this->radio_shortcode( array( 'station_id' => $station_id ) );
		}
*/
		return $content;
	}

	/*public function radio_shortcode( $atts = array() )
	{
		query_posts( $instance );

		$atts = shortcode_atts(
		 		array(
					'station_id'		=> 0,
					'poster'			=> ''
				), $atts, 'exc_radio_shortcode');

		if ( ! intval( $atts['station_id'] ) )
		{
			exc_die( __( 'The station information is invalid.', 'exc-uploader-theme' ) );
		}

		if ( empty( $atts['poster'] ) )
		{
			$atts['poster'] = exc_kv( (array) wp_get_attachment_image_src( get_post_thumbnail_id( $atts['station_id'] ), 'medium' ), 0, get_template_directory_uri() . '/images/no-image-180x180.png' );
		}

		$this->eXc->load_view( 'shortcodes/radio', $atts );
	}
*/
	public function register_taxonomy()
	{
		$labels = array(
			'name'              => _x( 'Genres', 'taxonomy general name', 'exc-uploader-theme' ),
			'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'exc-uploader-theme' ),
			'search_items'      => __( 'Search Genres', 'exc-uploader-theme' ),
			'all_items'         => __( 'All Genres', 'exc-uploader-theme' ),
			'parent_item'       => __( 'Parent Genre', 'exc-uploader-theme' ),
			'parent_item_colon' => __( 'Parent Genre:', 'exc-uploader-theme' ),
			'edit_item'         => __( 'Edit Genre', 'exc-uploader-theme' ),
			'update_item'       => __( 'Update Genre', 'exc-uploader-theme' ),
			'add_new_item'      => __( 'Add New Genre', 'exc-uploader-theme' ),
			'new_item_name'     => __( 'New Genre Name', 'exc-uploader-theme' ),
			'menu_name'         => __( 'Genre', 'exc-uploader-theme' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'genre' ),
		);

		register_taxonomy( 'genre', array( $this->name ), $args );
	}

	public function register_post_type()
	{
		$labels = array(
				'name'                => _x( 'Radios', 'Radios Post', 'exc-uploader-theme' ),
				'singular_name'       => _x( 'Radio', 'Radio Post', 'exc-uploader-theme' ),
				'menu_name'           => __( 'Radios', 'exc-uploader-theme' ),
				'parent_item_colon'   => __( 'Parent Radio:', 'exc-uploader-theme' ),
				'all_items'           => __( 'All Stations', 'exc-uploader-theme' ),
				'view_item'           => __( 'View Radio', 'exc-uploader-theme' ),
				'add_new_item'        => __( 'Add New Radio', 'exc-uploader-theme' ),
				'add_new'             => __( 'Add Station', 'exc-uploader-theme' ),
				'edit_item'           => __( 'Edit Radio', 'exc-uploader-theme' ),
				'update_item'         => __( 'Update Radio', 'exc-uploader-theme' ),
				'search_items'        => __( 'Search Radio', 'exc-uploader-theme' ),
				'not_found'           => __( 'Radio Not found', 'exc-uploader-theme' ),
				'not_found_in_trash'  => __( 'Radio Not found in Trash', 'exc-uploader-theme' ),
			);
			
		$args = array(
			'label'               => __( 'Radios', 'exc-uploader-theme' ),
			'description'         => __( 'You can manage your radio posts', 'exc-uploader-theme' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-format-audio',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			'rewrite'			  => array('slug' => 'radio')
		);
		
		register_post_type( $this->name, $args );
	}
}