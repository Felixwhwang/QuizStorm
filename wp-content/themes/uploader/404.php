<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

// Load Layout Structure
$layout = exc_layout_structure();

// Load Header
get_header( $layout['header'] ); ?>

<!-- Page Container -->
<div class="container">

    <section class="error-page">
        <div class="error-page-inner">
            <figure><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/40-404-Page.png"/></figure>
            <div class="error-page-text">
                <?php printf(
                        __("Oops! It's looking like you may have taken a wrong turn please go back to our %s OR use our search form to explore relevent content.", 'exc-uploader-theme'),
                        '<a href="' . esc_url( home_url() ) . '">' . __('Home page!', 'exc-uploader-theme') . '</a>'
                    );
                ?>
            </div>
            <?php get_search_form(); ?>
        </div>
    </section>

</div>

<!-- Footer -->
<?php get_footer(); ?>