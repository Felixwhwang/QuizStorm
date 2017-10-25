<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Theme_Options_Extension' ) )
{
    class eXc_Theme_Options_Extension extends eXc_Extension_Abstract
    {
        protected $version = '1.0.2';

        protected function initialize_extension()
        {
            // Do nothing if we are not in admin section
            if ( ! is_admin() ) {
                return false;
            }

            // Load theme options
            add_action( 'wp_loaded', array( &$this, 'load_theme_options' ) );

            // Import Demo Data
            add_action( 'wp_ajax_exc_theme_install_demo', array( &$this, 'import_demo_data' ) );
        }

        public function load_theme_options()
        {
            $this->exc()->load_file( 'functions/wp_helper' );

            $this->exc()->wp_admin->edit('menu')->add_theme_page(
                array(

                    'page_title'    => esc_html__('Theme Options', 'exc-framework'),
                    'menu_title'    => esc_html__('Theme Options', 'exc-framework'),
                    'menu_slug'     => 'exc-theme-options',

                 ), 'theme_options/settings' );
        }

        public function demo_importer_view()
        {
            $form_settings =& $this->eXc()->wp_admin->edit('menu')->get_active_form_settings();
            $this->local()->load_view( 'views/demo_importer/index', $form_settings );
        }

        public function extended_theme_options( $options, $file )
        {
            if ( $file == 'theme_options/settings' )
            {
                $options['_config']['import_demo_data'] = 'extensions/theme_options/config/import_data';
                $options['_config']['changelog'] = 'extensions/theme_options/config/changelog';

                // Automatically remove this filter
                remove_filter( 'exc_config_array_theme_options_settings', array( &$this, 'extended_theme_options' ), 10 );
            }

            return $options;
        }

        private function check_theme_update( $skip_session_timer = false )
        {

        }

        public function import_demo_data()
        {
            // @TODO: code for demo data import
        }

        public function export_theme_options()
        {
            if ( ! current_user_can( 'manage_options' ) )
            {
                return;
            }

            if ( is_a( $this->eXc, 'exc_base_class' ) )
            {
                $config_files = $this->exc()->load_config_file( 'theme_options/settings', array(), TRUE );

                if ( ! empty( $config_files['_config'] ) )
                {
                    $options_data = array();
                    $site_url = site_url();

                    foreach ( $config_files['_config'] as $name => $file )
                    {
                        $config = $this->eXc->load_config_file( $file, array(), TRUE );

                        if ( ! empty( $config['db_name'] ) )
                        {
                            // @TODO: verify that the field is available
                            $options_data[ $name ] = get_option( $config['db_name'] );
                        }
                    }

                    var_export( $options_data );
                    exit;
                }
            }
        }
    }
}