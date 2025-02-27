<?php
/**
 * Template part for displaying pages with additional articles
 * This is a fallback for when other content-*.php parts are missing (e.g. loop, page, single, category, search, etc)
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Crate
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( has_post_thumbnail() && is_singular() ) : ?>

	<figure class="featured-image">
		<div class="hero-wrap" style="background-image: url(<?php echo esc_url( get_the_post_thumbnail_url() ); ?>);">
			<?php the_post_thumbnail( 'large' ); ?>
		</div>
		<figcaption><?php the_post_thumbnail_caption(); ?></figcaption>
		
	</figure>

	<?php endif; ?>

	<header class="entry-header">
		<?php if ( is_singular() ) { ?>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php if ( get_post_meta( get_the_ID(), 'subheading', true ) ) { ?>
				<h2 class="entry-subtitle"><?php echo esc_html( get_post_meta( get_the_ID(), 'subheading', true ) ); ?></h2>
			<?php } ?>
		<?php } else { ?>
			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		<?php } ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		
		<?php the_content(); ?>

	</div><!-- .entry-content -->

	<?php get_template_part( 'template-parts/partials/articles' ); ?>

	<?php get_template_part( 'template-parts/partials/share' ); ?>

	<?php get_template_part( 'template-parts/partials/subscribe' ); ?>

	<footer class="entry-footer">
		<div class="post-terms">
			<?php
				// Get ALL the terms across all taxonomies by passing get_taxonomies as second arg into the_terms()!
				//the_terms( get_the_id(), get_taxonomies( '', 'names' ), __('Posted in: '), '' );
			?>
		</div>

	</footer><!-- .entry-footer -->
</article><!-- #post-## -->