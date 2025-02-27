<?php
/**
 * Event partial
 *
 * @package Crate
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed' ); }

?>
<?php if ( get_field( 'featured_event', 'option' ) ) { ?>

	<div class="container feature-wrap">
		<div class="feature-content event-content">
			<?php 
			
			$event = get_post( get_field( 'featured_event', 'option' ) );

			?>
			<div class="feature-thumb">
				<div class="featured-image-wrap">
					<div class="featured-image" aria-hidden="true" style="background-image: url( <?php echo get_the_post_thumbnail_url( $event->ID, 'large' ); ?>);"></div>
					<?php echo get_the_post_thumbnail( $event->ID, 'thumbnail', array( "class" => "visuallyhidden", "alt" => $event->post_title ) ); ?>
					<a href="<?php echo get_permalink( $event->ID ); ?>" title="<?php echo esc_attr( __( 'Go to ', 'crate' ). $event->post_title ); ?>"><span class="visuallyhidden"><?php echo esc_html( $event->post_title ); ?></span></a>
				</div>
			</div>
			<div class="feature-info">
				<h3 class="feature-title"><a href="<?php echo get_permalink( $event->ID ); ?>" title="<?php echo esc_attr( __( 'Go to ', 'crate' ). $event->post_title ); ?>"><?php echo esc_html( $event->post_title ); ?></a></h3>
				<span class="feature-date">
					<?php
						if ( tribe_get_start_date( $event->ID, true, 'l, F j' ) === tribe_get_end_date( $event->ID, true, 'l, F j' ) ) {
							echo tribe_get_start_date( $event->ID, true, 'l, F j @ g:i a' ) . ' - ' . tribe_get_end_date( $event->ID, true, 'g:i a' );
						} else {
							echo __( 'Starts: ', '' ) . tribe_get_start_date( $event->ID, true, 'l, F j @ g:i a' ) .' | '. __( 'Ends: ', '' ) . tribe_get_end_date( $event->ID, true, 'l, F j @ g:i a' );
						}
					?>
				</span>
				<?php if ( 0 ===  (int) get_post_meta( $event->ID, '_EventCost', true ) ) { ?>
					 | <span class="feature-price"><?php esc_html_e( 'Free', 'crate' ) ?></span>
				<?php } ?>
				
				<?php if ( $event->post_content ) { 
					// This limits the post content to 220 characters and doesn't cut off the last word
					$excerpt = preg_replace('/\s+?(\S+)?$/', '', substr($event->post_content, 0, 220));
				?>

					<div class="feature-description">
						<p><?php echo $excerpt; ?></p>
					</div>
					
				<?php } ?>

				<a href="<?php echo get_permalink( $event->ID ); ?>" title="<?php echo esc_attr( __( 'Go to ', 'crate' ). $event->post_title ); ?>"><?php esc_html_e( 'Learn More', 'crate' ); ?></a>

			</div>
		</div>
	</div>

<?php } ?>