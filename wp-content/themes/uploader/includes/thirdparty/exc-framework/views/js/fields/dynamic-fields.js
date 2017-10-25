(function(window, $, undefined){

    "use strict";

    window.exc_dynamic_rows = function( container )
    {
        this.container = $( container );

        //this.name = this.container.data('name');
        this.container_id = this.container.attr('id');
        this.default_title = this.container.data('default-title');
        this.document = $(document);

        this.sectionContainer = this.container.parents('.exc-dynamic-row-container:first');
        this.toolbar = this.sectionContainer.find('.hide-on-load[data-toolbar="' + this.container_id + '"]');

        // Keep the toolbar hidden on page load
        //this.toolbar.hide();

        // this.section = this.container.parents('.exc-dynamic-section:first');

        // if ( ! this.section.length ) {
        //     this.section = this.container;
        // }

        //this.org_html = this.container[0].outerHTML;
        this.org_html = this.container.html();

        this.header = this.container.find('.exc-fieldset-header').eq(0);
        //this.add_btn = this.container.find('.exc-add-btn').eq(0);

        this.template = $('#' + this.container_id + '_tmpl');

        this.data = ( 'undefined' !== typeof exc_dynamic_fields
                            && 'undefined' !== typeof exc_dynamic_fields[ this.container_id ] )
                        ? exc_dynamic_fields[ this.container_id ] : {};

        this.cur_row_settings = {};
        this.init();

        if ( 'function' === typeof this['initExtension'] ) {
            this.initExtension();
        }
    }

    exc_dynamic_rows.prototype = {

        init: function () {

            this.bindEvents();

            // Retrive Saved Data
            this._retrive();

            this._refresh();
        },

        optClicked: function (e) {

            e.preventDefault();

            var self = e.data.instance;

            self.executeCmd( $(this) );
        },

        executeCmd: function (target) {

            this.row = target;

            this.opt_name = this.row.data('opt-name');

            if ( this.opt_name && 'function' === typeof this[ this.opt_name ] ) {

                this._setup();

                var row = this[ this.opt_name ]();

                if ( 'toggle_status' !== this.opt_name ) {
                    this.toggle_status( true );
                }

                this._triggerEvents( row );

                this._refresh();
            }
        },

        toggle_status: function( update )
        {
            this.statusField = this.current_row.find( '.exc-row-status' );

            if ( this.statusField.length )
            {
                if ( update ) // Make sure we have similar values
                {
                    var button_status = this.current_row.find('.exc-status > .fa:first');

                    if ( button_status.length )
                    {
                        var activeStatus = ( button_status.hasClass( 'fa-eye' ) ) ? 'on' : '';
                        this.statusField.val( activeStatus );
                    }

                } else
                {
                    var currentStatus = this.statusField.val(),
                        toggleStatus = ( currentStatus != 'on' ) ? 'on' : '',
                        stateClass = ( toggleStatus ) ? 'fa fa-eye' : 'fa fa-eye-slash';

                    this.statusField.val( toggleStatus );
                    this.row.attr( 'class', stateClass );
                }
            }
        },

        delete_row: function()
        {
            //@TODO: if empty then display default message
            return this.current_row.remove();
        },

        add_row: function()
        {
            if ( this.container.children('.exc-dynamic-row').length )
            {
                this.current_row = $( this._get_html() ).insertAfter( this.container.children('.exc-dynamic-row:last') );

            } else
            {
                this.current_row = $( this._get_html() ).prependTo( this.container );
            }

            return this.current_row;
        },

        add_row_above: function()
        {
            return this.current_row = $( this._get_html() ).insertBefore( this.current_row );
        },

        add_row_below: function()
        {
            return this.current_row = $( this._get_html() ).insertAfter( this.current_row );
            //this.current_row.after( this.get_html() );
        },

        move_to_top: function()
        {
            return this.current_row = this.current_row.insertBefore( this.container.children('.exc-dynamic-row:first') );
        },

        move_to_bottom: function()
        {
            return this.current_row = this.current_row.insertAfter( this.container.children('.exc-dynamic-row:last') );
        },

        empty_box: function()
        {
            if ( ! this.container.data('autostart') && this.org_html.length ) {
                this.toolbar.hide();
                return this.container.html( this.org_html );
            }

            var row = this.add_row();

            this._triggerEvents( row );
            this._refresh();
        },

        updateTitle: function(e)
        {
            e.preventDefault();

            var $this = $( this ),
                row = $this.parents('.exc-dynamic-row:first'),
                container = row.parents('.exc-form-rows:first'),
                title = row.find('.exc_row_title'),
                value = $this.val() || container.data('default-title');

            title.text( value );
        },

        updateHeader: function()
        {
            if ( this.header.length )
            {
                if ( this.header.index() !== 0 )
                {
                    this.header.parent().prepend( this.header );
                }

                this.header.show(); // Make sure the header is at the top
            }
        },

        bindEvents: function()
        {
            var self = this;

            this.document.on( 'click', '[data-controls="' + this.container_id + '"] a', {instance: this}, this.optClicked );

            //this.container.on( 'click', '.exc-row-controls a', {instance: this}, this.optClicked );
            this.container.on('keyup change', ':input[data-primary_field="1"]', {instance: this}, this.updateTitle );
            this.container.sortable({ handle: ".exc-move", axis: "y", items: "> div.exc-dynamic-row", opacity: 0.5, placeholder: "sortable-placeholder", update: function(){ self._refresh(); } });
        },

        _setup: function()
        {
            this.controls = this.row.parents('.exc-row-controls');
            this.current_row = this.controls.parents('.exc-dynamic-row:first');
            this.id = this.current_row.data('row-id');
            this.length = this.container.children('.exc-dynamic-row').length + 1;
            this.i = this.length - 1;
            this.statusField = this.current_row.find('.exc-row-status');
        },

        _triggerEvents: function( row, data )
        {
            this.container.trigger( 'exc-dynamic-' + this.opt_name, [ row, data ] );

            // Wordpress Widget page support
            // if ( 'widgets' === pagenow )
            // {
            //  var form = this.container.parents('form:first'),
            //      widget_id = form.children('.widget_number:first').val();

            //  $( ':input', row ).each( function( ii, iv ){
            //      var html = $(iv),
            //          id = html.attr('id'),
            //          name = html.attr('name');

            //      html.attr('id', id.replace(/__i__/, widget_id) );
            //      html.attr('name', name.replace(/__i__/, widget_id) );
            //  });
            // }

            // Depreciated Code ( will be removed in future version )
            if ( this.opt_name !== 'delete_row' )
            {
                $(document).trigger('eXc.switch_field');
                $(document).trigger('eXc.colorpicker');
            }
        },

        _retrive: function()
        {
            if ( ! this.data.length ) {
                return this.empty_box();
            }

            var self = this;

            self.opt_name = 'add_row';
            self.row = self.container.find('.exc-row-controls a');
            self.cur_row_settings = { delete: true, move: true };

            $.each( this.data, function( i, nodes ) {

                if ( eXc.length( nodes ) )
                {
                    self._setup();

                    if ( 'undefined' !== typeof nodes['_settings'] )
                    {
                        self.cur_row_settings = nodes['_settings'];
                    }

                    var row = self.add_row(),
                        primary_field = true;

                    $( ':input', row ).each( function( ii, iv ){

                        var key,
                            field = $( iv ),
                            field_name = field.length ? field.attr('name') : false;

                        if ( field_name && ( key = field_name.match(/\[([a-z0-9_]+)\]$/) ) )
                        {
                            // Iconpicker fix @TODO: trigger event and write this code with iconpicker js
                            if ( field.attr('role') === 'iconpicker' )
                            {
                                field.data( 'icon', nodes[ key[1] ] );
                            } else
                            {
                                // @TODO: check if we have value attribute otherwise trigger event
                                field.val( nodes[ key[1] ] );
                            }

                            // Switch fix
                            // @TODO: bind event in switch.js
                            if( field.hasClass('exc-switch-field' ) )
                            {
                                if ( nodes[ key[1] ] )
                                {
                                    field.attr( 'checked', true );

                                } else
                                {
                                    field.attr( 'checked', false );
                                }
                            }

                            if ( primary_field && field.data('primary_field') )
                            {
                                primary_field = false;
                                field.trigger('keyup');
                            }
                        }
                    });

                    // Reflect Row Status
                    var statusField = row.find('.exc-row-status'),
                        statusIcon = row.find('.exc-row-controls > .exc-status'),
                        statusClass = statusField.val() ? 'fa fa-eye' : 'fa fa-eye-slash';

                    if ( statusField.length && statusIcon.length )
                    {
                        statusIcon.find('.fa').attr('class', statusClass);
                    }

                    self._triggerEvents( row, nodes );
                } else
                {
                    self._setup();
                    self.empty_box();
                }
            });

            self.cur_row_settings = {};
            this.updateHeader();
        },

        _get_html: function()
        {
            var html = this.template.html(),
                title = $( html ).find(':input[data-primary-field="1"]:first').val() || this.default_title;

            return _.template( html, eXc.tmpl )({i: this.i, count: this.length, title: title, settings: this.cur_row_settings});
        },

        _refresh: function()
        {
            var rows = this.container.children('.exc-dynamic-row');

            if ( rows.length )
            {
                this.updateHeader();

                this.toolbar.show();
                //this.add_btn.show();

                this.container.children('.exc-add-row').remove();

                $.each( rows, function(i, v)
                {
                    var element = $(v),
                        count = i + 1;

                    element.find('.exc-count').text( count );

                    $.each(element.find(':input'), function(ei, ev){
                        var field = $(ev),
                            field_name = field.length ? field.attr('name') : false,
                            newName;

                            if ( field_name && ( newName = field_name.replace(/\[(\d+)\]/, '['+i+']') ) )
                            {
                                field.attr('name', newName);
                            }
                    });

                    // Make sure the tooltip is working
                    element.find('[data-toggle="tooltip"]').tooltip();
                });

            } else {
                this.empty_box();
            }
        }
    };

    $( document ).ready( function(){
        $('.exc-form-rows, div[data-action="exc-form-rows"]').each( function(){
            new exc_dynamic_rows( this );
        });
    });

})(window, jQuery);