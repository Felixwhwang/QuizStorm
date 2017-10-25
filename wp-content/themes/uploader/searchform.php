<?php defined( 'ABSPATH' ) or die( 'restricted access.' ); ?>

<form action="<?php echo esc_url( home_url( '/' ) ); ?>" class="searchform" id="searchform" method="get" role="search">
	<div class="search">
		<label for="s" class="screen-reader-text"><?php _e('Search for:', 'exc-uploader-theme');?></label>
		<input type="text" id="s" name="s" value="">
		<input type="submit" value="" id="searchsubmit">
	</div>
</form>