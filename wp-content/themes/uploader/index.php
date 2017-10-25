<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

// Load Layout Structure
$layout = exc_layout_structure();

$layout['post_type'] = array( 'post', 'exc_video_post', 'exc_audio_post', 'exc_image_post' );

// Load Header
get_header( $layout['header'] );

if ( ! is_front_page() && is_singular() ) {

    $layout =& exc_get_layout_settings();

    $layout['page_title'] = get_the_title();

    get_template_part( 'modules/page-banner' );
}?>

<?php exc_load_template( 'modules/filtration', array( 'layout' => $layout ) );?>

<!-- main content --> 
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

        <div class="<?php echo esc_attr( exc_kv( $layout, 'content_width', 'col-md-6' ) );?> main-content">
            <?php
                echo exc_mf_media_query(
                    array(
                        'columns'       => exc_kv( $layout, 'columns', 3 ),
                        'list_columns'  => exc_kv( $layout, 'list_columns', 2 ),
                        'post_type'     => array( 'post', 'exc_video_post', 'exc_audio_post', 'exc_image_post' ),
                        'list_masonry'  => 1,
                        'autoload'      => $layout['autoload']
                    ) );?>
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