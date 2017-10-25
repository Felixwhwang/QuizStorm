<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Custom_Page_Class' ) )
{
    class eXc_Custom_Page_Class
    {
        private $eXc;
        private $rules = array();
        private $active_rule = array();
        private $loaded = false;
        private $template = '';
        private $rewrite = array();
        private $login_url = '';

        public function __construct( &$_eXc )
        {
            $this->eXc = $_eXc;

            // @TODO: if admin then automatically unset the class
            if ( exc_is_client_side() )
            {
                add_action( 'init', array( &$this, 'rewrite_rule' ) );
                add_filter( 'query_vars', array( &$this, 'register_query_var' ) );

                add_action( 'pre_get_posts', array( &$this, 'customize_query' ) );
            }
        }

        public function customize_query( $query )
        {
            if ( $query->is_main_query() )
            {
                $current_custom_page = get_query_var( 'exc_custom_page' );

                // check if we are on custom page
                foreach ( $this->rules as $rule )
                {
                    if ( $current_custom_page == $rule['slug'] )
                    {
                        // Quick hack to make sure that wordpress is considering this page
                        // as home page
                        $query->is_home = '';

                        $this->active_rule =& $rule;

                        // @TODO: verify that do we really need this codition?
                        if ( ( isset( $rule['protected'] ) && $rule['protected'] ) && ! is_user_logged_in() )
                        {
                            if ( $this->login_url )
                            {
                                wp_redirect( $this->login_url );
                                exit;
                            }

                            exc_die( _x( 'You don\'t have permission to access this page', 'extracoding custom page', 'exc-framework' ) );

                        } elseif ( is_callable( $rule['callback'] ) )
                        {
                            // Time to trigger callback
                            // @TODO: copy wp do_action functionality
                            $data = array( 'rule' => &$rule, 'query' => &$query );

                            call_user_func_array( $rule['callback'], $data );
                        }

                        $this->template = $rule['template'];

                        // Update Page title
                        if ( ! empty( $rule['title'] ) )
                        {
                            add_filter( 'pre_get_document_title', array( &$this, 'set_page_title' ) );
                        }

                        do_action( 'exc_custom_page', $rule );

                        break;
                    }
                }
            }

            if ( ! empty( $this->template ) )
            {
                //add_action( 'template_redirect', array( &$this, 'url_rewrite_templates' ) );
                add_filter( 'template_include', array( &$this, 'load_template' ) );
            }

            return $query;
        }

        //public function add_rule( $regex, $url = '', $after = 'top', $template = '', $protected = false, $title = '' )
        public function add_rule( $args, $template = '' )
        {
            // @TODO: get only one argument and remove url, after, template and protected, title
            if ( ! is_array( $args ) )
            {
                $args = array( 'regex' => $args );
            }

            $defaults = array(
                            'regex'     => '',
                            'slug'      => '',
                            'redirect'  => '',
                            'after'     => 'top',
                            'callback'  => '',
                            'template'  => $template,
                            'protected' => false,
                            'title'     => '',
                            'vars'      => array()
                        );

            $args = wp_parse_args( $args, $defaults );

            // Do nothing if regex of callback information is not available
            if ( empty( $args['regex'] ) || ( empty( $args['template'] ) && ! is_callable( $args['callback'] ) ) )
            {
                return $this;
            }

            // @TODO: use better approch
            if ( empty( $args['slug'] ) )
            {
                $regex_slug = ( substr_count( $args['regex'], '/' ) > 1 ) ? strtolower( str_replace('/', '-', preg_replace( '#/\(.*?\)/#', '-', $args['regex'] ) ) ) : strtolower( $args['regex'] );
                $args['slug'] = implode( '-', array_filter( ( array ) explode( '-', trim( preg_replace( "#([^a-z0-9-_])#i", "", $regex_slug ) ) ) ) );
            }

            if ( empty( $args['redirect'] ) )
            {
                $args['redirect'] = 'index.php?exc_custom_page=' . $args['slug'];
            }

            parse_str( preg_replace( '#index.php\??#', '', $args['redirect'] ), $output );

            $args['vars'] = array_keys( $output );

            //@TODO: log message if template file is not exists

            //if ( ! is_callable( $args['template'] ) )

            // do nothing if the file is the complete file path is given
            if ( ! empty( $args['template'] ) && ! file_exists( $args['template'] ) ) {
                $args['template'] = $args['template'] . '.php';
                $args['template'] = locate_template( $args['template'] );
            }

            $args = apply_filters( 'exc_custom_page_args', $args );

            if ( $args ) {
                $this->rules[] = $args;
            }

            return $this;
        }

        public function rewrite_rule()
        {
            global $wp_rewrite;

            foreach ( $this->rules as $rule )
            {
                extract( $rule );

                add_rewrite_rule( $regex, $redirect, $after );
            }

            $wp_rewrite->flush_rules();
        }

        public function register_query_var( $vars )
        {
            foreach ( $this->rules as $rule )
            {
                foreach( $rule['vars'] as $var )
                {
                    if ( ! in_array( $var, $vars ) )
                    {
                        $vars[] = $var;
                    }
                }
            }

            return $vars;
        }

        public function login_url( $url )
        {
            // @TODO: use plugin function to retrive this URL
            if ( $url = apply_filters( 'exc_login_url', $url ) )
            {
                $this->login_url = $url;
            } else
            {
                $this->login_url = network_site_url( 'wp-admin' );
            }

            return $this;
        }

        public function rewrite_base( $key = '', $value = '' )
        {
            if ( ! $key || ! $value )
            {
                return;

            } elseif ( empty( $this->rewrite ) )
            {
                add_action( 'init', array( $this, '_rewrite' ) );
            }

            $this->rewrite[ $key ] = $value;
        }

        public function _rewrite()
        {
            global $wp_rewrite;

            foreach ( $this->rewrite as $k => $v )
            {
                $wp_rewrite->{ $k } = $v;
            }

            $wp_rewrite->flush_rules();
        }

        public function load_template()
        {
            return $this->template;
        }

        public function set_page_title( $title )
        {
            return $this->active_rule['title'];
        }
    }
}