<?php defined( 'ABSPATH' ) OR die('restricted access');?>

<script type="text/html" id="tmpl-exc-radio-meta">
	<h4 class="exc-playlist-itme-title"><marquee scrollamount="2">{{ data.title }}</marquee></h4>
		<# if ( data.meta.album ) { #><span class="exc-playlist-item-album">{{ data.meta.album }}</span><# } #>
		<# if ( data.meta.artist ) { #><span class="exc-playlist-item-artist">{{ data.meta.artist }}</span><# } #>
</script>

<script type="text/html" id="tmpl-exc-radio-loader">
	<div class="circle">
		<div class="c1"></div>
		<div class="c2"></div>
		<div class="c3"></div>
	</div>
</script>