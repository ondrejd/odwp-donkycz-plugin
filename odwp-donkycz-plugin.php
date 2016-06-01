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
 * Plugin URI:        https://github.com/ondrejd/odwp-donkycz-plugin
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

if ( ! class_exists( 'DonkyCz' ) ):

/**
 * Main class of the plugin.
 *
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @package odwp-donkycz-plugin
 * @since 0.1
 */
class DonkyCz {
	const SLUG = 'odwp-donkycz-plugin';
	const VERSION = '0.1';

	/**
	 * Default options of the plugin.
	 * @var array $default_options
	 */
	protected $default_options = array(
		// ...
	);

	/**
	 * Holds instance of class self. Part of singleton implementation.
	 * @var DonkyCz $instance
	 */
	private static $instance;

	/**
	 * Returns instance of class self. Part of singleton implementation.
	 *
	 * @return DonkyCz
	 */
	public static function get_instance() {
		if ( ! ( self::$instance instanceof DonkyCz ) ) {
			self::$instance = new DonkyCz();
		}

		return self::$instance;
	} // end get_instance()

	/**
	 * Constructor. Called when plugin is initialised.
	 *
	 * @since 0.1
	 * @access protected
	 */
	protected function __construct() {
		//$this->plugin_url = plugin_dir_url( __FILE__ );

		$this->set_locale();
		$this->load_dependencies();

		$this->define_custom_post_types();
		$this->define_custom_taxonomies();

		$this->define_public_hooks();

		if ( is_admin() ) {
			$this->define_admin_hooks();
		}
	}

	/**
	 * @since 0.1
	 * @access protected
	 * @param string $meta_key Correct meta key for the columns.
	 * @param int    $user_id  ID of user for whom we want to check hidden columns settings. 
	 * @param array  $current  Array with ID of columns we want to hide by default.
	 */
	protected function set_default_hidden_columns( $meta_key, $user_id, $default = array() ) {
		$current = get_user_option( $meta_key, $user_id );

		if ( is_array( $current ) ) {
			return;
		}

		update_user_option( $user_id, $meta_key, $default, true );
	}

	/**
	 * @since 0.1
	 * @access protected
	 * @param int    $post_id  ID of the post we want to update.
	 * @param string $meta_key Meta key.
	 * @param string $value    Meta value.
	 */
	protected function update_meta_key( $post_id, $meta_key, $value ) {
		$current_value = get_post_meta( $post_id, $meta_key, true );

		if ( $value && '' == $current_value ) {
			add_post_meta( $post_id, $meta_key, $value, true);
		}
		elseif ( $value && $value != $current_value ) {
			update_post_meta( $post_id, $meta_key, $value );
		}
		elseif ( '' == $value && $current_value ) {
			delete_post_meta( $post_id, $meta_key, $current_value );
		}
	}

	/**
	 * Activate plugin.
	 *
	 * @since 0.1
	 * @global wpdb $wpdb
	 */
	public static function activate() {
		require_once plugin_dir_path( __FILE__ ) . '/includes/class-donkycz-contact-form-model.php';

		DonkyCz_Contact_Form_Model::create_table();
	}

	/**
	 * Deactivate plugin.
	 *
	 * @since 0.1
	 */
	public static function deactivate() {
		// ...
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
		load_plugin_textdomain( self::SLUG, false, dirname( __FILE__ ) . '/languages/' );
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
	 * - {@see DonkyCz_Contact_Form_Model}. Defines simple data model for contact form.
	 * - {@see DonkyCz_Contact_Form_Table}. Defines data listing table in WP admin.
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
			$plugin_dir . 'includes/class-donkycz-custom-post-type-toy.php',
			$plugin_dir . 'includes/class-donkycz-taxonomy-toy-category.php',
			$plugin_dir . 'includes/class-donkycz-contact-form-model.php',
			$plugin_dir . 'includes/class-donkycz-contact-form-table.php',
			//$plugin_dir . 'admin/class-donkycz-admin.php',
			//$plugin_dir . 'public/class-donkycz-public.php',
		);

		foreach ( $main_files as $file ) {
			if ( file_exists( $file ) && is_readable( $file ) ) {
				require_once $file;
			}
		}
	}

	/**
	 * Register all custom post types.
	 *
	 * @since 0.1
	 * @access private
	 */
	private function define_custom_post_types() {
		$type_toy = new DonkyCz_Custom_Post_Type_Toy();

		// Initialize custom post type
		$this->loader->add_action( 'init', $type_toy, 'init' );
	}

	/**
	 * Register all custom taxonomies.
	 *
	 * @since 0.1
	 * @access private
	 */
	private function define_custom_taxonomies() {
		$taxonomy_toy = new DonkyCz_Taxonomy_Toy_Category();

		// Initialize taxonomy
		$this->loader->add_action( 'init', $taxonomy_toy, 'init' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since 0.1
	 * @access private
	 */
	private function define_admin_hooks() {
		// Scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		// Metaboxes
		add_action( 'add_meta_boxes', array( $this, 'add_toy_metaboxes' ) );
		add_action( 'save_post', array( $this, 'save_toy_metaboxes' ) );
		add_action( 'new_to_publish', array( $this, 'save_toy_metaboxes' ) );
		// Toy list table
		add_filter( 'manage_toy_posts_columns', array( $this, 'toy_list_manage_posts_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'toy_list_manage_custom_columns' ), 10, 2 );
		add_filter( 'manage_edit-toy_sortable_columns', array( $this, 'toy_list_manage_sortable_columns' ) );
		add_action( 'restrict_manage_posts', array( $this, 'toy_list_restrict_listings_by_category' ) );
		add_action( 'contextual_help', array( $this, 'toy_list_contextual_help' ), 10, 3 );
		add_action( 'wp_login', array( $this, 'toy_list_set_default_hidden_columns' ), 10, 2 );
return;// TODO !!!!
		// Contacts list table
		// Perform row actions within toys list table.
		add_filter( 'request', array( $this, 'contact_list_perform_row_actions' ) );
		add_action( 'wp_login', array( $this, 'contact_list_set_default_hidden_columns' ), 10, 2);

		//contact_list_add_screen_options
		//contact_list_set_screen_options

		// Contact form
		add_action( 'admin_init', array( $this, 'contact_form_admin_init' ) );
		add_action( 'admin_menu', array( $this, 'contact_form_admin_menu' ) );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since 0.1
	 * @access private
	 */
	private function define_public_hooks() {
		// Scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts' ) );
		// Add custom post type to WP front-page
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

		// Contact form
		add_shortcode( 'contact-form', array( $this, 'contact_form_render' ) );
		add_action( 'wp_ajax_process_form_ajax', array( $this, 'contact_form_process_ajax' ) );
        add_action( 'wp_ajax_nopriv_process_form_ajax', array( $this, 'contact_form_process_ajax' ) );
	}

	/**
	 * Register the stylesheets for front-end.
	 *
	 * @since 0.1
	 */
	public function enqueue_public_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScripts for front-end.
	 *
	 * @since 0.1
	 */
	public function enqueue_public_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/public.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Register the stylesheets for administration.
	 *
	 * @since 0.1
	 */
	public function enqueue_admin_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScripts for administration.
	 *
	 * @since 0.1
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Show just our custom post type on WP front page.
	 *
	 * @since 0.1
	 * @param WP_Query $query
	 * @return WP_Query
	 */
	public function pre_get_posts( $query ) {
		if ( is_home() && $query->is_main_query() ) {
			$query->set( 'post_type', array( DonkyCz_Custom_Post_Type_Toy::TYPE ) );
		}

		return $query;
	}

	/**
	 * Renders contact form (replaces the shortcode).
	 * 
	 * @since 0.1
	 * @return string
	 * @todo We shoud use filter hook `request`!
	 */
	public function contact_form_render() {
		$res = $this->contact_form_process();

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
		$scripturl  = plugin_dir_url( dirname( __FILE__ ) ) . 'js/contact-form.js';

		wp_register_script( $scriptname, $scripturl, array( 'jquery' ), DonkyCz::VERSION );
		wp_localize_script( $scriptname, 'pluginObject', array(
			'prefix' => $prefix,
			'url'    => admin_url( 'admin-ajax.php' )
		) );
		wp_enqueue_script( $scriptname );

		// Include view
		include_once plugin_dir_path( dirname( __FILE__ ) ) . '/partials/public/contact-form.php';
	}

	/**
	 * Process submitting of contact form.
	 *
	 * @since 0.1
	 * @return mixed Returns NULL when form was not submitted. Returns FALSE when submitted but not processed and TRUE if all is OK.
	 *
	 * @todo Return (or set) error message here!
	 * @todo Return FALSE and set error message when entity saving doesn't end well.
	 * @todo Should be called using filter hook `request` not directly from {@see DonkyCz_Public::contact_form_render()}!
	 */
	public function contact_form_process() {
		$prefix = 'odwpdcz-';
		$submit = filter_input( INPUT_POST, 'submit' );
		$nonce  = filter_input( INPUT_POST, 'cfnonce' );

		if ( ! $submit || ! $nonce ) {
			return null;
		}

		if ( ! wp_verify_nonce( $nonce, 'contact-form' ) ) {
			// TODO Error message: "Nonce not verified!"
			return false;
		}

		$entity = $this->contact_form_get_data();
		$res = $entity->save();

		if ( $res === false ) {
			// TODO Error message: "Data was not saved to the database!"
			// TODO return false;
			//echo "\nData was not saved to the database!\n";
			return false;
		}

		return true;
	}

	/**
	 * Process submitting of contact form using AJAX. 
	 *
	 * @since 0.1
	 */
	public function contact_form_process_ajax() {
		//check_ajax_referer( 'ajax_contact_form', 'security' );

		$entity = $this->contact_form_get_data();
		$res = $entity->save();

		if ( $res === false ) {
			wp_send_json_error( array(
				'error' => __( 'Při ukládání dat z formuláře do databáze nastala chyba!', self::SLUG ) 
			) );
		}

		wp_send_json_success( array(
			'message' => __( 'Formulář byl úspěšně odeslán.', self::SLUG )
		) );
	}

	/**
	 * @since 0.1
	 * @access private
	 * @return DonkyCz_Contact_Form_Model Returns new contact created from the POST data.
	 */
	protected function contact_form_get_data() {
		$data = array( 'id' => null , 'read' => 0, 'created' => date( 'Y-m-d H:i:s' ) );
		$data['sender'] = filter_input( INPUT_POST, 'sender' );
		$data['email'] = filter_input( INPUT_POST, 'email' );
		$data['message'] = filter_input( INPUT_POST, 'message' );
		$data['toy_id'] = filter_input( INPUT_POST, 'toy_id' );
		$data['toy_spec'] = filter_input( INPUT_POST, 'toy_spec' );
		
		return new DonkyCz_Contact_Form_Model( $data );
	}

	/**
	 * Add meta boxes for courses.
	 *
	 * @since 0.1
	 */
	public function add_toy_metaboxes() {
		add_meta_box( 'toy_description_metabox', __( 'Krátký popis', self::SLUG ), array( $this, 'toy_metabox_description' ), 'toy', 'normal', 'high' );
		add_meta_box( 'toy_material_metabox', __( 'Použitý materiál', self::SLUG ), array( $this, 'toy_metabox_material' ), 'toy', 'normal', 'high' );
		add_meta_box( 'toy_dimensions_metabox', __( 'Rozměry hračky', self::SLUG ), array( $this, 'toy_metabox_dimensions' ), 'toy', 'normal', 'high' );
		add_meta_box( 'toy_price_metabox', __( 'Cena', self::SLUG ), array( $this, 'toy_metabox_price' ), 'toy', 'side', 'core' );
		add_meta_box( 'toy_stock_metabox', __( 'Skladem', self::SLUG ), array( $this, 'toy_metabox_stock' ), 'toy', 'side', 'default' );
		add_meta_box( 'toy_order_metabox', __( 'Pořadí', self::SLUG ), array( $this, 'toy_metabox_order' ), 'toy', 'side', 'default' );
	}

	/**
	 * Renders content for `toy_description_metabox`.
	 *
	 * @since 0.1
	 * @param WP_Post $post Object of the edited post.
	 */
	public function toy_metabox_description( $post ) {
		$description = get_post_meta( $post->ID, 'toy_description', true );

		include_once plugin_dir_path( __FILE__ ) . 'partials/admin/metabox-description.php';
	}

	/**
	 * Renders content for `toy_material_metabox`.
	 *
	 * @since 0.1
	 * @param WP_Post $post Object of the edited post.
	 */
	public function toy_metabox_material( $post ) {
		$material = get_post_meta( $post->ID, 'toy_material', true );

		include_once plugin_dir_path( __FILE__ ) . 'partials/admin/metabox-material.php';
	}

	/**
	 * Renders content for `toy_dimensions_metabox`.
	 *
	 * @since 0.1
	 * @param WP_Post $post Object of the edited post.
	 */
	public function toy_metabox_dimensions( $post ) {
		$dimensions = get_post_meta( $post->ID, 'toy_dimensions', true );

		include_once plugin_dir_path( __FILE__ ) . 'partials/admin/metabox-dimensions.php';
	}

	/**
	 * Renders content for `toy_price_metabox`.
	 *
	 * @since 0.1
	 * @param WP_Post $post Object of the edited post.
	 */
	public function toy_metabox_price( $post ) {
		$price = get_post_meta( $post->ID, 'toy_price', true );

		include_once plugin_dir_path( __FILE__ ) . 'partials/admin/metabox-price.php';
	}

	/**
	 * Renders content for `toy_stock_metabox`.
	 *
	 * @since 0.1
	 * @param WP_Post $post Object of the edited post.
	 */
	public function toy_metabox_stock( $post ) {
		$stock = get_post_meta( $post->ID, 'toy_stock', true );

		include_once plugin_dir_path( __FILE__ ) . 'partials/admin/metabox-stock.php';
	}

	/**
	 * Renders content for `toy_order_metabox`.
	 *
	 * @since 0.1
	 * @param WP_Post $post Object of the edited post.
	 */
	public function toy_metabox_order( $post ) {
		$order = get_post_meta( $post->ID, 'toy_order', true );

		include_once plugin_dir_path( __FILE__ ) . 'partials/admin/metabox-order.php';
	}

	/**
	 * Saves values metaboxes for toy custom post type.
	 *
	 * @since 0.1
	 * @param int $post_id ID of the edited post.
	 */
	public function save_toy_metaboxes( $post_id ) {
		// Skip
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check user privileges
		if ( !current_user_can( 'edit_post', $post_id ) ) {
			// TODO Print admin notice!
			return;
		}

		// Description
		$description = filter_input( INPUT_POST, 'toy_description' );
		$this->update_meta_key( $post_id, 'toy_description', $description );
		// Material
		$material = filter_input( INPUT_POST, 'toy_material' );
		$this->update_meta_key( $post_id, 'toy_material', $material );
		// Dimensions
		$dimensions = filter_input( INPUT_POST, 'toy_dimensions' );
		$this->update_meta_key( $post_id, 'toy_dimensions', $dimensions );
		// Price
		$price = filter_input( INPUT_POST, 'toy_price' );
		$this->update_meta_key( $post_id, 'toy_price', $price );
		// Stock
		$stock = filter_input( INPUT_POST, 'toy_stock' );
		$this->update_meta_key( $post_id, 'toy_stock', $stock );
		// Order
		$order = filter_input( INPUT_POST, 'toy_order' );
		$this->update_meta_key( $post_id, 'toy_order', $order );
	}

	/**
	 * Set columns for toys list table.
	 *
	 * @since 0.1
	 * @param array $columns Current columns.
	 * @return array Our columns.
	 */
	public function toy_list_manage_posts_columns( $columns ) {
		$columns['toy_description'] = __( 'Krátký popis', self::SLUG );
		$columns['toy_material'] = __( 'Použitý materiál', self::SLUG );
		$columns['toy_dimensions'] = __( 'Rozměry hračky', self::SLUG );
		$columns['toy_price'] = __( 'Cena', self::SLUG );
		$columns['toy_stock'] = __( 'Sklad', self::SLUG );
		$columns['toy_order'] = __( 'Pořadí', self::SLUG );
		$columns['toy_image'] = __( 'Obrázek', self::SLUG );

		return $columns;
	}

	/**
	 * Set content for our custom columns in toys list table.
	 *
	 * @since 0.1
	 * @param string $column  ID of the current column.
	 * @param int    $post_id ID of the current post.
	 */
	public function toy_list_manage_custom_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'toy_description':
				$description = get_post_meta( $post_id, 'toy_description', true );
				echo $description;
				break;

			case 'toy_material':
				$material = get_post_meta( $post_id, 'toy_material', true );
				echo $material;
				break;

			case 'toy_dimensions':
				$dimensions = get_post_meta( $post_id, 'toy_dimensions', true );
				echo $dimensions;
				break;

			case 'toy_price':
				$price = get_post_meta( $post_id, 'toy_price', true );
				if ( empty( $price ) ) {
					echo __( '0 Kč', self::SLUG );
				} else {
					printf( __( '%s Kč', self::SLUG ), $price );
				}
				break;

			case 'toy_stock':
				$stock = get_post_meta( $post_id, 'toy_stock', true );
				if ( empty( $stock ) ) {
					echo __( '0 ks', self::SLUG );
				} else {
					printf( __( '%s ks', self::SLUG ), $stock );
				}
				break;

			case 'toy_order':
				$order = get_post_meta( $post_id, 'toy_order', true );
				echo ( empty( $order ) ) ? '0' : $order;
				break;

			case 'toy_image':
				$image = DonkyCz_Custom_Post_Type_Toy::get_toy_image( $post_id );
				if ( $image ) {
					echo '<img src="' . $image . '" style="width: 70px;" />';
				}
				break;
		}
	}

	/**
	 * Set our custom columns sortable in toys list table.
	 *
	 * @since 0.1
	 * @param array $columns Currently sortable columns.
	 * @return array Array of our sortable columns.
	 */
	public function toy_list_manage_sortable_columns( $columns ) {
		$taxonomy = DonkyCz_Taxonomy_Toy_Category::TAXONOMY;

		$columns['toy_description'] = 'toy_description';
		$columns['toy_material'] = 'toy_material';
		$columns['toy_dimensions'] = 'toy_dimensions';
		$columns['toy_price'] = 'toy_price';
		$columns['toy_stock'] = 'toy_stock';
		$columns['toy_order'] = 'toy_order';
		$columns['taxonomy-' . $taxonomy] = $taxonomy;

		return $columns;
	}

	/**
	 * Hook for `restrict_manage_posts` action (for toys list table).
	 *
	 * @since 0.1
	 * @global string   $typenow
	 * @global WP_Query $wp_query
	 */
	public function toy_list_restrict_listings_by_category() {
		global $typenow;
		global $wp_query;

		if ( $typenow == 'toy' ) {
			$taxonomy = DonkyCz_Taxonomy_Toy_Category::TAXONOMY;
			$category_taxonomy = get_taxonomy( $taxonomy );

			$selected_category = array_key_exists('toy_category', $wp_query->query)
				? $wp_query->query['toy_category']
				: null;

			wp_dropdown_categories(array(
				'show_option_all' =>  __( 'Zobrazit všechny kategorie', self::SLUG ),
				'taxonomy'        =>  $taxonomy,
				'name'            =>  $taxonomy,
				'orderby'         =>  'name',
				'selected'        =>  $selected_category,
				'hierarchical'    =>  true,
				'depth'           =>  3,
				'show_count'      =>  true,
				'hide_empty'      =>  true
			));
		}
	}

	/**
	 * Returns contextual help for toys listing.
	 *
	 * @since 0.1
	 * @return string HTML with help.
	 */
	public function toy_list_contextual_help( $contextual_help, $screen_id, $screen ) { 
		if ( 'edit-toy' == $screen->id ) {
			return sprintf(
				__( '<h2>Hračky</h2><p>Na této stránce najdete přehled již vytvořených hraček. Přehled můžete řadit dle pořadí či upravit jeho vzhled pomocí <i>%s</i>.</p><p>Zobrazit/editovat detaily harčky můžete po kliknutí na její název. Můžete také použít rychlou úpravu pomocí tlačítka <i>%s</i> či <i>%s</i>.</p>', self::SLUG ),
				__( 'Screen Options' ),
				__( 'Quick Edit' ),
				__( 'Bulk Actions' )
			);
		}

		return __( '<h2>Upravování hraček</h2><p>Na této stránce můžete vložit krátký popis hračky, upřesnit materiály z kteréhoých byla vyrobena, případně zadat její cenu a počet kusů na skladě (tyto dvě položky nejsou vždy zobrazovány uživatelům).</p><p>Je důležité, aby krátký popis i použité materiály nepřesáhly stanovenou délku textu, protože pak dojde k nekorektnímu zobrazení hračky pro návštěvníky stránky.</p>', self::SLUG );
	}

	/**
	 * Hide some columns in toys list table by default.
	 *
	 * @since 0.1
	 * @param string  $user_login String with user login.
	 * @param WP_User $user       Initialized user object (if user login is correct).
	 */
	public function toy_list_set_default_hidden_columns( $user_login, $user ) {
		if ( ( $user instanceof WP_User ) ) {
			$this->set_default_hidden_columns( 'manageedit-toycolumnshidden', $user->ID, array(
				'date',
				'toy_description',
				'toy_price',
				'toy_stock',
			) );
		}
	}

	/**
	 * Register contact form updates for TinyMCE editor. 
	 *
	 * @since 0.1
	 */
	public function contact_form_admin_init() {
		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
			if ( get_user_option( 'rich_editing' ) == 'true' ) {
				add_filter( 'mce_external_plugins', array( $this, 'contact_form_tinymce_plugin' ) );
				add_filter( 'mce_buttons', array( $this, 'contact_form_tinymce_button' ) );
				add_filter( 'mce_external_languages', array( $this, 'contact_form_tinymce_language' ) );
			}
		}
	}

	/**
	 * Register menu for contact form.
	 *
	 * @since 0.1
	 */
	public function contact_form_admin_menu() {
		$icon   = plugin_dir_url( dirname( __FILE__ ) . 'odwp-donkycz-plugin.php' ) . 'icon-20.png';
		$prefix = 'odwpdcz-';

		$hook = add_submenu_page(
			'edit.php?post_type=' . DonkyCz_Custom_Post_Type_Toy::TYPE,
			__( 'Kontaktní formulář - Data', self::SLUG ),
			__( 'Kontaktní form.', self::SLUG ),
			'manage_options',
			$prefix . 'odwpdcz-data_page',
			array( $this, 'contact_from_list' )
		);
		add_action( "load-$hook", array( $this, 'contact_list_add_screen_options' ) );
		add_filter( 'set-screen-option', array( $this, 'contact_list_set_screen_options' ), 10, 3 );
	}

	/**
	 * Render contacts listing.
	 *
	 * @since 0.1
	 */
	public function contact_from_list() {
		$prefix = 'odwpdcz-';

		$table = new DonkyCz_Contact_Form_Table();

		// Include view
		include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/partials/contactform-table.php';
	}

	/**
	 * Register TinyMCE contact form button.
	 *
	 * @since 0.1
	 * @param array $buttons
	 * @return array
	 */
	public function contact_form_tinymce_button( $buttons ) {
		array_push( $buttons, '|', 'donkycz' );
		return $buttons;
	}

	/**
	 * Register our TinyMCE contact form plugin.
	 *
	 * @since 0.1
	 * @param array $plugins
	 * @return array
	 * @uses plugin_dir_url()
	 */
	public function contact_form_tinymce_plugin( $plugins ) {
		$plugins['donkycz'] = plugin_dir_url( dirname( __FILE__ ) ) . 'js/tinymce.js';
		return $plugins;
	}

    /**
     * Adds language file for our TinyMCE contact form button.
     *
     * @since 0.1
     * @param array $locales
     * @return array
	 * @uses plugin_dir_path()
     */
    public function contact_form_tinymce_language( $locales ) {
        $locales['odwp-donkycz-btn'] = plugin_dir_path( dirname( __FILE__ ) ) . '/admin/tinymce-i18n.php';
        return $locales;
    }

	/**
	 * Hide some columns in contacts list table by default.
	 *
	 * @since 0.1
	 * @param string  $user_login String with user login.
	 * @param WP_User $user       Initialized user object (if user login is correct).
	 */
	public function contact_list_set_default_hidden_columns( $user_login, $user ) {
		if ( ( $user instanceof WP_User ) ) {
			$this->set_default_hidden_columns( 'managetoy_page_odwpdcz-data_pagecolumnshidden', $user->ID, array(
				'id'
			) );
		}
	}

	/**
	 * Performs rows actions for table list with contacts.
	 *
	 * Called via filter hook `request` from {@see DonkyCz::define_admin_hooks}.
	 *
	 * @since 0.1
	 * @param array $query_vars The array of requested query variables.
	 */
	public function contact_list_perform_row_actions( $query_vars ) {
		// Get parameters
		//$action = filter_input( INPUT_GET, 'action' );
		//$contact_id = ( int ) filter_input( INPUT_GET, 'contact_id' );
		$action = array_key_exists( 'action', $query_vars ) ? $query_vars['action'] : null;
		$contact_id = array_key_exists( 'contact_id', $query_vars ) ? ( int ) $query_vars['contact_id'] : null;

		if ( empty( $action ) || $contact_id <= 0 ) {
			return;
		}

		// Get contact
		$contact = DonkyCz_Contact_Form_Model::find_by_id( $contact_id );

		if ( ! ( $contact instanceof DonkyCz_Contact_Form_Model ) ) {
			return;
		}

		// Delete action
		if ( $action == 'delete' ) {
			 $contact->remove();
			 return;
		}

		// Read/unread action
		$contact->read = ( $action == 'read' ) ? true : false;
		$contact->save();
	}

	/**
	 * Add screen options.
	 *
	 * @since 0.1
	 * @global DonkyCz_Contact_Form_Table $donkycz_contact_form_table
	 */
	public function contact_list_add_screen_options() {
		// TODO global $donkycz_contact_form_table;
		
		$option = 'per_page';
		$args = array(
			'label' => __( 'Záznamů na stránce', self::SLUG ),
			'default' => 8,
			'option' => 'contacts_per_page'
		);
		add_screen_option( $option, $args );

		// TODO $donkycz_contact_form_table = new DonkyCz_Contact_Form_Table();
	}

	/**
	 * Set screen option.
	 *
	 * @since 0.1
	 * @param string $status
	 * @param string $option
	 * @param mixed  $value
	 * @return mixed
	 */
	public function contact_list_set_screen_options( $status, $option, $value ) {
		return $value;
	}
}

endif;


// Plugin's activation/deactivation
register_activation_hook( __FILE__, array( 'DonkyCz', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'DonkyCz', 'deactivate' ) );
// Initialize plugin
add_action( 'plugins_loaded', array( 'DonkyCz', 'get_instance' ) );