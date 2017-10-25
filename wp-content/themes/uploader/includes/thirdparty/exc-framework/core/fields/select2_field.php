<?php if( ! defined('ABSPATH')) exit('restricted access');

/** Make sure select field is already loaded */

if ( ! class_exists( 'eXc_Select_Field' ) )
{
    $this->load('core/fields/select_field');
}

if ( ! class_exists( 'eXc_Select2_field' ) )
{
    class eXc_Select2_Field extends eXc_Select_Field
    {
        function enqueue_files()
        {
            if ( empty( $this->config ) )
            {
                return;
            }

            $this->eXc->html->load_js( 'select2', $this->eXc->system_url( 'views/js/fields/select2.min.js' ) )
                            ->load_css( 'select2', $this->eXc->system_url( 'views/css/fields/select2.css' ) )
                            //->inline_js( 'select2', $this->custom_js() );
                            ->inline_js( 'select2', $this->custom_js(), '', true, true );
        }

        function html( $return = false )
        {
            if ( isset( $this->config['data_type'] ) && $this->config['data_type'] == 'json' )
            {
                $this->config['markup'] = sprintf( $this->element_wrap('input'),
                                            exc_parse_atts( $this->config['attrs'] ) );

                $this->config['json_data'] = $this->json_array( $data );

            } else
            {
                $wrap = ( isset( $this->config['element'] ) && $this->element_wrap( $this->config['element'] ) )
                            ? $this->element_wrap( $this->config['element'] ) : $this->element_wrap('input');

                $this->config['markup'] = ( isset( $this->config['element'] ) && $this->config['element'] == 'select' ) ? $this->get_html() :
                                            sprintf( $wrap, exc_parse_atts( $this->config['attrs'] ), '' );
            }

            return $this->apply_skin( $return );
        }

        function normalize_data()
        {
            $this->config['attrs'] = wp_parse_args(
                                    (array) $this->config['attrs'],
                                            array(
                                                'type'  => 'hidden',
                                                'name'  => $this->name,
                                                'id'    => str_replace('/', '-', $this->name)
                                            )
                                        );

            $this->config['attrs']['class'] = ( ! empty( $this->config['attrs']['class'] ) )
                                                ? $this->config['attrs']['class'] . ' exc-select2-field'
                                                : 'exc-select2-field';

            //select2 settings
            $this->config['select2'] = ( isset( $this->config['select2'] ) ) ? $this->config['select2'] : array();
        }

        function json_array($data)
        {
            $json = array();
            foreach($data['options'] as $key=>$val)
            {
                $json[] = '{id:"'.$key.'", text:"'.$val.'"}';
            }

            return '['.implode(', ', (array) $json).']';
        }

        private function custom_js()
        {
            $id = exc_kv( $this->config, 'attrs/id', $this->name );

            return "
                //( function( window, $){

                    $(document).on('select2-attach', function(){
                        var container = $('#" . $id . "');

                        if ( ! container.length || container.hasClass('select2-offscreen') )
                        {
                            return;
                        }

                        container.select2(" . json_encode( $this->config['select2'] ) . ");
                    });

                    $( document ).trigger( 'select2-attach' );

                    // Dynamic Fields
                    $( document ).on('exc-dynamic-add_row', function(e, row){
                        $( document ).trigger( 'select2-attach' );
                    });

                //})( window, jQuery );
            ";
        }
    }
}