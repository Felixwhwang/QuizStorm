<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

$layout = exc_get_layout_settings();

// Filtration
if ( has_shortcode( $GLOBALS['post']->post_content, 'mf_media_query' ) || has_shortcode( $GLOBALS['post']->post_content, 'exc_media_query' ) )
{
    $pattern = get_shortcode_regex();

    if (   preg_match_all( '/'. $pattern .'/s', $GLOBALS['post']->post_content, $matches )
        && array_key_exists( 2, $matches )
        && ( in_array( 'mf_media_query', $matches[2] ) || in_array( 'exc_media_query', $matches[2] ) ) )
    {
        $attributes = (array) shortcode_parse_atts( $matches[3][0] );

        $layout['post_type'] = ( isset( $attributes['post_type'] ) ) ? explode(', ', $attributes['post_type']) : array('post', 'exc_audio_post', 'exc_video_post', 'exc_image_post');
    }

    if ( isset( $attributes['filtration'] ) && $attributes['filtration'] == 0 )
    {
        // Hide Filtration on user demand
    } else
    {
        $layout['active_view']  = ( isset( $layout['active_view'] ) ) ? $layout['active_view'] : exc_kv( $attributes, 'active_view' );
        $layout['list_columns'] = ( isset( $layout['list_columns'] ) ) ? $layout['list_columns'] : exc_kv( $attributes, 'list_columns' );

        exc_load_template( 'modules/filtration', array( 'layout' => $layout ) );
    }
}?>

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
            // Start the Loop.
            while ( have_posts() ) : the_post();
                the_content();
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