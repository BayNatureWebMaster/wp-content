<?php

class MC4WP_Ecommerce_Tracker
{
    private $plugin_file;
    private $settings;

    /**
    * @var string $plugin_file
    * @var array $settings
    */
    public function __construct($plugin_file, $settings)
    {
        $this->plugin_file = $plugin_file;
        $this->settings = $settings;
    }

    /**
     * Add hooks
     */
    public function hook()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_footer', [$this, 'output_mcjs_script'], 60);
        add_action('woocommerce_checkout_update_order_meta', [$this, 'attach_order_meta'], 50, 1);
    }

    /**
     * Enqueue script on checkout page that periodically sends form data for guest checkouts.
     */
    public function enqueue_assets()
    {
        add_filter('script_loader_tag', [ $this, 'add_async_attribute' ], 20, 2);
        wp_enqueue_script('mc4wp-ecommerce-tracker', plugins_url("/assets/js/tracker.js", $this->plugin_file), [], MC4WP_PREMIUM_VERSION, true);
    }

    public function add_async_attribute($tag, $handle)
    {
        if ($handle !== 'mc4wp-ecommerce-tracker' || strpos($tag, ' defer ') !== false) {
            return $tag;
        }

        return str_replace(' src=', ' defer src=', $tag);
    }

    public function output_mcjs_script()
    {
        if (! $this->settings['load_mcjs_script'] || empty($this->settings['mcjs_url'])) {
            return;
        }

        printf('<script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","%s");</script>', $this->settings['mcjs_url']);
    }

    /**
     * @param int $order_id
     */
    public function attach_order_meta($order_id)
    {
        $order = wc_get_order($order_id);
        $dirty = false;

        $tracking_code = $this->get_tracking_code();
        if (! empty($tracking_code)) {
            $order->update_meta_data('mc_tc', $tracking_code);
            $dirty = true;
        }

        $campaign_id = $this->get_campaign_id();
        if (! empty($campaign_id)) {
            $order->update_meta_data('mc_cid', $campaign_id);
            $dirty = true;
        }

        $email_id = $this->get_email_id();
        if (! empty($email_id)) {
            $order->update_meta_data('mc_eid', $email_id);
            $dirty = true;
        }

        $landing_site = $this->get_landing_site();
        if (! empty($landing_site)) {
            $order->update_meta_data('mc_landing_site', $landing_site);
            $dirty = true;
        }

        if ($dirty) {
            $order->save();
        }
    }

    /**
     * @param int $order_id (optional)
     * @param string $key
     * @param bool $from_request
     * @return string
     */
    protected function get_value($order_id, $key, $from_request = true)
    {
        $value = '';

        // first, get from order meta
        if ($order_id && is_numeric($order_id)) {
            $value = $this->get_meta_value($order_id, $key);
        }

        if ($from_request) {
            // then, get from URL
            if (empty($value)) {
                $value = $this->get_url_value($key);
            }

            // then, get from cookie
            if (empty($value)) {
                $value = $this->get_cookie_value($key);
            }
        }

        return (string) $value;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function get_url_value($key)
    {
        return empty($_GET[$key]) ? '' : (string) $_GET[ $key ];
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function get_cookie_value($key)
    {
        return empty($_COOKIE[ $key ]) ? '' : (string) $_COOKIE[ $key ];
    }

    /**
     * @param int $order_id
     * @param string $key
     *
     * @return string
     */
    protected function get_meta_value($order_id, $key)
    {
            $order = wc_get_order($order_id);
        if (!$order) {
            return '';
        }

            return (string) $order->get_meta($key);
    }

    /**
     * @param int $order_id
     * @param bool $from_request
     * @return string
     */
    public function get_tracking_code($order_id = null, $from_request = true)
    {
        return $this->get_value($order_id, 'mc_tc', $from_request);
    }


    /**
     * @param int $order_id (optional)
     * @param bool $from_request
     *
     * @return string
     */
    public function get_campaign_id($order_id = null, $from_request = true)
    {
        return $this->get_value($order_id, 'mc_cid', $from_request);
    }


    /**
     * @param int $order_id (optional)
     * @param bool $from_request
     * @return string
     */
    public function get_email_id($order_id = null, $from_request = true)
    {
        return $this->get_value($order_id, 'mc_eid', $from_request);
    }

    /**
     * @param int $order_id (optionaL)
     * @param bool $from_request
     * @return string
     */
    public function get_landing_site($order_id = null, $from_request = true)
    {
        return $this->get_value($order_id, 'mc_landing_site', $from_request);
    }
}
