<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Framework_Class' ) )
{
    class eXc_Framework_Class extends eXc_Plugin_Abstract
    {
        /**
         * Extracoding Product Name ( do not change the name )
         *
         * @since 1.0
         * @var string
         */
        protected $product_name = 'exc-framework';

        public function initialize_plugin()
        {
            if ( is_admin() ) {
                //$this->register_admin_pages();
            }
        }

        public function register_admin_pages()
        {
            $local_config_dir = $this->local()->get_query_path('config/system/');

            $this->wp_admin->edit('menu')->add_page(
                        array(
                            'page_title'    => esc_html__('Extracoding Framework', 'exc-framework'),
                            'menu_title'    => esc_html__('Extracoding', 'exc-framework'),
                            'menu_slug'     => 'exc-framework',
                            'function'      => array( &$this, 'dashboard' )
                         )
                    )

                    ->add_subpage(
                        array(

                            'page_title'    => esc_html__('Dashboard', 'exc-framework'),
                            'menu_title'    => esc_html__('Dashboard', 'exc-framework'),
                            'menu_slug'     => 'exc-framework',

                         ), 'exc-framework'
                        )

                    ->add_subpage(
                        array(

                            'page_title'    => esc_html__('Manage Extensions', 'exc-framework'),
                            'menu_title'    => esc_html__('Installed Extensions', 'exc-framework'),
                            // 'position'      => 20,
                            'menu_slug'     => 'exc-manage-extensions',
                            //'function'      => array( &$this, 'test' )

                         ),

                        'exc-framework', $local_config_dir . 'extensions' )

                    ->add_subpage(
                        array(

                            'page_title'    => esc_html__('Add Extension', 'exc-framework'),
                            'menu_title'    => esc_html__('Add Extension', 'exc-framework'),
                            // 'position'      => 20,
                            'menu_slug'     => 'exc-install-extension',
                            'function'      => array( &$this, 'test' )

                         ), 'exc-framework'/*, 'config/auto_update'*/ )

                    ->add_subpage(
                        array(

                            'page_title'    => esc_html__('Auto Install / Update', 'exc-framework'),
                            'menu_title'    => esc_html__('Auto Install / Update', 'exc-framework'),
                            // 'position'      => 25,
                            'menu_slug'     => 'exc-auto-updates',

                         ), 'exc-framework', 'config/auto_update' );
        }

        public function dashboard()
        {
            $this->load_view( 'welcome_page' );
        }
    }

    exc_load_plugin( 'eXc_Framework_Class', __FILE__ );
}