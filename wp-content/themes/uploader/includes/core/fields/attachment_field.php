<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Attachment_Field' ) )
{
	class eXc_Attachment_Field extends eXc_Fields_Abstract
	{
		/**
		 * Default Skin
		 *
		 * @var string
		 * @since 1.0
		 */
		//protected $default_skin = 'skin_default';

		public function html( $return = false )
		{
			$this->config['markup'] = sprintf(
										$this->element_wrap('input'),
										exc_parse_atts( $this->config['attrs'] ),
										$this->set_value()
									);

			return $this->apply_skin( $return );
		}
		
		public function enqueue_files()
		{
			$this->eXc->html->inline_js( 'exc-framework', $this->custom_js() );
		}

		private function custom_js()
		{
			return "
				( function( window, $){
					
				})( window, jQuery );
			";
		}
	}
}