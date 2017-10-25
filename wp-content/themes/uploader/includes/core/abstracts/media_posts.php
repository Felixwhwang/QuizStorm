<?php if( ! defined('ABSPATH')) exit('restricted access');

if( ! class_exists('eXc_Media_Post') )
{
	abstract class eXc_Media_Post
	{
		protected $eXc;
		
		protected $name;
		
		protected $show_embed_btn = false;
		
		//protected $strip_content = true;

		protected $is_download = false;

		abstract protected function initialize();
		
		abstract protected function supported_hosts();
		
		abstract public function register_post_type();
		
		abstract public function content_filter( $content );
		
		abstract public function register_metabox();
		
		function __construct( &$eXc )
		{
			$this->eXc = $eXc;

			//Register Post Type
			add_action( 'init', array( &$this, 'register_post_type' ), 0 );
			
			// Page Builder for pages
			add_action( 'load-post.php', array( &$this, 'register_metabox' ) );
			add_action( 'load-post-new.php', array( &$this, 'register_metabox' ) );

			// Filter for views counter and other processing
			//add_filter( 'the_content', array( &$this, 'content_filter' ) );

			$layout_settings = get_option( 'mf_layout' );

			//$this->strip_content = ( exc_kv( $layout_settings, 'default_strip_content') == 'on' ) ? true : false;
			$this->is_download = ( exc_kv( $layout_settings, 'default_download_btn') == 'on' ) ? true : false;

			$this->initialize();
		}

		function extend_shortcode( $out, $pairs, $atts )
		{
			$out['download'] = $this->is_download;

			if ( isset( $atts['download'] ) )
			{
				$out['download'] = ( $atts['download'] == 'true' ) ? true : false;
			}

			return $out;
		}

		function add_download_button( $output, $atts )
		{
			preg_match('@<(video|audio)@i', $output, $matches );

			if ( ! empty( $atts['download'] ) )
			{
				$output = preg_replace('@<(video|audio)@i', '<$1 data-download="1"', $output, 1 );
			}

			return $output;
		}

		function skin_playlist( $shortcode, $atts, $post_id )
		{
			$background_image = isset( $atts['background_image'] ) ? $atts['background_image'] : true;

			$bg_image = '';

			if ( ! $background_image )
			{
				return;
				
			} else if( wp_http_validate_url( $background_image ) ) // replace it with valid_url function
			{
				$bg_image = $background_image;

			} else if ( has_post_thumbnail( $post_id ) )
			{
				$thumb_id = get_post_thumbnail_id();
				$thumb_url = wp_get_attachment_image_src( $thumb_id, 'original' );
				$bg_image = $thumb_url[0];
			}

			// if ( $bg_image )
			// {
				@ob_start();
				echo do_shortcode( $shortcode );
				$playlist = ob_get_contents();
				ob_end_clean();

				$download = ( $this->is_download ) ? 'data-download="1" ' : '';

				if ( isset( $atts['download'] ) && empty( $atts['download'] ) )
				{
					$download = '';
				}

				return preg_replace('@class="wp-playlist@', $download . 'style="background: url(' . $bg_image . ')" class="wp-playlist', $playlist, 1 );
			//}

			return;
		}
	}
}