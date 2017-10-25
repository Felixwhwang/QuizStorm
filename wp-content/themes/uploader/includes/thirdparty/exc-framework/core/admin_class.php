<?php defined('ABSPATH') OR die('restricted access');

/**
 * Extracoding Framework
 *
 * An open source extracoding framework
 *
 * @package     Extracoding framework
 * @author      Extracoding team <info@extracoding.com>
 * @copyright   Copyright 2014 © Extracoding - All rights reserved
 * @license     http://extracoding.com/framework/license.html
 * @link        http://extracoding.com
 * @version     Version 1.0
 */

/**
 * eXc_Wp_Admin_Class
 *
 * Wordpress admin panel class
 *
 * @package     Extracoding framework
 * @subpackage  Core
 * @category    Core
 * @author      Hassan R. Bhutta
 * @since       1.0
 * @license Copyright 2014 © Extracoding. - All rights reserved
 *
 */

 /** Code Updated */
if ( ! class_exists( 'eXc_Admin_Class' ) )
{
    class eXc_Admin_Class extends eXc_Controller_Abstract
    {
        protected function initialize_class() {}

        public function edit( $classname, $arguments = null )
        {
            if ( "_class" != substr( $classname, -6 ) ) {
                $classname = $classname . "_class";
            }

            $class_instance = $this->exc()->load( "core/admin/" . $classname, "", false, $this, $arguments );
            $this->exc()->clear_query();

            return $class_instance;
        }

        public function prepare_form( &$fields )
        {
            // Load form class or get instance and then prepare fields
            return $this->exc()->load( 'core/form_class' )->prepare_fields( $fields );
        }
    }
}