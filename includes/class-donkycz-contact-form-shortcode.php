<?php
/**
 * Contact form shortcode.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/includes
 */

if ( !class_exists( 'DonkyCz_Contact_Form_Shortcode' ) ) :

/**
 * Class implementing contact form (using WP shortcode).
 *
 * @since 0.1
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/includes
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
class DonkyCz_Contact_Form_Shortcode {
	/**
	 * @access private
	 * @var array $default_options
	 */
	private $default_options = array();

	/**
	 * Initialize contact form shortcode.
	 *
	 * Adds contact form shortcode self and adds some TinyMCE enhancements in WP admin.
	 *
	 * @since 0.1
	 * @uses add_action()
	 * @uses add_filter()
	 * @uses add_shortcode()
	 * @uses current_user_can()
	 * @uses get_user_option()
	 * @uses is_admin()
	 */
	public function init() {
		// Ensure that options are initialized
		$this->get_options();

		// Register our shortcode
		add_shortcode( 'contact-form', array( $this, 'render' ) );

		// If we are not in WP admin return
		if ( ! is_admin() ) {
			return;
		}

		// Register admin menu
		// TODO Check if current user can manage options!
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// If current user can not edit pages or posts return
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		// If user has enabled rich editing (thus using TinyMCE) register our editor enhancements
		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter( 'mce_external_plugins', array( $this, 'tinymce_plugin' ) );
			add_filter( 'mce_buttons', array( $this, 'tinymce_button' ) );
            add_filter( 'mce_external_languages', array( $this, 'tinymce_language' ) );
		}
	} // end init()

	/**
	 * Return options for contact form shortcode.
	 *
	 * @return array
	 * @since 0.0.1
	 * @uses get_option()
	 * @uses update_option()
	 */
	public function get_options() {
		$prefix = 'odwpdcz-';
		$options = get_option( $prefix . '-options' );
		$need_update = false;

		if ( $options === false ) {
			$need_update = true;
			$options = array();
		}

		foreach ( $this->default_options as $key => $value ) {
			if ( ! array_key_exists( $key, $options ) ) {
				$options[$key] = $value;
			}
		}

		if ( ! array_key_exists( 'latest_used_version ', $options ) ) {
			$options['latest_used_version'] = DonkyCz::VERSION;
			$need_update = true;
		}

		if ( $need_update === true ) {
			update_option( $prefix . '-options', $options );
		}

		return $options;
	} // end get_options()

	/**
	 * Register menu in WordPress administration.
	 *
	 * @since 0.1
	 * @uses add_action()
	 * @uses add_filter()
	 * @uses add_menu_page()
	 * @uses add_options_page()
	 * @uses plugin_dir_url()
	 */
	public function admin_menu() {
		$icon   = plugin_dir_url( dirname( __FILE__ ) . 'odwp-donkycz-plugin.php' ) . 'icon-20.png';
		$prefix = 'odwpdcz-';

		$hook = add_submenu_page(
			'edit.php?post_type=' . DonkyCz_Custom_Post_Type_Toy::NAME,
			__( 'Kontaktní formulář - Data', DonkyCz::SLUG ),
			__( 'Kontaktní form.', DonkyCz::SLUG ),
			'manage_options',
			$prefix . 'data_page',
			array( $this, 'data_page' )
		);
		add_action( "load-$hook", array( 'DonkyCz_Contact_Form_Table', 'add_screen_options' ) );
		add_filter( 'set-screen-option', array( 'DonkyCz_Contact_Form_Table', 'set_screen_options' ), 10, 3 );

		add_options_page(
			__( 'Kontaktní formulář - Nastavení', DonkyCz::SLUG ),
			__( 'Kontaktní form.', DonkyCz::SLUG ),
			'manage_options',
			$prefix . 'options_page',
			array( $this, 'options_page' )
		);
	} // end admin_menu()

	/**
	 * Render options page (in WordPress administration).
	 *
	 * @since 0.1
	 * @uses get_options()
	 * @uses wp_verify_nonce()
	 */
	public function options_page() {
		$prefix = 'odwpdcz-';
		$options = $this->get_options();
		$need_update = $updated = false;

		if (
			filter_input( INPUT_POST, $prefix . 'submit' ) &&
			(bool) wp_verify_nonce( filter_input( INPUT_POST, $prefix . 'nonce' ) ) === true
		) {
			$need_update = true;

			/*$agreement_payment = filter_input( INPUT_POST, $prefix . 'agreement_payment' );
			$agreement_course  = filter_input( INPUT_POST, $prefix . 'agreement_course' );

			if (
				$options['agreement_payment'] == $agreement_payment &&
				$options['agreement_course'] == $agreement_course
			) {
				$need_update = false;
			} else {
				$options['agreement_payment'] = $agreement_payment;
				$options['agreement_course'] = $agreement_course;
			}*/
		}

		if ( $need_update === true ) {
			$updated = update_option( $prefix . 'options', $options );
		}

		// Include view
		include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/partials/contactform-options.php';
	} // end options_page()

	/**
	 * Render data page (in WordPress administration).
	 *
	 * @since 0.1
	 * @uses get_options()
	 * @uses wp_verify_nonce()
	 */
	public function data_page() {
		$prefix = 'odwpdcz-';

		$table = new DonkyCz_Contact_Form_Table();

		// Include view
		include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/partials/contactform-table.php';
	} // end options_page()

	/**
	 * Register TinyMCE button.
	 *
	 * @since 0.1
	 * @param array $buttons
	 * @return array
	 */
	public function tinymce_button( $buttons ) {
		array_push( $buttons, '|', 'donkycz' );
		return $buttons;
	} // end tinymce_button( $buttons )

	/**
	 * Register our TinyMCE plugin.
	 *
	 * @since 0.1
	 * @param array $plugins
	 * @return array
	 * @uses plugin_dir_url()
	 */
	public function tinymce_plugin( $plugins ) {
		$plugins['donkycz'] = plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/tinymce.js';
		return $plugins;
	} // end tinymce_plugin( $plugins )

    /**
     * Adds language file for our TinyMCE button.
     *
     * @since 0.1
     * @param array $locales
     * @return array
	 * @uses plugin_dir_path()
     */
    public function tinymce_language( $locales ) {
        $locales['odwp-donkycz-btn'] = plugin_dir_path( dirname( __FILE__ ) ) . '/admin/tinymce-i18n.php';
        return $locales;
    } // end tinymce_language( $locales )

	/**
	 * Renders contact form (replaces the shortcode).
	 * 
	 * @since 0.1
	 * @return string
	 * @uses plugin_dir_path()
	 * @uses plugin_dir_url()
	 * @uses wp_register_script()
	 * @uses wp_localize_script()
	 * @uses wp_enqeue_scrript()
	 */
	public function render() {
		$res = $this->process_form();

		if ( true === $res ) {
			// Form was submitted and processed successfully...
			echo '<p>Form was successfully submitted</p>';
			return;
		}
		elseif ( false === $res ) {
			// Form was submitted but processing failed...
			echo '<p>Form was <b>not</b> submitted</p>';
			return;
		}
		
		// Form is not submitted yet...

		/**
		 * @var string $prefix Form elements ID prefix.
		 */
		$prefix = 'odwpdcz-';

		/**
		 * @var WP_Query $toys Available toys.
		 */
		$toys = DonkyCz_Custom_Post_Type_Toy::get_toys();

		// Append required JavaScript
		$scriptname = $prefix . 'contact-form';
		$scripturl  = plugin_dir_url( dirname( __FILE__ ) ) . 'public/js/contact-form.js';
		wp_register_script( $scriptname, $scripturl, array( 'jquery' ), DonkyCz::VERSION );
		wp_localize_script( $scriptname, 'pluginObject', array(
			'formPrefix' => $prefix,
			'ajaxUrl' => null,
		) );
		wp_enqueue_script( $scriptname );

		// Include view
		include_once plugin_dir_path( dirname( __FILE__ ) ) . '/public/partials/contact-form.php';
	} // end render()

	/**
	 * @since 0.1
	 * @return TRUE|FALSE|NULL Returns NULL when form was not submitted.
	 *                         Returns FALSE when form was submitted but not 
	 *                         processed correctly and TRUE if form was 
	 *                         submitted and processed successfully.
	 * @uses wp_verify_nonce()
	 */
	private function process_form() {
		$prefix = 'odwpdcz-';
		$submit = filter_input( INPUT_POST, 'submit' );
		$nonce  = filter_input( INPUT_POST, 'cfnonce' );

		if (
			! filter_input( INPUT_POST, $prefix . 'submit' ) ||
			! (bool) wp_verify_nonce( filter_input( INPUT_POST, $prefix . 'nonce' ) )
		) {
			return;
		}
var_dump($_POST);
exit();

		if ( ! $submit || ! $nonce ) {
			return null;
		}

		if ( ! wp_verify_nonce( $nonce, 'contact-form' ) ) {
			// XXX Nonce not verified!
			return false;
		}
		
		// XXX ...

		return true;
	} // end process_form()
} // End of DonkyCz_Contact_Form_Shortcode

endif;