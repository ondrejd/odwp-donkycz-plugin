<?php
/**
 * Fired during plugin activation
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/includes
 */

if ( !class_exists( 'DonkyCz_Activator' ) ):

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since 0.1
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/includes
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
class DonkyCz_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since 0.1
	 */
	public static function activate() {
		//include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-donkycz-contact-form-model.php';
		DonkyCz_Contact_Form_Model::create_table();
	}
}

endif;