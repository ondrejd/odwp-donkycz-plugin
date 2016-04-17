<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/includes
 */

if ( !class_exists( 'DonkyCz' ) ):

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since 0.1
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/includes
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
class DonkyCz {
	/**
	 * @const string
	 */
	const SLUG = 'odwp-donkycz-plugin';

	/**
	 * @const string
	 */
	const VERSION = '0.1';

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since 0.1
	 * @access protected
	 * @var DonkyCz_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since 0.1
	 * @access protected
	 * @var string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since 0.1
	 * @access protected
	 * @var string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 0.1
	 */
	public function __construct() {
		$this->plugin_name = 'odwp-donkycz';
		$this->version = '0.1';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_custom_post_types();
		$this->define_taxonomies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - {@see DonkyCz_Loader}. Orchestrates the hooks of the plugin.
	 * - {@see DonkyCz_i18n}. Defines internationalization functionality.
	 * - {@see DonkyCz_Admin}. Defines all hooks for the admin area.
	 * - {@see DonkyCz_Public}. Defines all hooks for the public side of the site.
	 * - {@see DonkyCz_Custom_Post_Type_Toy}. Defines new custom post type.
	 * - {@see DonkyCz_Taxonomy_Toy_Category}. Defines new taxonomy for our custom post type.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since 0.1
	 * @access private
	 */
	private function load_dependencies() {
		$plugin_dir = plugin_dir_path( dirname( __FILE__ ) );
		$main_files = array(
			// The class responsible for orchestrating the actions and filters of the core plugin.
			$plugin_dir . 'includes/class-donkycz-loader.php',
			// The class responsible for defining internationalization functionality of the plugin.
			$plugin_dir . 'includes/class-donkycz-i18n.php',
			// The class responsible for defining all actions that occur in the admin area.
			$plugin_dir . 'admin/class-donkycz-admin.php',
			// The class responsible for defining all actions that occur in the public-facing side of the site.
			$plugin_dir . 'public/class-donkycz-public.php',
			// Custom post type and its taxonomy
			$plugin_dir . 'includes/class-donkycz-custom-post-type-toy.php',
			$plugin_dir . 'includes/class-donkycz-taxonomy-toy-category.php',
			// Contact form shortcode
			$plugin_dir . 'includes/class-donkycz-contact-form-shortcode.php'
		);

		foreach ( $main_files as $file ) {
			if ( file_exists( $file ) && is_readable( $file ) ) {
				require_once $file;
			}
		}

		$this->loader = new DonkyCz_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the {@see DonkyCz_i18n} class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since 0.1
	 * @access private
	 */
	private function set_locale() {
		$plugin_i18n = new DonkyCz_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since 0.1
	 * @access private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new DonkyCz_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// Metaboxes
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_toy_metaboxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_toy_metaboxes' );
		$this->loader->add_action( 'new_to_publish', $plugin_admin, 'save_toy_metaboxes' );
		// Toy list
		$this->loader->add_filter( 'manage_toy_posts_columns', $plugin_admin, 'toy_list_manage_posts_columns' );
		$this->loader->add_action( 'manage_posts_custom_column', $plugin_admin, 'toy_list_manage_custom_columns', 10, 2 );
		$this->loader->add_filter( 'manage_edit-toy_sortable_columns', $plugin_admin, 'toy_list_manage_sortable_columns' );
		$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'toy_list_restrict_listings_by_category' );
		$this->loader->add_action( 'contextual_help', $plugin_admin, 'toy_list_contextual_help', 10, 3 );

		/**
		 * Hide some columns by default
		 * @link https://wordpress.org/support/topic/default-custom-post-column-to-off-in-screen-options
		 */
		$this->loader->add_action( 'wp_login', $plugin_admin, 'toy_list_set_default_hidden_columns' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since 0.1
	 * @access private
	 */
	private function define_public_hooks() {
		$plugin_public = new DonkyCz_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$contact_form = new DonkyCz_Contact_Form_Shortcode();

		$this->loader->add_action( 'init', $contact_form, 'init' );
	}

	/**
	 * @since 0.1
	 * @access private
	 */
	private function define_taxonomies() {
		$taxonomy = new DonkyCz_Taxonomy_Toy_Category();

		$this->loader->add_action( 'init', $taxonomy, 'init' );
	}

	/**
	 * @since 0.1
	 * @access private
	 */
	private function define_custom_post_types() {
		$custom_post_type = new DonkyCz_Custom_Post_Type_Toy();

		$this->loader->add_action( 'init', $custom_post_type, 'init' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 0.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since 0.1
	 * @return string The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since 0.1
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since 0.1
	 * @return string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}

endif;