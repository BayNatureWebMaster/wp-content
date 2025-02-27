<?php
/**
 * Crate functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Crate
 */

/**
 * New for 2017: Namespacing! No need to prefix all function names with crate_ anymore!
 */
namespace CShop\Crate;

define( 'CRATE_VERSION', '4.2.0' );


/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( 'crate', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// Example code for gallery image
	// add_image_size( 'gallery', 400, 400, true );
	// Example for retina version for ResponsifyWP and others
	// add_image_size( 'gallery@2x', 800, 800, true );

	// This theme uses wp_nav_menu() in three locations.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'crate' ),
		'utility' => esc_html__( 'Utility', 'crate' ),
		'mobile'  => esc_html__( 'Mobile', 'crate' ),
		'donate' => esc_html__('Donate', 'crate'),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/**
	 * Support Gutenthings
	 */
	add_theme_support( 'align-wide' );

	// Color palette
	add_theme_support(
		'editor-color-palette', array(
			array(
				'name'  => esc_html__( 'Black', 'crate' ),
				'slug'  => 'black',
				'color' => '#000000',
			),
			array(
				'name'  => esc_html__( 'White', 'crate' ),
				'slug'  => 'white',
				'color' => '#ffffff',
			),
			array(
				'name'  => esc_html__( 'Dark Gray', 'crate' ),
				'slug'  => 'dark-gray',
				'color' => '#2c2c2c',
			),
			array(
				'name'  => esc_html__( 'Gray', 'crate' ),
				'slug'  => 'gray',
				'color' => '#363636',
			),
			array(
				'name'  => esc_html__( 'Light Gray', 'crate' ),
				'slug'  => 'light-gray',
				'color' => '#ecebea',
			),
			array(
				'name'  => esc_html__( 'Pinnacles', 'crate' ),
				'slug'  => 'pinnacles',
				'color' => '#f26322',
			),
			array(
				'name'  => esc_html__( 'Sunset', 'crate' ),
				'slug'  => 'sunset',
				'color' => '#ae1327',
			),
			array(
				'name'  => esc_html__( 'Storm', 'crate' ),
				'slug'  => 'storm',
				'color' => '#6193b7',
			),
			array(
				'name'  => esc_html__( 'Cobalt', 'crate' ),
				'slug'  => 'cobalt',
				'color' => '#003660',
			),
			array(
				'name'  => esc_html__( 'Calm Ocean', 'crate' ),
				'slug'  => 'calm-ocean',
				'color' => '#1a9aa2',
			),
			array(
				'name'  => esc_html__( 'Ocean Storm', 'crate' ),
				'slug'  => 'ocean-storm',
				'color' => '#03505b',
			),
			array(
				'name'  => esc_html__( 'Ivy', 'crate' ),
				'slug'  => 'ivy',
				'color' => '#447223',
			),
			array(
				'name'  => esc_html__( 'Eucalyptus', 'crate' ),
				'slug'  => 'eucalyptus',
				'color' => '#588075',
			),
			array(
				'name'  => esc_html__( 'Light Green', 'crate' ),
				'slug'  => 'light-green',
				'color' => '#67b24b',
			),
			array(
				'name'  => esc_html__( 'Poppy', 'crate' ),
				'slug'  => 'poppy',
				'color' => '#faa61a',
			),
			array(
				'name' => esc_html__( 'Yellow', 'crate' ),
				'slug'  => 'yellow',
				'color' => '#ffdf00',
			),
			array(
				'name'  => esc_html__( 'Bark', 'crate' ),
				'slug'  => 'bark',
				'color' => '#3c1605',
			),
			array(
				'name'  => esc_html__( 'Brown', 'crate' ),
				'slug'  => 'brown',
				'color' => '#b79257',
			)
		)
	);

	// Force users to ONLY use palette colors
	add_theme_support( 'disable-custom-colors' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup' );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'crate' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'crate' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area 1', 'crate' ),
		'id'            => 'footer-widget-area-1',
		'description'   => esc_html__( 'Add widgets here.', 'crate' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area 2', 'crate' ),
		'id'            => 'footer-widget-area-2',
		'description'   => esc_html__( 'Add widgets here.', 'crate' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area 3', 'crate' ),
		'id'            => 'footer-widget-area-3',
		'description'   => esc_html__( 'Add widgets here.', 'crate' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area 4', 'crate' ),
		'id'            => 'footer-widget-area-4',
		'description'   => esc_html__( 'Add widgets here.', 'crate' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', __NAMESPACE__ . '\widgets_init' );

/**
 * Get rid of default woocommerce styles
 */
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/**
 * Include other functions
 */
foreach ( glob( __DIR__ . "/inc/*.{php,inc}", GLOB_BRACE ) as $filename ) {
	include $filename;
}
