<?php defined('ABSPATH') OR die('restricted access');

if ( ! is_single() ) {
	return;
}

$widget_title = exc_kv( $instance, 'title' );?>

<div class="sidebar-block">
	<div class="post-metadata">
		<?php if ( $widget_title ) :?>
			<?php exc_kv( $args, 'before_title', '', true ); ?>
				<h3><?php echo esc_html( $widget_title ); ?></h3>
			<?php exc_kv( $args, 'after_widget', '', true ); ?>
		<?php endif;?>

		<ul>
			<?php if ( ! is_attachment() ) :?>
			<li>
				<i class="fa fa-bars"></i>
				<div class="tags">
					<?php the_category(', ');?>
				</div>
			</li>

			<?php the_tags( '<li><i class="fa fa-tag"></i><div class="tags">', ', ', '</div></li>' );?>

			<?php endif;?>

			<?php if ( $license = get_post_meta( get_the_id(), 'license' ) ) :?>
				<li>
					<i class="fa fa-bars"></i>
					<?php echo esc_html( $license );?>
				</li>
			<?php endif; ?>
		</ul>
	</div>
</div>