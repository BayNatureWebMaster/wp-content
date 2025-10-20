<?php
/**
 * Event Submission Form Terms of Submission Block.
 * Renders the website fields in the submission form.
 *
 * Override this template in your own theme by creating a file at
 * [your-theme]/tribe-events/community/modules/terms.php
 *
 * @link https://evnt.is/1ao4 Help article for Community & Tickets template files.
 *
 * @since 4.7.1
 * @since 4.8.2 Updated template link.
 * @since 4.10.6 Updated term description to properly display special characters.
 * @since 5.0.9 Updated to use scrollable div for better accessibility.
 *
 * @var boolean $terms_enabled      Whether terms are enabled.
 * @var string  $terms_description  Terms description.
 */

if ( empty( $terms_enabled ) ) {
	return;
}

if ( empty( $terms_description ) ) {
	return;
}

?>
<div class="tribe-section tribe-section-terms">
	<div class="tribe-section-header">
		<h3 id="tec-terms-of-submission-heading">
			<?php esc_html_e( 'Terms of Submission', 'tribe-events-community' ); ?>
		</h3>
	</div>

	<?php
	/**
	 * Allow developers to hook and add content to the beginning of this section
	 */
	do_action( 'tribe_events_community_section_before_terms' );
	?>

	<div class="tribe-section-content">
		<div
			class="tec-event-terms-description"
			role="region"
			aria-labelledby="tec-terms-of-submission-heading"
			aria-describedby="tec-terms-scroll-instructions"
			tabindex="0"
		>
			<?php
			$terms_text = stripslashes( wp_specialchars_decode( $terms_description ) );

			echo wp_kses_post( wpautop( $terms_text ) );
			?>
		</div>
		<p id="tec-terms-scroll-instructions" class="screen-reader-text">
			<?php esc_html_e( 'Use arrow keys or page up/down to scroll through the terms of submission.', 'tribe-events-community' ); ?>
		</p>

		<br/>
		<input
			type="checkbox"
			id="terms"
			name="terms"
			value="true"
			class="<?php tribe_community_events_field_classes( 'terms', [ 'event-terms-agree' ] ); ?>"
		/>
		<?php tribe_community_events_field_label( 'terms', __( 'I agree to the terms of submission', 'tribe-events-community' ) ); ?>
	</div>

	<?php
	/**
	 * Allow developers to hook and add content to the end of this section
	 */
	do_action( 'tribe_events_community_section_after_terms' );
	?>
</div>
