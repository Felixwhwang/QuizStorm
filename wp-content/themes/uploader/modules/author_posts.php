<div class="related-posts">
	<div class="related-heading clearfix">
		<h2><?php _e('More from this author', 'exc-uploader-theme'); ?></h2>
		<a class="btn btn-primary btn-sm" href="<?php echo esc_url( get_author_posts_url( exc_kv( $settings, 'author' ) ) );?>"><?php _e( 'View all posts', 'exc-uploader-theme' );?></a>
	</div>
	<ul class="col col-<?php echo esc_attr( exc_kv( $settings, 'columns', 4 ) );?> media-posts">

		<?php while( have_posts() ) : the_post();?>
		<li>
			<a class="related-entry" href="<?php echo esc_url( get_permalink() );?>" rel="bookmark">
				<figure>
					<?php
					if ( has_post_thumbnail() ) :
						$attachment_id = get_post_thumbnail_id();
						$attachment = wp_get_attachment_image_src( $attachment_id, 'grid-thumb');?>
						
						<img src="<?php echo esc_url( $attachment[0] );?>" />
					<?php else:?>
						<img alt="no-image" src="<?php echo esc_url( get_template_directory_uri() ) . '/images/no-image-278x180.png';?>">
					<?php endif;?>

					<figcaption>
						<?php the_title();?>
					</figcaption>

				</figure>
			</a>
		</li>
		<?php endwhile;?>

	</ul>
</div>