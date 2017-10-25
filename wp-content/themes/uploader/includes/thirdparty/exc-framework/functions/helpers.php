<?php defined('ABSPATH') OR die('restricted access');

if ( ! function_exists( 'exc_site_url' ) )
{
    function exc_site_url( $args = array() )
    {
        if ( get_option( 'permalink_structure' ) )
        {
            return site_url( implode( '/', $args ) );
        } else
        {
            return http_build_query( $args );
        }
    }
}

if ( ! function_exists( 'exc_to_slug' ) )
{
    function exc_to_slug( $string )
    {
        return trim( preg_replace( "#([^a-z0-9])#i", "_", strtolower($string) ) );
    }
}

if ( ! function_exists( 'exc_to_text' ) )
{
    function exc_to_text( $string )
    {
        return ucwords( trim( preg_replace( "#([^a-z0-9])#i", " ", strtolower($string) ) ) );
    }
}

if ( ! function_exists( 'hex2rgb' ) )
{
    function hex2rgb( $hex )
    {
        if ( empty( $hex ) )
        {
            return;
        }

        // Automatically toggle value
        if ( is_array( $hex ) )
        {
            return rgb2hex( $hex );
        }

        $hex = str_replace("#", "", $hex);

        if ( strlen( $hex ) == 3 )
        {
            $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
            $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
            $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );

        } else
        {
            $r = hexdec( substr( $hex, 0, 2 ) );
            $g = hexdec( substr( $hex, 2, 2 ) );
            $b = hexdec( substr( $hex, 4, 2 ) );
        }

        $rgb = array( $r, $g, $b );

        return $rgb;
    }
}

if ( ! function_exists( 'rgb2hex' ) )
{
    function rgb2hex( $rgb )
    {
        if ( ! is_array( $rgb ) )
        {
            return;
        }

        $hex = "#";
        $hex .= str_pad( dechex( $rgb[0] ), 2, "0", STR_PAD_LEFT );
        $hex .= str_pad( dechex( $rgb[1] ), 2, "0", STR_PAD_LEFT );
        $hex .= str_pad( dechex( $rgb[2] ), 2, "0", STR_PAD_LEFT );

        return $hex; // returns the hex value including the number sign (#)
    }
}

if ( ! function_exists( 'exc_load_plugin' ) )
{
    function exc_load_plugin( $class, $arguments = array() )
    {
        $class = strtolower( $class );

        if ( ! class_exists( $class ) )
        {
            // Die or display error
        } else
        {
            if ( ! is_array( $arguments ) ) {
                $arguments = array( 'file'  => $arguments );
            }

            if ( empty( $arguments['file'] ) ) {
                $backtrace = debug_backtrace();
                $caller = array_shift( $backtrace );

                $arguments['file'] = $caller['file'];
            }

            $GLOBALS['_exc'][ $class ] = new $class( $arguments );
        }
    }
}

$_exc_active_product_instance_name = '';

if ( ! function_exists( 'exc_set_product_instance_name' ) ) :

function exc_set_product_instance_name( $product_name )
{
    global $_exc, $_exc_active_product_instance_name;

    if ( ! isset( $_exc[ $product_name ] ) ||
            ! is_a( $_exc[ $product_name ], 'eXc_Base_Class' ) ) {
        exc_die(
            sprintf(
                __(
                    'The product name %s must be a valid exc_base_class instance',
                    'exc-framework'
                )
            )
        );
    }

    $_exc_active_product_instance_name = $product_name;
}

endif;

if ( ! function_exists( 'exc_theme_instance' ) )
{
    function &exc_theme_instance()
    {
        global $_exc, $_exc_active_product_instance_name;

        if ( ! defined( 'EXC_THEME_INSTANCE_NAME' ) ) {
            exc_die(
                __('You must define theme instance to get it.', 'exc-framework')
            );
        }

        $_exc_active_product_instance_name = EXC_THEME_INSTANCE_NAME;

        return exc_get_instance();
    }
}

if ( ! function_exists( 'exc_get_instance' ) )
{
    function &exc_get_instance( $product_name = '' )
    {
        global $_exc, $_exc_active_product_instance_name;

        if ( ! $product_name ) {
            $product_name = $_exc_active_product_instance_name;
        }

        if ( ! isset( $_exc[ $_exc_active_product_instance_name ] ) )
        {
            exc_die(
                sprintf(
                    __('The product % is not loaded yet.', 'exc-framework'),
                    $product_name
                )
            );
        }

        return $GLOBALS['_exc'][ $product_name ];
    }
}

if ( ! function_exists('exc_system_log') )
{
    function exc_system_log()
    {
        // Write the system logs
    }
}

if ( ! function_exists( 'exc_get_option' ) )
{
    function exc_get_option( $key = '', $normalize = true, $depth = -2 )
    {
        if ( ! is_array( $key ) )
        {
            if ( ! $key || ! $settings = get_option( $key ) )
            {
                return array();
            }

        } else
        {
            $settings = $key;
        }

        if ( ! $normalize ) {
            return $settings;
        }

        // Normalize data
        return apply_filters( 'exc-option-' . $key, exc_normalize_data( $settings, $depth ) );
    }
}

if ( ! function_exists( 'exc_normalize_data' ) )
{
    function exc_normalize_data( $settings, $depth = -2, $skip_special_keys = array('tabs', 'dynamic', 'accordion') )
    {
        $data = array();

        if ( ! empty( $settings ) )
        {
            foreach ( $settings as $k => $v )
            {
                $parts = explode( '-', $k );

                $structure = array_slice( $parts, $depth );

                exc_set_xpath_value( $data, implode( '/', $structure ), $v, 'set' );
            }
        }

        return $data;
    }
}

// Special functions
if ( ! function_exists( 'exc_die' ) )
{
    function exc_die( $error = '' )
    {
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
        {
            wp_send_json_error( $error );
        } else
        {
            wp_die( $error );
        }
    }
}

if ( ! function_exists( 'exc_success' ) )
{
    function exc_success( $message )
    {
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
        {
            wp_send_json_success( $message );
        } else
        {
            echo $message . "\n";
        }
    }
}

/**
 * Print array in readable format
 *
 * A print_r similar function to print the array in readable format with the help of HTML &lt;pre&gt; tag
 *
 * @access  public
 * @param   array to print
 * @param   boolean default is true to auto exit the php script execution
 * @return  void
 */

if ( ! function_exists( 'printr' ) )
{
    function printr()
    {
        $exit = true;
        $args = func_get_args();

        if ( func_num_args() > 1 )
        {
            end( $args );

            if ( current( $args ) == 'FALSE' )
            {
                $exit = false;
                array_pop( $args );
            }
        }

        echo '<pre>';

        foreach ( $args as $argument )
        {
            print_r( $argument );
            echo "\n";
        }

        if ( $exit )
        {
            exit();
        }
    }
}

/**
 * exc_ajax_login_check check through ajax if user is logged in
 *
 * The function check if user is logged in
 *
 * @access  public
 * @param   void
 * @return  JSON
 */

if ( ! function_exists( 'exc_ajax_login' ) )
{
    function exc_ajax_login()
    {
        if ( ! wp_verify_nonce( exc_kv( $_POST, 'security' ), 'exc-login-check' ) )
        {
            wp_send_json_error();
        }

        if ( is_user_logged_in() )
        {
            wp_send_json_success();

        } else
        {
            exc_die( _x( 'You are not logged in.', 'exc-login', 'exc-framework' ) );
        }
    }

    add_action( 'wp_ajax_exc_login_check', 'exc_ajax_login' );
    add_action( 'wp_ajax_nopriv_exc_login_check', 'exc_ajax_login' );
}

// Array Functions
if ( ! function_exists( 'exc_kv' ) )
{
    function exc_kv( $array = array(), $keys, $default = '', $echo = false )
    {
        if ( ! is_array( $array ) )
        {
            // Treat array as primary value and keys as default
            $return = ( $array ) ? $array : $keys;
        } else
        {
            $keys = is_array( $keys ) ? implode( '/', $keys ) : $keys;
            $return = exc_get_xpath_value( $array, $keys, $default );
        }

        if ( $echo )
        {
            echo $return;

        } else
        {
            return $return;
        }
    }
}

if ( ! function_exists( 'exc_set_xpath_value' ) )
{
    function exc_set_xpath_value(array &$array, $path = '', $value, $action = 'merge')
    {
        if ( ! is_string( $path ) )
        {
            throw new Exception('Path must be a string');
        }

        $path = trim( $path, '/' );

        $parts = explode( '/', $path );

        $pointer =& $array;
        end( $parts );

        $last_key = current( $parts );

        foreach ( $parts as $key => $part )
        {
            if ( ! isset( $pointer[ $part ] ) )
            {
                $pointer[ $part ] = array();
            }

            if ( $last_key == $part && count( $parts ) == ( $key + 1 ) )
            {
                if ( $action == 'merge' )
                {
                    $pointer = array_merge( array_filter( $pointer ), $value );

                } elseif ($action == 'remove')
                {
                    if ( isset( $pointer[ $last_key ] ) )
                    {
                        unset( $pointer[ $last_key ] );
                    }

                } elseif ( $action == 'set' )
                {
                    $pointer[ $part ] = $value;
                } else
                {
                    $pointer_key = array_search( $part, array_keys( $pointer ) );

                    if ( false !== $key )
                    {
                        $newArray = array();

                        foreach ( $pointer as $k => $v )
                        {
                            if ( $action == 'after' )
                            {
                                $newArray[ $k ] = $v;
                            }

                            if ( $k == $last_key )
                            {
                                foreach( (array) $value as $vk => $vv )
                                {
                                    $newArray[ $vk ] = $vv;
                                }
                            }

                            if ( $action == 'before' )
                            {
                                $newArray[$k] = $v;
                            }
                        }

                        $pointer = $newArray;
                    }
                }


            } else
            {
                $pointer =& $pointer[ $part ];
            }
        }
    }
}

if ( ! function_exists( 'exc_get_xpath_value' ) )
{
    function exc_get_xpath_value( array $array, $xpath, $default = null )
    {
        if ( ! is_array( $xpath ) )
        {
            $xpath = array( $xpath );
        }

        // fail if the path is empty
        if( empty( $xpath ) )
        {
            throw new Exception( "The Xpath must be defined" );
        }

        $return = array();

        foreach ( $xpath as $path )
        {
            // remove all leading and trailing slashes
            $path = trim( $path, '/' );

            // use current array as the initial value
            $value = $array;

            // extract parts of the path
            $parts = explode( '/', $path );

            // loop through each part and extract its value
            foreach ( $parts as $part )
            {
                if ( isset( $value[ $part ] ) )
                {
                    if ( empty( $value[ $part ] ) && ! is_bool( $value[ $part ] ) && ! is_numeric( $value[ $part ] ) )
                    {
                        return $default;
                    }

                    // replace current value with the child
                    $value = $value[ $part ];

                } else
                {
                    // key doesn't exist, fail
                    return $default;
                }
            }

            $return[ $path ] = $value;
        }

        return ( count( $return ) == 1 ) ? current( $return ) : $return;
    }
}

if ( ! function_exists( 'exc_array_to_string' ) )
{
    function exc_array_to_string( $array = array(), $options = array() )
    {
        $default = array(
                    'wrap_s' => '="',
                    'wrap_e' => '" '
                );

        if ( ! is_array( $options ) )
        {
            parse_str( $options, $options );
        }

        $options = array_merge( $default, $options );

        $return = '';

        foreach ( $array as $k => $v )
        {
            $return .= $k . $options['wrap_s'] . $v . $options['wrap_e'];
        }

        return $return;
    }
}

// Useful functions from exc_admin_class
if ( ! function_exists( 'exc_is_client_side' ) )
{
    function exc_is_client_side()
    {
        return ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) );
    }
}

if ( ! function_exists( 'exc_is_post_editing' ) )
{
    function exc_is_post_editing()
    {
        if ( is_admin() && ( $GLOBALS['pagenow'] == 'post.php' || $GLOBALS['pagenow'] == 'post-new.php' ) )
        {
            return true;
        }

        return false;
    }
}

if ( ! function_exists( 'exc_is_ajax_request' ) )
{
    function exc_is_ajax_request( $page = '' )
    {
        $request_page = ( isset( $_REQUEST['plugin_page'] ) ) ? $_REQUEST['plugin_page'] : '';

        if ( is_admin() && $GLOBALS['pagenow'] == 'admin-ajax.php' && $request_page == $page )
        {
            return true;
        }

        return false;
    }
}

// HTML Attributes
if ( ! function_exists( 'exc_parse_atts' ) )
{
    function exc_parse_atts( $attributes, $additional = array() )
    {
        // @TODO apply condition for attributes where values is not required
        // e.g checked; selected; required; multiple; disabled;
        if ( ! empty( $additional ) )
        {
            $attributes = wp_parse_args( $attributes, $additional );
        }

        $attrs = '';
        $empty_attrs = array( 'checked', 'selected', 'required', 'multiple', 'disabled' );

        foreach ( $attributes as $key => $val )
        {
            if ( $key == 'data' && is_array( $val ) )
            {
                foreach ( $val as $k => $v )
                {
                    $attrs .= 'data-' . $k . '="' . exc_normalize_atts( $v ) . '" ';
                }

                continue;

            } elseif ( $key == 'value' || is_array( $val ) )
            {
                $val = exc_normalize_atts( $val, $attributes['name'] );
            }

            $attrs .= ( ! $val && in_array( $key, $empty_attrs ) !== false) ? $key . ' ' : $key . '="' . esc_attr( $val ) . '" ';
        }

        return $attrs;
    }
}

if ( ! function_exists( 'exc_normalize_atts' ) )
{
    function exc_normalize_atts( $str = '', $field_name = '' )
    {
        static $prepped_fields = array();

        if ( is_array( $str ) )
        {
            foreach ( $str as $key => $val )
            {
                $str[ $key ] = exc_normalize_atts( $val );
            }

            return esc_attr( json_encode( $str ) );
        }

        if ( $str === '' )
        {
            return '';

        } elseif ( $str === false )
        {
            return 'false';
        } elseif ( $str === true )
        {
            return 'true';
        }

        if ( isset( $prepped_fields[ $field_name ] ) )
        {
            return $str;
        }

        $str = esc_attr( $str );

        if ( $field_name != '' )
        {
            $prepped_fields[ $field_name ] = $field_name;
        }

        return $str;
    }
}

if ( ! function_exists( 'exc_shortcode_id' ) )
{
    function exc_shortcode_id( $base = 'exc-shortcode-' )
    {
        static $i = 0;
        $i++;

        return $base . $i;
    }
}

if ( ! function_exists( 'exc_load_extension' ) )
{
    function exc_load_extension( $extension_name, &$instance = '', $arguments = array(), $base_path = 'extensions' )
    {
        if ( ! is_a( $instance, 'exc_base_class' ) )
        {
            $instance =& exc_theme_instance();
        }

        if ( ! empty ( $arguments )
                && is_bool( $arguments ) )
        {
            // Automatically decide
            $arguments = array( 'autoload' => $arguments );
        }

        $arguments = wp_parse_args(
                        $arguments,
                        array(
                            'classname' => '',
                            'autoload'  => true
                        )
                    );

        $instance->load_extension( $base_path . '/' . $extension_name, $arguments );
    }
}

if ( ! function_exists( 'exc_path_to_url' ) )
{
    function exc_path_to_url( $path, $file = '' )
    {
        $path = wp_normalize_path( $path );

        // WP Content Directory Path
        $content_dir_path = wp_normalize_path( WP_CONTENT_DIR );
        $content_dir_url = set_url_scheme( WP_CONTENT_URL );

        $base_dir = str_replace( $content_dir_path, '', $path );

        if ( $base_dir )
        {
            $content_dir_url .= '/' . ltrim( $base_dir, '/' );
        }

        $file = wp_normalize_path( $file );

        if ( ! empty( $file ) && is_string( $file ) )
        {
             $content_dir_url .= ltrim( $file, '/' );
        }

        return $content_dir_url;
    }
}

if ( ! function_exists( 'exc_get_post_meta' ) )
{
    function exc_get_post_meta( $meta_key, $post_id = 0, $default_value = '', $single = true )
    {
        if ( ! $post_id )
        {
            $post_id = get_the_ID();
        }

        // Fetch the meta information
        //no need to cache the result as wordpress is already doing it

        $key_parts = explode( '/', $meta_key );

        $meta_data = get_post_meta( $post_id, $key_parts[0], $single );

        if ( ! empty( $meta_data )
                && is_array( $meta_data ) && count( $key_parts ) > 1 )
        {
            unset( $key_parts[0] );

            if ( ! $single )
            {
                $return_data = array();

                foreach ( $meta_data as $index => $data )
                {
                    $return_data[ $index ] = exc_get_xpath_value( $data, $key_parts, $default_value );
                }

                return $return_data;
            }

            return exc_get_xpath_value( $meta_data, $key_parts, $default_value );
        } else
        {
            return ( ! empty( $meta_data ) ) ? $meta_data : $default_value;
        }
    }
}