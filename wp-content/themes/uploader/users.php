<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

// Load Layout Structure
$layout = exc_layout_structure( 'users' );

// Load Header
get_header( $layout['header'] );?>

<div class="page-banner">
    <div class="container-fluid">
        <div class="row">

            <?php if ( exc_kv( $layout, 'show_filtration' ) == 'on' ) :?>
                <div class="col-sm-7">
                    <h1><?php _e('Browse Users', 'exc-uploader-theme'); ?></h1>
                    <?php exc_the_breadcrumbs();?>
                </div>

                <div class="col-sm-5 hidden-xs">
                    <div class="banner-right">
                        <?php get_template_part( 'modules/user_filtration' );?>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-sm-12">
                    <h1><?php _e('Browse Users', 'exc-uploader-theme'); ?></h1>
                    <?php exc_the_breadcrumbs();?>
                </div>
            <?php endif;?>
        </div>
    </div>
</div>

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

            exc_mf_user_query(
                    array(
                        'columns'       => exc_kv( $layout, 'columns' ),
                        'post_limit'    => 3,
                        'hide_empty'    => false,
                        'words_limit'   => 20,
                        'template'      => 'users-list-view'
                    )
                );
            ?>

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