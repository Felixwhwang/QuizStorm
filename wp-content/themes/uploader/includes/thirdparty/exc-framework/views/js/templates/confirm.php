<script type="text/html" id="tmpl-exc-confirm">
	<div class="modal-dialog">
		<div class="modal-content">

			<# if ( title ) { #>
			<div class=modal-header>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{{ title }}</h4>
			</div>
			<# } #>

			<div class="modal-body">{{ text }}</div>
			<div class="modal-footer">
				<# _.each( buttons, function (settings) { #>
					<button class="btn {{ settings.class }}" type="button" data-dismiss="modal">{{ settings.label }}</button>
				<# } ) #>

			</div>
		</div>
	</div>
</script>