<?php
// In your post-types setup (alongside the CPT registration).
add_action('init', function() {
    // Services CPT (already provided, included here for completeness)
    register_extended_post_type('services', [
        'menu_icon'    => 'dashicons-editor-help',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'services'],
        'show_in_rest' => true,
    ], [
        'singular' => 'Service',
        'plural'   => 'Services',
        'slug'     => 'services'
    ]);

    // Service Categories taxonomy
    register_extended_taxonomy('service_category', 'services', [
        'hierarchical' => true,
        'show_ui'      => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'service-category'],
    ], [
        'singular' => 'Service Category',
        'plural'   => 'Service Categories',
        'slug'     => 'service-category',
    ]);
});
