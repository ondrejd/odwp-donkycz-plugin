<?php
/**
 * Custom post type for toys.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/includes
 */

if ( !class_exists( 'DonkyCz_Custom_Post_Type_Toy' ) ):

/**
 * Class implementing toy custom post type.
 *
 * @since 0.1
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/includes
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
class DonkyCz_Custom_Post_Type_Toy {
	/**
	 * Name of taxonomy.
	 * @const string
	 */
	const NAME = 'toy';

	/**
	 * Initialize custom post type.
	 *
	 * @since 0.1
	 */
	public function init() {
		//echo plugin_dir_url( dirname( __FILE__ ) . 'odwp-donkycz-plugin.php' ) . 'icon-32.png';
		$labels = array(
			'name' => _x( 'Hračky', 'post type general name', DonkyCz::SLUG ),
			'singular_name' => _x( 'Vytvořit hračku', 'post type singular name', DonkyCz::SLUG ),
			'add_new' => _x( 'Přidej novou', 'add new course', DonkyCz::SLUG ),
			'add_new_item' => __( 'Přidej novou hračku', DonkyCz::SLUG ),
			'edit_item' => __( 'Edituj hračku', DonkyCz::SLUG ),
			'new_item' => __( 'Nová hračka', DonkyCz::SLUG ),
			'view_item' => __( 'Zobraz hračku', DonkyCz::SLUG ),
			'search_items' => __( 'Prohledej hračky', DonkyCz::SLUG ),
			'not_found' => __( 'Žádná hračka nebyla nalezena', DonkyCz::SLUG ),
			'not_found_in_trash' => __( 'Žádná hračka nebyla v koši nalezena', DonkyCz::SLUG ),
			'all_items' => __( 'Přehled hraček', DonkyCz::SLUG ),
			'archives' => __( 'Archiv hraček', DonkyCz::SLUG ),
			'menu_name' => __( 'Hračky', DonkyCz::SLUG )
		);

		$args = array(
			'labels' => $labels,
			'description' => __( 'Správa kurzů poskytovaných Richardem Vojtíkem.', DonkyCz::SLUG ),
			'public' => true,
			'menu_position' => 5,
			'menu_icon' => plugin_dir_url( dirname( __FILE__ ) . 'odwp-donkycz-plugin.php' ) . 'icon-20.png',
			'supports' => array( 'title', 'thumbnail', 'revisions' ),
			'taxonomies' => array(),//array( 'DonkyCz_Taxonomy_Toy_Category::NAME' )
			// Note: Taxonomy is registered for this post type in its own class `DonkyCz_Taxonomy_Toy_Category`
			'has_archive' => true
		);

		register_post_type( self::NAME, $args );
	}

	/**
	 * Returns toys.
	 *
	 * @static
	 * @since 0.1
	 * @return WP_Query
	 */
	public static function get_toys() {
		$args = array(
			'post_type' => self::NAME,
			'no_paging' => true,
			'posts_per_page' => -1
		);

		return new WP_Query( $args );
	}
}

endif;