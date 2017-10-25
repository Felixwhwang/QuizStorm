<?php if( ! defined('ABSPATH')) exit('restricted access');

if( ! class_exists( 'eXc_Radio_Field' ) )
{
	class eXc_Radio_Field extends eXc_Fields_Abstract
	{
		function html($return = false)
		{
			$this->config['markup'] = '';
			foreach($this->config['options'] as $k=>$v)
			{
				$this->config['markup'] .= sprintf($this->element_wrap('radio'),
												exc_parse_atts($this->config['attrs']), $v);
			}
			
			return $this->apply_skin($data, $return);
		}
		
		function normalize_data()
		{
			$this->config['attrs'] = wp_parse_args(
										$this->config['attrs'],
										array(
											'name' => $this->name,
											'type' => 'radio'
										)
									);
		}
	}
}