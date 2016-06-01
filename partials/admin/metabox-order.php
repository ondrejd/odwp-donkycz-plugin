<?php
/**
 * Renders metabox for toy order.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/admin/partials
 */

?>
<fieldset class="toy-order-metabox toy-side-metabox">
	<p>
		<label for="toy_order"><?= __( 'Pořadí:', DonkyCz::SLUG ) ?></label>
		<input type="number" name="toy_order" id="toy_order" value="<?= $order ?>" min="1" step="1" />
	</p>
</fieldset>