<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_datepikcer_Field' ) )
{
	class eXc_datepicker_Field extends eXc_Fields_Abstract
	{
		protected $default_skin = 'form_group';

		public function html( $return = false )
		{
			$this->config['markup'] = sprintf( $this->element_wrap('input'),
										exc_parse_atts( $this->config['attrs'] ),
										$this->set_value()
									);

			return $this->apply_skin( $return );
		}

		public function enqueue_files()
		{
			$this->eXc->html->load_js( 'bs-datepicker', $this->eXc->get_file_url( 'views/js/bootstrap-datepicker/bootstrap-datepicker.min.js' ) )
							->load_css( 'bs-datepicker', $this->eXc->get_file_url('views/css/bootstrap-datepicker/bootstrap-datepicker.min.css') )
							->inline_js( 'bs-datepicker', $this->custom_js() );
		}
		
		protected function normalize_data()
		{
			if ( FALSE == strstr( $this->config['attrs']['class'], 'exc-datepicker-field' ) )
			{
				$this->config['attrs']['class'] .= ' exc-datepicker-field';
			}

			$this->config['attrs'] = wp_parse_args(
										$this->config['attrs'],
										array(
											'name' => $this->name,
											'type' => ( ! empty( $this->config['type'] ) ) ? $this->config['type'] : 'text'
										)
									);

			if ( isset( $this->config['attrs']['value'] ) )
			{
				unset( $this->config['attrs']['value'] );	
			} 
		}

		private function custom_js()
		{
			return "
				( function( window, $){

				$('.exc-datepicker-field').datepicker({container: '.exc-panel', autoclose: true});
					
				// Dynamic Fields
				$( document ).on('exc-dynamic-add_row', function(e, row){
					$('.exc-datepicker-field', row).datepicker({container: '.exc-panel', autoclose: true});
				});

				})( window, jQuery );
			";
		}
	}
}