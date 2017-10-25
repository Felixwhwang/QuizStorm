<?php defined( 'ABSPATH' ) or die( 'restricted access.' ); ?>

<aside class="single-page-sidebar col-md-3 col-sm-4">

	<!-- Upload Block -->
	<?php
	if ( is_preview() && ( get_post_status( get_the_ID() ) == 'pending' ) ): ?>

	<div class="sidebar-block">
		<div class="about-us">
			<p><h4 ><i><?php _e('This post is pending for review and visible to author only.', 'exc-uploader-theme'); ?></i></h4></p>
		</div>
	</div>
	<?php endif;?>

</aside>