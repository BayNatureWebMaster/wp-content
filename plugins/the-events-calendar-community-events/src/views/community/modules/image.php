<?php
// Don't load directly.
defined( 'WPINC' ) || die;

/**
 * Event Submission Form Image Uploader Block
 * Renders the image upload field in the submission form.
 *
 * Override this template in your own theme by creating a file at
 * [your-theme]/tribe-events/community/modules/image.php
 *
 * @link https://evnt.is/1ao4 Help article for Community & Tickets template files.
 *
 * @since 3.1
 * @since 4.7.1 Now using new tribe_community_events_field_classes function to set up classes for the input.
 * @since 4.8.2 Updated template link.
 * @since 5.0.9 Made the template more Accessible.
 *
 * @version 4.8.2
 */

/**
 * Get the community main instance to check file upload errors and size limits.
 *
 * @var \Tribe__Events__Community__Main $community_main
 */
$community_main = tribe( 'community.main' );

$upload_error = $community_main->max_file_size_exceeded();
$size_format  = size_format( $community_main->max_file_size_allowed() );

/* translators: %s is the event label.*/
$image_upload_label = sprintf( __( '%s Image', 'tribe-events-community' ), tribe( Tribe__Events__Community__Main::class )->get_event_label( 'singular' ) );
?>

<div class="tribe-section tribe-section-image-uploader">
	<div class="tribe-section-header">
		<h3><?php echo esc_html( $image_upload_label ); ?></h3>
	</div>

	<?php
	/**
	 * Fires before the featured image section content.
	 *
	 * @since 4.7.1
	 */
	do_action( 'tribe_events_community_section_before_featured_image' );
	?>

	<div class="tribe-section-content">
		<?php $class = ( get_post() && has_post_thumbnail() ) ? 'has-image' : ''; ?>
		<div class="tribe-image-upload-area <?php echo esc_attr( $class ); ?>">
			<input type="hidden" name="detach_thumbnail" id="tribe-events-community-detach-thumbnail" value="false">

			<div class="note">
				<p id="tec-event-image-help">
					<?php
					echo esc_html(
						sprintf(
							// translators: %1$s is the file size.
							__( 'Choose a .jpg, .png, or .gif file under %1$s in size.', 'tribe-events-community' ),
							$size_format
						)
					);
					?>
				</p>
			</div>

			<div class="form-controls">
				<span class="selected-msg"><?php esc_html_e( 'Selected:', 'tribe-events-community' ); ?></span>

				<input id="uploadFile" class="uploadFile" disabled aria-hidden="true" tabindex="-1"/>

				<label for="event_image" class="choose-file tribe-button tribe-button-secondary">
					<?php esc_html_e( 'Choose Image', 'tribe-events-community' ); ?>
				</label>

				<span id="tec-event-image-status" role="status" aria-live="polite" class="screen-reader-text">
					<?php esc_html_e( 'No file chosen.', 'tribe-events-community' ); ?>
				</span>

				<input
					id="event_image"
					type="file"
					name="event_image"
					accept=".jpg,.jpeg,.png,.gif"
					<?php if ( $upload_error ) : ?>
						aria-invalid="true"
					<?php endif; ?>
					aria-describedby="tec-event-image-help<?php echo $upload_error ? ' tec-event-image-error' : ''; ?>"
					class="event_image <?php tribe_community_events_field_classes( 'event_image', [] ); ?>"
				>

				<?php if ( $upload_error ) : ?>
					<p id="tec-event-image-error" class="tribe-error" role="alert">
						<?php esc_html_e( 'The selected file is too large.', 'tribe-events-community' ); ?>
					</p>
				<?php endif; ?>
			</div>

			<div class="tribe-remove-upload"><a href="#"><?php esc_html_e( 'Remove image', 'tribe-events-community' ); ?></a></div>
		</div>
	</div>

	<?php
	/**
	 * Fires after the featured image section content.
	 *
	 * @since 4.7.1
	 */
	do_action( 'tribe_events_community_section_after_featured_image' );
	?>
</div>
