<?php if( ! defined('ABSPATH')) exit('restricted access');

if( ! class_exists("eXc_Wysiwyg_Field") )
{
	class eXc_Wysiwyg_Field extends eXc_Fields_Abstract
	{
		function enqueue_files()
		{
			wp_enqueue_style( 'buttons' );
			wp_enqueue_style( 'editor-buttons' );
		}

		function html($return = false)
		{
			//stop buffering
			ob_start();
			
			//load wordpress built-in editor
			wp_editor( $this->set_value(), $this->name, $this->config['wp_editor'] );
			
			$this->config['markup'] = ob_get_clean();
			
			return $this->apply_skin($return);
		}
		
		function normalize_data()
		{
			$this->config['wp_editor'] =
				wp_parse_args( exc_kv( $this->config, 'wp_editor', array() ),
								array(
									'wpautop'		=> true,
									'dwf'			=> false,
									'media_buttons' => false,
									'textarea_rows' => 3,
									'textarea_name'	=> $this->name
								)
							);
		}
	}
}