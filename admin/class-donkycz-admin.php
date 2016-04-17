<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/admin
 */

if ( !class_exists( 'DonkyCz_Admin' ) ):

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since 0.1
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/admin
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
class DonkyCz_Admin {
	/**
	 * @since 0.1
	 * @access private
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * @since 0.1
	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 0.1
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * @since 0.1
	 * @access private
	 * @param integer $post_id
	 * @param string $meta_key
	 * @param $string $value
	 * @return void
	 * @uses add_post_meta()
	 * @uses update_post_meta()
	 * @uses delete_post_meta()
	 */
	private function update_meta_key( $post_id, $meta_key, $value ) {
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
	 * Register the stylesheets for the admin area.
	 *
	 * @since 0.1
	 * @uses wp_enqueue_style()
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 0.1
	 * @uses wp_enqueue_script()
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Add meta boxes for courses.
	 *
	 * @since 0.1
	 * @uses add_meta_box()
	 */
	public function add_toy_metaboxes() {
		add_meta_box( 'toy_description_metabox', __( 'Krátký popis', DonkyCz::SLUG ), array( $this, 'toy_metabox_description' ), 'toy', 'normal', 'high' );
		add_meta_box( 'toy_material_metabox', __( 'Použitý materiál', DonkyCz::SLUG ), array( $this, 'toy_metabox_material' ), 'toy', 'normal', 'high' );
		add_meta_box( 'toy_dimensions_metabox', __( 'Rozměry hračky', DonkyCz::SLUG ), array( $this, 'toy_metabox_dimensions' ), 'toy', 'normal', 'high' );
		add_meta_box( 'toy_price_metabox', __( 'Cena', DonkyCz::SLUG ), array( $this, 'toy_metabox_price' ), 'toy', 'side', 'core' );
		add_meta_box( 'toy_stock_metabox', __( 'Skladem', DonkyCz::SLUG ), array( $this, 'toy_metabox_stock' ), 'toy', 'side', 'default' );
		add_meta_box( 'toy_order_metabox', __( 'Pořadí', DonkyCz::SLUG ), array( $this, 'toy_metabox_order' ), 'toy', 'side', 'default' );
	}

	/**
	 * Renders content for `toy_description_metabox`.
	 *
	 * @since 0.1
	 * @param WP_Post $post
	 * @uses get_post_meta()
	 * @uses plugin_dir_path()
	 */
	public function toy_metabox_description( $post ) {
		$description = get_post_meta( $post->ID, 'toy_description', true );

		include_once plugin_dir_path( __FILE__ ) . 'partials/metabox-description.php';
	}

	/**
	 * Renders content for `toy_material_metabox`.
	 *
	 * @since 0.1
	 * @param WP_Post $post
	 * @uses get_post_meta()
	 * @uses plugin_dir_path()
	 */
	public function toy_metabox_material( $post ) {
		$material = get_post_meta( $post->ID, 'toy_material', true );

		include_once plugin_dir_path( __FILE__ ) . 'partials/metabox-material.php';
	}

	/**
	 * Renders content for `toy_dimensions_metabox`.
	 *
	 * @since 0.1
	 * @param WP_Post $post
	 * @uses get_post_meta()
	 * @uses plugin_dir_path()
	 */
	public function toy_metabox_dimensions( $post ) {
		$dimensions = get_post_meta( $post->ID, 'toy_dimensions', true );

		include_once plugin_dir_path( __FILE__ ) . 'partials/metabox-dimensions.php';
	}

	/**
	 * Renders content for `toy_price_metabox`.
	 *
	 * @since 0.1
	 * @param WP_Post $post
	 * @uses get_post_meta()
	 * @uses plugin_dir_path()
	 */
	public function toy_metabox_price( $post ) {
		$price = get_post_meta( $post->ID, 'toy_price', true );

		include_once plugin_dir_path( __FILE__ ) . 'partials/metabox-price.php';
	}

	/**
	 * Renders content for `toy_stock_metabox`.
	 *
	 * @since 0.1
	 * @param WP_Post $post
	 * @uses get_post_meta()
	 * @uses plugin_dir_path()
	 */
	public function toy_metabox_stock( $post ) {
		$stock = get_post_meta( $post->ID, 'toy_stock', true );

		include_once plugin_dir_path( __FILE__ ) . 'partials/metabox-stock.php';
	}

	/**
	 * Renders content for `toy_order_metabox`.
	 *
	 * @since 0.1
	 * @param WP_Post $post
	 * @uses get_post_meta()
	 * @uses plugin_dir_path()
	 */
	public function toy_metabox_order( $post ) {
		$order = get_post_meta( $post->ID, 'toy_order', true );

		include_once plugin_dir_path( __FILE__ ) . 'partials/metabox-order.php';
	}

	/**
	 * Saves values from `course_fio_metabox`.
	 *
	 * @since 0.1
	 * @param integer $post_id
	 * @return void
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
	 * Change the columns for the toys table.
	 *
	 * @since 0.1
	 * @param array $columns
	 * @return array
	 */
	public function toy_list_manage_posts_columns( $columns ) {
		/*array(4) {
		  ["cb"]=> string(25) "<input type="checkbox" />"
		  ["title"]=> string(6) "Název"
		  ["taxonomy-toy_category"]=> string(17) "Kategorie hraček"
		  ["date"]=> string(5) "Datum"
		}*/
		$columns['toy_description'] = __( 'Krátký popis', DonkyCz::SLUG );
		$columns['toy_material'] = __( 'Použitý materiál', DonkyCz::SLUG );
		$columns['toy_dimensions'] = __( 'Rozměry hračky', DonkyCz::SLUG );
		$columns['toy_price'] = __( 'Cena', DonkyCz::SLUG );
		$columns['toy_stock'] = __( 'Sklad', DonkyCz::SLUG );
		$columns['toy_order'] = __( 'Pořadí', DonkyCz::SLUG );
		$columns['toy_image'] = __( 'Obrázek', DonkyCz::SLUG );

		return $columns;
	}

	/**
	 * Add content for our custom columns for the toys table.
	 *
	 * @since 0.1
	 * @param string $column
	 * @param integer $post_id
	 * @uses get_post_meta()
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
					echo __( '0 Kč', DonkyCz::SLUG );
				} else {
					printf( __( '%s Kč', DonkyCz::SLUG ), $price );
				}
				break;

			case 'toy_stock':
				$stock = get_post_meta( $post_id, 'toy_stock', true );
				if ( empty( $stock ) ) {
					echo __( '0 ks', DonkyCz::SLUG );
				} else {
					printf( __( '%s ks', DonkyCz::SLUG ), $stock );
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
	 * Make our columns in toys table sortable.
	 *
	 * @since 0.1
	 * @param array $columns
	 * @return array
	 */
	public function toy_list_manage_sortable_columns( $columns ) {
		$columns['toy_description'] = 'toy_description';
		$columns['toy_material'] = 'toy_material';
		$columns['toy_dimensions'] = 'toy_dimensions';
		$columns['toy_price'] = 'toy_price';
		$columns['toy_stock'] = 'toy_stock';
		$columns['toy_order'] = 'toy_order';
		$columns['taxonomy-' . DonkyCz_Taxonomy_Toy_Category::NAME] = DonkyCz_Taxonomy_Toy_Category::NAME;

		return $columns;
	}

	/**
	 * Hook for `restrict_manage_posts` action (for toys listing).
	 *
	 * @since 0.1
	 * @global string $typenow
	 * @global WP_Query $wp_query
	 * @uses get_taxonomy()
	 * @uses wp_dropdown_categories()
	 */
	public function toy_list_restrict_listings_by_category() {
		global $typenow;
		global $wp_query;

		if ( $typenow == 'toy' ) {
			$taxonomy = DonkyCz_Taxonomy_Toy_Category::NAME;
			$category_taxonomy = get_taxonomy( $taxonomy );

			$selected_category = array_key_exists('toy_category', $wp_query->query)
				? $wp_query->query['toy_category']
				: null;

			wp_dropdown_categories(array(
				'show_option_all' =>  __( 'Zobrazit všechny kategorie', DonkyCz::SLUG ),
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
	 * @return string
	 */
	public function toy_list_contextual_help( $contextual_help, $screen_id, $screen ) { 
		if ( 'toy' == $screen->id ) {
			$contextual_help = __( '<h2>Upravování hraček</h2><p>Na této stránce můžete vložit krátký popis hračky, upřesnit materiály z kteréhoých byla vyrobena, případně zadat její cenu a počet kusů na skladě (tyto dvě položky nejsou vždy zobrazovány uživatelům).</p><p>Je důležité, aby krátký popis i použité materiály nepřesáhly stanovenou délku textu, protože pak dojde k nekorektnímu zobrazení hračky pro návštěvníky stránky.</p>', DonkyCz::SLUG );
		} elseif ( 'edit-toy' == $screen->id ) {
			$contextual_help = sprintf(
				__( '<h2>Hračky</h2><p>Na této stránce najdete přehled již vytvořených hraček. Přehled můžete řadit dle pořadí či upravit jeho vzhled pomocí <i>%s</i>.</p><p>Zobrazit/editovat detaily harčky můžete po kliknutí na její název. Můžete také použít rychlou úpravu pomocí tlačítka <i>%s</i> či <i>%s</i>.</p>', DonkyCz::SLUG ),
				__( 'Screen Options' ),
				__( 'Quick Edit' ),
				__( 'Bulk Actions' )
			);
		}
		return $contextual_help;
	}

	/**
	 * ...
	 *
	 * @since 0.1
	 */
	public function toy_list_set_default_hidden_columns( $user_id ) {
		$hidden_columns = get_user_option( 'manageedit-toycolumnshidden' );
		if ( !is_array( $hidden_columns ) ) {
			$hidden_columns = array();
		}

		//if ( !in_array('id', $hidden_columns ) ){
			$hidden_columns[] = 'toy_description';
			$hidden_columns[] = 'toy_price';
			$hidden_columns[] = 'toy_stock';

			add_user_meta( $user_id, 'manageedit-toycolumnshidden', $hidden_columns );
		//}
	}
}

endif;