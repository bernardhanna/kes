<?php
// Requires johnbillion/extended-cpts (same helper you’re already using).
add_action('init', function () {
    // Projects CPT
    register_extended_post_type('projects', [
        'menu_icon'    => 'dashicons-portfolio',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'projects'],
        'show_in_rest' => true,
    ], [
        'singular' => 'Project',
        'plural'   => 'Projects',
        'slug'     => 'projects',
    ]);

    // Project Categories (hierarchical, like regular post categories)
    register_extended_taxonomy('project_category', 'projects', [
        'hierarchical' => true,
        'show_ui'      => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'project-category'],
    ], [
        'singular' => 'Project Category',
        'plural'   => 'Project Categories',
        'slug'     => 'project-category',
    ]);
});
