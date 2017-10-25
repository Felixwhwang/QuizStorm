<?php defined('ABSPATH') OR die('Restricted Access');

if ( ! function_exists('exc_get_votes') )
{
	function exc_get_votes( $post_id = '' )
	{
		return exc_theme_instance()->author->get_votes( $post_id );
	}
}

if ( ! function_exists('exc_get_author_votes') )
{
	function exc_get_author_votes( $author_id )
	{
		if ( ! intval( $author_id ) )
		{
			return 0;
		}

		return exc_theme_instance()->author->get_author_votes( $author_id );
	}
}

if ( ! function_exists('exc_get_author_views') )
{
	function exc_get_author_views( $author_id )
	{
		if ( ! intval( $author_id ) )
		{
			return 0;
		}

		echo $author_id;exit;
	}
}

if ( ! function_exists('exc_get_author_followers') )
{
	function exc_get_author_followers( $author_id )
	{
		if ( ! intval( $author_id ) )
		{
			return 0;
		}

		return exc_theme_instance()->author->get_author_followers( $author_id );
	}
}

if ( ! function_exists( 'exc_get_author_following' ) )
{
	function exc_get_author_following( $author_id )
	{
		return exc_theme_instance()->author->get_author_following( $author_id );
	}
}

if ( ! function_exists('exc_is_voted') )
{
	function exc_is_voted( $post_id = '', $user_id = '' )
	{
		return exc_theme_instance()->author->is_voted( $post_id, $user_id );
	}
}

if ( ! function_exists('exc_get_follower') )
{
	function exc_get_follower( $author_id, $user_id )
	{
		$follower = exc_theme_instance()->author->get_follower( $author_id, $user_id );

		return ( is_object( $follower ) ) ? $follower->follower_id : $follower;
	}
}