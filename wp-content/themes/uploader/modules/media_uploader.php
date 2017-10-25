<?php defined('ABSPATH') OR die('restricted access');

$upload_action = exc_kv( (array) get_option("mf_uploader_settings"), "upload_action", "" );?>

<div class="exc-media-uploader">
	<div class="<?php mf_container_class();?> exc-media-dropzone">
		<div class="page-header">
			<h2><?php echo $heading_str;?></h2>
			<p><?php echo $about_str;?></p>
			<div class="uploader-box">
				<?php if ( $enqueue_script ) : ?>
					<?php if ( $upload_action == "on" ) :?>
						<a class="btn btn-lg btn-primary" id="exc-media-upload-primary-btn">
							<i class="fa fa-cloud-upload"></i><?php echo $btn_str;?>
						</a>
					<?php else :?>
						<span id="exc-media-upload-primary-btn" class="hide"></span>

						<a class="btn btn-lg btn-primary" data-action="exc-uploader">
							<i class="fa fa-cloud-upload"></i><?php echo $btn_str;?>
						</a>

					<?php endif;?>
				<?php else: ?>
					<a class="btn btn-lg btn-primary exc-media-upload-btn">
						<i class="fa fa-cloud-upload"></i><?php echo $btn_str;?>
					</a>
				<?php endif;?>
				<p>
					<span class="drop-files"><?php echo $dropfiles_str;?></span>
				</p>
			</div>
		</div>
	</div>
</div>