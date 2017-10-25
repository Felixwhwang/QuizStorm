( function( window, $ ){

    var media_uploader = function( button )
    {
        this.button = button;
        this.buttonName = button.attr('name') || 'attachments';
        this.mimeTypes = button.attr('data-mime-types') || '';

        button.removeAttr( 'name');

        this.frame;
        this.eventStatus = false;

        this.attachmentIDs = this.button.val();

        this.buttonText = this.button.text();
        this.settings = exc_media_uploader_field;

        this.listContainer = this.button.siblings('.exc-attachment-media-list');

        if ( ! this.listContainer.length )
        {
            this.listContainer = $('<ul/>', {class: 'exc-attachment-media-list'}).appendTo( this.button.parent() );
        }

        this.template = $('#tmpl-exc-media-uploader-field').html();

        this.init();
    }

    $.extend( media_uploader.prototype, {

        init: function(){

            if ( this.attachmentIDs.length )
            {
                this.button.attr( 'disabled', true );
                this.button.text( this.settings.strings.loading );

                var self = this;

                $.post( ajaxurl, {action: 'query-attachments', query: { post__in: this.attachmentIDs.split( ',' ), orderby: 'post__in' } }, function(r){
                    if ( ! r.success )
                    {
                        // Field ERROR
                    } else
                    {
                        self.refreshAttachments( r.data );
                    }
                });

            } else
            {
                this.button.attr('disabled', false);
                this.bindEvents();
            }
        },

        toggleButton: function()
        {
            var limit = this.button.attr('data-upload-limit') || '',
                remaining = ( limit ) ? limit - this.listContainer.children().length : 1;

            if ( remaining > 0 )
            {
                this.button.attr('disabled', false).show();
            } else
            {
                this.button.attr('disabled', true).hide();
            }
        },

        loadMediaUploader: function(e) {

            e.preventDefault();

            var self = e.data.self,
                uploadLimit = self.button.attr('data-upload-limit') || 0,
                multiple = ( uploadLimit > 1 ) ? true : false,
                mimeTypes = self.button.attr('data-mime-types') || '',
                opts = {
                        title: "Select Media",
                        className: 'media-frame',
                        multiple: multiple,
                        library: { type: mimeTypes }
                    };

            if ( self.mimeTypes != mimeTypes ) {

                //opts[ 'cache' ] = false;
            }

            this.mimeTypes = mimeTypes;

            if ( self.frame ) {
                self.frame.open();
                return;
            }

            self.frame = wp.media( opts ).open();

            self.frame.on('select', function()
            {
                var selection = self.frame.state().get('selection').toJSON();

                self.refreshAttachments( selection );
                //self.frame.off('select');
            });
        },

        refreshAttachments: function( attachments ) {

            // LOAD li Template and display list based on mime type
            attachments = attachments || {};

            // Make sure the file is not in list
            var self = this;
            attachments = _.filter( attachments, function( attachment )
            {
                return self.listContainer.children( 'li#item_' + attachment.id ).length == 0;
            });

            if ( attachments.length )
            {
                var html = _.template( this.template, eXc.tmpl )({attachments: attachments, fieldName: this.buttonName});

                this.listContainer.append( html );

                this.button.text( this.buttonText );

                this.toggleButton();
                this.bindEvents();
            }
        },

        bindEvents: function() {

            if ( this.eventStatus )
            {
                return;
            }

            this.eventStatus = true;

            this.button.on( 'click', {self: this}, this.loadMediaUploader );
            this.listContainer.on('click', '.exc-attachment-delete', {self: this}, this.deleteAttachment);

            this.listContainer.sortable({ handle: ".exc-attachment-sort", axis: "y", opacity: 0.5 });

            var self = this;

            this.button.on( 'change', function(e){
                self.toggleButton();
            });
        },

        deleteAttachment: function(e) {
            e.preventDefault();

            var $this = $(this),
                self = e.data.self;

            $this.parents('.exc-attachment-item:first').remove();
            self.toggleButton();
        }
    });

    $( document ).ready( function(){

        var initiateUploader = function()
        {
            new media_uploader( $( this ) );
        }

        $('.exc-media-uploader').each( initiateUploader );

        $( document ).on('exc-dynamic-add_row', function( e, row ){
            row.find('.exc-media-uploader').each( initiateUploader );
        });
    });

})( window, jQuery );