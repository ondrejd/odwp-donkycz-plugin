<?php
/**
 * Toy category taxonomy.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/includes
 */

if ( !class_exists( 'DonkyCz_Taxonomy_Toy_Category' ) ):

/**
 * Class implementing toy category taxonomy.
 *
 * @since 0.1
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/includes
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
class DonkyCz_Taxonomy_Toy_Category {
	/**
	 * Name of taxonomy.
	 * @const string
	 */
	const TAXONOMY = 'toy_category';

	/**
	 * Arguments for the custom taxonomy.
	 *
	 * @var array $args
	 */
	public $args = array();

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct() {
		$this->args = array(
			'label' => __( 'Kategorie hraček', DonkyCz::SLUG ),
			'labels' => array(
				'name' => _x( 'Kategorie hraček', 'taxonomy general name', DonkyCz::SLUG ),
				'singular_name' => _x( 'Kategorie hračky', 'taxonomy singular name', DonkyCz::SLUG ),
				'menu_name' => __( 'Kategorie hraček', DonkyCz::SLUG ),
				'all_items' => __( 'Všechny kategorie', DonkyCz::SLUG ),
				'edit_item' => __( 'Edituj kategorii', DonkyCz::SLUG ),
				'view_item' => __( 'Zobraz kategorii', DonkyCz::SLUG ),
				'update_item' => __( 'Aktualizuj kategorii', DonkyCz::SLUG ),
				'add_new_item' => __( 'Přidej novou kategorii', DonkyCz::SLUG ),
				'parent_item' => __( 'Nadřazená kategorie', DonkyCz::SLUG ),
				'parent_item_colon' => __( 'Nadřazená kategorie:', DonkyCz::SLUG ),
				'search_items' => __( 'Hledat kategorie', DonkyCz::SLUG ),
				'popular_items' => __( 'Oblíbené kategorie', DonkyCz::SLUG ),
				'separate_items_with_commas' => __( 'Oddělte kategorie čárkami', DonkyCz::SLUG ),
				'add_or_remove_items' => __( 'Přidej nebo odstraň kategorie', DonkyCz::SLUG ),
				'choose_from_most_used' => __( 'Vyberte z nejpoužívanějších kategorií', DonkyCz::SLUG ),
				'not_found' => __( 'Žádna kategorie nebyla nalezena', DonkyCz::SLUG )
			),
			'public' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => false,
			'show_in_quick_edit' => true,
			'show_admin_column' => true,
			'description' => __( 'Kategorie jednotlivých hraček', DonkyCz::SLUG ),
			'hierarchical' => false,
			'rewrite' => true,
		);
	}

	/**
	 * Initialize custom taxonomy.
	 *
	 * @since 0.1
	 */
	public function init() {
		register_taxonomy( self::TAXONOMY, DonkyCz_Custom_Post_Type_Toy::TYPE, $this->args );
	}
}

endif;