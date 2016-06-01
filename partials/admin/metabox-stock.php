<?php
/**
 * Renders metabox for toy stock.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/admin/partials
 */

?>
<fieldset class="toy-stock-metabox toy-side-metabox">
	<p>
		<label for="toy_stock"><?= __( 'Skladem:', DonkyCz::SLUG ) ?></label>
		<input type="number" name="toy_stock" id="toy_stock" value="<?= $stock ?>" min="0" step="1" />
		<span><?= __( 'ks', DonkyCz::SLUG ) ?></span>
	</p>
</fieldset>