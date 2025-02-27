<?php
/**
 * Template part for displaying articles/posts w/o banner image
 * This is a fallback for when other content-*.php parts are missing (e.g. loop, page, single, category, search, etc)
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Crate
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php get_template_part( 'template-parts/partials/issue-bar' ); ?>

	<header class="entry-header">
		<?php if ( is_singular() ) { bn_display_category_name(); ?>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php if ( get_post_meta( get_the_ID(), 'subheading', true ) ) { ?>
				<h2 class="entry-subtitle"><?php echo esc_html( get_post_meta( get_the_ID(), 'subheading', true ) ); ?></h2>
			<?php } ?>
		<?php } else { ?>
			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		<?php } ?>

		<?php if ( is_singular( array( 'post', 'article' ) ) ) { ?>
			<div class="entry-source">

				<span class="byline">
					<?php
						echo esc_html( 'by ' );
					if ( function_exists( 'coauthors_posts_links' ) ) {
						coauthors_posts_links();
					} else {
						the_author();
					}
					?>
				</span>

				<div class="meta-group">

					<div class="meta-date">
						<?php echo esc_html( get_the_date() ); ?>
					</div>

					<?php if ( get_post_meta( get_the_ID(), 'sponsor', true ) ) { ?>
						<div class="meta-sponsor">
							<?php if ( get_post_meta( get_the_ID(), 'sponsor_link', true ) ) { ?>
								<a href="<?php echo esc_url( get_post_meta( get_the_ID(), 'sponsor_link', true ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( 'Sponsored by ' . get_post_meta( get_the_ID(), 'sponsor', true ) ); ?></a>
							<?php } else { ?>
								<?php echo esc_html( 'Sponsored by ' . get_post_meta( get_the_ID(), 'sponsor', true ) ); ?>
							<?php } ?>
						</div>
					<?php } ?>

				</div><!-- .meta-group -->

			</div><!-- .entry-meta -->
			<?php
}//end if
?>
	</header><!-- .entry-header -->

	<?php get_template_part( 'template-parts/partials/share' ); ?>

	<div class="entry-content">

		<?php
			the_content(
				sprintf(
					/* translators: %s: Name of current post. */
					wp_kses(
						__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'crate' ), array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				)
			);
			?>
	</div><!-- .entry-content -->

	<?php get_template_part( 'template-parts/partials/author' ); ?>

	<?php get_template_part( 'template-parts/partials/share' ); ?>

	<?php get_template_part( 'template-parts/partials/subscribe' ); ?>

	<?php if ( is_singular( array( 'article', 'post' ) ) ) { ?>
		<?php get_template_part( 'template-parts/partials/related' ); ?>
	<?php } ?>

	<?php get_template_part( 'template-parts/partials/event' ); ?>

	<footer class="entry-footer">
		<div class="post-terms">
			<?php
				// Get ALL the terms across all taxonomies by passing get_taxonomies as second arg into the_terms()!
				// the_terms( get_the_id(), get_taxonomies( '', 'names' ), __('Posted in: '), '' );
			?>
		</div>

	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
