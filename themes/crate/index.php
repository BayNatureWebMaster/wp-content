<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
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

			if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>

			<?php
			endif;

			/* Start the Loop */
			while ( have_posts() ) :

				the_post();
				get_template_part( 'template-parts/content', 'loop' );

			endwhile;
			?>
			<nav class="pagination">
				<?php echo paginate_links( array( 'type' => 'list' ) ); ?>
			</nav>

			<?php
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; 

		get_template_part( 'template-parts/partials/subscribe' ); ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
