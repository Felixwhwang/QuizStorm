<?php defined('ABSPATH') OR die('restricted access');

$upload_action = exc_kv( (array) get_option("mf_uploader_settings"), "upload_action", "" );?>

<?php if ( $upload_action == "on" ) :?>
    <a class="btn btn-primary upload-button" href="#" id="exc-media-upload-primary-btn">
        <i class="fa fa-cloud-upload"></i> <?php _e('Upload', 'exc-uploader-theme');?>
    </a>
<?php else :?>

    <span id="exc-media-upload-primary-btn" class="hide"></span>

    <a class="btn btn-primary upload-button" href="#" data-action="exc-uploader">
        <i class="fa fa-cloud-upload"></i> <?php _e('Upload', 'exc-uploader-theme');?>
    </a>
<?php endif;?>