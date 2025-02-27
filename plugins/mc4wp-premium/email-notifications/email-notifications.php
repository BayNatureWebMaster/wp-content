<?php

defined('ABSPATH') or exit;

require __DIR__ . '/includes/class-factory.php';
$factory = new MC4WP_Form_Notification_Factory();
$factory->add_hooks();

if (is_admin() && (! defined('DOING_AJAX') || ! DOING_AJAX)) {
    require __DIR__ . '/includes/class-admin.php';
    $admin = new MC4WP_Form_Notifications_Admin(__FILE__);
    $admin->add_hooks();
}
