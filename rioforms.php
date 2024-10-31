<?php
/**
 * Plugin Name:       RioForms
 * Description:       Create stunning, responsive forms in record time with the next-generation WordPress drag and drop form builder plugin.
 * Requires at least: 6.5
 * Requires PHP:      7.0
 * Version:           1.1.0
 * Author:            WPRio
 * Author URI:        https://rioforms.io/
 * License:           GPL-2.0
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       rioforms
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'RIOFORMS_DIR' ) ) {
	define( 'RIOFORMS_DIR', __DIR__ );
}
if ( ! defined( 'RIOFORMS_VERSION' ) ) {
	define( 'RIOFORMS_VERSION', '1.1.0' );
}
if ( ! defined( 'RIOFORMS_DIR_URL' ) ) {
	define( 'RIOFORMS_DIR_URL', plugin_dir_url( __FILE__ ) );
}
if( !defined('RIOFORMS_PATH') ) {
	define( 'RIOFORMS_PATH', plugin_dir_path( __FILE__ ) );
}

require __DIR__ . '/vendor/autoload.php';

function rioforms() {
	return Rioforms\RioForms::get_instance();
}
rioforms();


