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
 * @var string $prefix Form elements ID prefix.
 */
$prefix = 'odwpdcz-';

/**
 * @var WP_Query $toys Available toys.
 */
$toys = DonkyCz_Custom_Post_Type_Toy::get_toys();

?>
<form id="<?= $prefix ?>contact_form" name="contact_form">
	<p>
		<label for="<?= $prefix ?>sender">
			<?= __( 'Jméno a příjmení', DonkyCz::SLUG ) ?><br/>
			<input type="text" id="<?= $prefix ?>sender" name="sender" class="input" value=""/>
		</label>
	</p>
	<p>
		<label for="<?= $prefix ?>email">
			<?= __( 'E-mailová adresa', DonkyCz::SLUG ) ?><br/>
			<input type="email" id="<?= $prefix ?>email" name="email" class="input" value=""/>
		</label>
	</p>
	<p>
		<label for="<?= $prefix ?>message">
			<?= __( 'Chci se zeptat', DonkyCz::SLUG ) ?><br/>
			<textarea id="<?= $prefix ?>message" name="message" class="input"></textarea>
		</label>
	</p>
	<p>
		<label for="<?= $prefix ?>toy_id">
			<?= __( 'Vyberte hračku', DonkyCz::SLUG ) ?><br/>
			<?php if ( $toys->have_posts() ): ?>
			<select id="<?= $prefix ?>toy_id" name="toy_id" class="input">
				<?php while ( $toys->have_posts() ) : $toys->the_post(); ?>
				<option value="<?php the_ID(); ?>"><?php the_title(); ?></option>
				<?php endwhile; ?>
			</select>
			<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</label>
	</p>
	<p>
		<label for="<?= $prefix ?>toy_spec">
			<?= __( 'Specifikace hračky', DonkyCz::SLUG ) ?><br/>
			<textarea id="<?= $prefix ?>toy_spec" name="toy_spec" class="input"></textarea>
			<span class="description"><?= __( '(Napište prosím Vaši představu ohledně vzhledu hračky, velikosti, barevnosti, doplňcích apod.)', DonkyCz::SLUG ) ?></span>
		</label>
	</p>
	<p>
		<!-- TODO Add spinner! -->
		<input type="submit" value="<?= __( 'Odeslat', DonkyCz::SLUG ) ?>" name="<?= $prefix ?>submit" class="button button-primary"/>
	</p>
</form>