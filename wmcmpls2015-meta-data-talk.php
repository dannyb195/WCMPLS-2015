<?php
/**
 * Plugin Name: WCMPLS 2015 demo functions
 * Plugin URI:
 * Description: WCMPLS 2015 demo functions, requires Alley Interactive's Field Manager
 * Author: Dan Beil
 * Author URI:
 * Version: 0.1
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( defined( 'FM_VERSION' ) ) {
	define( 'WCMPLS2015_BASE_DIR', dirname( __FILE__ ) );
	require_once( WCMPLS2015_BASE_DIR . '/inc/post-types/class-post-type-sections.php' );
	require_once( WCMPLS2015_BASE_DIR . '/inc/class-wcmpls-menu.php' );
}