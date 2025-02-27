<?php

namespace TEC\Events_Community\Integrations\Plugins\Events;

use TEC\Common\Integrations\Traits\Plugin_Integration;
use TEC\Events_Community\Integrations\Plugin_Integration_Abstract;

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
	 */
	protected function add_actions(): void {
	}

	/**
	 * Adds the filters required for the Settings Page.
	 *
	 * @since 5.0.0
	 */
	protected function add_filters(): void {
	}
}
