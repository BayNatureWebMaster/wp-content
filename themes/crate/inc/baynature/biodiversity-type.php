<?php
/**
 * Biodiversity Post Type
 *
 * @package Crate
 */
namespace CShop\Crate;

// BIODIVERSITY SERIES - CUSTOM POST TYPE
function biodiversity_type() {
  $labels = array(
    'name'               => _x( 'Biodiversity', 'post type general name' ),
    'singular_name'      => _x( 'Article', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'article' ),
    'add_new_item'       => __( 'Add New Article' ),
    'edit_item'          => __( 'Edit Article' ),
    'new_item'           => __( 'New Article' ),
    'all_items'          => __( 'All Articles' ),
    'view_item'          => __( 'View Article' ),
    'search_items'       => __( 'Search Articles' ),
    'not_found'          => __( 'No articles found' ),
    'not_found_in_trash' => __( 'No articles found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => 'Biodiversity Series'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Biodiversity Series',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author' ),
    'has_archive'   => true,
  );
  register_post_type( 'biodiversity', $args ); 
}
add_action( 'init', __NAMESPACE__ . '\biodiversity_type' );