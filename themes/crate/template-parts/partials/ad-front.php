<?php
/**
 * Event partial
 *
 * @package Crate
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed' ); }

?>
<?php if ( get_post_meta( get_the_id(), 'frontpage_ad', true ) ) { ?>

	<section class="frontpage-ad">
		<?php echo apply_filters( 'the_content', get_post_meta( get_the_id(), 'frontpage_ad', true ) ); ?>
	</section>

<?php } ?>
