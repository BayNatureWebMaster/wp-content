<?php
/**
 * Related posts partial
 *
 * @package Crate
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed' ); }

?>

<div class="container grid-content-wrap">
	<div class="grid-content">

		<?php

		$related_posts_ids = array();

		// If staff picks, add staff picks to id array
		if ( get_field( 'staff_picked_posts' ) ) {
			$related_posts_ids = array_merge( $related_posts_ids, get_field( 'staff_picked_posts' ) );
		}

		// If no staff picks and related posts, add related posts to id array
		if ( get_field( 'related_posts' ) && empty( get_field( 'staff_picked_posts' ) ) ) {
			$related_posts_ids = array_merge( $related_posts_ids, get_field( 'related_posts' ) );
		}

		// If staff picks count is less than 4 (total to display), add related posts to id array
//		echo "field type = ";
//		bn_print_r(gettype('staff_picked_posts'));
//		echo "field content = ";
//		bn_print_r( get_field( 'staff_picked_posts' ) );
		if ( 0 === strcmp(gettype(get_field( 'staff_picked_posts' )), "array")) {
			//echo "got related posts";
			if ( 4 > count( get_field( 'staff_picked_posts' ) ) ) {
				if ( get_field( 'related_posts' ) ) {
					$related_posts_ids = array_merge( $related_posts_ids, get_field( 'related_posts' ) );
				}
			}
		}


		// If staff and related post count is less than 4, fill with related posts by tags and categories
		if ( 4 > count( $related_posts_ids ) || empty( $related_posts_ids ) ) {

			$categories = get_the_category();
			$tags = get_the_tags();

			$cat_ids = wp_list_pluck( $categories, "term_id" );
			$tag_ids = wp_list_pluck( $tags, "term_id" );

			$related_posts_fallback_args = array(
				'post_type'    => array( 'post', 'article' ),
				'post__not_in' => array( get_the_id() ),
				'category__in' => $cat_ids,
				'tag__in'      => $tag_ids,
				'orderby'      => 'date',
				'order'        => 'DESC',

			);
			$related_posts_fallback = get_posts( $related_posts_fallback_args );

			// If no matches by categories and tags, fallback to match categories only
			if ( empty( $related_posts_fallback ) ) {
				$related_posts_cat_fallback_args = array(
					'post_type'    => array( 'post', 'article' ),
					'post__not_in' => array( get_the_id() ),
					'category__in' => $cat_ids,
					'orderby'      => 'date',
					'order'        => 'DESC',
				);
				$related_posts_fallback = get_posts( $related_posts_cat_fallback_args );
			}

			$related_posts_fallback_ids = wp_list_pluck( $related_posts_fallback, "ID" );

			$related_posts_ids = array_merge( $related_posts_ids, $related_posts_fallback_ids );

		}

	if ( ! empty( $related_posts_ids ) ) {

		$related_posts_args = array(
			'post_type'      => array( 'post', 'article' ),
			'posts_per_page' => 4,
			'post__in'       => $related_posts_ids,
			'orderby'        => 'post__in',

		);

			global $post;
			$related_posts = get_posts( $related_posts_args );

			if ( is_array( $related_posts ) && ! empty( $related_posts ) ){ ?>

				<h4 class="grid-posts-heading"><?php esc_html_e( 'Read This Next', 'crate' ); ?></h4>

				<div class="grid-posts">
		
					<?php foreach ( $related_posts as $post ) : setup_postdata( $post ); ?>

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
										if ( is_in_magazine( get_the_id() ) ) {
											$type = __( 'Magazine', 'crate' );
										} else {
											$type = __( 'Baynature.org', 'crate' );
										}
									?>
									<span class="<?php echo esc_attr( strtolower( $type ) ); ?>"><?php echo esc_html( $type ); ?></span>
								</div>
							<?php } ?>
						</div>
					</div>

					<?php endforeach; wp_reset_postdata(); ?>

				</div>

		<?php } ?>

	</div>
</div>

<?php } // endif ?>
