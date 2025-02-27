<?php
/**
 * Template Name: Articles
 *
 * This is the template that displays full width pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Crate
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'articles' );

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();