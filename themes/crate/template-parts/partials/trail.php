<?php
/**
 * Event partial
 *
 * @package Crate
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed' ); }

if ( class_exists( 'acf' ) ) {
	?>
	<?php if ( get_field( 'featured_trail_title', 'option' ) ) { ?>

	<div class="container feature-wrap">
		<div class="feature-content trail-content">
			<?php

			$trail = get_post( get_field( 'featured_trail', 'option' ) );
			$image = wp_get_attachment_image_src( get_field( 'featured_trail_image', 'option' ), 'width=600&height=400&crop=1' );

			?>
			<div class="feature-thumb">
				<div class="featured-image-wrap">
					<div class="featured-image" aria-hidden="true" style="background-image: url( <?php echo $image[0]; ?>);"></div>
					<a href="<?php echo esc_url( get_field( 'featured_trail_link' , 'option' ) ); ?>" title="<?php echo esc_attr( __( 'Go to ', 'crate' ) . get_field( 'featured_trail_title' , 'option' ) ); ?>"><span class="visuallyhidden"><?php echo esc_html( get_field( 'featured_trail_title' , 'option' ) ); ?></span></a>
				</div>
			</div>
			<div class="feature-info">
				<div class="feature-heading">
					<h4 class="featured-label"><?php esc_html_e( 'Featured Trail', 'crate' ); ?></h4> <span class="divider">|</span> <a class="featured-more-link" href="<?php echo esc_url( get_field( 'featured_trail_link' , 'option' ) ); ?>"></a>
				</div>
				<h3 class="feature-title"><a href="<?php echo esc_url( get_field( 'featured_trail_link' , 'option' ) ); ?>" title="<?php echo esc_attr( __( 'Go to ', 'crate' ) . get_field( 'featured_trail_title' , 'option' ) ); ?>"><?php echo esc_html( get_field( 'featured_trail_title' , 'option' ) ); ?></a></h3>

				<div class="feature-meta">
					<span class="length"><?php echo esc_html( get_field( 'featured_trail_length' , 'option' ) ); ?><span> <span class="divider">|</span> <span class="difficulty"><?php echo esc_html( get_field( 'featured_trail_difficulty' , 'option' ) ); ?></span>
				</div>
				
				<?php
				if ( get_field( 'featured_trail_description' , 'option' ) ) {
					$excerpt = get_field( 'featured_trail_description' , 'option' );
					?>

					<div class="feature-description">
						<p><?php echo $excerpt; ?></p>
					</div>
					
				<?php } ?>

			</div>
		</div>
	</div>

	<?php }//end if
}//end if
?>
