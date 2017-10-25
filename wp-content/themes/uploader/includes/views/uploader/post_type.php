<div class="form-group exc-form-field col-sm-12">
	<label for="contentType"><?php echo esc_html__( $label, 'exc-uploader-theme' );?></label>
	<div class="content-button exc-clickable-wrapper exc-inline-buttons clickable-img" data-toggle="buttons">
		<?php echo __( $markup, 'exc-uploader-theme' );?>
	</div>

	<?php if ( $help ):?>
		<p><?php echo __( $help, 'exc-uploader-theme' );?></p>
	<?php endif;?>
</div>