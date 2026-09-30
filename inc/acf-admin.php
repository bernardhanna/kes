<?php
/**
 * Theme-controlled visibility of plugin menus in wp-admin.
 */

if (! defined('ABSPATH')) {
    exit;
}

function matrix_starter_admin_ui_option_enabled(string $field): bool
{
    if (! function_exists('get_field')) {
        return false;
    }

    return (bool) get_field($field, 'option');
}

add_filter('acf/settings/show_admin', function ($show_admin) {
    if (matrix_starter_admin_ui_option_enabled('hide_acf_admin_ui')) {
        return false;
    }

    return $show_admin;
});

add_action('admin_menu', function () {
    if (matrix_starter_admin_ui_option_enabled('hide_wp_mail_smtp_menu')) {
        remove_menu_page('wp-mail-smtp');
    }
}, 999);

add_action('network_admin_menu', function () {
    if (matrix_starter_admin_ui_option_enabled('hide_wp_mail_smtp_menu')) {
        remove_menu_page('wp-mail-smtp');
    }
}, 999);
