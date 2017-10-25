<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				printf( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'twentyfifteen' ),
					number_format_i18n( get_comments_number() ), get_the_title() );
			?>
		</h2>

		<?php exc_comment_nav(); ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 56,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php exc_comment_nav(); ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'twentyfifteen' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>

</div><!-- .comments-area -->

<?php
return;
if ( post_password_required() ):?>
	<div class="alert alert-info"><?php _e( 'This post is password protected. Enter the password to view comments.', 'exc-uploader-theme' ); ?></div>
	<?php return;
endif;?>

<div class="comments">
	<?php if ( have_comments() ) :?>
		<div class="header">
			<h2>
			<?php
				printf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'exc-uploader-theme' ),
				number_format_i18n( get_comments_number() ), get_the_title() );?>

			</h2>
		</div>
	
		<ul class="media-list">
		<?php
			wp_list_comments( array(
				'style'      => 'ul',
				'short_ping' => true,
				'avatar_size'=> 60,
				'callback' => 'exc_comment_style',
			) );
		?>
		</ul>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>

			<?php $layout_settings = get_option( 'mf_layout' );?>

			<?php if ( exc_kv( $layout_settings, 'ajax_blog_pagi' ) == 'on' ): ?>
			<div class="load-more">
				<a class="btn btn-block btn-load-more" href="#" id="exc-load-comments" data-security="<?php echo wp_create_nonce("exc_comments");?>" data-pages="<?php echo esc_attr( get_comment_pages_count() );?>">
					<i class="icon-loading"></i><?php _e('load more', 'exc-uploader-theme');?>
				</a>
			</div>
			<?php else:?>
				<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
					<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'exc-uploader-theme' ); ?></h1>
					<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'exc-uploader-theme' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'exc-uploader-theme' ) ); ?></div>
				</nav>
			<?php endif;?>

		<?php endif;?>
	
	<?php endif;?>
	
	<?php if ( ! comments_open() ) :?>

		<p class="comments-disabled">
			<strong><i><?php _e( 'The comments are closed on this post.', 'exc-uploader-theme' ); ?></i></strong>
		</p>

	<?php endif;?>
	
	<?php comment_form();?>
	
</div>