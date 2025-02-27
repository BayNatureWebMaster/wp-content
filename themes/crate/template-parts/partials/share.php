<?php
/**
 * Share partial
 *
 * @package Crate
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed' ); }
	?>
<div class="container share-wrap">
	<div class="share-content">
		<h4 class="share-message"><?php esc_html_e( 'Share This', 'crate' ); ?>:</h4>
		<div class="addthis_toolbox addthis_default_style">
			<?php echo do_shortcode('[DISPLAY_ULTIMATE_PLUS]'); ?>
		</div>
	</div>
</div>
