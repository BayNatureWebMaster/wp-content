<?php

/**
 * Class MC4WP_Email_Notification
 *
 * @access private
 */
class MC4WP_Email_Notification
{
    /**
     * @var mixed Recipients of the email notification
     */
    protected $recipients;

    /**
     * @var string Message of the email
     */
    protected $message_body = '';

    /**
     * @var string Email subject
     */
    protected $subject = '';

    /**
     * @var MC4WP_Form Form this notification is set-up for
     */
    protected $form;

    /**
     * @var string Content type of the email
     */
    protected $content_type = 'text/html';

    /**
     * @var MC4WP_MailChimp_Subscriber[]
     */
    protected $map;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $raw_data;

    /**
     * @param string $recipients
     * @param string $subject
     * @param string $message_body
     * @param string $content_type
     * @param MC4WP_Form $form
     * @param MC4WP_MailChimp_Subscriber[] $map
     */
    public function __construct($recipients, $subject, $message_body, $content_type, MC4WP_Form $form, array $map = [])
    {
        $this->recipients = $recipients;
        $this->subject = $subject;
        $this->content_type = $content_type;
        $this->message_body = $message_body;
        $this->form = $form;
        $this->data = method_exists($form, 'get_data') ? $form->get_data() : $form->data;
        $this->raw_data = method_exists($form, 'get_raw_data') ? array_change_key_case($form->get_raw_data(), CASE_UPPER) : $this->data;
        $this->map = $map;
    }

    /**
     * @return string
     */
    public function get_recipients()
    {
        $form = $this->form;

        // parse tags in receivers
        $recipients = $this->parse_tags($this->recipients);

        /**
         * Filters the recipients of the email notification for forms just before it is sent.
         *
         * @param string $recipients
         * @param MC4WP_Form $form
         */
        $recipients = (string) apply_filters('mc4wp_form_email_notification_recipients', $recipients, $form);

        return $recipients;
    }

    /**
     * @return string
     */
    public function get_subject()
    {
        $form = $this->form;

        // parse tags in subject
        $subject = $this->parse_tags($this->subject);

        /**
         * Filters the subject line of the email notification just before it is sent.
         *
         * @param string $subject
         * @param MC4WP_Form $form
         */
        $subject = (string) apply_filters('mc4wp_form_email_notification_subject', $subject, $form);

        return $subject;
    }

    /**
     * @return string
     */
    public function get_message()
    {
        $form = $this->form;

        // parse tags in message body
        $message = $this->parse_tags($this->message_body);

        // add <br> tags when content type is set to HTML
        if ($this->content_type === 'text/html') {
            $message = nl2br($message);
        }

        /**
         * Filters the message body of the email notification for forms just before it is sent.
         *
         * @param string $message
         * @param MC4WP_Form $form
         */
        $message = (string) apply_filters('mc4wp_form_email_notification_message', $message, $form);

        return $message;
    }

    /**
     * Get email headers
     *
     * @return array
     */
    public function get_headers()
    {
        $form = $this->form;

        $headers = [
            'Content-Type' => $this->content_type,
        ];


        /**
         * Filters the email headers for the email notification for forms.
         *
         * @param array $headers
         * @param MC4WP_Form $form
         */
        $headers = (array) apply_filters('mc4wp_form_email_notification_headers', $headers, $form);

        $headers_i = [];
        foreach ($headers as $key => $value) {
            // if key is a string, use that as header prefix
            if (is_string($key)) {
                $headers_i[] = sprintf("%s: %s", $key, $value);
            } else {
                $headers_i[] = $value;
            }
        }

        return $headers_i;
    }

    /**
     * Get email attachments
     *
     * @return array
     */
    public function get_attachments()
    {
        $form = $this->form;
        $attachments = [];

        /**
         * Filters the attachments to add to the email notification for forms.
         *
         * @param array $attachments
         * @param MC4WP_Form $form
         */
        return (array) apply_filters('mc4wp_form_email_notification_attachments', $attachments, $form);
    }

    /**
     * Send the email
     *
     * @return bool
     */
    public function send()
    {
        return wp_mail(
            $this->get_recipients(),
            $this->get_subject(),
            $this->get_message(),
            $this->get_headers(),
            $this->get_attachments()
        );
    }

    /**
     * Returns a readable & sanitized presentation of the value
     *
     * @param mixed $value
     *
     * @return string
     */
    private function readable_value($value)
    {
        if (is_array($value)) {
            // test if we can turn this array into a string..
            $plain = array_values($value);
            if (empty($plain) || is_array($plain[0])) {
                return '';
            }

            $value = join(', ', (array) $value);
        }

        // Format as date if value looks like a date
        if (is_string($value) && strlen($value) >= 8 && strlen($value) <= 10 && preg_match('/\d{4}[-\/]\d{1,2}[-\/]\d{1,2}|\d{1,2}[-\/]\d{1,2}[-\/]\d{4}/', $value) > 0 && ($timestamp = strtotime($value)) && $timestamp != false) {
            $value = date(get_option('date_format'), $timestamp);
        }

        return esc_html($value);
    }

    /**
     * @param string $key
     * @param null $subkey
     *
     * @return string
     */
    private function replace_field_tag($key, $subkey = null)
    {
        // return empty string if value not known
        if (isset($this->data[ $key ])) {
            $value = $this->data[ $key ];
        } elseif (isset($this->raw_data[$key])) {
            $value = $this->raw_data[ $key ];
        } else {
            return '';
        }

        // do we need subkey?
        if ($subkey && is_array($value)) {
            // uppercase array keys as mc4wp only uppercases top-level array keys
            $value = array_change_key_case($value, CASE_UPPER);
            $value = isset($value[ $subkey ]) ? $this->readable_value($value[ $subkey ]) : '';

            return $value;
        }

        return $this->readable_value($value);
    }

    /**
     * @param string $key
     * @param string $subkey
     * @return string
     */
    private function replace_interests_tag($key, $subkey)
    {
        // return empty string if value not known
        if (! isset($this->data[ $key ]) || empty($this->map)) {
            return '';
        }

        $mailchimp = new MC4WP_MailChimp();
        $interest_names = [];
        $interest_category_id = strtolower($subkey);

        // version 4.x gives us a map of list ids => subscriber objects
        foreach ($this->map as $list_id => $subscriber_data) {
            $list = $mailchimp->get_list($list_id);
            if (!$list) {
                continue;
            }

            $interest_category = $this->get_interest_category_by_interest_or_category_id($list_id, $interest_category_id);

            // interests
            foreach ($subscriber_data->interests as $interest_id => $interested) {
                if (!$interested) {
                    continue;
                }

                // skip if this is interest from other category
                if (!isset($interest_category->interests[$interest_id])) {
                    continue;
                }

                $interest_name = $interest_category->interests[$interest_id];
                $interest_names[] = $interest_name;
            }
        }

        return $this->readable_value($interest_names);
    }

    /**
     * @param array $matches
     * @return string
     */
    private function replace_tag($matches)
    {
        $key = strtoupper($matches[1]);
        $subkey = isset($matches[2]) ? strtoupper($matches[2]) : null;

        // catch-all tag
        if ($key === '_ALL_') {
            return $this->replace_summary_tag();
        }

        // [INTERESTS:1234]
        if ($key === 'INTERESTS') {
            return $this->replace_interests_tag($key, $subkey);
        }

        // [_MC4WP_LISTS]
        if ($key === '_MC4WP_LISTS') {
            return $this->replace_lists_tag();
        }

        // field tag
        return $this->replace_field_tag($key, $subkey);
    }

    /**
    * @return string
    */
    private function replace_lists_tag()
    {
        $list_names = [];
        $mailchimp = new MC4WP_MailChimp();

        foreach ($this->map as $list_id => $subscriber_data) {
            $list = $mailchimp->get_list($list_id);
            $list_names[] = $list ? $list->name : 'Unknown list';
        }

        return join(', ', $list_names);
    }

    /**
     * @param $string
     *
     * @return mixed
     */
    private function parse_tags($string)
    {
        $string = preg_replace_callback('/\[(\w+)(?:\:(\w+)){0,1}\]/', [ $this, 'replace_tag' ], $string);
        return $string;
    }

    private function get_field_by_tag($list_id, $field_tag)
    {
        $mailchimp = new MC4WP_MailChimp();

        // method requires Mailchimp for WordPress version 4.6.0
        if (! method_exists($mailchimp, 'get_list_merge_fields')) {
            return null;
        }

        $merge_fields = $mailchimp->get_list_merge_fields($list_id);
        foreach ($merge_fields as $field) {
            if ($field->tag === $field_tag) {
                return $field;
            }
        }

        return null;
    }

    private function get_interest_category_by_interest_or_category_id($list_id, $interest_or_category_id)
    {
        $mailchimp = new MC4WP_MailChimp();

        // method requires Mailchimp for WordPress version 4.6.0
        if (! method_exists($mailchimp, 'get_list_interest_categories')) {
            return null;
        }
        $interest_categories = $mailchimp->get_list_interest_categories($list_id);
        foreach ($interest_categories as $category) {
            if ($category->id === $interest_or_category_id || isset($category->interests[$interest_or_category_id])) {
                return $category;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    private function replace_summary_tag()
    {
        if (empty($this->map)) {
            return '';
        }

        $string = '';
        $mailchimp = new MC4WP_MailChimp();

        // for BC with Mailchimp for WP 3.x
        $plain_map = array_values($this->map);
        if (! empty($plain_map) && ! $plain_map[0] instanceof MC4WP_MailChimp_Subscriber) {
            foreach ($this->map as $key => $value) {
                $string .= sprintf('<strong>%s</strong>: %s', $key, $this->readable_value($value)) . PHP_EOL;
            }

            return $string;
        }

        // version 4.x gives us a map of list ids => subscriber objects
        foreach ($this->map as $list_id => $subscriber_data) {
            $list = $mailchimp->get_list($list_id);

            $string .= sprintf('<h4>%s</h4>', $list ? $list->name : 'Unknown list') . PHP_EOL . PHP_EOL;
            $string .= sprintf('<strong>%s</strong>: %s <br />', __('Email Address', 'mailchimp-for-wp'), $subscriber_data->email_address) . PHP_EOL;

            // merge fields
            foreach ($subscriber_data->merge_fields as $tag => $value) {
                $field = $this->get_field_by_tag($list_id, $tag);
                $string .= sprintf('<strong>%s</strong>: %s <br />', $field ? $field->name : $tag, $this->readable_value($value)) . PHP_EOL;
            }

            // interests
            foreach ($subscriber_data->interests as $interest_id => $interested) {
                if (!$interested) {
                    continue;
                }

                $interest_category = $this->get_interest_category_by_interest_or_category_id($list_id, $interest_id);
                if ($interest_category && isset($interest_category->interests[$interest_id])) {
                    $string .= sprintf('<strong>%s</strong>: %s <br />', $interest_category->title, $interest_category->interests[$interest_id]) . PHP_EOL;
                }
            }

            $string .= '<br />' . PHP_EOL . PHP_EOL;
        }

        if ($this->content_type === 'text/plain') {
            $string = strip_tags($string);
        }

        return $string;
    }
}
