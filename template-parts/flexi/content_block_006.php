<?php
/**
 * Content Block 006 (Flexible Content block)
 * - Left image + right content
 * - Gradient background (inline style)
 * - Repeater list with arrow icon
 * - Button is an ACF link array
 */

$show_section = (bool) get_sub_field('show_section');
if (!$show_section) return;

// Content
$image         = get_sub_field('left_image');
$heading_tag   = get_sub_field('heading_tag') ?: 'h1';
$heading_text  = get_sub_field('heading_text') ?: '';
$description   = get_sub_field('description');
$services      = get_sub_field('services');
$btn           = get_sub_field('download_button');
$btn_label     = get_sub_field('download_button_label') ?: 'Download brochure';

// Design
$gradient_from = get_sub_field('gradient_from') ?: '#262262';
$gradient_to   = get_sub_field('gradient_to') ?: '#2B3990';
$heading_color = get_sub_field('heading_color') ?: 'text-white';
$text_color    = get_sub_field('text_color') ?: 'text-white';
$accent_color  = get_sub_field('accent_bar_color') ?: 'bg-[#CBE9E1]';

// Padding classes
$padding_classes = [];
if (have_rows('padding_settings')) {
   while (have_rows('padding_settings')) {
       the_row();
       $screen_size   = get_sub_field('screen_size');
       $padding_top   = get_sub_field('padding_top');
       $padding_bottom= get_sub_field('padding_bottom');

       if ($screen_size !== '' && $padding_top !== '' && $padding_top !== null) {
           $padding_classes[] = "{$screen_size}:pt-[{$padding_top}rem]";
       }
       if ($screen_size !== '' && $padding_bottom !== '' && $padding_bottom !== null) {
           $padding_classes[] = "{$screen_size}:pb-[{$padding_bottom}rem]";
       }
   }
}
$padding_classes_str = !empty($padding_classes) ? ' ' . esc_attr(implode(' ', $padding_classes)) : '';

// Allowed tags
$allowed_tags = ['h1','h2','h3','h4','h5','h6','span','p'];
if (!in_array($heading_tag, $allowed_tags, true)) {
    $heading_tag = 'h1';
}

// Image meta
$img_url   = '';
$img_alt   = esc_attr__('Treatment section image', 'matrix-starter');
$img_title = esc_attr__('Treatment section image', 'matrix-starter');
if (!empty($image) && is_array($image)) {
    $img_url   = !empty($image['url'])   ? esc_url($image['url'])   : '';
    $img_alt   = !empty($image['alt'])   ? esc_attr($image['alt'])  : $img_alt;
    $img_title = !empty($image['title']) ? esc_attr($image['title']): $img_title;
}

// Button
$btn_url = $btn_target = '';
if (is_array($btn) && !empty($btn['url'])) {
    $btn_url    = esc_url($btn['url']);
    $btn_target = !empty($btn['target']) ? esc_attr($btn['target']) : '_self';
    $btn_label  = !empty($btn_label) ? esc_html($btn_label) : (!empty($btn['title']) ? esc_html($btn['title']) : esc_html__('Download brochure', 'matrix-starter'));
}

// Random section id
$section_id = 'content-block-006-' . uniqid();
?>

<section
  id="<?php echo esc_attr($section_id); ?>"
  data-matrix-block="<?php echo esc_attr(str_replace('_', '-', get_row_layout()) . '-' . get_row_index()); ?>"
  role="region"
  aria-label="<?php echo esc_attr__('Water treatment services and solutions', 'matrix-starter'); ?>"
  class="flex overflow-hidden relative w-full content-section-five"
  style="background-image: linear-gradient(to right, <?php echo esc_attr($gradient_from); ?>, <?php echo esc_attr($gradient_to); ?>);"
>
  <div class="flex flex-col items-center w-full mx-auto max-w-[1184px] <?php echo $padding_classes_str; ?>">

    <div class="flex flex-col gap-8 items-center w-full md:flex-row lg:gap-0 lg:px-0">

      <!-- Left image -->
      <div class="w-full h-full lg:w-1/2">
        <?php if ($img_url): ?>
          <img
            src="<?php echo $img_url; ?>"
            alt="<?php echo $img_alt; ?>"
            title="<?php echo $img_title; ?>"
            loading="lazy"
            class="w-full max-sm:h-[311px] h-full object-cover rounded-none"
            style="border-radius: 0 !important;"
          />
        <?php endif; ?>
      </div>

      <!-- Right content -->
      <div class="flex items-center px-6 py-8 w-full lg:w-1/2 lg:px-12 lg:py-16">
        <main id="main-content" class="flex flex-col gap-8 max-w-md">

          <!-- Heading + accent (same as content-section-five) -->
          <div class="flex flex-col gap-4">
            <?php if (!empty($heading_text)): ?>
              <<?php echo esc_attr($heading_tag); ?> class="text-3xl font-bold leading-none text-white">
                <?php echo esc_html($heading_text); ?>
              </<?php echo esc_attr($heading_tag); ?>>
            <?php endif; ?>
            <div class="mt-1 w-8 h-1 <?php echo esc_attr($accent_color); ?>" aria-hidden="true" role="presentation"></div>
          </div>

          <!-- Description (16px/400/20px, white) -->
          <?php if (!empty($description)): ?>
            <article class="flex flex-col gap-4">
              <div class="!text-[18px] !font-medium !leading-6 !font-secondary !text-white">
                <?php echo wp_kses_post($description); ?>
              </div>
            </article>
          <?php endif; ?>

          <!-- Services list -->
          <?php if (!empty($services) && is_array($services)): ?>
            <ul class="flex flex-col gap-4">
              <?php foreach ($services as $row):
                $txt = !empty($row['item_text']) ? esc_html($row['item_text']) : '';
                if ($txt === '') continue;
              ?>
                <li class="flex gap-3 items-start">
                  <!-- Arrow icon -->
                  <svg class="flex-shrink-0 mt-0.5 w-5 h-5 text-white" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.16699 10H15.8337" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 4.16663L15.8333 9.99996L10 15.8333" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <span class="text-base font-normal leading-5 text-white font-red-hat-text">
                    <?php echo $txt; ?>
                  </span>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <!-- Download button (same hover/pressed/focus as content-section-five, 18px/500/24px, #2B3990) -->
          <?php if (!empty($btn_url)): ?>
            <div class="pt-4">
              <a
                href="<?php echo $btn_url; ?>"
                target="<?php echo $btn_target; ?>"
                class="content-section-five-btn btn inline-flex gap-2 justify-center items-center w-full px-6 py-3.5 font-red-hat-text text-[18px] font-medium leading-[24px] bg-white rounded-full transition-all duration-300"
                style="color: var(--Blue-300, #2B3990);"
                aria-label="<?php echo esc_attr($btn_label); ?>"
              >
                <svg class="flex-shrink-0 w-5 h-5" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                  <path d="M19 13V17C19 17.5304 18.7893 18.0391 18.4142 18.4142C18.0391 18.7893 17.5304 19 17 19H3C2.46957 19 1.96086 18.7893 1.58579 18.4142C1.21071 18.0391 1 17.5304 1 17V13M5 8L10 13M10 13L15 8M10 13V1" stroke="var(--Blue-300, #2B3990)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span><?php echo esc_html($btn_label); ?></span>
              </a>
            </div>
          <?php endif; ?>

        </main>
      </div>

    </div>
  </div>
</section>
