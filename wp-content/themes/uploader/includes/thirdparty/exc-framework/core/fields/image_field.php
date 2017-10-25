<?php if( ! defined('ABSPATH')) exit('restricted access');

if( ! class_exists( 'eXc_Image_Field' ) )
{
    class eXc_Image_Field extends eXc_Fields_Abstract
    {
        protected $default_skin = 'image_upload';

        function html( $return = false )
        {
            $this->config['markup'] = sprintf( $this->element_wrap( 'input' ),
                                        exc_parse_atts( $this->config['attrs'] ),
                                        $this->set_value() );

            return $this->apply_skin( $return );
        }

        function enqueue_files()
        {
            // Load thickbox library
            add_thickbox();

            $this->eXc->html->load_js( 'exc-image-upload', $this->eXc->system_url( 'views/js/fields/image.js' ) );

            $i18n_thickbox_title = apply_filters( 'exc_thickbox_title', sprintf( _x( 'Upload a %s', 'extracoding image field', 'exc-framework' ), $this->config['label'] ) );

            $this->eXc->html->localize_script( 'exc-image-upload', 'exc_image_upload', array( 'i18n_title' => $i18n_thickbox_title ) );
        }

        function skin_form_field( $return = false )
        {
            $this->config['markup'] = $this->skin_image_upload( $return, false );

            return parent::skin_form_field( $return );
        }

        function skin_widget_image_upload( $return = false, $apply_skin = false )
        {
            $i18n_remove = apply_filters( 'exc_remove_string', _x( 'Remove', 'extracoding image field', 'exc-framework' ) );

            $image_url = $this->set_value();
            $image_html = ( $this->eXc->validation->valid_url( $this->set_value() ) )? '<img src=" ' . esc_url( $image_url ) . ' " />' : '';

            $hide_empty = ( $image_html ) ? '' : 'hide';

            $this->config['markup'] = sprintf( '%s %s
                                                <span class="exc-btn-group">
                                                    <button type="button" class="btn btn-sm btn-default exc-image-upload-btn">%s %s</button>
                                                    <button class="btn btn-sm btn-danger %s exc-image-field-remove" type="submit">%s %s</button>
                                                </span>
                                                 %s',
                                                $this->config['markup'],
                                                $this->eXc->validation->error( $this->name ),
                                                $this->config['btn_icon'], $this->config['btn_text'],
                                                $hide_empty, $this->config['btn_remove_icon'], $i18n_remove,
                                                $this->get_help_note( '<i class="help-block">%s</i>' ) );

            //Hack help note to display before preview
            $this->config['help'] = '';

            //Don't show error in form group skin
            $this->display_error = false;

            $this->config['markup'] .= sprintf( '<span class="exc-preview">%s</span>', $image_html );


            return parent::skin_wp_widget( $return );
        }

        function skin_image_upload( $return = false, $apply_skin = true )
        {
            $this->config['markup'] = sprintf( '<div class="input-group">%s<span class="input-group-btn">
                                                <button type="button" class="btn btn-default exc-image-upload-btn">%s %s</button> </span></div>%s %s',
                                                $this->config['markup'],
                                                $this->config['btn_icon'], $this->config['btn_text'],
                                                $this->eXc->validation->error( $this->name ),
                                                $this->get_help_note() );

            //Hack help note to display before preview
            $this->config['help'] = '';

            //Don't show error in form group skin
            $this->display_error = false;

            $i18n_remove = apply_filters( 'exc_remove_string', _x( 'Remove', 'extracoding image field', 'exc-framework' ) );

            $hide = ( $image = $this->set_value() ) ? '' : 'hide';

            $image = ($image) ? '<img src=' . esc_url( $image ) . ' />' : '';

            $this->config['markup'] .= sprintf( '<div class="exc-preview">%s</div>
                                                    <div class="exc-btn-group">
                                                        <button class="btn btn-danger exc-image-field-remove %s" type="submit">%s</button>
                                                    </div>',
                                                $image,
                                                $hide,
                                                $i18n_remove
                                            );

            return ( TRUE == $apply_skin ) ? $this->skin_form_group( $return ) : $this->config['markup'];
        }

        protected function normalize_data()
        {
            $this->config['attrs'] = wp_parse_args(
                                        $this->config['attrs'],
                                        array(
                                            'name'  => $this->name,
                                            'type'  => 'text',
                                            'class' => 'exc-image-field'
                                        )
                                    );

            if ( FALSE == strstr( $this->config['attrs']['class'], 'exc-image-field' ) )
            {
                $this->config['attrs']['class'] .= ' exc-image-field';
            }

            $i18n_upload_str = apply_filters( 'exc_upload_string', _x( 'Upload', 'extracoding image field', 'exc-framework' ) );

            if ( ! isset( $this->config['btn_text'] ) )
            {
                $this->config['btn_text'] = $i18n_upload_str;
            }

            if ( ! isset( $this->config['btn_icon'] ) )
            {
                $this->config['btn_icon'] = '<i class="fa fa-upload"></i>';
            }

            if ( ! isset( $this->config['btn_remove_icon'] ) )
            {
                $this->config['btn_remove_icon'] = '<i class="fa fa-trash"></i>';
            }
        }
    }
}