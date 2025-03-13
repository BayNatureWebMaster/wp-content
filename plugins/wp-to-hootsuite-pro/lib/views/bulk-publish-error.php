<?php
/**
 * Outputs when an error occured in Bulk Publish.
 *
 * @since 3.0.5
 *
 * @package WP_To_Social_Pro
 * @author  WP Zinc
 */

?>
<header>
	<h1>
		<?php echo esc_html( $this->base->plugin->displayName ); ?>

		<span>
			<?php esc_html_e( 'Bulk Publish', 'wp-to-social-pro' ); ?>
		</span>
	</h1>
</header>

<hr class="wp-header-end" />

<div class="wrap">
	<?php
	// Output notices.
	$this->base->get_class( 'notices' )->output_notices();
	?>
</div>
