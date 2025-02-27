<?php

use Automattic\WooCommerce\Utilities\OrderUtil;

/**
* Class MC4WP_Ecommerce_Admin
*
* @ignore
*/
class MC4WP_Ecommerce_Admin
{
    /**
    * @var string
    */
    protected $plugin_file;

    /**
    * @var array
    */
    protected $settings;

    /**
    * @var MC4WP_Queue
    */
    protected $queue;

    /**
     * MC4WP_Ecommerce_Admin constructor.
     *
     * @param string $plugin_file
     * @param MC4WP_Queue|null $queue
     * @param array $settings
     */
    public function __construct($plugin_file, $queue, array $settings)
    {
        $this->plugin_file = $plugin_file;
        $this->queue = $queue;
        $this->settings = $settings;

        // Don't typehint $queue in constructor as this may be null when e-commerce is disabled
    }

    /**
    * Add hooks
    */
    public function add_hooks()
    {
        add_filter('mc4wp_admin_menu_items', [ $this, 'menu_items' ]);
        add_action('mc4wp_admin_save_ecommerce_settings', [ $this, 'save_settings' ]);
        add_action('mc4wp_admin_ecommerce_reset', [ $this, 'reset_data' ]);
        add_action('mc4wp_admin_ecommerce_queue_all_products', [ $this, 'queue_all_products' ]);
        add_action('mc4wp_admin_ecommerce_queue_all_orders', [ $this, 'queue_all_orders' ]);
        add_action('admin_notices', [$this, 'admin_notices']);


        // if connected to a list, add bulk actions for products, orders & coupons
        if (! empty($this->settings['store']['list_id']) && $this->settings['enable_product_tracking']) {
            $post_types = [ 'product', 'shop_coupon' ];
            if ($this->settings['enable_order_tracking']) {
                $post_types[] = 'shop_order';
            }

            foreach ($post_types as $post_type) {
                add_filter('bulk_actions-edit-' . $post_type, [ $this, 'bulk_action_add']);
                add_filter('handle_bulk_actions-edit-' . $post_type, [ $this, 'bulk_action_handle' ], 10, 3);
            }
            add_action('admin_notices', [ $this, 'bulk_action_admin_notice' ]);
        }
    }

    public function admin_notices()
    {
        if (!isset($_GET['page']) || strpos($_GET['page'], 'mailchimp-for-wp') !== 0) {
            return;
        }

        // show notice when e-commerce automations are still disabled
        if (! empty($this->settings['store']['list_id']) && $this->settings['store']['is_syncing']) {
            echo '<div class="notice notice-warning"><p>' . sprintf(__('<strong>Heads up!</strong> Your e-commerce automations in Mailchimp are disabled right now. If you\'re done adding products and orders, do not forget to <a href="%s">re-enable automations here</a>.', 'mc4wp-ecommerce'), admin_url('admin.php?page=mailchimp-for-wp-ecommerce&edit=store')) . '</p></div>';
        }
    }

    // GET /wp-admin/admin.php?page=mailchimp-for-wp-ecommerce&_mc4wp_action=ecommerce_queue_all_products
    public function queue_all_products()
    {
        $helper = new MC4WP_Ecommerce_Helper();
        $product_ids = $helper->get_product_ids();

        foreach ($product_ids as $product_id) {
            $this->queue->put([
                'method' => 'add_product',
                'args' => [ $product_id ]
            ]);
        }

        $this->queue->save();
    }

        // GET /wp-admin/admin.php?page=mailchimp-for-wp-ecommerce&_mc4wp_action=ecommerce_queue_all_orders
    public function queue_all_orders()
    {
        $helper = new MC4WP_Ecommerce_Helper();
        $ids = $helper->get_order_ids();

        foreach ($ids as $id) {
            $this->queue->put([
                'method' => 'add_order',
                'args' => [ $id ]
            ]);
        }

        $this->queue->save();
    }

    /**
    * Runs logic for saving e-commerce settings & wizard.
    */
    public function save_settings()
    {
        // check if queue processor is scheduled
        _mc4wp_ecommerce_schedule_events();

        $ecommerce = $this->get_ecommerce();
        $messages = $this->get_admin_messages();

        $dirty = stripslashes_deep($_POST['mc4wp_ecommerce']);

        // merge with current settings to allow passing partial arrays
        $current = $this->settings;
        $dirty = array_replace_recursive($current, $dirty);
        $diff = array_diff($dirty['store'], $current['store']);

        if (! empty($diff)) {
            try {
                $store_data = $ecommerce->update_store($dirty['store']);
            } catch (Exception $e) {
                $messages->flash((string) $e, 'error');
                $_POST['_redirect_to'] = '';
                return; // return means we're not saving
            }

            // store actual store ID + mc.js script url
            $dirty['store_id'] = $store_data->id;
            $dirty['mcjs_url'] = $store_data->connected_site->site_script->url;
            $ecommerce->set_store_id($store_data->id);
        }


        // verify script installation after it is toggled
        if ($dirty['load_mcjs_script'] == 1 && $current['load_mcjs_script'] == 0) {
            try {
                $ecommerce->ensure_connected_site();
                $ecommerce->verify_store_script_installation();
            } catch (MC4WP_API_Exception $e) {
                // error verifying script installation (store not found)
                $this->get_log()->error(sprintf('E-Commerce: error verifying script installation. %s', $e));
                $messages->flash("Error enabling MC.js. Please re-connect your store to Mailchimp.", 'error');
                return; // return means we're not saving
            }
        }

        // save new settings if something changed
        if ($dirty != $current || ! empty($diff)) {
            update_option('mc4wp_ecommerce', $dirty);
            $messages->flash('Settings saved!');
        }
    }

    /**
    * @param array $items
    *
    * @return array
    */
    public function menu_items($items)
    {
        $items[] = [
            'title' => __('E-Commerce', 'mc4wp-ecommerce'),
            'text' => __('E-Commerce', 'mc4wp-ecommerce'),
            'slug' => 'ecommerce',
            'callback' => [ $this, 'show_settings_page' ],
            'load_callback', [ $this, 'redirect_to_wizard' ],
        ];

        return $items;
    }

    /**
    * Redirect to wizard when store settings are empty.
    */
    public function redirect_to_wizard()
    {
        $settings = $this->settings;

        if ($settings['enable_product_tracking'] && empty($settings['store']['list_id']) && ! isset($_GET['wizard'])) {
            wp_safe_redirect(add_query_arg([ 'wizard' => 1 ]));
        }
    }

    /**
    * Show settings page
    */
    public function show_settings_page()
    {
        $settings = $this->settings;
        $mailchimp = new MC4WP_MailChimp();
        $lists = $mailchimp->get_lists();
        $connected_list = null;

        $helper = new MC4WP_Ecommerce_Helper();

        $product_ids = $helper->get_product_ids();
        $tracked_product_ids = $helper->get_tracked_product_ids();
        $order_ids = $helper->get_order_ids();
        $tracked_order_ids = $helper->get_tracked_order_ids();

        // we need to reset array index here because array_diff preserves it
        $untracked_product_ids = array_values(array_diff($product_ids, $tracked_product_ids));
        $untracked_order_ids = array_values(array_diff($order_ids, $tracked_order_ids));

        $product_count = new MC4WP_Ecommerce_Object_Count(count($product_ids), count($untracked_product_ids));
        $order_count = new MC4WP_Ecommerce_Object_Count(count($order_ids), count($untracked_order_ids));

        $assets_url = plugins_url('/assets', $this->plugin_file);
        wp_enqueue_style('mc4wp-ecommerce-admin', $assets_url . '/css/admin.css', [], MC4WP_PREMIUM_VERSION);
        wp_enqueue_script('mc4wp-ecommerce-admin', $assets_url . '/js/admin.js', [], MC4WP_PREMIUM_VERSION, true);
        wp_localize_script('mc4wp-ecommerce-admin', 'mc4wp_ecommerce', [
            'i18n' => [
                'done' => __('All done!', 'mc4wp-ecommerce'),
                'pause' => __('Pause', 'mc4wp-ecommerce'),
                'resume' => __('Resume', 'mc4wp-ecommerce'),
                'confirmation' => __('Are you sure you want to do this?', 'mc4wp-ecommerce'),
                'process' => __('Process now', 'mc4wp-ecommerce'),
                'reset' => __('Clear queue', 'mc4wp-ecommerce'),
                'processing' => __('Processing queue, please wait.', 'mc4wp-ecommerce'),
            ],
//            'product_count' => $product_count,
//            'product_ids' => $untracked_product_ids,
//            'order_count' => $order_count,
//            'order_ids' => (array) $untracked_order_ids,
        ]);

        // get connected list
        if (! empty($settings['store']['list_id'])) {
            $connected_list = $mailchimp->get_list($settings['store']['list_id']);
        }

        $queue = $this->queue;

        if (isset($_GET['edit']) && $_GET['edit'] === 'store') {
            require __DIR__ . '/views/edit-store.php';
        } elseif (! empty($_GET['wizard'])) {
            require __DIR__ . '/views/wizard.php';
        } else {
            require __DIR__ . '/views/admin-page.php';
        }
    }

    /**
    * Resets all e-commerce data
    */
    public function reset_data()
    {
        $this->settings['store']['list_id'] = '';
        update_option('mc4wp_ecommerce', $this->settings);

        // delete local tracking indicators
        delete_post_meta_by_key(MC4WP_Ecommerce::META_KEY);

        // for HPOS too
        if (class_exists(OrderUtil::class) && OrderUtil::custom_orders_table_usage_is_enabled()) {
            global $wpdb;
            $query = $wpdb->prepare("DELETE FROM {$wpdb->prefix}wc_orders_meta WHERE meta_key = %s", [MC4WP_Ecommerce::META_KEY]);
            $wpdb->query($query);
        }

        if (! empty($_POST['delete_store_in_mailchimp'])) {
            // remove store in mailchimp
            try {
                $this->get_api()->delete_ecommerce_store($this->settings['store_id']);
            } catch (MC4WP_API_Resource_Not_Found_Exception $e) {
                // good.
            } catch (Exception $e) {
                // bad.
                $this->get_admin_messages()->flash((string) $e, 'error');
                return;
            }
        }

        $this->settings['store_id'] = '';
        update_option('mc4wp_ecommerce', $this->settings);
    }

    public function bulk_action_add($bulk_actions)
    {
        $bulk_actions['mc4wp_ecommerce_bulk_sync_objects'] = __('Synchronise with Mailchimp', 'mc4wp-premium');
        return $bulk_actions;
    }

    public function bulk_action_handle($redirect_to, $doaction, $post_ids)
    {
        if ($doaction !== 'mc4wp_ecommerce_bulk_sync_objects' || empty($post_ids)) {
            return $redirect_to;
        }

        // trigger save_post_${post_type} so object observer can queue a new job
        $post_type = get_post_type($post_ids[0]);
        foreach ($post_ids as $post_id) {
            do_action('save_post_' . $post_type, $post_id);
        }

        $redirect_to = add_query_arg('mc4wp_ecommerce_bulk_synced_objects', count($post_ids), $redirect_to);
        return $redirect_to;
    }

    public function bulk_action_admin_notice()
    {
        if (empty($_REQUEST['mc4wp_ecommerce_bulk_synced_objects'])) {
            return;
        }

        $count = intval($_REQUEST['mc4wp_ecommerce_bulk_synced_objects']);
        $next_run = wp_next_scheduled('mc4wp_ecommerce_process_queue');

        echo '<div id="message" class="updated fade">';
        echo '<p>' . sprintf(__('Added <strong>%d</strong> synchronisation job to MC4WP\'s  background queue. Currently <a href="%s">pending background jobs</a> will be processed on <strong>%s</strong> at <strong>%s</strong>.', 'mc4wp-premium'), $count, admin_url('admin.php?page=mailchimp-for-wp-ecommerce'), date(get_option('date_format'), $next_run), date(get_option('time_format'), $next_run)) . '</p>';
        echo '</div>';
    }

    /**
     * @return MC4WP_Debug_Log
     */
    private function get_log()
    {
        return mc4wp('log');
    }

    /**
    * @return MC4WP_API_V3
    */
    private function get_api()
    {
        return mc4wp('api');
    }

    /**
    * @return MC4WP_Ecommerce
    */
    private function get_ecommerce()
    {
        return mc4wp('ecommerce');
    }

    /**
    * @return MC4WP_Admin_Messages
    */
    private function get_admin_messages()
    {
        return mc4wp('admin.messages');
    }
}
