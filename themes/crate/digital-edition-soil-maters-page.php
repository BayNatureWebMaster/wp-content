<?php
/**
 * Template Name: Digital Edition Soil Maters  Page
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Crate
 */
get_header(); ?>
<header class="entry-header">
	<?php if ( is_singular() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
			if ( get_post_meta( get_the_ID(), 'subheading', true ) ) { ?>
				<h2 class="entry-subtitle"><?php echo esc_html( get_post_meta( get_the_ID(), 'subheading', true ) ); ?></h2>
	<?php } ?>
	<?php } else { ?>
			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		<?php } ?>
</header><!-- .entry-header -->

<div class="entry-content">
	<?php 
	switch ( isVistorActiveSoilMatersDigitalSubscriber() ) {
		case 0:
			echo "<p>Please <a href='/my-account/'>log in to your account</a> to access your digital edition!</p>";
			break;
		case 1:
			echo "<p>You do not have an active Soil Maters Digital Edition Subscription. If you would like you can purchase a <a href='/product/soil-matters-from-backyard-to-backcountry-digital-edition/'>Soil Matters: Digital Edition Subscription</a> now and get instant access!</p>";
			break;
		case 2:
			the_content();
			break;
	}
	?>
</div>
<?php get_footer(); ?>
