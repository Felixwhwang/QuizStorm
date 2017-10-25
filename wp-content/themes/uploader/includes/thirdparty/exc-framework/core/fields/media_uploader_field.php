<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Media_Uploader_Field' ) )
{
    class eXc_Media_Uploader_Field extends eXc_Fields_Abstract
    {
        protected $default_skin = 'media_uploader';

        public function html( $return = false )
        {
            return $this->apply_skin( $return );
        }

        protected function skin_media_uploader( $return = false )
        {
            $max_upload_limit = ( ! empty( $this->config['data-upload-limit'] ) ) ? $this->config['data-upload-limit'] : 0;

            if ( $max_upload_limit > 1 )
            {
                $i18n_upload_button = apply_filters( 'exc_plural_upload_string', _x( 'Upload Files', 'extracoding media uploader field', 'exc-framework' ) );
            } else
            {
                $i18n_upload_button = apply_filters( 'exc_singular_upload_string', _x('Upload File', 'extracoding media uploader field', 'exc-framework' ) );
            }

            if ( ! $this->is_dynamic ) {
                $this->config['attrs']['value'] = implode( ',', (array) $this->set_value() );
            }

            $this->config['markup'] = sprintf(
                                            '<button %s><span class="spinner"></span>%s</button>',
                                            exc_parse_atts( $this->config['attrs'] ),
                                            $i18n_upload_button
                                        );

            $this->config['markup'] .= $this->get_help_note();

            $this->config['help'] = '';

            // Attach already uploaded files list
            //$this->get_uploaded_files();

            return $this->skin_form_group( $return );
        }

        protected function normalize_data()
        {
            if ( empty( $this->config['attrs']['name'] ) )
            {
                $this->config['attrs']['name'] = $this->name;
            }

            // Make sure the button is by default disabled
            if ( ! isset( $this->config['attrs']['disabled'] ) )
            {
                $this->config['attrs']['disabled'] = 'disabled';
            }

            if ( empty( $this->config['attrs']['class'] ) )
            {
                $this->config['attrs']['class'] = 'btn btn-primary exc-media-uploader hide-if-no-js new-files';
            } else
            {
                $this->config['attrs']['class'] .= ' btn btn-primary exc-media-uploader hide-if-no-js new-files';
            }
        }

        public function enqueue_files()
        {
            //parent::enqueue_files();

            // Include media uploader files
            wp_enqueue_media();

            $i18n_title = _x( 'Select Files', 'exc file uploader', 'exc-framework' );

            $this->eXc->html->load_js( 'exc-uploader-field', $this->eXc->system_url( 'views/js/fields/media-uploader.js' ), '1.0', array('exc-framework') );

            $this->eXc->html->localize_script( 'exc-uploader-field', 'exc_media_uploader_field',
                        array(
                            'strings' =>
                                array(
                                    'title'             => $i18n_title,
                                    'loading'           => _x( 'Loading...', 'extracoding media uploader', 'exc-framework' ),
                                    'maxLimitSingular'  => _x( 'You can upload only 1 file.', 'extracoding media uploader', 'exc-framework' ),
                                    'maxLimitPlural'    => _x( 'You can upload maximum %d files.', 'extracoding media uploader', 'exc-framework' ),
                                ),

                            'security' => wp_create_nonce( 'exc-get-attachment-list' )
                        )
                    );

            $this->eXc->html->inline_js( 'exc-framework', $this->custom_js() );
        }

        public function add_actions()
        {
            // Attach images via Ajax
            add_action( 'print_media_templates', array( &$this, 'print_templates' ) );

            add_action( 'wp_ajax_exc_load_attachment_list', array( &$this, 'load_attachment_list' ) );
            // if ( $this->is_dynamic )
            // {
            //  add_action( 'exc_dynamic_fields_data', array( &$this, 'extend_dynamic_data' ), 10, 2 );
            // }
        }

        public function load_attachment_list()
        {

        }

        // public function extend_dynamic_data( $settings, $field_name )
        // {
        //  return $settings;
        //  if ( $field_name == $this->name )
        //  {
        //      foreach ( $settings as $data_key => $data )
        //      {
        //          if ( isset( $data[ $this->config_key ] ) && intval( $data[ $this->config_key ] ) )
        //          {
        //              $attachment_data = $data[ $this->config_key ];

        //              if ( is_array( $attachment_data ) )
        //              {
        //                  exc_die( __('The attachment data is array in file media_uploader_field.php', 'exc-framework' ) );
        //              } else
        //              {
        //                  $post = get_post( $attachment_data );

        //                  if ( $post && $post->post_type == 'attachment' )
        //                  {
        //                      // @TODO: make sure the mime type is allowed

        //                      $post_mime_type = current( explode('/', $post->post_mime_type ) );

        //                      $html_content = '';

        //                      switch ( $post_mime_type )
        //                      {
        //                          case "image" :

        //                              $image_data = wp_get_attachment_image_src( $post->ID, 'medium' );

        //                              if ( ! empty( $image_data[0] ) )
        //                              {
        //                                  $html_content = '<img src="' . esc_url( $image_data[0] ) . '" />';
        //                              }

        //                          break;

        //                          case "audio" :

        //                          break;

        //                          case "video" :

        //                              $html_content = wp_video_shortcode(
        //                                                  array(
        //                                                      'url'       => $post->guid,
        //                                                      'loop'      => '',
        //                                                      'autoplay'  => false,
        //                                                      // 'width'      => 980,
        //                                                      // 'height' => 735,
        //                                                      'preload'   => 'none'
        //                                                  )
        //                                              );
        //                          break;
        //                      }
        //                  }

        //                  $settings[ $data_key ][ $this->config_key . '_preview' ] = $html_content;

        //                  wp_reset_postdata();
        //              }
        //          }
        //      }
        //  }

        //  return $settings;
        // }

        // protected function skin_file_upload( $return = false )
        // {
        //  $i18n_upload  = ( exc_kv( $this->config, array( 'el_attrs', 'data-maxUploadLimit' ) ) > 1 )
        //                  ? apply_filters( 'exc_plural_upload_string', _x( 'Upload Files', 'extracoding media uploader field', 'exc-framework' ) )
        //                  : apply_filters( 'exc_singular_upload_string', _x('Upload File', 'extracoding media uploader field', 'exc-framework' ) );

        //  $this->config['markup'] = sprintf(
        //                                  '<button %s><span class="spinner"></span>%s</button>',
        //                                  exc_parse_atts( $this->config['attrs'] ),
        //                                  $i18n_upload
        //                              );

        //  $this->config['markup'] .= $this->get_help_note();

        //  $this->config['help'] = '';

        //  // Attach already uploaded files list
        //  $this->get_uploaded_files();

        //  return $this->skin_form_group( $return );
        // }

        // protected function normalize_data()
        // {
        //  parent::normalize_data();

        //  $this->config['attrs']['type'] = 'button';
        //  $this->config['el_attrs']['data-field_name'] = $this->config['attrs']['name'];
        // }

        function normalize_class( &$classes, $new_values = array( 'exc-file-upload' ) )
        {
            parent::normalize_class( $classes, array('btn', 'btn-default', 'exc-media-uploader', 'hide-if-no-js', 'new-files' ) );
        }

        function print_templates()
        {
            $i18n_delete_str = apply_filters('exc_delete_string', _x('Delete', 'extracoding media uploader field', 'exc-framework'));
            $i18n_edit_str = apply_filters('exc_edit_string', _x('Edit', 'extracoding media uploader field', 'exc-framework'));?>

            <script id="tmpl-exc-media-uploader-field" type="text/html">
                <# _.each( attachments, function( attachment ) { #>
                    <li class="exc-attachment-item" id="item_{{{ attachment.id }}}">

                        <div class="panel attachment-tools">
                            <div class="panel-heading">
                                <ul class="exc-panel-heading-list pull-left">
                                    <li class="atch-type">

                                        <# if ( attachment.type === 'image' ) { #>
                                            <i class="fa fa-image"></i>
                                        <# } else if ( attachment.type === 'video' ) { #>
                                            <i class="fa fa-video-camera"></i>
                                        <# } else if ( attachment.type === 'audio' ) { #>
                                            <i class="fa fa-music"></i>
                                        <# } else { #>
                                            <i class="fa fa-file"></i>
                                        <# } #>
                                    </li>
                                    <li class="atch-title"><h4 class="panel-title"><a href="{{{ attachment.source }}}" target="_blank">{{{ attachment.title }}}</a></h4></li>
                                </ul>
                                <ul class="exc-panel-heading-list pull-right">
                                    <li class="exc-attachment-delete"><a class="attachment-delete" href="#" data-attachment-id="{{{ attachment.id }}}"><?php echo '<i class="fa fa-times"></i>' ?></a></li>
                                    <li class="exc-attachment-edit"><a class="attachment-edit" href="{{{ attachment.editLink }}}" target="_blank"><?php echo '<i class="fa fa-edit"></i>'?></a></li>
                                    <li class="exc-attachment-sort"><a class="fa fa-arrows" href="#"></a></li>
                                </ul>
                            </div>
                        </div>

                        <input type="hidden" name="{{{ fieldName }}}[]" value="{{{ attachment.id }}}" />
                    </li>
                <# }); #>
            </script>
            <?php
        }

        private function custom_js()
        {
            return "
                ( function( window, $){

                    // $( document ).on('exc-dynamic-add_row', function( e, row, data ){

                    //  var attachments = row.find('.exc-preview-thumb');

                    //  if ( attachments.length )
                    //  {
                    //      $.each( attachments, function(){
                    //          var data_array = attachments.prev('.exc-media-uploader'),
                    //              name = data_array.attr('name').match(/\[\d+\]\[(.*)\]$/);

                    //          if ( 'undefined' !== typeof name[1] )
                    //          {
                    //              $(this).replaceWith( data[ name[1] + '_preview'] );
                    //          }
                    //      });
                    //  }
                    // });

                })( window, jQuery );
            ";
        }
    }
}