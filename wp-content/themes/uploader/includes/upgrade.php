<?php defined('ABSPATH') OR die('restricted access');

if ( ! is_admin() && FALSE === ( in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) )
        && ! isset( $_GET['interim-login'] ) ) )
{
    wp_die( "The theme requires upgrade, please login to admin panel." );
}

if ( ! class_exists('eXc_Easy_Upgrader') )
{
    class eXc_Easy_Upgrader
    {
        /**
         * Extracoding Instance
         *
         * @since 1.0
         * @var object
         */
        private $eXc;

        /**
         * List Of Updates
         *
         * @since 1.0
         * @var array
         */
        private $updates = array();

        /**
         * Active Task Information
         *
         * @since 1.0
         * @var array
         */
        private $active_task = array();

        /**
         * Update Prefix
         *
         * @since 1.0
         * @var string
         */
        private $update_option_name;

        /**
         * Update Method Name
         *
         * @since 1.0
         * @var string
         */
        private $update_method_name;

        /**
         * Theme Version Option Name
         *
         * @since 1.0
         * @var string
         */
        private $product_version_name;

        function __construct( &$eXc )
        {
            global $wp_filter;

            // Make sure the Depreciated theme plugin is not working
            if ( ! empty( $wp_filter['after_setup_theme'] ) )
            {
                foreach ( $wp_filter['after_setup_theme'] as $parent_filter_key => $filters )
                {
                    foreach ( $filters as $filter_key => $filter )
                    {
                        if ( is_a( $filter['function'][0], 'eXc_Uploader_Theme_Plugin' ) )
                        {
                            unset( $wp_filter['after_setup_theme'][ $parent_filter_key ] );
                        }
                    }
                }
            }

            if ( ! empty( $wp_filter['widgets_init'] ) )
            {
                foreach ( $wp_filter['widgets_init'] as $parent_filter_key => $filters )
                {
                    foreach ( $filters as $filter_key => $filter )
                    {
                        if ( is_a( $filter['function'][0], 'eXc_Uploader_Theme_Plugin' ) )
                        {
                            unset( $wp_filter['widgets_init'][ $parent_filter_key ] );
                        }
                    }
                }
            }

            $this->eXc =& $eXc;

            $product_version_id = ( method_exists( $this->eXc, 'get_product_version' ) ) ? $this->eXc->get_product_version() : '2.2';
            $this->notice_option_name = $this->eXc->get_product_name() . '-' .  $product_version_id . '-' . 'notice';

            if ( get_option( $this->notice_option_name ) != 'off' )
            {
                add_action( 'admin_notices', array( &$this, 'display_upgrade_notice' ) );
                add_action( 'admin_footer', array( &$this, 'print_inline_script' ) );
            }

            // Make sure that the exc-uploader-theme plugin is not active
            //$active_plugins = get_option( 'active_plugins' );

            // To Avoid Extracoding Uploader Theme Plugin Error load the latest helper file
            require_once get_template_directory() . '/includes/thirdparty/exc-framework/functions/helpers.php';
            // Set user-defined error handler function

            // Register Menu
            add_action( 'admin_menu', array( &$this, 'add_menu_page' ) );

            add_action( 'wp_ajax_uploader_dismiss_upgrade_notice', array( &$this, 'hide_upgrade_notice' ) );
            add_action( 'wp_ajax_exc-theme-upgrade-task', array( &$this, 'process_task' ) );
        }

        public function hide_upgrade_notice()
        {
            update_option( $this->notice_option_name, 'off' );
        }

        public function process_task()
        {
            $secret_key = ( ! empty( $_POST['secret_key'] ) ) ? $_POST['secret_key'] : '';
            $action_id = ( ! empty( $_POST['action_id'] ) ) ? $_POST['action_id'] : '';
            $task_id = ( ! empty( $_POST['task_id'] ) ) ? $_POST['task_id'] : '';

            if ( ! $secret_key || ! $action_id || ! $task_id
                    || ! wp_verify_nonce( $secret_key, 'exc-theme-upgrade' ) )
            {
                $this->json_send_error( __("Invalid Request! Please refresh page and try again.", "exc-uploader-theme") );
            }

            // Load updates list
            $this->load_upgrader();

            if ( ! isset( $this->updates[ $task_id ] ) )
            {
                $this->json_send_error( __("Invalid Request!", "exc-uploader-theme") );
            }

            // Make sure that previous task is marked as done
            $this->active_task = array();

            foreach ( $this->updates as $update_id => $update )
            {
                if ( $update_id == $task_id )
                {
                    $this->active_task = $update;
                    break;
                }

                $status = ( ! empty( $update['status'] ) ) ? $update['status'] : false;

                if ( ! $status )
                {
                    $this->json_send_error( __("You must have to perform the update steps in order.", "exc-uploader-theme") );
                }
            }

            if ( $this->active_task['status'] )
            {
                $this->json_send_error( __('This task is already processed', 'exc-uploader-theme') );
            }

            // Run the action
            $task_method_name = $this->update_method_name . '_' . $task_id;

            if ( empty( $this->active_task['actions'][ $action_id ] ) || ! method_exists( $this, $task_method_name ) )
            {
                $this->json_send_error( __("Invalid action, please try again", "exc-uploader-theme") );
            }

            if ( ! $this->{ $task_method_name }( $action_id ) )
            {
                $this->json_send_error( __('Update failed', 'exc-uploader-theme' ) );
            }

            update_option( $this->active_task['db_key'], TRUE, 'no' );

            unset( $this->updates[ $task_id ] );

            //$product_version_name = $this->eXc->get_product_name();
            $product_version_name = 'exc-uploader-theme';

            if ( empty( $this->updates ) )
            {
                $product_version_name = $product_version_name ? $product_version_name . '-version' : 'exc-uploader-theme-version';

                update_option( $product_version_name, 2.2 );

                exc_success( array( 'redirect_to' => admin_url( 'themes.php?page=exc-theme-options' ) ) );
            }

            // @TODO: update database
            exc_success('');
        }

        public function add_menu_page()
        {
            if ( ! empty( $_GET['page'] ) && $_GET['page'] == 'exc-theme-easy-upgrade' )
            {
                add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_files' ) );
            }

            add_theme_page( 'Theme Installer', 'Extracoding Theme Installer', 'manage_options', 'exc-theme-easy-upgrade', array( &$this, 'load_upgrader' ) );
        }

        public function enqueue_files()
        {
            wp_enqueue_script( 'thickbox' );
            wp_enqueue_style( 'thickbox' );

            wp_enqueue_style( 'exc-theme-installer', get_template_directory_uri() . '/includes/extensions/theme_options/views/css/upgrade.css' );
            wp_enqueue_script( 'exc-theme-installer', get_template_directory_uri() . '/includes/extensions/theme_options/views/js/upgrade.js', array('jquery'), false, true );
        }

        public function load_upgrader()
        {
            // Old Version
            $product_name = $this->eXc->get_product_name();
            $product_name = trim( str_replace( array( 'exc-', '-theme' ), '', $product_name ) );

            $this->product_version_name = $product_name . '-version';
            $db_version_id = get_option( $this->product_version_name, '1.0' );

            // Call the upgrade based on the version id
            //if ( ! function_exists( 'exc_to_slug' ) )
            //{
                //sanitize_title( $title, $fallback_title = '', $context = 'save' )
            //}

            $this->update_method_name = 'upgrade_' . str_replace( '.', '_', $db_version_id );

            // Do nothing if the upgrade is not available
            if ( ! method_exists( $this, $this->update_method_name ) )
            {
                wp_die( __("The theme upgrade is failed, please contact the theme provider, the quick fix is to use your backup.", "exc-uploader-theme") );
            }

            $this->update_option_name = $product_name . '_' . $this->update_method_name;

            // Always Caution for backup
            $backup_db_key = $this->update_option_name . '_backup';

            if ( ! $backup_status = get_option( $backup_db_key ) )
            {
                $this->updates['backup'] = array(
                                'type'      => 'new',
                                'task'      => __('<strong>Disclaimer:</strong> It is highly recommended that you take backup of your website and database, as we will take no responsiblity of any data loss due to the failure in upgrade process. We are working hard for an automatic backup system but until that you have to do it manually or by using any third party plugin with your sole responsibility.', 'exc-uploader-theme'),

                                'actions'   => array(
                                                'mark_task' => esc_html__('Mark as Done', 'exc-uploader-theme'),
                                                //'backup_cp_instructions' => esc_html__('Watch cPanel Backup Instructions', 'exc-uploader-theme'),
                                                //'backup_plugin_instructions' => esc_html__('Watch Plugin Backup Instructions', 'exc-uploader-theme'),
                                            ),

                                'db_key'    => $backup_db_key,
                                'status'    => $backup_status
                            );
            }

            // Call the upgrade function
            // @TODO: Automatically call all latest upgrade methods
            $this->{ $this->update_method_name }();

            if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )
            {
                if ( empty( $this->updates ) ) {

                    $product_version_name = ( ! empty( $product_version_name ) ) ? $product_version_name . '-version' : 'exc-uploader-theme-version';

                    update_option( $product_version_name, 2.2 );
                    wp_die( '<strong>Redirecting ... if failed then refresh page</strong><script type="text/javascript">window.setTimeout(function(){ window.location = " ' . admin_url( 'themes.php?page=exc-theme-options' ) . ' "; }, 0);</script>');
                }

                $this->load_view();
            }
        }

        private function load_view()
        {
            $title = esc_html__( 'Few Steps to finish your upgrade...', 'exc-uploader-theme' );
            $updates =& $this->updates;

            require get_template_directory() . '/includes/extensions/theme_options/views/theme-upgrade.php';
        }

        private function upgrade_1_0()
        {
            $exc_framework_version = 0;

            if ( defined( 'EXTRACODING_FRAMEWORK_VERSION' ) && EXTRACODING_FRAMEWORK_VERSION )
            {
                $exc_framework_version = EXTRACODING_FRAMEWORK_VERSION;
            }

            // Check if framework upgrade is required
            if ( $exc_framework_version != '2.2' )
            {
                $framework_db_key = $this->update_option_name . '_exc-framework';

                $this->updates['exc_framework'] = array(
                            'type'      => 'update',
                            'task'      => esc_html__('Update Extracoding Framework to version 2.2.', 'exc-uploader-theme'),
                            'actions'   => array( 'tgm-framework-update' => array( 'label'  => esc_html__( 'Update with TGM Plugin', 'exc-uploader-theme' ), 'link' => admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) ),
                            'db_key'    => $framework_db_key,
                            'status'    => get_option( $this->update_option_name . '_exc-framework' )
                        );
            }

            if ( ! function_exists( 'is_plugin_active' ) )
            {
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }

            if ( is_plugin_active( 'exc-uploader-theme/plugin_info.php') )
            {
                $plugin_db_key = $this->update_option_name . '_uploader-plugin';

                $this->updates['uploader_plugin'] = array(
                            'type'  => 'update',
                            'task'  => sprintf(
                                            esc_html__('Delete "%s" as all files related to this plugin are moved in uploader/includes directory, if in case you made some custom changes in plugin files then copy the updated files in child theme at the same location under includes directory.', 'exc-uploader-theme'),
                                            '<strong>Uploader Wordpress Theme Plugin</strong>'
                                        ),

                            'actions'   => array( 'tgm-framework-update' => array( 'label' => esc_html__( 'Delete in Plugins Section', 'exc-uploader-theme' ), 'link' => admin_url( 'plugins.php' ) ) ),
                            'db_key'    => $plugin_db_key,
                            'status'    => get_option( $plugin_db_key )
                    );
            }

            if ( get_template_directory() !== get_stylesheet_directory() )
            {
                $child_theme_db_key = $this->update_option_name . '_child-text-domain';

                if ( ! get_option( $child_theme_db_key ) )
                {
                    $this->updates['child_text_domain'] = array(
                            'type'      => 'update',
                            'task'      => sprintf(
                                                esc_html__('The text domain THEME_NAME is deperciated in favour of \'exc-uploader-theme\', please make sure that you changed it in your child theme or custom exc-uploader-theme plugin files. The easiest way is to use any code editor and search for string THEME_NAME in directory "%s" and replace it with \'exc-uploader-theme\'.', 'exc-uploader-theme'),
                                                '<strong>' . basename( get_stylesheet_directory() ) . '</strong>'
                                            ),

                            'actions'   => array( 'mark_task' => esc_html__( 'Mark as Done', 'exc-uploader-theme' ) ),
                            'db_key'    => $child_theme_db_key,
                            'status'    => FALSE
                    );
                }
            }

            // Database Upgrade
            $db_upgrade_key = $this->update_option_name . '_db-upgrade';

            if ( ! get_option( $db_upgrade_key ) )
            {
                $this->updates['db_upgrade'] = array(
                            'type'      => 'fix',
                            'notice'    => '<div class="error"><p><strong>WARNING:</strong> Make sure that you already took backup of your website and database.</p></div>',
                            'task'      => esc_html__('All theme options, taxonomies and widgets custom fields structure is updated to reduce the processing speed and fix PHP suhosin post array limit issue.', 'exc-uploader-theme'),
                            'actions'   => array( 'exc_db_upgrade' => esc_html__( 'Run Task', 'exc-uploader-theme' ) ),
                            'db_key'    => $db_upgrade_key,
                            'status'    => FALSE
                    );
            }
        }

        private function upgrade_1_0_child_text_domain( $action )
        {
            if ( $action == 'mark_task' )
            {
                return true;
            }
        }

        private function upgrade_1_0_backup( $action )
        {
            if ( $action == 'mark_task' )
            {
                return true;
            } elseif ( $action == 'backup_cp_instructions' )
            {
                exc_success( "Send the cpanel instructions" );
            }
        }

        private function upgrade_1_0_db_upgrade( $action )
        {
            global $wpdb;

            @set_time_limit(0);

            if ( $action == 'exc_db_upgrade' )
            {
                // Update old color values
                if ( ! get_option( 'exc-uploader-theme-version' ) ) {

                    $style_options = array(
                            'exc-uploader-theme_header_center_style',
                            'exc-uploader-theme_header_classic_style',
                            'exc-uploader-theme_header_default_style',
                            'exc-uploader-theme_style'
                        );

                    foreach ( $style_options as $style_option_name )
                    {
                        $style_option_array_key = $style_option_name . '_array';
                        $saved_settings = get_option( $style_option_array_key );

                        if ( $saved_settings ) {

                            $style_settings = '';

                            foreach( $saved_settings as $key => $values )
                            {
                                foreach ( $values as $k => $v )
                                {
                                    if ( $k == 'color' || stristr( $k, 'background-color' )
                                            || $k == 'border-color' )
                                    {
                                        unset( $saved_settings[ $key ][ $k ] );
                                    }
                                }

                                if ( ! empty( $saved_settings[ $key ] ) ) {
                                    $style_settings .= $key . " {\n\t" . implode('', $saved_settings[ $key ]) . "\n}\n";
                                }
                            }

                            update_option($style_option_array_key, $saved_settings);
                            update_option($style_option_name, $style_settings);
                        }
                    }
                }

                $db_names = array(
                            'general_settings'      => 'mf_general_settings',
                            'top_notifications'     => 'mf_notifications',
                            'header_settings'       => 'mf_header_settings',
                            'layout_settings'       => 'mf_layout',
                            'style_settings'        => 'mf_style_settings',
                            'manage_sidebars'       => 'mf_sidebars',
                            'member_settings'       => 'mf_member_settings',
                            'uploader_settings'     => 'mf_uploader_settings',
                            'mail_settings'         => 'mf_mail_settings',
                            'social_media_settings' => 'mf_social_media',
                            'footer_settings'       => 'mf_footer_settings'
                        );

                foreach ( $db_names as $file_name => $db_name )
                {
                    $old_db_settings = get_option( $db_name );
                    $new_db_settings = $this->upgrade_1_0_old_db_structure( $file_name );

                    if ( ! empty( $new_db_settings ) )
                    {
                        //$updated_settings = array();

                        foreach ( $new_db_settings as $old_key => $new_key )
                        {
                            $old_db_key = $file_name . '-' . $old_key;

                            if ( $new_key == '_depreciated_' )
                            {
                                if ( isset( $old_db_settings[ $old_db_key ] ) )
                                {
                                    unset( $old_db_settings[ $old_db_key ] );
                                }

                                continue;
                            }

                            $old_value = '';

                            if ( isset( $old_db_settings[ $old_db_key ] ) )
                            {
                                $old_value = $old_db_settings[ $old_db_key ];
                                unset( $old_db_settings[ $old_db_key ] );
                            }

                            if ( ! is_array( $new_key ) )
                            {
                                $new_key = array( 'name' => $new_key, 'value' => $old_value );
                            } elseif ( ! isset( $new_key['value'] ) )
                            {
                                $new_key['value'] = $old_value;
                            }


                            if ( isset( $old_db_settings[ $new_key['name'] ] ) )
                            {
                                continue;
                            }

                            $old_db_settings[ $new_key['name'] ] = $new_key[ 'value' ];
                        }

                        update_option( $db_name, $old_db_settings );
                    }
                }

                // Update Taxonomy
                // $taxonomy_settings = $wpdb->get_results(
                //                          "
                //                              SELECT * FROM {$wpdb->options}
                //                              WHERE option_name like 'taxonomy_meta_%'
                //                          "
                //                      );
                                        
                // if ( ! empty( $taxonomy_settings ) )
                // {
                //  foreach ( $taxonomy_settings as $settings )
                //  {
                //      $option_value = maybe_unserialize( $settings->option_value );

                //      //$new_settings = $option_value;

                //      foreach ( (array) $option_value as $key => $value )
                //      {
                //          if ( FALSE === strpos( $key, 'exc_layout_' ) )
                //          {
                //              $option_value[ 'exc_layout_' . $key ] = $value;

                //              unset( $option_value[ $key ] );
                //          }
                //      }

                //      update_option( $settings->option_name, $option_value );
                //  }
                // }

                
                // Update Widgets

                // Update MetaBoxes
                $layout_settings = $wpdb->get_results(
                                        $wpdb->prepare(
                                                "
                                                    SELECT * FROM {$wpdb->postmeta}
                                                    WHERE meta_key = %s
                                                ",

                                                'mf_layout'

                                            )
                                    );

                if ( ! empty( $layout_settings ) )
                {
                    foreach ( $layout_settings as $settings )
                    {
                        $meta_value = maybe_unserialize( $settings->meta_value );

                        $new_settings = $meta_value;

                        foreach ( (array) $meta_value as $key => $value )
                        {
                            if ( FALSE !== strpos( $key, 'block-layout-' ) )
                            {
                                $new_key = str_ireplace( 'block-layout-', 'exc_layout_', $key );

                                $new_settings[ $new_key ] = $value;

                                unset( $new_settings[ $key ] );
                            }
                        }

                        update_post_meta( $settings->post_id, 'mf_layout', $new_settings );
                    }
                }

                unset( $layout_settings );

                $taxonomies = $wpdb->get_results(
                                        $wpdb->prepare(
                                                "
                                                    SELECT * FROM {$wpdb->options}
                                                    WHERE option_name like %s
                                                ",

                                                'taxonomy_meta_%'

                                            )
                                    );

                if ( ! empty( $taxonomies ) )
                {
                    foreach ( $taxonomies as $taxonomy )
                    {
                        $option_value = maybe_unserialize( $taxonomy->option_value );

                        $new_settings = $option_value;

                        foreach ( (array) $option_value as $key => $value )
                        {
                            if ( FALSE !== strpos( $key, 'wpblock-layout-' ) )
                            {
                                $new_key = str_ireplace( 'wpblock-layout-', 'exc_layout_', $key );

                                $new_settings[ $new_key ] = $value;

                                unset( $new_settings[ $key ] );
                            }
                        }

                        update_option( $taxonomy->option_name, $new_settings, 'no' );
                    }
                }
            }

            return true;
        }

        private function upgrade_1_0_old_db_structure( $basename )
        {
            $old_db_structure['general_settings']['block-favicon_block-favicon'] = '_depreciated_';
            $old_db_structure['general_settings']['block-favicon_block-iphone'] = '_depreciated_';
            $old_db_structure['general_settings']['block-favicon_block-ipad'] = '_depreciated_';
            $old_db_structure['general_settings']['block-favicon_block-iphone_retina'] = '_depreciated_';
            $old_db_structure['general_settings']['block-favicon_block-ipad_retina'] = '_depreciated_';

            $old_db_structure['general_settings']['block-additional_block-header_code'] = '_depreciated_';
            $old_db_structure['general_settings']['block-additional_block-footer_code'] = '_depreciated_';
            $old_db_structure['general_settings']['block-tracking_block-code'] = '_depreciated_';

            $old_db_structure['general_settings']['block-layout_settings-structure'] = array( 'name' => 'structure', 'value' => 'full-width' );
            $old_db_structure['general_settings']['block-layout_settings-bg_pattern'] = 'bg_pattern';

            //Notifcations Settings
            $old_db_structure['top_notifications']['block-settings-status'] = 'status';
            $old_db_structure['top_notifications']['block-settings-random'] = 'random';
            $old_db_structure['top_notifications']['block-settings-close_btn'] = 'close_btn';

            // manage notifications block settings
            $old_db_structure['top_notifications']['block-notifications-dynamic-top_notifications'] = 'top_notifications';
            $old_db_structure['top_notifications']['block-style_settings-bg_color'] = 'bg_color';
            $old_db_structure['top_notifications']['block-style_settings-text_color'] = 'text_color';
            $old_db_structure['top_notifications']['block-style_settings-btn_bg'] = 'btn_bg';
            $old_db_structure['top_notifications']['block-style_settings-btn_text_color'] = 'btn_text_color';
            $old_db_structure['top_notifications']['block-style_settings-btn_color_hover'] = 'btn_text_hover_color';

            // Logo Block
            $old_db_structure['header_settings']['block-header-logo'] = 'logo';
            $old_db_structure['header_settings']['block-header-logo_w'] = 'logo_width';
            $old_db_structure['header_settings']['block-header-logo_h'] = 'logo_height';

            // Default Header settings
            // Header Sticky Status

            $old_db_structure['header_settings']['block-style_settings-sticky'] = '_depreciated_';
            $old_db_structure['header_settings']['block-style_settings-bg_image'] = '_depreciated_';
            $old_db_structure['header_settings']['block-style_settings-bg_color'] = '_depreciated_';
            $old_db_structure['header_settings']['block-style_settings-header_opacity'] = '_depreciated_';
            $old_db_structure['header_settings']['block-style_settings-header_heading_color'] = '_depreciated_';
            $old_db_structure['header_settings']['block-style_settings-header_textcolor'] = '_depreciated_';

            $old_db_structure['header_settings']['block-header_default-tabs-general_settings-is_sticky'] = 'header_is_sticky';
            $old_db_structure['header_settings']['block-header_default-tabs-general_settings-bg_image'] = 'header_bg_image';
            $old_db_structure['header_settings']['block-header_default-tabs-general_settings-bg_color'] = 'header_bg_color';
            $old_db_structure['header_settings']['block-header_default-tabs-general_settings-transparency'] = 'header_transparency';
            $old_db_structure['header_settings']['block-header_default-tabs-menu_settings-status'] = 'header_menu_status';
            $old_db_structure['header_settings']['block-header_default-tabs-menu_settings-text_color'] = 'header_menu_text_color';
            $old_db_structure['header_settings']['block-header_default-tabs-menu_settings-text_hove_color'] = 'header_menu_hover_color';
            $old_db_structure['header_settings']['block-header_default-tabs-menu_settings-padding_top'] = 'header_menu_padding_top';
            $old_db_structure['header_settings']['block-header_default-tabs-menu_settings-padding_right'] = 'header_menu_padding_right';
            $old_db_structure['header_settings']['block-header_default-tabs-menu_settings-padding_bottom'] = 'header_menu_padding_bottom';
            $old_db_structure['header_settings']['block-header_default-tabs-menu_settings-padding_left'] = 'header_menu_padding_left';
            $old_db_structure['header_settings']['block-header_default-tabs-member_controls-status'] = 'header_member_status';
            $old_db_structure['header_settings']['block-header_default-tabs-member_controls-icons_color'] = 'header_member_icons_color';
            $old_db_structure['header_settings']['block-header_default-tabs-member_controls-border_color'] = 'header_member_border_color';
            $old_db_structure['header_settings']['block-header_default-tabs-member_controls-bg_color'] = 'header_member_bg_color';
            $old_db_structure['header_settings']['block-header_default-tabs-member_controls-bg_opacity'] = 'header_member_bg_opacity';
            $old_db_structure['header_settings']['block-header_default-tabs-member_controls-text_color'] = 'header_member_text_color';
            $old_db_structure['header_settings']['block-header_default-tabs-top_bar_settings-status'] = 'header_topbar_status';
            $old_db_structure['header_settings']['block-header_default-tabs-top_bar_settings-email_addr'] = 'header_topbar_email_addr';
            $old_db_structure['header_settings']['block-header_default-tabs-top_bar_settings-phone_number'] = 'header_topbar_phone';
            $old_db_structure['header_settings']['block-header_default-tabs-top_bar_settings-social_icons'] = 'header_topbar_social_icons';
            $old_db_structure['header_settings']['block-header_default-tabs-top_bar_settings-color'] = 'header_topbar_color';
            $old_db_structure['header_settings']['block-header_default-tabs-top_bar_settings-bg_color'] = 'header_topbar_bg_color';
            $old_db_structure['header_settings']['block-header_default-tabs-top_bar_settings-bg_opacity'] = 'header_topbar_bg_opacity';

            // Header Center Settings
            $old_db_structure['header_settings']['block-header_center-tabs-general_settings-bg_image'] = 'hc_bg_image';
            $old_db_structure['header_settings']['block-header_center-tabs-general_settings-bg_color'] = 'hc_bg_color';
            $old_db_structure['header_settings']['block-header_center-tabs-general_settings-transparency'] = 'hc_transparency';
            $old_db_structure['header_settings']['block-header_center-tabs-menu_settings-status'] = 'hc_menu_status';
            $old_db_structure['header_settings']['block-header_center-tabs-menu_settings-text_color'] = 'hc_menu_text_color';
            $old_db_structure['header_settings']['block-header_center-tabs-menu_settings-text_hove_color'] = 'hc_menu_hover_color';
            $old_db_structure['header_settings']['block-header_center-tabs-menu_settings-padding_top'] = 'hc_menu_padding_top';
            $old_db_structure['header_settings']['block-header_center-tabs-menu_settings-padding_right'] = 'hc_menu_padding_right';
            $old_db_structure['header_settings']['block-header_center-tabs-menu_settings-padding_bottom'] = 'hc_menu_padding_bottom';
            $old_db_structure['header_settings']['block-header_center-tabs-menu_settings-padding_left'] = 'hc_menu_padding_left';
            $old_db_structure['header_settings']['block-header_center-tabs-member_controls-status'] = 'hc_member_status';
            $old_db_structure['header_settings']['block-header_center-tabs-member_controls-icons_color'] = 'hc_member_icons_color';
            $old_db_structure['header_settings']['block-header_center-tabs-member_controls-border_color'] = 'hc_member_border_color';
            $old_db_structure['header_settings']['block-header_center-tabs-member_controls-bg_color'] = 'hc_member_bg_color';
            $old_db_structure['header_settings']['block-header_center-tabs-member_controls-bg_opacity'] = 'hc_member_bg_opacity';
            $old_db_structure['header_settings']['block-header_center-tabs-member_controls-text_color'] = 'hc_member_text_color';
            $old_db_structure['header_settings']['block-header_center-tabs-top_bar_settings-status'] = 'hc_topbar_status';
            $old_db_structure['header_settings']['block-header_center-tabs-top_bar_settings-email_addr'] = 'hc_topbar_email_addr';
            $old_db_structure['header_settings']['block-header_center-tabs-top_bar_settings-phone_number'] = 'hc_topbar_phone';
            $old_db_structure['header_settings']['block-header_center-tabs-top_bar_settings-social_icons'] = 'hc_topbar_social_icons';
            $old_db_structure['header_settings']['block-header_center-tabs-top_bar_settings-color'] = 'hc_topbar_color';
            $old_db_structure['header_settings']['block-header_center-tabs-top_bar_settings-bg_color'] = 'hc_topbar_bg_color';
            $old_db_structure['header_settings']['block-header_center-tabs-top_bar_settings-bg_opacity'] = 'hc_topbar_bg_opacity';

            // Header Classic Settings
            $old_db_structure['header_settings']['block-header_classic-tabs-general_settings-bg_image'] = 'hcl_bg_image';
            $old_db_structure['header_settings']['block-header_classic-tabs-general_settings-bg_color'] = 'hcl_bg_color';
            $old_db_structure['header_settings']['block-header_classic-tabs-general_settings-transparency'] = 'hcl_transparency';
            $old_db_structure['header_settings']['block-header_classic-tabs-menu_settings-status'] = 'hcl_menu_status';
            $old_db_structure['header_settings']['block-header_classic-tabs-menu_settings-text_color'] = 'hcl_menu_text_color';
            $old_db_structure['header_settings']['block-header_classic-tabs-menu_settings-text_hove_color'] = 'hcl_menu_hover_color';
            $old_db_structure['header_settings']['block-header_classic-tabs-menu_settings-padding_top'] = 'hcl_menu_padding_top';
            $old_db_structure['header_settings']['block-header_classic-tabs-menu_settings-padding_right'] = 'hcl_menu_padding_right';
            $old_db_structure['header_settings']['block-header_classic-tabs-menu_settings-padding_bottom'] = 'hcl_menu_padding_bottom';
            $old_db_structure['header_settings']['block-header_classic-tabs-menu_settings-padding_left'] = 'hcl_menu_padding_left';
            $old_db_structure['header_settings']['block-header_classic-tabs-member_controls-status'] = 'hcl_member_status';
            $old_db_structure['header_settings']['block-header_classic-tabs-member_controls-icons_color'] = 'hcl_member_icons_color';
            $old_db_structure['header_settings']['block-header_classic-tabs-member_controls-border_color'] = 'hcl_member_border_color';
            $old_db_structure['header_settings']['block-header_classic-tabs-member_controls-bg_color'] = 'hcl_member_bg_color';
            $old_db_structure['header_settings']['block-header_classic-tabs-member_controls-bg_opacity'] = 'hcl_member_bg_opacity';
            $old_db_structure['header_settings']['block-header_classic-tabs-member_controls-text_color'] = 'hcl_member_text_color';
            $old_db_structure['header_settings']['block-header_classic-tabs-contact_details-status'] = 'hcl_contact_status';
            $old_db_structure['header_settings']['block-header_classic-tabs-contact_details-email_addr'] = 'hcl_contact_email_addr';
            $old_db_structure['header_settings']['block-header_classic-tabs-contact_details-phone_number'] = 'hcl_contact_phone';

            // Layout Settings
            // default Settings
            $old_db_structure['layout_settings']['block-default_settings-header'] = 'default_header';
            $old_db_structure['layout_settings']['block-default_settings-slider'] = 'default_slider';
            $old_db_structure['layout_settings']['block-default_settings-revslider_id'] = 'default_revslider_id';
            $old_db_structure['layout_settings']['block-default_settings-structure'] = 'default_structure';
            $old_db_structure['layout_settings']['block-default_settings-left_sidebar'] = 'default_left_sidebar';
            $old_db_structure['layout_settings']['block-default_settings-right_sidebar'] = 'default_right_sidebar';
            $old_db_structure['layout_settings']['block-default_settings-strip_content'] = '_depreciated_';
            $old_db_structure['layout_settings']['block-default_settings-download_btn'] = 'default_download_btn';

            // wordpress built-in template
            // Archive Template
            $old_db_structure['layout_settings']['block-settings-tabs-archives-header'] = 'archives_header';
            $old_db_structure['layout_settings']['block-settings-tabs-archives-slider'] = 'archives_slider';
            $old_db_structure['layout_settings']['block-settings-tabs-archives-revslider_id'] = 'archives_revslider_id';
            $old_db_structure['layout_settings']['block-settings-tabs-archives-structure'] = 'archives_structure';
            $old_db_structure['layout_settings']['block-settings-tabs-archives-left_sidebar'] = 'archives_left_sidebar';
            $old_db_structure['layout_settings']['block-settings-tabs-archives-right_sidebar'] = 'archives_right_sidebar';
            $old_db_structure['layout_settings']['block-settings-tabs-archives-post_type'] = 'archives_post_type';
            $old_db_structure['layout_settings']['block-settings-tabs-archives-show_filtration'] = 'archives_show_filtration';
            $old_db_structure['layout_settings']['block-settings-tabs-archives-active_view'] = 'archives_active_view';
            $old_db_structure['layout_settings']['block-settings-tabs-archives-columns'] = 'archives_columns';
            $old_db_structure['layout_settings']['block-settings-tabs-archives-list_columns'] = 'archives_list_columns';

            // Categories Template
            $old_db_structure['layout_settings']['block-settings-tabs-categories-force_settings'] = '_depreciated_';
            $old_db_structure['layout_settings']['block-settings-tabs-categories-header'] = 'categories_header';
            $old_db_structure['layout_settings']['block-settings-tabs-categories-slider'] = 'categories_slider';
            $old_db_structure['layout_settings']['block-settings-tabs-categories-revslider_id'] = 'categories_revslider_id';
            $old_db_structure['layout_settings']['block-settings-tabs-categories-structure'] = 'categories_structure';
            $old_db_structure['layout_settings']['block-settings-tabs-categories-left_sidebar'] = 'categories_left_sidebar';
            $old_db_structure['layout_settings']['block-settings-tabs-categories-right_sidebar'] = 'categories_right_sidebar';
            $old_db_structure['layout_settings']['block-settings-tabs-categories-post_type'] = 'categories_post_type';
            $old_db_structure['layout_settings']['block-settings-tabs-categories-show_filtration'] = 'categories_show_filtration';
            $old_db_structure['layout_settings']['block-settings-tabs-categories-active_view'] = 'categories_active_view';
            $old_db_structure['layout_settings']['block-settings-tabs-categories-columns'] = 'categories_columns';
            $old_db_structure['layout_settings']['block-settings-tabs-categories-list_columns'] = 'categories_list_columns';

            // Template Template
            $old_db_structure['layout_settings']['block-settings-tabs-tags-force_settings'] = '_depreciated_';
            $old_db_structure['layout_settings']['block-settings-tabs-tags-header'] = 'tags_header';
            $old_db_structure['layout_settings']['block-settings-tabs-tags-slider'] = 'tags_slider';
            $old_db_structure['layout_settings']['block-settings-tabs-tags-revslider_id'] = 'tags_revslider_id';
            $old_db_structure['layout_settings']['block-settings-tabs-tags-structure'] = 'tags_structure';
            $old_db_structure['layout_settings']['block-settings-tabs-tags-left_sidebar'] = 'tags_left_sidebar';
            $old_db_structure['layout_settings']['block-settings-tabs-tags-right_sidebar'] = 'tags_right_sidebar';
            $old_db_structure['layout_settings']['block-settings-tabs-tags-post_type'] = 'tags_post_type';
            $old_db_structure['layout_settings']['block-settings-tabs-tags-show_filtration'] = 'tags_show_filtration';
            $old_db_structure['layout_settings']['block-settings-tabs-tags-active_view'] = 'tags_active_view';
            $old_db_structure['layout_settings']['block-settings-tabs-tags-columns'] = 'tags_columns';
            $old_db_structure['layout_settings']['block-settings-tabs-tags-list_columns'] = 'tags_list_columns';

            // Search Template
            $old_db_structure['layout_settings']['block-settings-tabs-search-header'] = 'search_header';
            $old_db_structure['layout_settings']['block-settings-tabs-search-structure'] = 'search_structure';
            $old_db_structure['layout_settings']['block-settings-tabs-search-left_sidebar'] = 'search_left_sidebar';
            $old_db_structure['layout_settings']['block-settings-tabs-search-right_sidebar'] = 'search_right_sidebar';
            $old_db_structure['layout_settings']['block-settings-tabs-search-post_type'] = 'search_post_type';
            $old_db_structure['layout_settings']['block-settings-tabs-search-show_filtration'] = 'search_show_filtration';
            $old_db_structure['layout_settings']['block-settings-tabs-search-active_view'] = 'search_active_view';
            $old_db_structure['layout_settings']['block-settings-tabs-search-columns'] = 'search_columns';
            $old_db_structure['layout_settings']['block-settings-tabs-search-list_columns'] = 'search_list_columns';

            // Custom Pages Template
            // Browse Categories  Page Template
            $old_db_structure['layout_settings']['block-custom_pages-tabs-browse_categories-header'] = 'browse_categories_header';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-browse_categories-slider'] = 'browse_categories_slider';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-browse_categories-revslider_id'] = 'browse_categories_revslider_id';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-browse_categories-structure'] = 'browse_categories_structure';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-browse_categories-left_sidebar'] = 'browse_categories_left_sidebar';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-browse_categories-right_sidebar'] = 'browse_categories_right_sidebar';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-browse_categories-post_type'] = '_depreciated_';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-browse_categories-show_filtration'] = '_depreciated_';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-browse_categories-active_view'] = '_depreciated_';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-browse_categories-columns'] = 'browse_categories_columns';

            // Radio Genere Page Template
            $old_db_structure['layout_settings']['block-custom_pages-tabs-radio_genre-force_settings'] = '_depreciated_';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-radio_genre-header'] = 'genre_header';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-radio_genre-slider'] = 'genre_slider';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-radio_genre-revslider_id'] = 'genre_revslider_id';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-radio_genre-structure'] = 'genre_structure';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-radio_genre-left_sidebar'] = 'genre_left_sidebar';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-radio_genre-right_sidebar'] = 'genre_right_sidebar';

            // Contact Page Template
            $old_db_structure['layout_settings']['block-custom_pages-tabs-contact-header'] = 'contact_header';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-contact-structure'] = 'contact_structure';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-contact-left_sidebar'] = 'contact_left_sidebar';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-contact-right_sidebar'] = 'contact_right_sidebar';

            // User Page Template
            $old_db_structure['layout_settings']['block-custom_pages-tabs-users-header'] = 'users_header';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-users-slider'] = 'users_slider';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-users-revslider_id'] = 'users_revslider_id';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-users-structure'] = 'users_structure';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-users-left_sidebar'] = 'users_left_sidebar';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-users-right_sidebar'] = 'users_right_sidebar';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-users-user_roles'] = 'users_user_roles';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-users-show_filtration'] = 'users_show_filtration';
            $old_db_structure['layout_settings']['block-custom_pages-tabs-users-columns'] = 'users_columns';

            // Pagination Block
            $old_db_structure['layout_settings']['block-pagination-ajax_pagi'] = 'ajax_pagi';
            $old_db_structure['layout_settings']['block-pagination-ajax_blog_pagi'] = 'ajax_blog_pagi';
            $old_db_structure['layout_settings']['block-pagination-ajax_comments_pagi'] = 'ajax_comments_pagi';

            // Mange Sidebars
            $old_db_structure['manage_sidebars']['block-settings-dynamic-sidebars'] = 'sidebars';

            // Member settings
            $old_db_structure['member_settings']['block-member-member_ctrls'] = '_depreciated_';
            $old_db_structure['member_settings']['block-member-status'] = 'status';
            $old_db_structure['member_settings']['block-member-frontend_login'] = 'frontend_login';
            $old_db_structure['member_settings']['block-member-admin_bar'] = 'admin_bar';
            $old_db_structure['member_settings']['block-member-register_as'] = 'register_as'; 
            $old_db_structure['member_settings']['block-backgrounds-attachments'] = 'bg_attachments';
            $old_db_structure['member_settings']['block-strings-signin_btn'] = 'signin_btn';

            // Uploader Settings
            $old_db_structure['uploader_settings']['block-strings-or'] = '_depreciated_';
            $old_db_structure['uploader_settings']['block-settings-status'] = 'status';
            $old_db_structure['uploader_settings']['block-settings-posts_limit'] = 'posts_limit';
            $old_db_structure['uploader_settings']['block-settings-attachments_limit'] = 'attachments_limit';
            $old_db_structure['uploader_settings']['block-settings-featured_image'] = 'featured_image';
            $old_db_structure['uploader_settings']['block-settings-allowed_mime'] = 'allowed_mime';
            $old_db_structure['uploader_settings']['block-settings-post_status'] = 'post_status';
            $old_db_structure['uploader_settings']['block-settings-rename'] = 'rename';
            $old_db_structure['uploader_settings']['block-settings-prevent_duplicates'] = 'prevent_duplicates';
            $old_db_structure['uploader_settings']['block-settings-max_file_size'] = 'max_file_size';
            $old_db_structure['uploader_settings']['block-strings-heading'] = 'heading';
            $old_db_structure['uploader_settings']['block-strings-about'] = 'about';
            $old_db_structure['uploader_settings']['block-strings-btn'] = 'btn';
            $old_db_structure['uploader_settings']['block-strings-dropfiles'] = 'dropfiles';

            // Social Media Settings
            $old_db_structure['social_media_settings']['block-social-facebook'] = 'facebook';
            $old_db_structure['social_media_settings']['block-social-twitter'] = 'twitter';
            $old_db_structure['social_media_settings']['block-social-gplus'] = 'gplus';
            $old_db_structure['social_media_settings']['block-social-instagram'] = 'instagram';
            $old_db_structure['social_media_settings']['block-social-youtube'] = 'youtube';
            $old_db_structure['social_media_settings']['block-social-vimeo'] = 'vimeo';
            $old_db_structure['social_media_settings']['block-social-soundcloud'] = 'soundcloud';
            $old_db_structure['social_media_settings']['block-social-flickr'] = 'flickr';

            // Footer Settings
            $old_db_structure['footer_settings']['block-settings-is_sticky'] = 'is_sticky';
            $old_db_structure['footer_settings']['block-settings-copyright'] = 'copyright';
            $old_db_structure['footer_settings']['block-footer_style-bg_color'] = 'bg_color';
            $old_db_structure['footer_settings']['block-footer_style-r_bg_color'] = 'r_bg_color';
            $old_db_structure['footer_settings']['block-footer_style-text_color'] = 'text_color';
            $old_db_structure['footer_settings']['block-footer_style-menu_text_color'] = 'menu_text_color';

            // Mail Settings
            $old_db_structure['mail_settings']['block-sender-to'] = '_depreciated_';
            $old_db_structure['mail_settings']['block-sender-from_name'] = '_depreciated_';
            $old_db_structure['mail_settings']['block-sender-from_email'] = '_depreciated_';
            $old_db_structure['mail_settings']['block-sender-user_info'] = '_depreciated_';

            $old_db_structure['mail_settings']['block-general_settings-tabs-general_settings-to'] = 'to';
            $old_db_structure['mail_settings']['block-general_settings-tabs-general_settings-from_name'] = 'from_name';
            $old_db_structure['mail_settings']['block-general_settings-tabs-general_settings-from_email'] = 'from_email';
            $old_db_structure['mail_settings']['block-general_settings-tabs-smtp_settings-status'] = 'smtp_status';
            $old_db_structure['mail_settings']['block-general_settings-tabs-smtp_settings-SMTPAuth'] = 'smtp_auth';
            $old_db_structure['mail_settings']['block-general_settings-tabs-smtp_settings-Host'] = 'smtp_host';
            $old_db_structure['mail_settings']['block-general_settings-tabs-smtp_settings-Username'] = 'smtp_username';
            $old_db_structure['mail_settings']['block-general_settings-tabs-smtp_settings-Password'] = 'smtp_password';
            $old_db_structure['mail_settings']['block-general_settings-tabs-smtp_settings-Port'] = 'smtp_port';
            $old_db_structure['mail_settings']['block-general_settings-tabs-smtp_settings-SMTPDebug'] = 'smtp_debug';
            $old_db_structure['mail_settings']['block-general_settings-tabs-smtp_settings-SMTPSecure'] = 'smtp_secure';
            $old_db_structure['mail_settings']['block-emails-tabs-new_user-status'] = 'user_status';
            $old_db_structure['mail_settings']['block-emails-tabs-new_user-subject'] = 'user_subject';
            $old_db_structure['mail_settings']['block-emails-tabs-new_user-body'] = 'user_body';
            $old_db_structure['mail_settings']['block-emails-tabs-password_recovery-subject'] = 'recovery_subject';
            $old_db_structure['mail_settings']['block-emails-tabs-password_recovery-body'] = 'recovery_body';
            $old_db_structure['mail_settings']['block-user_emails-tabs-like_notification-status'] = 'like_status';
            $old_db_structure['mail_settings']['block-user_emails-tabs-like_notification-content_type'] = 'like_content_type';
            $old_db_structure['mail_settings']['block-user_emails-tabs-like_notification-subject'] = 'like_subject';
            $old_db_structure['mail_settings']['block-user_emails-tabs-like_notification-body'] = 'like_body';
            $old_db_structure['mail_settings']['block-user_emails-tabs-follow_notification-status'] = 'follow_status';
            $old_db_structure['mail_settings']['block-user_emails-tabs-follow_notification-content_type'] = 'follow_content_type';
            $old_db_structure['mail_settings']['block-user_emails-tabs-follow_notification-subject'] = 'follow_subject';
            $old_db_structure['mail_settings']['block-user_emails-tabs-follow_notification-body'] = 'follow_body';
            $old_db_structure['mail_settings']['block-user_emails-tabs-subscriber_notification-status'] = 'subscriber_status';
            $old_db_structure['mail_settings']['block-user_emails-tabs-subscriber_notification-content_type'] = 'subscriber_content_type';
            $old_db_structure['mail_settings']['block-user_emails-tabs-subscriber_notification-subject'] = 'subscriber_subject';
            $old_db_structure['mail_settings']['block-user_emails-tabs-subscriber_notification-body'] = 'subscriber_body';

            // Style Settings
            $old_db_structure['style_settings']['block-colors-primary_bg'] = 'primary_bg';
            $old_db_structure['style_settings']['block-colors-primary_color'] = 'primary_color';
            $old_db_structure['style_settings']['block-colors-primary_border_color'] = 'primary_border_color';
            $old_db_structure['style_settings']['block-colors-menu_border_color'] = 'menu_border_color';

            // Style Array
            $old_db_structure['style_settings']['block-colors-menu_border_color'] = 'menu_border_color';

            return ( ! empty( $old_db_structure[ $basename ] ) ) ? $old_db_structure[ $basename ] : '';
        }

        private function json_send_error( $error )
        {
            wp_send_json_error( '<div class="error task-notice"><p>' . $error . '</p></div>' );
        }

        public function error_handler( $errno, $errstr, $errfile, $errline )
        {
            //echo "<b>Custom error:</b> [$errno] $errstr<br>";
            //echo " Error on line $errline in $errfile<br>";
        }

        public function display_upgrade_notice()
        {
            // Do not display message, if we are on upgrade page
            if ( isset( $_GET['page'] ) && $_GET['page'] == 'exc-theme-easy-upgrade' )
            {
                return;
            }?>

            <div class="notice error exc-uploader-dismiss-upgrade-notice is-dismissible">
                <p>
                    <strong><?php printf(
                            __( 'URGENT: Uploader theme requires major upgrade, %s.', 'exc-uploader-theme' ),
                            '<a href="' . esc_url( admin_url( 'themes.php?page=exc-theme-easy-upgrade' ) ) . '">' . __( 'Click here to finish it', 'exc-uploader-theme' ) . '</a>'
                        );?>
                    </strong>
                </p>
            </div>
            <?php
        }

        public function print_inline_script()
        {?>
            <script type="text/javascript">
                ( function(window, $ ){
                    $('.exc-uploader-dismiss-upgrade-notice').on('click', function(e){
                        $.post( ajaxurl, {'action': 'uploader_dismiss_upgrade_notice'}, function(r){

                        })
                    });
                })( window, jQuery );
            </script>

        <?php
        }
    }

    new eXc_Easy_Upgrader( $this );
}