<?php
/**
 * Renders options page for contact form.
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

	<h1><?php esc_html_e( 'Kontaktní formulář - Nastavení', DonkyCz::SLUG ); ?></h1>

	<?php if ( $need_update === true && $updated === true ):?>
	<div id="<?php echo $prefix; ?>message" class="updated notice is-dismissible">
		<p><?php esc_html_e( 'Nastavení bylo úspěšně aktualizováno.', DonkyCz::SLUG ); ?></p>
	</div>
	<?php elseif ( $need_update === true && $updated !== true ):?>
	<div id="<?php echo $prefix; ?>message" class="error notice is-dismissible">
		<p><?php esc_html_e( 'Nastavení nebylo úspěšně aktualizováno!', DonkyCz::SLUG ); ?></p>
	</div>
	<?php endif?>

	<form name="<?php echo $prefix; ?>form" id="<?php echo $prefix; ?>form" action="<?= esc_url( admin_url( 'admin.php?page=' . $prefix . 'options_page' ) ); ?>" method="post" novalidate>
		<?php echo wp_nonce_field(-1, $prefix . '-nonce', true, false ); ?>
		<h2 class="title"><?php esc_html_e( 'Hlavní nastavení', DonkyCz::SLUG ); ?></h2>
		<p><?php esc_html_e( 'Hlavní nastavení pluginu <strong>RIV RV - Kurzy</strong>.', DonkyCz::SLUG ); ?></p>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="<?php echo $prefix; ?>agreement_payment"><?php esc_html_e( 'Souhlas s plat. podmínkami', DonkyCz::SLUG ); ?></label>
					</th>
					<td>
						<fieldset>
							<p>
								<label for="<?php echo $prefix; ?>agreement_payment"><?php esc_html_e( 'Tento text bude překopírován k dané objednávce kurzu v momentu jejího vytvoření.', DonkyCz::SLUG ); ?></label>
								<textarea name="<?php echo $prefix; ?>agreement_payment" id="<?php echo $prefix; ?>agreement_payment" value="<?= $options['agreement_payment']?>" class="large-text code" cols="50" rows="10"></textarea>
							</p>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
	</form>

</div>