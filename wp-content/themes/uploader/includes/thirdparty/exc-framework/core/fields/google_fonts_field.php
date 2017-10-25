<?php defined('ABSPATH') or die('restricted access.');

if ( ! class_exists( 'eXc_Select2_Field' ) )
{
	$this->load('core/fields/select2_field');
}

if ( ! class_exists( 'eXc_Google_Fonts_Field') )
{
	class eXc_Google_Fonts_Field extends eXc_Select2_Field
	{
		function enqueue_files()
		{
			
		}
	}
}