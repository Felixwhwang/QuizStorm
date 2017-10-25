<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

// Load Layout Structure
$layout = exc_layout_structure( 'detail_page', 'exc_layout', array( 'page_title' => get_the_title() ) );

// Load Header
get_header( $layout['header'] ); ?>

<?php get_template_part( 'modules/page-banner' );?>

<main id="main" class="main <?php mf_container_class();?> ">
	
	<div class="row">

		<?php if ( in_array( $layout['structure'], array('left-sidebar', 'two-sidebars', 'left-two-sidebars') ) ) :?>
		<!-- Left Sidebar -->
		<aside class="col-lg-3 col-md-3 col-sm-4 side-bar">
			<?php dynamic_sidebar( $layout['left_sidebar'] );?>
		</aside>
		<?php endif;?>

		<?php if ( $layout['structure'] == 'left-two-sidebars' ): // show right sidebar before content if user has selected two sidebars on left ?>
			<aside class="col-lg-3 col-md-3 col-sm-4 side-bar">
				<?php dynamic_sidebar( $layout['right_sidebar'] );?>
			</aside>
		<?php endif;?>

		<div class="<?php echo esc_attr( exc_kv( $layout, 'content_width', 'col-md-6' ) );?> main-content">

			<?php
			
			if ( is_active_sidebar( 'exc-uploader-before-content-sidebar' ) ) {
				dynamic_sidebar( 'exc-uploader-before-content-sidebar' );
			}

			// Start the Loop.
			while ( have_posts() ) : the_post();

				the_content();
				
				if ( is_active_sidebar( 'exc-uploader-after-content-sidebar' ) ) {
					dynamic_sidebar( 'exc-uploader-after-content-sidebar' );
				}

				if ( exc_count_user_posts( get_the_author_meta('ID'), array( 'post', 'exc_image_post', 'exc_audio_post', 'exc_video_post' ), array( 'publish' ) ) > 1 )
				{
					exc_mf_author_posts();	
				} else 
				{
					exc_mf_related_posts();
				}

				// Comments
				if ( comments_open() || get_comments_number() )
				{
					comments_template();
				}

			endwhile;?>

		</div>
		
		<?php if ( $layout['structure'] == 'right-two-sidebars' ): // show left sidebar after content if user has selected two sidebars on right ?>
		<!-- Left Sidebar -->
		<aside class="col-lg-3 col-md-3 col-sm-4 side-bar">
			<?php dynamic_sidebar( $layout['left_sidebar'] );?>
		</aside>
		<?php endif;?>

		<?php if ( in_array( $layout['structure'], array('right-sidebar', 'two-sidebars', 'right-two-sidebars') ) ): ?>
			<!-- Right Sidebar -->
			<aside class="col-lg-3 col-md-3 col-sm-4 side-bar">
				<?php dynamic_sidebar( $layout['right_sidebar'] );?>
			</aside>
		<?php endif;?>
	</div>

</main>

<?php get_footer();?>