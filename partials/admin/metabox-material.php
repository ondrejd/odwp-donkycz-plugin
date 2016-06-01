<?php
/**
 * Renders metabox for toy material.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/admin/partials
 */

?>
<fieldset class="toy-material-metabox toy-main-metabox">
	<label class="screen-reader-text" for="toy_material"><?= __( 'Použitý materiál:', DonkyCz::SLUG ) ?></label>
	<textarea id="toy_material" name="toy_material" cols="40" rows="1"><?= $material ?></textarea>
	<p><?= __( 'Zadejte popis materiálů, z kterých je hračka vyrobena (maximálně 50 znaků).' ) ?></p>
</fieldset>