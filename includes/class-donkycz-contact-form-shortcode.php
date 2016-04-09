<?php
/**
 * The file that defines the core plugin class
 *
 * @since 0.1
 * @link https://github.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin\includes
 */

if ( !class_exists( 'DonkyCz_Contact_Form_Shortcode' ) ):

/**
 * Class implementing contact form (using WP shortcode).
 *
 * @since 0.1
 * @package odwp-donky_cz
 * @subpackage odwp-donky_cz\includes
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
class DonkyCz_Contact_Form_Shortcode {
	/**
	 * Initialize contact form shortcode.
	 * 
	 * @since 0.1
	 * @uses add_shortcode()
	 * @uses current_user_can()
	 * @uses get_user_option()
	 * @uses add_filter()
	 */
	public function init() {
		add_shortcode( 'contact-form', array( $this, 'render' ) );

		// Add filters to run registration of our TinyMCE button and plugin.
		if ( !current_user_can( 'edit_posts' ) && !current_user_can('edit_pages') ) {
			return;
		}

		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter( 'mce_external_plugins', array( $this, 'register_plugin' ) );
			add_filter( 'mce_buttons', array( $this, 'register_button' ) );
		}
	}

	/**
	 * Register TinyMCE button.
	 *
	 * @since 0.1
	 * @param array $buttons
	 * @return array
	 */
	public function register_button( $buttons ) {
		array_push( $buttons, '|', 'donkycz' );

		return $buttons;
	}

	/**
	 * Register our TinyMCE plugin.
	 *
	 * @since 0.1
	 * @param array $plugins
	 * @return array
	 */
	public function register_plugin( $plugins ) {
		$plugins['donkycz'] = plugin_dir_url( dirname ( __FILE__ ) ) . '/admin/js/contactform.js';

		return $plugins;
	}

	/**
	 * Renders contact form (replaces the shortcode).
	 * 
	 * @since 0.1
	 * @return string
	 * @uses wp_nonce()
	 */
	public function render() {
		return '<code>XXX</code>';
	}
}

endif;