<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_IconPicker_Field' ) )
{
    class eXc_IconPicker_Field extends eXc_Fields_Abstract
    {
        protected $default_skin = 'form_group';

        public function html( $return = false )
        {
            $element = ( ! empty( $this->config['element'] ) ) ?
                            $this->config['element'] : 'button';

            // Default value
            if ( empty( $this->config['attrs']['data-icon'] ) )
            {
                $this->config['attrs']['data-icon'] = $this->set_value();
            }

            if ( $element == 'div' )
            {
                $this->config['markup'] = sprintf(
                                            '<div %s role="iconpicker"></div>',
                                            exc_parse_atts( $this->config['attrs'] )
                                        );

            } else
            {
                $this->config['markup'] = sprintf( '<button %s role="iconpicker"></button>',
                                            exc_parse_atts( $this->config['attrs'] )
                                        );
            }

            return $this->apply_skin( $return );
        }

        public function enqueue_files()
        {
            $this->eXc->html->load_js( 'bs-iconpikcer-iconset', $this->eXc->get_file_url( 'views/js/bootstrap-iconpicker/iconset/iconset-all.min.js' ) )
                            ->load_js( 'bs-iconpicker', $this->eXc->get_file_url( 'views/js/bootstrap-iconpicker/bootstrap-iconpicker.min.js' ) )
                            ->load_css( 'bs-iconpicker', $this->eXc->get_file_url('views/css/bootstrap-iconpicker/bootstrap-iconpicker.min.css') )
                            ->inline_js( 'bs-iconpicker', $this->custom_js() );
        }

        protected function normalize_data()
        {
            if ( empty( $this->config['attrs']['name'] ) )
            {
                $this->config['attrs']['name'] = $this->name;
            }

            if ( isset( $this->config['element'] ) && $this->config['element'] == 'input' )
            {
                $this->name = $this->name . '-data';
            }

            $this->config['data'] = wp_parse_args(
                                        $this->config['data'],
                                        array(
                                            'iconset'               => 'glyphicon|ionicon|fontawesome|weathericon|mapicon|octicon|typicon|elusiveicon',
                                            'icon'                  => '',
                                            'search'                => '',
                                            'search-text'           => '',
                                            'header'                => '',
                                            'footer'                => '',
                                            'label-footer'          => '',
                                            'row'                   => '',
                                            'cols'                  => '',
                                            'align'                 => 'center',
                                            'placement'             => 'bottom',
                                            'arrow-class'           => '',
                                            'arrow-prev-icon-class' => '',
                                            'arrow-next-icon-class' => '',
                                            'selected-class'        => '',
                                            'unselected-class'      => ''
                                        )
                                    );

            foreach ( $this->config['data'] as $k => $v )
            {
                if ( $v )
                {
                    $this->config['attrs'][ 'data-' . $k ] = $v;
                }
            }

            unset( $this->config['data'] );
        }

        private function custom_js()
        {
            return "
                ( function( window, $){
                    $('body').on('shown.bs.popover', function(e){

                        var target = $( e.target ),
                          popover_id = target.attr('aria-describedby'),
                          popover = $( '#' + popover_id );

                        if ( popover_id && target.attr('role') === 'iconpicker' )
                        {
                            popover.wrap('<div class=\"exc-panel\"></div>');
                        }
                    });

                    $('body').on('hide.bs.popover', function(e){

                        var target = $( e.target ),
                        popover_id = target.attr('aria-describedby');

                        if ( popover_id && target.attr('role') === 'iconpicker' )
                        {
                            $( '#' + popover_id ).parent('.exc-panel').remove();
                        }
                    });

                    // Dynamic Fields
                    $( document ).on('exc-dynamic-add_row', function(e, row){
                        $('[role=\"iconpicker\"]', row).iconpicker();
                    });
                })( window, jQuery );
            ";
        }
    }
}