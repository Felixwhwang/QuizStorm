<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Text_Field' ) )
{
	$this->load('core/fields/text_field');
}

if ( ! class_exists( 'eXc_Hidden_Field' ) )
{
	class eXc_Hidden_Field extends eXc_Text_Field
	{
		function html( $return = false )
		{
			$this->config['markup'] = sprintf(
										$this->element_wrap('input'),
										exc_parse_atts( $this->config['attrs'] ),
										$this->set_value()
									);
			
			if ( ! $return )
			{
				echo $this->config['markup'];
				
			} else
			{
				return $this->config['markup'];
			}
		}

		function normalize_data()
		{
			parent::normalize_data();

			$this->config['attrs']['type'] = 'hidden';
		}
	}
}