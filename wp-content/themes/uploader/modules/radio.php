<?php while( have_posts() ) : the_post();?>
<li class="clearfix exc-radio-station">
	
	<?php

	$meta = get_post_meta( get_the_ID(), 'mf_radio', true );
	$bg_color = exc_kv( ( array ) $meta, 'block-layout-tabs-style_settings-bg_color', '#ccc999');?>
	
	<div class="category-box">

		<?php
		if ( ! $poster = exc_kv( (array) wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium' ), 0 ) )
		{
			$poster = get_template_directory_uri() . '/images/no-image-180x180.png';
		}?>

		<div class="catg-thumb-img">
			<?php
			if ( has_post_thumbnail() )
			{
				the_post_thumbnail('thumbnail');
			} else
			{
				echo '<img src="' . esc_url( get_template_directory_uri() ) . '/images/no-image-180x180.png" />';
			}?>
		</div>
		<div class="catg-footer">
			<?php the_title();?>
		</div>
		<a class="exc-play-station btn-overlay" href="#" data-station-id="<?php the_ID();?>" data-poster="<?php echo esc_url( $poster );?>"><span class="btn-play-pause"><i class="fa fa-play"></i></span></a>
	</div>
</li>
<?php endwhile;?>