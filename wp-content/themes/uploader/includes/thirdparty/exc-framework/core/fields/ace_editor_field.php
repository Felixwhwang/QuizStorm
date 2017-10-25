<?php defined( 'ABSPATH' ) or die('restricted access');

if ( ! class_exists( 'eXc_Textarea_Field') ) {
    $this->load('core/fields/textarea_field');
}

if ( ! class_exists('eXc_Ace_Editor_Field') ) :
class eXc_Ace_Editor_Field extends eXc_Textarea_Field
{
    public function enqueue_files()
    {
        $this->eXc->html->load_js_args(
                        'ace',
                        '//cdn.jsdelivr.net/ace/1.1.8/min/ace.js'
                    )
        ->inline_js(
                'ace',
                '
                    if ( typeof ace === "object" )
                    {
                        $.each( $("textarea.ace-editor"), function(key, textarea){

                            var textarea = $( textarea ),
                                editor = textarea.parent().children(".exc-ace-editor"),
                                editor_id = "exc-ace-editor-" + key,
                                mode = textarea.data("ace_mode") || "html",
                                theme = textarea.data("ace_theme") || "crimson_editor",
                                height = textarea.data("ace_height") || "200px";

                            if ( ! editor.length )
                            {
                                var html = $("<div/>", {id: editor_id});
                                editor = html.insertAfter( textarea );

                                editor.height( height );
                            }

                            textarea.hide();

                            var editor = ace.edit( editor_id );
                            editor.getSession().setValue( textarea.val() );

                            editor.getSession().on("change", function() {
                                   textarea.val( editor.getSession().getValue() );
                               });

                            editor.setTheme("ace/theme/" + theme);
                            editor.getSession().setMode("ace/mode/" + mode );
                        } );
                    }
                ', '', true
            );

    }

    public function normalize_data()
    {
        parent::normalize_data();

        $this->config['attrs']['class'] = stristr( $this->config['attrs']['class'], 'ace-editor' ) ?
                                            $this->config['attrs']['class'] :
                                            $this->config['attrs']['class'] . ' ace-editor';
    }
}

endif;