<?php
/*
Plugin Name: Cek Ongkir Gratis
Plugin URI: https://websweetstudio.com/cek-ongkir-gratis
Description: Plugin untuk melakukan cek ongkir gratis dengan mudah.
Version: 1.0
Author: Aditya Kristyanto
Author URI: https://websweetstudio.com
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('CEK_ONGKIR_GRATIS_VERSION', '1.6.8');

/**
 * Define plugin path url
 */
define('CEK_ONGKIR_GRATIS_URL', plugin_dir_url(__FILE__));
define('CEK_ONGKIR_GRATIS_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));

/**
 * Add function
 */
require_once plugin_dir_path(__FILE__) . 'inc/enqueue.php';
require_once plugin_dir_path(__FILE__) .'/class-loader.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cek_ongkir_gratis()
{

	$plugin = new cek_ongkir_gratis();
	$plugin->run();
}
run_cek_ongkir_gratis();