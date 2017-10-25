<?php defined('ABSPATH') or die('restricted access.');

/* Template Name: Front Page */

// Load Layout Structure
$layout = exc_layout_structure();

// Load Header
get_header( exc_get_layout( 'header' ) );

get_template_part( 'page-templates/page-builder' );

get_footer();?>