<?php
/**
 * Service Provider for interfacing with TEC\Common\Notifications.
 *
 * @since 5.0.8
 *
 * @package TEC\Events_Community\Notifications
 */

namespace TEC\Events_Community\Notifications;

use TEC\Common\Contracts\Service_Provider;

/**
 * Class Provider
 *
 * @since 5.0.8
 *
 * @package TEC\Events_Community\Notifications
 */
class Provider extends Service_Provider {

	/**
	 * Handles the registering of the provider.
	 *
	 * @since 5.0.8
	 */
	public function register() {
		$this->container->register_on_action( 'tec_common_ian_loaded', Notifications::class );
	}
}
