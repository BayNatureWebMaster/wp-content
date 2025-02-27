<?php
// Don't load directly.
defined( 'WPINC' ) or die;

/**
 * Event Submission Form Custom Select field.
 * This is used to add a custom select field to the event submission form that contains custom field inputs.
 *
 * Override this template in your own theme by creating a file at
 * [your-theme]/tribe-events/community/modules/custom/fields/dropdown.php
 *
 * @link https://evnt.is/1ao4 Help article for Community & Tickets template files.
 *
 * @since  4.6.3
 * @since  4.7.1 Now using new tribe_community_events_field_classes function to set up classes for the input.
 * @since 4.8.2 Updated template link.
 * @since 4.9.1 Updated value for required fields.
 *
 * @version 4.9.1
 *
 * @var array        $fields        List of form fields.
 * @var int          $post_id       Current Post ID.
 * @var array        $field         Current field data.
 * @var string       $field_classes List of field classes separated by space.
 * @var string       $field_name    Field name.
 * @var string       $field_label   Field label.
 * @var string       $field_type    Field type.
 * @var string       $field_id      Field HTML ID.
 * @var string|array $value         Current field value (or value from $_POST).
 * @var array        $options       List of options for checkbox/radio/dropdown fields.
 */
?>

<select
	class="tribe-dropdown"
	id="<?php echo esc_attr( $field_id ); ?>"
	name="<?php echo esc_attr( $field_name ); ?>"
	class="<?php tribe_community_events_field_classes( $field_name, [] ); ?>"
>
	<?php foreach ( $options as $option ) : ?>
		<?php

		$option_value   = $option;
		$option_display = $option;
			/* None is added to the list of potential dropdown options.
			 * We equate None to '' so that the validation work properly.
			 */
			if ( 'None' === $option ) {
				$option_value = '';
				$option_display = __( 'None' , 'tribe-events-community' );
			}
		?>
		<option
			value="<?php echo esc_attr( $option_value ); ?>"
			<?php selected( in_array( $option_value, (array) $value, true ) ); ?>
		>
			<?php echo esc_html( $option_display ); ?>
		</option>
	<?php endforeach; ?>
</select>
