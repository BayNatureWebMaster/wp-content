<?php

class MC4WP_Logging_Admin
{
    /**
     * Add hooks
     */
    public function add_hooks()
    {
        add_action('admin_init', [ $this, 'init' ]);

        add_filter('mc4wp_admin_menu_items', [ $this, 'menu_items' ]);
        add_action('mc4wp_dashboard_setup', [ $this, 'register_dashboard_widget' ]);
        add_action('mc4wp_admin_log_export', [ $this, 'run_log_exporter' ]);
        add_action('mc4wp_admin_log_empty', [ $this, 'run_log_empty' ]);
        add_action('mc4wp_admin_log_set_purge_schedule', [ $this, 'set_purge_schedule' ]);
        add_action('mc4wp_admin_enqueue_assets', [ $this, 'enqueue_assets' ]);
        add_action('mc4wp_admin_after_integration_settings', [ $this, 'show_link_to_integration_log' ], 60);
        add_filter('set-screen-option', [ $this, 'set_screen_option' ], 10, 3);
    }

    /**
     * TODO: Make this more pretty (UX)
     *
     * Add a link to log overview to each integration
     *
     * @param MC4WP_Integration $integration
     */
    public function show_link_to_integration_log(MC4WP_Integration $integration)
    {
        echo sprintf('<p><a href="%s">' . __('Show sign-ups that used this integration.', 'mailchimp-for-wp') . '</a></p>', admin_url('admin.php?page=mailchimp-for-wp-reports&tab=log&view=' . $integration->slug));
    }

    /**
     * Init
     *
     * @hooked `init`
     */
    public function init()
    {
        $this->run_upgrade_routines();
    }

    /**
     * Maybe run upgrade routines
     *
     * @return bool
     */
    protected function run_upgrade_routines()
    {
        $from_version = get_option('mc4wp_log_version', 0);
        $to_version = MC4WP_PREMIUM_VERSION;

        // run from URL variable so it's easy to re-run previous migrations
        if (isset($_GET['mc4wp_run_log_migration'])) {
            $from_version = $_GET['mc4wp_run_log_migration'];
        }

        // do we have a known version?
        if (empty($from_version)) {
            update_option('mc4wp_log_version', $to_version);
            return false;
        }

        // are we at or above specified version?
        if (version_compare($from_version, $to_version, '>=')) {
            return false;
        }

        $upgrade_routines = new MC4WP_Upgrade_Routines($from_version, $to_version, __DIR__ . '/migrations');
        $upgrade_routines->run();
        update_option('mc4wp_log_version', $to_version);
        return true;
    }

    /**
     * Enqueue assets for log pages.
     *
     * @param string $suffix
     */
    public function enqueue_assets($suffix = '')
    {
        $page = isset($_GET['page']) ? $_GET['page'] : '';
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'statistics';

        /* Reports page */
        if ($page === 'mailchimp-for-wp-reports' && $tab === 'statistics') {
            $assets_url = plugins_url('logging/assets', MC4WP_PREMIUM_PLUGIN_FILE);

            wp_register_script('mc4wp-admin-statistics', $assets_url . '/js/admin-statistics.js', [], MC4WP_PREMIUM_VERSION, true);
            wp_enqueue_script('mc4wp-admin-statistics');

            wp_enqueue_style('mc4wp-admin-reports', $assets_url . '/css/admin.css', [ 'mc4wp-admin' ], MC4WP_PREMIUM_VERSION);
        }

        if ($page === 'mailchimp-for-wp-reports' && ($tab === 'log' || $tab === 'statistics')) {
            wp_enqueue_style('mc4wp-admin-reports', plugins_url('logging/assets/css/admin.css', MC4WP_PREMIUM_PLUGIN_FILE), [ 'mc4wp-admin' ], MC4WP_PREMIUM_VERSION);
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
            'title' => __('Reports', 'mailchimp-for-wp'),
            'text' => __('Reports', 'mailchimp-for-wp'),
            'slug' => 'reports',
            'callback' => [ $this, 'show_reports' ],
            'load_callback' => [ $this, 'add_screen_options' ]
        ];

        return $items;
    }

    /**
     * Register dashboard widgets
     */
    public function register_dashboard_widget()
    {
        // only show widget to people with required capability
        if (! current_user_can('manage_options')) {
            return false;
        }

        wp_add_dashboard_widget(
            'mc4wp_log_widget',         // Widget slug.
            'Mailchimp Sign-Ups',         // Title.
            [ 'MC4WP_Dashboard_Log_Widget', 'make' ] // Display function.
        );
    }

    /**
     * Hooks into the `log_empty` action
     */
    public function run_log_empty()
    {
        $log = new MC4WP_Logger();
        $log->truncate();
    }

    /**
     * Run the log exporter
     */
    public function run_log_exporter()
    {
        $args = [];
        $request = array_merge($_POST, $_GET);

        if (! empty($request['start_year'])) {
            $start_year = absint($request['start_year']);
            $start_month = (isset($request['start_month'])) ? absint($request['start_month']) : 1;
            $timestring = sprintf('%s-%s', $start_year, $start_month);
            $args['datetime_after'] = date('Y-m-d 00:00:00', strtotime($timestring));
        }

        if (! empty($request['end_year'])) {
            $end_year = absint($request['end_year']);
            $end_month = (isset($request['end_month'])) ? absint($request['end_month']) : 12;
            $timestring = sprintf('%s-%s', $end_year, $end_month);
            $args['datetime_before'] = date('Y-m-t 23:59:59', strtotime($timestring));
        }

        $exporter = new MC4WP_Log_Exporter();
        $exporter->filter($args);
        $exporter->output();
        exit;
    }

    /**
     * Show reports page
     */
    public function show_reports()
    {
        $current_tab = ! empty($_GET['tab']) ? $_GET['tab'] : 'statistics';
        $tab_method = 'show_' . $current_tab . '_page';

        if (method_exists($this, $tab_method)) {
            call_user_func([ $this, $tab_method ]);
        }
    }

    /**
     * Show log page
     */
    public function show_advanced_page()
    {
        $options = get_option('mc4wp');
        if (empty($options['log_purge_days'])) {
            $options['log_purge_days'] = 365;
        }

        $current_tab = 'advanced';
        include dirname(__DIR__, 1) . '/views/admin-reports.php';
    }

    /**
     * Show log page
     */
    public function show_export_page()
    {
        $current_tab = 'export';
        include dirname(__DIR__, 1) . '/views/admin-reports.php';
    }

    /**
     * Show log page
     */
    public function show_log_page()
    {
        $mailchimp = new MC4WP_MailChimp();
        $table = new MC4WP_Log_Table($mailchimp);
        $current_tab = 'log';

        include dirname(__DIR__, 1) . '/views/admin-reports.php';
    }

    public function show_log_item_page()
    {
        $current_tab = 'log_item';

        $id = (int) $_GET['id'];
        $logger = new MC4WP_Logger();
        $item = $logger->find_by_id($id);

        if ($item) {
            $mailchimp = new MC4WP_MailChimp();
            $list = $mailchimp->get_list($item->list_id);
        } else {
            $current_tab = 'log_item_not_found';
        }

        include dirname(__DIR__, 1) . '/views/admin-reports.php';
    }

    private function get_interest_category_by_interest_id($list_id, $interest_id)
    {
        $mailchimp = new MC4WP_MailChimp();

        $interest_categories = $mailchimp->get_list_interest_categories($list_id);
        foreach ($interest_categories as $category) {
            if (isset($category->interests[$interest_id])) {
                return $category;
            }
        }

        return null;
    }

    /**
     * Show reports (stats) page
     */
    public function show_statistics_page()
    {
        $current_tab = 'statistics';

        if (!function_exists('wp_timezone')) {
            echo "You need WordPress version 5.3 or higher to view the reports page.";
            return;
        }

        $graph = new MC4WP_Graph($_GET);
        $graph->init();

        // add scripts
        wp_localize_script('mc4wp-admin-statistics', 'mc4wp_statistics_data', $graph->datasets);

        $start_day = (isset($_GET['start_day'])) ? $_GET['start_day'] : 0;
        $start_month = (isset($_GET['start_month'])) ? $_GET['start_month'] : 0;
        $start_year = (isset($_GET['start_year'])) ? $_GET['start_year'] : 0;
        $end_day = (isset($_GET['end_day'])) ? $_GET['end_day'] : date('j');
        $end_month = (isset($_GET['end_month'])) ? $_GET['end_month'] : date('n');
        $end_year = (isset($_GET['end_year'])) ? $_GET['end_year'] : date('Y');

        include dirname(__DIR__, 1) . '/views/admin-reports.php';
    }

    /**
     * Add screen options
     */
    public function add_screen_options()
    {
        // do nothing if not on log page
        if (empty($_GET['tab']) || $_GET['tab'] !== 'log') {
            return;
        }

        add_screen_option('per_page', [ 'default' => 20, 'option' => 'mc4wp_log_per_page' ]);
    }

    /**
     * @param $status
     * @param $option
     * @param $value
     *
     * @return int
     */
    public function set_screen_option($status, $option, $value)
    {
        if ('mc4wp_log_per_page' === $option) {
            return $value;
        }

        return $status;
    }

    /**
    * Set up the schedule to periodically delete all log items older than X days
    */
    public function set_purge_schedule()
    {
        $options = get_option('mc4wp', []);
        $options['log_purge_days'] = max(1, (int) $_POST['log_purge_days']);
        update_option('mc4wp', $options);
        _mc4wp_logging_schedule_purge_event();
    }
}
