<?php
/**
 * Event submission form website block.
 *
 * Renders the website fields in the submission form.
 *
 * Override this template in your own theme by creating a file at
 * [your-theme]/tribe-events/integrations/the-events-calendar/modules/website.php
 *
 * @link https://evnt.is/1ao4 Help article for Community & Tickets template files.
 *
 * @since  3.1
 * @since  4.7.1 Now using new tribe_community_events_field_classes function to set up classes for the input.
 * @since  4.8.2 Updated template link.
 * @since 5.0.4 Updated template file path.
 *
 * @version 5.0.4
 */

?>

<div class="tribe-section tribe-section-website">
	<div class="tribe-section-header">
		<h3><?php printf( __( '%s Website', 'tribe-events-community' ), tribe_get_event_label_singular() ); ?></h3>
	</div>

	<?php
	/**
	 * Allow developers to hook and add content to the beginning of this section.
	 */
	do_action( 'tribe_events_community_section_before_website' );
	?>

	<table class="tribe-section-content">
		<colgroup>
			<col class="tribe-colgroup tribe-colgroup-label">
			<col class="tribe-colgroup tribe-colgroup-field">
		</colgroup>

		<tr class="tribe-section-content-row">
			<td class="tribe-section-content-label">
				<?php tribe_community_events_field_label( 'EventURL', __( 'External Link:', 'tribe-events-community' ) ); ?>
			</td>
			<td class="tribe-section-content-field">
				<input
					type="text"
					id="EventURL"
					name="EventURL"
					size="25"
					value="<?php echo esc_url( $event_url ); ?>"
					placeholder="<?php esc_attr_e( 'Enter URL for event information', 'tribe-events-community' ); ?>"
					class="<?php tribe_community_events_field_classes( 'EventURL', [] ); ?>"
				/>
			</td>
		</tr>
	</table>

	<?php
	/**
	 * Allow developers to hook and add content to the end of this section.
	 */
	do_action( 'tribe_events_community_section_after_website' );
	?>
</div>
