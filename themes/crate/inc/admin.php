<?php
/**
 * Functions that hack, add pages to, or otherwise modify the admin UI
 *
 * @package Crate
 */

namespace CShop\Crate;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed' );
}

/**
 * Remove some admin menus we never use, and add an 'Edit Home' link in Pages
 */
function alter_admin_menus() {
	global $menu, $submenu;

	// Remove menus we don't need.
	$restricted = array( __( 'Feed Items', 'crate' ) );
	foreach ( $menu as $key => $item ) {
		$text_title = trim( strip_tags( $item[0] ) );
		if ( in_array( $text_title, $restricted, true ) ) {
			unset( $menu[ $key ] );
		}
	}

	// Add a handy Edit Home link.
	if ( get_option( 'page_on_front' ) ) {
		$submenu['edit.php?post_type=page'][15] = array(
			__( 'Edit Home', 'crate' ),
			'edit_pages',
			'post.php?action=edit&post=' . get_option( 'page_on_front' ),
		);
	}

}
add_action( 'admin_menu', __NAMESPACE__ . '\alter_admin_menus' );


/**
 * Reminder about DB_NAME when working on the Cornershop development server.
 */
function db_notice() {
	echo '<div class="notice error"><p>The database is currently set to <strong>demo_wordpress</strong>. Please use a different database!</p></div>';
}
if ( DB_NAME === 'dev_wordpress' || DB_NAME === 'demo_wordpress' ) {
	add_action( 'admin_notices', __NAMESPACE__ . '\db_notice' );
}


/**
 * Reminder about being on the dev server when working on the Cornershop development server and not logged in as us.
 */
function devserver_notice() {
	echo '<div class="notice error is-dismissible" style="background-color: #dc3232; color: white; text-transform: uppercase;"><p><strong>You are currently working on the Cornershop development server.</strong></p></div>';
}
if ( isset( $_SERVER['HTTP_HOST'] ) && false !== stripos( $_SERVER['HTTP_HOST'], 'cshp.co' ) && wp_get_current_user()->user_login !== 'cornershop' ) {
	add_action( 'admin_notices', __NAMESPACE__ . '\crate_devserver_notice', 1 );
}


/**
 * Remove the annoying Customize link from the admin bar.
 */
function remove_admin_bar_customize( $wp_admin_bar ) {
	$wp_admin_bar->remove_menu( 'customize' );
}
add_action( 'admin_bar_menu', __NAMESPACE__ . '\remove_admin_bar_customize', 999 );


/**
 * Fix the annoying "password field is empty" error in Chrome.
 * See https://premium.wpmudev.org/blog/fixing-password-empty-wordpress-chrome-error/
 */
function kill_wp_attempt_focus_start() {
	ob_start( 'kill_wp_attempt_focus_replace' );
}
//add_action( 'login_form', __NAMESPACE__ . '\kill_wp_attempt_focus_start' );

/**
 * Not sure what this does, but it works.
 */
function kill_wp_attempt_focus_replace( $html ) {
	return preg_replace( "/d.value = '';/", '', $html );
}

/**
 * Spit out the modified form.
 */
function kill_wp_attempt_focus_end() {
	ob_end_flush();
}
//add_action( 'login_footer', __NAMESPACE__ . '\kill_wp_attempt_focus_end' );

/**
 * Prevent users from editing .htaccess, etc. using Yoast, because that's sketchy and weird and ew.
 */
add_filter( 'wpseo_allow_system_file_edit', '__return_false' );

/**
 * Disable wp-admin menu for ACF unless SCRIPT_DEBUG is enabled,
 * which will restrict the ACF admin to development environments.
 *
 * @see https://www.advancedcustomfields.com/resources/how-to-hide-acf-menu-from-clients/
 *
 * @param boolean $show True to show the admin, false to hide
 * @return boolean Disabled ACF admin unless it was otherwise enabled & SCRIPT_DEBUG is set to true
 */
function acf_show_admin( $show ) {
	return $show && defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
}

//add_filter( 'acf/settings/show_admin', __NAMESPACE__ . '\acf_show_admin' );
