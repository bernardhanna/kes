<?php

add_action('init', function () {
    register_extended_post_type('team', [
        'menu_icon'    => 'dashicons-groups',
        'supports'     => ['title', 'thumbnail', 'revisions', 'page-attributes'],
        'has_archive'  => false,
        'public'       => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'team'],
    ], [
        'singular' => 'Team member',
        'plural'   => 'Team',
        'slug'     => 'team',
    ]);

    register_extended_taxonomy('team_category', 'team', [
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'team-category'],
    ], [
        'singular' => 'Team category',
        'plural'   => 'Team categories',
        'slug'     => 'team-category',
    ]);
});

add_action('init', function () {
    if (term_exists('senior-management', 'team_category')) {
        return;
    }

    wp_insert_term(
        __('Senior Management', 'matrix-starter'),
        'team_category',
        ['slug' => 'senior-management']
    );
}, 20);
