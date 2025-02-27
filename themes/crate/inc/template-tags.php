<?php
/**
 * Functions that are called directly from template files, typically to display post information
 *
 * @package Crate
 */

function crate_post_source( $in_loop ) {

	$post_type = get_post_type();

	$source_elements = array();

	// Output the date.
	$source_elements['date'] = array(
		'element' => 'span',
		'class'   => 'entry-date',
		'content' => get_the_date(),
	);

	// If there's an author, output that.
	if ( get_the_author() ) {
		$source_elements['author'] = array(
			'element' => 'span',
			'class'   => 'entry-author',
			// Output a link to the author archive with the Author's chosen display name showing.
			'content' => sprintf( '<span class="author vcard">By <a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
				get_author_posts_url( get_the_author_meta( 'ID' ) ),
				sprintf( esc_attr__( 'View all posts by %s', 'crate' ), get_the_author() ),
				get_the_author()
			)
		);
	}

	// Make it possible to hook other stuff on. This is useful for keeping post types self-contained.
	$source_elements = apply_filters( 'post_source_elements', $source_elements, $in_loop );

	// Make it possible to change the separator.
	$source_separator = apply_filters( 'post_source_separator', ' • ' );

	// Now output them all.
	foreach( $source_elements as $element ) {
		printf( '<%s class="%s">%s</%1$s>',
			$element['element'],
			$element['class'],
			$element['content']
		);
		if ( $element !== end( $source_elements ) ) {
			print $source_separator;
		}
	}

}
