<?php
/**
 * Template for diplaying current magazine on front page
 *
 * @package Crate
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed' ); }

	//$magazine = wc_get_product( get_field( 'current_magazine' ) );
	//$magazine_key = $magazine->get_sku();
	//echo "my sku = ".$magazine_key;
	$issueKey = get_field('issue_key');
	//echo "my key = " .$issueKey;
	$args = array(
		'post_type' => 'article',
		'posts_per_page' => 2,
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => 'issue_key',
				'value'   => $issueKey,
				'compare' => '=',
			),
			array(
				'key'     => 'special_section',
				'value'   => $issueKey,
				'compare' => '=',
			),
		),
	);

	/* If featured post selected, set $featured_post to the posts selected
	 * else set featured post to first post in query and offset the query by 1
	 */
	$featured_post = null;
	if ( get_field( 'featured_article' ) ) {
		$args['post__not_in'] = array( get_field( 'featured_article' ) );
		$featured_post = get_post( get_field( 'featured_article' ) );
		// EDIT - 74 11-02-21
		$featured_post_left = get_post( get_field( 'featured_article_left' ) );
		$featured_post_right = get_post( get_field( 'featured_article_right' ) );
	} else {
		$featured_args = $args;
		$featured_args['posts_per_page'] = 1;
		$featured_query = new WP_Query( $featured_args );
		$featured_post = $featured_query->posts[0];
		$args['offset'] = 1;
	}

	// the query
	$magazine_query = new WP_Query( $args ); ?>

	<?php if ( $magazine_query->have_posts() ) : ?>

<section class="current-magazine">
<?php /*
	<h2 class="section-title"><a href='<?php echo esc_url( get_site_url('', '/product\/') . $magazine->get_slug() ); ?>'><?php esc_html_e( 'Current Magazine' , 'crate' ); ?></a></h2>
	*/ ?>
	<h2 class="section-title"><a href='<?php echo esc_url( get_site_url('', '/current-issue\/')  ); ?>'><?php esc_html_e( 'Current Magazine' , 'crate' ); ?></a></h2>

		<div class="featured-article" style="background-image: url( <?php echo get_the_post_thumbnail_url( $featured_post->ID, 'large' ); ?>);">
			<a href="<?php echo esc_url( get_the_permalink( $featured_post->ID ) ); ?>" title="<?php echo esc_attr( $featured_post->post_title ); ?>">
				<?php echo get_the_post_thumbnail( $featured_post->ID, 'medium' ); ?>
				<div class="article-content">
					<h3 class="title"><?php echo esc_html( $featured_post->post_title ); ?></h3>
					<div class="excerpt"><p><?php echo esc_html( $featured_post->post_excerpt ); ?></p></div>
					<div class="date"><?php echo esc_html( date( 'F j, Y', strtotime( $featured_post->post_date ) ) );?></div>
				</div>
			</a>
			<div class="overlay" aria-hidden="true"></div>
		</div>

		<div class="grid-posts">
		<!-- EDIT - 74 -->
			<?php // we don't want to loop - while ( $magazine_query->have_posts() ) : $magazine_query->the_post(); ?>
			<!-- Left featured post -->
				<div class="grid-post">
					<div class="featured-image-wrap">
						<div class="featured-image" aria-hidden="true" style="background-image: url( <?php echo get_the_post_thumbnail_url( $featured_post_left->ID /*get_the_id()*/, 'large' ); ?>);"></div>
						<a href="<?php echo esc_url( get_the_permalink( $featured_post_left->ID ) ); //the_permalink(); ?>">
							<?php echo get_the_post_thumbnail( $featured_post_left->ID, 'medium' ) /*the_post_thumbnail( 'medium' ); */?>
							<div class="hover-text">
							<?php if ( get_post_meta( $featured_post_left->ID /*get_the_id()*/, 'featured_thumbnail_hover_text', true ) ) { ?>
								<p><?php echo esc_html( get_post_meta( $featured_post_left->ID /*get_the_id()*/, 'featured_thumbnail_hover_text', true ) ); ?></p>
								<span><?php esc_html_e( 'More', 'crate' ); ?></span>
							<?php } ?>
							</div>
						</a>
					</div>
					<h3 class="title"><a href="<?php echo esc_url( get_the_permalink( $featured_post_left->ID ) );//the_permalink(); ?>"><?php echo esc_html( $featured_post_left->post_title );/*the_title();*/ ?></a></h3>
					<div class="excerpt"><?php echo esc_html( $featured_post_left->post_excerpt ); /*the_excerpt(); */?></div>
					<div class="date"><?php echo esc_html( date( 'F j, Y', strtotime( $featured_post_left->post_date ) ) );/*echo esc_html( get_the_date() );*/?></div>
				</div>
			<!-- Right featured post -->
				<div class="grid-post">
					<div class="featured-image-wrap">
						<div class="featured-image" aria-hidden="true" style="background-image: url( <?php echo get_the_post_thumbnail_url( $featured_post_right->ID /*get_the_id()*/, 'large' ); ?>);"></div>
						<a href="<?php echo esc_url( get_the_permalink( $featured_post_right->ID ) );//the_permalink(); ?>">
							<?php echo get_the_post_thumbnail( $featured_post_right->ID, 'medium' ) /*the_post_thumbnail( 'medium' ); */?>
							<div class="hover-text">
							<?php if ( get_post_meta( $featured_post_right->ID /*get_the_id()*/, 'featured_thumbnail_hover_text', true ) ) { ?>
								<p><?php echo esc_html( get_post_meta( $featured_post_right->ID /*get_the_id()*/, 'featured_thumbnail_hover_text', true ) ); ?></p>
								<span><?php esc_html_e( 'More', 'crate' ); ?></span>
							<?php } ?>
							</div>
						</a>
					</div>
					<h3 class="title"><a href="<?php echo esc_url( get_the_permalink( $featured_post_right->ID ) );//the_permalink(); ?>"><?php echo esc_html( $featured_post_right->post_title );/*the_title();*/ ?></a></h3>
					<div class="excerpt"><?php echo esc_html( $featured_post_right->post_excerpt ); /*the_excerpt(); */?></div>
					<div class="date"><?php echo esc_html( date( 'F j, Y', strtotime( $featured_post_right->post_date ) ) );/*echo esc_html( get_the_date() );*/?></div>
				</div>
			<?php //endwhile; ?>

		</div>

		<?php wp_reset_postdata(); ?>
		<?php /*
		<a class="view-more" href="<?php echo esc_url( get_site_url('', '/product\/') . $issueKey ); ?>"><?php esc_html_e( 'See the Full Issue', 'crate' ); ?></a>
		*/ ?>
		<a class="view-more" href="<?php echo esc_url( get_site_url('', '/current-issue\/') ); ?>"><?php esc_html_e( 'See the Full Issue', 'crate' ); ?></a>


</section>

<?php endif; ?>