<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

/* Template Name: Protected Page */

if ( ! is_user_logged_in() ) {
	
	$theme_instance =& exc_theme_instance();
	
	$theme_instance->load('core/session_class')
		->set_flashdata( 'session_message', array( 'type' => 'error', 'message' => sprintf( __( 'You must login to access "%s" page.', 'exc-uploader-theme' ), get_the_title() ), 'ttl' => 0 ) );

	// Automatically delete last request uri
	$redirect_to = wp_validate_redirect( get_permalink(), site_url() );
	$theme_instance->session->set_data( 'redirect_to', $redirect_to );

	// Redirecting page so keep session message
	$link = site_url( '#login' );
	wp_safe_redirect( $link );
	exit;
}

get_template_part('page');