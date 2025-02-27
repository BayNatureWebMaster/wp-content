<?php

class MC4WP_Ecommerce_Object_Transformer_WC3 implements MC4WP_Ecommerce_Object_Transformer
{
    /**
     * @var MC4WP_Ecommerce_Tracker
     */
    protected $tracker;

    /**
     * @var array
     */
    protected $settings;

    /**
     * MC4WP_Ecommerce_Object_Transformer constructor.
     *
     * @param array $settings
     * @param MC4WP_Ecommerce_Tracker $tracker
     */
    public function __construct(array $settings, MC4WP_Ecommerce_Tracker $tracker)
    {
        $this->settings = $settings;
        $this->tracker = $tracker;
    }

    /**
     * @param WC_Order|WP_User|WC_Customer|object $object
     * @param string $property
     *
     * @return string
     */
    private function get_object_property($object, $property)
    {
        // since WooCommerce 3.0, but only on instances of WC_Order and WC_Customer
        $method_name = 'get_' . $property;
        if (method_exists($object, $method_name)) {
            return $object->{$method_name}();
        }

        // instances of WP_User or WC_Customer
        if (!empty($object->{$property})) {
            return $object->{$property};
        }

        return '';
    }

    /**
     * @param string $email_address
     * @return string
     */
    public function get_customer_id($email_address)
    {
        return (string) md5(strtolower($email_address));
    }

    /**
     * Generate unique cart ID based on email address + today's date in Y-m-d
     *
     * @param string $customer_email_address
     * @see get_customer_id
     * @return string
     */
    public function get_cart_id($customer_email_address)
    {
        $date = date('Y-m-d');
        $customer_email_address = strtolower(trim($customer_email_address));
        $cart_id = md5($date . $customer_email_address);
        return $cart_id;
    }

    /**
     * @param object|WP_User|WC_Order|WC_Customer $object
     * @return array
     * @throws Exception
     * @link https://mailchimp.com/developer/api/marketing/ecommerce-customers/add-customer/
     */
    public function customer($object)
    {
        // first, attempt to get billing_email from order or customer object.
        $billing_email = (string) $this->get_object_property($object, 'billing_email');
        $user_email = (string) $this->get_object_property($object, 'user_email');

        // if the above failed, try "user_email" property
        if ('' === $billing_email && '' !== $user_email) {
            $billing_email = $user_email;
        }

        if ('' === $billing_email) {
            throw new Exception("Customer data requires a billing_email property", MC4WP_Ecommerce::ERR_NO_EMAIL_ADDRESS);
        }


        $customer_data = [
            'email_address' => $billing_email,
            'opt_in_status' => false,
        ];

        // fill top-level keys
        $map = [
            'billing_first_name' => 'first_name',
            'billing_last_name' => 'last_name',
            'billing_company' => 'company',
        ];
        foreach ($map as $source_property => $target_property) {
            $value = $this->get_object_property($object, $source_property);
            if (!empty($value)) {
                $customer_data[$target_property] = $value;
            }
        }

        // fill address keys
        $address_data = [
            'address1' => $this->get_object_property($object, 'billing_address_1'),
            'address2' => $this->get_object_property($object, 'billing_address_2'),
            'city' => $this->get_object_property($object, 'billing_city'),
            'province' => $this->get_object_property($object, 'billing_state'),
            'postal_code' => $this->get_object_property($object, 'billing_postcode'),
            'country_code' => $this->get_object_property($object, 'billing_country'),
        ];

        // only add address to data if this looks like a complete address
        // this is because mailchimp doesn't validate the format here. but DOES in subsequent POST list/members calls.
        if (
            !empty($address_data['address1'])
            && !empty($address_data['city'])
            && !empty($address_data['province'])
            && !empty($address_data['postal_code'])
            && !empty($address_data['country_code'])
        ) {
            $customer_data['address'] = $address_data;
        }

        /**
         * Filter the customer data before it is sent to Mailchimp.
         *
         * @array $customer_data
         */
        $customer_data = apply_filters('mc4wp_ecommerce_customer_data', $customer_data);

        // set ID because we don't want that to be filtered.
        $customer_data['id'] = $this->get_customer_id($billing_email);

        return $customer_data;
    }

    /**
     * @param WC_Order $order
     * @return int
     * @throws Exception
     */
    public function order_id(WC_Order $order)
    {
        if (method_exists($order, 'get_id')) {
            return $order->get_id();
        }

        if (!empty($order->id)) {
            return $order->id;
        }

        throw new Exception('Can not get ID from order');
    }

    /**
     * @param WC_Product $product
     * @return int
     * @throws Exception
     */
    public function product_id(WC_Product $product)
    {
        if (method_exists($product, 'get_id')) {
            return $product->get_id();
        }

        if (!empty($product->id)) {
            return $product->id;
        }

        throw new Exception('Can not get ID from product');
    }

    public function order_number(WC_Order $order)
    {
        return trim($order->get_order_number(), '#');
    }

    /**
     * @param WC_Order $order
     * @return array
     * @link https://mailchimp.com/developer/api/marketing/ecommerce-orders/add-order/
     */
    public function order(WC_Order $order)
    {
        $order_id = $order->get_id();
        $order_number = $this->order_number($order);
        $billing_email = $order->get_billing_email();

        // generate item lines data
        $items = $order->get_items('line_item');
        $order_lines_data = [];

        /**
         * @var int $item_id
         * @var WC_Order_Item_Product $item
         */
        foreach ($items as $item_id => $item) {
            // get item ID from method
            $item_id = method_exists($item, 'get_id') ? $item->get_id() : $item_id;

            // product may not exist anymore by now, we validate this outside of this class
            $product_id = $item->get_product_id();

            // calculate cost of a single item
            $qty = (int) $item->get_quantity();

            // sanity check for quantity
            if ($qty === 0) {
                continue;
            }

            $item_price = (float) ($item->get_total() / $qty);
            $line_data = [
                'id' => (string) $item_id,
                'product_id' => (string) $product_id,
                'product_variant_id' => (string) $product_id,
                'quantity' => $qty,
                'price' =>  $item_price,
            ];

            // use variation ID if set & product variation exists
            $variation_id = $item->get_variation_id();
            if (!empty($variation_id)) {
                $variation_product = wc_get_product($variation_id);

                if ($variation_product && $variation_product->get_parent_id() == $product_id) {
                    $line_data['product_variant_id'] = (string) $variation_id;
                }
            }

            $order_lines_data[] = $line_data;
        }

        // add order
        $customer_id = $this->get_customer_id($billing_email);
        $order_data = [
            'id' => (string) $order_number, // use order number instead of ID, because emails.
            'customer' => ['id' => $customer_id],
            'order_total' => floatval($order->get_total()),
            'tax_total' => floatval($order->get_total_tax()),
            'shipping_total' => floatval($order->get_shipping_total()),
            'discount_total' => floatval($order->get_discount_total()),
            'currency_code' => (string) $order->get_currency(),
            'lines' => (array) $order_lines_data,
            'billing_address' => $this->order_billing_address($order),
        ];

        if ($order->has_shipping_address()) {
            $order_data['shipping_address'] = $this->order_shipping_address($order);
        }

        // merge in order statuses (financial_status, fulfillment_status)
        $statuses = $this->order_status($order);
        $order_data = array_merge($order_data, $statuses);

        $date_created = $order->get_date_created();
        if ($date_created !== null) {
            $order_data['processed_at_foreign'] = $date_created->setTimezone(new \DateTimeZone('UTC'))->format(DateTime::ATOM);
        }

        // add tracking code(s)
        $tracking_code = $this->tracker->get_tracking_code($order_id, false);
        if (!empty($tracking_code)) {
            $order_data['tracking_code'] = $tracking_code;
        }

        $campaign_id = $this->tracker->get_campaign_id($order_id, false);
        if (!empty($campaign_id)) {
            $order_data['campaign_id'] = $campaign_id;
        }

        $landing_site = $this->tracker->get_landing_site($order_id, false);
        if (! empty($landing_site)) {
            $order_data['landing_site'] = $landing_site;
        }

        // only send `order_url` if it looks like an actual domain, because mailchimp will reject values like "localhost/order/5"
        $order_url = $order->get_view_order_url();
        if (strpos($order_url, '.') && strpos($order_url, 'wordpress.') === false && strpos($order_url, '.localhost') === false) {
            $order_data['order_url'] = $order_url;
        }

        /**
         * Filter order data that is sent to Mailchimp.
         *
         * @param array $order_data
         * @param WC_Order $order
         */
        $order_data = apply_filters('mc4wp_ecommerce_order_data', $order_data, $order);

        return $order_data;
    }

    /**
     * @param WC_Product $product
     * @return array
     * @link https://mailchimp.com/developer/api/marketing/ecommerce-products/add-product/
     */
    public function product(WC_Product $product)
    {
        // data to send to Mailchimp
        $product_data = [
            // required
            'id' => (string) $product->get_id(),
            'title' => (string) strip_tags($product->get_title()),
            'url' => (string) $product->get_permalink(),
            'variants' => [
                $this->get_product_variant_data($product) // only add main product as a variation here
            ],

            // optional
            'type' => (string) $product->get_type(),
            'image_url' => function_exists('get_the_post_thumbnail_url') ? (string) get_the_post_thumbnail_url($product->get_id(), 'shop_single') : '',
        ];

        // add product categories, joined together by "|"
        $category_names = [];
        $category_objects = get_the_terms($product->get_id(), 'product_cat');
        if (is_array($category_objects)) {
            foreach ($category_objects as $term) {
                $category_names[] = $term->name;
            }
            if (!empty($category_names)) {
                $product_data['vendor'] = join('|', $category_names);
            }
        }

        /**
         * Filter product data that is sent to Mailchimp.
         *
         * @param array $product_data
         */
        $product_data = apply_filters('mc4wp_ecommerce_product_data', $product_data, $product);
        return $product_data;
    }

    /**
     * @param WC_Product $product
     *
     * @return array
     */
    public function product_variants(WC_Product $product)
    {
        // add product variants if this is a variable product
        $variants = [];
        $children = $product->get_children();
        if (is_array($children)) {
            foreach ($children as $product_variation_id) {
                $product_variation = wc_get_product($product_variation_id);

                // only add variation if it exists
                if ($product_variation instanceof WC_Product) {
                    $variants[] = $this->get_product_variant_data($product_variation);
                }
            }
        }

        /**
         * Filter product data that is sent to Mailchimp.
         *
         * @param array $variants_data
         */
        $variants = apply_filters('mc4wp_ecommerce_product_variants_data', $variants, $product);

        return $variants;
    }

    /**
     * @param WC_Product $product
     * @return array
     */
    private function get_product_variant_data(WC_Product $product)
    {

        // determine inventory quantity; default to 0 for unpublished products
        $inventory_quantity = 0;
        $visibility = 'hidden';

        // only get actual stock qty when product is published & visible
        if ($product->get_status() === 'publish' && $product->get_catalog_visibility() !== 'hidden') {
            $visibility = 'visible';

            if ($product->managing_stock()) {
                $inventory_quantity = $product->get_stock_quantity();
            } else {
                $out_of_stock = $product->get_stock_status() !== 'instock';
                $inventory_quantity = $out_of_stock ? 0 : 1; // default to 1 when not managing stock & not manually set to "out of stock"
            }
        }

        $data = [
            // required
            'id' => (string) $product->get_id(),
            'title' => (string) strip_tags($product->get_title()),
            'url' => (string) $product->get_permalink(),

            // optional
            'price' => (float) $product->get_price(),
            'image_url' => function_exists('get_the_post_thumbnail_url') ? (string) get_the_post_thumbnail_url($product->get_id(), 'shop_single') : '',
            'inventory_quantity' => (int) $inventory_quantity,
            'visibility' => $visibility,
        ];

        // add SKU
        $sku = (string) $product->get_sku();
        if ($sku !== '') {
            $data['sku'] = $sku;
            $data['title'] .= ' (' . $sku . ')';
        }

        return $data;
    }

    /**
     * @param array $customer_data
     * @param array $cart_items
     *
     * @return array
     *
     * @throws Exception
     */
    public function cart(array $customer_data, array $cart_items)
    {
        $lines_data = [];
        $order_total = 0.00;
        $tax_total = 0.00;

        // check if cart has lines
        if (empty($cart_items)) {
            throw new Exception("Cart has no item lines", MC4WP_Ecommerce::ERR_NO_ITEMS);
        }

        // generate data for cart lines
        foreach ($cart_items as $line_id => $cart_item) {
            $product_variant_id = !empty($cart_item['variation_id']) ? $cart_item['variation_id'] : $cart_item['product_id'];
            $product = wc_get_product($product_variant_id);

            // check if product exists before adding to line data
            if (! $product) {
                continue;
            }

            // get product ID from product object if it's a variable product
            $product_id = $cart_item['product_id'];
            if ($product_id !== $product_variant_id) {
                $product_id = $product->get_parent_id();
            }

            $lines_data[] = [
                'id' => (string) $line_id,
                'product_id' => (string) $product_id,
                'product_variant_id' => (string) $product_variant_id,
                'quantity' => (int) $cart_item['quantity'],
                'price' => isset($cart_item['line_total']) ? floatval($cart_item['line_total']) / floatval($cart_item['quantity']) : floatval($product->get_price()),
            ];

            $order_total += isset($cart_item['line_subtotal']) ? floatval($cart_item['line_subtotal']) : floatval($product->get_price()) * $cart_item['quantity'];
            $tax_total += isset($cart_item['line_tax']) ? floatval($cart_item['line_tax']) : 0.00;
        }

        $cart_id = $this->get_cart_id($customer_data['email_address']);
        $checkout_url = add_query_arg(['mc_cart_id' => $cart_id], wc_get_cart_url());
        $cart_data = [
            'id' => (string) $cart_id,
            'customer' => $customer_data,
            'checkout_url' => (string) $checkout_url,
            'currency_code' => (string) $this->settings['store']['currency_code'],
            'tax_total' => (float) $tax_total,
            'order_total' => (float) $order_total,
            'lines' => (array) $lines_data,
        ];

        /**
         * Filters the cart data that is sent to Mailchimp.
         *
         * @param array $cart_data
         * @param array $cart_items Raw cart items array coming from WooCommerce.
         */
        $cart_data = apply_filters('mc4wp_ecommerce_cart_data', $cart_data, $cart_items);

        return $cart_data;
    }

    /**
     * @see https://mailchimp.com/developer/guides/getting-started-with-ecommerce/#Order_Notifications
     * @param WC_Order $order
     * @return array
     */
    private function order_status(WC_Order $order)
    {
        $map = [
            'pending' => [
                'financial_status' => 'pending', // Sends the order confirmation
            ],
            'on-hold' => [
                'financial_status' => 'pending', // Sends the order confirmation
            ],
            'processing' => [
                'financial_status' => 'pending', // Sends the order confirmation
            ],
            'completed' => [
                'financial_status' => 'paid', // Sends the order invoice
                'fulfillment_status' => 'fulfilled', // Sends the shipping confirmation
            ],
            'cancelled' => [
                'financial_status' => 'cancelled', // Sends cancellation confirmation
            ],
            'refunded' => [
                'financial_status' => 'refunded', // Sends refund confirmation
            ],
            'failed' => [],
        ];

        $status = (string) $order->get_status();
        if (isset($map[$status])) {
            return $map[$status];
        }

        return [];
    }

    /**
     * @param WC_Order $order
     * @return object
     */
    private function order_shipping_address(WC_Order $order)
    {
        return (object) [
            'name' => sprintf('%s %s', $order->get_shipping_first_name(), $order->get_shipping_last_name()),
            'company' => $order->get_shipping_company(),
            'address1' => $order->get_shipping_address_1(),
            'address2' => $order->get_shipping_address_2(),
            'city' => $order->get_shipping_city(),
            'province' => $order->get_shipping_state(),
            'postal_code' => $order->get_shipping_postcode(),
            'country' => $order->get_shipping_country(),
        ];
    }

    /**
     * @param WC_Order $order
     * @return object
     */
    private function order_billing_address(WC_Order $order)
    {
        return (object) [
            'name' => sprintf('%s %s', $order->get_billing_first_name(), $order->get_billing_last_name()),
            'company' => $order->get_billing_company(),
            'phone' => $order->get_billing_phone(),
            'address1' => $order->get_billing_address_1(),
            'address2' => $order->get_billing_address_2(),
            'city' => $order->get_billing_city(),
            'province' => $order->get_billing_state(),
            'postal_code' => $order->get_billing_postcode(),
            'country' => $order->get_billing_country(),
        ];
    }
}
