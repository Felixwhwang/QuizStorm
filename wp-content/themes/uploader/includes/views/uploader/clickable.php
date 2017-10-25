<div class="form-group exc-form-field col-sm-6">
    <label for="contentType"><?php echo __( $label, 'exc-uploader-theme');?></label>
    <div class="content-button exc-clickable-wrapper" data-toggle="buttons">
        <?php echo __( $markup, 'exc-uploader-theme');?>
    </div>

    <?php if ( $help ) :?>
        <p><?php echo __( $help, 'exc-uploader-theme');?></p>
    <?php endif;?>
</div>