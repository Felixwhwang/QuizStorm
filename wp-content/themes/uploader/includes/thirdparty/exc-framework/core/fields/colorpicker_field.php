<?php if( ! defined('ABSPATH')) exit('restricted access');

if ( ! class_exists( 'eXc_Colorpicker_Field' ) )
{
    class eXc_Colorpicker_Field extends eXc_Fields_Abstract
    {
        protected $default_skin = 'form_group';

        function enqueue_files()
        {
            $this->eXc->html->load_js( 'colpick', $this->eXc->system_url( 'views/js/fields/colpick.js' ) )
                            ->load_js( 'exc-colorpicker', $this->eXc->system_url( 'views/js/fields/colorpicker.js' ) )
                            ->load_css( 'exc-colorpicker', $this->eXc->system_url( 'views/css/fields/colpick.css' ) );
        }

        function html( $return = false )
        {
            if ( ( $value = $this->set_value() ) && is_array( $value ) )
            {
                $value = rgb2hex( $value );
            }

            $this->config['markup'] = sprintf( $this->element_wrap('input'),
                                        exc_parse_atts( $this->config['attrs'] ),
                                        $value );

            return $this->apply_skin( $return );
        }

        function normalize_data()
        {
            $this->config['attrs'] = wp_parse_args(
                                        $this->config['attrs'],
                                        array(
                                            'name'  => $this->name,
                                            'type'  => ( $this->config['type'] ) ? $this->config['type'] : 'text',
                                            'class' => 'exc-colorpicker'
                                        )
                                    );

            if ( isset( $this->config['attrs']['value'] ) )
            {
                unset( $this->config['attrs']['value'] );
            }

            $class = exc_kv( $this->config, array('attrs', 'class') );

            if ( strstr( $class, 'exc-colorpicker') === false )
            {
                $this->config['attrs']['class'] .= ' exc-colorpicker';
            }

            // Change the default color
            $this->config['attrs']['style'] = 'border-color:' . $this->set_value() . ';';
        }
    }
}