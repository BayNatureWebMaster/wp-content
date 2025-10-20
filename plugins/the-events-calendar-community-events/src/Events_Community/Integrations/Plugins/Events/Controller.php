<?php

namespace TEC\Events_Community\Integrations\Plugins\Events;

use TEC\Common\Integrations\Traits\Plugin_Integration;
use TEC\Events_Community\Integrations\Plugin_Integration_Abstract;
use Tribe__Events__Main;

/**
 * Class Provider
 *
 * @since   5.0.0
 *
 * @package TEC\Events_Community\Integrations\Plugins\The_Events_Calendar
 */
class Controller extends Plugin_Integration_Abstract {
	use Plugin_Integration;

	/**
	 * @inheritDoc
	 */
	public static function get_slug(): string {
		return 'the-events-calendar';
	}

	/**
	 * @inheritDoc
	 */
	public function load_conditionals(): bool {
		return did_action( 'tribe_events_bound_implementations' );
	}

	/**
	 * @inheritDoc
	 */
	protected function load(): void {
		$this->container->register( Settings\Controller::class );
		$this->container->register( Events\Controller::class );
		$this->container->register( Venues\Controller::class );
		$this->container->register( Organizers\Controller::class );
		$this->container->register( Anonymous_Users\Controller::class );
		// Register the Service Provider for Hooks.
		$this->register_hooks();
	}

	/**
	 * Registers the provider handling all the 1st level filters and actions for this Service Provider.
	 *
	 * @since 5.0.0
	 */
	protected function register_hooks(): void {
		$this->add_actions();
		$this->add_filters();
	}

	/**
	 * Adds the actions required for the Settings Page.
	 *
	 * @since 5.0.0
	 * @since 5.0.10 Added the `tec_events_community_fully_loaded` action.
	 */
	protected function add_actions(): void {
		add_action( 'tec_events_community_fully_loaded', [ $this, 'move_ce_settings_from_tec' ] );
	}

	/**
	 * Adds the filters required for the Settings Page.
	 *
	 * @since 5.0.0
	 */
	protected function add_filters(): void {
		add_filter( 'tec_events_community_settings_content_creation_section', [ $this, 'add_defaults_header' ], 10 );

		// Override event label functions with tribe_get_event_label_* functions.
		add_filter( 'tec_events_community_event_label_singular', 'tribe_get_event_label_singular' );
		add_filter( 'tec_events_community_event_label_plural', 'tribe_get_event_label_plural' );
		add_filter( 'tec_events_community_event_label_singular_lowercase', 'tribe_get_event_label_singular_lowercase' );
		add_filter( 'tec_events_community_event_label_plural_lowercase', 'tribe_get_event_label_plural_lowercase' );
	}

	/**
	 * Add header section for defaults.
	 *
	 * @since 5.0.4
	 * @since 5.0.10 Added a div container to the "Form Defaults" heading for style consistency.
	 *
	 * @param  array $fields The fields for the settings page.
	 *
	 * @return array
	 */
	public function add_defaults_header( array $fields ): array {
		$fields['tec-events-community-settings-defaults-heading'] = [
			'type'        => 'html',
			'html'        => '<div class="tec-settings-form__header-block tec-settings-form__element--rowspan-2">'
				. '<h3 id="tec-events-community-settings-defaults" class="tec-settings-form__section-header tec-settings-form__section-header--sub">'
				. esc_html__( 'Form Defaults', 'tribe-events-community' )
				. '</h3>'
				. '</div>',
			'conditional' => Tribe__Events__Main::instance()->get_venue_info() || Tribe__Events__Main::instance()->get_organizer_info(),
		];

		return $fields;
	}

	/**
	 * Move Community Events settings from The Events Calendar to Community Events.
	 *
	 * @since 5.0.10
	 *
	 * @return void
	 */
	public function move_ce_settings_from_tec() {
		if ( ! is_admin() ) {
			return;
		}

		$settings = [
			'prevent_new_venues',
			'prevent_new_organizers',
			'defaultCommunityVenueID',
			'defaultCommunityOrganizerID',
		];

		$ce_main = tribe( 'community.main' );

		foreach ( $settings as $setting ) {
			$tec_value = tribe_get_option( $setting, 'does_not_exist' );

			// Bail if the setting does not exist in TEC.
			if ( 'does_not_exist' === $tec_value ) {
				return;
			}

			$ce_value = $ce_main->getOption( $setting, 'does_not_exist' );

			// Migrate setting from TEC to CE.
			if ( 'does_not_exist' === $ce_value ) {
				$ce_main->setOption( $setting, $tec_value );
			}

			// Delete setting from TEC.
			tribe_remove_option( $setting );
		}
	}
}
