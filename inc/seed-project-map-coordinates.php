<?php
/**
 * One-time seeding for project map coordinates.
 *
 * Assigns random coordinates + location labels to current projects when missing.
 * Regions used: Dublin, Cork, Germany, Poland, Netherlands.
 */

add_action('init', function () {
    $flag_key = 'matrix_project_coords_seeded_v1';
    if (get_option($flag_key)) {
        return;
    }

    $project_ids = get_posts([
        'post_type'      => 'projects',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'orderby'        => 'date',
        'order'          => 'ASC',
    ]);

    if (empty($project_ids)) {
        return;
    }

    $regions = [
        ['label' => 'Dublin, Ireland',     'lat_min' => 53.2800, 'lat_max' => 53.4200, 'lng_min' => -6.4200, 'lng_max' => -6.1000],
        ['label' => 'Cork, Ireland',       'lat_min' => 51.8400, 'lat_max' => 51.9500, 'lng_min' => -8.6000, 'lng_max' => -8.3800],
        ['label' => 'Germany',             'lat_min' => 52.4500, 'lat_max' => 52.6000, 'lng_min' => 13.3000, 'lng_max' => 13.5500],
        ['label' => 'Poland',              'lat_min' => 52.1500, 'lat_max' => 52.3100, 'lng_min' => 20.9000, 'lng_max' => 21.1000],
        ['label' => 'Netherlands',         'lat_min' => 52.3000, 'lat_max' => 52.4200, 'lng_min' => 4.7500,  'lng_max' => 4.9800],
    ];

    $region_count = count($regions);
    $seeded_any   = false;
    $idx          = 0;

    foreach ($project_ids as $pid) {
        $existing_lat = get_post_meta($pid, 'map_latitude', true);
        $existing_lng = get_post_meta($pid, 'map_longitude', true);

        // Keep existing coordinates intact.
        if ($existing_lat !== '' && $existing_lng !== '') {
            continue;
        }

        $region = $regions[$idx % $region_count];
        $idx++;

        $lat = $region['lat_min'] + (mt_rand() / mt_getrandmax()) * ($region['lat_max'] - $region['lat_min']);
        $lng = $region['lng_min'] + (mt_rand() / mt_getrandmax()) * ($region['lng_max'] - $region['lng_min']);

        update_post_meta($pid, 'map_latitude', number_format($lat, 6, '.', ''));
        update_post_meta($pid, 'map_longitude', number_format($lng, 6, '.', ''));

        $existing_location = get_post_meta($pid, 'project_location', true);
        if ($existing_location === '') {
            update_post_meta($pid, 'project_location', $region['label']);
        }

        $seeded_any = true;
    }

    if ($seeded_any) {
        update_option($flag_key, 1, false);
    }
}, 99);

