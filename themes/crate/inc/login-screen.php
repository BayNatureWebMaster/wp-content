<?php
/**
 * Login screen customizations
 *
 * @package Produce
 */

/**
 * Change logo URL.
 */
function crate_logo_url() {
	return home_url();
}
add_filter( 'login_headerurl', 'crate_logo_url' );


/**
 * Change logo title.
 */
function crate_logo_url_title() {
	return get_bloginfo( 'name' );
}
add_filter( 'login_headertitle', 'crate_logo_url_title' );


/**
 * Restyle the login screen logo.
 */
function crate_login_logo() {
	?>
	<style type="text/css">
		.login #login h1 a {
			background-image: url( <?php echo wp_get_attachment_url( get_field( 'site_logo', 'option' ) ); ?> );
			background-size: contain !important;
			width: 235px;
			height: 33px;
		}

		body.login {
			background: #f1f1f1;
		}
	</style>
<?php }
add_action( 'login_enqueue_scripts', 'crate_login_logo' );
