<?php

if (is_admin() || (defined('DOING_CRON') && DOING_CRON) || (defined('WP_CLI') && WP_CLI)) {
    $api_url = 'https://my.mc4wp.com/api/v2';
    $plugin_slug = 'mc4wp-premium';
    $plugin_file = MC4WP_PREMIUM_PLUGIN_FILE;
    $plugin_version = MC4WP_PREMIUM_VERSION;

    require __DIR__ . '/class-admin.php';
    $admin = new MC4WP\Licensing\Admin($plugin_slug, $plugin_file, $plugin_version, $api_url);
    $admin->add_hooks();
}

register_deactivation_hook(MC4WP_PREMIUM_PLUGIN_FILE, function () {
    wp_clear_scheduled_hook('mc4wp_license_check');
});
