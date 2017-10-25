<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

$_exc_uploader = exc_theme_instance(); // Get extracoding theme instance

$location = array( exc_kv( $user_meta, 'city' ), exc_kv( $user_meta, 'state' ), exc_kv( $user_meta, 'country' ) );?>

<!-- User Details -->
<div class="sidebar-block">
	<div class="user-detail" id="user_details">

		<!-- User Avatar -->
		<?php exc_get_avatar( 120, 'thumbnail', $user_info->ID );?>

		<?php
		if ( get_query_var('exc_custom_page') == 'dashboard-profile' ) :?>
			<form id="profile-image" action="<?php echo esc_url( $_SERVER['PHP_SELF'] );?>" method="post" enctype="multipart/form-data">
				<?php
				$i18n_edit_text		= _x('Change Photo', 'extracoding uploader frontend profile image edit string', 'exc-uploader-theme');
				$i18n_cancel_text	= _x('Cancel', 'extracoding uploader frontend profile image cancel string', 'exc-uploader-theme'); ?>

				<a href="#" class="btn btn-default btn-sm profile-upload-btn"><i class="fa fa-pencil-square-o"></i><span class="profile-btn-label"><?php echo $i18n_edit_text; ?></span></a>
				<input type="file" name="profile_img" id="profile-img" class="hide" data-edit-text="<?php echo esc_attr( $i18n_edit_text ); ?>" data-cancel-text="<?php echo esc_attr( $i18n_cancel_text ); ?>" data-edit-class="fa fa-pencil-square-o" data-cancel-class="fa fa-times" data-loading-class="fa fa-gear fa-spin" />
				<input type="hidden" name="action" value="exc_profile_image" />
				<input type="hidden" name="security" value="<?php echo wp_create_nonce( 'exc-profile-image' ); ?>" />
			</form>
		<?php
		endif;?>

		<input type="file" name="profile_image" id="profile_image" class="hide" />

		<h3><?php exc_get_user_name( $user_info );?></h3>

		<?php if( count( $location ) && isset( $user_meta['address'] ) ):?>

			<!-- User Location -->
			<p class="user-location"><?php echo esc_html( $user_meta['address'] );?></p>

		<?php endif;?>

		<!-- User Follow Button -->
		<?php if ( exc_get_follower( $user_info->ID, get_current_user_id() ) ) :?>
			<a href="#" class="btn btn-primary btn-sm exc-follow-author" data-id="<?php echo esc_attr( $user_info->ID );?>" role="button">
				<i class="fa fa-plus"></i>
				<span class="exc-followers-info-<?php echo esc_attr( $user_info->ID );?>"><?php _e('Unfollow', 'exc-uploader-theme');?></span>
			</a>
		<?php else :?>
			<a href="#" class="btn btn-primary btn-sm exc-follow-author" data-id="<?php echo esc_attr( $user_info->ID );?>" role="button">
				<i class="fa fa-plus"></i>
				<span class="exc-followers-info-<?php echo esc_attr( $user_info->ID );?>"><?php _e('Follow', 'exc-uploader-theme');?></span>
			</a>
			
		<?php endif;?>
		<div class="subscription-form exc-follower-form-<?php echo esc_attr( $user_info->ID );?>"></div>
		
	</div>
</div>

<!-- User Statistics -->
<div class="sidebar-block">
	<ul class="user-stats clearfix">
		<?php if ( isset( $user_meta['_exc_media_views'] ) ): ?>
		<li><span><?php echo $user_meta['_exc_media_views'];?></span><?php _e('Media Views', 'exc-uploader-theme');?></li>
		<?php endif;?>

		<li><span><?php echo exc_get_author_votes( $user_info->ID );?></span><?php _e('Appreciations', 'exc-uploader-theme');?></li>

		<li><span><?php echo exc_get_author_followers( $user_info->ID ); ?></span><?php _e('Followers', 'exc-uploader-theme');?></li>

		<li><span><?php echo exc_get_author_following( $user_info->ID )?></span><?php _e('Following', 'exc-uploader-theme');?></li>
	</ul>
</div>

<!-- User Social Network -->
<div class="sidebar-block">
	<div class="find-us">
		<h3><?php _e('find us', 'exc-uploader-theme');?></h3>
		<ul class="social-services">
			<?php if( ! empty($user_meta['facebook'] ) ) : ?>
				<li><a class="facebook" href="<?php echo esc_url( $user_meta['facebook'] );?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
			<?php endif;?>

			<?php if( ! empty($user_meta['twitter'] ) ) : ?>
				<li><a class="twitter" href="<?php echo esc_url( $user_meta['twitter'] );?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
			<?php endif;?>
			
			<?php if( ! empty( $user_meta['google-plus'] ) ) : ?>
				<li><a class="google-plus" href="<?php echo esc_url( $user_meta['google-plus'] );?>"><i class="fa fa-google-plus"></i></a></li>
			<?php endif;?>

			<?php if( ! empty( $user_meta['instagram'] ) ) : ?>
				<li><a class="instagram" href="<?php echo esc_url( $user_meta['instagram'] );?>"><i class="fa fa-instagram"></i></a></li>
			<?php endif;?>

			<?php if( ! empty( $user_meta['youtube'] ) ) : ?>
				<li><a class="youtube" href="<?php echo esc_url( $user_meta['youtube'] );?>"><i class="fa fa-youtube"></i></a></li>
			<?php endif;?>

			<?php if( ! empty( $user_meta['vimeo'] ) ) : ?>
				<li><a class="vimeo" href="<?php echo esc_url( $user_meta['vimeo'] );?>"><i class="fa fa-vimeo-square"></i></a></li>
			<?php endif;?>

			<?php if( ! empty( $user_meta['soundcloud'] ) ) : ?>
				<li><a class="soundcloud" href="<?php echo esc_url( $user_meta['soundcloud'] );?>"><i class="fa fa-soundcloud"></i></a></li>
			<?php endif;?>
			
			<?php if( ! empty( $user_meta['flickr'] ) ) : ?>
				<li><a class="flickr" href="<?php echo esc_url( $user_meta['flickr'] );?>"><i class="fa fa-flickr"></i></a></li>
			<?php endif;?>
		</ul>
	</div>
</div>

<!-- User About -->
<?php if ( ! empty( $user_meta['description'] ) ) : ?>
<div class="sidebar-block">
	<div class="about-us">
		<h3><?php _e('about us', 'exc-uploader-theme');?></h3>
		<p><?php echo $user_meta['description']; ?></p>
	</div>
</div>
<?php endif; ?>

<!-- User Contact Info -->
<?php if ( ! empty( $user_meta['public_email'] ) || ! empty( $user_meta['skype_id'] ) ) : ?>
<div class="sidebar-block">
	<div class="contact-us">
		<h3><?php _e('contact us', 'exc-uploader-theme');?></h3>

		<?php if ( ! empty( $user_meta['public_email'] ) ) : ?>
		<a class="mail-id" href="#"><i class="fa fa-envelope"></i><?php echo $user_meta['public_email']; ?></a>
		<?php endif ;?>
		
		<?php if ( ! empty( $user_meta['skype_id']) ) : ?>
		<a class="skype-id" href="#"><i class="fa fa-skype"></i><?php echo $user_meta['skype_id']; ?></a>
		<?php endif ;?>
	</div>
</div>
<?php endif;?>