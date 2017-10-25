<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( "eXc_User_Profile_fields_Class" ) ) :

class eXc_User_Profile_fields_Class extends eXc_DB_Options_Class
{
    private $profile_pages = array( "profile.php", "user-new.php", "user-edit.php" );

    private $_fields = array();

    private $_add_contact_fields = array();

    private $_remove_contact_fields = array();

    private $_fields_data = array();

    private $_fields_data_map = array();

    public $active_user_id = 0;

    private $error_data = array();
    //private $_form_sections = array( "user_edit_form_tag", "personal_options", "profile_personal_options");

    protected function initialize_class()
    {
        if ( is_admin() ) {

            // Load Settings when admin is ready
            add_action( 'admin_init', array( &$this, 'load_settings' ) );
        }
    }

    public function add_fields( $config_files )
    {
        // Do nothing if we are not in admin panel
        if ( ! $config_files || ! is_admin() ) {
            return;
        }

        if ( ! is_array( $config_files ) ) {
            $config_files = array( $config_files );
        }

        foreach ( $config_files as $config_file ) {

            if ( ! in_array( $config_file, $this->_fields ) ) {
                $this->_fields[] = $config_file;
            }
        }

        // if ( did_action( "admin_init" ) ) {
        //     return $this->load_settings();
        // }
    }

    public function load_settings()
    {
        global $pagenow;

        if ( $pagenow == "user-edit.php" ) {

            if ( empty( $_REQUEST['user_id'] ) || ! intval( $_REQUEST['user_id'] ) ) {
                return;
            }

            $this->active_user_id = $_REQUEST['user_id'];
        } else {
            $this->active_user_id = get_current_user_id();
        }

        if ( ! in_array( $pagenow, $this->profile_pages ) ) {
            return;
        }

        // Enqueue admin files
        $this->enqueue_files();

        add_filter( 'exc-prepare-form', array( &$this, 'load_saved_data' ) );

        foreach ( $this->_fields as $config_file ) {

            $this->_fields_data[ $config_file ] = $this->exc()->load_config_file( $config_file );
            $this->_fields_data_map[ $this->_fields_data[ $config_file ]["_name"] ] = $config_file;

            $this->wp_admin()->prepare_form( $this->_fields_data[ $config_file ] );
            //$this->exc()->wp_admin->prepare_form( $this->_fields_data[ $config_file ] );
        }

        add_action( 'admin_notices', array( &$this, 'display_form_errors' ) );

        add_action( "show_user_profile", array( &$this, "_add_profile_fields" ) );
        add_action( "edit_user_profile", array( &$this, "_add_profile_fields" ) );
        add_action( "user_new_form", array( &$this, "_add_profile_fields" ) );

        add_action( "personal_options_update", array( &$this, "save_profile_fields" ) );
        add_action( "edit_user_profile_update", array( &$this, "save_profile_fields" ) );
    }

    public function load_saved_data( $form_name )
    {
        if ( isset( $this->_fields_data_map[ $form_name ] ) ) {

            $config_file = $this->_fields_data_map[ $form_name ];
            $this->form_settings =& $this->_fields_data[ $config_file ];

            // quick hack for db_name
            if ( empty( $this->form_settings['db_name'] ) ) {
                $this->form_settings['db_name'] = "_keep_all_fields_as_individual";
            }

            $this->prepare_form( $form_name );
        }
    }

    public function _add_profile_fields()
    {
        foreach ( $this->_fields_data as $fields ) {
            $this->exc()->load_view( exc_kv( $fields, '_layout' ), $fields );
        }
    }

    public function save_profile_fields()
    {
        if ( ! $this->active_user_id && ! ( current_user_can( 'edit_user', $this->active_user_id ) || current_user_can( "edit_users" ) ) ) {
            return false;
        }

        foreach ( $this->_fields_data as $fields ) {

            $this->form_settings =& $fields;

            // quick hack for db_name
            if ( empty( $this->form_settings['db_name'] ) ) {
                $this->form_settings['db_name'] = "_keep_all_fields_as_individual";
            }

            $this->save_options();
        }
    }

    public function add_contact_fields( $fields )
    {
        $this->manage_contact_fields( $fields, $this->_add_contact_fields );
    }

    public function remove_contact_fields( $fields )
    {
        $this->manage_contact_fields( $fields, $this->_remove_contact_fields );
    }

    public function _user_contact_fields_filter( $user_contact_fields )
    {
        foreach ( $this->_add_contact_fields as $field_name => $field_label ) {
            $user_contact_fields[ $field_name ] = $field_label;
        }

        foreach ( $this->_remove_contact_fields as $field_name => $field_label ) {

            if ( isset( $user_contact_fields[ $field_name ] ) ) {
                unset( $user_contact_fields[ $field_name ] );
            }
        }

        return $user_contact_fields;
    }

    public function display_form_errors()
    { //printr( $this->error_data );
        if ( empty( $this->error_data ) ) {
            return;
        }?>

        <div class="notice error exc-dismiss-upgrade-notice is-dismissible">
            <?php foreach ( $this->error_data as $error_key => $error_value ) :?>
                <p><?php echo esc_html( $error_value );?></p>
            <?php endforeach;?>
        </div>
    <?php
    }

    private function manage_contact_fields( $fields, &$property ) {

        if ( empty( $fields ) ) {
            return;
        }

        if ( ! has_filter( "user_contactmethods", array( &$this, "_user_contact_fields_filter" ) ) ) {
            add_filter( "user_contactmethods", array( &$this, "_user_contact_fields_filter" ) );
        }

        if ( ! is_array( $fields ) ) {
            $fields = array( $fields );
        }

        $property = array_merge( $property, $fields );
    }

    protected function get_option( $option, $default = false )
    {
        if ( ! $this->active_user_id ) {
            return $default;
        }

        if ( $option == '_keep_all_fields_as_individual' ) {

            $user_meta_data = get_user_meta( $this->active_user_id );

            $user_data = array();
            $form_name = $this->form_settings['_name'];

            foreach ( $this->exc()->form->get_fields_list( $form_name ) as $field ) {

                $field_name = $field->get_name();

                if ( isset( $user_meta_data[ $field_name ] ) ) {
                    $user_data[ $field_name ] = maybe_unserialize( exc_kv( $user_meta_data[ $field_name ], 0 ) );
                }
            }

        } else {
            $user_data = get_user_meta( $this->active_user_id, $option, TRUE );
        }

        if ( $user_data ) {
            return $user_data;
        }

        return $default;
    }

    protected function add_option( $meta_key, $meta_value = '', $deprecated = '', $autoload = 'yes' )
    {
        if ( ! $this->active_user_id ) {
            return false;
        }

        $meta_value = apply_filters( "exc_update_add_meta_" . $meta_key, $meta_value, $this->active_user_id );

        return add_user_meta( $this->active_user_id, $meta_key, $meta_value, true );
    }

    protected function update_option( $option, $value, $autoload = null )
    {
        if ( ! $this->active_user_id ) {
            return false;
        }

        if ( $option == '_keep_all_fields_as_individual' ) {

            foreach ( $value as $meta_key => $meta_value ) {
                $added_value = $this->add_option( $meta_key, $meta_value );

                if ( ! $added_value ) {

                    $meta_value = apply_filters( "exc_update_user_meta_" . $meta_key, $meta_value, $this->active_user_id );
                    update_user_meta( $this->active_user_id, $meta_key, $meta_value );
                }
            }

            return true;
        }

        $added_value = $this->add_option( $option, $value );

        if ( ! $added_value ) {

            $value = apply_filters( "exc_update_user_meta_" . $option, $value, $this->active_user_id );
            return update_user_meta( $this->active_user_id, $option, $value );
        }

        return $added_value;
    }

    protected function save_error( $error_data )
    {
         $this->error_data = $error_data;
        //add_action( 'admin_footer', array( &$this, 'print_inline_script' ) );
    }

    protected function save_success( $success_data )
    {
        //exit("saved");
        //exc_success( $success_data );
    }
}
endif;