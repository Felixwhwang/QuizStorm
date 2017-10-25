<?php if( ! defined('ABSPATH')) exit('restricted access');

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
 
class exc_mail_class
{
	private $eXc;
	private $args = array();
	
	function __construct( &$eXc )
	{
		$this->eXc = $eXc;
	}
	
	public function configure_smtp( $phpmailer )
	{
		$phpmailer->isSMTP();
		$mail->SMTPDebug = 0;

		$phpmailer->Host = 'smtp.gmail.com';
		$phpmailer->SMTPAuth = TRUE;
		$phpmailer->Port = 587;		
		$phpmailer->SMTPSecure = 'tls';

		$phpmailer->Username = '';
		$phpmailer->Password = '';
	} 

	function send( $args = array() )
	{
		$this->args = $args;

		if( isset( $args['from'] ) )
		{
			add_filter( 'wp_mail_from', array( &$this, 'update_mail_from') );
			add_filter( 'wp_mail_from_name', array( &$this, 'update_mail_from_name') );
		}
		
		if( ( false === $status = wp_mail( exc_kv($args, 'to'), exc_kv($args, 'subject'), exc_kv($args, 'message') ) ) )
		{
			$this->eXc->validation->custom_error( 'mail_function', $GLOBALS['phpmailer']->ErrorInfo );
		}
		
		$this->args = array();

		return $status;
	}
	
	function update_mail_from_name( $original_email_from )
	{
		return exc_kv( $this->args, 'from_name', $this->get_from_name() );
	}
	
	function update_mail_from( $original_email_address )
	{
		return exc_kv( $this->args, 'from', $this->get_from_addr() );
	}
	
	function get_from_name( $from_name = '')
	{
		return $from_name ? $from_name : 'Wordpress';
	}
	
	function get_from_addr( $from = '' )
	{
		if ( $from ) return $from;
		
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
									'home_url'		=> network_home_url( '/' ),
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