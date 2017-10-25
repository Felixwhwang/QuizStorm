<?php

$page = ( $p = get_query_var('exc_custom_page') ) ? $p : 'users';

$base = ( substr( $page, 0, 9 ) == 'dashboard' ) ? 'dashboard' : 'users';
$author_name = get_query_var('author_name');

$urls = array(
			'profile'		=> __('My Profile', 'exc-uploader-theme'),
			'media-files'	=> __('Media Files', 'exc-uploader-theme'),
			'followers'		=> __('Followers', 'exc-uploader-theme'),
			'following'		=> __('Following', 'exc-uploader-theme'),
			'likes'			=> __('Media Likes', 'exc-uploader-theme')
			);

$permalink_structure = get_option('permalink_structure'); ?>

<div class="dashboard-nav clearfix">

<?php
foreach ( $urls as $url => $label )
{
	if ( $url == 'profile' && $base != 'dashboard' )
	{
		continue;
	}

	$active_class = ( ( $page == $base && $url == 'media-files' ) || $page == ( $base . '-' . $url ) ) ? 'active' : '';

	if ( $permalink_structure )
	{
		$link = ( $base == 'dashboard' ) ? site_url( 'dashboard/' . $url ) : site_url( 'users/' . $author_name . '/' . $url );
	} else 
	{
		$link = ( $base == 'dashboard' ) ? site_url( '?exc_custom_page=dashboard-' . $url ) : site_url( '?exc_custom_page=users-' . $url . '&author_name=' . $author_name );
	}

	echo '<a href="' . $link . '" class="nav-item ' . $active_class . '">' . $label . '</a>';
}?>
</div>