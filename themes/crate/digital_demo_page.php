<?php
/**
 * Template Name: Demo Digital Edition Page
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
	switch ( isVistorActiveDemoDigitalSubscriber() ) {
		case 0:
			echo "<p>You need to login to your account to access the Digital Edition. <a href='/my-account/'>Click here to login</a></p>";
			break;
		case 1:
			echo "<p>You do not have an active Digital Edition Subscriptiom. If you would like you can purchase a <a href='/product-category/subscriptions-renewals/'>Digital Edition Subscription</a> now and get instant access!</p>";
			break;
		case 2:
			the_content();
			break;
	}
	//if (isVistorActiveDigitalSubscriber()) the_content(); 
	//else {
		//echo "<p>You do not have an active Digital Edition Subscriptiom. If you like you can purchase one now and get instant access!</p>";
	//}
	?>
</div>
<?php get_footer(); ?>
