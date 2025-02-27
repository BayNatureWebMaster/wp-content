<?php
/**
 * Includes legacy Bay Nature functions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Crate
 */

/**
 * New for 2017: Namespacing! No need to prefix all function names with crate_ anymore!
 */
namespace CShop\Crate;

/**
 * Include other functions
 */
foreach ( glob( __DIR__ . "/baynature/*.{php,inc}", GLOB_BRACE ) as $filename ) {
	include $filename;
}