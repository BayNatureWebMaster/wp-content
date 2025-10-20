<?php
/**
 * Handles registering Providers for the TEC\Events_Community\Custom_Tables\V1 (RBE) namespace.
 *
 * @since   4.10.0
 *
 * @package TEC\Events_Community\Custom_Tables\V1;
 */

namespace TEC\Events_Community\Custom_Tables\V1;

use TEC\Common\Contracts\Service_Provider;
use TEC\Events\Custom_Tables\V1\Models\Occurrence;
use TEC\Events\Custom_Tables\V1\Provider as TEC_Provider;
use TEC\Events\Custom_Tables\V1\Migration\State;
use TEC\Events_Pro\Custom_Tables\V1\Editors\Provider as ECP_Editor_Provider;
/**
 * Class Provider.
 *
 * @since   4.10.0
 *
 * @package TEC\Events_Community\Custom_Tables\V1;
 */
class Provider extends Service_Provider {
	/**
	 * @var bool
	 */
	protected $has_registered = false;

	/**
	 * Registers any dependent providers.
	 *
	 * @since 4.10.0
	 * @since 5.0.9 Implemented action `tec_events_community_shortcode_assets`.
	 *
	 * @return bool Whether the Event-wide maintenance mode was activated or not.
	 */
	public function register() {
		if ( $this->has_registered ) {
			return false;
		}

		if ( ! class_exists( TEC_Provider::class ) || ! class_exists( State::class ) ) {
			return false;
		}

		if ( ! TEC_Provider::is_active() ) {
			return false;
		}

		if ( ! defined( 'TEC_EC_CUSTOM_TABLES_V1_ROOT' ) ) {
			define( 'TEC_EC_CUSTOM_TABLES_V1_ROOT', __DIR__ );
		}

		$state = tribe( State::class );

		if ( $state->should_lock_for_maintenance() ) {
			$this->container->register( Migration\Maintenance_Mode\Provider::class );
		}

		if ( class_exists(  'Tribe__Events__Pro__Main'  ) ) {
			$this->load_ecp_assets();

			add_filter( 'tec_events_community_event_form_post_id', [ $this, 'normalize_post_id' ] );

			// Hook into the ECP CT1 provider registration to set up shortcode assets.
			add_action( 'tec_events_pro_custom_tables_v1_editors_provider_registered', [ $this, 'setup_shortcode_assets' ] );
		}

		$this->has_registered = true;

		return true;
	}

	/**
	 * Sets up shortcode asset enqueuing when the ECP CT1 provider is registered.
	 *
	 * This method hooks into the `tec_events_pro_custom_tables_v1_editors_provider_registered` action
	 * to ensure the Events Pro Custom Tables V1 provider is fully registered before setting up
	 * our shortcode asset enqueuing logic.
	 *
	 * @since 5.0.9
	 *
	 * @return void
	 */
	public function setup_shortcode_assets() {
		add_action( 'tec_events_community_shortcode_assets', [ $this, 'enqueue_ct1_shortcode_assets' ] );
	}

	/**
	 * Enqueues the Classic Editor Custom Tables v1 asset group for shortcode-based Community Events pages.
	 *
	 * This method should be hooked to the `tec_events_community_shortcode_assets` action
	 * to load the necessary assets when the Community Events form is rendered via shortcode.
	 *
	 * @since 5.0.9
	 *
	 * @return void
	 */
	public function enqueue_ct1_shortcode_assets() {
		$group_name = ECP_Editor_Provider::$classic_event_min_group_key;

		if ( ! empty( $group_name ) ) {
			tribe_asset_enqueue_group( $group_name );
		}
	}

	/**
	 * Loads ECP CT1 assets if the request is to edit an Event.
	 *
	 * @since 4.10.0
	 *
	 * @return void The method does not return anything and will have the side effect of loading the assets,
	 *              if required.
	 */
	private function load_ecp_assets(): void {
		$assets = new Assets;
		$this->container->singleton( Assets::class, $assets );

		/*
		 * We need to run this check very early, at `plugins_loaded` time to be able to prevent the
		 * Blocks Editor code from loading. If we check later, a number of Classic Editor facilities
		 * required by the Custom Tables v1 UI will not be available if the user is using the Blocks
		 * Editor to edit Events.
		 */
		if ( ! $assets->is_edit_route() ) {
			return;
		}

		if ( is_admin() || ! tribe_context()->doing_php_initial_state() ) {
			return;
		}

		// Never use the blocks editor when looking at a plugin edit page.
		add_filter( 'tribe_editor_should_load_blocks', '__return_false' );

		// Immediately set up to load ECP CT1 assets.
		$assets->enqueue_ecp_assets();
	}

	/**
	 * Normalizes the post ID the Event Form is being rendered for to always redirect it
	 * to a real post ID, if provisional.
	 *
	 * @since 4.10.5
	 *
	 * @param int|null $post_id The post ID the Event Form is being rendered for, if any.
	 *
	 * @return int|null A real post ID, if the post ID was a provisional one, `null` otherwise.
	 */
	public function normalize_post_id( ?int $post_id = null ): ?int {
		return $post_id ? Occurrence::normalize_id( $post_id ) : $post_id;
	}
}
