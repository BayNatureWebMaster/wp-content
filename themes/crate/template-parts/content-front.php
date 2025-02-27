<?php
/**
 * Template part for displaying front page sections
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Crate
 */

?>

<div id="frontpage-sections">
<?php if (true===wp_is_mobile()) {
		get_template_part( 'template-parts/partials/magazine' ); // was magazine
		get_template_part( 'template-parts/partials/ad-front' ); // was
		get_template_part( 'template-parts/partials/picks' ); // was picks

 } else { ?>
	<?php if ( 'picks' === get_field( 'highlighted_section' ) ) { ?>

		<?php get_template_part( 'template-parts/partials/picks' ); ?>
		<?php get_template_part( 'template-parts/partials/ad-front' ); ?>
		<?php get_template_part( 'template-parts/partials/magazine' ); ?>

	<?php } else { ?>

		<?php get_template_part( 'template-parts/partials/magazine' ); ?>
		<?php get_template_part( 'template-parts/partials/ad-front' ); ?>
		<?php get_template_part( 'template-parts/partials/picks' ); ?>

	<?php } ?>

	<?php get_template_part( 'template-parts/partials/event-front' ); ?>
	<?php get_template_part( 'template-parts/partials/trail' ); ?>
	<?php } ?>
</div>

