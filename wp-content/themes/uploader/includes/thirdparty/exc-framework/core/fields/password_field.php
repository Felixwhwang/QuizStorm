<?php if( ! defined('ABSPATH')) exit('restricted access');

if( ! class_exists('eXc_Text_Field'))
{
	$this->load('core/fields/text_field');
}

if ( ! class_exists( 'eXc_Password_Field' ) )
{
	class eXc_Password_Field extends eXc_Text_Field
	{
		function normalize_data()
		{
			parent::normalize_data();
			
			$this->config['attrs']['type'] = 'password';
		}
	}
}
