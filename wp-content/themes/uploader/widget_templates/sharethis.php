<?php defined('ABSPATH') OR die('restricted access');

$widget_title = exc_kv( $instance, 'title' );?>

<!-- Social links -->
<div class="sidebar-block">
	<div class="find-us">
		
		<?php if ( $widget_title ) :?>
			<?php exc_kv( $args, 'before_title', '', true ); ?>
				<h3><?php echo esc_html( $widget_title ); ?></h3>
			<?php exc_kv( $args, 'after_widget', '', true ); ?>
		<?php endif;?>

		<span class='st_facebook_large' displayText='Facebook'></span>
		<span class='st_twitter_large' displayText='Tweet'></span>
		<span class='st_googleplus_large' displayText='Google +'></span>
		<span class='st_linkedin_large' displayText='LinkedIn'></span>
		<span class='st_pinterest_large' displayText='Pinterest'></span>
		<span class='st_digg_large' displayText='Digg'></span>
		<span class='st_email_large' displayText='Email'></span>
	</div>
</div>