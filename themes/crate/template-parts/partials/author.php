<?php
/**
 * Author partial
 *
 * @package Crate
 */
global $post;
//bn_print_r($post);
//echo wp_get_document_title() . " LRT "; 
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed' ); }
	$authors = get_coauthors();
	$heading = ( count( $authors ) > 1 ? __( 'About the Authors', 'crate' ) : __( 'About the Author', 'crate' ) );
?>

<?php if ( ! empty( $authors ) ) {   ?> 
	<div class="container author-wrap">
		<div class="author-content">
			<h6 class="author-heading"><?php echo esc_html( $heading ); ?></h6>

			<?php $author_count = 0; foreach ( $authors as $author ) {

			?>
				<div class="author">

					<div class="author-name">
						<a href="<?php // Bay Nature Mod = EDIT-69. July 24 2019 echo esc_url( get_author_posts_url($post->post_author) ); ?>"><?php //echo esc_html( $author->display_name ); ?>
						<?php
						if ( function_exists( 'coauthors_posts_links' ) ) {
							$authors_html = coauthors_posts_links(null,null,null,null,false);
							/* In the case where there is more than one author for an article we only want to display the authors name and post link once */
							if ( 0 === $author_count ) echo $authors_html;
							$author_count++;
						} else {
							the_author();
						}
						?>
						</a>
					</div>
					<?php if ( ! empty( $author->description ) ) { ?>
						<div class="author-description">
							<p><?php echo nl2br( $author->description ); ?></p>
						</div>

					<?php } ?>

				</div>
			<?php 
			} ?>
		</div>
	</div>
<?php } ?>


