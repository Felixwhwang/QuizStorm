<?php defined('ABSPATH') OR die('restricted access');

if ( is_front_page() || is_home() ) {
	return;
}

$layout = exc_get_layout_settings();?>

<div class="page-banner">
	<div class="container-fluid">
		<div class="row">

			<?php if ( ! empty( $layout['header_sidebar_status'] ) && 
						! empty( $layout['header_sidebar'] )
						&& is_active_sidebar( $layout['header_sidebar'] ) ) :?>
				<div class="col-sm-7">
					<h1><?php echo exc_kv( $layout, 'page_title' );?></h1>
					<?php exc_the_breadcrumbs();?>
				</div>
				
				<div class="col-sm-5 hidden-xs">
					<div class="banner-right">
						<?php dynamic_sidebar( $layout['header_sidebar'] );?>
					</div>
				</div>
			<?php else: ?>
				<div class="col-sm-12">
					<h1><?php echo exc_kv( $layout, 'page_title' );?></h1>
					<?php exc_the_breadcrumbs();?>
				</div>
			<?php endif;?>
		</div>
	</div>
</div>