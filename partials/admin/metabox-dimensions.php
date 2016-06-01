<?php
/**
 * Renders metabox for toy dimensions.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/admin/partials
 */

?>
<fieldset class="toy-dimensions-metabox toy-main-metabox">
	<label class="screen-reader-text" for="toy_dimensions"><?= __( 'Rozměry hračky:', DonkyCz::SLUG ) ?></label>
	<textarea id="toy_dimensions" name="toy_dimensions" cols="40" rows="1"><?= $dimensions ?></textarea>
	<p><?= __( 'Zadejte rozměry hračky (maximálně 25 znaků).' ) ?></p>
</fieldset>