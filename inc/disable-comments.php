<?php
/**
 * Disable comments site-wide and remove from wp-admin.
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Empty comment counts (matches wp_count_comments() return shape).
 */
function matrix_starter_zero_comment_counts(): stdClass
{
    return (object) [
        'approved'       => 0,
        'moderated'      => 0,
        'spam'           => 0,
        'trash'          => 0,
        'post-trashed'   => 0,
        'total_comments' => 0,
        'all'            => 0,
    ];
}

add_filter('wp_count_comments', function ($counts, $post_id) {
    return matrix_starter_zero_comment_counts();
}, 10, 2);

add_action('admin_init', function () {
    global $pagenow;

    if ($pagenow === 'edit-comments.php' || $pagenow === 'comment.php') {
        wp_safe_redirect(admin_url());
        exit;
    }

    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
    remove_submenu_page('options-general.php', 'options-discussion.php');
}, 999);

add_action('add_admin_bar_menus', function () {
    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
}, 0);

add_action('admin_bar_menu', function ($wp_admin_bar) {
    if ($wp_admin_bar instanceof WP_Admin_Bar) {
        $wp_admin_bar->remove_node('comments');
    }
}, 999);

add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);
