<?php
/**
 * HTML sitemap helpers.
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * @return array{label: string, url: string, children?: array}
 */
function matrix_starter_sitemap_link(string $label, string $url, array $children = []): array
{
    $item = [
        'label' => $label,
        'url'   => $url,
    ];

    if ($children !== []) {
        $item['children'] = $children;
    }

    return $item;
}

/**
 * Append CPT posts to an existing section (matched by title), skipping duplicate URLs.
 *
 * @param array<int, array{title: string, links: array}> $sections
 */
function matrix_starter_sitemap_merge_cpt_posts(array &$sections, string $title_match, string $post_type, array &$added_urls): bool
{
    foreach ($sections as &$section) {
        if (strcasecmp((string) ($section['title'] ?? ''), $title_match) !== 0) {
            continue;
        }

        $posts = get_posts([
            'post_type'      => $post_type,
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        ]);

        foreach ($posts as $post) {
            $url = get_permalink($post);
            if (! $url || isset($added_urls[$url])) {
                continue;
            }

            $added_urls[$url]              = true;
            $section['links'][]            = matrix_starter_sitemap_link(get_the_title($post), $url);
        }

        return true;
    }

    unset($section);

    return false;
}

/**
 * @return array<int, array{title: string, links: array}>
 */
function matrix_starter_get_sitemap_sections(): array
{
    $sections      = [];
    $added_urls    = [];

    $remember = static function (array $item) use (&$added_urls): array {
        $added_urls[$item['url']] = true;

        return $item;
    };

    $remember_many = static function (array $links) use ($remember): array {
        return array_map($remember, $links);
    };

    // Primary navigation columns.
    if (class_exists(\Log1x\Navi\Navi::class)) {
        $navigation = \Log1x\Navi\Navi::make()->build('primary');

        if ($navigation->isNotEmpty()) {
            foreach ($navigation->toArray() as $item) {
                $classes = (string) ($item->classes ?? '');
                if (str_contains($classes, 'request-call')) {
                    continue;
                }

                $links = [];
                if (! empty($item->url)) {
                    $links[] = matrix_starter_sitemap_link((string) $item->label, (string) $item->url);
                }

                if (! empty($item->children)) {
                    foreach ($item->children as $child) {
                        if (empty($child->url)) {
                            continue;
                        }
                        $links[] = matrix_starter_sitemap_link((string) $child->label, (string) $child->url);
                    }
                }

                if ($links === []) {
                    continue;
                }

                $sections[] = [
                    'title' => (string) $item->label,
                    'links' => $remember_many($links),
                ];
            }
        }
    }

    // Enrich nav columns with CPT entries (avoid duplicate sections).
    if (post_type_exists('services')) {
        if (! matrix_starter_sitemap_merge_cpt_posts($sections, __('Services', 'matrix-starter'), 'services', $added_urls)) {
            foreach ($sections as &$section) {
                if (stripos((string) ($section['title'] ?? ''), 'service') !== false) {
                    matrix_starter_sitemap_merge_cpt_posts($sections, (string) $section['title'], 'services', $added_urls);
                    break;
                }
            }
            unset($section);
        }
    }

    if (post_type_exists('projects')) {
        matrix_starter_sitemap_merge_cpt_posts($sections, __('Projects', 'matrix-starter'), 'projects', $added_urls);
    }

    if (post_type_exists('jobs')) {
        matrix_starter_sitemap_merge_cpt_posts($sections, __('Careers', 'matrix-starter'), 'jobs', $added_urls);
    }

    // Legal / utility pages not already listed.
    $utility_slugs = ['privacy-notice', 'cookie-policy', 'accessibility', 'accessibility-2', 'frequently-asked-questions', 'sitemap'];
    $utility_links = [];

    foreach ($utility_slugs as $slug) {
        $page = get_page_by_path($slug);
        if (! $page || $page->post_status !== 'publish') {
            continue;
        }

        $url = get_permalink($page);
        if (isset($added_urls[$url])) {
            continue;
        }

        $utility_links[] = $remember(matrix_starter_sitemap_link(get_the_title($page), $url));
    }

    if ($utility_links !== []) {
        $sections[] = [
            'title' => __('Legal & information', 'matrix-starter'),
            'links' => $utility_links,
        ];
    }

    return $sections;
}

/**
 * @param array<int, array{label: string, url: string, children?: array}> $links
 */
function matrix_starter_render_sitemap_links(array $links, int $depth = 0): void
{
    if ($links === []) {
        return;
    }

    $list_class = $depth === 0
        ? 'flex flex-col gap-3 p-0 m-0 list-none'
        : 'flex flex-col gap-2 p-0 mt-2 ml-4 list-none border-l border-[#EAECF0] pl-4';

    $link_class = 'font-red-hat-text text-base font-normal leading-5 text-[#344054] transition-colors hover:text-[#00ACD8] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#00ACD8] focus-visible:ring-offset-2';

    echo '<ul class="' . esc_attr($list_class) . '">';

    foreach ($links as $link) {
        if (empty($link['url'])) {
            continue;
        }

        echo '<li>';
        printf(
            '<a href="%s" class="%s">%s</a>',
            esc_url($link['url']),
            esc_attr($link_class),
            esc_html($link['label'] ?? '')
        );

        if (! empty($link['children']) && is_array($link['children'])) {
            matrix_starter_render_sitemap_links($link['children'], $depth + 1);
        }

        echo '</li>';
    }

    echo '</ul>';
}
