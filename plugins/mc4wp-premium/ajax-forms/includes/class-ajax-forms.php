<?php

/**
 * Class MC4WP_Form_Ajaxifier
 *
 * @ignore
 */
class MC4WP_AJAX_Forms
{
    /**
     * @var string
     */
    protected $plugin_file;

    /**
     * @var bool Is the script enqueued already?
     */
    protected $is_script_enqueued = false;

    /**
     * @param string $plugin_file
     */
    public function __construct($plugin_file)
    {
        $this->plugin_file = $plugin_file;

        add_filter('mc4wp_form_css_classes', [$this, 'form_css_classes'], 10, 2);
        add_filter('mc4wp_form_settings', [$this, 'form_settings']);
        add_action('mc4wp_output_form', [$this, 'maybe_enqueue_script']);
        add_action('mc4wp_form_respond', [$this, 'respond_to_request']);
    }

    /**
     * @param array $settings
     *
     * @return array
     */
    public function form_settings($settings)
    {
        $defaults = [
            'ajax' => 1
        ];
        $settings = array_merge($defaults, $settings);
        return $settings;
    }

    /**
     * @param            $classes
     * @param MC4WP_Form $form
     *
     * @return array
     */
    public function form_css_classes($classes, MC4WP_Form $form)
    {
        if ($form->settings['ajax']) {
            $classes[] = 'mc4wp-ajax';
        }

        return $classes;
    }

    /**
     * Enqueues the AJAX script whenever a form is outputted with AJAX enabled.
     *
     * This also fetches the "general error" text of the first form it encounters with AJAX enabled. Not optimal, but does the trick.
     *
     * @param MC4WP_Form $form
     */
    public function maybe_enqueue_script(MC4WP_Form $form)
    {
        if (! $form->settings['ajax'] || $this->is_script_enqueued) {
            return;
        }

        wp_enqueue_script('mc4wp-ajax-forms', plugins_url('/assets/js/ajax-forms.js', $this->plugin_file), ['mc4wp-forms-api'], MC4WP_PREMIUM_VERSION, true);
        add_filter('script_loader_tag', [$this, 'add_defer_attribute' ], 10, 2);

        // default loading character
        $character = "&bull;";

        /**
         * Filters the loading character used for AJAX requests
         *
         * @param string $character
         */
        $loading_character = (string) apply_filters('mc4wp_forms_ajax_loading_character', $character);
        $ajax_url = add_query_arg(['action' => 'mc4wp-form'], admin_url('admin-ajax.php'));
        $error_text = $form->get_message('error');

        // Print vars required by AJAX script
        $vars = [
            'loading_character'     => $loading_character,
            'ajax_url'              => $ajax_url,
            'error_text'            => (string) $error_text,
        ];
        $vars = apply_filters('mc4wp_ajax_form_vars', $vars, $form);

        wp_localize_script('mc4wp-ajax-forms', 'mc4wp_ajax_vars', $vars);

        $this->is_script_enqueued = true;
    }

    /**
     * Adds defer attribute to our <script> element
     */
    public function add_defer_attribute($tag, $handle)
    {
        if ($handle !== 'mc4wp-ajax-forms') {
            return $tag;
        }

        return str_replace(' src=', ' defer src=', $tag);
    }


    /**
     * @param MC4WP_Form $form
     */
    public function respond_to_request(MC4WP_Form $form)
    {
        // do nothing if we're not doing AJAX
        if (! defined('DOING_AJAX') || ! DOING_AJAX) {
            return;
        }

        // clear output, some plugins might have thrown errors by now.
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        send_origin_headers();
        send_nosniff_header();
        nocache_headers();

        // Format response using Google JSON Style Guide: https://google.github.io/styleguide/jsoncstyleguide.xml
        $response = [];

        // error
        if ($form->has_errors()) {
            $response['error'] = [
                'type' => $form->errors[0],
                'message' => $form->get_response_html(),
                'errors' => $form->errors
            ];

            wp_send_json($response);
            exit;
        }

        // success
        $data = [
            'event' => '',
            'message' => $form->get_response_html(),
            'hide_fields' => (bool) $form->settings['hide_after_success']
        ];

        // set event: "subscribed", "unsubscribed" or "subscriber_updated"
        if (! empty($form->last_event)) {
            $data['event'] = $form->last_event;
        }

        // set redirect url (if not empty or 0)
        $redirect_url = $form->get_redirect_url();
        if (! empty($redirect_url)) {
            $data['redirect_to'] = $redirect_url;
        }

        $response['data'] = $data;
        wp_send_json($response);
        exit;
    }
}
