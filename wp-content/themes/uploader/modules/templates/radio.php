<script type="text/html" id="tmpl-exc-radio-shortcode">
	<h1 class="exc-playlist-item-title">{{ data.title }}</h1>
	<# if ( data.meta.album ) { #><span class="exc-playlist-item-album">{{ data.meta.album }}</span><# } #>
	<# if ( data.meta.artist ) { #><span class="exc-playlist-item-artist">{{ data.meta.artist }}</span><# } #>
	<# if ( data.meta.genre ) { #><span class="exc-playlist-item-genre">{{ data.meta.genre }}</span><# } #>
</script>