<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

// Load Layout Structure
$layout = exc_layout_structure('', 'exc_layout', array( 'page_title' => __('contact us', 'exc-uploader-theme') ) );

// Load Header
get_header( $layout['header'] ); 

get_template_part( 'modules/page-banner' );?>

<main id="main" class="main <?php mf_container_class();?>" role="main">

	<div class="row">
		
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

		<div class="<?php echo esc_attr( mf_layout_content_width( $layout['structure'] ) );?> main-content">
			<?php exc_mf_contact_form();?>
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