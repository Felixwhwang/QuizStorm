jQuery( document ).ready( function( $ )
{
    "use strict";

    var fileUpload = //function( container )
    {
        $field: {},
        settings: exc_upload_settings,

        _setup: function( el )
        {
            fileUpload.$field = {};

            fileUpload.$field.el = $( el );
            fileUpload.$field.el.siblings('.alert').remove();

            fileUpload.$field.ul = fileUpload.$field.el.siblings('ul.exc-preview-thumb:first');
            fileUpload.$field.uploaded = fileUpload.$field.ul.children('li').length;
            fileUpload.$field.fieldName = fileUpload.$field.ul.data('field_name');
            fileUpload.$field.limit = fileUpload.$field.ul.data('upload_limit');
            fileUpload.$field.allowedMimes = fileUpload.$field.ul.data('allowed_mimes');
            fileUpload.$field.i18n_limit = ( fileUpload.$field.limit == '1' ) ? fileUpload.settings.maxLimitSingular : fileUpload.settings.maxLimitPlural.replace(/%d/, fileUpload.$field.limit );

            return fileUpload.updateBtn();
        },

        init: function()
        {
            fileUpload.bindEvents();

            // Update button status
            $.each( $('.exc-media-uploader'), function(){
                fileUpload._setup( this );
            });
        },

        bindEvents: function()
        {
            $( document ).on( 'click', '.exc-media-uploader', fileUpload.uploadFile );
            $( 'ul.exc-preview-thumb' ).on( 'click', '.exc-file-delete', fileUpload.deleteFile );
        },

        uploadFile: function(e)
        {
            e.preventDefault();

            if ( fileUpload._setup( this ) )
            {
                if ( fileUpload.$field.el.hasClass( 'exc-media-uploader' ) )
                {
                    fileUpload.mediaUploader();
                }

            } else
            {
                fileUpload.$field.el.after( eXc.error( fileUpload.$field.i18n_limit ) );
            }
        },

        deleteFile: function(e)
        {
            e.preventDefault();

            var li = $( this ).parents('li:first'),
                btn = li.parent().siblings('button');

            li.remove();

            fileUpload._setup( btn );
        },

        updateBtn: function()
        {
            if ( false === fileUpload._isRemaingUploads() )
            {
                fileUpload.$field.el.addClass('disabled');
                return false;
            } else
            {
                fileUpload.$field.el.removeClass('disabled');
                return true;
            }
        },

        _countRemainingUploads: function()
        {
            return fileUpload.$field.limit - fileUpload.$field.uploaded;
        },

        _isRemaingUploads: function()
        {
            return ( isNaN( fileUpload.$field.limit ) || fileUpload.$field.limit === 0 || fileUpload.$field.uploaded < fileUpload.$field.limit ) ? true : false;
        },

        mediaUploader: function()
        {
            var opts = {
                            className: 'media-frame',
                            multiple: true,
                            title: fileUpload.settings.title
                        };

            // Restrict Mime Types
            if ( fileUpload.$field.allowedMimes )
            {
                opts.library = { type: fileUpload.$field.allowedMimes }
            }


            var frame = wp.media( opts ).open();

            frame.on('select', function()
            {
                var selection = frame.state().get('selection').toJSON(),
                    ids;

                selection = _.filter( selection, function( attachment )
                {
                    return fileUpload.$field.ul.children( 'li#item_' + attachment.id ).length == 0;
                });

                var post_id = $( '#post_ID' ).val() || 0,
                    ids = _.pluck( selection, 'id' );

                if ( fileUpload.$field.limit && ( ids.length + fileUpload.$field.uploaded ) > fileUpload.$field.limit )
                {
                    fileUpload.$field.el.after( eXc.error( fileUpload.$field.i18n_limit ) );
                    return;
                }

                if( ids.length > 0 )
                {
                    fileUpload.$field.ul.append(
                            _.template( $('#tmpl-exc-file-advanced').html(), eXc.tmpl )({
                                    field: fileUpload.$field.fieldName,
                                    attachments: selection
                                })
                        );
                }

                fileUpload._setup( fileUpload.$field.el );

                frame.off('select');
            });
        }
    }

    fileUpload.init();
});