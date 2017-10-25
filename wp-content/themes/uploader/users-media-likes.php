<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

// User data
@extract( exc_get_user_data( get_query_var('author') ) );

if ( ! isset( $user_data ) || empty( $user_data ) )
{
    get_template_part( '404' );
    exit;
}

// Load Layout Structure
$layout = exc_layout_structure( 'users' );

// Load Header
get_header( $layout['header'] ); ?>

<main role="main">
    <div class="<?php mf_container_class();?> user-dashboard">
        <div class="row">

            <!-- Sidebar -->
            <div class="col-sm-3 sidebar-dashboard">
                <?php exc_load_template('sidebar-dashboard', array('user_id' => $user_data->ID, 'user_info' => $user_data, 'user_meta' => $user_meta ) ); ?>
            </div>

            <div class="dashboard col-sm-9">

                <!-- Dashboard Navigation -->
                <?php get_template_part('modules/dashboard-menu');?>

                <!-- Dashboard Content -->
                <div class="dashboard-content">
                    <?php

                    echo exc_mf_media_query(
                            array(
                                'autoload'      => true,
                                'author'        => $user_data->ID
                            )
                        );?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer();?>