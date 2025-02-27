<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//set_site_transient('update_plugins',null);

/**
 * Allows plugins to use their own update API.
 *
 */
class Sellcodes_Plugin_Updater {

	private $api_url     = '';
	private $api_data    = array();
	private $name        = '';
	private $slug        = '';
	private $version     = '';
	private $wp_override = false;

	/**
	 * Class constructor.
	 *
	 * @uses plugin_basename()
	 * @uses hook()
	 *
	 * @param string  $_api_url     The URL pointing to the custom API endpoint.
	 * @param string  $_plugin_file Path to the plugin file.
	 * @param array   $_api_data    Optional data to send with API calls.
	 */
	public function __construct( $_api_url, $_plugin_file, $_api_data = null ) {

        global $sc_usm_plugin_data;

		$this->api_url     = trailingslashit( $_api_url );
		$this->api_data    = $_api_data;
		$this->name        = plugin_basename( $_plugin_file );
		$this->slug        = basename( $_plugin_file, '.php' );
		$this->version     = $_api_data['version'];
		$this->wp_override = isset( $_api_data['wp_override'] ) ? (bool) $_api_data['wp_override'] : false;

        $sc_usm_plugin_data[ $this->slug ] = $this->api_data;

		// Set up hooks.
		$this->init();
	}

	/**
	 * Set up WordPress filters to hook into WP's update process.
	 *
	 * @uses add_filter()
	 *
	 * @return void
	 */
	public function init() {

		$license = trim( get_option( SELLCODES_LICENSING.'_license_key' ));

		if(!empty($license))
		{
			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
			add_filter( 'plugins_api', array( $this, 'plugins_api_filter' ), 10, 3 );
			remove_action( 'after_plugin_row_' . $this->name, 'wp_plugin_update_row', 10 );
			add_action( 'after_plugin_row_' . $this->name, array( $this, 'show_update_notification' ), 10, 2 );
			add_action( 'admin_init', array( $this, 'show_changelog' ) );
		}
	}

	/**
	 * Check for Updates at the defined API endpoint and modify the update array.
	 *
	 * This function dives into the update API just when WordPress creates its update array,
	 * then adds a custom API call and injects the custom plugin data retrieved from the API.
	 * It is reassembled from parts of the native WordPress plugin update code.
	 * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
	 *
	 * @uses api_request()
	 *
	 * @param array   $_transient_data Update array build by WordPress.
	 * @return array Modified update array with custom plugin data.
	 */
	public function check_update( $_transient_data ) {
		global $pagenow;

		if ( ! is_object( $_transient_data ) ) {
			$_transient_data = new stdClass;
		}

		if ( 'plugins.php' == $pagenow && is_multisite() ) {
			return $_transient_data;
		}

		if ( ! empty( $_transient_data->response ) && ! empty( $_transient_data->response[ $this->name ] ) && false === $this->wp_override ) {
			return $_transient_data;
		}

		$version_info = $this->api_request( 'plugin_latest_version', array( 'slug' => $this->slug ) );

		if ( false !== $version_info && is_object( $version_info ) && isset( $version_info->new_version ) ) {

			if ( version_compare( $this->version, $version_info->new_version, '<' ) ) {
				$_transient_data->response[ $this->name ] = $version_info;
			}

			$_transient_data->last_checked           = current_time( 'timestamp' );
			$_transient_data->checked[ $this->name ] = $this->version;

		}
		
		return $_transient_data;
	}

	/**
	 * show update nofication row -- needed for multisite subsites, because WP won't tell you otherwise,
	 *
	 * @param string  $file
	 * @param array   $plugin
	 */
	public function show_update_notification( $file, $plugin ) {
		
		if ( is_network_admin() ) {
			return;
		}

		if( ! current_user_can( 'update_plugins' ) ) {
			return;
		}
		
		if( ! is_multisite() ) {
			return;
		}
		
		if ( $this->name != $file ) {
			return;
		}

		// Remove our filter on the site transient
		remove_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ), 10 );

		$update_cache = get_site_transient( 'update_plugins' );

		$update_cache = is_object( $update_cache ) ? $update_cache : new stdClass();

		if ( empty( $update_cache->response ) || empty( $update_cache->response[ $this->name ] ) ) {

			$cache_key    = md5( 'sc_usm_plugin_'.sanitize_key( $this->name ).'_version_info');
			$version_info = get_transient( $cache_key );

			if( false === $version_info ) {

				$version_info = $this->api_request( 'plugin_latest_version', array( 'slug' => $this->slug ) );

				set_transient( $cache_key, $version_info, 3600 );
			}

			if( ! is_object( $version_info ) ) {
				return;
			}

			if( version_compare( $this->version, $version_info->new_version, '<' ) ) {
				$update_cache->response[ $this->name ] = $version_info;
			}

			$update_cache->last_checked = current_time( 'timestamp' );
			$update_cache->checked[ $this->name ] = $this->version;

			set_site_transient( 'update_plugins', $update_cache );
		}
		else {
			$version_info = $update_cache->response[ $this->name ];
		}
		
		// Restore our filter
		add_filter('pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );

		if ( ! empty( $update_cache->response[ $this->name ] ) && version_compare( $this->version, $version_info->new_version, '<' ) ) {

			// build a plugin list row, with update notification
			$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
			
			# <tr class="plugin-update-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange">
			echo '<tr class="plugin-update-tr" id="'.$this->slug.'-update" data-slug="'.$this->slug.'" data-plugin="'.$this->slug.'/' . $file . '">';
			echo '<td colspan="3" class="plugin-update colspanchange">';
			echo '<div class="update-message notice inline notice-warning notice-alt">';

			$changelog_link = self_admin_url( 'index.php?sc_usm_action=view_plugin_changelog&plugin=' . $this->name . '&slug=' . $this->slug . '&TB_iframe=true&width=772&height=911' );

			if ( empty( $version_info->download_link ) ) {
				printf(
					__( 'There is a new version of %1$s available. %2$sView version %3$s details%4$s.', 'ultimate-social-media-plus' ),
					esc_html( $version_info->name ),
					'<a target="_blank" class="thickbox" href="' . esc_url( $changelog_link ) . '">',
					esc_html( $version_info->new_version ),
					'</a>'
				);
			} else {
				printf(
					__( 'There is a new version of %1$s available. %2$sView version %3$s details%4$s or %5$supdate now%6$s.', 'ultimate-social-media-plus' ),
					esc_html( $version_info->name ),
					'<a target="_blank" class="thickbox" href="' . esc_url( $changelog_link ) . '">',
					esc_html( $version_info->new_version ),
					'</a>',
					'<a href="' . esc_url( wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $this->name, 'upgrade-plugin_' . $this->name ) ) .'">',
					'</a>'
				);
			}

			do_action( "in_plugin_update_message-{$file}", $plugin, $version_info );

			echo '</div></td></tr>';
		}
	}

	/**
	 * Updates information on the "View version x.x details" page with custom data.
	 *
	 * @uses api_request()
	 *
	 * @param mixed   $_data
	 * @param string  $_action
	 * @param object  $_args
	 * @return object $_data
	 */
	public function plugins_api_filter( $_data, $_action = '', $_args = null ) {


		if ( $_action != 'plugin_information' ) {

			return $_data;

		}

		if ( ! isset( $_args->slug ) || ( $_args->slug != $this->slug ) ) {

			return $_data;

		}

		$to_send = array(
			'slug'   => $this->slug,
			'is_ssl' => is_ssl(),
			'fields' => array(
				'banners' => array(),
				'reviews' => false
			)
		);

		$cache_key = 'sc_usm_api_request_' . substr( md5( serialize( $this->slug ) ), 0, 15 );

		//Get the transient where we store the api request for this plugin for 24 hours
		$sc_usm_api_request_transient = get_site_transient( $cache_key );


		//If we have no transient-saved value, run the API, set a fresh transient with the API value, and return that value too right now.
		if ( empty( $sc_usm_api_request_transient ) ){

			$api_response = $this->api_request( 'plugin_information', $to_send );

			//Expires in 1 day
			set_site_transient( $cache_key, $api_response, DAY_IN_SECONDS );

			if ( false !== $api_response ) {
				$_data = $api_response;
			}
		}
		else{
			$_data = $sc_usm_api_request_transient;
		}

		$api_response = $this->api_request( 'plugin_information', $to_send );
		
		$_data = $api_response;

		if(isset($_data->sections['description'])){
			$_data->sections['description'] = wpautop($_data->sections['description']);
		}

        if(isset($_data->sections['changelog'])){
			$_data->sections['changelog'] = wpautop($_data->sections['changelog']);
		}

        if(isset($_data->sections['frequently_asked_questions'])){
			$_data->sections['frequently_asked_questions'] = wpautop($_data->sections['frequently_asked_questions']);
		}

		if(isset($_data->sections['screenshots'])){
			unset($_data->sections['screenshots']);
		}

		if(isset($_data->homepage)){
			unset($_data->homepage);
		}		

		if(isset($_data->donate_link)){
			unset($_data->donate_link);
		}		
		
		return $_data;
	}

	/**
	 * Disable SSL verification in order to prevent download update failures
	 *
	 * @param array   $args
	 * @param string  $url
	 * @return object $array
	 */
	public function http_request_args( $args, $url ) {
		// If it is an https request and we are performing a package download, disable ssl verification
		if ( strpos( $url, 'https://' ) !== false && strpos( $url, 'edd_action=package_download' ) ) {
			$args['sslverify'] = false;
		}
		return $args;
	}

	/**
	 * Calls the API and, if successfull, returns the object delivered by the API.
	 *
	 * @uses get_bloginfo()
	 * @uses wp_remote_post()
	 * @uses is_wp_error()
	 *
	 * @param string  $_action The requested action.
	 * @param array   $_data   Parameters for the API action.
	 * @return false|object
	 */
	private function api_request( $_action, $_data ) {

		global $wp_version;

		$data = array_merge( $this->api_data, $_data );

		if ( $data['slug'] != $this->slug ) {
			return;
		}

		$request = false;

		if(false != Sellcodes_License_API_Manager::check_license()){
			
			$update_api_url = $this->api_url."get_version";

			$api_params = array(
				'license_key' => ! empty( $data['license'] ) ? $data['license'] : '',
				'product_id'  => isset( $data['item_name'] ) ? $data['item_name'] : false,
				'baseurl'     => home_url(),
				'slug'        => $this->slug
			);

			$request  = wp_remote_post( $update_api_url, array( 'timeout' => 30, 'sslverify' => false, 'body' => $api_params ) );
			$httpCode = wp_remote_retrieve_response_code( $request );

			if ( 500 == $httpCode || 0 == $httpCode )
				return false;

			$request = json_decode( str_replace("\xEF\xBB\xBF",'',wp_remote_retrieve_body($request)));

			if(isset($request->success) && false == $request->success){
				return false;			
			}

			if ( $request && isset( $request->sections ) ) {
				$request->sections = maybe_unserialize( $request->sections );
			}
		}

		
		return $request;
	}

	public function show_changelog() {

		global $sc_usm_plugin_data;

		if( empty( $_REQUEST['sc_usm_action'] ) || 'view_plugin_changelog' != $_REQUEST['sc_usm_action'] ) {
			return;
		}

		if( empty( $_REQUEST['plugin'] ) ) {
			return;
		}

		if( empty( $_REQUEST['slug'] ) ) {
			return;
		}

		if( ! current_user_can( 'update_plugins' ) ) {
			wp_die( __( 'You do not have permission to install plugin updates', 'ultimate-social-media-plus' ), __( 'Error', 'ultimate-social-media-plus' ), array( 'response' => 403 ) );
		}

		$data         = $sc_usm_plugin_data[ $_REQUEST['slug'] ];
		$cache_key    = md5( 'sc_usm_plugin_' . sanitize_key( $_REQUEST['plugin'] ) . '_version_info' );
		$version_info = get_transient( $cache_key );

		if( false === $version_info ) {

			$update_api_url = $this->api_url."get_version";

			$api_params = array(
				'license_key' => ! empty( $data['license'] ) ? $data['license'] : '',
				'product_id'  => isset( $data['item_name'] ) ? $data['item_name'] : false,
				'baseurl'     => home_url(),
				'slug'        => $this->slug
			);

			$request = wp_remote_post( $update_api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			if ( 500 == $httpCode || 0 == $httpCode )
				return;

			$version_info = json_decode( str_replace("\xEF\xBB\xBF",'',wp_remote_retrieve_body($request)));;


			if(isset($version_info->success) && false == $version_info->success){				
				return false;			
			}

			if ( ! empty( $version_info ) && isset( $version_info->sections ) ) {
				$version_info->sections = maybe_unserialize( $version_info->sections );
			} else {
				$version_info = false;
			}
			set_transient( $cache_key, $version_info, 3600 );
		}

		if( ! empty( $version_info ) && isset( $version_info->sections['changelog'] ) ) {
			echo '<div style="background:#fff;padding:10px;">' . $version_info->sections['changelog'] . '</div>';
		}
		exit;
	}

}