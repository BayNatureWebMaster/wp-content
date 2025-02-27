<?php
/**
 * The template file for search results.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Crate
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main container" role="main">

		<?php
		if ( have_posts() ) :
			global $wp_query;
		?>

			<div class="search-wrap">

				<h1 class="search-term"><?php echo esc_html__( 'You searched for ', 'crate' ). '<strong>'. $wp_query->query['s'] .'</strong>'; ?></h1>



				<?php if ( get_field( 'search_facets', 'option' ) ) { ?>
					<div class="search-facets">
						<?php echo get_search_form(); ?>
						<?php echo get_field( 'search_facets', 'option' ); ?>
					</div>
				<?php } ?>

				<div class="search-results facetwp-template">

					<?php

					/* Start the Loop */
					while ( have_posts() ) :

						the_post();
						get_template_part( 'template-parts/content', 'loop' );

					endwhile;
					?>

				</div>

			</div><!-- .search-wrap -->

			<nav class="pagination">
				<?php echo facetwp_display( 'pager' ); ?>
			</nav>

			<?php wp_reset_postdata(); ?>

				<?php
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;

		?>

		<?php get_template_part( 'template-parts/partials/subscribe' ); ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
