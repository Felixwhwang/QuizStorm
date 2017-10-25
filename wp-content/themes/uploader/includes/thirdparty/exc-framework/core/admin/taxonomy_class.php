<?php defined('ABSPATH') OR die('restricted access');

class eXc_Taxonomy_Class
{
    private $eXc;
    private $_meta_query = array();

    private $form_name;

    function __construct( &$eXc )
    {
        $this->eXc = $eXc;

        add_filter( 'exc-prepare-form', array( $this, 'prepare_form' ) );
    }

    function on()
    {
        $pages = func_get_args();

        if ( empty( $pages ) )
        {
            exc_die( __( 'Please enter taxonomy to edit.', 'exc-framework' ) );
        }

        foreach ( $pages as $page )
        {
            add_action( $page . '_add_form_fields', array( $this, 'new_form' ) );
            add_action( $page . '_edit_form', array( $this, 'edit_form' ) );

            // Validate fields before adding new term
            add_filter('pre_insert_term', array( $this, 'insert_term' ) );

            add_action( 'edited_' . $page, array( $this, 'save_form' ), 10, 2 );
            add_action( 'created_' . $page, array( $this, 'save_form' ), 10, 2 );
        }

        $this->eXc->html->load_bootstrap();

        return $this;
    }

    function insert_term( $term )
    {
        $errors = $this->eXc->validation->errors_array();

        if ( $errors )
        {
            $wp_errors = new WP_Error;

            foreach ( $errors as $err_key => $err )
            {
                $wp_errors->add( $err_key, $err );
            }

            return $wp_errors;
        }

        return $term;
    }

    function prepare_form( $name = '' )
    {

    }

    function add_fields( &$fields )
    {
        $this->form_name = $fields['_name'];
        $this->eXc->wp_admin->prepare_form( $fields );
    }

    function edit_form( $term )
    {
        $taxonomy_name = 'taxonomy_meta_' . $term->term_id;

        $data = get_option( $taxonomy_name, array() );

        if ( $data )
        {
            $this->eXc->validation->set_data( $data );
            $this->eXc->form->apply_validation( $this->form_name );
        }

        $config = $this->eXc->form->get_config( $this->form_name );
        $this->eXc->load_view( exc_kv( $config, '_layout' ), $config );
    }

    function new_form()
    {
        $config = $this->eXc->form->get_config( $this->form_name );
        $fields = $this->eXc->form->get_fields_list( $this->form_name );

        // Change form group to form fields
        foreach ( $fields as $k => $v )
        {
            $v->config['skin'] = 'form_field';
        }

        $this->eXc->load_view( exc_kv( $config, '_layout' ), $config );
    }

    function save_form( $term_id )
    {
        if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'inline-save-tax' )
        {
            return $term_id;
        }

        if ( ! isset( $term_id ) || ( ! isset( $_POST['taxonomy'] ) ) || ( ! current_user_can( 'manage_categories' ) ) )
        {
            return $term_id;
        }

        if ( $this->eXc->form->is_nonce_verified( $this->form_name ) )
        {
            if ( count( $this->eXc->validation->_error_array ) )
            {
                // Show error
            } else
            {
                $is_post = count( $_POST ) || false;

                $taxonomy_name = 'taxonomy_meta_' . $term_id;

                $settings = array();

                foreach ( (array) $this->eXc->form->get_fields_list( $this->form_name ) as $field )
                {
                    $field_name = $field->get_name();

                    $settings[ $field_name ] = ( $is_post ) ? exc_kv( $_POST, $field_name ) : $field->set_value();
                }

                $saved_data = get_option( $taxonomy_name );

                ( $saved_data ) ? update_option( $taxonomy_name, $settings ) : add_option( $taxonomy_name, $settings, '', 'no' );
            }
        }

        return $term_id;
    }
}