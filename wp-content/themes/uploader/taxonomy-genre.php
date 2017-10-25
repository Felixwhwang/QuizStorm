<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

exc_theme_instance()->radio->enqueue_files( true );
exc_theme_instance()->radio->load_template();

// Load Layout Structure
$layout = exc_layout_structure( 'genre', 'exc_layout', array( 'page_title' => get_the_archive_title() ) );

get_header( $layout['header'] );

get_template_part( 'modules/page-banner' );?>

<main id="main" class="main <?php mf_container_class();?>" role="main">

    <div class="row">

        <!-- Left Sidebar -->
        <?php if ( in_array( $layout['structure'], array('left-sidebar', 'two-sidebars', 'left-two-sidebars') ) ): ?>
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
            <div class="category-list">

                <div class="exc-radio" data-skin="exc-radio-shortcode">

                    <div class="exc-player">
                        <figure class="exc-radio-poster"></figure>
                        <div class="exc-playlist-caption"></div>
                        <div class="exc-radio-msgs"></div>
                        <audio controls="controls" preload="none" style="display: none;"></audio>
                    </div>

                    <?php
                        echo exc_mf_media_query(
                            array(
                                'post_type' => 'exc_radio_post',
                                'template'  => 'modules/radio',
                                'columns'   => $layout['columns']
                            )
                        );
                    ?>
                </div>
            </div>
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