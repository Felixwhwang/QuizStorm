<?php defined('ABSPATH') OR die('restricted access');

$this->html->load_css_args( 'exc-demo-importer', $this->get_file_url('extensions/theme_options/views/css/importer.css', 'local_dir'), array( 'theme-options-style' ) );
$this->html->load_js( 'exc-demo-importer', $this->get_file_url('extensions/theme_options/views/js/importer.js', 'local_dir') );

$options = array(
				'title'		=> esc_html__('Import Demo Data', 'exc-autolove'),
				'db_name'	=> 'EMPTY_DB_NAME',
				'action'	=> 'exc_ultimate_users',
				//'_layout'	=> 'settings',
				//'_layout'	=> 'demo_importer/index'
			);