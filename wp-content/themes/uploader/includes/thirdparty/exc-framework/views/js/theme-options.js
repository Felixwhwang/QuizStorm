jQuery( document ).ready( function($) {

    // @TODO: update code with new standards
    var xhr = '';

    $('.exc-form input[type="text"]').keypress( function(e) {

        if ( e.which == 13 )
        {
            e.preventDefault();
            $( this ).parents('form').submit();
        }
    });

    $( 'body' ).on( 'submit', '.exc-form', function(e) {

        e.preventDefault();

        if ( xhr && xhr.readyState !== 4 )
        {
            return;
        }

        var form = $(this),
            submitBtn = form.find('.exc-submit-btn');
            icon = submitBtn.find('i');
            data = form.serializeArray();

            icon.attr('class', 'fa fa-cog fa-spin');

            form.find('div.alert').remove();

            form.trigger('exc-form-submit', [data]);

        xhr = $.post(ajaxurl, data, function(r) {

            if (r.success)
            {
                form.prepend('<div class="alert alert-success">'+r.data+'</div>');
                eXc.focus(form);

            }else
            {
                if ( 'string' !== typeof(r.data) ) {

                    var firstField = '';
                    $.each(r.data, function(k, v){

                        var field = $(':input[name="' + k + '"]');

                        firstField = firstField || field;

                        if( field.length )
                        {
                            var error = '<div role="alert" class="alert alert-danger">' + v + '</div>';

                            if ( field.parent('.input-group').length )
                            {
                                field.parent().after( error );
                            } else
                            {
                                field.after( error );
                            }

                        }else
                        {
                            form.prepend('<div class="alert alert-danger">' + v + '</div>');
                        }
                    });

                    if (firstField) {
                        eXc.focus(firstField);
                    }

                } else {
                    form.prepend('<div class="alert alert-danger">' + r.data + '</div>');
                    eXc.focus(form);
                }
            }

            icon.attr('class', 'fa fa-save');

        }, 'json');
    });
});