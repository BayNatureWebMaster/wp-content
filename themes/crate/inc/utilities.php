<?php
/**
 * Site utilities.
 *
 *
 * @package Crate
 */

/**
 * Get this weeks date
 */
function current_week() {
	// set current timestamp
	$today = time();
	$w = array();

	// calculate the number of days since Monday
	$dow = date('w', $today);
	$offset = $dow - 1;

	if ($offset < 0) {
		$offset = 6;
	}

	// calculate timestamp from Monday to Sunday
	$monday = $today - ($offset * 86400);
	$tuesday = $monday + (1 * 86400);
	$wednesday = $monday + (2 * 86400);
	$thursday = $monday + (3 * 86400);
	$friday = $monday + (4 * 86400);
	$saturday = $monday + (5 * 86400);
	$sunday = $monday + (6 * 86400);

	// return current week array
	$w['monday'] = $monday ;
	$w['sunday'] = $sunday ;

	return $w;
}

function current_week_date_format() {
	$current_week = current_week();

	$date_string = date('F j', $current_week['monday']);
	$date_string .= '-';
	$date_string .= date('F j', $current_week['sunday']);

	return $date_string;
}

function is_in_magazine( $post_id ) {

	$issue_key = get_post_meta( $post_id, 'issue_key', true );

	if ( 'null' === $issue_key || empty( $issue_key ) )
		return false; 

	return true;

}
