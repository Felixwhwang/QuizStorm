<?php defined( 'ABSPATH' ) or die( 'restricted access.' ); ?>

<!-- Filtration -->
<?php

$layout = ( isset( $layout ) ) ? $layout : exc_get_layout_settings();

if( has_shortcode( $GLOBALS['post']->post_content, 'mf_media_query' ) )
{
	get_template_part( 'modules/filtration' );
}?>

<!-- main content --> 
<main id="main" class="main <?php mf_container_class();?>" role="main">

	<div class="row">
		
		<!-- Left Sidebar -->
		<?php if ( $layout['structure'] == 'left-sidebar' ) :?>
		<aside class="col-lg-3 col-md-3 col-sm-4 side-bar">
			<?php dynamic_sidebar( $layout['left_sidebar'] );?>
		</aside>
		<?php endif;?>

		<div class="<?php echo ( $layout['structure'] == 'full-width' ) ? 'col-lg-12' : 'col-md-9 col-sm-8';?> main-content">
			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();
				the_content();
			endwhile;?>
		</div>
		
		<?php if ( $layout['structure'] == 'right-sidebar' ) :?>
		<!-- Right Sidebar -->
		<aside class="col-lg-3 col-md-3 col-sm-4 side-bar">
			<?php dynamic_sidebar( $layout['right_sidebar'] );?>
		</aside>
		<?php endif;?>
		
	</div>

</main>