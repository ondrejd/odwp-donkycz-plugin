<?php
/**
 * Renders metabox for toy description.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/admin/partials
 */

?>
<fieldset class="toy-description-metabox toy-main-metabox">
	<label class="screen-reader-text" for="toy_description"><?= __( 'Popis:', DonkyCz::SLUG ) ?></label>
	<textarea id="toy_description" name="toy_description" cols="40" rows="1"><?= $description ?></textarea>
	<p><?= __( 'Zadejte stručný popis hračky (maximálně 50 znaků).' ) ?></p>
</fieldset>