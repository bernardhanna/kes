<?php
/**
 * Team CPT and team_001 block helpers.
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * @return array{image: int, name: string, job_title: string, description: string}
 */
function matrix_starter_team_normalize_manual_member(array $member): array
{
    $image_raw = $member['image'] ?? 0;
    $image_id  = is_array($image_raw)
        ? (int) ($image_raw['ID'] ?? 0)
        : (int) $image_raw;

    return [
        'image'       => $image_id,
        'name'        => ! empty($member['name']) ? (string) $member['name'] : '',
        'job_title'   => ! empty($member['job_title']) ? (string) $member['job_title'] : '',
        'description' => ! empty($member['description']) ? (string) $member['description'] : '',
    ];
}

/**
 * @return array{image: int, name: string, job_title: string, description: string}
 */
function matrix_starter_team_normalize_post(WP_Post $post): array
{
    $post_id = (int) $post->ID;
    $bio     = function_exists('get_field') ? (string) (get_field('bio', $post_id) ?: '') : '';

    if ($bio === '' && $post->post_content !== '') {
        $bio = apply_filters('the_content', $post->post_content);
    }

    return [
        'image'       => (int) get_post_thumbnail_id($post_id),
        'name'        => get_the_title($post_id),
        'job_title'   => function_exists('get_field') ? (string) (get_field('job_title', $post_id) ?: '') : '',
        'description' => $bio,
    ];
}

/**
 * @return list<array{image: int, name: string, job_title: string, description: string}>
 */
function matrix_starter_team_get_all_members(string $orderby = 'menu_order', string $order = 'ASC', int $category_id = 0, array $exclude_ids = []): array
{
    $orderby = in_array($orderby, ['menu_order', 'title', 'date'], true) ? $orderby : 'menu_order';
    $order   = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

    $exclude_ids = array_values(array_filter(array_map('intval', $exclude_ids), static fn (int $id): bool => $id > 0));

    $args = [
        'post_type'              => 'team',
        'post_status'            => 'publish',
        'posts_per_page'         => -1,
        'orderby'                => $orderby,
        'order'                  => $order,
        'no_found_rows'          => true,
        'update_post_meta_cache' => true,
        'update_post_term_cache' => true,
    ];

    if ($exclude_ids !== []) {
        $args['post__not_in'] = $exclude_ids;
    }

    if ($category_id > 0) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'team_category',
                'field'    => 'term_id',
                'terms'    => [$category_id],
            ],
        ];
    }

    $query = new WP_Query($args);

    if (! $query->have_posts()) {
        wp_reset_postdata();

        return [];
    }

    $items = [];

    while ($query->have_posts()) {
        $query->the_post();
        $post = get_post();
        if ($post instanceof WP_Post) {
            $items[] = matrix_starter_team_normalize_post($post);
        }
    }

    wp_reset_postdata();

    return $items;
}

/**
 * @param array<int, WP_Post|int|object>|null $posts
 * @return list<int>
 */
function matrix_starter_team_collect_post_ids(?array $posts): array
{
    if (empty($posts) || ! is_array($posts)) {
        return [];
    }

    $ids = [];

    foreach ($posts as $row) {
        $post_id = is_object($row) ? (int) ($row->ID ?? 0) : (int) $row;
        if ($post_id > 0) {
            $ids[] = $post_id;
        }
    }

    return $ids;
}

/**
 * @param array<int, WP_Post|int|object> $posts
 * @return list<array{image: int, name: string, job_title: string, description: string}>
 */
function matrix_starter_team_members_from_posts(array $posts): array
{
    $items = [];

    foreach ($posts as $row) {
        $post_id = is_object($row) ? (int) ($row->ID ?? 0) : (int) $row;
        if ($post_id <= 0) {
            continue;
        }

        $post = get_post($post_id);
        if (! $post instanceof WP_Post || $post->post_type !== 'team' || $post->post_status !== 'publish') {
            continue;
        }

        $items[] = matrix_starter_team_normalize_post($post);
    }

    return $items;
}

/**
 * Resolve team_001 block members from ACF sub fields.
 *
 * @return list<array{image: int, name: string, job_title: string, description: string}>
 */
function matrix_starter_team_001_resolve_members(): array
{
    $source = get_sub_field('team_source');
    if (! is_string($source) || $source === '') {
        $manual = get_sub_field('team_members');
        $picked = get_sub_field('selected_team');
        if (! empty($manual) && is_array($manual)) {
            $source = 'manual';
        } elseif (! empty($picked) && is_array($picked)) {
            $source = 'selected';
        } else {
            $source = 'all';
        }
    }

    if ($source === 'manual') {
        $manual = get_sub_field('team_members');
        if (empty($manual) || ! is_array($manual)) {
            return [];
        }

        return array_map('matrix_starter_team_normalize_manual_member', $manual);
    }

    if ($source === 'selected') {
        $picked = get_sub_field('selected_team');

        return is_array($picked) ? matrix_starter_team_members_from_posts($picked) : [];
    }

    $category_raw = get_sub_field('all_team_category');
    $category_id  = 0;
    if (is_object($category_raw) && isset($category_raw->term_id)) {
        $category_id = (int) $category_raw->term_id;
    } elseif (is_numeric($category_raw)) {
        $category_id = (int) $category_raw;
    }

    $orderby = get_sub_field('all_orderby');
    $order   = get_sub_field('all_order');
    $excluded = get_sub_field('excluded_team');

    return matrix_starter_team_get_all_members(
        is_string($orderby) && $orderby !== '' ? $orderby : 'menu_order',
        is_string($order) && $order !== '' ? $order : 'ASC',
        $category_id,
        matrix_starter_team_collect_post_ids(is_array($excluded) ? $excluded : null),
    );
}

/**
 * Create or update a team CPT post from a manual repeater row.
 */
function matrix_starter_team_upsert_from_manual_row(array $member, string $category_slug = 'senior-management'): int
{
    $name = trim((string) ($member['name'] ?? ''));
    if ($name === '') {
        return 0;
    }

    $slug     = sanitize_title($name);
    $existing = get_page_by_path($slug, OBJECT, 'team');
    $post_id  = $existing instanceof WP_Post ? (int) $existing->ID : 0;

    $postarr = [
        'post_type'   => 'team',
        'post_title'  => $name,
        'post_name'   => $slug,
        'post_status' => 'publish',
    ];

    if ($post_id > 0) {
        $postarr['ID'] = $post_id;
        $result        = wp_update_post($postarr, true);
    } else {
        $result = wp_insert_post($postarr, true);
    }

    if (is_wp_error($result) || ! $result) {
        return 0;
    }

    $post_id = (int) $result;

    if (function_exists('update_field')) {
        if (! empty($member['job_title'])) {
            update_field('job_title', (string) $member['job_title'], $post_id);
        }
        if (! empty($member['description'])) {
            update_field('bio', (string) $member['description'], $post_id);
        }
    }

    $image_raw = $member['image'] ?? 0;
    $image_id  = is_array($image_raw)
        ? (int) ($image_raw['ID'] ?? 0)
        : (int) $image_raw;
    if ($image_id > 0) {
        set_post_thumbnail($post_id, $image_id);
    }

    if ($category_slug !== '') {
        $term = get_term_by('slug', $category_slug, 'team_category');
        if ($term && ! is_wp_error($term)) {
            wp_set_object_terms($post_id, [(int) $term->term_id], 'team_category', false);
        }
    }

    return $post_id;
}
