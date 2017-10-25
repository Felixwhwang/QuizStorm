<?php defined('ABSPATH') or die('restricted access');

$module_settings = get_option('mf_notifications');?>

<?php

if ( exc_kv( $module_settings, 'status' ) == 'on'
		&& ( $notifications = exc_kv( $module_settings, 'top_notifications', array() ) ) ) :

	$active_post_id = 0;
	$has_sticky = false;
	$is_user_logged_in = is_user_logged_in();

	foreach ( $notifications as $k => $v )
	{
		if ( ! exc_kv( $v, 'status' ) )
		{
			unset( $notifications[ $k ] );
			continue;
		}

		if ( ! empty( $v['visible_to'] ) && $v['visible_to'] != 'all' )
		{
			if ( ( $is_user_logged_in && $v['visible_to'] != 'registered' ) 
					|| ( ! $is_user_logged_in && $v['visible_to'] == 'registered' ) )
			{
				// unset unwanted notifications
				unset( $notifications[ $k ] );
				continue;
			}
		}
		
		if ( false === $has_sticky && exc_kv( $v, 'sticky' ) )
		{
			$has_sticky = true;
			$active_post_id = $k;
		}
	}

	if ( empty( $notifications ) )
	{
		return;
	}
	
	if ( false === $has_sticky || empty( $notifications[ $active_post_id ] ) )
	{
		$active_post_id = ( exc_kv( $module_settings, 'random') == 'on' ) ? array_rand( $notifications ) : key( array_keys( $notifications ) );
	}?>

	<?php if ( ! empty( $notifications[ $active_post_id ] ) ) : ?>
	<div class="top-bar" id="top-bar">
		<div class="<?php mf_container_class();?>">
			<div class="topbar-inner">

				<!-- Close Button -->
				<?php if( exc_kv( $module_settings, 'close_btn' ) == 'on' ):?>
					<button type="button" class="close close-topbar" aria-hidden="true">&times;</button>
				<?php endif;?>

				<?php

				//Show notification content exc_kv last variable will automatically echo the output
				$notification = ( array ) exc_kv( $notifications, $active_post_id );

				exc_kv( $notification, 'content', '', true); ?>

			</div>
		</div>
	</div>
	<?php endif ;?>
<?php endif;?>