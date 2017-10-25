<?php defined('ABSPATH') OR die('restricted access');

// @TODO: add support in backend
if ( is_admin() )
{
	// Load extended theme option
	exc_load_extension( 'theme_options/theme_options', $this );

} else 
{
	if ( exc_is_client_side() )
	{
		// Load Media Query Extension
		exc_load_extension( 'media_query/media_query', $this );
	}
}