<?php
// Jobs CPT (unchanged except supports)
add_action('init', function() {
    register_extended_post_type('jobs', [
        'menu_icon'   => 'dashicons-editor-help',
        'supports'    => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions'],
        'has_archive' => true,
        'rewrite'     => ['slug' => 'jobs'],
        'show_in_rest'=> true,
    ], [
        'singular' => 'Job',
        'plural'   => 'Jobs',
        'slug'     => 'jobss', // If this was accidental, change to 'jobs' and re-save permalinks.
    ]);
});

// Job Categories taxonomy
add_action('init', function () {
    register_extended_taxonomy('job_category', 'jobs', [
        'hierarchical'       => true,           // behaves like Categories
        'show_ui'            => true,           // show taxonomy UI
        'show_in_menu'       => true,
        'show_admin_column'  => true,           // column in list table
        'show_in_quick_edit' => true,
        'show_in_rest'       => true,           // Gutenberg sidebar panel
        'rewrite'            => ['slug' => 'job-category'],
        // Key bit: use the default hierarchical meta box (with "Add New")
        // You can also set 'meta_box' => 'checkbox' explicitly if you prefer.
        // Remove 'meta_box' => 'simple' (that UI is limited).
        // 'meta_box' => 'checkbox',
    ], [
        'singular' => 'Job Category',
        'plural'   => 'Job Categories',
        'slug'     => 'job-category',
    ]);
});
