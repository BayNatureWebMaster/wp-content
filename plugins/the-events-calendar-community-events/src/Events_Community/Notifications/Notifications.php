<?php
/**
 * Class that handles interfacing with TEC\Common\Notifications.
 *
 * @since 5.0.8
 *
 * @package TEC\Events_Community\Notifications
 */

namespace TEC\Events_Community\Notifications;

use TEC\Events_Community\Integrations\Integration_Abstract;
use TEC\Common\Integrations\Traits\Plugin_Integration;
use Tribe__Events__Community__Main;

/**
 * Class Notifications
 *
 * @since 5.0.8
 * @package TEC\Events_Community\Notifications
 */
class Notifications extends Integration_Abstract {
	use Plugin_Integration;

	/**
	 * The slug of this plugin calling the Notifications class.
	 *
	 * @since 5.0.8
	 *
	 * @return string
	 */
	public static function get_slug(): string {
		return 'events-community';
	}

	/**
	 * @inheritDoc
	 */
	public function load_conditionals(): bool {
		return has_action( 'tribe_common_loaded' );
	}

	/**
	 * @inheritDoc
	 */
	protected function load(): void {
		add_filter( 'tec_common_ian_allowed_pages', [ $this, 'add_allowed_pages' ] );
		add_filter( 'tec_common_ian_slugs', [ $this, 'add_ce_slug' ] );
		add_filter( 'tec_common_ian_filter_callback', [ $this, 'filter_callback' ], 10, 3 );
		add_filter( 'tec_common_ian_feed', [ $this, 'inject_ce_notifications' ], 10, 2 );

		add_action( 'admin_footer', [ $this, 'render_icon' ] );
	}

	/**
	 * Adds the Tickets settings page to the list of allowed pages for Notifications.
	 *
	 * @since 5.0.8
	 *
	 * @param array $allowed An array of pages where notifications will be displayed.
	 *
	 * @return array
	 */
	public function add_allowed_pages( $allowed ) {
		$parents = tribe( Tribe__Events__Community__Main::class )->get_parent_plugins();

		if ( $parents['tec'] ) {
			$allowed[] = 'tribe_events_page_tec-events-settings';
		}

		if ( $parents['et'] ) {
			$allowed[] = 'tickets_page_tec-tickets-settings';
			$allowed[] = 'tickets_page_tickets-setup';
		}

		return $allowed;
	}

	/**
	 * Adds CE to the list of supported plugin slugs for IAN.
	 *
	 * @since 5.0.8
	 *
	 * @param array $slugs The array of plugin slugs that support IAN.
	 *
	 * @return array
	 */
	public function add_ce_slug( $slugs ) {
		$slugs[] = 'events-community';
		return $slugs;
	}

	/**
	 * Custom filter callback for CE notifications.
	 * Includes notifications from general-tec, general-et, and general-ce areas.
	 *
	 * @since 5.0.8
	 *
	 * @param callable $callback The filtering callback function.
	 * @param array    $body     The full API response body.
	 * @param string   $slug     The plugin slug.
	 *
	 * @return callable
	 */
	public function filter_callback( $callback, $body, $slug ) {
		// Cache CE notifications from the full API response for injection into other feeds.
		// Do this for any plugin that fetches the feed, not just CE.
		$this->cache_ce_notifications( $body, $slug );

		// Only apply custom filtering for CE plugin.
		if ( 'events-community' !== $slug ) {
			return $callback;
		}

		return function( $body, $slug ) {
			$notifications = [];
			$seen_versions = [];

			// Get notifications from all three areas.
			$areas = [ 'general-tec', 'general-et', 'general-ce' ];

			foreach ( $areas as $area ) {
				if ( ! isset( $body['notifications_by_area'][ $area ] ) ) {
					continue;
				}

				foreach ( $body['notifications_by_area'][ $area ] as $notification ) {
					// Only include notifications that are for the CE plugin.
					if ( ! $this->is_ce_notification( $notification ) ) {
						continue;
					}

					// Check for duplicates based on condition version.
					$version_key = $this->get_version_key( $notification );
					if ( in_array( $version_key, $seen_versions, true ) ) {
						continue;
					}

					$notifications[] = $notification;
					$seen_versions[] = $version_key;
				}
			}

			return $notifications;
		};
	}

	/**
	 * Check if a notification is for the CE plugin.
	 *
	 * @since 5.0.8
	 *
	 * @param array $notification The notification data.
	 *
	 * @return bool
	 */
	public function is_ce_notification( $notification ) {
		// Check if the notification has conditions that include events-community.
		if ( isset( $notification['conditions'] ) && is_array( $notification['conditions'] ) ) {
			foreach ( $notification['conditions'] as $condition ) {
				if ( strpos( $condition, 'events-community' ) !== false ) {
					return true;
				}
			}
		}

		// Check if the notification slug indicates it's for CE.
		if ( isset( $notification['slug'] ) && strpos( $notification['slug'], 'ce-' ) === 0 ) {
			return true;
		}

		return false;
	}

	/**
	 * Get a unique version key for duplicate detection.
	 *
	 * @since 5.0.8
	 *
	 * @param array $notification The notification data.
	 *
	 * @return string
	 */
	public function get_version_key( $notification ) {
		$key_parts = [];

		// Include version information from conditions as primary fingerprint.
		if ( isset( $notification['conditions'] ) && is_array( $notification['conditions'] ) ) {
			foreach ( $notification['conditions'] as $condition ) {
				if ( strpos( $condition, 'events-community@' ) !== false ) {
					$key_parts[] = 'version:' . $condition;
					break;
				}
			}
		}

		// Include the slug as secondary identifier, but strip ID prefix for duplicate detection.
		if ( isset( $notification['slug'] ) ) {
			$slug = $notification['slug'];
			// Strip ID prefix (e.g., "49_ce-update-6-19-25a" becomes "ce-update-6-19-25a")
			if ( preg_match( '/^\d+_(.+)$/', $slug, $matches ) ) {
				$slug = $matches[1];
			}
			$key_parts[] = 'slug:' . $slug;
		}

		return implode( '|', $key_parts );
	}

	/**
	 * Outputs the hook that renders the Notifications icon on all TEC admin pages.
	 *
	 * @since 5.0.8
	 */
	public function render_icon() {
		// Don't double-dip on the action.
		if ( did_action( 'tec_ian_icon' ) ) {
			return;
		}

		/**
		 * Fires to trigger the IAN icon on admin pages.
		 *
		 * @since 5.0.8
		 */
		do_action( 'tec_ian_icon', $this->get_slug() );
	}

	/**
	 * Cache CE notifications from the full API response.
	 *
	 * @since 5.0.8
	 *
	 * @param array $body The full API response body.
	 * @param string $slug The plugin slug that triggered the cache.
	 */
	public function cache_ce_notifications( $body, $slug ) {
		// Cache CE notifications whenever any plugin fetches the feed.
		// This ensures CE notifications are always available for injection.

		$ce_notifications = [];

		// Extract CE notifications from all areas.
		if ( isset( $body['notifications_by_area'] ) ) {
			foreach ( $body['notifications_by_area'] as $area_notifications ) {
				foreach ( $area_notifications as $notification ) {
					if ( $this->is_ce_notification( $notification ) ) {
						$ce_notifications[] = $notification;
					}
				}
			}
		}

		// Cache the CE notifications.
		if ( ! empty( $ce_notifications ) ) {
			$cache = tribe_cache();
			$cache->set_transient( 'tec_ian_api_feed_events-community', $ce_notifications, 15 * MINUTE_IN_SECONDS );
		}
	}

	/**
	 * Inject CE notifications into TEC and ET feeds.
	 *
	 * @since 5.0.8
	 *
	 * @param array  $feed The filtered notifications feed.
	 * @param string $slug The plugin slug.
	 *
	 * @return array
	 */
	public function inject_ce_notifications( $feed, $slug ) {
		// Only inject into TEC and ET feeds.
		if ( ! in_array( $slug, [ 'the-events-calendar', 'event-tickets', 'et', 'tec' ], true ) ) {
			return $feed;
		}

		// Get CE notifications from the cache.
		$cache = tribe_cache();
		$ce_feed = $cache->get_transient( 'tec_ian_api_feed_events-community' );

		// If no CE notifications in cache, return the original feed.
		if ( false === $ce_feed || ! is_array( $ce_feed ) || empty( $ce_feed ) ) {
			return $feed;
		}

		$ce_notifications = [];
		$seen_versions = [];

		// Get existing notification versions to avoid duplicates.
		foreach ( $feed as $notification ) {
			$version_key = $this->get_version_key( $notification );
			$seen_versions[] = $version_key;
		}

		// Add CE notifications that aren't already in the feed.
		foreach ( $ce_feed as $notification ) {
			// Only include CE-specific notifications.
			if ( ! $this->is_ce_notification( $notification ) ) {
				continue;
			}

			$version_key = $this->get_version_key( $notification );
			if ( in_array( $version_key, $seen_versions, true ) ) {
				continue;
			}

			// Apply template formatting to the notification (since it missed the earlier processing)
			$template = new \TEC\Common\Notifications\Template();
			$notification['html'] = $template->render_notification( $notification, false );
			$notification['read'] = false; // Default to unread for injected notifications

			$ce_notifications[] = $notification;
			$seen_versions[] = $version_key;
		}

		// Merge CE notifications with the existing feed.
		return array_merge( $feed, $ce_notifications );
	}
}
