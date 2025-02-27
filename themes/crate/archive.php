<?php
/**
 * The archive template file.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Crate
 */

get_header();
?>

	<div id="primary" class="content-area">

		<?php
		if ( is_category() ) :

			$the_category = get_queried_object();
			$category_image = get_field( 'category_image', 'category_' . $the_category->term_id );

			?>

			<figure class="featured-image">
				<div class="hero-wrap" style="background-image: url(<?php echo esc_url( $category_image['url'] ); ?>);">
					<?php echo wp_get_attachment_image( $category_image['id'], 'large' ); ?>
					<div class="hero-title">
						<h1><?php echo str_replace( 'and', '&amp;', $the_category->name ); ?></h1>
					</div>
				</div>
				<figcaption><?php echo $category_image['caption']; ?></figcaption>

			</figure>

		<?php endif; ?>

		<main id="main" class="site-main container" role="main">

			<?php
			if ( is_category() ) :
				?>

				<section class="cat-description">
					<h2><?php echo $the_category->description; ?></h2>
				</section>

				<?php
			endif;

			if ( have_posts() ) :
				?>

				<div class="ajax-archive">

					<?php
					/* Start the Loop */
					while ( have_posts() ) :

						the_post();
						get_template_part( 'template-parts/content', 'loop' );

					endwhile;
					?>

				</div><!-- ajax-archive -->

				<nav class="archive-nav">
					<?php
						$big = 999999999;
					// need an unlikely integer
						$translated = __( 'Page', 'clp' );
					// Supply translatable string
						echo paginate_links(
							array(
								'current'            => max( 1, get_query_var( 'paged' ) ),
								'total'              => $wp_query->max_num_pages,
								'before_page_number' => '<span class="screen-reader-text">' . $translated . ' </span>',
							)
						);
					?>
				</nav>

				<?php
			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;

			wp_reset_postdata();

			get_template_part( 'template-parts/partials/subscribe' );
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
