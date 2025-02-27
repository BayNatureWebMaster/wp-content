<?php
/**
 * Subscribe partial
 *
 * @package Crate
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed' ); }

?>

<div class="container subscribe-wrap">
	<div class="subscribe-content">
		<div class="subscribe-message">
			<?php echo get_field( 'subscribe_text', 'option' ); ?>
		</div>
		<div class="subscribe-button">
			<a class="button button-large subscribe-cta" href="/membership/" title="<?php esc_attr_e( 'Subscribe', 'crate' ); ?>"><?php esc_html_e( 'Join/Renew', 'crate' ); ?></a>
		</div>
	</div>
</div>