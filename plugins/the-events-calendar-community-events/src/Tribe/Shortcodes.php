<?php
// Don't load directly
use TEC\Events_Community\Callbacks\Event\Callback_Add_Edit;
use TEC\Events_Community\Callbacks\Listing\Callback_Listing;
use TEC\Events_Community\Integrations\Plugins\Events\Organizers\Route_Callbacks\Callback_Edit as Organizer_Callback;
use TEC\Events_Community\Integrations\Plugins\Events\Venues\Route_Callbacks\Callback_Edit as Venue_Callback;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Setup the Community Shortcodes
 *
 * @since 4.6.2
 */
class Tribe__Events__Community__Shortcodes extends Tribe__Events__Community__Shortcode__Abstract {

	/**
	 * Add a hook for the tribe_community_events shortcode tag
	 *
	 * @since 4.6.2
	 */
	public function hooks() {
		add_shortcode( 'tribe_community_events', [ $this, 'do_shortcode' ] );
		add_action( 'tribe_events_community_section_after_submit', [ $this, 'set_shortcode_type_input' ] );
	}

	/**
	 * Enqueue the scripts and stylesheets for Community Shortcodes
	 *
	 * @since 4.6.2
	 */
	public function enqueue_assets() {
		/** @var Tribe__Events__Community__Main $community */
		$community = tribe( 'community.main' );

		$community->maybeLoadAssets( true );
		$community->enqueue_assets();

		tribe_asset_enqueue( 'tribe-events-community-shortcodes' );
	}

	/**
	 * Display the Community Shortcodes
	 *
	 * To display the CE Submission Form use:
	 * [tribe_community_events] or [tribe_community_events view="submission_form"]
	 *
	 * To display the events created by current user use:
	 * [tribe_community_events view="my_events"]
	 *
	 * To display the edit forms use:
	 * [tribe_community_events view="edit_event" id="your_event_id"]
	 * [tribe_community_events view="edit_venue" id="your_venue_id"]
	 * [tribe_community_events view="edit_organizer" id="your_organizer_id"]
	 *
	 * To modify the Add New button on the event list
	 * [tribe_community_events new_event_url="url where you have entered the CE Submission Form Shortcode"]
	 *
	 * To modify the Add New button on the event list
	 * [tribe_community_events event_list_url="url where you have entered the CE Event List Shortcode"]
	 *
	 * @since 4.10.5 Add additional logic when ECP is enabled to load the CT1 assets.
	 * @since 4.6.2
	 * @since 4.7.0 Use the callbacks for each switch/case.
	 *
	 * @param array  $attributes
	 * @param string $tag the name of the shortcode
	 *
	 * @return mixed
	 */
	public function do_shortcode( $attributes = [], $tag = 'tribe_community_events' ) {
		$this->enqueue_assets();

		/** @var Tribe__Events__Community__Main $community */
		$community = tribe( 'community.main' );
		// Normalize attribute keys, lowercase
		$attributes = array_change_key_case( (array) $attributes, CASE_LOWER );
		$tribe_id   = $this->check_id( $attributes );
		$tribe_view = array_key_exists( 'view', $attributes ) ? $attributes['view'] : 'submission_form';

		// Override default attributes with user attributes
		$tribe_attributes = shortcode_atts(
			[
				'view'           => $tribe_view,
				'id'             => $tribe_id,
				'new_event_url'  => '',
				'event_list_url' => '',
			],
			$attributes,
			$tag
		);

		// Set current view.
		$this->type = $tribe_attributes['view'];

		// This validation is necessary for the pagination to work properly with
		// [tribe_community_events view="my_events"] shortcode.
		$page_number = absint( basename( $_SERVER['REQUEST_URI'] ) );
		if ( $page_number === 0 ) {
			$page_number = 1;
		}

		// set new event form url
		$this->new_event_url = esc_url( $tribe_attributes['new_event_url'] );
		if ( ! empty ( $this->new_event_url ) ) {
			add_filter( 'tribe-community-events-add-event-link', [ $this, 'new_event_form_url' ], 20 );
		}

		// set event list url
		$this->event_list_url = esc_url( $tribe_attributes['event_list_url'] );
		if ( ! empty ( $this->event_list_url ) ) {
			add_filter( 'tribe-community-events-list-events-link', [ $this, 'listings_url' ], 20 );
		}

		/**
		 * Provides an opportunity to enqueue additional scripts before rendering the shortcodes
		 *
		 * @since 4.6.2
		 */
		do_action( 'tribe_events_community_before_shortcode' );

		/** @var Tribe__Events__Community__Main $main */
		$main = tribe( 'community.main' );

		switch ( $tribe_attributes['view'] ) {
			case 'submission_form':

				$add = tribe( Callback_Add_Edit::class );
				$add->setup( [ 'event_id' => null ] );
				$view = $add->callback();
				break;
			case 'my_events':
				add_filter(
					'tribe_events_community_shortcode_nav_link',
					[
						tribe( 'community.shortcodes' ),
						'custom_nav_link',
					]
				);
				$listing = tribe( Callback_Listing::class );
				$listing->setup( [ 'listPage' => $page_number, 'print_before_after_override' => 'false', 'shortcode' => true ] );
				$view =  $listing->callback();
				break;
			case 'edit_event':
				if ( $tribe_id === false ) {
					$view = esc_html__( 'Community Shortcode error: The view you specified doesn\'t exist or the Event ID is invalid', 'tribe-events-community' );
				} else {

					$add = tribe( Callback_Add_Edit::class );
					$add->setup( [ 'event_id' => $tribe_id ] );
					$view = $add->callback();

				}
				break;
			case 'edit_venue':
				if ( $tribe_id === false ) {
					$view = esc_html__( 'Community Shortcode error: The view you specified doesn\'t exist or the Venue ID is invalid', 'tribe-events-community' );
				} else {
					$venue_edit = tribe( Venue_Callback::class );

					$venue_edit->setup( [ 'venue_id' => $tribe_id ] );
					$view =  $venue_edit->callback();
				}
				break;
			case 'edit_organizer':
				if ( $tribe_id === false ) {
					$view = esc_html__( 'Community Shortcode error: The view you specified doesn\'t exist or the Organizer ID is invalid', 'tribe-events-community' );
				} else {
					$organizer_edit = tribe( Organizer_Callback::class );

					$organizer_edit->setup( [ 'organizer_id' => $tribe_id ] );

					$view =  $organizer_edit->callback();
				}
				break;
			default:
				$view = esc_html__( 'Community Shortcode error: Please specify which form you want to display', 'tribe-events-community' );
		}

		$display = "<div id='tribe-community-events-shortcode' style='visibility:hidden;'>$view</div>";
		$display .= '<script>setTimeout(function(){document.getElementById("tribe-community-events-shortcode").style.visibility = "visible";},400);</script>';

		return $display;
	}

	/**
	 * Filter the shortcode nav links
	 *
	 * @since 4.6.2
	 *
	 * @return string The permalink
	 */
	public function custom_nav_link() {
		return get_permalink();
	}

	/**
	 * Filter the add event form url with the shortcode attribute
	 *
	 * @since 4.6.3
	 *
	 * @return string The permalink
	 */
	public function new_event_form_url() {
		return $this->new_event_url;
	}

	/**
	 * Filter the listings url with the shortcode attribute
	 *
	 * @since 4.6.3
	 *
	 * @return string The permalink
	 */
	public function listings_url() {
		return $this->event_list_url;
	}

	/**
	 * If ECP is enabled, load the assets required for CT1 to work on shortcodes.
	 *
	 * @since 4.10.5
	 *
	 * @return void
	 */
	public function load_ecp_assets() {
		if ( class_exists( 'Tribe__Events__Pro__Main' ) ) {
			Tribe( \TEC\Events_Community\Custom_Tables\V1\Assets::class )->enqueue_asset_group();
		}
	}
}
