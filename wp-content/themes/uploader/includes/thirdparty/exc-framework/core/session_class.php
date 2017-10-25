<?php if ( ! defined('ABSPATH')) exit('restricted access');

if ( ! class_exists( "eXc_Session_Class" ) ) :
class eXc_Session_Class extends eXc_Controller_Abstract
{
    //current time
    private $now = '';

    private $session_expiration = '7200';

    private $session_update_time = 300;

    private $cookie_path = '';

    private $session_data = array(
                                'session_id' => '',
                                'ip_address' => '',
                                'last_activity' => '',
                                'user_data' => array()
                            );

    private $flashdata_key = 'flashdata';

    function initialize_class()
    {
        $this->now = current_time( 'timestamp', 1 );

        foreach ( array( 'session_expiration', 'session_update_time', 'cookie_path' ) as $key ) {
            if ( isset( $this->arguments[ $key ] ) ) {
                $this->{$key} = $this->arguments[ $key ];
            }
        }

        $session_data = $this->read_session_data();

        if ( ! $session_data ) {
            $this->create_new_session();
        } else {
            $this->session_update();
        }

        // @TODO: don't sweep data during ajax requests
        if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )
        {
            $this->_flashdata_sweep();
        }

        $this->_flashdata_mark();
    }

    private function read_session_data()
    {
        if ( isset( $_COOKIE['_exc_session'] ) ) {

            //@TODO: read transient
            $this->session_id = stripslashes( $_COOKIE['_exc_session'] );

            $this->session_data = get_transient( "_exc_session_{$this->session_id}" );

            if ( $this->session_data ) {
                //@TODO: destory session
                return true;
            }
        }

        return false;
    }

    private function create_new_session( $is_update = false )
    {
        require_once( ABSPATH . 'wp-includes/class-phpass.php' );

        $session_hash = new PasswordHash( 8, false );

        $user_ip = $this->get_user_ip();
        $session_hash = $session_hash->get_random_bytes( 32 );
        //$session_hash = $session_hash->get_random_bytes( 32 ) . $user_ip;

        $this->session_data =
            array(
                'session_id'    =>  md5( $session_hash ),
                'ip_address'    =>  $user_ip,
                'last_activity' =>  $this->now,
                'user_data'     =>  ( isset( $this->session_data['user_data'] ) && $is_update ) ? $this->session_data['user_data'] : array()
            );

        if ( $is_update ) {
            $this->destroy();
        }

        $this->session_id = $this->session_data['session_id'];

        set_transient( "_exc_session_{$this->session_id}", $this->session_data, $this->session_expiration );

        setcookie( '_exc_session', $this->session_data['session_id'], time() + $this->session_expiration, $this->cookie_path, COOKIE_DOMAIN );
    }

    private function session_update( $force = false )
    {
        //@TODO: check if it's time to update session
        if ( ( $this->session_data['last_activity'] + $this->session_update_time ) >= $this->now ) {
            return;
        }

        //@TODO: Check if we have expire data in array
        $this->create_new_session( true );
    }

    public function set_flashdata( $newdata = array(), $newval = '' )
    {
        if ( ! is_array( $newdata ) ) {
            $newdata = array( $newdata => $newval );
        }

        foreach ( ( array ) $newdata as $key => $val ) {
            $flashdata_key = $this->flashdata_key . ':new:' . $key;
            $this->set_data( $flashdata_key, $val );
        }
    }

    public function keep_flashdata( $key )
    {
        $old_flashdata_key = $this->flashdata_key . ':old:' . $key;
        $value = $this->get_data( $old_flashdata_key );

        $new_flashdata_key = $this->flashdata_key . ':new:' . $key;
        $this->set_data( $new_flashdata_key, $value );
    }

    public function flashdata( $key, $preserve_data = FALSE )
    {
        $flashdata_key = $this->flashdata_key . ':old:' . $key;

        // Keep the flash data if required
        if ( $preserve_data ) {
            $this->keep_flashdata( $key );
        }

        return $this->get_data( $flashdata_key );
    }

    public function set_data( $new_data, $new_value = '', $update = true )
    {
        if ( ! is_array( $new_data ) ) {
            $new_data = array( $new_data => $new_value );
        }

        if ( ! isset( $this->session_data['user_data'] ) ) {
            $this->session_data['user_data'] = array();
        }

        $this->session_data['user_data'] = ( $update ) ?
                                            array_replace( $this->session_data['user_data'], $new_data ) :
                                            array_merge_recursive( $this->session_data['user_data'], $new_data );

        set_transient( "_exc_session_{$this->session_id}", $this->session_data, $this->session_expiration );
    }

    public function unset_data( $key )
    {
        if ( isset( $this->session_data['user_data'][ $key ] ) )
        {
            unset( $this->session_data['user_data'][ $key ] );

            set_transient( "_exc_session_{$this->session_id}", $this->session_data, $this->session_expiration );
        }
    }

    public function get_data( $key, $delete = false )
    {
        if ( isset( $this->session_data['user_data'][ $key ] ) ) {

            $data = $this->session_data['user_data'][ $key ];

            if ( $delete ) {
                $this->unset_data( $key );
            }

            return $data;
        }
    }

    public function destroy()
    {
        if ( $this->session_id ) {
            delete_transient( "_wp_session_{$this->session_id}" );
        }

        setcookie( '_exc_session', "", ( $this->now - 21100000 ), $this->cookie_path, COOKIE_DOMAIN, 0 );
    }

    public function get_user_ip()
    {
        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];

        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    //Cache complex code
    public function cache( $id, $code, $ttl = YEAR_IN_SECONDS )
    {
        return set_transient( $id, $code, $ttl );
    }

    //Get saved cache
    public function get_cache( $id )
    {
        return get_transient( $id );
    }

    //Delete cache
    public function delete_cache( $id )
    {
        return delete_transient( $id );
    }

    private function _flashdata_mark()
    {
        $userdata = isset( $this->session_data['user_data'] ) ? $this->session_data['user_data'] : array();

        foreach ( $userdata as $name => $value ) {

            $parts = explode(':new:', $name);

            if ( is_array( $parts ) && count( $parts ) == 2 ) {

                $new_name = $this->flashdata_key . ':old:' . $parts[1];
                $this->set_data( $new_name, $value );
                $this->unset_data( $name );
            }
        }
    }

    private function _flashdata_sweep()
    {
        $userdata = isset( $this->session_data['user_data'] ) ? $this->session_data['user_data'] : array();

        foreach ( $userdata as $key => $value ) {

            if ( strpos( $key, ':old:' ) ) {
                $this->unset_data( $key );
            }
        }
    }
}
endif;