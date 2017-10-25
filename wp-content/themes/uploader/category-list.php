<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

// Load Layout Structure
//$layout = exc_layout_structure( 'browse_categories' );
$layout = exc_layout_structure( 'browse_categories', 'exc_layout', array( 'page_title' => __('Browse Categories', 'exc-uploader-theme') ) );

// Load Header
get_header( $layout['header'] );

get_template_part( 'modules/page-banner' );?>

<main id="main" class="main <?php mf_container_class();?>" role="main">

	<div class="row">
		
		<!-- Left Sidebar -->
		<?php if ( in_array( $layout['structure'], array('left-sidebar', 'two-sidebars', 'left-two-sidebars') ) ): ?>
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
			<ul class="col col-<?php echo esc_attr( $layout['columns'] ); ?>">

			<?php foreach( ( array ) get_categories() as $category ) :
					$category_layout = get_option( 'taxonomy_meta_' . $category->term_id );
					$category_link = esc_url( get_category_link( $category ) );
					$bg_color = ( ! empty( $category_layout['exc_layout_bg_color'] ) ) ? $category_layout['exc_layout_bg_color'] : '';?>

				<li>
					<a href="<?php echo esc_url( $category_link ); ?>" class="category-box" style="background-color: <?php echo esc_attr( $bg_color );?>">
						<?php if ( $icon_class = exc_kv( $category_layout, 'exc_layout_icon_class' ) ) :?>
							<div class="catg-thumb-icon">
								<span class="<?php echo esc_attr( $icon_class ) ;?>"></span>
							</div>
						<?php else :?>
							<div class="catg-thumb-img">
								<img src="<?php echo esc_url( exc_kv( $category_layout, 'exc_layout_image', get_template_directory_uri() . '/images/no-image.png' ) ); ?>" alt="<?php echo esc_attr( $category->name ); ?>" />
							</div>
						<?php endif; ?>

						<div class="catg-footer">
							<?php echo $category->name;?>
							<!-- <span class="no-of-posts"><?php echo exc_views_format( $category->count );?></span> -->
						</div>
					</a>
				</li>

			<?php endforeach;?>
			</ul>
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

<!-- Footer -->
<?php get_footer();?>