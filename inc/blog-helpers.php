<?php
/**
 * Blog index helpers (sorting).
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Allowed blog sort keys.
 *
 * @return string[]
 */
function matrix_starter_blog_sort_keys(): array
{
    return ['date-desc', 'date-asc', 'title-asc', 'title-desc'];
}

/**
 * Human-readable labels for sort options.
 *
 * @return array<string, string>
 */
function matrix_starter_blog_sort_options(): array
{
    return [
        'date-desc'  => __('Newest first', 'matrix-starter'),
        'date-asc'   => __('Oldest first', 'matrix-starter'),
        'title-asc'  => __('A – Z', 'matrix-starter'),
        'title-desc' => __('Z – A', 'matrix-starter'),
    ];
}

/**
 * Current sort from the query string (validated).
 */
function matrix_starter_get_blog_sort(): string
{
    $sort    = isset($_GET['sort']) ? sanitize_key(wp_unslash($_GET['sort'])) : 'date-desc';
    $allowed = matrix_starter_blog_sort_keys();

    return in_array($sort, $allowed, true) ? $sort : 'date-desc';
}

/**
 * Apply sort to a WP_Query posts array.
 *
 * @param array<string, mixed> $args
 * @return array<string, mixed>
 */
function matrix_starter_apply_blog_sort_to_query_args(array $args, ?string $sort = null): array
{
    $sort = $sort ?? matrix_starter_get_blog_sort();

    switch ($sort) {
        case 'date-asc':
            $args['orderby'] = 'date';
            $args['order']   = 'ASC';
            break;
        case 'title-asc':
            $args['orderby'] = 'title';
            $args['order']   = 'ASC';
            break;
        case 'title-desc':
            $args['orderby'] = 'title';
            $args['order']   = 'DESC';
            break;
        default:
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
            break;
    }

    return $args;
}

/**
 * URL for a blog sort option (resets pagination).
 */
function matrix_starter_blog_sort_url(string $sort): string
{
    if (! in_array($sort, matrix_starter_blog_sort_keys(), true)) {
        $sort = 'date-desc';
    }

    $blog_page_id = (int) get_option('page_for_posts');
    $base         = $blog_page_id > 0 ? get_permalink($blog_page_id) : home_url('/');

    if (! $base) {
        $base = home_url('/');
    }

    $url = remove_query_arg(['paged', 'page'], $base);
    $url = add_query_arg('sort', $sort, $url);

    return $url;
}

/**
 * Preserve sort in blog pagination links.
 *
 * @param string $result Pagination URL.
 */
function matrix_starter_blog_pagenum_link_with_sort(string $result): string
{
    $sort = matrix_starter_get_blog_sort();

    if ($sort === 'date-desc') {
        return remove_query_arg('sort', $result);
    }

    return add_query_arg('sort', $sort, $result);
}

/**
 * Categories shown on blog card badges (excludes default / uncategorized).
 *
 * @return \WP_Term[]
 */
function matrix_starter_get_post_badge_categories(int $post_id = 0, int $limit = 2): array
{
    $post_id    = $post_id ?: get_the_ID();
    $categories = get_the_category($post_id);

    if (empty($categories) || is_wp_error($categories)) {
        return [];
    }

    $exclude_ids = array_values(array_unique(array_filter(array_map('intval', [
        get_cat_ID('All') ?: 0,
        (int) get_option('default_category'),
        ($uncat = get_category_by_slug('uncategorized')) ? (int) $uncat->term_id : 0,
    ]))));

    $filtered = array_values(array_filter(
        $categories,
        static function ($cat) use ($exclude_ids) {
            return ! in_array((int) $cat->term_id, $exclude_ids, true);
        }
    ));

    usort($filtered, static function ($a, $b) {
        return (int) $a->term_id <=> (int) $b->term_id;
    });

    return array_slice($filtered, 0, max(1, $limit));
}

/**
 * 301 /blognews/ → posts page (/news/) so old URLs do not 404.
 */
add_action('template_redirect', 'matrix_starter_redirect_legacy_blognews_url', 0);

function matrix_starter_redirect_legacy_blognews_url(): void
{
    if (is_admin() || wp_doing_ajax() || wp_doing_cron()) {
        return;
    }

    $path = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    if ($path !== 'blognews' && ! str_starts_with($path, 'blognews/')) {
        return;
    }

    $blog_page_id = (int) get_option('page_for_posts');
    $target       = $blog_page_id > 0 ? get_permalink($blog_page_id) : home_url('/news/');
    if (! $target) {
        $target = home_url('/news/');
    }

    // Preserve path after /blognews/ (e.g. pagination) and query string.
    $suffix = '';
    if (str_starts_with($path, 'blognews/')) {
        $suffix = substr($path, strlen('blognews'));
    }
    $dest = untrailingslashit($target) . $suffix . '/';
    $query = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY);
    if (is_string($query) && $query !== '') {
        $dest .= '?' . $query;
    }

    wp_safe_redirect($dest, 301);
    exit;
}
