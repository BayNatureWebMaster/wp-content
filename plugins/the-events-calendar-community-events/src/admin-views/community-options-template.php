<?php

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Tribe Community settings
 *
 * @package Tribe__Events__Community__Main
 *
 * @author  The Events Calendar
 *
 * @since   1.0
 *
 * @var     Tribe__Events__Community__Main $tce
 */
$tce = tribe( 'community.main' );

// Settings will be displayed conditionally upon $base_url empty or not
if ( '' === get_option( 'permalink_structure' ) ) {
	$base_url = '';
} else {
	$base_url = trailingslashit( trailingslashit( home_url() ) . $tce->getCommunityRewriteSlug() );
}

$trash_vs_delete_options = [
	'1' => __( 'Placed in the Trash', 'tribe-events-community' ),
	'0' => __( 'Permanently Deleted', 'tribe-events-community' ),
];

/*
 * Note to editors: Due to the foreach at the end,
 * you do not have to set type on html entries
 * and you do not have to add a parent option to inputs - it's all done for you!
 */

// Set up roles.
$blockRolesList = $tce->getOption( 'blockRolesList' );

if ( empty( $blockRolesList ) ) {
	$blockRolesList = [];
}

$redirectRoles = [];

foreach ( get_editable_roles() as $role => $atts ) {
	// Don't let them lock admins out.
	if ( 'administrator' === $role ) {
		continue;
	}

	$redirectRoles[ $role ] = $atts['name'];
}

$statuses = [
	'draft'   => 'Draft',
	'pending' => 'Pending Review',
	'publish' => 'Published',
];

/**
 * Allow for customizing the possible post statuses that submitted events default to.
 *
 * @since 1.0
 *
 * @param array $statuses
 */
$statuses = apply_filters( 'tribe_community_events_default_status_options', $statuses );

$info_fields = [
	'info-start'           => [
		'html' => '<div id="modern-tribe-info">',
	],
	'info-box-title'       => [
		'html' => '<h3>' . __( 'Community Settings', 'tribe-events-community' ) . '</h3>',
	],
	'info-box-description' => [
		'html' =>
			sprintf(
				__( '<p>Community enables users to submit events through a form on your site. Whether soliciting contributions from anonymous users or registered members, you have complete editorial control over what makes it onto the calendar.</p><p>Check out our <a href="%s">Community New User Primer</a> for information on configuring and using the plugin.', 'tribe-events-community' ),
				  'https://evnt.is/cestart/?utm_campaign=in-app&utm_medium=plugin-community&utm_source=communitytab')
	],
	'info-end'             => [
		'html' => '</div>',
	],
];

$general_fields = [
	'form-wrap-start'           => [
		'html' => '<div class="tribe-settings-form-wrap tribe-community-options">',
	],
	'general-heading'           => [
		'type'  => 'heading',
		'label' => __( 'General', 'tribe-events-community' ),
	],
	'allowAnonymousSubmissions' => [
		'type'            => 'checkbox_bool',
		'label'           => __( 'Allow anonymous submissions', 'tribe-events-community' ),
		'tooltip'         => __( 'Check this box to allow users to submit events without having a WordPress account', 'tribe-events-community' ),
		'default'         => false,
		'validation_type' => 'boolean',
	],
	'useVisualEditor'           => [
		'type'            => 'checkbox_bool',
		'label'           => __( 'Use visual editor for event descriptions', 'tribe-events-community' ),
		'default'         => false,
		'validation_type' => 'boolean',
	],
	'default_post_type' => [
		'type'            => 'wrapped_html',
		'label'           => __( 'Default Post Type for submitted events', 'tribe-events-community' ),
		'html' => $tce->get_community_events_post_type(),
	],
	'defaultStatus'             => [
		'type'            => 'dropdown',
		'label'           => __( 'Default status for submitted events', 'tribe-events-community' ),
		'validation_type' => 'options',
		'size'            => 'large',
		'default'         => 'draft',
		'options'         => $statuses,
	],
	'spamPrevention'            => [
		'type'  => 'wrapped_html',
		'label' => __( 'Spam Prevention', 'tribe-events-community' ),
		'html'  => '<p><i>'
			. sprintf(
				__(
					'To enable spam prevention for anonymous submissions, enter your reCAPTCHA API keys under <a href="%1$s">%2$s</a>',
					'tribe-events-community'
				),
				$tce->get_settings_strategy()->get_url( [ 'tab' => 'addons' ] ),
				_x(
					'Events &rarr; Settings &rarr; APIs',
					'Click path in The Events Calendar settings to get to the API settings section separated by right arrows (HTML "&rarr;")',
					'tribe-events-community'
				)
			) . '</i></p>',
	],
];

$terms_fields = [
	'terms-heading'     => [
		'type'  => 'heading',
		'label' => __( 'Terms of Submissions', 'tribe-events-community' ),
	],
	'termsEnabled' => [
		'type'            => 'checkbox_bool',
		'label'           => __( 'Enable Terms of Submission?', 'tribe-events-community' ),
		'default'         => false,
		'validation_type' => 'boolean',
		'tooltip'         => __( 'Event submitters will have to agree to your terms to add/edit events.', 'tribe-events-community' ),
	],
	'termsDescription'    => [
		'type'            => 'textarea',
		'label'           => __( 'Terms of submission', 'tribe-events-community' ),
		'default'         => '',
		'tooltip'         => __( 'The Terms of Submission that event submitters will have to agree to upon adding/editing events.', 'tribe-events-community' ),
		'validation_type' => 'textarea',
	],
];

$community_rewrite_slug_settings = [
	'communityRewriteSlug'     => [
		'type'            => 'text',
		'label'           => __( 'Community rewrite slug', 'tribe-events-community' ),
		'validation_type' => 'slug',
		'size'            => 'medium',
		'default'         => 'community',
		'tooltip'         => __(
			'The slug used for building the Community URL - it is appended to the main events slug.',
			'tribe-events-community'
		),
	],
	'community-add-slug'       => [
		'type'            => 'text',
		'label'           => __( 'Add slug', 'tribe-events-community' ),
		'validation_type' => 'slug',
		'size'            => 'medium',
		'default'         => $tce->get_default_rewrite_slugs( 'add' ),
		'tooltip'         => __(
			'The slug used for building the community "add event" URL - it is appended to the main Community slug.',
			'tribe-events-community'
		),
	],
	'community-list-slug'      => [
		'type'            => 'text',
		'label'           => __( 'List slug', 'tribe-events-community' ),
		'validation_type' => 'slug',
		'size'            => 'medium',
		'default'         => $tce->get_default_rewrite_slugs( 'list' ),
		'tooltip'         => __(
			'The slug used for building the community "events list" URL - it is appended to the main Community slug.',
			'tribe-events-community'
		),
	],
	'community-edit-slug'      => [
		'type'            => 'text',
		'label'           => __( 'Edit slug', 'tribe-events-community' ),
		'validation_type' => 'slug',
		'size'            => 'medium',
		'default'         => $tce->get_default_rewrite_slugs( 'edit' ),
		'tooltip'         => __(
			'The slug used for building the community "edit event" URL - it is appended to the main Community slug.',
			'tribe-events-community'
		),
	],
	'community-venue-slug'     => [
		'type'            => 'text',
		'label'           => __( 'Venue slug', 'tribe-events-community' ),
		'validation_type' => 'slug',
		'size'            => 'medium',
		'default'         => $tce->get_default_rewrite_slugs( 'venue' ),
		'tooltip'         => __(
			'The slug used for building the community "edit venue" URL - it is appended to the main Community slug.',
			'tribe-events-community'
		),
	],
	'community-organizer-slug' => [
		'type'            => 'text',
		'label'           => __( 'Organizer slug', 'tribe-events-community' ),
		'validation_type' => 'slug',
		'size'            => 'medium',
		'default'         => $tce->get_default_rewrite_slugs( 'organizer' ),
		'tooltip'         => __(
			'The slug used for building the community "edit organizer" URL - it is appended to the main Community slug.',
			'tribe-events-community'
		),
	],
	'community-event-slug'     => [
		'type'            => 'text',
		'label'           => __( 'Event slug', 'tribe-events-community' ),
		'validation_type' => 'slug',
		'size'            => 'medium',
		'default'         => $tce->get_default_rewrite_slugs( 'event' ),
		'tooltip'         => __(
			'The slug used for building the community "edit event" URL - it is appended to the main Community slug.',
			'tribe-events-community'
		),
	],
];

// Auto-check the rewrites checkbox upon page load if existing customizations exist.
$checked = '';

foreach ( (array) $tce::getOptions() as $key => $value ) {
	if (
		! empty( $value )
		&& array_key_exists( $key, $community_rewrite_slug_settings )
	) {
		$checked = checked( true, true, false );
		break;
	}
}

// @todo redscar - It would be great to make this dynamic based off of the routes that are created instead of hardcoding them.
$base_urls = [
	[
		'name'  => 'Community base',
		'url'   => $base_url,
		'order' => 1,
	],
	[
		'name'  => 'List events',
		'url'   => $base_url . sanitize_title( $tce->get_rewrite_slug( 'list' ) ),
		'order' => 2,
	],
	[
		'name'  => 'Add event',
		'url'   => $base_url . sanitize_title( $tce->get_rewrite_slug( 'add' ) ),
		'order' => 3,
	],
];

$edit_urls = [
	[
		'name'  => 'Edit event',
		'url'   => trailingslashit( $base_url ) . trailingslashit( sanitize_title( $tce->get_rewrite_slug( 'edit' ) ) ) . sanitize_title( $tce->get_rewrite_slug( 'event' ) ),
		'order' => 1,
	],
];

/**
 * Filters the array of base community URLs for further customization.
 *
 * @since 5.0.0
 *
 * @param array  $base_urls Array of base URLs and their details.
 * @param string $base_url The base URL used by Community.
 *
 * @return array Filtered array of base URLs.
 */
$base_urls = apply_filters( 'tribe_community_settings_base_urls', $base_urls, $base_url );

/**
 * Filters the array of community edit URLs that require an ID appended for further customization.
 *
 * @since 5.0.0
 *
 * @param array  $edit_urls Array of edit URLs and their details.
 * @param string $base_url The base URL used by Community.
 *
 * @return array Filtered array of edit URLs.
 */
$edit_urls = apply_filters( 'tribe_community_settings_edit_urls', $edit_urls, $base_url );

$current_base_url_display = '<ul>';
foreach ( $base_urls as $url ) {
	$current_base_url_display .= sprintf( '<li><strong>%s:</strong> <code>%s</code></li>',  esc_html( $url['name'] ), trailingslashit( $url['url'] ) );
}
$current_base_url_display .= '</ul>';

$current_edit_url_display = '<ul>';
foreach ( $edit_urls as $url ) {
	$current_edit_url_display .= sprintf( '<li><strong>%s:</strong> <code>%s</code></li>',  esc_html( $url['name'] ), trailingslashit( $url['url'] ) );
}
$current_edit_url_display .= '</ul>';

// Generate the rewrite fields with the box checked if applicable.
$rewrite_fields = [
	'rewrite-heading'                       => [
		'type'  => 'heading',
		'label' => __( 'Community URLs', 'tribe-events-community' ),
	],
	'rewrite-notice-unprettyPermalinks'     => [
		'type'        => 'wrapped_html',
		'label'       => esc_html__( 'Cannot be set', 'tribe-events-community' ),
		'html'        => '<p>'
			. _x(
				sprintf(
					'Community requires non-default (pretty) Permalinks to be enabled or the %1$s shortcode to exist on a post or page.<br><br>You cannot edit Community slugs for your events pages as you do not have pretty Permalinks enabled. In order to edit the slugs here, first <a href="%2$s">enable pretty Permalinks</a>.',
					'[tribe_community_events]',
					esc_url( trailingslashit( get_admin_url() ) . 'options-permalink.php' )
				),
				'Pretty permalinks error for URL slugs',
				'tribe-events-community'
			)
			. '</p>',
		'conditional' => ! $base_url,
	],
	'rewrite-notice'                        => [
		'type'            => 'wrapped_html',
		'html'            =>
			sprintf(
				'<p>%1$s<br><i>%2$s</i></p>',
				esc_html__(
					'Edit the default URLs for Community Pages.',
					'tribe-events-community'
				),
				esc_html__(
					'Note that these slugs are not translatable. Please write them in the correct language for your site.',
					'tribe-events-community'
				)
			),
		'size'            => 'medium',
		'validation_type' => 'html',
		'conditional'     => $base_url,
	],
	'current_urls'                          => [
		'type'            => 'wrapped_html',
		'html'            => sprintf(
			'<h4>%1$s:</h4>
			%2$s
			<p><strong>%3$s</strong></p>
			%4$s
			<p><input type="checkbox" class="tribe-accordion" id="events-community-rewrite-slugs-toggle" %5$s>
			<label for="events-community-rewrite-slugs-toggle">%6$s</label></p>
			',
			esc_html__( 'Current Community URLs', 'tribe-events-community' ),
			$current_base_url_display,
			esc_html__( 'The following are always appended by an event (or post) ID', 'tribe-events-community' ),
			$current_edit_url_display,
			$checked,
			esc_html__( 'Edit URL Slugs (unchecked clears all customizations)', 'tribe-events-community' )
		),
		'size'            => 'medium',
		'validation_type' => 'html',
		'conditional'     => $base_url,
	],
	'community-rewrite-slug-settings-start' => [
		'html' => '<div id="tribe-events-community-tickets-rewrite-slug-settings" class="tribe-dependent" data-depends="#events-community-rewrite-slugs-toggle" data-condition-is-checked>',
	],
];

$rewrite_fields += $community_rewrite_slug_settings;

$rewrite_fields['community-rewrite-slug-settings-end'] = [
	'html' => '</div>',
];

foreach ( $rewrite_fields as $name => $setting ) {
	// If it doesn't have a type, assume it is html.
	if ( empty( $setting['type'] ) ) {
		$setting['type'] = 'html';
	}

	// Skip non-field "settings".
	if ( in_array( $setting['type'], [ 'html', 'wrapped_html', 'heading' ], true ) ) {
		continue;
	}

	$setting['class']        = 'light-bordered full-width';
	$setting['can_be_empty'] = true;

	$existing_field_attributes = Tribe__Utils__Array::get( $setting, 'fieldset_attributes', [] );

	$additional_attributes = [
		'data-depends'              => '#events-community-rewrite-slugs-toggle',
		'data-condition-is-checked' => '',
	];

	$setting['fieldset_attributes'] = array_merge( $existing_field_attributes, $additional_attributes );

	$setting['validate_if'] = new Tribe__Field_Conditional( 'events-community-rewrite-slugs-toggle', 'tribe_is_truthy' );

	$rewrite_fields[ $name ] = $setting;
}

$alert_fields = [
	'alerts-heading'     => [
		'type'  => 'heading',
		'label' => __( 'Alerts', 'tribe-events-community' ),
	],
	'emailAlertsEnabled' => [
		'type'            => 'checkbox_bool',
		'label'           => __( 'Send an email alert when a new event is submitted', 'tribe-events-community' ),
		'default'         => false,
		'validation_type' => 'boolean',
	],
	'emailAlertsList'    => [
		'type'            => 'textarea',
		'label'           => __( 'Email addresses to be notified', 'tribe-events-community' ),
		'default'         => get_option( 'admin_email' ),
		'tooltip'         => __( 'One address per line', 'tribe-events-community' ),
		'validation_type' => 'textarea',
	],
];

$member_fields = [
	'member-heading'                => [
		'type'  => 'heading',
		'label' => __( 'Members', 'tribe-events-community' ),
	],
	'member-info'                   => [
		'html' =>
			sprintf(
				'<p>%s</p>',
				__( 'Control the permissions for your logged in users. Allow them to:', 'tribe-events-community' )
			),
	],
	'allowUsersToEditSubmissions'   => [
		'type'            => 'checkbox_bool',
		'label'           => __( 'Edit their submissions', 'tribe-events-community' ),
		'tooltip'         => __( 'Users can edit their events, venues, and organizers', 'tribe-events-community' ),
		'default'         => false,
		'validation_type' => 'boolean',
	],
	'allowUsersToDeleteSubmissions' => [
		'type'            => 'checkbox_bool',
		'label'           => __( 'Remove their submissions', 'tribe-events-community' ),
		'tooltip'         => __( 'Users can delete their events', 'tribe-events-community' ),
		'default'         => false,
		'validation_type' => 'boolean',
	],
	'trashItemsVsDelete'            => [
		'type'            => 'radio',
		'label'           => __( 'Deleted events should be:', 'tribe-events-community' ),
		'options'         => $trash_vs_delete_options,
		'default'         => '1',
		'validation_type' => 'options',
	],
];

$my_events_fields = [
	'myevents-heading' => [
		'type'  => 'heading',
		'label' => __( 'My Events', 'tribe-events-community' ),
	],
	'eventsPerPage'    => [
		'type'            => 'text',
		'label'           => __( 'Events per page', 'tribe-events-community' ),
		'tooltip'         => __( 'This is the number of events displayed per page', 'tribe-events-community' ),
		'size'            => 'small',
		'default'         => 10,
		'validation_type' => 'positive_int',
	],
];

$access_control_fields = [
	'access-heading'                        => [
		'type'  => 'heading',
		'label' => __( 'Access Control', 'tribe-events-community' ),
	],
	'blockRolesFromAdmin'                   => [
		'type'            => 'checkbox_bool',
		'label'           => __( 'Block access to WordPress dashboard', 'tribe-events-community' ),
		'tooltip'         => __( 'Also disables the admin bar', 'tribe-events-community' ),
		'default'         => false,
		'validation_type' => 'boolean',
	],
	'blockRolesList'                        => [
		'type'            => 'checkbox_list',
		'label'           => __( 'Roles to block', 'tribe-events-community' ),
		'default'         => [],
		'options'         => $redirectRoles,
		'validation_type' => 'options_multi',
		'tooltip'         => __( 'Check any roles listed to block access to the dashboard.', 'tribe-events-community' ),
		'can_be_empty'    => true,
	],
	'blockRolesRedirect'                    => [
		'type'            => 'text',
		'label'           => __( 'Redirect URL', 'tribe-events-community' ),
		'tooltip'         => sprintf(
			__( 'Redirect for users attempting to access the admin without permissions<br>Enter an absolute or relative URL<br>Leave blank for the %1$sCommunity List View%2$s', 'tribe-events-community' ),
			'<a href="' . esc_url( $tce->getUrl( 'list' ) ) . '">',
			'</a>'
		),
		'default'         => '',
		'placeholder'     => $tce->getUrl( 'list' ),
		'validation_type' => 'url',
		'can_be_empty'    => true,
	],
	'single_geography_mode'                 => [
		'type'            => 'checkbox_bool',
		'label'           => __( 'Single geography mode', 'tribe-events-community' ),
		'tooltip'         => __( 'Removes the country, state/province and timezone selectors from the submission form', 'tribe-events-community' ),
		'default'         => false,
		'validation_type' => 'boolean',
	],
	'tribe_community_events_wrapper_closer' => [
		'html' => '</div>',
	],
];

$fields = array_merge(
	$info_fields,
	$general_fields,
	$terms_fields,
	$rewrite_fields,
	$alert_fields,
	$member_fields,
	$my_events_fields,
	$access_control_fields
);

// Rinse and repeat params
foreach ( $fields as $name => $setting ) {
	// If it doesn't have a type, assume it is html.
	if ( empty( $setting['type'] ) ) {
		$setting['type'] = 'html';
	}

	// Skip non-field "settings".
	if ( in_array( $setting['type'], [ 'html', 'wrapped_html', 'heading' ], true ) ) {
		continue;
	}

	// Set parent option for alltheinputs!
	$setting['parent_option'] = Tribe__Events__Community__Main::OPTIONNAME;

	$fields[ $name ] = $setting;
}



$communityTab = [
	'priority' => 36,
	'fields'   => $fields,
];


/**
 * Allow for customization of the array of out-of-the-box Community settings.
 * To add additional fields, append them to the 'fields' array.
 *
 * @since 1.0
 *
 * @param array $communityTab
 */
$communityTab = apply_filters( 'tribe_community_settings_tab', $communityTab );
