<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Text_Field' ) )
{
	$this->load('core/fields/text_field');
}

if ( ! class_exists( 'eXc_Url_Field' ) )
{
	class eXc_Url_Field extends eXc_Text_Field
	{
		protected $field_type = 'url';
	}
}