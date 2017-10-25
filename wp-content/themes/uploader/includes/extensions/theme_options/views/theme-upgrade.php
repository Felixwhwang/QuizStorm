<?php defined('ABSPATH') OR die('restricted access');?>

<div class="exc-theme-update">
	<div class="tu-header">

		<?php if ( isset( $title ) ) :?>
			<h2><?php echo $title;?></h2>
		<?php endif;?>

		<!-- <a class="button button-primary" href="<?php echo esc_url( admin_url('themes.php?page=exc-theme-changelog') );?>">
			<?php _e('View Changelog', 'exc-uploader-theme');?>
		</a> -->

		<ul class="exc-index-list">
			<li class="new"><span class="index"><?php _e('N', 'exc-uploader-theme');?></span><?php esc_html_e( 'New', 'exc-uploader-theme' );?></li>
			<li class="fix"><span class="index"><?php _e('F', 'exc-uploader-theme');?></span><?php esc_html_e( 'Fix', 'exc-uploader-theme' );?></li>
			<li class="update"><span class="index"><?php _e('U', 'exc-uploader-theme');?></span><?php esc_html_e( 'Update', 'exc-uploader-theme' );?></li>
		</ul>

	</div>

	<?php if ( ! empty( $updates ) ) :?>
		<section class="exc-tasks" data-secret-key="<?php echo wp_create_nonce('exc-theme-upgrade');?>">
			<div class="card">
				<ul class="exc-task-list">

					<?php foreach ( $updates as $id => $update ) :?>

						<?php
						$type = ( ! empty( $update['type'] ) ) ? $update['type'] : 'new';
						$task = ( ! empty( $update['task'] ) ) ? $update['task'] : __('Unknown Update', 'exc-uploader-theme');

						if ( $update['status'] )
						{
							$type = $type . ' task-done';
						}?>

						<li class="<?php echo esc_attr( $type );?>" data-task="<?php echo esc_attr( $id );?>">
							
							<span class="index">
								<?php echo substr( $update['type'], 0, 1 );?>
								<span class="spinner"></span>
							</span>

							<?php if ( ! empty( $update['notice'] ) ) :?>
								<?php echo $update['notice'];?>
							<?php endif;?>

							<p class="task-detail"><?php echo $task;?></p>

							<?php if ( ! empty( $update['actions'] ) && ! $update['status'] ) : ?>
								<div class="action-buttons">
									<?php foreach( $update['actions'] as $action_key => $action ) :?>

										<?php if ( is_array( $action ) ): ?>
											<a href="<?php echo esc_url( $action['link'] );?>" class="btn btn-primary" data-action-id="<?php echo esc_attr( $action_key );?>"> <?php echo esc_html( exc_kv( $action, 'label' ) );?> </a>
										<?php else :?>
											<a href="#" class="btn btn-primary" data-action-id="<?php echo esc_attr( $action_key );?>"> <?php echo esc_html( $action );?> </a>
										<?php endif;?>
									<?php endforeach;?>
								</div>
							<?php endif;?>
						</li>

					<?php endforeach;?>
				</ul>
			</div>
		</section>
	<?php endif;?>
</div>