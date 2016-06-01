<?php
/**
 * Custom post type.
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
	 * Post type (max. 20 characters, cannot contain capital letters or spaces).
	 * @const string
	 */
	const TYPE = 'toy';

	/**
	 * URL of the icon associated with the custom post type.
	 *
	 * @var string $icon
	 */
	public $icon;

	/**
	 * Arguments for the custom post type.
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
		// TODO This must works without `dirname( __FILE__ ) . 'odwp-donkycz-plugin.php!` !!!
		$this->icon = plugin_dir_url( dirname( __FILE__ ) . 'odwp-donkycz-plugin.php' ) . 'icon-20.png';
		$this->args = array(
			// Labels
			'labels' => array(
				'name' => _x( 'Hračky', 'post type general name', DonkyCz::SLUG ),
				'singular_name' => _x( 'Hračka', 'post type singular name', DonkyCz::SLUG ),
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
				'menu_name' => __( 'Hračky', DonkyCz::SLUG ),
			),

			// Frontend
			'has_archive' => true,
			'public' => true,
			'publicly_queryable' => true,

			// Admin
			'capability_type' => 'post',
			'description' => __( 'Hračky od Donky.cz', DonkyCz::SLUG ),
			'menu_icon' => $this->icon,
			'menu_position' => 5,
			'query_var' => true, // Sets the query_var for this post type. Defaults to the name of the Custom Post Type.
			'show_in_menu' => true, // Whether to show this Custom Post Type in the WordPress Administration menu.
			'show_ui' => true, // Whether to generate a UI in the WordPress Administration to allow adding, editing and deleting Posts for this Custom Post Type.
			'supports' => array(
				'editor',
				'title',
				'page-attributes',
				'revisions',
				'thumbnail',
			),
			'taxonomies' => array(),
		);
	}

	/**
	 * Initialize custom post type.
	 *
	 * @since 0.1
	 * @link https://codex.wordpress.org/Post_Types#Custom_Post_Types
	 * @link https://codex.wordpress.org/Function_Reference/register_post_type
	 * @uses register_post_type()
	 */
	public function init() {
		register_post_type( self::TYPE, $this->args );
	}

	/**
	 * @static
	 * @since 0.1
	 * @param int $toy_id
	 * @return WP_Query Returns all toys as `WP_Query`.
	 */
	public static function find_all() {
		$args = array(
			'post_type' => self::TYPE,
			'no_paging' => true,
			'posts_per_page' => -1
		);

		return new WP_Query( $args );
	}

	/**
	 * @static
	 * @since 0.1
	 * @param int $toy_id
	 * @return WP_Post Returns toy by its ID.
	 */
	public static function find_by_id( $toy_id ) {
		return get_post( $toy_id );
	}

	/**
	 * @obsolete Use `find_all` instead!
	 * @static
	 * @since 0.1
	 * @return WP_Query Returns toys.
	 */
	public static function get_toys() {
		return self::find_all();
	}

	/**
	 * Returns image for the given toy.
	 *
	 * @static
	 * @since 0.1
	 * @param integer $toy_id
	 * @return string
	 * @uses get_post_thumbnail_id()
	 * @uses wp_get_attachment_image_src()
	 */
	public static function get_toy_image( $toy_id ) {
		$image_id = get_post_thumbnail_id( $toy_id );

		if ( $image_id ) {
			$image = wp_get_attachment_image_src( $image_id, 'featured_preview' );
			return $image[0];
		}
	}
}

endif;