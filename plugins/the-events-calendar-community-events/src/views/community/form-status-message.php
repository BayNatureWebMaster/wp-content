<?php
/**
 * Community Events Status Messages
 *
 * @since 5.0.9
 *
 * @var array $messages Array of message arrays with 'type', 'message', and 'role'
 */

?>
<?php foreach ( $messages as $msg ) : ?>
	<div class="tribe-community-notice tribe-community-notice-<?php echo esc_attr( $msg['type'] ); ?>" role="<?php echo esc_attr( $msg['role'] ); ?>"  aria-live="polite" tabindex="0">
		<?php echo wp_kses_post( $msg['message'] ); ?>
	</div>
<?php endforeach; ?>
