<?php
/**
 * The archive template file.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Crate
 */

get_header('author'); ?>

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
			//bn_print_r($_SERVER['REQUEST_URI']);
			$author_list = get_coauthors();
			//bn_print_r( sizeof($author_list) );
			$nAuthors = count($author_list);
			for ($i = 0; $i < $nAuthors; $i++) {
				//if (strcmp('/author/'.$author->user_login.'/', $_SERVER['REQUEST_URI'])) $author = get_coauthors()[$i];
				//echo $i;
				$author = get_coauthors()[$i];
				if ( 0 == strcmp('/author/'.$author->user_nicename.'/', $_SERVER['REQUEST_URI']))  break;  /* $author = get_coauthors()[$i]; */
				//bn_print_r($author);
				//bn_print_r ('/author/'.$author->user_nicename.'/');
				//bn_print_r ($_SERVER['REQUEST_URI']);
				//user_nicename
			}
			
			?>

			<header class="entry-header">
				<h1 class="entry-title"><?php echo esc_html( $author->display_name ); ?></h1>
				<?php if ( ! empty( $author->description ) ) { ?>
					<div class="entry-source">
						<span class="bio">
							<?php echo nl2br( $author->description ); ?>
						</span>
					</div>
				<?php } ?>
			</header>
			
			<?php
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

				</div><!-- jake ajax-archive -->

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
