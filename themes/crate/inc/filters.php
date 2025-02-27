<?php
/**
 * Theme filters and actions
 *
 * @package Crate
 */

namespace CShop\Crate;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed' ); }

/**
 * Sets FacetWP cache duration
 */
function facetwp_cache_lifetime( $seconds ) {
	return 86400; // one day
}
add_filter( 'facetwp_cache_lifetime', __NAMESPACE__ . '\facetwp_cache_lifetime' );

/**
 * Add style tag in head to modify theme styles
 */
function user_styles_override() {

	$season = get_field( 'current_season', 'option' );
	$primary_color = get_field( $season .'_primary_color', 'option' );
	$secondary_color = get_field( $season .'_secondary_color', 'option' );

	ob_start();

?>
		<style type="text/css">

			.entry-content blockquote {
				color: <?php echo esc_attr( $primary_color ); ?>;
			}

			.entry-content p.has-drop-cap:not(:focus):first-letter,
			.entry-content p .dropcap {
				color: <?php echo esc_attr( $primary_color ); ?>;
			}

			.site-header .primary-nav,
			footer.site-footer {
				border-bottom-color: <?php echo esc_attr( $primary_color ); ?>;
			}

		</style>
<?php

	$user_styles = ob_get_contents();

	ob_get_clean();

	echo $user_styles;
}
add_action( 'wp_head', __NAMESPACE__ . '\user_styles_override' );


/***
 * Related Post Suggestions
 */
function related_posts( $args, $field, $post_id ) {

	$categories = get_the_category( $post_id );
	$tags = get_the_tags(  $post_id );

	$cat_ids = wp_list_pluck( $categories, "term_id" );
	$tag_ids = wp_list_pluck( $tags, "term_id" );

	$args['post_type'] = array( 'post', 'article' );
	$args['post__not_in'] = array( $post_id );
	$args['category__in'] = $cat_ids;
	$args['tag__in'] = $tag_ids;


	// return
	return $args;

}
add_filter('acf/fields/relationship/query/name=related_posts', __NAMESPACE__ . '\related_posts', 10, 3);

/***
 * Staff Picked Posts
 */
function staff_picked_posts( $args, $field, $post_id ) {


	$args['post_type'] = array( 'post', 'article' );


	// return
	return $args;

}
add_filter('acf/fields/relationship/query/name=staff_picked_posts', __NAMESPACE__ . '\staff_picked_posts', 10, 3);

/***
 * Staff Picks for the Week
 */
function picks_for_the_week( $args, $field, $post_id ) {


	$args['post_type'] = array( 'post', 'article' );


	// return
	return $args;

}
add_filter('acf/fields/relationship/query/name=week_picks', __NAMESPACE__ . '\picks_for_the_week', 10, 3);

/***
 * Featured Article Suggestions
 */
function featured_posts_from_current_magazine( $args, $field, $post_id ) {
	
	//$magazine = wc_get_product( get_post_meta( $post_id, 'current_magazine', true ) );
	//$magazine_key = $magazine->get_sku();
	$magazine_key = get_field('issue_key', 4);

	if ( ! empty( $magazine_key ) ) {
		$args['post_type']  = array( 'article' );
		$args['meta_query'] = array(
				'relation' => 'OR',
				array(
					'key'     => 'issue_key',
					'value'   => $magazine_key,
					'compare' => '=',
				),
				array(
					'key'     => 'special_section',
					'value'   => $magazine_key,
					'compare' => '=',
				),
			);
	}

	// return
	return $args;

}
add_filter('acf/fields/post_object/query/name=featured_article', __NAMESPACE__ . '\featured_posts_from_current_magazine', 10, 3);
// EDIT- 74 11-03-21
add_filter('acf/fields/post_object/query/name=featured_article_left', __NAMESPACE__ . '\featured_posts_from_current_magazine', 10, 3);
add_filter('acf/fields/post_object/query/name=featured_article_right', __NAMESPACE__ . '\featured_posts_from_current_magazine', 10, 3);

/***
 * Customize Facet Pager HTML
 */
function facetwp_pager_html( $output, $params ) {

	// The logic below is to matach the default WP pagination functionality
	$output = '';
	$page = $params['page'];
	$total_pages = $params['total_pages'];
	$output .= '<ul class="page-numbers">';
	if ( $page > 1 ) {
		$output .= '<li><a class="facetwp-page page-numbers" data-page="' . ($page - 1) . '">« '. __( 'Previous', 'crate' ) .'</a></li>';
	}
	if ( $page < $total_pages && $total_pages > 1 && $page !== $total_pages ) {
		$output .= '<li><span aria-current="page" class="page-numbers current">'. $page .'</span></li>';

		if ( ($page + 1) < $total_pages ) {
			$output .= '<li><a class="facetwp-page page-numbers" data-page="' . ($page + 1) . '">'. ($page + 1) .'</a></li>';
		}
		if ( ($page + 2) < $total_pages ) {
			$output .= '<li><a class="facetwp-page page-numbers" data-page="' . ($page + 2) . '">'. ($page + 2) .'</a></li>';
		}
		if ( $page !== $total_pages - 1 ) {
			$output .= '<li><span class="page-numbers dots">…</span></li>';
		}
		$output .= '<li><a class="facetwp-page page-numbers" data-page="' . $total_pages . '">'. $total_pages .'</a></li>';
		$output .= '<li><a class="facetwp-page page-numbers" data-page="' . ($page + 1) . '">'. __( 'Next', 'crate' ) .' »</a></li>';
	}
	if ( $page === $total_pages && 1 < $total_pages ) {
		if ( ($page - 2) < $total_pages && 0 < ($page - 2) ) {
			$output .= '<li><a class="facetwp-page page-numbers" data-page="' . ($page - 2) . '">'. ($page - 2) .'</a></li>';
		}
		if ( ($page - 1) < $total_pages ) {
			$output .= '<li><a class="facetwp-page page-numbers" data-page="' . ($page - 1) . '">'. ($page - 1) .'</a></li>';
		}
		$output .= '<li><span aria-current="page" class="page-numbers current">'. $page .'</span></li>';
	}
	$output .= '</ul>';
	return $output;
}
add_filter( 'facetwp_pager_html', __NAMESPACE__ . '\facetwp_pager_html', 10, 2 );

/***
 * Allow only specified facet values for topics facet
 */
function include_specific_facets( $params, $class ) {
	if ( 'topics' == $params['facet_name'] ) {
		$included_terms = array( 'Science and Nature', 'Conservation', 'Recreation' );
		if ( ! in_array( $params['facet_display_value'], $included_terms ) ) {
			return false;
		}
	}
	return $params;
}
add_filter( 'facetwp_index_row', __NAMESPACE__ . '\include_specific_facets', 10, 2 );

/***
 * Customize facet term order
 */
function custom_facet_order( $orderby, $facet ) {
	if ( 'topics' == $facet['name'] ) {
		$orderby = 'FIELD(f.facet_display_value, "Science and Nature", "Conservation", "Recreation")';
	}
	return $orderby;
}
add_filter( 'facetwp_facet_orderby', __NAMESPACE__ . '\custom_facet_order', 10, 2 );

/***
 * Order search results by date desc
 */

/***
 * Replaces term count function used in Bay Nature plugin
 */
function update_terms_count() {
	$taxonomies = get_taxonomies();
	foreach ( $taxonomies as $taxonomy ) {
		$terms = get_terms( array(
			'taxonomy' => $taxonomy,
			'hide_empty' => false,
		) );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			$term_tax_ids = wp_list_pluck( $terms, 'term_taxonomy_id');
			wp_update_term_count_now( $term_tax_ids, $taxonomy );
		}
	}
}
// Do not uncomment, will break the saving of menu items
//add_action( 'save_post', __NAMESPACE__ . '\update_terms_count', 10, 3 );

/***
 * Add labels above facets
 */
function fwp_add_facet_labels() { ?>
	<script>
	(function($) {
		$(document).on('facetwp-loaded', function() {
			$('.facetwp-facet').each(function() {
				var facet_name = $(this).attr('data-name');
				var facet_label = FWP.settings.labels[facet_name];
				if ($('.facet-label[data-for="' + facet_name + '"]').length < 1) {
					$(this).before('<h3 class="facet-label" data-for="' + facet_name + '">' + facet_label + '</h3>');
				}
			});
		});
	})(jQuery);
	</script>
<?php
}
add_action( 'wp_head', __NAMESPACE__ . '\fwp_add_facet_labels', 100 );

/***
 * Exclude articles and posts with "Archive" category 
 */
function exclude_category( $query ) {
	if ( $query->is_main_query() && ! is_admin() ) {
		$exclude = get_cat_ID('Archive');
		$query->set( 'cat', '-'. $exclude );
	}

	// Include articles and posts for author and category archives
	if ( ( $query->is_author() || $query->is_category() ) && $query->is_main_query() && ! $query->is_admin() ) {
		$query->set( 'post_type', array( 'article', 'post' ) );
	}
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\exclude_category' );

/***
 * ALWAYS return search results ordered by date 
 */
//add_filter( 'searchwp_return_orderby_date', '__return_true' );

/***
 * Exclude articles and posts with "Archive" category from being indexed by searchwp
 */
function searchwp_exclude( $ids, $engine, $terms ) {

	$entries_to_exclude = get_posts(
		array(
			'post_type' => 'any',
			'nopaging'  => true,
			'fields'    => 'ids',
			'tax_query' => array(
				array(
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => array( 'archive' ),
				),
			),
		)
	);

	$ids = array_unique( array_merge( $ids, array_map( 'absint', $entries_to_exclude ) ) );

	return $ids;

}
//add_filter( 'searchwp_exclude', __NAMESPACE__ . '\searchwp_exclude', 10, 3 );
