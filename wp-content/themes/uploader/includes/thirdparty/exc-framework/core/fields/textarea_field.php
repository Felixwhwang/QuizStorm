<?php if( ! defined('ABSPATH')) exit('restricted access');

if ( ! class_exists( 'eXc_Textarea_Field' ) )
{
	class eXc_Textarea_Field extends eXc_Fields_Abstract
	{
		protected $default_skin = 'form_group';
		
		function html($return = false)
		{			
			$this->config['markup'] = sprintf("<textarea %s>%s</textarea>",
										exc_parse_atts($this->config['attrs']),
										$this->eXc->validation->set_value($this->name, $this->config['default'])
									);
									
			return $this->apply_skin($return);
		}
		
		function normalize_data()
		{
			$this->config['attrs'] = array_merge(
										array(
											'name' => $this->name,
											'rows' => 3
										), $this->config['attrs']
							);
		}
	}
}