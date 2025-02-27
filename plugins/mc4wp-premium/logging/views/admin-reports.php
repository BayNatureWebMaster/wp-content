<?php defined('ABSPATH') or exit;

$tabs = [
    'statistics' => __('Statistics', 'mailchimp-for-wp'),
    'log' => __('Log', 'mailchimp-for-wp'),
    'export' => __("Export", 'mailchimp-for-wp'),
    'advanced' => __('Advanced', 'mailchimp-for-wp'),
];
?>
<div id="mc4wp-admin" class="wrap reports">

    <h1 class="mc4wp-page-title">
        <?php _e('Mailchimp for WordPress', 'mailchimp-for-wp'); ?>: <?php _e('Reports', 'mailchimp-for-wp'); ?>
    </h1>

    <?php settings_errors(); ?>

    <h2 class="nav-tab-wrapper">
        <?php foreach ($tabs as $tab => $name) {
            echo sprintf('<a href="%s" class="nav-tab %s">%s</a>', admin_url('admin.php?page=mailchimp-for-wp-reports&tab=' . $tab), $current_tab === $tab ? 'nav-tab-active' : '', $name);
        } ?>
    </h2>

    <br class="clear" />

    <?php

    $tab_file = __DIR__ . "/admin-reports-{$current_tab}.php";

    if (file_exists($tab_file)) {
        include $tab_file;
    }

    ?>

</div>
