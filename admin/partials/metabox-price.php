<?php
/**
 * Renders metabox for toy price.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/admin/partials
 */

?>
<fieldset class="toy-price-metabox toy-side-metabox">
	<p>
		<label for="toy_price"><?= __( 'Cena:', DonkyCz::SLUG ) ?></label>
		<input type="number" name="toy_price" id="toy_price" value="<?= $price ?>" min="0" step="1" />
		<span><?= __( 'Kč', DonkyCz::SLUG ) ?></span>
	</p>
</fieldset>