<?php

$member_settings = get_option( 'mf_member_settings' );
$uploader_settings = get_option( 'mf_uploader_settings' );
$is_permalink = get_option( 'permalink_structure' );

//return if user login is disabled
if ( exc_kv( $member_settings, 'status' ) != 'on' )
{
    return;
}

$current_user = wp_get_current_user(); ?>

<div class="login" id="exc-user-ctrls">

    <?php if ( is_user_logged_in() ) :?>

    <div class="welcome-btn dropdown">

        <button class="btn dropdown-toggle" data-toggle="dropdown">
            <figure class="user-img">
                <?php echo exc_get_avatar( 30 ); ?>
            </figure>
            <span><?php _e( 'Welcome', 'exc-uploader-theme' );?></span>
            <p><?php exc_get_user_name( $current_user );?></p>
        </button>

        <ul aria-labelledby="user-dropdowv" class="dropdown-menu dropdown-menu-right">

            <?php if ( FALSE !== in_array( current_user_role(), exc_kv( $member_settings, 'admin_bar' ) ) ):?>
            <li>
                <a href="<?php echo esc_url( admin_url( ) );?>">
                <i class="fa fa-wordpress"></i><?php _e('WP Admin', 'exc-uploader-theme');?></a>
            </li>
            <?php endif;?>

            <li>
                <?php $url = ( $is_permalink ) ? 'dashboard' : '?exc_custom_page=dashboard';?>
                <a href="<?php echo esc_url( home_url( $url ) );?>">
                <i class="fa fa-dashboard"></i><?php _e( 'Dashboard', 'exc-uploader-theme' );?></a>
            </li>

            <li>
                <?php $url = ( $is_permalink ) ? 'dashboard/media-files' : '?exc_custom_page=dashboard-media-files';?>
                <a href="<?php echo esc_url( home_url( $url ) );?>">
                    <i class="fa fa-image"></i><?php _e( 'Media Files', 'exc-uploader-theme' );?>
                </a>
            </li>

            <li>
                <?php $url = ( $is_permalink ) ? 'dashboard/followers' : '?exc_custom_page=dashboard-followers';?>
                <a href="<?php echo esc_url( home_url( $url ) );?>">
                    <i class="fa fa-users"></i><?php printf( __( 'Followers (%d)', 'exc-uploader-theme' ), exc_get_author_followers( $current_user->ID ) );?>
                </a>
            </li>

            <li>
                <?php $url = ( $is_permalink ) ? 'dashboard/profile' : '?exc_custom_page=dashboard-profile';?>
                <a href="<?php echo esc_url( home_url( $url ) );?>">
                    <i class="fa fa-user"></i><?php _e( 'Profile Settings', 'exc-uploader-theme' );?>
                </a>
            </li>

            <li>
                <a href="#logout">
                    <i class="fa fa-sign-out"></i><?php _e( 'Logout', 'exc-uploader-theme' );?>
                </a>
            </li>

        </ul>
    </div>

    <?php else:?>

    <div class="user-button">
        <a href="#login" class="btn btn-default"><?php _e('Register / Login', 'exc-uploader-theme');?></a>
    </div>

    <?php endif;?>
</div>