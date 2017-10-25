<?php defined('ABSPATH') OR die('restricted access');?>

<!-- Empty Uploader -->
<script id="tmpl-exc-add-empty" type="text/html">
    <div class="drop-file-here">
        <?php printf( __('Drop attachments here or %s to upload', 'exc-uploader-theme'),
                '<a href="#" class="exc-media-upload-btn">' . _x('Click Here', 'extracoding uploader attachment string', 'exc-uploader-theme' ) . '</a>');?>
    </div>
</script>

<script id="tmpl-exc-content-type" type="text/html">

    <div class="select-content-type">
        <ul class="content-type-list">

            <# _.each( allowedPostTypes, function( postType ) { #>
                <li><a class="exc-post-type" href="#" data-post-type="{{{ postType.type }}}"><i class="fa {{{ postType.icon }}}"></i>{{{ postType.label }}}</a></li>
            <# }); #>
        </ul>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
</script>

<!-- Frame Uploader -->
<script id="tmpl-exc-mf-uploader-head" type="text/html">
    <div class="form-header-box upload-more-files">
        <span><?php _e('Want to upload more files?', 'exc-uploader-theme');?></span>
        <a href="#" class="exc-media-upload-btn"><?php _e('CLICK HERE', 'exc-uploader-theme');?></a>
        <span><?php _e('Or just drop more files', 'exc-uploader-theme');?></span>
    </div>
</script>

<!-- Uploader Frame Header -->
<script id="tmpl-exc-mf-uploader" type="text/html">
    <div class="exc-media-entries">
        <form method="post" action="" id="exc-mf-entry-form">
            <div class="exc-entries-form">
                <div class="row">

                    <?php $this->clear_query();?>
                    <?php $this->form->get_html( $config['_config'] );?>

                    <?php $post_id = ( isset( $post_id ) && is_numeric( $post_id ) ) ? $post_id : 0;?>
                    <?php $this->form->get_form_settings( $config['_name'], array( 'action' => 'exc_mf_uploader_entry', 'post_id'   => $post_id, 'secret_key' => wp_create_nonce( "exc-media-uploader-$post_id" ) ) );?>
                </div>
            </div>

            <div id="exc-media-list" class="exc-attachments-list"></div>

            <div class="actionbar-bottom clearfix">
                <span class="attach-file-wrap"><a class="exc-media-upload-btn btn btn-primary btn-attach-file" href="#">{{{ exc_plupload.i18n.buttons.attachFiles }}}</a></span>

                <a class="btn btn-success" href="#" id="exc-uploader-preview" target="_blank"><i class="fa fa-eye"></i>{{{ exc_plupload.i18n.buttons.preview }}}</a>
                <button class="btn btn-primary btn-save-changes" type="submit"><i class="fa fa-save"></i>{{{ exc_plupload.i18n.buttons.save }}}</button>
            </div>
        </form>
    </div>
</script>


<?php
$attachment_config = $this->load_config_file( 'uploader/attachments' );
$this->form->prepare_fields( $attachment_config );?>

<!-- Uploaded Items -->
<script id="tmpl-exc-uploaded-list" type="text/html">
    <# _.each( attachments, function( attachment ) { #>
        <# var featuredClass = ( attachment.featured ) ? ' featured' : '',
                postType = attachment.type.replace(/\/(.*)/, ''),
                isImage = ( postType === 'image' ) ? true : false; #>

        <div class="exc-attachment exc-attachment-saved-data {{{ featuredClass }}}" id="exc-uploader-file-{{{ attachment.id }}}" data-attachment-id="{{{ attachment.id }}}">
            <div class="attachment-desc">
                <span class="attachment-type">

                    <i class="fa fa-{{{ postType }}}"></i>

                </span>

                <span class="attachment-title">{{{ attachment.attachment_title }}}</span>
                <span class="attachment-size">{{{ attachment.file_date }}}</span>

                <div class="overlay-actions">
                    <div class="overlay-action-buttons">

                        <# if ( isImage ) { #>
                            <a href="#" class="set-as-featured {{{ featuredClass }}}" data-attachment-id="{{{ attachment.id }}}">
                                <# if ( featuredClass ) { #>
                                    {{{ exc_plupload.i18n.buttons.featured }}}
                                <# } else { #>
                                    {{{ exc_plupload.i18n.buttons.markFeatured }}}
                                <# } #>
                            </a>
                        <# } #>

                        <a href="#" class="exc-media-delete-btn" data-attachment-id="{{{ attachment.id }}}">{{{ exc_plupload.i18n.buttons.delete }}}</a>
                    </div>
                </div>
            </div>

            <div class="attachment-fields">

                <# if ( isImage ) { #>
                    <input type="hidden" class="featured-image" name="{{{ attachment.id }}}_featured_image" value="{{{ attachment.featured }}}" />
                <# } #>

                <?php $this->form->get_html( $attachment_config['_config'] );?>

            </div>
        </div>
    <# }); #>
</script>

<!-- Uploading Items -->
<script id="tmpl-exc-file-list" type="text/html">
    <# _.each( attachments, function( attachment ) { #>

        <# var isImage = ( attachment.type.substr(0, 5) === 'image' ) ? true : false; #>

        <div class="exc-attachment" id="{{{ attachment.id }}}">
            <div class="attachment-desc">
                <span class="attachment-type">
                    <i class="fa fa-image"></i>
                </span>

                <span class="attachment-title">{{{ attachment.prettyName }}}</span>
                <span class="attachment-size">{{{ plupload.formatSize( attachment.size ) }}}</span>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{{ attachment.percent }}}" aria-valuemin="0" aria-valuemax="100" style="width: {{{ attachment.percent }}}%;"></div>
                    <span>{{{ attachment.percent }}}%</span>
                </div>
                <div class="overlay-actions">
                    <div class="overlay-action-buttons">

                        <# if ( isImage ) { #>
                            <a href="#" class="set-as-featured" data-file-id="{{{ attachment.id }}}">{{{ exc_plupload.i18n.buttons.markFeatured }}}</a>
                        <# } #>

                        <a href="#" class="exc-media-delete-btn" data-file-id="{{{ attachment.id }}}">{{{ exc_plupload.i18n.buttons.delete }}}</a>
                    </div>
                </div>
            </div>
            <div class="attachment-fields">

                <# if ( isImage ) { #>
                    <input type="hidden" class="featured-image" name="{{{ attachment.id }}}_featured_image" value="{{{ attachment.featured }}}" />
                <# } #>

                <?php $this->form->get_html( $attachment_config['_config'] );?>
            </div>

        </div>
    <# }); #>
</script>

<script id="tmpl-exc-post-btn" type="text/html">
    <a href="{{{ link }}}" class="btn btn-sm btn-blue" target="_blank"><i class="fa fa-external-link"></i><?php _e('View', 'exc-uploader-theme');?></a>
</script>

<script id="tmpl-exc-attachment-list" type="text/html">
    <# _.each( attachments, function( attachment ) { #>
        <li id="{{{ attachment.id }}}">
            <a class="exc-media-delete-btn" href="#" data-file_id="{{{ attachment.id }}}"><i class="fa fa-times-circle"></i></a>
        </li>
    <# }); #>
</script>