<?php

use TEC\Common\Contracts\Service_Provider;
/**
 * Class Tribe__Events__Community__Service_Provider
 *
 * Provides the Community service.
 *
 * This class should handle implementation binding, builder functions and hooking for any first-level hook and be
 * devoid of business logic.
 *
 * @since 4.6.2
 */
class Tribe__Events__Community__Service_Provider extends Service_Provider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 4.6.2
	 */
	public function register() {
		$this->container->singleton( 'community.integrations', 'Tribe__Events__Community__Integrations__Manager' );
		$this->container->singleton( 'community.integrations.divi', 'Tribe__Events__Community__Integrations__Divi', [ 'hooks' ] );
		$this->container->singleton( 'community.integrations.virtual-events', 'Tribe__Events__Community__Integrations__Virtual_Events', [ 'hooks' ] );
		$this->container->singleton( 'community.shortcodes', 'Tribe__Events__Community__Shortcodes' );
		$this->container->singleton( Tribe\Events\Community\Integrations\Event_Status::class, Tribe\Events\Community\Integrations\Event_Status::class, [ 'hooks' ] );
		$this->container->singleton( Tribe\Events\Community\Integrations\Series::class, Tribe\Events\Community\Integrations\Series::class, [ 'hooks' ] );

		// Register the Views v2 service provider.
		$this->container->register( Tribe\Events\Community\Views\V2\Service_Provider::class );

		$this->hook();
	}

	/**
	 * Any hooking for any class needs happen here.
	 *
	 * In place of delegating the hooking responsibility to the single classes they are all hooked here.
	 *
	 * @since 4.6.2
	 */
	protected function hook() {

		add_action( 'init', tribe_callback( 'community.shortcodes', 'hooks' ) );

	}
}
