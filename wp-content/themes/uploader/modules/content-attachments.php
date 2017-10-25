<?php defined('ABSPATH') OR die('restricted access');?>

<?php
if ( $attachments ) :
	foreach ( $attachments as $attachment ): ?>
	
		<div class="entry-attachment">

			<?php if ( ! empty( $attachment['attachment_title'] ) ) :?>
				<h3><?php echo esc_html( $attachment['attachment_title'] );?></h3>
			<?php endif;?>

			<div class="entry-attachment-media">
				<?php echo $attachment['attachment_html'];?>
			</div>

			<?php if ( ! empty( $attachment['attachment_content'] ) ) :?>
				<div class="entry-attachment-content">

					<?php if ( ! empty( $attachment['attachment_content'] ) ) :?>
						<p><?php echo esc_html( $attachment['attachment_content'] );?></p>
					<?php endif;?>
				</div>
			<?php endif;?>

			<?php if ( ! empty( $attachment['attachment_source'] ) ) :?>
				<div class="entry-attachment-source">
					<span class="entry-source"><?php echo '<b>Source: </b>' . esc_html( $attachment['attachment_source'] );?></span>
				</div>
			<?php endif;?>
		</div>

<?php
	endforeach;
endif;?>