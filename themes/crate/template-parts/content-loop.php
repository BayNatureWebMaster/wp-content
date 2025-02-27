<?php
/**
 * Template part for displaying posts in a loop, typically in an archive of some kind.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Crate
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'in-loop' ); ?>>

	<?php
	$thumbnail         = get_the_post_thumbnail( get_the_ID(), 'medium' );
	$thumbnail_default = get_field( 'default_post_thumbnail_id', 'options' );
	$categories        = get_the_category();
	$type              = get_post_type();

	if ( has_post_thumbnail() ) : ?>

		<figure>
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( array( 'width' => 600, 'height' => 400, 'crop' => true ) ); ?></a>
		</figure>

	<?php elseif ( ! empty( $thumbnail_default ) ) : ?>

		<figure>
			<a href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( $thumbnail_default, 'medium' ); ?></a>
		</figure>

	<?php endif; ?>

	<div>
		<header class="entry-header">
			<?php
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );

				if ( $type !== 'product' ) {
			?>
				<div class="entry-source">
					<span class="entry-date"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>&nbsp;•&nbsp;
					<span class="entry-author">
						<?php
							echo esc_html( 'by ' );
						if ( function_exists( 'coauthors_posts_links' ) ) {
							coauthors_posts_links();
						} else {
							the_author();
						}
						?>
					</span>
				</div><!-- .entry-meta -->
			<?php } ?>

		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_excerpt(); ?>
		</div><!-- .entry-content -->
	</div>

</article><!-- #post-## -->
