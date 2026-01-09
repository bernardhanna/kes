<?php
/**
 * Latest Projects (Flexible Content block)
 * - Defaults to the 3 most recent "projects" posts.
 * - Editors can manually select exactly 3 projects.
 * - All design options removed; all text uses text-primary.
 * - Tailwind-only, get_sub_field everywhere, random section id, padding repeater.
 * - No aspect-* classes; uses fixed heights with object-cover.
 */

// Fields
$show_section        = (bool) get_sub_field('show_section');
$heading             = get_sub_field('heading') ?: 'Projects';
$intro               = get_sub_field('intro');

$cta_link            = get_sub_field('cta_link');
$use_manual          = (bool) get_sub_field('use_manual_selection');
$manual_projects     = get_sub_field('manual_projects');

// Padding classes
$padding_classes = [];
if (have_rows('padding_settings')) {
    while (have_rows('padding_settings')) {
        the_row();
        $screen_size    = get_sub_field('screen_size');
        $padding_top    = get_sub_field('padding_top');
        $padding_bottom = get_sub_field('padding_bottom');
        if ($screen_size !== '' && $padding_top !== '' && $padding_top !== null) {
            $padding_classes[] = "{$screen_size}:pt-[{$padding_top}rem]";
        }
        if ($screen_size !== '' && $padding_bottom !== '' && $padding_bottom !== null) {
            $padding_classes[] = "{$screen_size}:pb-[{$padding_bottom}rem]";
        }
    }
}
$padding_classes_str = !empty($padding_classes) ? ' ' . esc_attr(implode(' ', $padding_classes)) : '';

if (!$show_section) {
    return;
}

// Resolve projects: manual (exactly 3) or latest 3
$projects = [];
if ($use_manual && is_array($manual_projects) && count($manual_projects) === 3) {
    $projects = $manual_projects; // relationship objects
} else {
    $q = new WP_Query([
        'post_type'      => 'projects',
        'posts_per_page' => 3,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ]);
    if ($q->have_posts()) {
        while ($q->have_posts()) {
            $q->the_post();
            $projects[] = get_post();
        }
        wp_reset_postdata();
    }
}

if (empty($projects)) {
    return;
}

$section_id = 'latest-projects-' . uniqid();
?>

<section id="<?php echo esc_attr($section_id); ?>" class="flex overflow-hidden relative bg-white">
  <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5<?php echo $padding_classes_str; ?>">

    <div class="w-full">

      <!-- Header -->
      <div class="flex flex-col gap-8 mb-4 lg:flex-row lg:justify-between lg:items-start">
        <div class="flex-1">
          <div class="mb-5">
            <span class="mb-3 font-primary text-[30px] font-bold leading-[38px] text-primary">
              <?php echo esc_html($heading); ?>
            </span>
            <div class="w-8 h-1 rounded bg-[#00ACD8]"></div>
          </div>

          <?php if (!empty($intro)): ?>
            <div class="max-w-2xl text-[16px] font-normal leading-[20px] wp_editor font-secondary text-primary">
              <?php echo wp_kses_post($intro); ?>
            </div>
          <?php endif; ?>
        </div>
        <?php if (!empty($cta_link) && is_array($cta_link)): ?>
          <?php
            $cta_url    = !empty($cta_link['url']) ? esc_url($cta_link['url']) : '#';
            $cta_title  = !empty($cta_link['title']) ? esc_html($cta_link['title']) : 'View all our projects';
            $cta_target = !empty($cta_link['target']) ? esc_attr($cta_link['target']) : '_self';
          ?>
          <a
            href="<?php echo $cta_url; ?>"
            target="<?php echo $cta_target; ?>"
            class="btn w-fit whitespace-nowrap flex gap-2 justify-center items-center px-4 py-2.5 text-[14px] font-medium leading-[20px] text-[#2B3990] bg-white border-2 border-blue-900 border-solid rounded-[100px] hover:bg-blue-50 hover:border-blue-700 transition-colors duration-200 h-[38px] relative right-5"
            aria-label="<?php echo esc_attr($cta_title . ' - opens project gallery'); ?>"
          >
            <span class="text-blue-900">
              <?php echo $cta_title; ?>
            </span>
            <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 w-4 h-4">
              <path d="M3.3335 8.00004H12.6668M12.6668 8.00004L8.00016 3.33337M12.6668 8.00004L8.00016 12.6667" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
          </a>
        <?php endif; ?>

      </div>

      <!-- Grid -->
      <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3 lg:gap-10">
        <?php foreach ($projects as $p): ?>
          <?php
            $pid   = is_object($p) ? $p->ID : (int) $p;
            $title = esc_html(get_the_title($pid));
            $perma = esc_url(get_permalink($pid));

            // Featured image
            $thumb_id = get_post_thumbnail_id($pid);
            $img_url  = $thumb_id ? esc_url(wp_get_attachment_image_url($thumb_id, 'large')) : '';
            $img_alt  = $thumb_id ? esc_attr(get_post_meta($thumb_id, '_wp_attachment_image_alt', true)) : '';
            if ($img_alt === '') $img_alt = $title;
            $img_title = $thumb_id ? esc_attr(get_the_title($thumb_id)) : $title;

            // Excerpt
            $excerpt = get_the_excerpt($pid);
            if (!$excerpt) {
                $excerpt = wp_trim_words(wp_strip_all_tags(get_post_field('post_content', $pid)), 18, '…');
            }
          ?>
          <article class="flex flex-col gap-6">
            <a class="block overflow-hidden relative w-full rounded-[4px]" href="<?php echo $perma; ?>">
              <?php if ($img_url): ?>
                <img class="object-cover w-[358px] h-[246px] rounded-[4px]" src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>" title="<?php echo $img_title; ?>">
              <?php endif; ?>
            </a>

            <div class="flex flex-col gap-3">
              <span class="text-[20px] font-[500] leading-[26px] font-secondary text-[#1D2939]">
                <a class="text-[#1D2939]" href="<?php echo $perma; ?>"><?php echo $title; ?></a>
              </span>
              <?php if (!empty($excerpt)): ?>
                <p class="text-[18px] font-normal leading-[24px] font-secondary text-[#344054]">
                  <?php echo esc_html($excerpt); ?>
                </p>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

    </div>

  </div>
</section>
