<?php exc_kv($args, 'before_widget', '', true);?>

	<?php if( $title = exc_kv($instance, 'title') ): ?>
		<?php exc_kv($args, 'before_title', '', true);?>
			<?php echo esc_html( $title );?></h3>
		<?php exc_kv($args, 'after_widget', '', true);?>
	<?php endif;?>

<?php if ( have_posts() ) :?>
<ul class="advanced-posts-list">
	<?php while( have_posts() ) : the_post(); ?>
		<li>
			<div class="advanced-post">
				<a href="<?php the_permalink();?>" class="post-thumb">
					<?php
					if ( has_post_thumbnail() )
					{
						the_post_thumbnail('thumbnail');
					} else
					{
						echo '<img src="' . esc_url( get_template_directory_uri() ) . '/images/no-image-180x180.png" alt="no-image" />';
					}?>
				</a>
				<div class="post-content">
					<?php the_title('<h4><a href="' . esc_url( get_the_permalink() ) . '">', '</a></h4>');?>

					<p>
						<?php printf( _x('By %s', 'extracoding advanced posts widget', 'exc-uploader-theme' ), get_the_author() );?>
						<span class="post-date">
							<?php printf( _x('on %s', 'extracoding advanced posts widget', 'exc-uploader-theme' ), get_the_date( _x( 'd F Y', 'extracoding advanced posts widget', 'exc-uploader-theme' ) ) );?>
						</span>
					</p>
				</div>
			</div>
		</li>
	<?php endwhile;?>
</ul>
<?php else:?>

	<p>
		<div class="alert alert-info" role="alert">
			<?php echo _x('no posts found', 'extracoding advanced posts widget', 'exc-uploader-theme');?>
		</div>
	</p>

	<?php endif;?>

<?php exc_kv($args, 'after_widget', '', true);?>
