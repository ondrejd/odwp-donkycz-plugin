<?php
/**
 * odwp-courses
 *
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-courses
 * @package odwp-courses
 */

if (!class_exists('DonkyCz_Taxonomy_Toy_Category')):

/**
 * Class implementing course category taxonomy.
 *
 * @since 0.1
 */
class DonkyCz_Taxonomy_Toy_Category {
  /**
   * Name of taxonomy.
   * @const string
   */
  const NAME = 'toy_category';

  /**
   * Initialize taxonomy.
   *
   * @since 0.1
   */
  public function init() {
    $labels = array(
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
    );

    $args = array(
      'label' => __( 'Kategorie hraček', DonkyCz::SLUG ),
      'labels' => $labels,
      'public' => true,
      'show_in_menu' => true,
      'show_in_nav_menus' => true,
      'show_tagcloud' => false,
      'show_in_quick_edit' => true,
      'show_admin_column' => true,
      'description' => __( 'Kategorie jednotlivých hraček', DonkyCz::SLUG ),
      'hierarchical' => false,
      'rewrite' => true
    );

    register_taxonomy( self::NAME, DonkyCz_Custom_Post_Type_Toy::NAME, $args );
  }
}

endif;