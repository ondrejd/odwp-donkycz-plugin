<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since 0.1
 * @package odwp-donky_cz
 * @subpackage odwp-donky_cz\includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since 0.1
 * @package odwp-donky_cz
 * @subpackage odwp-donky_cz\includes
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
class DonkyCz_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 0.1
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'odwp-donky_cz',
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);

	}



}
