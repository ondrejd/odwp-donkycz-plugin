<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since 0.1
 * @link https://github.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Plugin pro Donky.cz
 * Plugin URI:        http://github.com/ondrejd/odwp-donky_cz/
 * Description:       Plugin with all necessary functionality for site Donky.cz.
 * Version:           0.1
 * Author:            Ondřej Doněk
 * Author URI:        http://ondrejd.info/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       odwp-donky_cz
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}


if ( !function_exists( 'activate_odwpdcz' ) ):
/**
 * The code that runs during plugin activation.
 *
 * @since 0.1
 * @see DonkyCz_Activator
 */
function activate_odwpdcz() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-donkycz-activator.php';
	DonkyCz_Activator::activate();
}
endif;


if ( !function_exists( 'deactivate_odwpdcz' ) ):
/**
 * The code that runs during plugin deactivation.
 *
 * @since 0.1
 * @see DonkyCz_Deactivator
 */
function deactivate_odwpdcz() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-donkycz-deactivator.php';
	DonkyCz_Deactivator::deactivate();
}
endif;


// Register activation and deactivation hook.
register_activation_hook( __FILE__, 'activate_odwpdcz' );
register_deactivation_hook( __FILE__, 'deactivate_odwpdcz' );


// The core plugin class that is used to define internationalization,
// admin-specific hooks, and public-facing site hooks.
require plugin_dir_path( __FILE__ ) . 'includes/class-donkycz.php';


if ( !function_exists( 'run_odwpdcz' ) ):
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 0.1
 */
function run_odwpdcz() {
	$plugin = new DonkyCz();
	$plugin->run();
}
endif;


// Execute the plugin
run_odwpdcz();
