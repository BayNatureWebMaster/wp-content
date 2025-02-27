<?php

/*
Mailchimp User Sync
Copyright (C) 2015 - 2025, Danny van Kooten, hi@dannyvankooten.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace MC4WP\User_Sync;

use MC4WP_Queue;
use WP_CLI;

defined('ABSPATH') or exit;

function _mc4wp_bootstrap_user_sync()
{
    define('MC4WP_USER_SYNC_PLUGIN_FILE', __FILE__);
    define('MC4WP_USER_SYNC_PLUGIN_DIR', __DIR__);
    define('MC4WP_USER_SYNC_PLUGIN_VERSION', MC4WP_PREMIUM_VERSION);

    require __DIR__ . '/src/functions.php';

    $settings = get_settings();

    $container = mc4wp();
    $container['user_sync.users'] = function () use ($settings) {
        require __DIR__ . '/src/class-users.php';
        require __DIR__ . '/src/class-tools.php';
        return new Users($settings['list'], $settings['role'], $settings['field_map'], new Tools());
    };
    $container['user_sync.queue'] = function () {
        return new MC4WP_Queue('mc4wp_sync_queue');
    };
    $container['user_sync.handler'] = function () use ($settings, $container) {
        require __DIR__ . '/src/class-user-handler.php';
        return new User_Handler($settings['list'], $container['user_sync.users']);
    };

    if (is_admin()) {
        require __DIR__ . '/src/class-admin.php';
        $admin = new Admin($settings);
        $admin->add_hooks();
    }

    require __DIR__ . '/src/class-webhook-listener.php';
    $webhook_listener = new Webhook_Listener($settings);
    $webhook_listener->add_hooks();

    // If User Sync is not enabled or no Mailchimp list is selected, return here to prevent unnecessary work.
    if (!$settings['enabled'] || $settings['list'] === '') {
        return;
    }

    require __DIR__ . '/src/default-filters.php';

    /** @var User_Handler $user_handler */
    $user_handler = $container['user_sync.handler'];
    $user_handler->add_hooks();

    /** @var MC4WP_Queue $queue */
    $queue = $container['user_sync.queue'];

    /** @var Users $users */
    $users = $container['user_sync.users'];

    require __DIR__ . '/src/class-observer.php';
    $observer = new Observer($queue, $users);
    $observer->add_hooks();

    require __DIR__ . '/src/class-worker.php';
    $worker = new Worker($queue, $user_handler);
    $worker->add_hooks();

    // deactivate old Mailchimp Sync plugin if active
    if (is_admin() && defined('MAILCHIMP_SYNC_VERSION')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        deactivate_plugins('mailchimp-sync/mailchimp-sync.php');
    }

    if (is_admin() && (defined('DOING_AJAX') && DOING_AJAX)) {
        require __DIR__ . '/src/class-ajax-listener.php';
        $ajax = new Ajax_Listener($user_handler, $users);
        $ajax->add_hooks();
    }

    // WP CLI Commands
    if (defined('WP_CLI') && WP_CLI) {
        require __DIR__ . '/src/class-cli-command.php';
        WP_CLI::add_command('mc4wp-user-sync', 'MC4WP\\User_Sync\\CLI_Command');
    }
}

_mc4wp_bootstrap_user_sync();
register_activation_hook(MC4WP_PREMIUM_PLUGIN_FILE, 'MC4WP\User_Sync\setup_schedule');
register_deactivation_hook(MC4WP_PREMIUM_PLUGIN_FILE, 'MC4WP\User_Sync\clear_schedule');
