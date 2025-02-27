<?php
/**
 * Handing (de)enqueueing scripts and styles to be loaded into Crate
 *
 * @package Crate
 */

namespace CShop\Crate;

if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access not allowed' ); }


/**
 * Main enqueue handler for front-end of the site
 */
function enqueue() {

	if ( ! is_admin() ) {

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			wp_enqueue_style( 'crate_style', get_template_directory_uri() . '/css/crate.css', array(), CRATE_VERSION );
			wp_enqueue_script( 'crate', get_template_directory_uri() . '/js/crate.js', array( 'jquery' ), CRATE_VERSION, true );
		} else {
			wp_enqueue_style( 'crate_style', get_template_directory_uri() . '/css/crate.min.css', array(), CRATE_VERSION );
			wp_enqueue_script( 'crate', get_template_directory_uri() . '/js/crate.min.js', array( 'jquery' ), CRATE_VERSION, true );
		}

		// put the AJAX endpoint URL into theme.ajaxurl
		wp_localize_script( 'crate', 'crate', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		// July 15 2019 - Removing this block of code. This was breaking the event cal - js error. 
		// It's well known that WP loads an old version of JQ for backward compatibility reasons
		/*
			Uncaught TypeError: a(...).tribe_spin is not a function
    		at g (tribe-events-ajax-list.min.js?ver=4.9.3.2:1)
    		at tribe-events-ajax-list.min.js?ver=4.9.3.2:1
    		at Object.pre_ajax (tribe-events-pro.min.js?ver=4.7.3:1)
    		at HTMLAnchorElement.<anonymous> (tribe-events-ajax-list.min.js?ver=4.9.3.2:1)
    		at HTMLDivElement.dispatch (jquery.js?ver=1.12.4:3)
    		at HTMLDivElement.r.handle (jquery.js?ver=1.12.4:3)
		*/
		//deregister default jquery, add updated version
		//wp_deregister_script( 'jquery' );
		//wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', array(), '3.3.1', false);
		//wp_enqueue_script( 'jquery' );
	}

}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue' );

/**
 * Load some styles in Gutenberg editor
 */
function gutenqueue() {
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		wp_enqueue_style( 'crate_gutenberg_styles', get_template_directory_uri() . '/css/editor-style.css', array(), CRATE_VERSION );
	} else {
		wp_enqueue_style( 'crate_gutenberg_styles', get_template_directory_uri() . '/css/editor-style.min.css', array(), CRATE_VERSION );
	}
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\gutenqueue' );


/**
 * Get rid of emoji stuff
 */
function disable_emoji() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', __NAMESPACE__ . '\disable_emoji', 99 );
