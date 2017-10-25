<?php if( ! defined('ABSPATH')) exit('restricted access');

if ( ! class_exists( 'eXc_Switch_Field' ) )
{
    class eXc_switch_Field extends eXc_fields_abstract
    {
        protected $field_type = 'switch';
        protected $default_skin = 'form_group';

        public function enqueue_files()
        {
            $this->eXc->html->load_js( 'bootstrap-switch', $this->eXc->system_url( 'views/js/bootstrap-switch/bootstrap-switch.min.js' ) )
                            ->load_js( 'exc-switch', $this->eXc->system_url( 'views/js/bootstrap-switch/switch.js' ) )
                            ->load_css( $this->eXc->system_url('views/css/bootstrap-switch/bootstrap-switch.min.css') );
        }

        public function html( $return = false )
        {
            if ( $this->set_value() == 'on' )
            {
                $this->config['attrs']['checked'] = true;

            } elseif ( isset( $this->config['attrs']['checked'] ) )
            {
                unset( $this->config['attrs']['checked'] );
            }

            $this->config['markup'] = sprintf( $this->element_wrap( 'checkbox' ),
                                         exc_parse_atts( $this->config['attrs'] )
                                    );

            return $this->apply_skin( $return );
        }

        public function normalize_data()
        {
            if(isset($this->config['attrs']['class']) && (strstr($this->config['attrs']['class'], 'exc-switch-field') === false))
            {
                $this->config['attrs']['class'] .= ' exc-switch-field';
            }

            $i18n_on_str = apply_filters('exc_on_string', _x('ON', 'extracoding switch field', 'exc-framework'));
            $i18n_off_str =  apply_filters('exc_off_string', _x('OFF', 'extracoding switch field', 'exc-framework'));

            $this->config['attrs'] = wp_parse_args($this->config['attrs'],
                                            array(
                                                'name' => $this->name,
                                                'type' => 'checkbox',
                                                'class' => 'exc-switch-field',
                                                'data-on-text' => $i18n_on_str,
                                                'data-off-text' => $i18n_off_str,
                                                'data-on-color' => 'info',
                                                'data-off-color'=> 'danger'
                                            )
                                        );
        }
    }
}