<?php
/**
 * Seed sample Locations for testing the hero map slide.
 * Runs once when no locations exist; creates a few European cities.
 */
if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    // Only run when locations CPT is registered and we have none
    if (!post_type_exists('locations')) {
        return;
    }
    $count = wp_count_posts('locations');
    $published = isset($count->publish) ? (int) $count->publish : 0;
    if ($published > 0) {
        return;
    }

    $samples = [
        [
            'title'   => 'Dublin',
            'lat'     => 53.349805,
            'lng'     => -6.26031,
            'address' => 'Dublin, Ireland',
        ],
        [
            'title'   => 'London',
            'lat'     => 51.5074,
            'lng'     => -0.1278,
            'address' => 'London, United Kingdom',
        ],
        [
            'title'   => 'Paris',
            'lat'     => 48.8566,
            'lng'     => 2.3522,
            'address' => 'Paris, France',
        ],
        [
            'title'   => 'Berlin',
            'lat'     => 52.52,
            'lng'     => 13.405,
            'address' => 'Berlin, Germany',
        ],
        [
            'title'   => 'Copenhagen',
            'lat'     => 55.6761,
            'lng'     => 12.5683,
            'address' => 'Copenhagen, Denmark',
        ],
        [
            'title'   => 'Warsaw',
            'lat'     => 52.2297,
            'lng'     => 21.0122,
            'address' => 'Warsaw, Poland',
        ],
    ];

    foreach ($samples as $s) {
        $post_id = wp_insert_post([
            'post_type'   => 'locations',
            'post_title'  => $s['title'],
            'post_status' => 'publish',
            'post_author' => 1,
        ], true);
        if (is_wp_error($post_id)) {
            continue;
        }
        update_post_meta($post_id, 'latitude', $s['lat']);
        update_post_meta($post_id, 'longitude', $s['lng']);
        if (!empty($s['address'])) {
            update_post_meta($post_id, 'address', $s['address']);
        }
    }
}, 20);
