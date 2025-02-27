<?php

defined('ABSPATH') or exit;

/**
 * @since 3.3
 * @return array
 */
function mc4wp_ecommerce_get_settings()
{
    $options = get_option('mc4wp_ecommerce', []);
    $options = is_array($options) ? $options : [];
    $defaults = [
        'enable_product_tracking' => 0,
        'enable_order_tracking' => 0,
        'enable_cart_tracking' => 0,
        'load_mcjs_script' => 0,
        'include_all_order_statuses' => 0,
        'store_id' => '',
        'store' => [
            'list_id' => '',
            'name' => get_bloginfo('name'),
            'currency_code' => get_woocommerce_currency(),
            'is_syncing' => 1,
        ],
        'mcjs_url' => '',
        'last_updated' => null,
    ];

    if (isset($options['enable_object_tracking'])) {
        $options['enable_product_tracking'] = $options['enable_object_tracking'];
        $options['enable_order_tracking'] = $options['enable_object_tracking'];
        unset($options['enable_object_tracking']);
    }

    // merge saved options with defaults
    $options = array_merge($defaults, $options);
    $options['store'] = array_merge($defaults['store'], $options['store']);

    // fill store_id dynamically if it's empty
    if (empty($options['store_id'])) {
        $options['store_id'] = (string) md5(get_option('siteurl', ''));
    }

    // backwards compat for moved mcjs_url prop
    if (empty($options['mcjs_url']) && ! empty($options['store']['mcjs_url'])) {
        $options['mcjs_url'] = $options['store']['mcjs_url'];
        unset($options['store']['mcjs_url']);
    }

    // disable cart & order tracking if product tracking is disabled
    if (!$options['enable_product_tracking']) {
        $options['enable_cart_tracking'] = 0;
        $options['enable_order_tracking'] = 0;
    }


    /**
     * Filters the options array
     *
     * @param array $options
     */
    $options = apply_filters('mc4wp_ecommerce_options', $options);

    return $options;
}

/**
 * @since 3.3.2
 *
 * @param array $new_settings
 * @return array $settings
 */
function mc4wp_ecommerce_update_settings(array $new_settings)
{
    $old_settings = mc4wp_ecommerce_get_settings();
    $settings = array_replace_recursive($old_settings, $new_settings);
    update_option('mc4wp_ecommerce', $settings);
    return $settings;
}

/**
 * Gets which order statuses should be stored in Mailchimp.
 *
 * @private
 * @since 3.3
 * @return array
 */
function mc4wp_ecommerce_get_order_statuses()
{
    $opts = mc4wp_ecommerce_get_settings();

    if ($opts['include_all_order_statuses']) {
        $order_statuses = array_keys(wc_get_order_statuses());
    } else {
        $order_statuses = [ 'wc-completed', 'wc-processing' ];
    }

    /**
     * Filters the order statuses to send to Mailchimp
     *
     * @param array $order_statuses
     * @since 3.3
     */
    $order_statuses = apply_filters('mc4wp_ecommerce_order_statuses', $order_statuses);
    return $order_statuses;
}

/**
 * @param array $schedules
 * @return array
 */
function _mc4wp_ecommerce_cron_schedules($schedules)
{
    $schedules['every-minute'] = [
        'interval' => 60,
        'display' => __('Every minute', 'mc4wp-ecommerce'),
    ];
    return $schedules;
}

/**
 * Schedule e-commerce events with WP Cron.
 */
function _mc4wp_ecommerce_schedule_events()
{
    /**
    * Allows you to disable the WP Cron schedule for processing the queue.
    *
    * To be used when you process the queue over WP CLI using `wp mc4wp-ecommerce process-queue`
    */
    if (! apply_filters('mc4wp_ecommerce_schedule_process_queue_event', true)) {
        return;
    }

    $event_name = 'mc4wp_ecommerce_process_queue';
    $actual_next = wp_next_scheduled($event_name);

    if (! $actual_next || $actual_next > (time() + 600)) {
        wp_schedule_event(time() + 60, 'every-minute', $event_name);
    }
}
