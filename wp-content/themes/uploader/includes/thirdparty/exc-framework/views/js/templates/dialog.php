<script type="text/html" id="tmpl-exc-dialog">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <# if ( 'undefined' !== typeof title ){ #>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php _e('Close', 'exc-framework');?></span></button>
                <h4 class="modal-title">{{ title }}</h4>
            </div>
            <# } #>

            <div class="modal-body">
                {{{ body }}}
            </div>

            <# if ( 'undefined' !== typeof options ){ #>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php _e('Close', 'exc-framework');?></button>
                <# _.each( options, function(k, v) { #>
                <button type="button" class="btn btn-info exc-dialog-{{{ v }}}" data-key="{{{ v }}}">{{{ k }}}</button>
                <# } ) #>

            </div>
            <# } #>
        </div>
    </div>
</script>