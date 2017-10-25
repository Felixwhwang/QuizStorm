<?php defined('ABSPATH') OR die('restricted access');
	
$author_id = ( ! empty( $instance['author'] ) ) ? $instance['author'] : 0;

if ( ! intval( $author_id ) ) {
	return;
}

$widget_title = exc_kv( $instance, 'title' );

if ( $description = get_the_author_meta( 'description', $author_id ) ) :?>
	<div class="sidebar-block">
		<div class="about-us">

			<?php if ( $widget_title ) :?>
				<?php exc_kv( $args, 'before_title', '', true ); ?>
					<h3><?php echo esc_html( $widget_title ); ?></h3>
				<?php exc_kv( $args, 'after_widget', '', true ); ?>
			<?php endif;?>

			<p><?php echo esc_html( $description ); ?></p>
		</div>
	</div>
<?php endif;?>