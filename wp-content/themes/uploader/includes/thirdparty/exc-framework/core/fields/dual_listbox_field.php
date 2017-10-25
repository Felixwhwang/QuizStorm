<?php defined('ABSPATH') OR die('restricted access');

// Make sure select field is already loaded
if ( ! class_exists( 'eXc_Select_Field' ) )
{
	$this->load( 'core/fields/select_field' );
}

if ( ! class_exists( 'eXc_dual_Listbox_Field' ) )
{
	class eXc_dual_Listbox_Field extends eXc_Select_Field
	{
		public function enqueue_files()
		{
			if ( empty( $this->config ) )
			{
				return;
			}

			$this->eXc->html->load_js( 'dual-listbox', $this->eXc->get_file_url( 'views/js/jquery.bootstrap-duallistbox.min.js', 'system_dir' ) )
							->load_css( 'dual-listbox', $this->eXc->get_file_url( 'views/css/bootstrap-duallistbox.min.css', 'system_dir' ) )
							->inline_js( 'dual-listbox', '$("#' . $this->config['attrs']['id'] . '").bootstrapDualListbox();', true, true );
		}
		
		public function normalize_data()
		{
			// @TODO: add dualbox custom CSS
			parent::normalize_data();

			$this->config['attrs']['id'] = exc_kv( $this->config, 'attrs/id', $this->name );
			$this->config['listbox'] = ( isset( $this->config['listbox'] ) ) ? $this->config['listbox'] : array();
		}
	}
}