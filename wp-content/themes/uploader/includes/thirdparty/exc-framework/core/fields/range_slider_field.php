<?php defined( 'ABSPATH' ) or die('restricted access');

if ( ! class_exists('eXc_Text_Field') )
{
	$this->load('core/fields/text_field');
}

if ( ! class_exists( 'eXc_Range_Slider_Field') )
{
	class eXc_Range_Slider_Field extends eXc_Text_Field
	{
		function enqueue_files()
		{
			$current_value = $this->set_value();
			$range = explode( ';', $current_value );

			$min_value = ( isset( $this->_args['min'] ) && intval( $this->_args['min'] ) )
								? $this->_args['min'] : 0;

			$from_value = ( isset( $range[0] ) && intval( $range[0] ) )
							? $range[0] : $min_value;

			if ( ( isset( $this->_args['type'] ) && $this->_args['type'] == 'double') )
			{
				$max_value = ( isset( $this->_args['max'] ) && intval( $this->_args['max'] ) )
								? $this->_args['max'] : 100;

				$to_value = ( isset( $range[1] ) && intval( $rang[1] ) )
							? $range[1] : $max_value;
			}

			$this->eXc->html
						->load_css_args(
							'ion.rangeSlider',
							$this->eXc->system_url('views/css/ion.rangeSlider/css/ion.rangeSlider.css')
						)

						->load_css_args(
							'ion.rangeSlider.skinHTML5',
							$this->eXc->system_url('views/css/ion.rangeSlider/css/ion.rangeSlider.skinHTML5.css'),
							array('ion.rangeSlider')
						)

						->load_js_args(
							'ion.rangeSlider',
							$this->eXc->system_url('views/js/ion-rangeSlider/ion.rangeSlider.min.js'),
							array('jquery')
						)

						->inline_js(
							'exc-range-slider',
							'$(".exc-range-slider").ionRangeSlider({});',
							
							'jquery',
							TRUE
						);
		}

		function html( $return = FALSE )
		{
			parent::html();
		}

		function normalize_data()
		{
			$this->config['attrs']['data'] = $this->_args;
			parent::normalize_data();

			$this->config['attrs']['type'] = 'text';

			if ( ! stristr( $this->config['attrs']['class'], 'exc-range-slider' ) )
			{
				$this->config['attrs']['class'] .= ' exc-range-slider';
			}

			$this->config['attrs'] =
							wp_parse_args( 
								$this->config['attrs'],
								array(
									'id'	=> $this->get_name() . '-slider',
								)
							);
		}

		protected function normalize_field_args( $args = array() )
		{
			// The following are most common arguments of Ion Range Slider
			// for complete list visit https://github.com/IonDen/ion.rangeSlider

			// @TODO: ability to pass callback through config file
			return wp_parse_args(
						$args,
						array(
							'type'				=> 'single',
							'min'				=> 0,
							'max'				=> 100,
							'step'				=> 1,
							'grid'				=> true,
							'hide_min_max'		=> false,
							'hide_from_to'		=> false,
							'prefix'			=> '',
							'postfix'			=> ''
						)
					);
		}
	}
}