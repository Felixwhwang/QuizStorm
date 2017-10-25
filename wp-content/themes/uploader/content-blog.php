<?php defined( 'ABSPATH' ) or die( 'restricted access.' );?>

	<div class="row">

		<div class="col-md-9 col-sm-8">

			<article id="post-<?php the_ID(); ?>" <?php post_class( "blog-detail" ); ?>>

				<?php ( is_single() ) ?
						the_title( '<h1>', '</h1>' ) :
						the_title( '<h1><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );?>

				<?php if ( has_post_thumbnail() ) :?>
					<p><?php the_post_thumbnail(  );?></p>
				<?php endif;?>

				<?php if ( is_search() ) :?>
					<?php the_excerpt(); ?>
				<?php else : ?>

				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'exc-uploader-theme' ) );

				wp_link_pages( array(
					'before' => '<ul class="pagination">',
					'after'	=> '</ul>',
					'link_before' => '<li>',
					'link_after' => '</li>',
				) );
				?>

				<?php endif;?>
			</article>
		</div>

		<div class="single-page-sidebar col-md-3 col-sm-4">
			<!-- user detail -->
			<div class="sidebar-block">
				<div class="user-detail">
					<figure class="user-thumb"> <?php echo exc_get_avatar( 64, 'thumbnail', get_the_author_meta('ID') ); ?> </figure>
					<span><?php _e('Uploaded by', 'exc-uploader-theme');?></span>

					<h3>
						<?php
						printf( '<a href="%1$s" rel="author">%2$s</a>',
								esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
								exc_get_user_name( get_the_author_meta( 'ID' ), true )
							 );?>
					</h3>

					<?php if ( exc_get_follower( get_the_author_meta('ID'), get_current_user_id() ) ) :?>
						<a href="#" class="btn btn-primary btn-xs exc-follow-author" data-id="<?php echo esc_attr( get_the_author_meta('ID') );?>" role="button">
							<i class="fa fa-plus"></i>
							<span class="exc-followers-info-<?php echo esc_attr( get_the_author_meta('ID') );?>"><?php _e('Unfollow', 'exc-uploader-theme');?></span>
						</a>
					<?php else :?>
						<a href="#" class="btn btn btn-primary btn-xs exc-follow-author" data-id="<?php echo esc_attr( get_the_author_meta('ID') );?>" role="button">
							<i class="fa fa-plus"></i>
							<span class="exc-followers-info-<?php echo esc_attr( get_the_author_meta('ID') );?>"><?php _e('Follow', 'exc-uploader-theme');?></span>
						</a>
						
					<?php endif;?>

					<div class="subscription-form exc-follower-form-<?php echo esc_attr( get_the_author_meta('ID') );?>"></div>
				</div>
				<!-- Entry stats -->
				<ul class="post-stats clearfix">
					<li>
						<i class="fa fa-thumbs-up"></i><span class="exc-votes-count-<?php the_ID();?>"><?php echo exc_get_votes();?></span>
					</li>

					<li>
						<i class="fa fa-eye"></i><?php echo exc_get_views();?>
					</li>

					<li>
						<i class="fa fa-comments"></i>
						<?php echo get_comments_number();?>
					</li>
				</ul>
			</div>
			<!-- Find us -->
			<div class="sidebar-block">
				<div class="find-us">
					<h3><?php _e('Share This Media', 'exc-uploader-theme'); ?></h3>

					<span class='st_facebook_large' displayText='Facebook'></span>
					<span class='st_twitter_large' displayText='Tweet'></span>
					<span class='st_googleplus_large' displayText='Google +'></span>
					<span class='st_linkedin_large' displayText='LinkedIn'></span>
					<span class='st_pinterest_large' displayText='Pinterest'></span>
					<span class='st_email_large' displayText='Email'></span>
				</div>
			</div>
			<!-- post meta data -->
			<div class="sidebar-block">
				<div class="post-metadata">
					<ul>

						<?php if ( is_singular( 'post' ) ) :?>
						<li>
							<i class="fa fa-bars"></i>
							<div class="tags">
								<?php the_category(', ');?>
							</div>
						</li>

						<?php the_tags( '<li><i class="fa fa-tag"></i><div class="tags">', ', ', '</div></li>' );?>

						<?php endif;?>

						<li>
							<i class="fa fa-comments"></i>
							<a class="commnts" href="#">
								<?php printf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'exc-uploader-theme' ),
										number_format_i18n( get_comments_number() ), get_the_title() );?>
							</a>
						</li>

						<li>
							<i class="fa fa-eye"></i>
							<?php printf( _x('%d views', 'extracoding uploader post views', 'exc-uploader-theme' ), exc_get_views() );?>
							
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>