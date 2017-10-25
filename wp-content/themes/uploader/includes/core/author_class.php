<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Author_Class' ) )
{
    class eXc_Author_Class
    {
        private $eXc;
        private $wpdb;
        private $_update_views_in = 600000; // Update views after 10 minutes
        //private $unlike_html =
        //private $_post_relike_in = 600000;

        function __construct( &$eXc )
        {
            $this->eXc = $eXc;
            $this->wpdb =& $GLOBALS['wpdb'];

            //$this->eXc->html->inline_js('test', "jQuery(document).ready( function($){eXc.notification( {id: 'resending-mail', message: 'this is page load message', insertion: 'replace', ttl: 5000, layout: 'bar', effect: 'slidetop', type: 'error', 'icon': 'fa fa-times' } );});");

            if( exc_is_client_side() )
            {
                add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );

                // Post Likes Ajax Callbacks
                add_action( 'wp_ajax_nopriv_exc_post_like', array( &$this, 'like_post') );
                add_action( 'wp_ajax_exc_post_like', array( &$this, 'like_post') );

                // Follow Authors
                add_action( 'wp_ajax_nopriv_exc_follow_author', array( &$this, 'follow_author') );
                add_action( 'wp_ajax_exc_follow_author', array( &$this, 'follow_author') );

                // Resend Subscriber Email
                add_action( 'wp_ajax_nopriv_resend_subscriber_email', array( &$this, 'resend_subscriber_email' ) );

                $this->eXc->load('core/validation_class');

                $this->eXc->load_file('functions/author');

                if ( isset( $_GET['ua'] ) && $_GET['ua'] == 'subscribe' )
                {
                    $this->activate_subscription();
                }
            }

            // Nofity users when publish a post.
            add_action( 'transition_post_status', array( &$this, 'send_notification_post' ), 10, 3 );

            // Update user media views
            add_filter( 'the_content', array( &$this, 'update_views' ) );
        }

        public function resend_subscriber_email()
        {
            if ( ! wp_verify_nonce( $_POST['security'], 'exc-author-class' ) )
            {
                exc_die( __('We are sorry but we cannot process your request, please refresh page and try again.', 'exc-uploader-theme' ) );
            }

            $session_data = $this->eXc->session->get_data('exc-follower-info');

            // Validate session data, if in case it is removed or corrupted or maybe user is trying to penetrate
            $this->eXc->validation->set_data( $session_data, true );

            $this->eXc->validation->set_rules( 'follower_id', '', 'required|is_natural_no_zero');
            $this->eXc->validation->set_rules( 'follower_name', '', 'required|min_length[3]|max_length[25]|alpha_space' );
            $this->eXc->validation->set_rules( 'follower_email', '', 'required|valid_email' );

            if ( $this->eXc->validation->run() !== FALSE )
            {
                $id = $this->eXc->validation->set_value('follower_id');
                $name = $this->eXc->validation->set_value('follower_name');
                $email = $this->eXc->validation->set_value('follower_email');

                $subscriber = $this->wpdb->get_row(
                                            $this->wpdb->prepare(
                                                "SELECT * FROM {$this->wpdb->prefix}exc_subscribers WHERE subscriber_id = %d LIMIT 0, 1",
                                                $id
                                            )
                                        );

                $response = array();

                if ( ! empty( $subscriber ) )
                {
                    // Check status
                    if ( $subscriber->subscriber_status )
                    {
                        exc_success( __( 'Your email address is already verified, please refresh page to start following users.', 'exc-uploader-theme' ) );
                    }

                    $activation_code = wp_generate_password( 20, false );

                    $this->wpdb->update(
                        $this->wpdb->prefix . 'exc_subscribers',

                        array(
                            'subscriber_activation_key' => $activation_code
                        ),

                        array(
                            'subscriber_id' => $subscriber->subscriber_id
                        )
                    );

                    $settings = get_option( 'mf_mail_settings' );

                    if ( exc_kv( $settings, 'subscriber_status' ) )
                    {
                        $meta = array(
                                    'to'        => $email,
                                    'from'      => exc_kv( $settings, 'from_email' ),
                                    'from_name' => exc_kv( $settings, 'from_name' )
                                );

                        $args = array(
                                'subscriber_name'           => $name,
                                'subscriber_email'          => $email,
                                'subscriber_activation_url' => site_url( "?ua=subscribe&sid={$subscriber->subscriber_id}&code={$activation_code}" )
                            );

                        $email_settings = array(
                                'status'        => exc_kv( $settings, 'subscriber_status' ),
                                'content_type'  => exc_kv( $settings, 'subscriber_conteny_type' ),
                                'subject'       => exc_kv( $settings, 'subscriber_subject' ),
                                'body'          => exc_kv( $settings, 'subscriber_body' )
                            );

                        if ( exc_send_mail( $email_settings, $meta, $args ) )
                        {
                            $response['html'] = __( "Another verification email has been sent to you, please check your inbox.", 'exc-uploader-theme' );
                            exc_success( $response );
                        }
                    }
                }
            }

            $errors = $this->eXc->validation->errors_array();

            $response['html'] = count( $errors )
                                ? implode( "\n", $errors )
                                 : __( "We are sorry but right now the system is not able to process your request, please try again later or contact us if the problem presists.", 'exc-uploader-theme' );
            exc_die( $response );
        }

        private function activate_subscription()
        {
            $err_message = __('We are sorry but your subscription code is invalid or expired.', 'exc-uploader-theme' );

            // @TODO: show different message if the user is logged in
            if ( empty( $_GET['sid'] ) || ! intval( $_GET['sid'] )
                || empty( $_GET['code'] ) || strlen( $_GET['code'] ) != 20 )
            {
                $this->eXc->session->set_flashdata( 'session_message', array( 'type' => 'error', 'message' => $err_message ) );
            } elseif ( is_user_logged_in() ) // If in case user requested for submission and then created account
            {
                $this->eXc->session->set_flashdata( 'session_message', array( 'type' => 'error', 'message' => __('You are already registered and can follow users.', 'exc-uploader-theme') ) );
            } else
            {
                $subscriber = $this->wpdb->get_row(
                                        $this->wpdb->prepare(
                                            "SELECT * FROM {$this->wpdb->prefix}exc_subscribers WHERE subscriber_id = %d AND subscriber_activation_key = %s LIMIT 0, 1",
                                            $_GET['sid'],
                                            $_GET['code']
                                        )
                                    );

                if ( ! empty( $subscriber ) )
                {
                    $this->wpdb->update(
                        $this->wpdb->prefix . 'exc_subscribers',

                        array(
                            'subscriber_status'         => 1,
                            'subscriber_activation_key' => ''
                        ),

                        array(
                            'subscriber_id' => $subscriber->subscriber_id
                        )
                    );

                    $this->eXc->session->set_flashdata( 'session_message', array( 'type' => 'success', 'message' => __('Thank you for your subscription, you may now follow users.', 'exc-uploader-theme'), 'icon' => 'fa fa-check') );

                } else
                {
                    $this->eXc->session->set_flashdata( 'session_message', array( 'type' => 'error', 'message' => $err_message ) );
                }
            }

            wp_safe_redirect( site_url(), 301 );
            exit;
        }

        public function follow_author()
        {
            // @TODO: send email notifications
            $author_id = $_POST['author_id'];

            if ( ! wp_verify_nonce( $_POST['security'], 'exc-author-class' ) || ! intval( $author_id )
                    // || ! user_can( $author_id, 'edit_posts' )
                )
            {
                exc_die( array( 'html' => '<div class="alert alert-danger">' . __('Hacking Attempt!!', 'exc-uploader-theme' ) . '</div>' ) );
            }

            $author = get_user_by( 'id', $author_id );

            $success = false;
            $response = array();

            if ( ! is_user_logged_in() )
            {
                $session_data = $this->eXc->session->get_data('exc-follower-info');

                if ( ! isset( $_POST['follower_name'] ) ) // Time to validate info
                {
                    $this->eXc->validation->set_data( $session_data, true );
                }

                if ( isset( $_POST['follower_name'] ) )
                {
                    $this->eXc->validation->set_rules( 'follower_name', _x( 'Name', 'author subscription form', 'exc-uploader-theme' ), 'required|min_length[3]|max_length[25]|alpha_space' );
                    $this->eXc->validation->set_rules( 'follower_email', _x( 'Email', 'author subcription form', 'exc-uploader-theme' ), 'required|valid_email' );

                    if ( $this->eXc->validation->run() !== FALSE )
                    {
                        $name = $this->eXc->validation->set_value('follower_name');
                        $email = $this->eXc->validation->set_value('follower_email');

                        // The user infomation is valid, time to save them
                        $subscriber = $this->wpdb->get_row(
                                        $this->wpdb->prepare(
                                            "SELECT * FROM {$this->wpdb->prefix}exc_subscribers WHERE subscriber_email = %s LIMIT 0, 1",
                                            $email
                                        )
                                    );

                        $subscriber_id = ( $subscriber ) ? $subscriber->subscriber_id : 0;
                        $this->eXc->session->set_data( 'exc-follower-info', array( 'follower_name' => $name, 'follower_email' => $email, 'follower_id' => $subscriber_id ) );

                        if ( ! $subscriber && count( $this->eXc->validation->errors_array() ) == 0 )
                        {
                            $activation_code = wp_generate_password( 20, false );

                            // @todo: send verification email
                            // Send Email to user
                            $settings = get_option( 'mf_mail_settings' );

                            if ( exc_kv( $settings, 'subscriber_status' ) )
                            {
                                $meta = array(
                                            'to'        => $email,
                                            'from'      => exc_kv( $settings, 'from_email' ),
                                            'from_name' => exc_kv( $settings, 'from_name' )
                                        );

                                $args = array(
                                        'subscriber_name'           => $name,
                                        'subscriber_email'          => $email,
                                        'subscriber_activation_url' => site_url( "?ua=subscribe&sid={$this->wpdb->insert_id}&code={$activation_code}" )
                                    );

                                $email_settings = array(
                                        'status'        => exc_kv( $settings, 'subscriber_status' ),
                                        'content_type'  => exc_kv( $settings, 'subscriber_conteny_type' ),
                                        'subject'       => exc_kv( $settings, 'subscriber_subject' ),
                                        'body'          => exc_kv( $settings, 'subscriber_body' )
                                    );

                                if ( exc_send_mail( $email_settings, $meta, $args ) )
                                {
                                    $this->wpdb->insert(
                                            $this->wpdb->prefix . 'exc_subscribers',
                                            array(
                                                'subscriber_name'           => $name,
                                                'subscriber_email'          => $email,
                                                'subscriber_type'           => 'author',
                                                'subscriber_activation_key' => $activation_code
                                            ),

                                            '%s'
                                        );

                                    if ( ! $this->wpdb->insert_id )
                                    {
                                        exc_die( __("We are sorry, system is not able to process your request now, please try again.", 'exc-uploader-theme' ) );
                                    }

                                    $response['html'] = '<div class="alert alert-success">' .
                                                            __( "Congratulations! You're just one step away to start following users, please check your email address for verification email.", 'exc-uploader-theme' ) .
                                                        '</div>';

                                    exc_success( $response );

                                } else
                                {
                                    $errors = $this->eXc->validation->errors_array();

                                    $response['html'] = ( count( $errors ) )
                                                        ? '<div class="alert alert-danger">' . implode( '\n', $errors ). '</div>'
                                                        : '<div class="alert alert-danger">' .
                                                            __( "We are sorry but system is not able to process your request right now, please try again later or contact us.", 'exc-uploader-theme' ) .
                                                        '</div>';

                                    exc_die( $response );
                                }

                            } else
                            {
                                exc_die( array( 'html' => '<div class="alert alert-danger">' . __('The subscriber email form settings are empty.', 'exc-uploader-theme') . '</div>' ) );
                            }
                        }

                        // Send activation email
                        $row = $this->wpdb->get_row(
                                $this->wpdb->prepare(
                                    "SELECT * FROM {$this->wpdb->prefix}exc_followers WHERE follower_author_id = %d AND follower_subscriber_id = %d LIMIT 0, 1",
                                    $author->ID,
                                    $subscriber->subscriber_id
                                )
                            );


                        if (  ! $subscriber->subscriber_status )
                        {
                            $response['html'] = '<div class="alert alert-danger">' .
                                                    sprintf
                                                    (
                                                        __( "You must verify your email address before following any other user, if in case you've not received any email from us then please check your spam folder or %s.", 'exc-uploader-theme' ),
                                                        '<a href="#" class="exc-follow-resend">' . _x( "Click here to resend", 'extracoding user following', 'exc-uploader-theme' ) . '</a>'
                                                    ) . '</div>';

                        exc_die( $response );

                        } else if ( ! $row /*&& ! $row->follower_id*/ )
                        {
                            $this->wpdb->insert(
                                $this->wpdb->prefix . 'exc_followers',
                                array(
                                    'follower_author_id'        => $author->ID,
                                    'follower_subscriber_id'    => $subscriber->subscriber_id,
                                ),

                                '%d'
                            );

                            $response['html'] = '<div class="alert alert-success">' .
                                sprintf(
                                    __( "Congratulations! You are now successfully following %s.", 'exc-uploader-theme' ),
                                    ucwords( $author->display_name )
                                ) . '</div>';

                            exc_success( $response );
                        } else
                        {
                            $status = ( $row->follower_status ) ? 0 : 1;

                            $this->wpdb->update(
                                $this->wpdb->prefix . 'exc_followers',

                                array(
                                    'follower_status' => $status
                                ),

                                array(
                                    'follower_id'   => $row->follower_id
                                ),

                                '%d'
                            );

                            if ( $status )
                            {
                                $response['html'] = '<div class="alert alert-success">' . __("Congratulations! you're now following %s again.", 'exc-uploader-theme') . '</div>';
                                $response['i18n_str'] = __( 'Unfollow', 'exc-uploader-theme' );
                            } else
                            {
                                $response['html'] = '<div class="alert alert-info">' . __("You're no more following %s and will not recieve anymore notifications related to them.", 'exc-uploader-theme') . '</div>';
                                $response['i18n_str'] = __( 'Follow', 'exc-uploader-theme' );
                            }

                            $response['html'] = sprintf( $response['html'], ucwords( $author->display_name ) );
                            exc_success( $response );
                        }
                    }

                }

                $response['html'] = $this->eXc->load_view( 'author/subscription_form', array(), true );
                exc_success( $response );
            } else
            {
                // Check if current user is already following user
                $current_user = wp_get_current_user();

                if ( $current_user->ID == $author->ID )
                {
                    $response['html'] = '<div class="alert alert-warning">' . __("We are sorry but you cannot follow yourself.", 'exc-uploader-theme') . '</div>';
                    exc_die( $response );
                }

                $row = $this->wpdb->get_row(
                                $this->wpdb->prepare(
                                    "SELECT * FROM {$this->wpdb->prefix}exc_followers WHERE follower_author_id = %d AND follower_user_id = %d LIMIT 0, 1",
                                    $author->ID,
                                    $current_user->ID
                                )
                            );

                if ( ! $row )
                {
                    $this->wpdb->insert(
                                    $this->wpdb->prefix . 'exc_followers',
                                    array(
                                        'follower_author_id'    => $author->ID,
                                        'follower_user_id'      => $current_user->ID,
                                    ),

                                    '%d'
                                );

                    $response['html'] = '<div class="alert alert-success">' .
                        sprintf(
                            __( "Congratulations! You are now successfully following %s.", 'exc-uploader-theme' ),
                            ucwords( $author->display_name )
                        ) . '</div>';

                    // Send Email to user
                    $settings = get_option( 'mf_mail_settings' );

                    if ( exc_kv( $settings, 'follow_status' ) )
                    {
                        $meta = array(
                                    'to'        => $author->user_email,
                                    'from'      => exc_kv( $settings, 'from_email' ),
                                    'from_name' => exc_kv( $settings, 'from_name' ),
                            );

                        $args = array(
                                'author_name'   => exc_get_user_name( $author, true ),
                                'user_name'     => exc_get_user_name( $current_user, true )
                            );

                        $email_settings = array(
                                'status'        => exc_kv( $settings, 'follow_status' ),
                                'content_type'  => exc_kv( $settings, 'follow_content_type' ),
                                'subject'       => exc_kv( $settings, 'follow_subject' ),
                                'body'          => exc_kv( $settings, 'follow_body' )
                            );

                        exc_send_mail( $email_settings, $meta, $args );
                    }

                    exc_success( $response );

                } else
                {
                    $status = ( $row->follower_status ) ? 0 : 1;

                    $this->wpdb->update(
                        $this->wpdb->prefix . 'exc_followers',

                        array(
                            'follower_status' => $status
                        ),

                        array(
                            'follower_id'   => $row->follower_id
                        ),

                        '%d'
                    );

                    if ( $status )
                    {
                        $response['html'] = '<div class="alert alert-success">' . __("Congratulations! you're now following %s again.", 'exc-uploader-theme') . '</div>';
                        $response['i18n_str'] = __( 'Unfollow', 'exc-uploader-theme' );
                    } else
                    {
                        $response['html'] = '<div class="alert alert-info">' . __("You're no more following %s and will not recieve anymore notifications related to them.", 'exc-uploader-theme') . '</div>';
                        $response['i18n_str'] = __( 'Follow', 'exc-uploader-theme' );
                    }

                    $response['html'] = sprintf( $response['html'], ucwords( $author->display_name ) );
                    exc_success( $response );
                }
            }
        }

        public function get_follower( $author_id, $user_id = '', $subscriber_id = '' )
        {
            if ( $user_id )
            {
                return
                $this->wpdb->get_row(
                    $this->wpdb->prepare(
                        "SELECT * FROM {$this->wpdb->prefix}exc_followers WHERE follower_author_id = %d AND Follower_user_id = %d AND follower_status = 1",
                        $author_id,
                        $user_id
                    )
                );
            } else
            {
                $subscriber_id = ( intval( $subscriber_id ) ) ? $subscriber_id : (int) exc_kv( $this->eXc->session->get_data('exc-follower-info'), 'follower_id', 0 );

                if ( ! $subscriber_id )
                {
                    return 0;
                }

                return
                    $this->wpdb->get_row(
                        $this->wpdb->prepare(
                            "SELECT * FROM {$this->wpdb->prefix}exc_followers WHERE follower_author_id = %d AND follower_subscriber_id = %d AND follower_status = 1",
                            $author_id,
                            $subscriber_id
                        )
                    );
            }
        }

        public function get_followers( $author_id )
        {
            return $this->wpdb->get_var(
                            $this->wpdb->prepare(
                                "SELECT count(*) FROM {$this->wpdb->prefix}exc_followers WHERE author_id = %d",
                                $author_id
                            )
                        );
        }

        public function get_author_following( $author_id )
        {
            return
            $this->wpdb->get_var(
                    $this->wpdb->prepare(
                        "SELECT count(*) FROM {$this->wpdb->prefix}exc_followers WHERE follower_user_id = %d AND follower_status = 1",
                        $author_id
                    )
                );
        }

        public function get_author_followers( $author_id )
        {
            return
            $this->wpdb->get_var(
                    $this->wpdb->prepare(
                        "SELECT count(*) FROM {$this->wpdb->prefix}exc_followers WHERE follower_author_id = %d AND follower_status = 1",
                        $author_id
                    )
                );
        }

        public function get_author_followers_emails( $author_id )
        {
            return
                $this->wpdb->get_results(
                    $this->wpdb->prepare(
                        "SELECT a.follower_id, b.subscriber_email, c.user_email FROM {$this->wpdb->prefix}exc_followers as a
                            LEFT JOIN {$this->wpdb->prefix}exc_subscribers as b on a.follower_subscriber_id = b.subscriber_id
                            LEFT JOIN {$this->wpdb->prefix}users as c on a.follower_user_id = c.ID Where a.follower_author_id = %d  AND a.follower_status = 1",
                        $author_id
                    )
                );
        }

        public function get_author_votes( $author_id )
        {
            return
            $this->wpdb->get_var(
                    $this->wpdb->prepare(
                        "SELECT count(*) FROM {$this->wpdb->prefix}exc_votes WHERE author_id = %d AND status = 1",
                        $author_id
                    )
                );
        }

        public function like_post()
        {
            $post_id = exc_kv( $_POST, 'ID' );
            $security = exc_kv( $_POST, 'security' );

            if ( ! wp_verify_nonce( $security, 'exc-author-class' ) || ! ( intval( $post_id ) )
                || 'publish' != get_post_status( $post_id ) )
            {
                exc_die( __('Page Expired!!', 'exc-uploader-theme' ) );
            }

            $response = array();

            if ( ! is_user_logged_in() )
            {
                $response = array(
                                'message'   => __('You must login to like this post.', 'exc-uploader-theme' ),
                                'icon'      => 'icon fa fa-lock'
                            );

                exc_die( $response );
            }

            $success = false;
            $user_id = get_current_user_id();

            $post = get_post( $post_id );

            if ( ! $post || $post->post_status != 'publish' )
            {
                exc_die( __( 'You cannot like this post.', 'exc-uploader-theme' ) );

            } elseif ( $post->post_author == $user_id ) {

                exc_die( __('You cannot like your own post.', 'exc-uploader-theme') );
            }

            // Check if user already liked
            $vote =
                $this->wpdb->get_row(
                    $this->wpdb->prepare(
                        "SELECT vote_id, status FROM {$this->wpdb->prefix}exc_votes WHERE post_id = %d AND user_id = %d",
                        $post_id,
                        $user_id
                    )
                );

            if ( $vote )
            {
                $status = ( $vote->status ) ? 0 : 1;

                $this->wpdb->update(
                        $this->wpdb->prefix . 'exc_votes',

                        array(
                            'status'    => $status
                        ),

                        array(
                            'vote_id'   => $vote->vote_id,
                        ),

                        '%d'
                    );

                $success = true;

                if ( $status )
                {
                    $response['message'] = __( "Congratulations! you liked it again.", 'exc-uploader-theme' );
                    $response['icon'] = 'icon fa fa-thumbs-o-up';
                    $response['icon_class'] = 'liked';
                } else
                {
                    $response['message'] = __( "You have successfully withdrawn your like.", 'exc-uploader-theme' );
                    $response['icon'] = 'icon fa fa-thumbs-o-down';
                }

            } else
            {
                $status = 1;

                $post = get_post( $post_id );

                $this->wpdb->insert(
                    $this->wpdb->prefix . 'exc_votes',

                    array(
                        'post_id'   => $post_id,
                        'user_id'   => $user_id,
                        'author_id' => $post->post_author,
                        'status'    => 1,
                    ),

                    '%d'
                );

                if ( ! $this->wpdb->insert_id )
                {
                    exc_die( __( "We are sorry, system is not able to process your request now, please try again.", 'exc-uploader-theme' ) );
                } else
                {
                    // Send Email to user
                    $settings = get_option( 'mf_mail_settings' );

                    if ( exc_kv( $settings, 'like_status' ) )
                    {
                        $meta = array(
                                    'to'        => get_the_author_meta('email', $post->post_author ),
                                    'from'      => exc_kv( $settings, 'from_email' ),
                                    'from_name' => exc_kv( $settings, 'from_name' ),
                            );

                        $current_user = wp_get_current_user();

                        $args = array(
                                'post_title'    => $post->post_title,
                                'post_url'      => get_permalink( $post->ID ),
                                'author_name'   => exc_get_user_name( $post->post_author, true ),
                                'user_name'     => exc_get_user_name( $current_user, true )
                            );

                        $email_settings = array(
                                'status'        => exc_kv( $settings, 'like_status' ),
                                'content_type'  => exc_kv( $settings, 'like_content_type' ),
                                'subject'       => exc_kv( $settings, 'like_subject' ),
                                'body'          => exc_kv( $settings, 'like_body' )
                            );

                        exc_send_mail( $email_settings, $meta, $args );
                    }

                    $success = true;
                    $response['message'] = __( 'Thank you for liking this post.', 'exc-uploader-theme' );

                    $response['icon']   = 'icon fa fa-thumbs-o-up';
                    $response['icon_class'] = 'liked';
                }
            }

            do_action( 'exc_post_like', $post_id, $status );

            $response['votes'] = $this->get_votes( $post_id );

            $response['i18n_str'] = ( $response['votes'] ) ?
                                    sprintf
                                    (
                                        _n( 'One Like', '%1$s Likes', $response['votes'], 'exc-uploader-theme' ),
                                        number_format_i18n( $response['votes'] )
                                    ) :

                                    __( 'Be the first to like it', 'exc-uploader-theme' );

            ( $success ) ? exc_success( $response ) : exc_die( $response );
        }

        /*function email_user( $settings, $meta, $args = array() )
        {
            $settings = wp_parse_args(
                            $settings,
                            array(
                                'status'        => '',
                                'content_type'  => 'text',
                                'subject'       => '',
                                'body'          => ''
                            )
                        );

            if ( $settings['status'] == 'on' )
            {
                $this->eXc->load('core/mail_class');

                $meta = wp_parse_args(
                        $meta,
                        array(
                            'to'            => '',
                            'from'          => '',
                            'from_name'     => '',
                            'contentType'   => $settings['content_type'],
                            'subject'       => $this->eXc->mail->parse_template( exc_kv( $settings, 'subject' ), $args ),
                            'message'       => $this->eXc->mail->parse_template( exc_kv( $settings, 'body' ), $args )
                        )
                    );

                return $this->eXc->mail->send( $meta );
            }
        }*/

        function get_votes_data( $post_id = '' )
        {
            // Return information related to votes
        }

        public function is_voted( $post_id = '', $user_id = '' )
        {
            $post_id = ( intval( $post_id ) ) ? $post_id : get_the_ID();

            $user_id = ( intval( $user_id ) ) ? $user_id : get_current_user_id();

            if ( ! $post_id || ! $user_id )
            {
                return false;
            }

            return $GLOBALS['wpdb']->get_var(
                        $GLOBALS['wpdb']->prepare(
                                "SELECT count(*) from {$GLOBALS['wpdb']->prefix}exc_votes WHERE post_id = %d and user_id = %d and status = 1",
                                $post_id,
                                $user_id
                            )
                        );
        }

        public function get_votes( $post_id = '' )
        {
            $post_id = ( $post_id ) ? $post_id : get_the_ID();

            return $GLOBALS['wpdb']->get_var(
                        $GLOBALS['wpdb']->prepare(
                                "SELECT count(*) from {$GLOBALS['wpdb']->prefix}exc_votes WHERE post_id = %d and status = 1",
                                $post_id
                            )
                        );
        }

        public function update_views( $content )
        {
            if ( is_single() || is_page() )
            {
                // Current Time
                $time_now = current_time( 'timestamp', 1 );

                // Check if Current User view is already counted
                $session_key = 'post_views_' . get_the_ID();

                if ( $session_time = $this->eXc->session->get_data( $session_key ) )
                {
                    // Check if session is expired
                    if ( ( $time_now - $this->_update_views_in ) >= $session_time )
                    {
                        $session_time = '';

                    } else
                    {
                        return $content;
                    }
                }

                // Update session time
                $this->eXc->session->set_data( $session_key, $time_now );

                // Call Global Extracoding Function to Update Current Post View
                exc_set_views( );

                // Update Author Overall Views
                $author_id = get_the_author_meta('ID');

                if( ! $views = get_the_author_meta( '_exc_media_views', $author_id ) )
                {
                    $views = 0;
                }

                update_user_meta( $author_id, '_exc_media_views', ++$views );
            }

            return $content;
        }

        public function enqueue_scripts()
        {
            // @TODO: Register Script in classes and enqueue them where they are required
            if( $url = $this->eXc->get_file_url('views/js/author.js', 'local_dir' ) )
            {
                wp_enqueue_script( 'exc-author-js', $url, array('jquery'), $this->eXc->get_version(), true );

                wp_localize_script('exc-author-js', 'exc_author_js', array(
                    'security'          => wp_create_nonce( 'exc-author-class' ),
                    'resend_i18n_str'   => __( 'Resending Activation Email', 'exc-uploader-theme' )
                ));
            }
        }

        protected function has_published( $new_status, $old_status )
        {
            $published = false;

            if ( $new_status === 'publish' && $old_status !== 'publish' )
            {
                $published = true;
            }

            return $published;
        }

        public function send_notification_post( $new_status, $old_status, $post )
        {
            $has_published = $this->has_published( $new_status, $old_status );
            //$has_published = true;

            $allowed_statuses = apply_filters( 'notify_users_email_allowed_post_statuses', $has_published, $new_status, $old_status );

            if ( ! $allowed_statuses ) {
                return;
            }

            // Prevent sending twice
            $sent = get_post_meta( $post->ID, '_notify_users_email_sended', true );

            if ( $sent ) {
                return;
            }

            $settings = array();
            // $settings = get_option( 'notify_users_email', array() );

            // if ( ! in_array( $post->post_type, (array) $settings['conditional_post_type'] ) ){
            //  return;
            // }

            $obj_taxonomies = get_object_taxonomies( $post->post_type, 'names' );

            $run = array();
            foreach ( $obj_taxonomies as $taxonomy ) {
                $run[ $taxonomy ] = true;
            }

            if ( 0 !== count( $run ) )
            {
                foreach ( $settings as $key => $value )
                {
                    if ( false === strrpos( $key, 'conditional_taxonomy_' ) ) {
                        continue;
                    }

                    $terms = array_filter( array_unique( array_map( 'absint', array_map( 'trim', (array) $value ) ) ) );

                    if ( empty( $terms ) ){
                        continue;
                    }

                    $taxonomy = str_replace( 'conditional_taxonomy_', '', $key );
                    $run[ $taxonomy ] = false;

                    foreach ( $terms as $key => $term ) {
                        if ( has_term( $term, $taxonomy, $post ) ){
                            $run[ $taxonomy ] = true;
                        }
                    }
                }

            } else
            {
                $run = array( true );
            }

            if ( in_array( false, $run ) ){
                return;
            }

            // Get current author
            $followers = $this->get_author_followers_emails( $post->post_author );

            if ( empty( $followers ) )
            {
                //return;
            }

            $emails = array();

            foreach ( $followers as $follower )
            {
                if ( ! empty( $follower->user_email ) )
                {
                    $emails[] = $follower->user_email;
                } else if ( ! empty( $follower->subscriber_email ) )
                {
                    $emails[] = $follower->subscriber_email;
                }
            }

            if ( empty( $emails ) )
            {
                return;
            }

            $settings = array(
                    'subject_post'  => sprintf( __( 'New post published at %s on {date}', 'notify-users-e-mail' ), get_bloginfo( 'name' ) ),
                    'body_post'     => __( 'A new post {title} - {link_post} has been published on {date}.', 'notify-users-e-mail' ),
                );

            $subject_post = $this->apply_content_placeholders( $settings['subject_post'], $post );
            $body_post    = $this->apply_content_placeholders( $settings['body_post'], $post );

            $headers      = array(
                'Content-Type: text/html; charset=UTF-8',
                'Bcc: ' . implode( ',', $emails ),
            );

            // Send the emails.
            if ( apply_filters( 'notify_users_email_use_wp_mail', true ) ) {
                wp_mail( '', $subject_post, $body_post, $headers );
            } else {
                do_action( 'notify_users_email_custom_mail_engine', $emails, $subject_post, $body_post );
            }

            add_post_meta( $post->ID, '_notify_users_email_sended', true );
        }

        protected function apply_content_placeholders( $string, $post ) {
            $string = str_replace( '{title}', sanitize_text_field( $post->post_title ), $string );
            $string = str_replace( '{link_post}', esc_url( get_permalink( $post->ID ) ), $string );
            $string = str_replace( '{content_post}', apply_filters( 'the_content',get_post_field('post_content', $post->ID)), $string );
            //$string = str_replace( '{date}', $this->get_formated_date( $post->post_date ), $string );

            return $string;
        }
    }
}