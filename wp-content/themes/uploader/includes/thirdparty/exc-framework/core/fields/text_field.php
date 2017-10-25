<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Text_Field' ) )
{
    class eXc_Text_Field extends eXc_Fields_Abstract
    {
        protected $default_skin = 'form_group';

        function html( $return = false )
        {
            $this->config['markup'] = sprintf(
                        $this->element_wrap('input'),
                        exc_parse_atts( $this->config['attrs'] ),
                        $this->set_value()
                    );

            return $this->apply_skin( $return );
        }

        function normalize_data()
        {
            $this->config['attrs'] = wp_parse_args(
                                        $this->config['attrs'],
                                        array(
                                            'name' => $this->name,
                                            'type' => ( $this->config['type'] ) ? $this->config['type'] : 'text',
                                        )
                                    );

            if ( isset( $this->config['attrs']['value'] ) )
            {
                unset( $this->config['attrs']['value'] );
            }
        }
    }
}