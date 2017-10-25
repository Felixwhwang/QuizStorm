<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_PostType_abstract' ) )
{
    abstract class eXc_PostType_abstract
    {
        /**
         * Extracoding Framework Instance
         *
         * @since 1.0
         * @var object
         */
        protected $eXc;

        /**
         * Argments passed to this class
         *
         * @since 1.0
         * @var object
         */
        protected $args = array();

        /**
         * Current post type slug
         *
         * @since 1.0
         * @var string
         */
        protected $post_type_name;

        /**
         * Abstract Method to Initialize Extension
         *
         * @since 1.0
         * @return null
         */
        abstract public function register_post_type();

        /**
         * Set post type name
         *
         * @since 1.0
         * @return string
         */
        abstract protected function set_post_type_name();

        public function __construct( &$eXc, $args = array() )
        {
            $this->eXc = $eXc;

            $this->args = $args;

            // set post type name
            $this->post_type_name = $this->set_post_type_name();

            // Register Post Type
            $current_filter = current_filter();

            if ( $current_filter == 'init' ) {
                $this->register_post_type();
                $this->register_taxonomy();

            } else {
                add_action( 'init', array( &$this, 'register_post_type' ), 0 );
                add_action( 'init', array( &$this, 'register_taxonomy') );
            }

            if ( method_exists( &$this, 'register_metabox' ) ) {

                // Register Metabox
                add_action( 'load-post.php', array( &$this, 'register_metabox_init' ) );
                add_action( 'load-post-new.php', array( &$this, 'register_metabox_init' ) );
            }
        }

        public function register_metabox_init()
        {
            $typenow = exc_kv( $GLOBALS, 'typenow' );

            if ( $this->post_type_name == $typenow )
            {
                // Load Bootstrap and style files on this page
                if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )
                {
                    $this->eXc->load('core/html_class')->load_bootstrap()
                        ->load_css( 'theme-options-style', $this->eXc->system_url('views/css/style.css') )
                        ->load_css( 'font-awesome', $this->eXc->system_url('views/css/font-awesome.min.css') );
                }

                $this->register_metabox();
            }
        }

        protected final function &exc( $clear_query_path = true )
        {
            // Automatically clear the query path
            if ( $clear_query_path ) {
                $this->eXc->clear_query();
            }

            return $this->eXc;
        }

        public function register_taxonomy(){}
    }
}