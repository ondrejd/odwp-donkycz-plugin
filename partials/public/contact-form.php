<?php
/**
 * Render contact form.
 *
 * @since 0.1
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://bitbucket.com/ondrejd/odwp-donkycz-plugin
 * @package odwp-donkycz-plugin
 * @subpackage odwp-donkycz-plugin/public/partials
 */

/**
 * These variables are available:
 *
 * @var string   $prefix   Form elements ID prefix.
 * @var WP_Query $toys     Available toys.
 */

?>
<form id="<?= $prefix ?>contact_form" name="contact_form" class="contact_form" method="post">
	<?php wp_nonce_field( 'contact-form', 'cfnonce' ); ?>
	<div class="form-row">
		<div class="left">
			<label for="<?= $prefix ?>sender">
				<span><?php _e( 'Jméno a příjmení', DonkyCz::SLUG ); ?></span>
				<input type="text" id="<?= $prefix ?>sender" name="sender" class="input" value=""/>
			</label>
		</div>
		<div class="right">
			<label for="<?= $prefix ?>email">
				<span><?php _e( 'E-mailová adresa', DonkyCz::SLUG ); ?></span>
				<input type="email" id="<?= $prefix ?>email" name="email" class="input" value=""/>
			</label>
		</div>
	</div>
	<div class="form-row">
		<label for="<?= $prefix ?>message">
			<span><?php _e( 'Chci se zeptat', DonkyCz::SLUG ); ?></span>
			<textarea id="<?= $prefix ?>message" name="message" class="input"></textarea>
		</label>
	</div>
	<div class="form-row">
		<label for="<?= $prefix ?>toy_id">
			<span><?php _e( 'Vyberte hračku', DonkyCz::SLUG ); ?></span>
			<?php if ( $toys->have_posts() ): ?>
			<select id="<?= $prefix ?>toy_id" name="toy_id" class="input">
				<option value="-1"><?php esc_html_e( 'Žádná', DonkyCz::SLUG ); ?></option>
				<?php while ( $toys->have_posts() ) : $toys->the_post(); ?>
				<option value="<?php the_ID(); ?>"><?php the_title(); ?></option>
				<?php endwhile; ?>
			</select>
			<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</label>
	</div>
	<div class="form-row">
		<label for="<?= $prefix ?>toy_spec">
			<span><?php _e( 'Specifikace hračky', DonkyCz::SLUG ); ?></span>
			<textarea id="<?= $prefix ?>toy_spec" name="toy_spec" class="input"></textarea>
			<span class="description"><?php _e( '(Napište prosím Vaši představu ohledně vzhledu hračky, velikosti, barevnosti, doplňcích apod.)', DonkyCz::SLUG ); ?></span>
		</label>
	</div>
	<div class="submit-row">
		<input id="<?= $prefix ?>submit" name="submit" type="submit" value="<?php esc_html_e( 'Odeslat', DonkyCz::SLUG ); ?>" class="button-primary save alignright"/>
		<span id="<?= $prefix ?>spinner" class="spinner is-active" style="visibility: collapse;"></span>
		<div class="clear"></div>
	</div>
</form>