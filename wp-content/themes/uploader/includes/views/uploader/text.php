<?php defined('ABSPATH') OR die('restricted access');?>

<div class="form-group exc-form-field col-sm-6">
	<label for="<?php echo esc_attr( $name );?>"><?php echo __( $label, 'exc-uploader-theme' );?></label>
	<?php echo __( $markup, 'exc-uploader-theme' );?>
	
	<?php if ( $help ): ?>
	<p><?php echo __( $help, 'exc-uploader-theme' );?></p>
	<?php endif;?>
</div>