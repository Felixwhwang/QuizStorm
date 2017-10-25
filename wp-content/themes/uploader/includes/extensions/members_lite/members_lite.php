<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Members_Lite_Extension' ) ):

class eXc_Members_Lite_Extension extends eXc_Extension_Abstract
{
    /**
     * Extracoding Product Name ( do not change the name )
     *
     * @since 1.0
     * @var string
     */
    protected $product_name = 'exc-users-plugin';

    /**
     * Extracoding Extension Name ( do not change the name )
     *
     * @since 1.0
     * @var string
     */
    protected $product_version = '1.0.1';

    /**
     * Extracoding Framework minimum version compatibility
     *
     * @since 1.1
     * @var float
     */
    protected $exc_supported_version = '2.3.0';

    /**
     * User form fields settings
     *
     * @since 1.0
     * @var object
     */
    protected $form_settings = array();

    /**
     * Hold the admin settings
     *
     * @since 1.0
     * @var object
     */
    protected $admin_settings = array();

    /**
     * Activate Extensions List
     *
     * @since 1.0
     * @var object
     */
    protected $active_extensions = array();

    protected function initialize_extension()
    {
        $this->db_name = 'mf_member_settings';

        // Load Already Saved Admin Settings
        $this->admin_settings = get_option( $this->db_name );

        // Do nothing if the plugin status is off
        if ( ! is_admin() && exc_kv( $this->admin_settings, 'status' ) != 'on' ) {
            return;
        }

        $this->admin_settings['social_media_login'] = 'on';

        // Social Media Login
        //add_action( 'init', array( &$this, 'social_media_login_handler' ) );
        $this->social_media_login_handler();
        $this->frontend_login_handler();
    }

    public function social_media_login_handler()
    {
        // do nothing if the plugin is installed
        if ( defined( 'WORDPRESS_SOCIAL_LOGIN_ABS_PATH' ) ) {
            return;
        }

        $plugin_dir = $this->local('thirdparty/wordpress-social-login')->get_query_path();
        $plugin_url = $this->local('thirdparty')->get_query_url('wordpress-social-login') . '/';
        $hybridauth_url = $plugin_url . 'hybridauth/';

        define( 'WORDPRESS_SOCIAL_LOGIN_ABS_PATH', $plugin_dir );

        defined( 'WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL' ) ||
            define( 'WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL', $plugin_url );

        defined( 'WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL' ) ||
            define( 'WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL', $hybridauth_url );

        $this->local('thirdparty/wordpress-social-login')->load_library('wp-social-login');

        if ( ! get_option( 'exc-members-lite-social-login-install' ) ) {
            wsl_install();

            update_option( 'exc-members-lite-social-login-install', 1 );
        }
    }

    private function frontend_login_handler()
    {
        // Check if we are on reset password page
        if ( isset( $_GET['ua'] ) && $_GET['ua'] == 'rp' )
        {
            $this->reset_password();
        }

        $is_user_logged_in = is_user_logged_in();

        if ( exc_kv( $this->admin_settings, 'frontend_login' ) == 'on' && ! ( defined('DOING_AJAX') && DOING_AJAX ) )
        {
            //if user is not logged in and trying to access login page then redirect her
            $wsl_actions = array( "wordpress_social_authenticate", "wordpress_social_profile_completion", "wordpress_social_account_linking", "wordpress_social_authenticated" );

            $current_page_action = exc_kv( $_GET, 'action' );

            if ( false == $is_user_logged_in && in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) && ! isset( $_GET['interim-login'] )
                    && ! in_array( $current_page_action, $wsl_actions ) ) {

                // Automatically delete last request uri
                $last_request_uri = $this->exc()->session->get_data( 'last_request_uri', true );

                $redirect_to = wp_validate_redirect( $last_request_uri, site_url() );
                $this->exc()->session->set_data( 'redirect_to', $redirect_to );

                // Redirecting page so keep session message
                $this->exc()->session->keep_flashdata('session_message');

                if ( defined( 'WORDPRESS_SOCIAL_LOGIN_ABS_PATH' ) ) {
                    $social_link = site_url( wsl_process_login() );
                }

                $link = site_url( '#login' );
                wp_safe_redirect( $link );
                exit;

            } elseif ( is_admin() && ( $user_role = current_user_role() ) && $user_role != 'administrator' &&
                ! in_array( current_user_role(), exc_kv( $this->admin_settings, 'admin_bar' ) ) )
            {
                wp_redirect( site_url() );
                exit;
            }

            // Keep Record of last browsed page
            if ( ! in_array( $GLOBALS['pagenow'], array( 'wp-login.php'/*, 'index.php' */) ) )
            {
                $this->exc()->session->set_data( 'last_request_uri', $_SERVER['REQUEST_URI'] );
            }
        }

        // Auto load scripts
        if ( exc_is_client_side() )
        {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_files' ) );

            // load js templates
            if ( FALSE === $is_user_logged_in )
            {
                // @TODO: add condition to load local or glabal file
                $this->config = $this->exc()->load_config_file( 'members/signup' );

                $this->exc()->load( 'core/form_class' )->prepare_fields( $this->config );

                //ajax validation
                add_action( 'wp_ajax_nopriv_exc_login', array( &$this, 'user_signin' ) );
                add_action( 'wp_ajax_nopriv_exc_signup_verify', array( &$this, 'verify_entry' ) );
                add_action( 'wp_ajax_nopriv_exc_signup', array( &$this, 'user_signup' ) );

                add_action( 'wp_ajax_nopriv_exc_forgot_password', array( &$this, 'forgot_password' ) );
                add_action( 'wp_ajax_nopriv_exc_reset_password', array( &$this, 'reset_password') );

                // Load Template
                add_action( 'wp_footer', array( &$this, 'load_template' ) );

            } else
            {
                // Delete Post
                add_action( "wp_ajax_exc_delete_post", array( &$this, 'delete_post' ) );

                // Update profile image
                add_action( "wp_ajax_exc_profile_image", array( &$this, 'edit_profile_image' ) );

                // Ajax Edit Profile
                add_action( "wp_ajax_exc_edit_profile", array( &$this, 'edit_profile') );

                // Ajax Logout
                add_action( "wp_ajax_exc_user_logout", array( &$this, "user_logout") );

                //Hide admin bar
                if ( FALSE === in_array( current_user_role(), exc_kv( $this->admin_settings, 'admin_bar' ) ) )
                {
                    show_admin_bar( false );
                }

                // Access user to it's pending for review post
                if ( ! ( defined('DOING_AJAX') && DOING_AJAX ) )
                {
                    add_filter( 'pre_get_posts', array( &$this, 'show_pending_posts' ) );
                }
            }
        }
    }

    public function show_pending_posts( $query )
    {
        if ( $query->is_main_query() && $query->is_preview() && is_singular( ) )
        {
            add_filter( 'posts_results', array( &$this, 'allow_preview' ) );
        }

        return $query;
    }

    function allow_preview( $posts )
    {
        remove_filter( 'posts_results', array( &$this, 'allow_preview' ) );

        if ( empty( $posts ) ) {
            return;
        }

        $post_id = $posts[0]->ID;

        if ( current_user_can( 'edit_posts', $post_id ) )
        {
            if ( in_array( get_post_status( $post_id ), array( 'publish', 'private' ) ) )
            {
                wp_redirect( get_permalink( $post_id ), 301 );
                exit;
            }

            $posts[0]->post_status = 'publish';
            $posts[0]->comment_status = 'closed';
            $posts[0]->ping_status = 'closed';
        }

        return $posts;
    }

    function edit_profile_image()
    {
        if ( ! wp_verify_nonce( exc_kv( $_POST, 'security' ), 'exc-profile-image' ) )
        {
            exc_die( __('Page Expired!!', 'exc-uploader-theme' ) );
        }

        $file = exc_kv( $_FILES, 'profile_img' );
        $file_type = exc_kv( $file, 'type' );

        $allowed_mime_types = array('image/jpeg', 'image/gif', 'image/png' );

        if ( false === in_array( $file_type, $allowed_mime_types ) )
        {
            exc_die(
                sprintf(
                    __('You must upload only supported %s files.', 'exc-uploader-theme' ),
                    implode( ', ', $allowed_mime_types )
                    )
                );
        }

        $upload = wp_handle_upload( $file, array( 'test_form' => false ) );

        if ( ! isset( $upload['file'] ) )
        {
            exc_die( sprintf( __( 'Unable to upload "%s", Possible file upload attack.', 'exc-uploader-theme' ), $file['name'] ) );
        }

        $filename = $upload['file'];

        $attachment = array(
                        'guid'           => $upload['url'],
                        'post_mime_type' => $upload['type'],
                        'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    );

        //wp_insert_attachment( $args, $file = false, $parent = 0 )
        $attachment_id = wp_insert_attachment( $attachment, $filename );

        if ( is_wp_error( $attachment_id ) )
        {
            @unlink( $upload['file'] ); //Remove uploaded file
            exc_die( sprintf( __( 'There was an error while image upload.', 'exc-uploader-theme' ), $filename ) );
        }

        wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $filename ) );

        $user_id = get_current_user_id();

        // Delete old image
        if ( $old_image_id = ( int ) get_the_author_meta( 'profile_image', $user_id ) )
        {
            wp_delete_attachment( $old_image_id, true );
        }

        $meta_id = update_user_meta( $user_id, 'profile_image', $attachment_id );

        if ( $meta_id )
        {
            wp_send_json_success( wp_get_attachment_image( $attachment_id ) );

        } else
        {
            exc_die(
                __('System is unable to update your profile, please make sure you entered the information correctly.', 'exc-uploader-theme' )
            );
        }
    }

    function edit_profile()
    {
        if ( ! wp_verify_nonce( exc_kv( $_POST, 'security' ), 'exc-edit-profile') )
        {
            exc_die( __('Page Expired!!', 'exc-uploader-theme' ) );
        }

        // Addition Security to verify allowed fields
        $path = locate_template( 'inc/edit_profile.php' );

        if ( ! $path )
        {
            exc_die( __('Edit profile configuration file is missing.', 'exc-uploader-theme') );
        }

        require( $path );

        $field_name = exc_kv( $_POST, 'name' );
        $field_id   = exc_kv( $_POST, 'pk' );
        $value      = exc_kv( $_POST, 'value' );

        if ( ! isset( $config[ $field_id ] ) )
        {
            exc_die( __('Page Expired!!', 'exc-uploader-theme' ) );
        }

        // Apply Validation Rules
        $this->exc()->validation->set_data( array( $field_id => $value ), true );

        $this->exc()->validation->set_rules( $field_id, exc_to_text( $field_id ), $config[ $field_id ]['validation'] );

        if ( $this->exc()->validation->run() === FALSE )
        {
            exc_die( $this->exc()->validation->_error_array[ $field_id ] );
        }

        // Get Validated value
        $value = $this->exc()->validation->set_value( $field_id );

        if ( $config[ $field_id ]['section'] == 'user_data' )
        {
            // Apply Validation
            $user_id = wp_update_user( array( 'ID' => get_current_user_id(), $field_name => $value ) );

            if ( is_wp_error( $user_id ) )
            {
                exc_die( $user_id->message() );
            } else
            {
                wp_send_json_success();
            }

        } elseif( $config[ $field_id ]['section'] == 'user_pass' )
        {
            $value = html_entity_decode( $value );
            wp_set_password( $value, get_current_user_id() );

            wp_logout();

            $redirect_to = get_option('permalink_structure') ? site_url( 'dashboard-profile' ) : site_url( '?exc_custom_page=dashboard-profile' );
            $this->exc()->session->set_data( 'redirect_to', $redirect_to );

            wp_send_json_success( site_url( '#login' ) );
        } else
        {
            if ( isset( $config[ $field_id ]['meta_key'] ) )
            {
                $meta_key = $config[ $field_id ]['meta_key'];

                $data = exc_kv( get_user_meta( get_current_user_id(), $meta_key ), '0', array() );

                $data[ $field_name ] = $value;
                $meta_id = update_user_meta( get_current_user_id(), $meta_key, $data );

            } else
            {
                $meta_id = update_user_meta( get_current_user_id(), $field_name, $value );
            }

            if ( $meta_id )
            {
                wp_send_json_success();

            } else
            {
                exc_die( __('System is unable to update your profile, please make sure you entered the information correctly.', 'exc-uploader-theme' ) );
            }

        }
    }

    function user_logout()
    {
        /*
        if( ! wp_verify_nonce( exc_kv( $_POST, 'security'), 'exc-login-check' ) )
        {
            wp_send_json_error( __("Page Expired!!", 'exc-uploader-theme') );
        }*/

        wp_logout();

        wp_send_json_success( );
    }

    function user_template()
    {
        return exc_load_template( 'modules/user_controls', array(), true );
    }

    function reset_password()
    {
        global $wpdb;

        if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )
        {
            $user_id = $this->exc()->session->flashdata('allow_password_reset');

            if ( ! wp_verify_nonce( exc_kv( $_POST, 'security'), 'exc-reset-password' )
                || ! intval( $user_id )
            )
            {
                exc_die( __( "Page Expired!!", 'exc-uploader-theme' ) );
            }

            $this->exc()->validation->set_rules('pwd', __( 'Password', 'exc-uploader-theme' ), 'required|min_length[6]');
            $this->exc()->validation->set_rules('confirm_pwd', __( 'Confirm Password', 'exc-uploader-theme' ), 'required|matches[pwd]');

            if ( $this->exc()->validation->run() !== FALSE )
            {
                if ( ! $user = get_user_by( 'id', $user_id ) )
                {
                    exc_die( __( "Hacking Attempt!!", 'exc-uploader-theme' ) );
                }

                wp_set_password( $this->exc()->validation->set_value('pwd'), $user_id );

                $this->exc()->session->set_flashdata( 'session_message', array( 'type' => 'success', 'message' => __('Congratulations! Your password has been successfully updated, You may login with your updated password.', 'exc-uploader-theme') ) );

                wp_send_json_success();

            } else
            {
                exc_die( implode( "<br />", $this->exc()->validation->errors_array() ) );
            }

        } else
        {
            $err_message = __('We are sorry but your activation key is invalid or expired.', 'exc-uploader-theme' );

            if ( empty( $_GET['login'] ) || empty( $_GET['key'] )
                    || strlen( $_GET['key'] ) != 20 || ( ! $user = get_user_by( 'login', $_GET['login'] ) )
                    || ! check_password_reset_key( $_GET['key'], $_GET['login'] )
                )
            {
                $this->exc()->session->set_flashdata( 'session_message', array( 'type' => 'error', 'message' => $err_message ) );
            } elseif ( is_user_logged_in() )
            {
                $this->exc()->session->set_flashdata( 'session_message', array( 'type' => 'error', 'message' => __('You are already logged in, please update your password through profile settings.', 'exc-uploader-theme') ) );
            } else
            {
                $this->exc()->session->set_flashdata( 'allow_password_reset', $user->ID );
                wp_safe_redirect( site_url('#reset_password'), 301 );
                exit;
            }

            wp_safe_redirect( site_url('/'), 301 );
            exit;
        }
    }

    function forgot_password()
    {
        global $wpdb, $wp_hasher;

        if ( ! wp_verify_nonce( exc_kv( $_POST, 'security'), 'exc-forgot-password' ) )
        {
            exc_die( __( "Page Expired!!", 'exc-uploader-theme' ) );
        }

        if ( isset( $_POST['email'] ) && is_email( $_POST['email'] ) )
        {
            do_action('lostpassword_post');

            if ( ! $user = get_user_by( 'email', $_POST['email'] ) )
            {
                exc_die( __('The email address is not belong to any account, please try again.', 'exc-uploader-theme' ) );
            }

            do_action( 'retrieve_password', $user->user_login );

            $allow = apply_filters( 'allow_password_reset', true, $user->ID );

            if ( ! $allow )
            {
                exc_die( __('You are not allowed to change password.', 'exc-uploader-theme') );

            } elseif ( is_wp_error( $allow ) )
            {
                return false;
            }

            //$key = $wpdb->get_var( $wpdb->prepare( "SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user->user_login ) );

            //if ( empty( $user->user_activation_key ) )
            //{
                $key = get_password_reset_key( $user );

                if ( is_wp_error( $key ) ) {
                    wp_send_json_error( __("Unable to process your request, plesae try again", THEME_NAME) );
                }
            //}

            $login = rawurlencode( $user->user_login );
            $args = array(
                        'user_login'            => $user->user_login,
                        'first_name'            => get_user_meta( 'first_name' ),
                        'last_name'             => get_user_meta( 'last_name' ),
                        'reset_password_link'   => site_url( "?ua=rp&key={$key}&login={$login}" )
                    );

            $this->exc()->load('core/mail_class');

            $settings = get_option('mf_mail_settings');

            if ( exc_kv( $settings, 'recovery_body' ) )
            {
                $meta = array(
                                'to'        => $user->user_email,
                                'from'      => exc_kv( $settings, 'from_email' ),
                                'from_name' => exc_kv( $settings, 'from_name' )
                            );

                $email_settings = array(
                            'status'        => 'on',
                            'subject'       => exc_kv( $settings, 'recovery_subject' ),
                            'body'          => exc_kv( $settings, 'recovery_body' )
                        );

                if ( exc_send_mail( $email_settings, $meta, $args ) )
                {
                    exc_success(
                            '<div class="alert alert-success">' .
                                __('Instructions for resetting your password has been sent.', 'exc-uploader-theme' ) .
                            '</div>'
                            );

                } else
                {
                    exc_die( implode( "br />", $this->exc()->validation->errors_array() ) );
                }
            }

        } else
        {
            exc_die( __('Please enter a valid email address.', 'exc-uploader-theme' ) );
        }
    }

    function user_signin()
    {
        if ( ! wp_verify_nonce( exc_kv( $_POST, 'security'), 'exc-login' ) )
        {
            exc_die( __( "Page Expired!!", 'exc-uploader-theme' ) );
        }

        if ( isset( $_POST['log'] ) && is_email( $_POST['log'] ) )
        {
            $email = $_POST['log'];

            if ( $user = get_user_by( 'email', $_POST['log'] ) )
            {
                $_POST['log'] = $user->user_login;

            } else
            {
                exc_die( __('The email address is not belong to any account, please try again.', 'exc-uploader-theme' ) );
            }

        } elseif ( empty( $_POST['log'] ) )
        {
            exc_die( __( 'Please enter a valid email address.', 'exc-uploader-theme' ) );
        }

        $creds = array();

        $user = wp_signon( $creds );

        if ( is_wp_error( $user ) )
        {
            if ( $user->get_error_code() == 'incorrect_password' )
            {
                exc_die(
                    sprintf(
                        __( '<strong>ERROR</strong>: The password you entered for the email address <strong>%s</strong> is incorrect. %s</div>', 'exc-uploader-theme' ),
                        $email,
                        '<a href="' . esc_url( site_url('#forgot_password') ) . '">' . __('Lost your password', 'exc-uploader-theme') . '</a>?'
                        )
                    );
            } else
            {
                exc_die( $user->get_error_message() );
            }
        }

        wp_set_current_user( $user->ID );

        wp_send_json_success( );
    }

    function user_signup()
    {
        do_action( 'exc_user_signup' );

        //@TODO: check form refer
        //@TODO: shift this code to general method so we can use it with standard web requests
        if ( ! $this->exc()->form->is_nonce_verified( 'members_signup' ) )
        {
            exc_die( __( "Page Expired!!", 'exc-uploader-theme' ) );
        }

        $data = array();

        foreach ( $this->exc()->form->get_fields_list( 'members_signup', true ) as $k => $v )
        {
            $this->verify_special_fields( $k );
            $data[ $k ] = $this->exc()->validation->set_value( $k );
        }

        if ( count( $this->exc()->validation->_error_array ) )
        {
            $errors = array();

            foreach ( $this->exc()->validation->_error_array as $k => $v )
            {
                $errors[ $k ] = $this->exc()->validation->error( $k );
            }

            exc_die( $errors );

        }

        // we must have mandatory fields
        if ( ! isset( $data['user_login'] ) || ! isset( $data['user_pass'] ) || ! isset( $data['user_email'] ) )
        {
            exc_die( __( "Hacking Attempt", 'exc-uploader-theme' ) );
        }

        // We are not using html_entity_decode as it will also decode the html tags as well
        $data['user_pass'] = html_entity_decode( $data['user_pass'] );

        //There is no need to normalize user fields and meta data as wp_insert_user has built-in functionality

        // Set user role
        $data['role'] = exc_kv( $this->admin_settings, 'register_as', 'contributor' );

        $user = wp_insert_user( $data );

        if ( ! is_wp_error( $user ) )
        {
            $settings = get_option( 'mf_mail_settings' );

            if ( exc_kv( $settings, 'user_status' ) )
            {
                // send notifcation to user
                $email_addr = $this->exc()->validation->set_value( 'user_email' );

                $args = array(
                            'user_login'            => $this->exc()->validation->set_value( 'user_login' ),
                            'user_email'            => $email_addr,
                            'first_name'            => $this->exc()->validation->set_value( 'first_name' ),
                            'last_name'             => $this->exc()->validation->set_value( 'last_name' ),
                            'user_password'         => $this->exc()->validation->set_value( 'user_pass' ),
                            'login_url'             => site_url('#login')
                        );

                $meta = array(
                            'to'        => $email_addr,
                            'from'      => exc_kv( $settings, 'from_email' ),
                            'from_name' => exc_kv( $settings, 'from_name' ),
                    );

                $email_settings = array(
                            'status'        => 'on',
                            'subject'       => exc_kv( $settings, 'user_subject' ),
                            'body'          => exc_kv( $settings, 'user_body' )
                        );

                if ( ! exc_send_mail( $email_settings, $meta, $args ) )
                {
                    exc_die( $this->exc()->validation->errors_array() );

                }
            }

            exc_success( exc_load_template( 'modules/signup_success', $data, true ) );
            //wp_new_user_notification( $user );

        } else
        {
            wp_send_json_error( $user->get_error_message() );
            //wp_send_json_error( __("We are sorry but currently we are unable to process your request, please try again", 'exc-uploader-theme') );
        }
    }

    function verify_entry()
    {
        $field = $_POST['field'] or exc_die( __( "Invalid request possibly hacking attempt", 'exc-uploader-theme' ) );

        //Additional Verifcations
        $this->verify_special_fields( $field );

        if ( $error = $this->exc()->validation->error( $field ) )
        {
            exc_die( $error );
        }

        wp_send_json_success();
    }

    function verify_special_fields( $field )
    {
        $value = $this->exc()->validation->set_value( $field );

        if ( $field == 'user_email' )
        {
            if ( email_exists( $value ) )
            {
                $this->exc()->validation->custom_error( $field, __( "The email address is already associated with an account.", 'exc-uploader-theme' ) );
            }

        } elseif ( $field == 'user_login' )
        {
            if ( username_exists( $value ) )
            {
                $this->exc()->validation->custom_error( $field, __( "The username is already in use, please try again.", 'exc-uploader-theme' ) );

            } elseif( ! validate_username( $value ) )
            {
                $this->exc()->validation->custom_error( $field, __( "The username is not valid, please try again.", 'exc-uploader-theme' ) );
            }

        }
    }

    function enqueue_files()
    {
        //wp_enqueue_script('password-strength-meter');
        $this->exc()->html->load_js( 'exc-members', get_template_directory_uri() . '/js/members.js' );

        if ( ! is_user_logged_in() )
        {
            $this->exc()->html
                ->load_js( 'video-js', get_template_directory_uri() . '/js/video.js')
                ->load_js( 'bigvideo', get_template_directory_uri() . '/js/bigvideo.js');
        }
        //$this->exc()->html->load_js('exc-signup', $this->exc()->system_url('views/js/members.js'));
    }

    function delete_post()
    {
        if ( ! wp_verify_nonce( $_POST['security'], "exc-media-filter" ) )
        {
            exc_die( __('Page expired, please refresh page and try again.', 'exc-uploader-theme') );
        }

        $post_id = $_POST['id'];

        if ( ! intval( $post_id ) || FALSE === get_post_status( $post_id ) )
        {
            exc_die( __('Hacking Attempt!!', 'exc-uploader-theme') );
        }

        $post = get_post( $post_id );

        if ( ! empty( $post ) && ( current_user_can( 'delete_post', $post_id ) || ( is_user_logged_in() && $post->post_author == get_current_user_id() ) ) )
        {
            wp_delete_post( $post_id );
        } else
        {
            exc_die( __('Hacking Attempt!!', 'exc-uploader-theme') );
        }

        exc_success( __('The post has been deleted successfully.', 'exc-uploader-theme') );
    }

    function load_template()
    {
        $this->exc()->form->load_settings( 'members_signup' );
        exc_load_template( 'modules/templates/members', array( 'config' => $this->config ), false );
    }
}

endif;