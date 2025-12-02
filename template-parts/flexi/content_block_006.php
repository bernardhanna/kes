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
$accent_color  = get_sub_field('accent_bar_color') ?: 'bg-blue-50';
$image_radius  = get_sub_field('image_radius') ?: 'rounded-none';

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
  role="region"
  aria-label="<?php echo esc_attr__('Water treatment services and solutions', 'matrix-starter'); ?>"
  class="relative flex overflow-hidden w-full"
  style="background-image: linear-gradient(to right, <?php echo esc_attr($gradient_from); ?>, <?php echo esc_attr($gradient_to); ?>);"
>
  <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5<?php echo $padding_classes_str; ?>">

    <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-0 w-full px-5 lg:px-0 py-12 lg:py-0">

      <!-- Left image -->
      <div class="w-full lg:w-1/2 h-64 lg:h-auto lg:min-h-[500px] flex-shrink-0">
        <?php if ($img_url): ?>
          <img
            src="<?php echo $img_url; ?>"
            alt="<?php echo $img_alt; ?>"
            title="<?php echo $img_title; ?>"
            loading="lazy"
            class="w-full h-full object-cover <?php echo esc_attr($image_radius); ?>"
          />
        <?php endif; ?>
      </div>

      <!-- Right content -->
      <div class="w-full lg:w-1/2 flex items-center px-6 lg:px-12 py-8 lg:py-16">
        <main id="main-content" class="flex flex-col gap-8 max-w-md">

          <!-- Heading + accent -->
          <div class="flex flex-col gap-4">
            <?php if (!empty($heading_text)): ?>
              <<?php echo esc_attr($heading_tag); ?> class="font-red-hat-display text-3xl lg:text-4xl font-bold leading-tight <?php echo esc_attr($heading_color); ?>">
                <?php echo esc_html($heading_text); ?>
              </<?php echo esc_attr($heading_tag); ?>>
            <?php endif; ?>
            <div class="w-8 h-1 <?php echo esc_attr($accent_color); ?>" aria-hidden="true" role="presentation"></div>
          </div>

          <!-- Description -->
          <?php if (!empty($description)): ?>
            <article class="flex flex-col gap-4">
              <div class="wp_editor font-red-hat-text text-base lg:text-lg font-normal leading-relaxed <?php echo esc_attr($text_color); ?>">
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
                <li class="flex items-start gap-3">
                  <!-- Arrow icon -->
                  <svg class="w-5 h-5 flex-shrink-0 mt-0.5 <?php echo esc_attr($text_color); ?>" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.16699 10H15.8337" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 4.16663L15.8333 9.99996L10 15.8333" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <span class="font-red-hat-text text-base lg:text-lg font-normal <?php echo esc_attr($text_color); ?>">
                    <?php echo $txt; ?>
                  </span>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <!-- Download button -->
          <?php if (!empty($btn_url)): ?>
            <div class="pt-4">
              <a
                href="<?php echo $btn_url; ?>"
                target="<?php echo $btn_target; ?>"
                class="flex items-center justify-center gap-2 w-full lg:w-auto px-8 py-3 lg:py-4 bg-white text-blue-600 rounded-full font-red-hat-text font-medium text-lg hover:opacity-90 transition-opacity"
                aria-label="<?php echo esc_attr($btn_label); ?>"
              >
                <svg class="w-6 h-6" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                  <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15M7 10L12 15M12 15L17 10M12 15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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
