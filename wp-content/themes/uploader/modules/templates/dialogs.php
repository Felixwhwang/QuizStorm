<?php $header_settings = get_option('mf_header_settings');?>

<script type="text/html" id="tmpl-exc-fs-dialog">
	<div class="full-page-bg">
		<button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-times"></i></button>
		<div class="reg-container">
			<div class="reg-header">
				<a href="<?php echo esc_url( home_url('/') );?>"><img src="<?php echo esc_url( exc_kv( $header_settings, 'logo', get_template_directory_uri().'/images/logo.png' ) );?>" alt="<?php echo esc_attr( get_bloginfo('name') ); ?>"></a>
			</div>
			<div class="reg-form">
				{{{ body }}}
			</div>
		</div>
	</div>
</script>

<!-- MF default frame code -->
<script id="tmpl-exc-dialog" type="text/html">
	<div class="attachment-wrap">
		<button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-times"></i></button>
		<div class="attachment-modal">
			<div class="frame-body" id="frame-body">
				{{{ body }}}
			</div>
		</div>
	</div>
</script>

<script id="tmpl-exc-message" type="text/html">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">{{{ title }}}</h4>
			</div>
			<div class="modal-body"> {{{ body }}}</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'exc-uploader-theme');?></button>
				<button type="button" class="btn btn-primary"><?php _e('Save changes', 'exc-uploader-theme');?></button>
			</div>
		</div>
	</div>
 </script>

 <script id="tmpl-delete-post" type="text/html">
 	<div class="confirm-deletion">
 		<div class="confirm-dialog">

 			<div class="dialog-header"><i class="fa fa-exclamation-triangle"></i>Confirm</div>
 			<div class="dialog-body">
 				<?php esc_html_e('Do you really want to delete this entry?', 'exc-uploader-theme'); ?>
 			</div>
 			<div class="dialog-footer">
 				<a class="btn btn-success confirm-delete" href="#"><i class="fa fa-check"></i><?php esc_html_e('Yes', 'exc-uploader-theme'); ?></a>
 				<a class="btn btn-danger cancel-delete" href="#"><i class="fa fa-times"></i><?php esc_html_e('No', 'exc-uploader-theme'); ?></a>
 			</div>
 		</div>
	</div>
 </script>