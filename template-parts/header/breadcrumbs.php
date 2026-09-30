<?php
/**
 * Template Part: Breadcrumbs
 * Renders site breadcrumbs on all pages except front/home (enforced by caller).
 * Tailwind + inline chevron SVG, accessible markup.
 */

if ( defined('ABSPATH') === false ) {
    exit;
}

// Build items
$items = [];

// Home
$items[] = [
    'label'   => __('Home', 'matrix-starter'),
    'url'     => home_url('/'),
    'current' => false,
];

if ( is_singular() ) {
    global $post;
    if ( $post instanceof WP_Post ) {
        $post_type = get_post_type($post);

        // If custom post type, add archive (when available)
        if ( $post_type && $post_type !== 'page' && $post_type !== 'post' ) {
            $pto = get_post_type_object($post_type);
            if ( $pto && !empty($pto->has_archive) ) {
                $archive_url = get_post_type_archive_link($post_type);
                if ( $post_type === 'services' ) {
                    $archive_url = matrix_starter_get_services_landing_url();
                }
                $items[] = [
                    'label'   => $post_type === 'services'
                        ? matrix_starter_get_services_landing_label()
                        : $pto->labels->name,
                    'url'     => $archive_url,
                    'current' => false,
                ];
            }
        }

        // Posts: add the first category
        if ( $post_type === 'post' ) {
            $cats = get_the_category($post->ID);
            if ( !empty($cats) && !is_wp_error($cats) ) {
                $cat = $cats[0];
                $items[] = [
                    'label'   => $cat->name,
                    'url'     => get_category_link($cat->term_id),
                    'current' => false,
                ];
            }
        }

        // Pages: include ancestors
        if ( $post_type === 'page' ) {
            $anc = get_post_ancestors($post->ID);
            $anc = array_reverse($anc);
            foreach ( $anc as $a_id ) {
                $items[] = [
                    'label'   => get_the_title($a_id),
                    'url'     => get_permalink($a_id),
                    'current' => false,
                ];
            }
        }

        // Current entry
        $items[] = [
            'label'   => get_the_title($post),
            'url'     => '',
            'current' => true,
        ];
    }

} elseif ( is_archive() ) {
    if ( is_post_type_archive('services') ) {
        $items[] = [
            'label'   => matrix_starter_get_services_landing_label(),
            'url'     => '',
            'current' => true,
        ];
    } else {
        $items[] = [
            'label'   => get_the_archive_title(),
            'url'     => '',
            'current' => true,
        ];
    }

} elseif ( is_search() ) {
    $items[] = [
        'label'   => sprintf( esc_html__('Search results for “%s”', 'matrix-starter'), get_search_query() ),
        'url'     => '',
        'current' => true,
    ];

} elseif ( is_404() ) {
    $items[] = [
        'label'   => esc_html__('404 Not Found', 'matrix-starter'),
        'url'     => '',
        'current' => true,
    ];
}

$breadcrumbs_mt     = is_user_logged_in()
    ? 'mt-[4rem] lg:mt-[5rem]'
    : 'mt-[5rem] lg:mt-[6rem]';
$crumb_text_bold    = 'font-red-hat-text text-[12px] font-bold leading-[18px] text-[color:var(--Gray-800,#1D2939)]';
$crumb_text_regular = 'font-red-hat-text text-[12px] font-normal leading-[18px] text-[color:var(--Gray-800,#1D2939)]';
$crumb_link_extra   = 'transition-colors duration-200 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2B3990]';
?>
<section class="w-full bg-[#F9FAFB]">
<nav aria-label="<?php echo esc_attr__('Breadcrumb', 'matrix-starter'); ?>"
     class="flex justify-between items-center w-full mx-auto max-w-container px-5 py-3 <?php echo esc_attr($breadcrumbs_mt); ?>">
  <ol class="flex overflow-hidden gap-2 justify-center items-center" role="list">
    <?php
    $last = count($items) - 1;
    foreach ($items as $i => $it) :
        $is_last     = ($i === $last);
        $current     = !empty($it['current']);
        $text_class  = $i === 0 ? $crumb_text_bold : $crumb_text_regular;
        ?>
        <li class="self-stretch my-auto"<?php echo $current ? ' aria-current="page"' : ''; ?>>
          <?php if (!$current && !empty($it['url'])): ?>
            <a href="<?php echo esc_url($it['url']); ?>"
               class="<?php echo esc_attr(trim($text_class . ' ' . $crumb_link_extra)); ?>">
              <?php echo esc_html($it['label']); ?>
            </a>
          <?php else: ?>
            <span class="inline-block <?php echo esc_attr($text_class); ?>"><?php echo esc_html($it['label']); ?></span>
          <?php endif; ?>
        </li>
        <?php if (!$is_last): ?>
          <li class="flex items-center self-stretch my-auto shrink-0" aria-hidden="true">
            <svg class="shrink-0" xmlns="http://www.w3.org/2000/svg" width="5" height="8" viewBox="0 0 5 8" fill="none">
              <path d="M1 7L4 4L1 1" stroke="#2B3990" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </li>
        <?php endif; ?>
    <?php endforeach; ?>
  </ol>
</nav>
</section>