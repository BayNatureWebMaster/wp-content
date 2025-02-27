<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Crate
 */

?>

</div> <!-- /.site-content-->
<footer class="site-footer">

	<div class="container">

		<div class="row">

		<?php 
			// Check if footer widget area is active and display if true. (4 possible widget areas)
			for ($i = 1; $i <= 4; $i++) {
			
				if ( is_active_sidebar( 'footer-widget-area-'. $i ) ) {
					echo '<div class="column">';
						dynamic_sidebar( 'footer-widget-area-'. $i );
					echo '</div>';
				}

			} 
		?>

		</div>

	</div>

	<div class="container">
		<div class="credit">
			<a href="https://cornershopcreative.com" target="_blank" rel="noopener noreferrer"><span class="text"><?php esc_html_e( 'Crafted by Cornershop Creative', 'crate' ); ?></span><i></i></a>
		</div>
	</div>

</footer>
</div><!-- /#body-wrapper -->
<?php wp_footer(); ?>
</body>
</html>