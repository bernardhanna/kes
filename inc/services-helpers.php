<?php
/**
 * Services landing page helpers (marketing page vs CPT archive).
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Page ID for the public Services landing page (slug: our-services).
 */
function matrix_starter_get_services_landing_page_id(): int
{
    static $page_id = null;

    if ($page_id !== null) {
        return $page_id;
    }

    $page    = get_page_by_path('our-services');
    $page_id = $page ? (int) $page->ID : 0;

    return $page_id;
}

/**
 * URL for the Services landing page (not the CPT archive at /services/).
 */
function matrix_starter_get_services_landing_url(): string
{
    $page_id = matrix_starter_get_services_landing_page_id();

    if ($page_id > 0) {
        return (string) get_permalink($page_id);
    }

    return home_url('/our-services/');
}

/**
 * Label for the Services breadcrumb / nav context.
 */
function matrix_starter_get_services_landing_label(): string
{
    $page_id = matrix_starter_get_services_landing_page_id();

    if ($page_id > 0) {
        $title = get_the_title($page_id);
        if ($title !== '') {
            return $title;
        }
    }

    $pto = get_post_type_object('services');

    return $pto ? $pto->labels->name : __('Services', 'matrix-starter');
}

/**
 * Redirect the services CPT archive (/services/) to the marketing landing page.
 */
add_action('template_redirect', 'matrix_starter_redirect_services_archive_to_landing');

function matrix_starter_redirect_services_archive_to_landing(): void
{
    if (is_admin() || ! is_post_type_archive('services')) {
        return;
    }

    $landing_url = matrix_starter_get_services_landing_url();

    if ($landing_url === '') {
        return;
    }

    wp_safe_redirect($landing_url, 301);
    exit;
}

/**
 * Normalized service card for the services_grid flexible block.
 *
 * @return array{
 *     image_id: int,
 *     title: string,
 *     title_tag: string,
 *     description: string,
 *     link: array{url: string, target: string, title?: string}|null,
 *     underline: string,
 *     width: string,
 * }
 */
function matrix_starter_services_grid_normalize_manual_item(array $service): array
{
    $image_raw = $service['image'] ?? 0;
    $image_id  = is_array($image_raw)
        ? (int) ($image_raw['ID'] ?? 0)
        : (int) $image_raw;

    $link = isset($service['link']) && is_array($service['link']) ? $service['link'] : null;

    return [
        'image_id'    => $image_id,
        'title'       => ! empty($service['title']) ? (string) $service['title'] : __('Service Title', 'matrix-starter'),
        'title_tag'   => ! empty($service['title_tag']) ? (string) $service['title_tag'] : 'h3',
        'description' => ! empty($service['description'])
            ? (string) $service['description']
            : __('Service description goes here.', 'matrix-starter'),
        'link'        => $link,
        'underline'   => ! empty($service['underline_color']) ? (string) $service['underline_color'] : '#00ACD8',
        'width'       => isset($service['width']) && $service['width'] === 'full' ? 'full' : 'half',
    ];
}

/**
 * Build services_grid cards from published service posts (excerpt as description).
 *
 * @return list<array<string, mixed>>
 */
function matrix_starter_services_grid_get_all_items(string $orderby = 'menu_order', string $order = 'ASC', string $width = 'full'): array
{
    $orderby = in_array($orderby, ['menu_order', 'title', 'date'], true) ? $orderby : 'menu_order';
    $order   = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
    $width   = $width === 'half' ? 'half' : 'full';

    $query = new WP_Query([
        'post_type'              => 'services',
        'post_status'            => 'publish',
        'posts_per_page'         => -1,
        'orderby'                => $orderby,
        'order'                  => $order,
        'no_found_rows'          => true,
        'update_post_meta_cache' => true,
        'update_post_term_cache' => false,
    ]);

    if (! $query->have_posts()) {
        wp_reset_postdata();

        return [];
    }

    $items = [];

    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();

        $excerpt = get_the_excerpt($post_id);
        if ($excerpt === '') {
            $description = '';
        } else {
            $description = '<p>' . esc_html($excerpt) . '</p>';
        }

        $items[] = [
            'image_id'    => (int) get_post_thumbnail_id($post_id),
            'title'       => get_the_title($post_id),
            'title_tag'   => 'h3',
            'description' => $description,
            'link'        => [
                'url'    => get_permalink($post_id),
                'target' => '_self',
                'title'  => get_the_title($post_id),
            ],
            'underline'   => '#00ACD8',
            'width'       => $width,
        ];
    }

    wp_reset_postdata();

    return $items;
}

/**
 * Resolve services_grid block items from ACF sub fields.
 *
 * @return list<array<string, mixed>>
 */
function matrix_starter_services_grid_resolve_items(): array
{
    $source = get_sub_field('services_source');
    if (! is_string($source) || $source === '') {
        $manual = get_sub_field('services');
        $source = (! empty($manual) && is_array($manual)) ? 'manual' : 'all';
    }

    if ($source === 'all') {
        $orderby = get_sub_field('all_orderby');
        $order   = get_sub_field('all_order');
        $width   = get_sub_field('all_card_width');

        return matrix_starter_services_grid_get_all_items(
            is_string($orderby) && $orderby !== '' ? $orderby : 'menu_order',
            is_string($order) && $order !== '' ? $order : 'ASC',
            is_string($width) && $width !== '' ? $width : 'full',
        );
    }

    $manual = get_sub_field('services');
    if (empty($manual) || ! is_array($manual)) {
        return [];
    }

    return array_map('matrix_starter_services_grid_normalize_manual_item', $manual);
}
