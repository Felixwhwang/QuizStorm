<?php defined('ABSPATH') OR die('restricted access');

if ( ! class_exists( 'eXc_Fields_Abstract' ) ) :

abstract class eXc_Fields_Abstract
{
    /**
     * Extracoding Framework Instance
     *
     * @since 1.0
     * @var object
     */
    protected $eXc;

    protected $name;
    protected $org_name;

    /**
     * Fields Custom Arguments
     *
     * @since 1.5
     * @var object
     */
    protected $_args = array();

    protected $default_layout;
    protected $default_skin;
    protected $reset = false;
    protected $display_error = true;

    abstract function html( $return = false );

    function __construct( &$eXc )
    {
        //@TODO: add support to pass unlimited arguments
        $this->eXc = $eXc;

        // Load script and CSS files
        if ( method_exists( $this, 'enqueue_files' ) )
        {
            add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_files' ), 1 );
            add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_files' ), 1 );
        }

        if ( method_exists( $this, 'localize_script' ) )
        {
            add_action( 'admin_print_footer_scripts', array( &$this, 'localize_script' ), 20 );
            add_action( 'wp_print_footer_scripts', array( &$this, 'localize_script' ), 20 );
        }
    }

    function set_data( $name, &$config, $key, $is_dynamic = false, $format = '[{{{ i }}}]' )
    {
        //@TODO: trigger action to announce that field is loaded

        $this->org_name = $this->name = $name;

        $this->config_key = $key;
        $this->config =& $config;
        $this->field_type = isset( $config['type'] ) ? $config['type'] : '';

        if ( $this->is_dynamic = $is_dynamic )
        {
            $this->name = $is_dynamic;

            $this->config['dynamic_name'] = $is_dynamic;

            $this->config['attrs']['name'] = $is_dynamic . $format . '[' . $key . ']';
        }

        /** Check if we have actions */

        // @Depreciated
        if ( method_exists( $this, 'add_actions' ) )
        {
            $this->add_actions();
        }

        $this->set_vars();
    }

    public function do_validation()
    {
        if ( $this->is_dynamic )
        {
            if ( isset( $_POST[ $this->is_dynamic ] ) && is_array( $_POST[ $this->is_dynamic ] ) )
            {
                foreach ( $_POST[ $this->is_dynamic ] as $k => $v )
                {
                    $this->eXc->validation->set_rules( $this->is_dynamic . '[' . $k . '][' . $this->config_key . ']', $this->config['label'], $this->config['validation'] );
                }
            }

        } else
        {
            $this->eXc->validation->set_rules( $this->name, $this->config['label'], $this->config['validation'] );
        }

        return $this->eXc->validation;
    }

    function set_value( $field_only = true )
    {
        if ( $field_only && $this->is_dynamic )
        {
            return;
        }

        $value = ( $this->config['selected'] ) ? $this->config['selected'] : $this->config['default'];

        if ( count( $_POST ) )
        {
            if ( ! empty( $this->is_dynamic ) && isset( $_POST[ $this->is_dynamic ] ) /*&& is_array( $_POST[ $this->is_dynamic ] )*/ )
            {
                $default_value = $this->eXc->validation->post_hack ? $value : '';
                $value = array();

                foreach ( $_POST[ $this->is_dynamic ] as $k => $v )
                {
                    if ( is_array( $v ) )
                    {
                        foreach( $v as $vk => $vv )
                        {
                            $value[ $k ][ $vk ] = $this->eXc->validation->set_value( $this->is_dynamic . '[' . $k . '][' . $vk . ']', $default_value );
                        }

                    } else
                    {
                        $value[ $k ][ $this->config_key ] = $this->eXc->validation->set_value( $this->is_dynamic . '[' . $k . '][' . $this->config_key . ']' );
                    }
                }

            }
            /*elseif ( $this->is_dynamic && $this->eXc->validation->post_hack )
            {
                $this->eXc->validation->set_value( $this->name, $value );
            }*/ else
            {
                $value = $this->eXc->validation->set_value( $this->name );
            }

        } else
        {
            $value = $this->eXc->validation->set_value( $this->org_name, $value );
        }

        return $value;
    }

    protected function set_vars()
    {
        $vars = array(
                    'label'         => sprintf( _x( '%s Field', 'extracoding field name suffix', 'exc-framework'), $this->field_type ), /** The custom label of the field */
                    'name'          => $this->name, /** The name of the field */
                    'config_key'    => $this->config_key,
                    'type'          => $this->field_type, /** the field type e.g text for text field */
                    'selected'      => array(), /** selected where using options */
                    'validation'    => '', /** Validation */
                    'options'       => array(), /** Options for select, checkbox, button etc */
                    'attrs'         => array(), /** Custom Attributes, also overwrites built-in attributes as well */
                    'data'          => array(), // HTML tag data attributes
                    'help'          => '', /** Description about field */
                    'layout'        => $this->default_layout, /** Layout file name */
                    'skin'          => $this->default_skin, /** Skin method name */
                    'default'       => '', /** Default value for input and textarea */
                    'wrap'          => '', /** Wrap the markup in HTML */
                    'wrap_option'   => '', //Wrap each option
                    'markup'        => '', /** Markup container */
                );

        $this->config = array_merge( $vars, $this->config );

        // Add default value to reset field value
        if ( $this->reset == true )
        {
            $this->config['attrs']['data-exc-default-value'] = $this->config['default'];
        }

        // Fields Custom Arguments
        if ( isset( $this->config['field_args'] ) )
        {
            if ( method_exists( $this, 'normalize_field_args' ) )
            {
                $this->_args = $this->normalize_field_args( $this->config['field_args'] );
            } else
            {
                $this->_args = wp_parse_args( $this->config['field_args'], array() );
            }

            unset( $this->config['field_args'] );
        }

        $this->normalize_data();

        if ( ! empty( $this->config['data'] ) )
        {
            $data = array();

            foreach ( $this->config['data'] as $data_key => $data_value )
            {
                $data[ 'data-' . $data_key ] = maybe_serialize( $data_value );
            }

            $this->config['attrs'] = array_merge( $data, $this->config['attrs'] );
        }
    }

    protected function normalize_data()
    {
        //Function to update configuration variables for fields
    }

    protected function apply_skin( $return )
    {
        /** Wrap the code if have wrapping HTML */
        if ( $this->config['wrap'] )
        {
            $this->config['markup'] = $this->wrap_it( $this->config['wrap'], $this->config, true );
        }

        if ( isset( $this->config['html'] ) ) {
            $this->config['markup'] = $this->wrap_it( $this->config['html'], $this->config, true );
        } elseif ( $this->config['layout'] ) {
            $this->config['markup'] = $this->eXc->load_view( $this->config['layout'], $this->config, $return );
        } elseif ( $this->config['skin'] )
        {
            /** Security Check: Make sure only method with prefix skin_ are accessible */
            if ( is_callable( $this->config['skin'] ) ) {

                $data = array( 'return' => $return, 'field' => &$this );
                $this->config['markup'] = call_user_func_array( $this->config['skin'], $data );

            } else {
                $skin = ( FALSE !== strstr( $this->config['skin'], 'skin_' ) ) ? $this->config['skin'] : 'skin_' . $this->config['skin'];

                if ( ! method_exists( $this, $skin ) )
                {
                    wp_die( sprintf( __( "The skin method '%s' is not exists.", 'exc-framework' ), $skin ) );
                }

                $this->config['markup'] = $this->{ $skin }( $return );
            }
        }

        $markup = $this->config['markup'];

        unset( $this->config['markup'] );

        if ( ! $return )
        {
            echo $markup;

        } else
        {
            return $markup;
        }
    }

    protected function wrap_it( $code, $data )
    {
        preg_match_all( '@{([a-z_]+)}@', $code, $matches );

        if ( ! isset( $matches[1] ) || empty( $matches[1] ) )
        {
            return;
        }

        if ( empty( $data['error'] ) && $this->display_error )
        {
            $data['error'] = $this->eXc->validation->error( $this->name );
        }

        $values = array();

        foreach ( $matches[1] as $var )
        {
            $values[] = ( isset( $data[ $var ] ) ) ? $data[ $var ] : '';
        }

        return str_replace( $matches[0], $values, $code );
    }

    private function skin_checkbox( $return )
    {
        $markup = sprintf('<div class="checkbox">%s</div>',
                            $this->config['markup']);

        if($this->config['help']) $markup .= sprintf('<span class="help-block">%s</span>', $this->config['help']);

        return $markup;
    }

    protected function skin_form_field( $return = false )
    {
        $markup = sprintf( '<div class="form-field exc-form-field"><label for="%s">%s</label>%s %s %s </div>',
                            $this->config['name'], $this->config['label'], $this->config['markup'],
                            ( $this->display_error ) ? $this->eXc->validation->error( $this->name ) : '',
                            $this->get_help_note()
                        );

        return $markup;
    }

    protected function skin_form_group( $return = false )
    {
        $columns = exc_kv( $this->config, 'el_columns', 6 );
        $label_columns = exc_kv( $this->config, 'label_columns', 2 );
        $markup = sprintf('<div class="form-group exc-form-field"><label for="%s" class="col-sm-%d">%s</label><div class="col-sm-%d">%s%s%s</div></div>',
                            $this->config['name'], $label_columns, $this->config['label'], $columns, $this->config['markup'],
                            ( $this->display_error ) ? $this->eXc->validation->error($this->name) : '',
                            $this->get_help_note() );

        return $markup;
    }

    protected function skin_form_group_basic( $return = false )
    {
        $help_text = '';

        if ( $this->config['help'] ) {
            $help_text = sprintf(
                            '<span class="help-block">%s</span>',
                            $this->config['help']
                        );
        }

        $markup = sprintf(
                        '<div class="form-group exc-form-field form-vertical">
                            <label for="%s">%s</label>%s %s
                         </div>',

                        $this->config['name'],
                        $this->config['label'],
                        $this->config['markup'],
                        $help_text
                    );

        $markup .= $this->eXc->validation->error( $this->name );

        return $markup;
    }

    protected function skin_wp_widget( $return = false )
    {
        $markup = sprintf('<p><label for="%s">%s:</label>%s %s %s</p>',
                            $this->config['name'], $this->config['label'], $this->config['markup'],
                            ( $this->display_error ) ? $this->eXc->validation->error( $this->name ) : '',
                            $this->get_help_note( '<i class="help-block">%s</i>' ) );

        return $markup;
    }

    public function get_help_note( $skin = '' )
    {
        if ( ! $skin )
        {
            $skin = ( $this->config['skin'] == 'form_field' ) ? '<p>%s</p>' : '<span class="help-block">%s</span>';
        }

        return ( $this->config['help'] ) ? sprintf($skin, $this->config['help']) : '';
    }

    protected function element_wrap($el)
    {
        $markup =
            array(
                'a'         => '<a %s>%s</a>'."\n",
                'radio'     => '<input %s><label>%s</label>'."\n",
                'input'     => '<input %s value="%s" />'."\n",
                'checkbox'  => '<input %s />'."\n",
                'button'    => '<button %s>%s</button>'."\n",
                'form'      => '<form %></form>'."\n",
                'label'     => '<label for="%s">%s</label>'."\n"
            );

        return ( isset( $markup[ $el ] ) ) ? $markup[ $el ] : '';
    }

    public function clear_value()
    {
        if ( isset( $this->eXc->validation->_field_data[ $this->name ] ) )
        {
            $this->eXc->validation->_field_data[$this->name]['postdata'] = '';

            return true;
        }

        return false;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function display_error( $error = TRUE )
    {
        $this->display_error = $error;
    }

    public final function &exc( $clear_query_path = true )
    {
        // Automatically clear the query path
        if ( $clear_query_path ) {
            $this->eXc->clear_query();
        }

        return $this->eXc;
    }
}

endif;