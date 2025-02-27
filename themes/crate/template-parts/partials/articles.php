<?php
/**
 * Related posts partial
 *
 * @package Crate
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed' ); }

?>

<?php

	$article_posts_args = array();
	if ( 'category' === get_field( 'articles_selection' ) ) {
		$category = get_field( 'articles_category_filter' );
		$count = get_field( 'articles_count' );
		$article_posts_args = array(
			'post_type'      => array( 'article' ),
			'posts_per_page' => $count,
			'category__in'   => $category,
			'orderby'        => 'date',
		);
	} else {
		$article_posts_ids = get_field( 'articles' );
		$article_posts_args = array(
			'post_type'      => array( 'article' ),
			'post__in'       => $article_posts_ids,
			'orderby'        => 'post__in',
		);
	}

	$articles_query = new WP_Query( $article_posts_args ); ?>

<?php if ( $articles_query->have_posts() && ! empty( get_field( 'articles_selection' ) ) ) : ?>

<div class="container grid-content-wrap articles-content-wrap">
	<div class="grid-content">

		<?php if ( get_field( 'articles_heading' ) ) { ?>
			<h4 class="grid-posts-heading"><?php echo esc_html( get_field( 'articles_heading' ) ); ?></h4>
		<?php } ?>

		<div class="grid-posts">

			<?php while ( $articles_query->have_posts() ) : $articles_query->the_post(); ?>

			<div class="grid-post">
				<div class="featured-image-wrap">
					<div class="featured-image" aria-hidden="true" style="background-image: url( <?php echo get_the_post_thumbnail_url( get_the_id(), 'large' ); ?>);"></div>
					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'medium' ); ?>
						<div class="hover-text">
						<?php if ( get_post_meta( get_the_id(), 'featured_thumbnail_hover_text', true ) ) { ?>
							<p><?php echo esc_html( get_post_meta( get_the_id(), 'featured_thumbnail_hover_text', true ) ); ?></p>
							<span><?php esc_html_e( 'More', 'crate' ); ?></span>
						<?php } ?>
						</div>
					</a>
				</div>
				<h3 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<div class="post-meta">
					<div class="date"><?php echo esc_html( get_the_date() );?></div>
					<span class="divider" aria-hidden="true">|</span>
					<?php if ( get_post_meta( get_the_ID(), 'sponsor', true ) ) { ?>
						<div class="sponsor">
							<?php if ( get_post_meta( get_the_ID(), 'sponsor_link', true ) ) { ?>
								<a href="<?php echo esc_url( get_post_meta( get_the_ID(), 'sponsor_link', true ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( 'Sponsored by '. get_post_meta( get_the_ID(), 'sponsor', true ) ); ?></a>
							<?php } else { ?>
								<?php echo esc_html( 'Sponsored by '. get_post_meta( get_the_ID(), 'sponsor', true ) ); ?>
							<?php } ?>
						</div>
					<?php } else { ?>
						<div class="type">
							<?php 
								$type = get_post_type();
								if ( 'post' === $type ) {
									$type = __( 'Blog', 'crate' );
								} else if ( 'article' === $type && ! empty ( get_post_meta( get_the_ID(), 'issue_key', true ) ) ) {
									$type = __( 'Magazine', 'crate' );
								}
							?>
							<span class="<?php echo esc_attr( strtolower( $type ) ); ?>"><?php echo esc_html( $type ); ?></span>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
</div>

<?php endif; ?>
