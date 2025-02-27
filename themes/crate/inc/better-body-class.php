<?php
/**
 * Makes some basic improvements to the wp-generated body class
 *
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/body_class
 *
 * @package Crate
 */

namespace CShop\Crate;

if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access not allowed' ); }

/**
 * Strips some things off WordPress's default body classing, and adds page slug.
 */
function body_class( $classes ) {

	// Clean up template names, because who needs -php in their HTML?
	foreach ( $classes as &$class ) {
		if ( strpos( $class, '-php' ) ) {
			$class = str_replace( array( 'page-', '-php' ), '', $class );
		}
	}

	// Add the current post slug as a class, for hyper-specific css targeting.
	if ( is_singular() ) {
		global $post;
		$classes[] = 'single-' . $post->post_name;
	}

	return $classes;
}
add_filter( 'body_class', __NAMESPACE__ . '\body_class' );
