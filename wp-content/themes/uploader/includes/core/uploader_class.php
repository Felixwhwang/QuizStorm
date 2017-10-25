<?php defined('ABSPATH') OR die('restricted access');

//@TODO: allow available uploads only
//@TODO: automatically create playlist of multiple audio and video files

if ( ! class_exists( 'eXc_Uploader_Class' ) )
{
    class eXc_Uploader_Class
    {
        private $eXc;
        private $config = array();
        private $allowed_mimes = array();
        private $settings = array();
        private $types = array( 'image', 'video', 'audio' );
        private $allowed_post_types = array( 'post', 'exc_image_post', 'exc_video_post', 'exc_audio_post' );

        private $post_type_icons = array( 'post' => 'fa-file-text-o', 'exc_image_post' => 'fa-file-image-o', 'exc_video_post' => 'fa-file-video-o', 'exc_audio_post' => 'fa-file-audio-o' );

        function __construct( &$eXc )
        {
            $this->eXc = $eXc;

            // Load media settings
            $this->settings = get_option( 'mf_uploader_settings' );

            // Restrict mime types
            $this->allowed_mimes = exc_kv( $this->settings, 'allowed_mime', $this->types );

            // Normalize the allowed file types
            $this->allowed_post_types = exc_kv( $this->settings, 'allowed_post_types', $this->allowed_post_types );

            // Ajax filtration
            add_action( 'wp_ajax_exc_mf_media_filter', array( $this, 'apply_filters' ) );
            add_action( 'wp_ajax_nopriv_exc_mf_media_filter', array( $this, 'apply_filters' ) );

            //Ajax Users filtration
            add_action( 'wp_ajax_exc_user_filter', array($this, 'apply_user_filters' ) );
            add_action( 'wp_ajax_nopriv_exc_user_filter', array($this, 'apply_user_filters' ) );

            add_action( 'wp_ajax_exc_uploader_post_data', array( &$this, 'get_post_data' ) );
            add_action( 'wp_ajax_nopriv_exc_uploader_post_data', array( &$this, 'get_post_data' ) );

            // Check if media upload is active on frontend
            if ( exc_kv( $this->settings, 'status' ) != 'on' )
            {
                // Automatically destroy this class
                return $this->eXc->_load_status = false;
            }

            if ( ( is_admin() && ! exc_is_ajax_request() ) )
            {
                add_action( 'load-post.php', array( &$this, 'register_metabox' ), 5 );
                add_action( 'load-post-new.php', array( &$this, 'register_metabox' ), 5 );

                // Do nothing on backend
                return;
            }

            //Load Style and Scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'js_files' ) );
            //add_action( 'wp_print_styles', array( $this, 'css_files') );

            //Load Wordpress Helper
            $this->eXc->load_file( 'functions/wp_helper' );

            // Ajax entry / upload validation
            add_action( 'wp_ajax_exc_mf_uploader_entry', array( $this, 'save_post' ) );
            add_action( 'wp_ajax_nopriv_exc_mf_uploader_entry', array( $this, 'save_post' ) );

            // Delete Attachment
            add_action( 'wp_ajax_exc_uploader_delete_file', array( $this, 'delete_post_attachments' ) );
            add_action( 'wp_ajax_nopriv_exc_uploader_delete_file', array( $this, 'delete_post_attachments' ) );

            //@TODO: cache prepare fields for fast processing
            //load configuration and prepare form

            if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )
            {
                $this->config = $this->eXc->load_config_file( 'uploader/frontend_post' );
                $this->eXc->load( 'core/form_class' )->prepare_fields( $this->config );
            }

            //add Post
            add_action( 'wp_ajax_nopriv_exc_media_upload', array( &$this, 'save_attachment' ) );
            add_action( 'wp_ajax_exc_media_upload', array( &$this, 'save_attachment' ) );

            add_filter( 'the_content', array( &$this, 'content_filter' ) );

            //load js templates
            //add_action( 'wp_footer', array($this, 'load_template' ) );
        }

        private function update_post_metadata( $post_id, $post_data )
        {
            // consider all extra fields as meta data
            if ( isset( $post_data['post_title'] ) )
            {
                foreach ( $this->config['_config'] as $k => $v )
                {
                    if ( ! isset( $post_data[ $k ] ) )
                    {
                        $value = $this->eXc->validation->set_value( $k );

                        add_post_meta( $post_id, $k, $value, true ) || update_post_meta( $post_id, $k, $value );
                    }
                }
            }
        }

        public function get_post_data()
        {
            if ( ! is_user_logged_in() )
            {
                exc_die( __( 'You must login to access post data.', 'exc-uploader-theme' ) );
            }

            $post_id = exc_kv( $_POST, 'post_id' );
            $secret_key = exc_kv( $_POST, 'secret_key' );

            $_POST = array();

            if ( ! intval( $post_id )
                    // || ! wp_verify_nonce( $secret_key, "exc-media-uploader-$post_id" )
                    || ! current_user_can( 'edit_posts', $post_id ) )
            {
                exc_die( __( 'You do not have permission to access this data.', 'exc-uploader-theme' ) );
            }

            $post = get_post( $post_id );

            // Edit only allowed post types
            if ( empty( $post ) || ! in_array( $post->post_type, $this->allowed_post_types ) )
            {
                exc_die( __( 'Invalid Request, please contact admin for more information.', 'exc-uploader-theme' ) );
            }

            $this->config = $this->eXc->load_config_file( 'uploader/frontend_post', array( 'post_id' => $post_id ) );
            //$this->eXc->wp_admin->prepare_fields( $this->config );

            // Find all extra fields in meta settings
            $settings = array();

            $this->eXc->load('core/form_class')->prepare_fields( $this->config );
            $fields =& $this->eXc->form->get_fields_list( 'uploader_frontend_post' );

            foreach ( $fields as $field )
            {
                $fieldname = $field->config['name'];

                if ( property_exists( $post, $fieldname ) )
                {
                    $value = $post->{ $fieldname };

                    if ( $fieldname == 'post_password' )
                    {
                        $value = str_repeat( '*', strlen( $value ) );
                    } elseif ( $fieldname == 'post_content' || $fieldname == 'post_excerpt' )
                    {
                        $value = html_entity_decode( $value );
                    }

                    $settings[ $fieldname ] = $value;
                } elseif( $fieldname == 'post_category' )
                {
                    $settings[ $fieldname ] = wp_get_post_categories( $post->ID );

                } elseif( $fieldname == 'tags_input' )
                {
                    $settings[ $fieldname ] = wp_get_post_tags( $post->ID, array( 'fields' => 'ids' ) );
                } else
                {
                    $value = get_post_meta( $post->ID, $fieldname, true );
                    $settings[ $fieldname ] = $value;
                }
            }

            $this->eXc->validation->set_data( $settings, TRUE );
            $this->eXc->load( 'core/form_class' )->prepare_fields( $this->config );

            $this->eXc->form->apply_validation();

            ob_start();

            $this->eXc->form->get_html( $this->config['_config'] );
            $this->eXc->form->get_form_settings( $this->config['_name'], array( 'action' => 'exc_mf_uploader_entry', 'post_id'  => $post_id, 'secret_key' => wp_create_nonce( "exc-media-uploader-$post_id" ) ) );

            $content = ob_get_contents();

            ob_end_clean();

            if ( 'publish' == $post->post_status )
            {
                $preview_link = esc_url( get_permalink( $post->ID ) );
            } else
            {
                $preview_link = set_url_scheme( get_permalink( $post->ID ) );
                $preview_link = esc_url( add_query_arg( 'preview', 'true', $preview_link ) );
            }

            $args = array(
                'numberposts'       => -1,
                'order'             => 'ASC',
                'post_mime_type'    => implode( ', ', $this->allowed_mimes ),
                'post_parent'       => $post->ID,
                'post_status'       => 'inherit',
                'post_type'         => 'attachment',
            );

            $attachments = get_children( $args );

            $response = array(
                            'form_data'         => $content,
                            'featured_image'    => get_post_thumbnail_id( $post_id ),
                            'attachments'       => array(),
                            'preview_link'      => $preview_link,
                            'post_type'         => $post->post_type
                        );

            $featured_image_id = get_post_thumbnail_id( $post_id );

            // Keep order
            //$response['attachments'] = array_flip( (array) get_post_meta( $post_id, 'exc_attachments_order', TRUE ) );
            $response['attachments'] = array();

            // Custom Layout Settings
            $metadata = get_post_meta( $post_id, 'mf_layout', TRUE );

            // Keep the metabox order
            if ( ! empty( $metadata['post_attachments'] ) )
            {
                foreach ( $metadata['post_attachments'] as $attachment )
                {
                    $response['attachments'][ 'attachment-' . $attachment['attachment_id'] ] = array();
                }
            }

            foreach ( $attachments as $index => $attachment )
            {
                $metadata =  wp_get_attachment_metadata( $attachment->ID );
                // @TODO: also send author and comments status
                //$meta_data = get_post_meta( $attachment->ID, '_wp_attachment_metadata', true );

                // @TODO: create the list based on config file
                $response['attachments'][ 'attachment-' . $attachment->ID ] =
                        array(
                            'id'                => $attachment->ID,
                            'attachment_title'  => $attachment->post_title,
                            'attachment_content'=> $attachment->post_content,
                            'attachment_source' => exc_kv( $metadata, 'attachment_source' ),
                            'type'              => $attachment->post_mime_type,
                            'featured'          => ( $featured_image_id == $attachment->ID ) ? "1" : "",
                            'file_date'         => sprintf(
                                                    esc_html_x( 'Submitted: %s', 'Uploader attachment time', 'exc-uploader-theme' ),
                                                    get_the_time( get_option( 'date_format' ), $attachment )
                                                )
                        );
            }

            $response['attachments'] = array_filter( $response['attachments'] );

            exc_success( $response );
        }

        function save_attachment()
        {
            if ( ! is_user_logged_in() )
            {
                exc_die( __('You must login before uploading files.', 'exc-uploader-theme' ) );
            }

            $post_id = exc_kv( $_POST, 'post_id' );
            $secret_key = exc_kv( $_POST, 'secret_key' );
            $attachment_id = exc_kv( $_POST, 'attachment_file_id' );

            if ( ! intval( $post_id ) || empty( $attachment_id )
                    || ! wp_verify_nonce( $secret_key, "exc-media-uploader-$post_id" )
                    || ! current_user_can( 'edit_posts', $post_id ) )
            {
                exc_die( __( 'You do not have permission to add / edit this post.', 'exc-uploader-theme' ) );
            }

            //$attachments_fields = exc_kv( $this->eXc->load_config_file( 'uploader/attachments' ), '_config', array() );
            $attachment_config = $this->eXc->load_config_file( 'uploader/attachments' );
            $db_name = exc_kv( $attachment_config, 'db_name' );

            $attachments_fields = exc_kv( $attachment_config, '_config', array() );

            unset( $attachment_config );

            $attachment_data_fields = array();
            $is_featured_image = ( ! empty( $_POST[ $attachment_id . '_featured_image'] ) ) ? TRUE : FALSE;

            foreach ( $attachments_fields as $fieldname => $field )
            {
                $post_field_name = $attachment_id . '_' . $fieldname;

                $attachment_data_fields[ $post_field_name ] = $fieldname;

                $this->eXc->validation->set_rules( $post_field_name, $field['label'], $field['validation'] );
            }

            $this->eXc->validation->run();

            if ( count( $this->eXc->validation->errors_array() ) )
            {
                exc_die( $this->eXc->validation->errors_array() );
            }

            // Upload File
            $file = exc_kv( $_FILES, 'async-upload' );

            // Make sure the file type is allowed
            // @TODO: add additional condition to make sure the file is not from hackers
            $type = current( (array) explode( '/', $file['type'] ) );

            $allowed_mimetypes = $this->allowed_mimetype();

            if ( false === in_array( $file['type'], $this->allowed_mimetype() ) )
            {
                exc_die(
                    sprintf(
                        __( 'You must upload only supported %s files.', 'exc-uploader-theme' ),
                        implode( ', ', array_keys( $allowed_mimetypes ) )
                    )
                );
            }

            // Check attachment limits
            if ( ! current_user_can( 'manage_options' ) )
            {
                // Get all the attachments
                $attachments = new WP_QUERY(
                                    array(
                                        'numberposts'       => -1,
                                        'post_type'         => 'attachment',
                                        'post_mime_type'    => implode( ', ', $this->allowed_mimes ),
                                        'post_parent'       => $post_id,
                                        'post_status'       => 'inherit',
                                    )
                                );

                if ( $attachments->found_posts >
                    exc_kv( $this->settings, 'attachments_limit', 5 ) )
                {
                    exc_die( __( 'We are sorry but you already reached your maximum attachments limit.', 'exc-uploader-theme' ) );
                }

                unset( $attachments );
            }

            // Upload File
            $upload = wp_handle_upload( $file, array( 'test_form' => false ) );

            // IOS has auto image rotation bug so fix it
            if ( 'image' == $type ) {
                $upload = $this->rotation_fix( $upload );
            }

            if ( ! isset( $upload['file'] ) )
            {
                exc_die(
                    sprintf(
                        __( 'Unable to upload "%s", Possible file upload attack.', 'exc-uploader-theme' ),
                        $file['name']
                    )
                );
            }

            $filename = $upload['file'];

            $attachment = array(
                            'post_mime_type' => $upload['type'],
                            'guid'           => $upload['url'],
                            'post_parent'    => $post_id,
                            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                            'post_content'   => ''
                        );

            $metadata = array();

            // Add attachment id
            $post_attachment_data = array();
            $post_attachment_data[ 'attachment_id' ] = 0;

            foreach ( $attachment_data_fields as $post_field_name => $config_field_name )
            {
                $value = $this->eXc->validation->set_value( $post_field_name );

                if ( $config_field_name == 'attachment_title' && $value )
                {
                    $attachment['post_title'] = $value;
                } elseif ( $config_field_name == 'attachment_content' && $value )
                {
                    $attachment['post_content'] = $value;
                } else
                {
                    $metadata[ $config_field_name ] = $value;
                }

                $post_attachment_data[ $config_field_name ] = $value;
            }

            $attachment_id = wp_insert_attachment( $attachment, $filename, $post_id );

            if ( is_wp_error( $attachment_id ) )
            {
                $this->removeFile( $upload ); // Remove uploaded file
                exc_die( sprintf( __( 'There was an error while uploading the image.', 'exc-uploader-theme' ), $filename ) );
            }

            $metadata = array_merge( wp_generate_attachment_metadata( $attachment_id, $filename ), (array) $metadata );

            wp_update_attachment_metadata( $attachment_id, $metadata );

            // Update Meta
            $post_settings = (array) get_post_meta( $post_id, $db_name, TRUE );

            $post_attachment_data[ 'attachment_title' ] = ( $post_attachment_data[ 'attachment_title' ] )
                                                            ? $post_attachment_data[ 'attachment_title' ]
                                                            : $attachment['post_title'];

            $post_attachment_data[ 'attachment_id' ] = $attachment_id;
            $post_attachment_data[ 'attachment_type' ] = 'attachment';

            $post_settings['post_attachments'][] = $post_attachment_data;

            update_post_meta( $post_id, $db_name, $post_settings );

            $post_attachment_data['id'] = $attachment_id;
            $post_attachment_data['type'] = $upload['type'];
            $post_attachment_data['file_date'] = esc_html_x( 'Submitted: Today', 'Uploader attachment time', 'exc-uploader-theme' );

            // If this post has no featured image and this is image then set it as featured image
            // @TODO: give users option to set the post thumbnail
            if ( $is_featured_image && $type == 'image' )
            {
                set_post_thumbnail( $post_id, $attachment_id );

                $post_attachment_data[ 'featured' ] = 1;
            }

            unset( $post_attachment_data[ 'attachment_id' ] );

            wp_send_json_success( array( $attachment_id => $post_attachment_data ) );
        }

        function allowed_mimetype()
        {
            //@TODO: Move this code to method
            $wp_allowed_mimes = get_allowed_mime_types();

            if ( ! is_array( $this->allowed_mimes ) ) {
                $this->allowed_mimes = array( $this->allowed_mimes );
            }

            $allowed_mimes = array();

            foreach ( $this->allowed_mimes as $mime ) {

                if ( empty( $mime ) ) {
                    continue;
                }

                // Fix for Chrome uploaded audio mp3
                if ( $mime == 'audio' )
                {
                    $allowed_mimes[ 'mp3' ] = 'audio/mp3';
                }

                $is_mime = strstr($mime, '/');

                foreach ( $wp_allowed_mimes as $k => $v ) {

                    if ( $is_mime ) {

                        if ( $v == $mime ) {

                            $allowed_mimes[ $k ] = $v;
                            break;
                        }

                    } elseif ( false !== strstr($v, $mime . '/') ) {
                        $allowed_mimes[ $k ] = $v;
                    }
                }

            }

            return $allowed_mimes;
        }

        function save_post()
        {
            if ( ! is_user_logged_in() )
            {
                exc_die( __('You must register before uploading files.', 'exc-uploader-theme' ) );
            }

            $post_id = exc_kv( $_POST, 'post_id', 0 );
            $secret_key = exc_kv( $_POST, 'secret_key' );
            //$create_playlist = exc_kv( $_POST, 'create_playlist' ) ? 1 : 0;

            $this->config = $this->eXc->load_config_file( 'uploader/frontend_post', array( 'post_id' => $post_id ) );
            $this->eXc->load( 'core/form_class' )->prepare_fields( $this->config );

            if ( ! is_numeric( $post_id )
                    || ! wp_verify_nonce( $secret_key, "exc-media-uploader-$post_id" )
                    || ( $post_id && ! current_user_can( 'edit_posts', $post_id ) ) )
            {
                exc_die( __( 'You do not have permission to add / edit this post.', 'exc-uploader-theme' ) );
            }

            // Display Form Errors
            if ( $errors = $this->eXc->validation->errors_array() )
            {
                exc_die( $errors );
            }

            // Quick Hack for POST ID
            if ( $post_id )
            {
                $_POST['ID'] = $post_id;

            } else // new post?
            {
                // check if the post type is allowed
                $post_type = exc_kv( $_POST, 'post_type' );

                if ( ! in_array( $post_type, $this->allowed_post_types ) )
                {
                    exc_die( __( 'Invalid post type, please refresh page and try again.', 'exc-uploader-theme' ) );
                    //exc_die( __( 'It seems the session is expired, please refresh the page and try again?', 'exc-uploader-theme' ) );
                }

                // Additional Security to make sure the allowed post type is available
                if ( ! post_type_exists( $post_type ) )
                {
                    exc_die(
                        sprintf(
                            __( '%s posts are not supported.', 'exc-uploader-theme' ),
                            exc_to_text( $post_type )
                        )
                    );
                }

                if ( ! current_user_can( 'manage_options' ) ) // check post limits, if not admin
                {
                    if ( exc_count_user_posts( get_current_user_ID(), $this->allowed_post_types, array( 'publish', 'private', 'pending', 'draft' ) ) >
                        exc_kv( $this->settings, 'posts_limit', 10 ) )
                    {
                        exc_die( __( 'We are sorry but you already reached your maximum posts limit.', 'exc-uploader-theme' ) );
                    }
                }
            }

            $post_data = array();

            $fields =& $this->eXc->form->get_fields_list( 'uploader/frontend_post' );

            foreach ( $fields as $field )
            {
                $post_data[ $field->config['name'] ] = $field->set_value();
            }

            // normalize post data
            $post_data = $this->normalize_post_data( $_POST );

            // IF THE POST ID AVAILABLE THEN UPDATE THE POST OTHERWISE INSERT NEW
            // We already checked that user has permission to edit this post
            if ( $post_id )
            {
                // Make sure that we are not going to update the post_type
                unset( $post_data['post_type'] );

                $post_data['post_status'] = get_post_status( $post_id );

                if ( ! empty( $_POST['attachments'] ) && $attachments = json_decode( stripslashes( $_POST['attachments'] ), TRUE ) )
                {
                    $attachment_config = $this->eXc->load_config_file( 'uploader/attachments' );
                    $db_name = exc_kv( $attachment_config, 'db_name' );

                    $attachments_fields = exc_kv( $attachment_config, '_config', array() );

                    unset( $attachment_config );

                    // Hack Post
                    $copy_field_data = $this->eXc->validation->_field_data;

                    $_POST = array();
                    $this->eXc->validation->_field_data = array();

                    // Hack post data
                    $hack_post_data = array();
                    $attachments_data = array();

                    foreach ( $attachments as $attachment_id => $attachment )
                    {
                        if ( ! empty ( $attachment['attachment_id'] ) && intval( $attachment['attachment_id'] )
                                && ! empty( $attachment['attachment_data'] ) )
                        {
                            foreach ( (array) $attachment['attachment_data'] as $data_key => $data_value )
                            {
                                $hack_post_data[ $data_key ] = $data_value;
                                $attachments_data[ $attachment['attachment_id'] ][ $data_key ] = $data_value;
                            }

                        } else
                        {
                            // Automatically remove invalid requests
                            unset( $attachments[ $attachment_id ] );
                        }
                    }

                    $this->eXc->validation->set_data( $hack_post_data, TRUE );

                    $attachments = $attachments_data;

                    // Reduce memory load
                    unset( $attachments_data );
                    unset( $hack_post_data );

                    // Data Validation
                    $data_fields = array();
                    $featured_image_id = 0;

                    foreach ( $attachments as $attachment_id => $attachment )
                    {
                        if ( ! $featured_image_id && ! empty( $attachment[ $attachment_id . '_featured_image'] ) )
                        {
                            $featured_image_id = $attachment_id;
                        }

                        foreach ( $attachments_fields as $fieldname => $field )
                        {
                            $post_field_name = $attachment_id . '_' . $fieldname;
                            $data_fields[ $attachment_id ][ $post_field_name ] = $fieldname;

                            $this->eXc->validation->set_rules( $attachment_id . '_' . $fieldname, $field['label'], $field['validation'] );
                        }
                    }

                    $this->eXc->validation->run();

                    if ( count( $this->eXc->validation->errors_array() ) )
                    {
                        exc_die( $this->eXc->validation->errors_array() );

                    } else
                    {
                        // Make sure that the attachment is a part of the parent post
                        $args = array(
                            'numberposts'       => -1,
                            'order'             => 'ASC',
                            'post_mime_type'    => implode( ', ', $this->allowed_mimes ),
                            'post_parent'       => $post_id,
                            'post_status'       => 'inherit',
                            'post_type'         => 'attachment',
                            'post__in'          => array_keys( $data_fields ),
                            'orderby'           => 'post__in'
                        );

                        $attachments_posts = get_children( $args );
                        $post_attachment_data = array();
                        $index = 0;

                        foreach ( (array) $attachments_posts as $attachment )
                        {
                            $postdata = array();
                            $metadata = array();

                            $post_type = current( explode( '/', $attachment->post_mime_type ) );

                            if ( $featured_image_id == $attachment->ID )
                            {
                                if ( $post_type == 'image' )
                                {
                                    set_post_thumbnail( $post_id, $featured_image_id );
                                }
                            }

                            // Add attachment id
                            $post_attachment_data[ $index ][ 'attachment_id' ] = $attachment->ID;
                            //$post_attachment_data[ $index ][ 'attachment_type' ] = $post_type;
                            $post_attachment_data[ $index ][ 'attachment_type' ] = 'attachment';

                            foreach ( $data_fields[ $attachment->ID ] as $post_field_name => $config_field_name )
                            {
                                $value = $this->eXc->validation->set_value( $post_field_name );

                                // @NOTE: update attachment core data only if the attachment belongs to this post
                                if ( $post_id == $attachment->post_parent )
                                {
                                    if ( $config_field_name == 'attachment_title' && $value )
                                    {
                                        $postdata[ 'post_title' ] = $value;
                                    } elseif ( $config_field_name == 'attachment_content' && $value )
                                    {
                                        $postdata[ 'post_content' ] = $value;
                                    } else
                                    {
                                        $metadata[ $config_field_name ] = $value;
                                    }
                                }

                                $post_attachment_data[ $index ][ $config_field_name ] = $value;
                            }

                            // Update attachment ID
                            if ( ! empty( $postdata ) )
                            {
                                $postdata['ID'] = $attachment->ID;

                                wp_update_post( $postdata );
                            }

                            if ( ! empty( $metadata ) )
                            {
                                $metadata = wp_parse_args( $metadata, (array) wp_get_attachment_metadata( $attachment->ID ) );

                                wp_update_attachment_metadata( $attachment->ID, $metadata );
                            }

                            $index++;
                        }

                        if ( $db_name )
                        {
                            $post_settings = (array) get_post_meta( $post_id, $db_name, TRUE );
                            $post_settings['post_attachments'] = $post_attachment_data;

                            update_post_meta( $post_id, $db_name, $post_settings );
                        }

                        //update_post_meta( $post_id, 'exc_attachments_order', array_keys( $data_fields ) );
                    }
                }

                $post_id = wp_update_post( $post_data, TRUE );

                $this->eXc->validation->_field_data = $copy_field_data;
            } else
            {
                $post_id = wp_insert_post( $post_data, TRUE );
            }

            if ( is_wp_error( $post_id ) )
            {
                wp_die( $post_id->get_error_messages() );
            }

            $this->update_post_metadata( $post_id, $post_data );

            // Add / Update Post Meta
            //update_post_meta( $post_id, 'exc_create_playlist', $create_playlist );

            //$this->update_post_metadata( $post_id, $post_data );

            if ( 'publish' == $post_data['post_status'] )
            {
                $preview_link = esc_url( get_permalink( $post_id ) );
            } else
            {
                $preview_link = set_url_scheme( get_permalink( $post_id ) );
                $preview_link = add_query_arg( 'preview', 'true', $preview_link );
            }

            wp_send_json_success(
                array(
                    'post_id'   => $post_id,
                    'secret_key'=> wp_create_nonce( "exc-media-uploader-$post_id" ),
                    'link'      => $preview_link
                )
            );
        }

        function delete_post_attachments()
        {
            // @TODO: Nonce Verification
            if ( ! is_user_logged_in() )
            {
                exc_die( __('You must register to preform this action.', 'exc-uploader-theme' ) );
            }

            $attachment_id = exc_kv( $_POST, 'id' );

            //$secret_key = $this->eXc->validation->set_value( 'secret_key' );

            if ( ! intval( $attachment_id )
                    /*|| ! wp_verify_nonce( $secret_key, "exc-media-uploader-$attachment_id" )*/
                    || FALSE === get_post_status( $attachment_id ) || ! current_user_can( 'delete_post', $attachment_id ) )
            {
                exc_die( __( 'Invalid Request, please contact admin for more information.', 'exc-uploader-theme' ) );
            }

            $post = get_post( $attachment_id );

            if ( empty( $post ) || 'attachment' != $post->post_type || ! $post->post_parent )
            {
                exc_die( __( 'Invalid Request, please contact admin for more information.', 'exc-uploader-theme' ) );
            }

            // Make sure the user is not deleting the featured image
            $type = current( explode( '/', $post->post_mime_type ) );

            if ( 'image' == $type &&
                    'on' == exc_kv( $this->settings, 'featured_image')
                    && intval( $post->post_parent ) )
            {
                $thumbnail_id = get_post_thumbnail_id( $post->post_parent );

                if ( $thumbnail_id == $attachment_id )
                {
                    exc_die( __('You cannot delete featured image without selecting another image as featured.', 'exc-uploader-theme' ) );
                }
            }

            if ( false === wp_delete_attachment( $attachment_id ) )
            {
                exc_die( __( "An unknown error has been occured, please try again or contact admin", 'exc-uploader-theme' ) );
            }

            // Delete from the list as well
            $metadata = get_post_meta( $post->post_parent, 'mf_layout', TRUE );
            $post_attachments = (array) exc_kv( $metadata, 'post_attachments' );

            foreach ( $post_attachments as $index => $attachment )
            {
                if ( isset( $attachment['attachment_id'] ) &&
                        $attachment['attachment_id'] == $post->ID )
                {
                    unset( $post_attachments[ $index ] );
                }
            }

            $metadata['post_attachments'] = array_values( $post_attachments );

            update_post_meta( $post->post_parent, 'mf_layout', $metadata );

            wp_send_json_success();
        }

        function js_files()
        {
            //wordpress default function to get settings wp_plupload_default_settings();
            //wp_enqueue_script( 'exc-plupload', get_template_directory_uri() . '/views/js/fields/plupload.js',
                                //array( 'jquery-ui-sortable', 'backbone', 'wp-ajax-response', 'plupload-all' ), '1.0', true );
            wp_register_script( 'exc-plupload', get_template_directory_uri() . '/js/uploader.min.js',
                                array( 'underscore', 'wp-ajax-response', 'plupload-all', 'jquery-ui-sortable' ), '1.2', true );

            //wp_enqueue_script( 'bootstrap-confirmation', get_template_directory_uri() . '/js/bootstrap-confirmation.js', array('bootstrap'), '1.0.1', true);

            $allowed_files = str_replace('|', ',', implode( (array) array_keys( $this->allowed_mimetype() ), ',') );

            $current_user_posts = exc_count_user_posts( get_current_user_ID(), $this->allowed_post_types, array( 'publish', 'private', 'pending' ) );
            $post_limit = ( ! current_user_can('manage_options') ) ? ( exc_kv( $this->settings, 'posts_limit', 10 ) - $current_user_posts ) : 0;

            $allowed_post_types = $this->get_allowed_post_types_array();

            $upload_settings = array(
                'settings' =>
                    array(
                    'runtimes'              =>  'html5,silverlight,flash,html4',
                    'file_data_name'        =>  'async-upload',
                    'browse_button'         =>  'exc-media-upload-primary-btn',
                    'multiple_queues'       =>  true,
                    'max_file_size'         =>  exc_kv( $this->settings, 'max_file_size', wp_max_upload_size() ) . 'b',
                    'url'                   =>  admin_url('admin-ajax.php'),
                    'flash_swf_url'         =>  includes_url('js/plupload/plupload.flash.swf'),
                    'silverlight_xap_url'   =>  includes_url('js/plupload/plupload.silverlight.xap'),
                    'multipart'             =>  true,
                    'urlstream_upload'      =>  true,
                    'sortable'              =>  true,

                    'rename'                =>  ( exc_kv( $this->settings, 'rename' ) == 'on' ) ? true : false,
                    'prevent_duplicates'    =>  ( exc_kv( $this->settings, 'prevent_duplicates' ) == 'on' ) ? true : false,
                    'filters'               =>  array(
                                                    array(
                                                        'title'         =>  _x( 'Allowed Media Files', 'media upload', 'exc-uploader-theme'),
                                                        'extensions'    =>  $allowed_files,
                                                    ),
                                                ),

                    'multipart_params'      =>  array(
                                                    'action'    => 'exc_media_upload',
                                                ),

                    'wp'                    =>  array(
                                                    'post_limit'        => $post_limit,
                                                    'attachments_limit' => ( ! current_user_can('manage_options') ) ? exc_kv( $this->settings, 'attachments_limit', 5 ) : 0,
                                                    'featured_image'    => ( exc_kv($this->settings, 'featured_image') == 'on') ? true : false,
                                                    'allowed_post_types'=> $allowed_post_types,
                                                    'on_success'        => exc_kv( $this->settings, 'form_save', 'default' )
                                                ),
                ),

                'noimage'                   => get_template_directory_uri() . '/images/no-image.png',

                'i18n'                      => array(
                                                    'messages'              => array( 'File size error.'    =>  __( 'File Size Error.', 'exc-uploader-theme' ) ),
                                                    'modal'                 => array(
                                                                                'frameTitle'                => esc_html_x( 'Media Uploader', 'Extracoding Uploader', 'exc-uploader-theme' ),
                                                                                'attachmentPanelHeading'    => esc_html_x( 'Your Attachments', 'Extracoding Uploader', 'exc-uploader-theme' ),
                                                                            ),

                                                    'buttons'               => array(
                                                                                'cancel'        => esc_html_x( 'Cancel', 'Extracoding Uploader', 'exc-uploader-theme'),
                                                                                'delete'        => esc_html_x( 'Delete', 'Extracoding Uploader', 'exc-uploader-theme'),
                                                                                'attachFiles'   => esc_html_x( 'Attach Files', 'Extracoding Uploader', 'exc-uploader-theme'),
                                                                                'markFeatured'  => esc_html_x( 'Mark Featured', 'Extracoding Uploader', 'exc-uploader-theme'),
                                                                                'createPlaylist'=> esc_html_x( 'Make Playlist', 'Extracoding Uploader', 'exc-uploader-theme'),
                                                                                'createList'    => esc_html_x( 'Create List', 'Extracoding Uploader', 'exc-uploader-theme'),
                                                                                'featured'      => esc_html_x( 'Featured Image', 'Extracoding Uploader', 'exc-uploader-theme'),
                                                                                'preview'       => esc_html_x( 'Preview', 'Extracoding Uploader', 'exc-uploader-theme'),
                                                                                'save'          => esc_html_x( 'Save Changes', 'Extracoding Uploader', 'exc-uploader-theme'),
                                                                                'saveDraft'     => esc_html_x( 'Save Draft', 'Extracoding Uploader', 'exc-uploader-theme'),
                                                                            ),

                                                    'uploader'              => array(
                                                                                    'unknownError' => esc_html_x( 'An unknown error has been occured please try again.', 'Extracoding Uploader', 'exc-uploader-theme' ),
                                                                                    'success'       => esc_html_x( 'File uploaded Successfully.', 'Extracoding Uploader', 'exc-uploader-theme' ),
                                                                                    'progress'      => esc_html_x( 'Uploading %s', 'Extracoding Uploader', 'exc-uploader-theme' ),
                                                                                    'cancel'        => esc_html_x( '%s:- was removed successfully from uploader que.', 'Extracoding Uploader', 'exc-uploader-theme' ),
                                                                                    'confirmDelete' => esc_html_x( 'Are you sure, you want to delete this file?.', 'Extracoding Uploader', 'exc-uploader-theme' ),
                                                                                    'updateSuccess' => esc_html_x( 'Post updated successfully.', 'Extracoding Uploader', 'exc-uploader-theme'),
                                                                                    'submittedToday'=> esc_html_x( 'Submitted: Today', 'Extracoding Uploader', 'exc-uploader-theme' )
                                                                            ),

                                                    'required'              => array(
                                                                                    'featuredImage' => esc_html_x( 'You must have to upload atleast one image.', 'Extracoding Uploader', 'exc-uploader-theme' ),
                                                                                    'login'         => sprintf( __( 'You must login to upload files, %s to login now.', 'exc-uploader-theme' ),
                                                                                                            '<a href="' . $_SERVER['REQUEST_URI'] . '#login">' . esc_html_x( 'Click here', 'Extracoding uploader', 'exc-uploader-theme' ) . '</a>'
                                                                                                        )
                                                                                ),

                                                    'requiredFeaturedImage' => esc_html__( 'You must have to upload atleast one image.', 'exc-uploader-theme' ),

                                                    'frameTitle'            => __( 'Media Uploader', 'exc-uploader-theme' ),
                                                    'frameHeadingSingular'  => __( '%d file currently uploading.', 'exc-uploader-theme' ),
                                                    'frameHeadingPlural'    => __( '%d files currently uploading.', 'exc-uploader-theme' ),
                                                    'featuredImage'         => __( 'Please upload atleast one featured image.', 'exc-uploader-theme' ),
                                                    'postLimit'             => __( 'You can upload maximum %d files.', 'exc-uploader-theme' ),
                                                    'attachmentLimit'       => __( 'You can attach maximum %d files.', 'exc-uploader-theme' ),
                                                    'unknownError'          => __( 'An unknown error has occured please try again.', 'exc-uploader-theme' ),
                                                    'settingsError'         => __( 'The settings of file "%s" are missing.', 'exc-uploader-theme' ),
                                                    'fileAddedSingular'     => __( '%d new file added successfully.', 'exc-uploader-theme' ),
                                                    'fileAddedPlural'       => __( '%d new files added successfully.', 'exc-uploader-theme' ),
                                                    'processing'            => __( 'processing...', 'exc-uploader-theme' ),
                                                    'uploading'             => __( 'Uploading %s', 'exc-uploader-theme' ),
                                                    'attachmentUpload'      => __( 'Uploading attachment file %s', 'exc-uploader-theme' ),
                                                    'fileDeleted'           => __( '%s:- is removed successfully.', 'exc-uploader-theme' ),
                                                    'uploaded'              => __( 'File uploaded Successfully.', 'exc-uploader-theme' ),
                                                    'attachmentQue'         => __( 'Waiting for attachments.', 'exc-uploader-theme' ),
                                                    'loginMessage'          => sprintf( __( 'You must login to upload files, %s to login now.', 'exc-uploader-theme' ),
                                                                                        '<a href="' . $_SERVER['REQUEST_URI'] . '#login">' . _x( 'Click here', 'extracoding uploader', 'exc-uploader-theme' ) . '</a>'
                                                                                    )
                                                ),

            );

            wp_localize_script( 'exc-plupload', 'exc_plupload', $upload_settings );
        }

        function apply_user_filters()
        {
            $query_id = ( isset( $_POST['query_id'] ) ) ? $_POST['query_id'] : '';

            // get user saved data
            $user_data = $this->eXc->load('core/session_class')->get_data( "mf_user_query_{$query_id}" );

            if ( empty( $user_data ) )
            {
                exc_page_error( 'Page Expired:',
                    __( 'Sorry, it seems that you may recently logged in or logged out and that\'s why the page was expired, please refresh it and try again.', 'exc-uploader-theme' )
                );

            } elseif ( ! isset( $_POST['offset'] ) || ! is_numeric( $_POST['offset'] ) ) //offset
            {
                $_POST['offset'] = $user_data['offset'];

            }

            if ( isset( $_POST['sort_users'] ) )
            {
                switch( $_POST['sort_users'] )
                {
                    case "most-appriciated":
                        $user_data['meta_key']  = '';
                        $user_data['orderby']   = 'appreciations';
                        break;

                    case "most-viewed":
                        $user_data['meta_key']  = '_exc_media_views';
                        $user_data['orderby']   = 'meta_value';
                        break;

                    case "most-discussed":
                        $user_data['meta_key']  = '';
                        $user_data['orderby']   = 'comment_count';
                        break;

                    case "most-recent":
                        $user_data['meta_key']  = '';
                        $user_data['orderby']   = 'ID';
                        break;

                    default:

                        if ( in_array( $user_data['meta_key'], array( 'most-appriciated', 'most-viewed', 'most-discussed', 'most-recent' ) ) )
                        {
                            $user_data['meta_key']  = '';
                        }

                        $user_data['orderby']   = 'post_count';
                        break;
                }
            }

            $user_data['offset'] = $_POST['offset'];

            // Order
            if ( isset( $_POST['order'] ) )
            {
                $user_data['order'] = ( strtolower( $_POST['order'] ) == 'asc' ) ? 'ASC' : 'DESC';
            }

            // search query
            if ( isset( $_POST['s'] ) )
            {
                $user_data['s'] = $_POST['s'];
            }

            //template
            if ( isset( $_POST['active_view'] ) ) {
                $user_data['active_view'] = ( $_POST['active_view'] == 'list' ) ? 'list' : 'grid';
            }

            exc_mf_user_query( $user_data );
        }

        function apply_filters()
        {
            // if ( ! wp_verify_nonce( $_POST['security'], "exc-media-filter" ) )
            // {
            //  exc_page_error(
            //      __( 'Page Expired', 'exc-uploader-theme' ),
            //      __( 'Sorry, it seems that you may recently logged in or logged out and that\'s why the page was expired, please refresh it and try again.', 'exc-uploader-theme' )
            //  );
            // }

            $invalid_request = true;

            if ( ! empty( $_POST["pk"] ) ) {

                $query_id = $_POST["pk"];
                $user_data = get_transient( "exc_media_query_{$query_id}" );

                if ( ! empty( $user_data ) ) {
                    $invalid_request = false;
                }
            }

            if ( $invalid_request )
            {
                exc_page_error( 'Page Expired',
                    __( 'Sorry, it seems that you may recently logged in or logged out and that\'s why the page was expired, please refresh it and try again.', 'exc-uploader-theme' )
                );

            }

            if ( ! isset( $_POST['offset'] ) || ! is_numeric( $_POST['offset'] ) ) {
                $_POST['offset'] = $user_data['offset'];
            }

            $user_data['offset'] = $_POST['offset'];

            //Post type
            if ( isset( $_POST['post_type'] ) ) {

                $post_type = $_POST['post_type'];

                if ( $post_type == 'any' ) {
                    unset( $user_data['post_type'] );
                } else {
                    if ( ! in_array( $post_type, $this->allowed_post_types ) ) {
                        exc_die( __("Invalid request, Possible hacking attempt!", 'exc-uploader-theme') );
                    }

                    $user_data['post_type'] = $post_type;
                }
            }

            // Paged
            if ( isset( $_POST['paged'] ) ) {
                $user_data['paged'] = ( is_numeric( $_POST['paged'] ) ) ? $_POST['paged'] : 0;
            }

            //category
            if ( isset( $_POST['cat'] ) ) {
                $user_data['cat'] = $_POST['cat'];
            }

            //template
            if ( isset( $_POST['active_view'] ) ) {
                $user_data['active_view'] = ( $_POST['active_view'] == 'list' ) ? 'list' : 'grid';
            }

            //search query
            if ( isset( $_POST['s'] ) ) {
                $user_data['s'] = esc_attr( $_POST['s'] );
            }

            $user_data["_cache_query"] = false;
            exc_media_query( $user_data );
        }

        public function get_option()
        {
            return $this->settings;
        }

        public function register_metabox()
        {
            $typenow = exc_kv( $GLOBALS, 'typenow' );

            if ( in_array( $typenow, $this->allowed_post_types ) )
            {
                add_filter( 'exc_config_array_metaboxes_layout_settings', array( &$this, 'extend_metabox_fields' ) );
            }
        }

        public function extend_metabox_fields( $options )
        {
            $this->eXc->html->load_js_args( 'exc-dynamic-fields', $this->eXc->system_url('views/js/fields/dynamic-fields.js'), array( 'jquery-ui-sortable', 'exc-uploader-field' ) )
                            ->inline_js( 'exc-uploader-field', $this->edit_page_custom_js(), array( 'exc-file-clickable' ), true );

            add_filter( 'exc_before_meta_update', array( &$this, 'update_attachment_metadata' ) );

            return $options;

            // $config = $this->eXc->load_config_file( 'uploader/attachments' );

            // $post_attachments = array(
            //                      'dynamic' =>
            //                          array(
            //                              '_settings'         => array( 'post_attachments' => array( 'toolbar' => array( 'delete', 'move', 'settings' ) ) ),
            //                              'post_attachments'  => $config['_config']
            //                          )
            //                  );

            // $layout_settings = $options['_config']['layout'];

            // $typenow = exc_kv( $GLOBALS, 'typenow' );

            // if ( $typenow == 'post' )
            // {
            //  $options['_config']['layout'] = array(
            //          'tabs'  => array(
            //                      'attachments' => $post_attachments,
            //                      'layout_settings' => $layout_settings
            //              )
            //      );
            // } else
            // {
            //  $options['_config']['layout'] = $post_attachments;
            // }


            // // Reduce memory load
            // unset( $config );

            // add_filter( 'exc_before_meta_update', array( &$this, 'update_attachment_metadata' ) );

            // return $options;
        }

        public function update_attachment_metadata( $settings )
        {
            if ( empty( $settings['post_attachments'] ) )
            {
                return $settings;
            }

            foreach ( $settings['post_attachments'] as $index => $attachment_data )
            {
                $invalid_request = TRUE;

                $attachment_type = exc_kv( $attachment_data, 'attachment_type' );

                switch ( $attachment_type )
                {
                    case "attachment" :

                        $attachment_id = ( ! empty( $attachment_data['attachment_id'] ) ) ? current( (array) $attachment_data['attachment_id'] ) : 0;

                        if ( ! intval( $attachment_id ) )
                        {
                            break;
                        }

                        // @TODO: make only one request with post__in option
                        $post =  get_post( $attachment_id ); //wp_get_attachment_metadata( $attachment_id );

                        if ( ! $post || ! $post->post_type == 'attachment'
                                || ! $post->post_mime_type )
                        {
                            break;
                        }

                        wp_reset_postdata();

                        $post_type = current( (array) explode('/', $post->post_mime_type ) );

                        // Keep the post type record
                        $settings['post_attachments'][ $index ]['_media_type'] = $post_type;
                        $settings['post_attachments'][ $index ]['_post_parent'] = $post->post_parent;

                        // @TODO: use wordpress native functionality to retrive these links
                        $settings['post_attachments'][ $index ]['_guid'] = $post->guid;
                        $settings['post_attachments'][ $index ]['attachment_id'] = $post->ID;

                        // Mark as valid request
                        $invalid_request = FALSE;

                    break;

                    case "video_playlist" :
                    case "audio_playlist" :

                        $playlist_ids = exc_kv( $attachment_data, 'attachment_id' );

                        if ( empty( $playlist_ids ) )
                        {
                            break;
                        }

                        $posts = get_posts(
                                    array(
                                        'ignore_sticky_posts'   => 1,
                                        'post_type'             => 'attachment',
                                        'post__in'              => $playlist_ids,
                                        'orderby'               => 'post__in',
                                        'posts_per_page'        => -1
                                    )
                                );

                        $allowed_mime_type = ( strstr( $attachment_type, 'video' ) ) ? 'video' : 'audio';

                        $playlist_ids = array();

                        foreach ( (array) $posts as $post )
                        {
                            $post_mime_type = current( (array) explode( '/', $post->post_mime_type ) );

                            if ( $allowed_mime_type == $post_mime_type )
                            {
                                $playlist_ids[] = $post->ID;
                            }
                        }

                        $settings['post_attachments'][ $index ]['attachment_id'] = $playlist_ids;
                        $settings['post_attachments'][ $index ]['_media_type'] = $attachment_type;

                        wp_reset_postdata();

                        $invalid_request = FALSE;

                    break;
                }

                if ( TRUE === $invalid_request )
                {
                    // Automatically delete invalid settings
                    unset( $settings['post_attachments'][ $index ] );
                }
            }

            return $settings;
        }

        private function normalize_post_data( $data = array() )
        {
            //@TODO: wordpress has built-in functionlity to handle post data, so remove this method
            $args = array(
                    'ID'                => '', // Are you updating an existing post?
                    'post_content'      => '', // The full text of the post.
                    'post_name'         => '', // The name (slug) for your post
                    'post_title'        => '', // The title of your post.
                    'post_status'       => 'draft', // Default 'draft'.
                    'post_type'         => 'post', // Default 'post'.
                    'post_author'       => '', // The user ID number of the author. Default is the current user ID.
                    'ping_status'       => '', // Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
                    'post_parent'       => '', // Sets the parent of the new post, if any. Default 0.
                    'menu_order'        => '', // If new post is a page, sets the order in which it should appear in supported menus. Default 0.
                    'to_ping'           => '',// Space or carriage return-separated list of URLs to ping. Default empty string.
                    'pinged'            => '',// Space or carriage return-separated list of URLs that have been pinged. Default empty string.
                    'post_password'     => '', // Password for post, if any. Default empty string.
                    'guid'              => '', //Skip this and let Wordpress handle it, usually.
                    'post_content_filtered' => '', // Skip this and let Wordpress handle it, usually.
                    'post_excerpt'      => '', // For all your post excerpt needs.
                    'post_date'         => '', // The time post was made.
                    'post_date_gmt'     => '', // The time post was made, in GMT.
                    'comment_status'    => '', // Default is the option 'default_comment_status', or 'closed'.
                    'post_category'     => '', // Default empty.
                    'tags_input'        => '', // Default empty.
                    'tax_input'         => '', // For custom taxonomies. Default empty.
                    'page_template'     => '', // Requires name of template file, eg template.php. Default empty.
                );

            $post_args = array();

            foreach ( $data as $k => $v ) {

                if ( isset( $args[ $k ] ) ) {
                    $post_args[ $k ] = $v;
                }
            }

            //status should be pending or private only
            $post_args['post_title'] = wp_strip_all_tags( $post_args['post_title'] );

            $post_args['post_status'] = ( $post_args['post_status'] == 'private' ) ? 'private' :
                                                exc_kv( $this->settings, 'post_status', 'pending' );

            $post_args['post_category'] = ( ! is_array( $post_args['post_category'] ) ) ? array( $post_args['post_category'] ) : $post_args['post_category'];

            return $post_args;
        }

        private function removeFile( $upload )
        {
            if( ! isset($upload['file']) )
            {
                return;
            }

            @unlink($upload['file']);
        }

        private function normalize_array($files)
        {
            $output = array();

            foreach ( $files as $key => $list )
            {
                foreach ( $list as $index => $value )
                {
                    $output[ $index ][ $key ] = $value;
                }
            }

            return $output;
        }

        public function get_allowed_post_types()
        {
            return $this->allowed_post_types;
        }

        public function get_allowed_post_types_array()
        {
            $allowed_post_types = array();

            foreach ( $this->allowed_post_types as $post_type )
            {
                $label = ( $post_type == 'post' ) ? _x('Article', 'extracoding uploader', 'exc-uploader-theme') : exc_to_text( str_replace( array('exc_', '_post'), '', $post_type ) );
                $icon = ( isset( $this->post_type_icons[ $post_type ] ) ) ? $this->post_type_icons[ $post_type ] : 'fa-file-text-o';

                $allowed_post_types[] = array( 'label' => __( $label, 'exc-uploader-theme' ), 'type' => $post_type, 'icon' => $icon );
            }

            return $allowed_post_types;
        }

        public function content_filter( $content )
        {
            global $wp_query;

            //remove_filter( 'the_content', array( &$this, 'content_filter' ) );

            if ( ! ( $wp_query->is_main_query() && $wp_query->in_the_loop )
                    || ! is_singular() )
            {
                return $content;
            }

            if ( post_password_required() )
            {
                return $content;
            }

            $post_id = get_the_ID();

            $settings = get_post_meta( $post_id, 'mf_layout', TRUE );

            $post_type = get_post_type();

            $general_theme_settings = get_option( 'mf_general_settings' );

            if ( 'on' == exc_kv( $general_theme_settings, 'featured_image' ) && has_post_thumbnail( $post_id ) &&
                    in_array( $post_type, array( 'post', 'exc_image_post' ) ) ) {

                $content = get_the_post_thumbnail( $post_id, 'large' ) . $content;
            }

            // OLD data support will be Depreciated in future
            if ( empty( $settings['post_attachments'] ) )
            {
                //$post_type = get_post_type();

                if ( 'exc_audio_post' == $post_type )
                {
                    $content = $this->eXc->load('core/audio_class')->content_filter( $content );

                } elseif ( 'exc_video_post' == $post_type )
                {
                    $content = $this->eXc->load('core/video_class')->content_filter( $content );
                } elseif ( 'exc_image_post' == $post_type )
                {
                    $content = $this->eXc->load('core/image_class')->content_filter( $content );
                }

                return $content;
            }

            // @TODO: cache the code and re-cache it only if the post is updated

            // Validate Data
            $settings = $this->update_attachment_metadata( $settings );

            // Attach Media
            foreach ( (array) $settings['post_attachments'] as $index => $attachment_data )
            {
                $media_type = exc_kv( $attachment_data, '_media_type' );

                $settings['post_attachments'][ $index ]['attachment_html'] = '';

                switch ( $media_type )
                {
                    case "audio" :
                        $settings['post_attachments'][ $index ]['attachment_html'] =
                            wp_audio_shortcode(
                                array(
                                    'src'       => $attachment_data['_guid'],
                                    'autoplay'  => false,
                                    'preload'   => 'none'
                                )
                            );
                    break;

                    case "video" :
                        $settings['post_attachments'][ $index ]['attachment_html'] =
                            wp_video_shortcode(
                                array(
                                    'src'       => $attachment_data['_guid'],
                                    'autoplay'  => false,
                                    'width'     => 980,
                                    'height'    => 735,
                                    'preload'   => 'none'
                                )
                            );
                    break;

                    case "image" :
                        // @TODO: give option in backend so user can select the image size
                        $settings['post_attachments'][ $index ]['attachment_html'] = wp_get_attachment_image( $attachment_data['attachment_id'], 'full', false );
                    break;
                    case "video_playlist" :
                    case "audio_playlist" :

                        if ( ! empty( $attachment_data['attachment_id'] ) )
                        {
                            $playlist_ids = implode( ', ', $attachment_data['attachment_id'] );

                            $type = ( $media_type == 'audio_playlist' ) ? 'audio' : 'video';

                            $settings['post_attachments'][ $index ]['attachment_html'] = $this->eXc->audio->skin_playlist( '[playlist type="' . $type . '" ids="' . $playlist_ids . '"]', array( 'ids' => $playlist_ids ), $playlist_ids );
                        }

                    break;

                    case "default" :
                        // Automatically unset unsupport format
                        unset( $settings['post_attachments'][ $index ] );
                    break;
                }
            }

            $content .= exc_load_template( 'modules/content-attachments', array( 'attachments' => $settings['post_attachments'] ), TRUE );

            return $content;
        }

        private function rotation_fix( $uploaded_file )
        {
            $exif = read_exif_data( $uploaded_file['file'] );

            //We're only interested in the orientation
            $exif_orientation = isset( $exif['Orientation'] ) ? $exif['Orientation'] : 0;

            $rotateImage = 0;

            if (6 == $exif_orientation) {

                $rotateImage = 90;
                $imageOrientation = 1;

            } elseif (3 == $exif_orientation) {

                $rotateImage = 180;
                $imageOrientation = 1;

            } elseif (8 == $exif_orientation) {
                $rotateImage = 270;
                $imageOrientation = 1;
            }

            if ($rotateImage) {

                if ( class_exists('Imagick') ) {
                    $imagick = new Imagick();
                    $imagick->readImage($uploaded_file['file']);
                    $imagick->rotateImage(new ImagickPixel(), $rotateImage);
                    $imagick->setImageOrientation($imageOrientation);
                    $imagick->writeImage($uploaded_file['file']);
                    $imagick->clear();
                    $imagick->destroy();

                } else {

                    $rotateImage = -$rotateImage;

                    switch ($uploaded_file['type']) {

                        case 'image/jpeg':
                            $source = imagecreatefromjpeg($uploaded_file['file']);
                            $rotate = imagerotate($source, $rotateImage, 0);
                            imagejpeg($rotate, $uploaded_file['file']);
                        break;

                        case 'image/png':
                            $source = imagecreatefrompng($uploaded_file['file']);
                            $rotate = imagerotate($source, $rotateImage, 0);
                            imagepng($rotate, $uploaded_file['file']);
                        break;

                        case 'image/gif':
                            $source = imagecreatefromgif($uploaded_file['file']);
                            $rotate = imagerotate($source, $rotateImage, 0);
                            imagegif($rotate, $uploaded_file['file']);
                        break;

                    }
                }
            }
            // The image orientation is fixed, pass it back for further processing
            return $uploaded_file;
        }

        private function edit_page_custom_js()
        {
            return '

                    $( document ).on("click", ".exc-clickable-wrapper > .exc-clickable", function(e){
                        var $this = $( this ),
                        mimeTypes = $this.data("mime-types"),
                        uploadLimit = $this.data("upload-limit");

                        if ( mimeTypes.length ) {

                            var uploadBtn = $this.parents(".panel-body:first").find(".exc-media-uploader");
                            uploadBtn.attr({"data-mime-types": mimeTypes, "data-upload-limit": uploadLimit });

                            uploadBtn.trigger( "change" );
                        }
                    });

                    $( document ).on("exc-dynamic-add_row", function( e, row ){
                        row.find(".exc-clickable-wrapper > .exc-clickable").trigger("click");
                    });
                ';
        }
    }
}