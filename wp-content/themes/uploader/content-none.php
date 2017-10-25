<?php defined( 'ABSPATH' ) or die( 'restricted access.' ); ?>

<section class="blank-page">

	<div class="box-left">
		<?php _e('No', 'exc-uploader-theme'); ?>
	</div>

	<div class="box-right">

		<?php if ( in_array( get_post_type(), array('exc_video_post', 'exc_audio_post', 'exc_image_post') ) ) :?>
			<span class="text-big"><?php _e('media found', 'exc-uploader-theme'); ?></span>
			<p><?php _e('Sorry! but there is no media attached with this post.', 'exc-uploader-theme');?></p>
		<?php else: ?>
			<span class="text-big"><?php _e('content found', 'exc-uploader-theme'); ?></span>
			<p><?php _e('Sorry! No content match <br>Please try again with different keywords.', 'exc-uploader-theme');?></p>
		<?php endif;?>
		
	</div>
</section>