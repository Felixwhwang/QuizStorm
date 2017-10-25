<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

$is_masonry = ( isset( $masonry ) ) ? $masonry : false;
$is_permalink = get_option('permalink_structure');

$base_url = site_url( 'users/' . $user->user_nicename . '/' );?>

<li <?php echo ( $is_masonry ) ? 'class="mason-item"' : 'class="grid-item"'; ?>>
    <div class="exc-user">
        <div class="exc-user-body">
            <div class="exc-user-profile-pic">
                <?php exc_get_avatar( 110, 'thumbnail', $user->ID );?>
            </div>
            <div class="exc-user-info">
                <h3>
                    <a href="<?php echo esc_url( get_author_posts_url( $user->ID ) ); ?>">
                        <?php exc_get_user_name( $user );?>
                    </a>
                </h3>
                <?php if ( $show_address ) : ?>
                <span class="user-address">
                    <?php $address = get_the_author_meta( 'address', $user->ID ); ?>

                    <?php if ( ! empty( $address ) ) :?>
                        <?php echo esc_html( $address ); ?>
                    <?php elseif( $is_masonry ) :?>
                        <?php _e('Address not available', 'exc-uploader-theme'); ?>
                    <?php endif;?>
                </span>
                <?php endif;?>

                <?php if ( exc_get_follower( $user->ID, get_current_user_id() ) ) :?>
                    <a href="#" class="btn btn-primary btn-xs exc-follow-author" data-id="<?php echo esc_attr( $user->ID );?>" role="button">
                        <i class="fa fa-plus"></i><span class="exc-followers-info-<?php echo esc_attr( $user->ID );?>"><?php _e('Unfollow', 'exc-uploader-theme');?></span>
                    </a>
                <?php else :?>
                    <a href="#" class="btn btn-xs btn-primary exc-follow-author" data-id="<?php echo esc_attr( $user->ID );?>" role="button">
                        <i class="fa fa-plus"></i><span class="exc-followers-info-<?php echo esc_attr( $user->ID );?>"><?php _e('Follow', 'exc-uploader-theme');?></span>
                    </a>
                <?php endif;?>
            </div>
            <?php if ( $show_about && ( $about = get_the_author_meta('description', $user->ID ) ) ) :?>
                <div class="exc-user-about">
                    <p><?php echo esc_html( wp_trim_words( $about, $words_limit ) );?></p>
                </div>
            <?php endif;?>
            <div class="subscription-form exc-follower-form-<?php echo esc_attr( $user->ID );?>"></div>
        </div>
        <ul class="exc-user-statistics">
            <li><a class="this-user-followers" href="<?php echo esc_url( $base_url . 'followers' );?>"><span class="count"><?php echo exc_get_author_followers( $user->ID ); ?></span><?php esc_html_e('Followers', 'exc-uploader-theme');?></a></li>
            <li><a class="this-user-posts" href="<?php echo esc_url( $base_url . 'media-files' );?>"><span class="count"><?php echo exc_count_user_posts( $user->ID, array( 'post', 'exc_image_post', 'exc_audio_post', 'exc_video_post' ), array( 'publish' ) );?></span><?php esc_html_e('Posts', 'exc-uploader-theme');?></a></li>
            <li><a class="this-user-followings" href="<?php echo esc_url( $base_url . 'following' );?>"><span class="count"><?php echo exc_get_author_following( $user->ID )?></span><?php esc_html_e('Following', 'exc-uploader-theme');?></a></li>
        </ul>
    </div>
</li>