<?php
/**
 * This week's picks
 *
 * @package Crate
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed' ); }

?>

<section class="bn-picks">
<?php /* Bay Nature Temp Mod - Label change for Picks block : 
	<h2 class="section-title"><?php echo esc_html__( 'Picks for' , 'crate' ) . ' ' . current_week_date_format(); ?></h2>
*/ ?>
	<h2 class="section-title"><?php echo esc_html__( 'In season in Northern California' , 'crate' ); ?></h2>

	<div class="grid-content-wrap">
		<div class="grid-content">

		<?php

				$week_posts_ids = get_post_meta( get_the_id(), 'week_picks', true );

				$week_posts_args = array(
					'post_type'      => array( 'post', 'article' ),
					'posts_per_page' => 3,
				);

				if ( ! empty( $week_posts_ids ) ) {
					$week_posts_args['post__in'] = $week_posts_ids;
					$week_posts_args['orderby']  = 'post__in';
				} else {
					$current_week = current_week();
					$week_posts_args['date_query'] = array(
						array(
							'column'    => 'post_date',
							'after'     => date( 'F j, Y', $current_week['monday'] ),
							'before'    => date( 'F j, Y', $current_week['sunday'] ),
							'inclusive' => true,
						),
					);
				}

				global $post;
				$week_posts = get_posts( $week_posts_args );

				if ( is_array( $week_posts ) && ! empty( $week_posts ) ) {

					?>

					<div class="grid-posts">

						<?php
						foreach ( $week_posts as $post ) :
							setup_postdata( $post );
							?>

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
						</div>

							<?php
						endforeach;
							wp_reset_postdata();
						?>

					</div>

					<?php
				}//end if
				?>

		</div>
	</div>

</section>
