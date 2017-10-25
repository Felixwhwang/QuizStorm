<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

$is_masonry = exc_kv( $settings, 'list_masonry' );
$masonry_class = ( $is_masonry ) ? 'mason-item' : 'grid-item';
$current_user_id = get_current_user_id();
$dropdown = exc_kv( $settings, 'list_show_dropdown' );

while( have_posts() ) : the_post();

    $post_id = get_the_ID();?>

    <li <?php post_class( $masonry_class );?> data-media_id="<?php the_ID();?>">
        <div class="thumbnail">

        <?php if ( has_post_thumbnail() ): ?>
            <figure class="image">
                <?php
                $thumb = ( isset( $settings['list_thumb_size'] ) ) ? $settings['list_thumb_size'] : 'large';

                $attachment_id = get_post_thumbnail_id();
                $attachment = wp_get_attachment_image_src( $attachment_id, $thumb ); ?>

                <a href="<?php echo esc_url( get_permalink() ); ?>">
                    <img src="<?php echo esc_url( $attachment[0] ); ?>" alt="<?php echo esc_attr( the_title() ); ?>" />
                </a>

                <?php if ( $dropdown == 'top' ):?>
                <ul class="post-type post-type-with-date">

                    <li>

                        <?php $votes = exc_get_votes(); ?>

                        <?php if ( intval( $votes ) ) : ?>

                            <a href="#" data-id="<?php echo esc_attr( get_the_ID() );?>" class="exc-post-like <?php echo esc_attr( ( exc_is_voted() ) ? 'liked' : ''); ?>">
                                <i class="fa fa-thumbs-up"></i>
                            </a>

                            <div class="pop-over exc-votes-info-<?php echo esc_attr( get_the_ID() ); ?>">
                                <?php printf( _n( 'One Like', '%1$s Likes', $votes, 'exc-uploader-theme' ),
                                            number_format_i18n( $votes ) );?>
                            </div>

                        <?php else: ?>

                            <a href="#" data-id="<?php echo esc_attr( get_the_ID() );?>" class="exc-post-like">
                                <i class="fa fa-thumbs-up"></i>
                            </a>

                            <div class="pop-over exc-votes-info-<?php echo esc_attr( get_the_ID() );?>">
                                <?php _e('Be the first to like it', 'exc-uploader-theme'); ?>
                            </div>

                        <?php endif;?>
                    </li>

                    <li>
                        <a href="http://www.facebook.com/sharer.php?u=<?php echo esc_url( get_permalink() );?>"><i class="fa fa-facebook"></i></a>
                        <div class="pop-over"><?php _e('Share on facebook', 'exc-uploader-theme');?></div>
                    </li>

                    <li>
                        <a href="https://twitter.com/share?text=<?php echo esc_attr( urlencode( sprintf( __("Check out this amazing post on %s", 'exc-uploader-theme' ), get_bloginfo('name', 'display') ) ) ); ?>&amp;url=<?php echo esc_url( get_permalink() );?>"><i class="fa fa-twitter"></i></a>
                        <div class="pop-over"><?php _e('Share on Twitter', 'exc-uploader-theme');?></div>
                    </li>

                    <li>
                        <span> <?php echo get_the_date( _x( 'd', 'extracoding uploader post dropdown date', 'exc-uploader-theme' ) );?> </span>
                        <?php echo get_the_date( _x( 'M', 'extracoding uploader post dropdown date', 'exc-uploader-theme' ) );?>
                    </li>
                </ul>
                <?php endif;?>

                <span class="post-type-icon">
                    <?php
                    $post_type = get_post_type();
                    if ( $post_type == 'exc_audio_post' ) :?>
                        <i class="fa fa-music"></i>
                    <?php
                    elseif ( $post_type == 'exc_video_post' ) :?>
                        <i class="fa fa-video-camera"></i>
                    <?php
                    elseif ( $post_type == 'exc_image_post' ) :?>
                        <i class="fa fa-image"></i>
                    <?php
                    else :?>
                        <i class="fa fa-file-text-o"></i>
                    <?php
                    endif;?>
                </span>

                <?php if ( $current_user_id ) :?>
                <ul class="post-control-bar clearfix">

                    <?php if ( current_user_can( 'delete_post', $post_id ) || ( is_user_logged_in() && $GLOBALS['post']->post_author == $current_user_id ) ) :?>
                    <li class="exc-edit-post" data-id="<?php echo $post_id;?>">
                        <span class="post-control"><i class="fa fa-edit"></i></span>
                    </li>

                    <li class="exc-delete-post" data-id="<?php echo $post_id;?>">
                        <span class="post-control"><i class="fa fa-trash"></i></span>
                    </li>
                    <?php endif;?>
                </ul>
                <?php endif;?>
            </figure>
        <?php endif;?>

            <div class="caption">

                <?php if ( $dropdown == 'bottom') :?>
                <div class="blog-date">
                    <span><i class="icon-share-post"></i>
                        <ul class="social-post">
                            <li>
                                <?php eXc_theme_instance()->post
                                ->get_like_link('<a href="#" data-id="{post_id}" class="post-liked {class}"><i class="icon-thumbs-up"></i></a>',
                                            '<a href="javascript:void(0);" class="post-liked"><i class="icon-thumbs-up"></i></a>');?>
                            <li><a href="#"><i class="icon-plus"></i></a></li>
                        </ul>
                    </span>
                    <p><?php the_date( _x( 'd M', 'post dropdown date format', 'exc-uploader-theme' ) );?></p>
                </div>
                <?php endif;?>

                <?php the_title( '<h3><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );?>

                <?php if ( exc_kv( $settings, 'list_show_author' ) ): ?>

                <span class="post-by">
                <?php
                    printf( '%1$s <a href="%2$s" rel="author">%3$s</a>',
                            _x('By', 'post author name', 'exc-uploader-theme'),
                            esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                            exc_get_user_name( get_the_author_meta( 'ID' ), true )
                         );?>
                </span>

                <?php endif;?>

                <?php

                if ( $content = exc_kv( $settings, 'list_show_content' ) ): ?>

                <p>
                    <?php $content = ( $e = get_the_excerpt() ) ? $e : strip_shortcodes( get_the_content() ); ?>

                    <?php if( ! $limit = exc_kv( $settings, 'list_words_limit') ):?>
                        <?php echo $content; ?>
                    <?php else:?>
                        <?php echo wp_trim_words( $content, $limit ); //@TODO: remove shortcodes from content?>
                    <?php endif;?>

                    <?php
                        wp_link_pages( array(
                            'before' => '<ul class="pagination">',
                            'after' => '</ul>',
                            'link_before' => '<li>',
                            'link_after' => '</li>',
                        ) );?>
                </p>

                <?php endif;?>

            </div>

            <?php if ( exc_kv( $settings, 'list_show_tags' ) ):?>

                <?php if ( ! has_tag() && ! $is_masonry ) : ?>
                    <div class="caption-bottom tags">
                        <?php _e('no tags are available', 'exc-uploader-theme');?>
                    </div>
                <?php else: ?>
                    <?php the_tags( '<div class="caption-bottom tags">', ', ', '</div>' );?>
                <?php endif; ?>

            <?php endif;?>

            <?php if ( exc_kv( $settings, 'list_show_ctrls' ) && current_user_can( 'edit_posts' ) ): ?>
                <div class="caption-bottom edit-delete">

                    <?php //edit_post_link( __( 'Edit', 'exc-uploader-theme' ), '<span class="edit-link">', '</span>' );?>
                    <a href="#" class="edit-file btn btn-xs" data-sec="<?php echo esc_attr( wp_create_nonce('exc-media-edit') ); ?>">
                        <i class="icon-edit"></i><?php _e('Edit', 'exc-uploader-theme');?>
                    </a>

                    <a href="#" class="delete-file btn btn-xs" data-sec="<?php echo esc_attr( wp_create_nonce('exc-media-delete') ); ?>">
                        <i class="icon-delete"></i><?php _e('Delete', 'exc-uploader-theme');?>
                    </a>
                </div>
            <?php endif;?>

            <div class="thumbnail-footer">
                <?php if ( exc_kv( $settings, 'show_stats') ) :?>
                    <ul class="post-views">
                        <?php //@TODO: appreciations, Number of views ?>
                        <li>

                            <?php
                            $total_votes = exc_get_votes();
                            $votes_class = ( exc_is_voted() ) ? 'exc-post-like liked' : 'exc-post-like';?>

                            <?php if ( get_the_author_meta( 'ID' ) == $current_user_id ) :?>
                                <a href="javascript:void(0);" class="exc-post-like">
                                    <i class="fa fa-thumbs-up"></i><span class="exc-votes-count-<?php echo $post_id;?>"><?php echo $total_votes;?></span> <?php echo _e('likes') ;?>
                                </a>
                            <?php else :?>
                                <a href="javascript:void(0);" data-id="<?php echo $post_id;?>" class="<?php echo esc_attr( $votes_class );?>">
                                    <i class="fa fa-thumbs-up"></i><span class="exc-votes-count-<?php echo $post_id;?>"><?php echo $total_votes;?></span> <?php echo _e('likes') ;?>
                                </a>
                            <?php endif;?>
                        </li>

                        <?php
                        if ( shortcode_exists( 'easy-total-shares' ) ) :
                            $url = get_permalink();
                            $share_count = do_shortcode('[easy-total-shares url="'.$url.'" align="left" share_text="' . esc_attr__('shares', 'exc-uploader-theme') . '" networks="facebook,twitter,google"]'); ?>

                            <li><a href="javascript:void(0);"><i class="fa fa-share-alt"></i><?php echo $share_count;?></a></li>
                        <?php endif;?>

                        <li><a href="javascript:void(0);"><i class="fa fa-eye"></i><?php printf( __('%d views', 'exc-uploader-theme'), exc_get_views() );?></a></li>
                    </ul>
                <?php endif;?>
            </div>

        </div>
    </li>
<?php endwhile;?>