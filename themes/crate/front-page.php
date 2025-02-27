<?php
/**
 * The template for displaying the front page.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Crate
 */

get_header(); ?>
<?php if (true===wp_is_mobile()) {
	render_mobile_layout();
}
else {
	render_desktop_layout();
}
	

get_footer();

function render_desktop_layout () {
	?>
<div id="primary" class="content-area container">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'front' );

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->

		<?php 
			// #secondary
			get_sidebar(); 
			// end #secondary
		?>

	</div><!-- #primary -->
	<?php
}

function render_mobile_layout () {
	?>
<div id="primary" class="content-area container">
	<?php 
			// #secondary
			get_sidebar(); 
			// end #secondary
		?>
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'front' );

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->

		

	</div><!-- #primary -->
	<?php
}