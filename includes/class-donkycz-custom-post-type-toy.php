<?php
/**
 * Custom post type for toys.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://github.com/ondrejd/odwp-donky_cz
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin\includes
 */

if ( !class_exists( 'DonkyCz_Custom_Post_Type_Toy' ) ):

/**
 * Class implementing course custom post type.
 *
 * @since 0.1
 * @package odwp-donky_cz
 * @subpackage odwp-donky_cz\includes
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
			'has_archive' => true
		);

		register_post_type( self::NAME, $args );
	}
}

endif;