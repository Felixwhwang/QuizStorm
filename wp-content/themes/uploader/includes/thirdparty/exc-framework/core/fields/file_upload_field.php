<?php defined( 'ABSPATH' ) or die( 'restricted access' );

if ( ! class_exists( 'eXc_File_Upload_Field' ) )
{
	class eXc_File_Upload_Field extends eXc_Fields_Abstract
	{
		protected $default_skin = 'file_upload';
		protected $allowed_mime_types = array();
		protected $multiple_upload = false;
		
		function enqueue_files()
		{
			$this->eXc->html->load_js( 'exc-file-upload', $this->eXc->system_url('views/js/fields/file-upload.js') );
		}

		function add_actions()
		{
			add_action( 'post_edit_form_tag', array( &$this, 'post_edit_form_tag' ) );
			add_action( 'wp_ajax_exc_delete_file', array( &$this, 'wp_ajax_delete_file' ) );
		}
		
		private function save_files()
		{	
			if ( empty( $_POST['post_ID'] ) || ! $this->eXc->form->verify_nonce() 
					|| ! current_user_can( 'edit_posts', $_POST['post_ID'] ) )
			{
				wp_die( __( 'You don\'t have permission to edit this post', 'exc-framework' ) );
			}

			
			if ( ! function_exists( 'wp_handle_upload' ) )
			{
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
			}

			//Attach already uploaded files
			//verify that we still have these attachments
			if ( $uploaded = (array) get_post_meta( $_POST['post_ID'], $this->name, true ) )
			{				
				foreach ( $uploaded as $k => $v )
				{
					if ( ! wp_get_attachment_url( $v ) )
					{
						unset( $uploaded[ $k ] );
					}
				}

				// reset array keys
				$_POST[ $this->name ] = array_values( $uploaded );
			}
			
			// @TODO: validate uploaded file size limit it should not increase the defined limit in theme options or wordpress
			$files = $this->normalize_array( $_FILES[ $this->name ] );
			
			$files_limit = $this->config['el_attrs']['data-maxfileuploads'];
			
			//check the maximum files limit
			if ( ( count( $_POST[ $this->name ] ) + count( $files ) ) > $files_limit )
			{
				wp_die(
					sprintf(
						__( 'The file uploads reached max %d files limit.', 'exc-framework' ),
						$files_limit
					)
				);
			}
			
			//@TODO: update following functionality with media_handle_upload
			foreach ( $files as $file )
			{
				if ( empty( $file['type'] ) )
				{
					continue;
				}
				
				//verify mime type before upload
				if ( FALSE === in_array( $file['type'], $this->allowed_mime_types ) )
				{
					//@TODO: strong session error messages handler
					wp_die( sprintf( __('You must uploaded only supported %s files.', 'exc-framework'),
								implode(', ', array_keys($this->allowed_mime_types))) );
				}
				
				$upload = wp_handle_upload( $file, array( 'test_form' => false ) );

				if ( ! isset( $upload['file'] ) )
				{
					//stop uploading files on error
					return $this->eXc->validation->custom_error($this->name,
									sprintf( __('Unable to upload "%s", Possible file upload attack.', 'exc-framework'), $file['name']));
				}
				
				$filename = $upload['file'];
				
				$attachment = array(
					'post_mime_type' => $upload['type'],
					'guid'           => $upload['url'],
					'post_parent'    => $_POST['post_ID'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
					'post_content'   => '',
				);
				
				$id = wp_insert_attachment( $attachment, $filename, $_POST['post_ID'] );
				
				if ( ! is_wp_error( $id ) )
				{
					wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $filename ) );
					
					//Hack post so we can validate it through validation class
					if ( $files_limit == 1 )
					{
						$_POST[ $this->name ] = $id;
					} else
					{
						$_POST[$this->name][] = $id;
					}

				} else
				{
					return $this->eXc->validation->custom_error($this->name, 
									sprintf( __('There was an error uploading the image.', 'exc-framework'), $filename));
				}
			}
		}
		
		function post_edit_form_tag()
		{
			echo ' enctype="multipart/form-data"';
		}

		function wp_ajax_delete_file()
		{
			//return if it's not related to this field
			if( ! isset($_POST[$this->name])) return;
			
			$post_id = exc_kv($_POST, 'post_id', 0);
			$field_name = exc_kv($_POST, 'field_name', $this->name);
			$force_delete = $this->eXc-kvalue($_POST, 'delete_force', 0);
			
			if( ! $attachment_id = exc_kv($_POST, $this->name))
					wp_send_json_error( __('Invalid request to delete file, possible hacking attempt.', 'exc-framework' ) );
				
			check_ajax_referer("exc-delete-file_{$field_name}");
			
			//@TODO: move all string message to language file
			if( ! $attachments = get_post_meta($post_id, $field_name, true)) //trigger error on empty
				wp_send_json_error( __('Unable to delete file, possible hacking attempt.', 'exc-framework'));
			
			//@TODO: add multiple file upload support in config file
			if(is_array($attachments))
			{
				//search for key
				if(($key = array_search($attachment_id, $attachments)) === false)
				{
					wp_send_json_error( __( 'Invalid request to delete file, possible hacking attempt.', 'exc-framework' ) );
				}
				
				unset( $attachments[ $key ] );
				
				if ( count( $attachments ) )
				{
					update_post_meta($post_id, $this->name, $attachments);
					wp_send_json_success();
				}
			}
			
			if($force_delete && ! wp_delete_attachment($attachment_id)) 
				wp_send_json_error( __('Invalid request to delete file, possible hacking attempt.', 'exc-framework'));
			
			delete_post_meta($post_id, $this->name, $attachment_id);
			
			wp_send_json_success();
		}

		function html( $return = false )
		{
			$this->get_uploaded_files();
			
			return $this->apply_skin($return);
		}
		
		protected function skin_file_upload($return = false)
		{
			$i18n_addmore  = apply_filters('exc_file_add_string', _x( '+ Add new file', 'extracoding upload field', 'exc-framework'));
			
			$this->config['markup'] .= sprintf('<input %s />', exc_parse_atts($this->config['attrs']));
			
			/** Attach already uploaded files list */
			$this->get_uploaded_files();
			
			if($this->multiple_upload)
			{
				$this->config['markup'] .= sprintf('<a href="#" class="exc-add-file"><strong>%s</strong></a>', $i18n_addmore);
			}
			
			return $this->skin_form_group($return);
		}
		
		
		function get_uploaded_files()
		{
			//@TODO: capabilities check
			$this->config['markup'] .= sprintf('<ul %s>', exc_parse_atts( $this->config['el_attrs'] ) );
			
			$i18n_delete = apply_filters('exc_file_delete_string', _x('Delete', 'extracoding upload field', 'exc-framework'));
			$i18n_edit = apply_filters('exc_file_edit_string', _x('Edit', 'extracoding upload field', 'exc-framework'));
			
			foreach((array) $this->set_value() as $attachment_id)
			{
				if ( ! wp_get_attachment_url( $attachment_id ) )
				{
					continue;
				}
				
				$this->config['markup'] .= sprintf(
							'<li id="item_%d">
								<div class="media">
									
									<!-- Attahment thumb -->
									<div class="pull-left">%s</div>
									
									<div class="media-body">
										<a href="%s" target="_blank">%s</a>
										<p>%s</p>
										<a class="exc-file-edit" href="%s" target="_blank">%s</a>
										<a class="exc-file-delete" href="#" data-attachment_id="%s">%s</a>
									</div>
								</div>

								<input type="hidden" name="%s[]" value="%d" />
							</li>',
							$attachment_id,
							wp_get_attachment_image($attachment_id, array(80,80), true),
							wp_get_attachment_url($attachment_id),
							get_the_title($attachment_id),
							get_post_mime_type($attachment_id),
							get_edit_post_link($attachment_id),
							$i18n_edit,
							$attachment_id,
							$i18n_delete,
							$this->config['el_attrs']['data-field_name'],
							$attachment_id
						);
			}
			
			$this->config['markup'] .= '</ul>';
		}

		private function normalize_array( $files )
		{
			$output = array();
			foreach($files as $key => $list)
			{
				foreach($list as $index=>$value)
				{
					$output[$index][$key] = $value;
				}
			}
			
			return $output;
		}
		
		protected function normalize_data()
		{
			//Normalize Mime types
			if ( isset( $this->config['allowed_mime_types'] ) )
			{
				//wordpress supported mime types list
				$wp_allowed_mimes = get_allowed_mime_types();

				if ( ! is_array( $this->config['allowed_mime_types'] ) )
				{
					$this->config['allowed_mime_types'] = array( $this->config['allowed_mime_types'] );
				}
				
				foreach ( $this->config['allowed_mime_types'] as $mime )
				{
					if ( empty( $mime ) )
					{
						continue;
					}
					
					$is_mime = strstr( $mime, '/' );

					foreach ( $wp_allowed_mimes as $k => $v )
					{
						if ( $is_mime )
						{
							if ( $v == $mime )
							{
								$this->allowed_mime_types[ $k ] = $v;
								break;
							}
							
						}elseif ( FALSE !== strstr( $v, $mime.'/' ) )
						{
							$this->allowed_mime_types[ $k ] = $v;
						}
					}
				}

				unset( $this->config['allowed_mime_types'] );
			}
			
			if( isset( $this->config['el_attrs']['class'] ) && FALSE === strstr( $this->config['el_attrs']['class'], 'exc-preview-thumb' ) )
			{
				$this->config['el_attrs']['class'] .= ' exc-preview-thumb';
			}

			//Normalize element attributes
			$this->config['el_attrs'] = wp_parse_args(
											exc_kv( $this->config, 'el_attrs', array() ),
												array(
													'class' 				=> 'exc-preview-thumb',
													'data-upload_limit' 	=> 0,
													'data-allowed_mimes' 	=> implode( ',', $this->allowed_mime_types ),
													//'data-field_name' 		=> $this->name,
													'data-field_name'		=> $this->config['attrs']['name']
												)
										);
			
			//Check if we have multiple file upload support
			if( $this->config['el_attrs']['data-upload_limit'] != '1' )
			{
				$this->multiple_upload = true;
			}
			
			//append input class name
			$this->normalize_class($this->config['attrs']['class']);
			
			$this->config['attrs'] = wp_parse_args(
										$this->config['attrs'],
										array(
											'name' => $this->name . '[]',
											'type' => 'file',
										)
									);
			
			//Save files
			$files = array_filter( exc_kv( $_FILES, array( $this->name, 'name' ), array() ) );
			
			if ( $files && $this->config['type'] == 'file_upload' )
			{
				$this->save_files();
			}
		}
		
		function normalize_class(&$classes, $new_values = array('exc-file-upload'))
		{
			if(empty($new_values)) return;
			
			if( ! is_array($classes)) $classes = array_filter(explode(' ', $classes));
			
			$classes = implode(' ', array_merge($classes, $new_values));
			
		}
	}
}
