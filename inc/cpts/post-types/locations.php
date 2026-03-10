<?php
/**
 * Locations CPT – map markers for hero map slide and other map blocks.
 */
add_action('init', function () {
    register_extended_post_type('locations', [
        'menu_icon'    => 'dashicons-location-alt',
        'supports'     => ['title', 'editor'],
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'locations'],
        'show_in_rest' => true,
    ], [
        'singular' => 'Location',
        'plural'   => 'Locations',
        'slug'     => 'locations',
    ]);
});
