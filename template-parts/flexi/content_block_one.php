<?php
/**
 * Content Section One (Flexible Content block)
 * - No Design options (uses fixed defaults)
 * - Reverse layout toggle
 * - Conditional image radius: 10px unless changed via control
 * - Uses get_sub_field throughout
 * - Section wrapper + container per spec
 * - Random section id
 */

// ========== Fetch fields ==========
$show_section   = (bool) get_sub_field('show_section');

$image          = get_sub_field('image');
$heading_tag    = get_sub_field('heading_tag') ?: 'h2';
$heading        = get_sub_field('heading') ?: '';
$description    = get_sub_field('description');
$description_full_width = (bool) get_sub_field('description_full_width');
$benefits       = get_sub_field('benefits');
$cta_link       = get_sub_field('cta_link');

// Layout
$reverse_layout      = (bool) get_sub_field('reverse_layout');
$enable_left_offset  = (bool) get_sub_field('enable_left_offset');
$container_width_mode = (string) (get_sub_field('container_width_mode') ?: '1088');
$center_text_vertically = (bool) get_sub_field('center_text_vertically');
$limit_heading_width = (bool) get_sub_field('limit_heading_width');
$heading_max_width_px = (int) get_sub_field('heading_max_width_px');

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

// Random id
$section_id = 'content-section-one-' . uniqid();

// Hide if toggled off
if (!$show_section) {
    return;
}

// Image meta
$img_url   = '';
$img_alt   = 'Section image';
$img_title = 'Section image';
if (!empty($image) && is_array($image)) {
    $img_url   = !empty($image['url'])   ? esc_url($image['url'])   : '';
    $img_alt   = !empty($image['alt'])   ? esc_attr($image['alt'])  : $img_alt;
    $img_title = !empty($image['title']) ? esc_attr($image['title']): $img_title;
}

// Allowed heading tags
$allowed_tags = ['h1','h2','h3','h4','h5','h6','span','p'];
if (!in_array($heading_tag, $allowed_tags, true)) {
    $heading_tag = 'h2';
}

$image_radius_class = 'rounded-[10px]';

// Defaults (Design tab removed)
$section_bg_class   = 'bg-white';
$heading_color      = 'text-primary';
$body_text_color    = 'text-gray-800';
$accent_bar_class   = 'bg-blue-bright';
$container_max_width_class = 'max-w-[1088px]';
$container_width_mode_normalized = strtolower(trim($container_width_mode));

if (in_array($container_width_mode_normalized, ['1088', '1088px', 'default'], true)) {
    $container_max_width_class = 'max-w-[1088px]';
} elseif (in_array($container_width_mode_normalized, ['1048', '1048px', '148xpx'], true)) {
    $container_max_width_class = 'max-w-[1048px]';
} elseif (in_array($container_width_mode_normalized, ['1180', '1180px'], true)) {
    $container_max_width_class = 'max-w-[1180px]';
} elseif ($container_width_mode_normalized === 'theme') {
    $container_max_width_class = is_singular('projects') ? 'max-w-[1100px]' : 'max-w-[1200px]';
} elseif ($container_width_mode_normalized === 'none') {
    $container_max_width_class = 'max-w-none';
}
$description_width_class = $description_full_width ? 'max-w-full' : 'max-w-[400px]';
$content_alignment_class = $center_text_vertically ? 'items-center' : 'items-start';
$rest_top_padding_class = $center_text_vertically ? 'pt-0 sm:pt-3' : 'pt-5';
$heading_max_width_style = '';
if ($limit_heading_width) {
    $heading_max_width_px = $heading_max_width_px > 0 ? $heading_max_width_px : 554;
    $heading_max_width_px = max(200, min(1200, $heading_max_width_px));
    $heading_max_width_style = 'max-width: ' . $heading_max_width_px . 'px;';
}

// At 640px and below: title → image → rest. From md up: two columns (image | heading+rest or reversed).
$heading_order = 'order-1 sm:order-2 sm:col-start-2 sm:row-start-1';
$img_order     = 'order-2 sm:order-1 sm:col-start-1 sm:row-start-1 sm:row-span-2';
$rest_order    = 'order-3 sm:col-start-2 sm:row-start-2';
if ($reverse_layout) {
    $heading_order = 'order-1 sm:order-2 sm:col-start-1 sm:row-start-1';
    $img_order     = 'order-2 sm:order-1 sm:col-start-2 sm:row-start-1 sm:row-span-2';
    $rest_order    = 'order-3 sm:col-start-1 sm:row-start-2';
}

if ($center_text_vertically) {
    $heading_order .= ' sm:row-span-2 sm:flex sm:flex-col sm:justify-center';
}
?>
<section id="<?php echo esc_attr($section_id); ?>" data-matrix-block="<?php echo esc_attr(str_replace('_', '-', get_row_layout()) . '-' . get_row_index()); ?>" class="relative flex overflow-hidden <?php echo esc_attr($section_bg_class); ?>">
  <div class="flex flex-col items-center w-full mx-auto <?php echo esc_attr($container_max_width_class); ?> pt-5 pb-5 max-xl:px-5<?php echo $padding_classes_str; ?> relative<?php echo $enable_left_offset ? ' xl:left-[2.5rem]' : ''; ?>">

      <div class="w-full xl:pt-[3.8rem] xl:pb-[4rem] relative<?php echo $enable_left_offset ? ' xxl:left-[5.2rem]' : ''; ?> py-[2.5rem] grid grid-cols-1 sm:grid-cols-2 gap-8 lg:gap-12 <?php echo esc_attr($content_alignment_class); ?> <?php echo esc_attr($body_text_color); ?>">

        <!-- Heading + Accent (first on mobile, then column 2 row 1 on desktop) -->
        <div class="w-full <?php echo esc_attr($heading_order); ?>">
          <?php if (!empty($heading)): ?>
            <<?php echo esc_attr($heading_tag); ?> class="text-primary font-primary text-[30px] font-bold leading-[38px]"<?php echo $heading_max_width_style !== '' ? ' style="' . esc_attr($heading_max_width_style) . '"' : ''; ?>>
              <?php echo esc_html($heading); ?>
            </<?php echo esc_attr($heading_tag); ?>>
          <?php endif; ?>
          <div class="w-8 h-1 bg-[#00ACD8] rounded"></div>

          <?php if ($center_text_vertically): ?>
            <!-- Desktop-centered text stack -->
            <div class="hidden sm:flex flex-col gap-6 w-full <?php echo esc_attr($rest_top_padding_class); ?>">
              <?php if (!empty($description)): ?>
                <div class="wp_editor font-secondary text-[#1D2939] text-base font-normal leading-5 <?php echo esc_attr($description_width_class); ?> [&>p]:mb-4 [&>p:last-child]:mb-0">
                  <?php echo wp_kses_post($description); ?>
                </div>
              <?php endif; ?>

              <?php if (!empty($benefits) && is_array($benefits)): ?>
                <div class="space-y-4">
                  <?php foreach ($benefits as $item): ?>
                    <?php $btxt = isset($item['text']) ? trim($item['text']) : ''; ?>
                    <?php if ($btxt !== ''): ?>
                      <div class="flex gap-4 items-start">
                        <svg class="w-5 h-5 flex-shrink-0 mt-1 <?php echo esc_attr($body_text_color); ?>" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                          <path d="M4.16675 10H15.8334" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M10 4.16663L15.8333 9.99996L10 15.8333" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="font-secondary font-normal text-base leading-relaxed <?php echo esc_attr($body_text_color); ?>">
                          <?php echo esc_html($btxt); ?>
                        </p>
                      </div>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>

              <?php if (!empty($cta_link) && is_array($cta_link)): ?>
                <?php
                  $cta_url    = !empty($cta_link['url']) ? esc_url($cta_link['url']) : '#';
                  $cta_title  = !empty($cta_link['title']) ? esc_html($cta_link['title']) : 'Learn more';
                  $cta_target = !empty($cta_link['target']) ? esc_attr($cta_link['target']) : '_self';
                ?>
                <div class="pt-2">
                  <a class="btn-primary" href="<?php echo $cta_url; ?>" target="<?php echo $cta_target; ?>"><?php echo $cta_title; ?></a>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>

        <!-- Description + Benefits + CTA (third on mobile, column 2 row 2 on desktop) -->
        <div class="flex flex-col gap-6 w-full <?php echo esc_attr($rest_top_padding_class); ?> <?php echo esc_attr($rest_order); ?><?php echo $center_text_vertically ? ' sm:hidden' : ''; ?>">
              <img
                src="<?php echo $img_url; ?>"
                alt="<?php echo $img_alt; ?>"
                title="<?php echo $img_title; ?>"
                class="object-contain w-full h-full rounded-lg max-sm:flex sm:hidden" />
          <?php if (!empty($description)): ?>
            <div class="wp_editor font-secondary text-[#1D2939] text-base font-normal leading-5 <?php echo esc_attr($description_width_class); ?> [&>p]:mb-4 [&>p:last-child]:mb-0">
              <?php echo wp_kses_post($description); ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($benefits) && is_array($benefits)): ?>
            <div class="space-y-4">
              <?php foreach ($benefits as $item): ?>
                <?php $btxt = isset($item['text']) ? trim($item['text']) : ''; ?>
                <?php if ($btxt !== ''): ?>
                  <div class="flex gap-4 items-start">
                    <svg class="w-5 h-5 flex-shrink-0 mt-1 <?php echo esc_attr($body_text_color); ?>" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                      <path d="M4.16675 10H15.8334" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M10 4.16663L15.8333 9.99996L10 15.8333" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p class="font-secondary font-normal text-base leading-relaxed <?php echo esc_attr($body_text_color); ?>">
                      <?php echo esc_html($btxt); ?>
                    </p>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($cta_link) && is_array($cta_link)): ?>
            <?php
              $cta_url    = !empty($cta_link['url']) ? esc_url($cta_link['url']) : '#';
              $cta_title  = !empty($cta_link['title']) ? esc_html($cta_link['title']) : 'Learn more';
              $cta_target = !empty($cta_link['target']) ? esc_attr($cta_link['target']) : '_self';
            ?>
            <div class="pt-2">
              <a class="btn-primary" href="<?php echo $cta_url; ?>" target="<?php echo $cta_target; ?>"><?php echo $cta_title; ?></a>
            </div>
          <?php endif; ?>

        </div>
        </div>

        <!-- Image (second on mobile, column 1 full height on desktop) -->
        <div class="w-full <?php echo esc_attr($img_order); ?>">
          <div class="relative w-full overflow-hidden <?php echo esc_attr($image_radius_class); ?>">
            <?php if ($img_url): ?>
              <img
                src="<?php echo $img_url; ?>"
                alt="<?php echo $img_alt; ?>"
                title="<?php echo $img_title; ?>"
                class="w-full h-full max-sm:hidden object-cover <?php echo esc_attr($image_radius_class); ?> max-h-[310px]" />
            <?php endif; ?>
          </div>
        </div>

      </div>

  </div>
</section>
