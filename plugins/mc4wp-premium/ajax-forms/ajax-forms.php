<?php

defined('ABSPATH') or exit;

// main functionality
require __DIR__ . '/includes/class-ajax-forms.php';
$ajax_forms = new MC4WP_AJAX_Forms(__FILE__);

if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) {
    require __DIR__ . '/includes/class-admin.php';
    $admin = new MC4WP_AJAX_Forms_Admin();
}
