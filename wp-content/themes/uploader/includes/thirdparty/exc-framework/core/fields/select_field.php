<?php if( ! defined('ABSPATH')) exit('restricted access');

if ( ! class_exists( 'eXc_Select_Field' ) )
{
    class eXc_Select_Field extends eXc_Fields_Abstract
    {
        protected $default_skin = 'form_group';

        function enqueue_files()
        {
            if ( isset( $this->config['attrs']['multiple'] ) ) {
                $this->eXc->html->inline_js( "exc-select-list", $this->custom_js(), "", true );
            }
        }

        function html($return = false)
        {
            $this->config['markup'] = $this->get_html();
            return $this->apply_skin($return);
        }

        function normalize_data()
        {
            $this->config['attrs'] = wp_parse_args(
                                        $this->config['attrs'],
                                        array(
                                            'name' => $this->name
                                        )
                                    );

            if ( isset( $this->config['attrs']['multiple'] ) ) $this->config['attrs']['name'] = $this->config['attrs']['name'].'[]';

            if( isset($this->config['selected']) && ! is_array($this->config['selected']) )
            {
                $this->config['selected'] = array( $this->config['selected'] );
            }
        }

        function get_html()
        {
            $form = '<select ' . exc_parse_atts( $this->config['attrs'] ) . ">\n";

            foreach( (array) $this->config['options'] as $key=>$val)
            {
                $key = (string) $key;

                if(is_array($val))
                {
                    $form .= '<optgroup label="'.$key.'">'."\n";

                    foreach ($val as $optgroup_key => $optgroup_val)
                    {
                        $sel = $this->eXc->validation->set_select($this->name, $optgroup_key, (in_array($optgroup_key, $this->config['selected'])));
                        $form .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
                    }

                    $form .= '</optgroup>'."\n";
                }
                else
                {
                    $sel = $this->eXc->validation->set_select($this->name, $key, (in_array($key, $this->config['selected'])));
                    $form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
                }
            }

            $form .= '</select>';

            if ( isset( $this->config['attrs']['multiple'] ) ) {
                $form .= '<a href="#" class="btn btn-sm btn-primary exc-clear-list">' . __("Clear All", "exc-framework") . '</a>';
            }

            return $form;
        }

        private function custom_js()
        {
            return '
                $( document ).on( "click", ".exc-clear-list", function (e) {
                    e.preventDefault();

                    $( this ).prev("select").val("");
                });
            ';
        }
    }
}