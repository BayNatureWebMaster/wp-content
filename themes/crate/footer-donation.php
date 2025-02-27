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
<script type="text/javascript">
jQuery('body').on('charitable:form:processed', function(e, donation) {
var inputElements = document.querySelectorAll('.donation-amount.selected input');
var amountInputElement = inputElements[inputElements.length-1];
var amount = amountInputElement.value;

if ('month' === document.querySelector('[name=recurring_donation]:checked').value) {
    amount = amount * 12;
}
window.dataLayer = window.dataLayer || [];
dataLayer.push({
'event': 'Donation',
'amount': amount
});
});
</script>
</html>