<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://techxplorer.com
 * @since             1.0.0
 * @package           Txp_Content_Tweaks
 *
 * @wordpress-plugin
 * Plugin Name:       Techxplorer's Content Tweaks
 * Plugin URI:        https://techxplorer.com/
 * Description:       Implements a small number of tweaks to content that I find useful.
 * Version:           1.3.0
 * Author:            techxplorer
 * Author URI:        https://techxplorer.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       txp-content-tweaks
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-txp-content-tweaks-activator.php
 */
function activate_txp_content_tweaks() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-txp-content-tweaks-activator.php';
	Txp_Content_Tweaks_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-txp-content-tweaks-deactivator.php
 */
function deactivate_txp_content_tweaks() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-txp-content-tweaks-deactivator.php';
	Txp_Content_Tweaks_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_txp_content_tweaks' );
register_deactivation_hook( __FILE__, 'deactivate_txp_content_tweaks' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-txp-content-tweaks.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_txp_content_tweaks() {

	$plugin = new Txp_Content_Tweaks();
	$plugin->run();

}
run_txp_content_tweaks();
