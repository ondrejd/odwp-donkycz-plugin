<?php
/**
 * The file that contains localization for our TinyMCE buttons/plugins.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/admin
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include `_WP_Editors` class if is not included yet.
if ( ! class_exists( '_WP_Editors' ) ) {
    require( ABSPATH . WPINC . '/class-wp-editor.php' );
}


if ( !function_exists( 'odwpdcz_translation' ) ):

/**
 * Returns translations for our TinyMCE buttons/plugins.
 *
 * @return string
 */
function odwpdcz_translation() {
    $strings = array(
        'button_title' => __( 'Donky.cz', 'odwp-donkycz-plugin' ),
        'menuitem1_text' => __( 'Kontaktní formulář', 'odwp-donkycz-plugin' )
    );

    $locale = _WP_Editors::$mce_locale;
    $translated = 'tinyMCE.addI18n("' . $locale . '.odwp-donkycz-plugin", ' . json_encode( $strings ) . ");\n";

    return $translated;
}

endif;


/**
 * @var string $strings Translation strings.
 */
$strings = odwpdcz_translation();