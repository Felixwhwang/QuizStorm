<?php defined('ABSPATH') OR die('restricted access');

/*
Plugin Name:  Extracoding Framework
Plugin URI:   http://www.extracoding.com/framework
License:      http://www.extracoding.com/framework/license.txt
Description:  Extracoding framework is a powerful tool to develop theme and plugins faster.
Version:      2.3.2
Author:       Hassan r. Bhutta
Author URI:   http://hassanrb.com
*/

define( 'EXTRACODING_FRAMEWORK_VERSION', '2.3.2' );

// Register global Extracoding variable
$GLOBALS['_exc'] = array();

/**
 * Load language files
 */
if ( ! function_exists( 'exc_load_plugin_textdomain' ) ) :

function exc_load_plugin_textdomain()
{
    load_plugin_textdomain( 'exc-framework', false, dirname( plugin_basename( FILE ) ) . '/languages/' );
}

load_theme_textdomain( 'exc-framework', get_template_directory() . '/languages' );

endif;

/**
 * Load Helper functions
 */
require_once "functions/helpers.php";

/**
 * Load eXc_Based_Class
 */
require_once "core/base_class.php";

/**
 * Load Plugin Abstract Class
 */
require_once "core/abstracts/plugin_abstract.php";

/**
 * Load Controller Abstract
 */
require_once "core/abstracts/controller_abstract.php";

/**
 * Plugin Page Settings
 */
require_once "framework_class.php";

/**
 * Annouce that Extracoding Framework is loaded
 */
do_action( 'exc-framework-load', '2.3.2' );