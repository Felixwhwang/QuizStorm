<?php exc_kv( $args, 'before_widget', '', true) ;?>

	<?php if ( $title = exc_kv($instance, 'title') ): ?>
		<?php exc_kv( $args, 'before_title', '', true );?>
			<?php echo esc_html( $title );?></h3>
		<?php exc_kv($args, 'after_widget', '', true);?>
	<?php endif;?>

	<?php if ( $user_query->total_users ): ?>
	<ul class="users-list">

		<?php foreach ( $user_query->results as $user ):
				$user_meta = array_map( function( $a ){ return $a[0]; }, get_user_meta( $user->ID ) );
				$display_name = exc_get_user_name( $user, true );
				$location = array( exc_kv( $user_meta, 'city' ), exc_kv( $user_meta, 'state' ), exc_kv( $user_meta, 'country' ) );?>
		<li>
			<div class="users-list-item clearfix">
				<div class="user-info">

					<a href="<?php echo esc_url( get_author_posts_url($user->ID) );?>" class="pull-left">
						<?php exc_get_avatar( 39, 'thumbnail', $user->ID );?>
					</a>

					<div class="user-content">
						<h4>
							<a href="<?php echo esc_url( get_author_posts_url($user->ID) );?>">
								<?php echo esc_html( $display_name );?>
							</a>
						</h4>

						<?php if( count( $location ) && isset( $user_meta['address'] ) ):?>
							<p><?php echo esc_html( $user_meta['address'] );?></p>
						<?php endif;?>

					</div>
				</div>
				<a data-toggle="collapse" class="status-open collapsed" href="#collapse-<?php echo $user->ID;?>"></a>
			</div>
			<div class="status collapse" id="collapse-<?php echo $user->ID;?>">

				<?php
				$background = '';

				if ( preg_match( '@src=["|\'](.*?)["|\']@i', exc_get_avatar( 120, 'medium', $user->ID, true ), $matches ) &&
						array_key_exists(1, $matches) )
				{
					$background = $matches[1];
				}?>

				<div class="about-user" style="background-image:url(<?php echo esc_url( $background );?>);">

					<?php exc_get_avatar( 120, 'thumbnail', $user->ID );?>

					<h4><?php echo esc_html( $display_name );?></h4>
					<?php if( count( $location ) && isset( $user_meta['address'] ) ):?>

						<!-- User Location -->
						<i><?php echo esc_html( $user_meta['address'] );?></i>

					<?php endif;?>


					<!-- User Follow Button -->
					<?php if ( exc_get_follower( $user->ID, get_current_user_id() ) ) :?>
						<a href="#" class="btn btn-primary btn-xs exc-follow-author" data-id="<?php echo $user->ID;?>" role="button">
							<span class="fa fa-plus"></span>
							<span class="exc-followers-info-<?php echo $user->ID;?>"><?php _e('Unfollow', 'exc-uploader-theme');?></span>
						</a>
					<?php else :?>
						<a href="#" class="btn btn-primary btn-xs exc-follow-author" data-id="<?php echo $user->ID;?>" role="button">
							<i class="fa fa-plus"></i>
							<span class="exc-followers-info-<?php echo $user->ID;?>"><?php _e('Follow', 'exc-uploader-theme');?></span>
						</a>

					<?php endif;?>

					<div class="subscription-form exc-follower-form-<?php echo $user->ID;?>"></div>
				</div>

				<ul class="user-status clearfix">
					<li><?php echo exc_get_author_following( $user->ID )?><span><?php _e('Following', 'exc-uploader-theme');?></span></li>
					<li><?php echo exc_get_author_followers( $user->ID ); ?><span><?php _e('Followers', 'exc-uploader-theme');?></span></li>
					<li><?php echo (int) exc_kv( $user_meta, '_exc_media_views' );?><span><?php _e('Views', 'exc-uploader-theme');?></span></li>
				</ul>

				<a class="status-close" href="#"><i class="fa fa-close"></i></a>
			</div>
		</li>
		<?php endforeach;?>

	</ul>

	<?php else:?>
		<p><?php _x('no user found', 'extracoding users widgets', 'exc-uploader-theme');?></p>
	<?php endif;?>

<?php exc_kv($args, 'after_widget', '', true);?>