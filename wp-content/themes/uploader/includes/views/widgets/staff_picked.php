<?php exc_kv($args, 'before_widget', '', true);?>

	<?php if( $title = exc_kv($instance, 'title') ): ?>
		<?php exc_kv($args, 'before_title', '', true);?>
			<?php echo esc_html( $title );?></h3>
		<?php exc_kv($args, 'after_widget', '', true);?>
	<?php endif;?>

	<?php if( $query->have_posts() ):?>
	<ul>
		<?php
		$i = 0;
		while ( $query->have_posts() ): $query->the_post(); ?>
			<li>
				<div class="staff-picked">
					<span><?php echo sprintf("%02s", (++$i));?></span>
					<div class="staff-picked-content">
						<h4><?php the_title('<a href="'. esc_url( get_permalink() ) .'">', '</a>');?></h4>
						<p><?php printf( _x('By %s', 'extracoding staff picked widget', 'exc-uploader-theme'), get_the_author() );?></p>
					</div>
				</div>
			</li>
		<?php endwhile;?>
	</ul>

	<?php else:?>

	<p>
		<div class="alert alert-info" role="alert">
			<?php echo _x('no posts found', 'extracoding staff picked widget', 'exc-uploader-theme');?>
		</div>
	</p>

	<?php endif;?>

<?php exc_kv($args, 'after_widget', '', true);?>