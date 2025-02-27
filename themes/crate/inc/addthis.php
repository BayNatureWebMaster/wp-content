<?php
/**
 * Adds the Addthis social sharing javaScript code to the theme
 *
 * @package Produce
 */

namespace CShop\Crate;

/**
 * Enqueue the Addthis JS code
 *
 * @return void
 */
function addthis_inject() {
	$add_this_script = sprintf( 'https://s7.addthis.com/js/300/addthis_widget.js#pubid=%s', 'ra-5b4e61ef457c6ba0' );
	wp_register_script( 'crate-addthis', $add_this_script, array(), false, true );
	wp_enqueue_script( 'crate-addthis' );
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\addthis_inject' );

/**
 * Add the async/defer attribute when enqueuing the Addthis JS script
 *
 * @return string Script loader tag with the async attribute added to it
 */
function addthis_async_attribute( $tag, $handle ) {
	if ( 'crate-addthis' !== $handle ) {
		return $tag;
	}
	return str_replace( ' src', ' async="async" src', $tag );
}

add_filter( 'script_loader_tag', __NAMESPACE__ . '\addthis_async_attribute', 10, 2 );
