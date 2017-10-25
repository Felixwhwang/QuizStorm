<?php exc_kv( $args, 'before_widget', '', true );?>

	<div class="exc-radio" data-skin="exc-radio-meta">

		<?php if( $title = exc_kv( $instance, 'title' ) ): ?>
			<?php exc_kv( $args, 'before_title', '', true );?>
				<?php echo esc_html( $title );?></h3>
			<?php exc_kv($args, 'after_widget', '', true);?>
		<?php endif;?>

		<div class="exc-player">
			<audio controls="controls" preload="none" style="display: none;"></audio>
			<div class="exc-playlist-caption"></div>

			<div class="exc-radio-msgs"></div>
		</div>

		<?php if ( have_posts() ) :?>
			<ul class="station-list">
				<?php while( have_posts() ) : the_post();?>

				<li class="clearfix exc-radio-station">
					<div class="station-info">

						<?php
						if ( has_post_thumbnail() )
						{
							the_post_thumbnail('thumbnail');
						} else
						{
							echo '<img src="' . get_template_directory_uri() . '/images/no-image-180x180.png" />';
						}?>

						<div class="station-content">
							<?php the_title( '<h4>', '</h4>' );?>

							<p><?php the_terms( get_the_ID(), 'genre' );?></p>
						</div>
					</div>

					<?php
					if ( ! $poster = exc_kv( (array) wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium' ), 0 ) )
					{
						$poster = get_template_directory_uri() . '/images/no-image-180x180.png';
					}?>

					<a class="exc-play-station" href="#" data-station-id="<?php the_ID();?>" data-poster="<?php echo esc_url( $poster );?>"><i class="fa fa-play"></i></a>
				</li>
				<?php endwhile;?>
			</ul>
		<?php else :?>

		<?php endif;?>
	</div>
	
<?php exc_kv($args, 'after_widget', '', true);?>