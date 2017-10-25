<?php if( ! defined('ABSPATH')) exit('restricted access');

if ( ! class_exists( 'eXc_Clickable_Field' ) )
{
    class eXc_Clickable_Field extends eXc_Fields_Abstract
    {
        protected $default_skin = 'form_clickable';
        private $char = 64; // Hex

        function enqueue_files()
        {
            $this->eXc->html->load_js( 'exc-file-clickable', $this->eXc->system_url( 'views/js/fields/clickable.js' ) );
        }

        function html( $return = false )
        {
            if ( empty( $this->config['options'] ) )
            {
                return;
            }

            if ( ! isset( $this->config['markup'] ) )
            {
                $this->config['markup'] = '';
            }

            foreach ( $this->config['options'] as $k => $v )
            {
                if ( isset( $this->config['option_skin'] ) )
                {
                    /** Security Check: Make sure only method with prefix skin_ are accessible */
                    $skin = ( false !== strstr( $this->config['skin'], 'skin_' ) ) ? $this->config['option_skin'] : 'skin_' . $this->config['option_skin'];

                    if  ( ! method_exists( $this, $skin ) )
                    {
                        exc_die( sprintf( __("The skin method '%s' is not exists.", 'exc-framework' ), $skin ) );
                    }

                    $this->config['markup'] .= $this->{ $skin }( $k, $v );
                }
            }

            $this->config['markup'] .= sprintf( $this->element_wrap('input'),
                                                    exc_parse_atts( $this->config['attrs'] ),
                                                    $this->set_value()
                                                );

            return $this->apply_skin( $return );
        }

        function normalize_data()
        {
            //@TODO: use saved value as default
            $this->config['attrs'] = wp_parse_args(
                            $this->config['attrs'],
                            array(
                                'name'              => $this->name,
                                'type'              => 'radio',
                                'class'             => 'form-control hide',
                                'checked'           => 'checked',
                                'data-max_limit'    =>  0
                            )
                        );
        }

        protected function skin_form_field( $return = false )
        {
            $this->config['markup'] = sprintf(
                                            '<div data-toggle="buttons" class="exc-clickable-wrapper content-button">%s</div>',
                                            $this->config['markup']
                                        );

            return parent::skin_form_field( $return );
        }

        protected function skin_form_clickable( $return, $apply_skin = true )
        {
            $this->config['markup'] = sprintf(
                                            '<div data-toggle="buttons" class="exc-clickable-wrapper content-button">%s</div>',
                                            $this->config['markup']
                                        );

            return ( TRUE == $apply_skin ) ? $this->skin_form_group( $return ) : $this->config['markup'];
        }

        protected function skin_withalpha( $id, $settings = array() )
        {
            $char = chr( ++$this->char );

            $atts = wp_parse_args( $settings,
                                array(
                                    'class'                 => 'btn exc-clickable exc-clickable-alpha',
                                    'data-linked_fields'    => '',
                                    'data-id'               => $id,
                                    'data-char'             => $char,
                                )
                        );

            if( isset( $atts['class'] ) && FALSE === strstr( $atts['class'], 'exc-clickable') )
            {
                $atts['class'] .= ' exc-clickable exc-clickable-alpha';
            }

            return sprintf(
                        '<label %s> <span class="letter">%s</span><div class="content-text">%s</div>
                        <span class="selected-tick"><i class="fa fa-check"></i></span></label>' . "\n",
                        exc_parse_atts( $atts ), $char, $settings['label']
                    );
        }

        protected function skin_withicons( $id, $settings = array() )
        {
            $settings = wp_parse_args( $settings,
                                    array(
                                        'icon'  => '',
                                        'label' => '',
                                        'attrs' => array(),
                                    )
                                );

            $atts = exc_kv( $settings, 'attrs' );

            if ( isset( $atts['class'] ) && FALSE === strstr( $atts['class'], 'exc-clickable') )
            {
                $atts['class'] .= ' exc-clickable';
            }

            $atts = wp_parse_args( $atts,
                                array(
                                    'class'                 => 'btn exc-clickable',
                                    'data-linked_fields'    => '',
                                    'data-id'               => $id,
                                )
                        );

            return sprintf(
                        '<label %s> %s <div class="content-text">%s</div>
                        <span class="selected-tick"><i class="glyphicon glyphicon-ok"></i></span></label>'."\n",
                        exc_parse_atts( $atts ), $settings['icon'], $settings['label']
                    );
        }

        protected function skin_withimages( $id, $atts = array() )
        {
            if ( isset( $atts['class'] ) && FALSE === strstr( $atts['class'], 'exc-clickable') )
            {
                $atts['class'] .= ' exc-clickable';
            }

            $atts = wp_parse_args( $atts,
                                array(
                                    'class'                 => 'exc-clickable clickable-image',
                                    'data-linked_fields'    => '',
                                    'data-id'               => $id
                                )
                        );

            $src = isset( $atts['src'] ) ? $atts['src'] : '';

            return sprintf(
                        '<div %s> <img src="%s" /><span class="exc-selected-layout"><i class="fa fa-check"></i></span></div>'."\n",
                        exc_parse_atts( $atts ),
                        $src
                    );
        }

        protected function skin_with_css( $id, $atts = array() )
        {
            if ( isset( $atts['class'] ) && FALSE === strstr( $atts['class'], 'exc-clickable') )
            {
                $atts['class'] .= ' exc-clickable';
            }

            $atts = wp_parse_args( $atts,
                                array(
                                    'class'                 => 'btn exc-clickable fat-image',
                                    'data-linked_fields'    => '',
                                    'data-id'               => $id,
                                )
                        );

            return sprintf(
                        '<label %s> <span class="exc-selected-layout"><i class="fa fa-check"></i></span></label>'."\n",
                        exc_parse_atts( $atts )
                    );
        }
    }
}