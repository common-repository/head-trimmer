<?php
/**
 * Plugin Name: Head Trimmer
 * Plugin URI: https://wordpress.org/plugins/head-trimmer/
 * Description: Customizable plugin to selectively remove WordPress version information, feeds, shortlinks, xmlrpc, emoji support and other miscellaneous extras from the HEAD element.
 * Version: 1.0.4
 * Author: John Dalesandro
 * Author URI: https://johndalesandro.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: head-trimmer
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'HEAD_TRIMMER_FILE' ) ) {
	define( 'HEAD_TRIMMER_FILE', __FILE__ );
}

if ( ! class_exists( 'Head_Trimmer' ) ) {
	require_once trailingslashit( dirname( HEAD_TRIMMER_FILE ) ) . 'classes/class-head-trimmer.php';
}

if ( is_admin() && ! class_exists( 'Head_Trimmer_Settings' ) ) {
	require_once trailingslashit( dirname( HEAD_TRIMMER_FILE ) ) . 'classes/class-head-trimmer-settings.php';
}
