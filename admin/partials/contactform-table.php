<?php
/**
 * Renders data page for contact form.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/admin/partials
 */

/**
 * These variables are available:
 *
 * @var string  $prefix      Form elements ID prefix.
 * @var array   $options     Options for contact form shortcode.
 * @var boolean $need_update TRUE if options were in need to update.
 * @var boolean $updated     TRUE if options were successfully updated.
 */

?><div class="wrap">

	<?php screen_icon(); ?>

	<h1><?php esc_html_e( 'Kontaktní formulář - Odeslaná data', DonkyCz::SLUG ); ?></h1>

	<?php
		$table->prepare_items();
		$table->search_box( 'search', 'search_id' );
		$table->display();
	?>

</div>