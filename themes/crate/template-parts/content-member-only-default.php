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
	<?php get_template_part( 'template-parts/partials/issue-bar' ); ?>

	<header class="entry-header">
		<?php if ( is_singular() ) { ?>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php if ( get_post_meta( get_the_ID(), 'subheading', true ) ) { ?>
				<h2 class="entry-subtitle"><?php echo esc_html( get_post_meta( get_the_ID(), 'subheading', true ) ); ?></h2>
			<?php } ?>
		<?php } else { ?>
			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		<?php } ?>
		/* start new code */
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
	/* end new code */ ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		
		<?php
		if ( unlock_paywall() ) { 
			the_content(); 
		}
		else {
			show_member_login_message( "article");
		}
		?>

	</div><!-- .entry-content -->
	<?php get_template_part( 'template-parts/partials/author' ); ?>

	<?php /*get_template_part( 'template-parts/partials/articles' ); */ ?>

	<?php //get_template_part( 'template-parts/partials/share' ); ?>

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