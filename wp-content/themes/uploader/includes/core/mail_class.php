<?php if( ! defined('ABSPATH') ) exit('restricted access');
/**
 * Extracoding Framework
 *
 * An open source extracoding framework
 *
 * @package		Extracoding framework
 * @author		Extracoding team <info@extracoding.com>
 * @copyright	Copyright 2014 © Extracoding - All rights reserved
 * @license		http://extracoding.com/framework/license.html
 * @website		http://extracoding.com
 * @since		Version 1.0
 */
 
if ( ! class_exists('eXc_Mail_Class') )
{
	class eXc_Mail_Class
	{
		private $eXc;
		private $args = array();
		
		function __construct( &$eXc )
		{
			$this->eXc = $eXc;
			add_action( 'phpmailer_init', array( &$this, 'configure_smtp' ) );
		}
		
		public function configure_smtp( $phpmailer )
		{
			if ( isset( $this->args['contentType'] ) && 'html' == $this->args['contentType'] )
			{
				$phpmailer->IsHTML( TRUE );
			} else
			{
				$phpmailer->IsHTML( FALSE );
			}

			$settings = get_option('mf_mail_settings');

			if ( empty( $settings ) || ( isset( $settings['smtp_status'] ) && $settings['smtp_status'] != 'on' ) )
			{
				return;
			}

			$this->args['reply-to'] = ( empty( $this->args['reply-to'] ) ) ? $this->args['from'] : $this->args['reply-to'];
			$this->args['reply-to-name'] = ( empty( $this->args['reply-to-name'] ) ) ? $this->args['from_name'] : $this->args['reply-to-name'];

			// Add User Name and Email address in reply to as google replace from email address with smtp email address
			$phpmailer->AddReplyTo( $this->args['reply-to'], exc_kv( $this->args, 'reply-to-name', $this->args['from_name'] ) );

			// Enable SMTP
			$phpmailer->isSMTP();

			$phpmailer->SMTPDebug = ( ! empty( $settings['smtp_debug'] ) && intval( $settings['smtp_debug'] ) ) ? $settings['smtp_debug'] : 0;
			$phpmailer->SMTPAuth = ( isset( $settings['smtp_auth'] ) && $settings['smtp_auth'] == 'on' ) ? TRUE : FALSE;
			$phpmailer->SMTPSecure = ( isset( $settings['smtp_secure'] ) && in_array( $settings['smtp_secure'], array('tls', 'ssl') ) ) ? $settings['smtp_secure'] : '';

			$phpmailer->Host = ( isset( $settings['smtp_host'] ) ) ? $settings['smtp_host'] : 'localhost';
			$phpmailer->Username = ( isset( $settings['smtp_username'] ) ) ? $settings['smtp_username'] : '';
			$phpmailer->Password = ( isset( $settings['smtp_password'] ) ) ? $settings['smtp_password'] : '';;
			$phpmailer->Port = ( isset( $settings['smtp_port'] ) && intval( $settings['smtp_port'] ) ) ? $settings['smtp_port'] : 25;			
		} 

		function send( $args = array() )
		{
			$this->args = $args;

			//if ( ! empty( $args['from'] ) )
			//{
				add_filter( 'wp_mail_from', array( &$this, 'update_mail_from') );
				add_filter( 'wp_mail_from_name', array( &$this, 'update_mail_from_name') );
			//}

			if ( ( false === $status = wp_mail( exc_kv($args, 'to'), exc_kv($args, 'subject'), exc_kv($args, 'message') ) ) )
			{
				$this->eXc->load('core/validation_class')->custom_error( 'mail_function', $GLOBALS['phpmailer']->ErrorInfo );
			}
			
			$this->args = array();

			return $status;
		}
		
		function update_mail_from_name( $original_email_from )
		{
			return ( ! empty( $this->args['from_name'] ) ) ? $this->args['from_name'] : $original_email_from;
		}
		
		function update_mail_from( $original_email_address )
		{
			return ( ! empty( $this->args['from'] ) && is_email( $this->args['from'] ) ) ? $this->args['from'] : $original_email_address;
		}
		
		function get_from_name( $from_name = '')
		{
			return $from_name ? $from_name : 'Wordpress';
		}
		
		function get_from_addr( $from = '' )
		{
			if ( $from )
			{
				return $from;
			}
			
			$sitename = strtolower( $_SERVER['SERVER_NAME'] );
			
			if ( substr( $sitename, 0, 4 ) == 'www.' ) {
				$sitename = substr( $sitename, 4 );
			}

			return $from_email = 'wordpress@' . $sitename;
		}
		
		function parse_template( $html = '', $args = array() )
		{
			if ( ! $html )
			{
				return false;
			}
			
			$args = wp_parse_args( $args,
									array(
										'blog_name'		=> get_bloginfo( 'name' ),
										'home_url'		=> home_url( '/' ),
										'site_url'		=> site_url()
									)
							);
							
			preg_match_all('@{([a-z_]+)}@', $html, $matches);
				
			$values = array();
			
			if ( isset( $matches[1] ) )
			{
				foreach ( $matches[1] as $k => $v )
				{
					$values[] = ( isset( $args[ $v ] ) ) ? $args[ $v ] : '';
				}
				
				$html = str_replace( $matches[0], $values,  $html );
			}
			
			return $html;
		}
	}
}