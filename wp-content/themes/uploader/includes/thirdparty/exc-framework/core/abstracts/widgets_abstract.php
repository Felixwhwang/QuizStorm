<?php defined('ABSPATH') or die('restricted access');

abstract class eXc_Widgets extends WP_Widget
{
	abstract protected function _settings();
	
	protected $eXc;
	
	protected $widget_settings = array();

	function __construct()
	{
		//extracoding theme instance
		$this->eXc =& exc_theme_instance();
		
		$this->_settings();
		
		//Prepare form configuration file, make sure we are on widget edit page
		$this->load_config();
		
		@extract( $this->widget_settings );
		
		parent::__construct( $id_base, $name, $widget_options, $control_options );
	}
	
	protected function load_config()
	{
		//Prepare fields only if we are on widgets page
		if ( in_array( $GLOBALS['pagenow'], array('widgets.php', 'customize.php' ) ) || ( isset( $_POST['id_base'] ) && ( $_POST['id_base'] == strtolower( $this->widget_settings['id_base'] ) ) ) )
		{
			$config = $this->eXc->load_config_file( $this->config );
			$this->eXc->wp_admin->prepare_form( $config );
		}
	}
	
	function widget( $args, $instance )
	{
		$this->eXc->load_view( $this->view, array('args' => $args, 'instance' => $instance) );
	}
	
	function form( $instance )
	{
		$fields =& $this->eXc->form->load_settings( $this->config )
						->get_fields_list();

		//@TODO: Don't run validation if it's already processed through update method
		$data = array();
		$is_post = $_POST || false;
		$original_post = $_POST;

		foreach ( $fields as $k=>$v )
		{
			$instance_value = ( isset( $instance[ $v->config['name'] ] ) ) ? $instance[ $v->config['name'] ] : '';
			$data[ $v->config['name'] ] = ( ! $is_post ) ? $instance_value : $v->set_value();
		}

		$this->eXc->validation->set_data( $data, true );
		$this->eXc->form->apply_validation();

		$_POST = $original_post;
		
		//@TODO: verify saved values
		foreach ( $fields as $field )
		{
			if ( $field->is_dynamic )
			{
				$field->config['attrs']['id'] = $this->get_field_id( $field->config['attrs']['name'] );
				$field->config['attrs']['name'] = $this->get_field_name( $field->config['attrs']['name'] );
			} else
			{
				$field->config['attrs']['id'] = $this->get_field_id( $field->config_key );
				$field->config['attrs']['name'] = $this->get_field_name( $field->config_key );
			}

			if ( isset( $field->config['attrs'] ) && isset( $field->config['attrs']['multiple'] ) )
			{
				$field->config['attrs']['name'] = $field->config['attrs']['name'] . '[]';
			}
		}
		
		$config = $this->eXc->form->get_config( $this->config );
		$this->eXc->load_view( exc_kv( $config, '_layout' ), $config );
	}
	
	function update( $new_instance, $old_instance )
	{
		//@TODO: extra security check to verify the data is belong to this widget
		// Post fix for validation class @TODO: update code in validation class to hold non-post data
		$orginal_post = $_POST;

		$this->eXc->validation->set_data( $new_instance, true );
		
		if( $this->eXc->form->apply_validation() !== FALSE )
		{
			return $new_instance;
		}
		
		$_POST = $orginal_post;

		return $old_instance;
	}

	//@TODO: don't save widget if we have validation errors
}