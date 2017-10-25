<?php if( ! defined('ABSPATH')) exit('restricted access');

if( ! class_exists('eXc_Url_Field'))
{
	$this->load('core/fields/url_field');
}

if ( ! class_exists( 'eXc_OEmbed_Field' ) )
{
	class eXc_OEmbed_Field extends eXc_Url_Field
	{
		var $default_skin = 'oembed';
		
		/**
			1. Ajax Callback to this field
			2. Each javascript files will use global settings variables, that will reduce the size of javascript files
		*/
		
		function enqueue_files()
		{
			$this->eXc->html->load_js( $this->eXc->system_url('views/js/fields/oembed.js') )
								->load_css( $this->eXc->system_url('views/css/fields/oembed.css') );
		}
		
		function add_actions()
		{
			add_action( 'wp_ajax_exc_get_embed_code', array($this, 'wp_ajax_get_embed') );
		}
		
		function get_embed_code($url)
		{
			return @wp_oembed_get($url);
		}
		
		function wp_ajax_get_embed()
		{
			/** Quick hack for submitted value */
			if($this->do_validation()->run() !== FALSE)
			{
				try
				{
					$request = wp_oembed_get( $_POST[$this->name] );
					
				}catch(Exception $e)
				{
					$this->eXc->validation->custom_error($this->name, 
									__( 'We are unable to process your request, please make sure you entered a supported URL.', 'exc-framework') );

					wp_send_json_error( $this->eXc->validation->error($this->name) );
				}
				
				wp_send_json_success( $request );

			}else wp_send_json_error( $this->eXc->validation->error($this->name) );
		}
		
		protected function skin_oembed()
		{
			$i18n_submit_str  = apply_filters('exc_file_submit_string', _x( 'Submit', 'extracoding oembed field submit', 'exc-framework' ) );
			$i18n_delete_str  = apply_filters('exc_file_delete_string', _x( 'Delete', 'extracoding oembed field delete', 'exc-framework' ) );
			
			$embed_code = ( $code = $this->get_embed_code($this->set_value()) ) ?
								sprintf('%s<button class="btn btn-default exc-clear-embed" type="submit">%s</button>',
										$code,
										$i18n_delete_str
									)
								: '';
			
			$this->config['markup'] = sprintf('
				<div class="form-group exc-oembed">
					<label for="%s" class="col-sm-2">%s</label>
					<div class="col-sm-8">
						<div class="input-group">
							%s
							
							<div class="input-group-btn">
								<button type="button" class="btn btn-default get-embed-code"><span class="spinner"></span>%s</button>
							</div>
						</div>
						
						<!-- Help note -->
						%s
						
						<div class="exc-preview">%s</div>
					</div>
					
					<!-- Validation Error -->
					%s
				</div>',
				
				$this->config['name'],
				$this->config['label'],
				$this->config['markup'],
				$i18n_submit_str,
				$this->get_help_note(),
				$embed_code,
				$this->eXc->validation->error($this->name)
			);

			return $this->config['markup'];
		}
	}
}
