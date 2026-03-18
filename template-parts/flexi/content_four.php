<?php
/**
 * Content Three (Flexible Content block)
 * - Left text (heading+divider+description), right image
 * - Stacked on mobile, side-by-side on desktop
 * - Uses get_sub_field, Tailwind-only, random id, padding repeater
 */

$show_section   = (bool) get_sub_field('show_section');

// Content
$heading_tag    = get_sub_field('heading_tag') ?: 'h1';
$heading        = get_sub_field('heading') ?: '';
$description    = get_sub_field('description');
$image          = get_sub_field('image');

// Design
$bg_color       = get_sub_field('background_color') ?: 'bg-white';
$text_color     = get_sub_field('text_color') ?: 'text-gray-800';
$heading_color  = get_sub_field('heading_color') ?: 'text-blue-500';
$accent_color   = get_sub_field('accent_bar_color') ?: 'bg-blue-100';
$image_radius   = get_sub_field('image_radius') ?: 'rounded-none';

// Layout: padding utility builder
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

// Allowed heading tags
$allowed_tags = ['h1','h2','h3','h4','h5','h6','span','p'];
if (!in_array($heading_tag, $allowed_tags, true)) {
    $heading_tag = 'h1';
}

// Image meta
$img_url   = '';
$img_alt   = __('About section image', 'matrix-starter');
$img_title = __('About section image', 'matrix-starter');
if (!empty($image) && is_array($image)) {
    $img_url   = !empty($image['url'])   ? esc_url($image['url'])   : '';
    $img_alt   = !empty($image['alt'])   ? esc_attr($image['alt'])  : $img_alt;
    $img_title = !empty($image['title']) ? esc_attr($image['title']): $img_title;
}

if (!$show_section) {
    return;
}

// Random section id
$section_id = 'content-four-' . uniqid();
?>

<section id="<?php echo esc_attr($section_id); ?>" class="content-four about-section relative flex overflow-hidden w-full <?php echo esc_attr($bg_color); ?>" role="region" aria-label="<?php echo esc_attr__('About section', 'matrix-starter'); ?>">
  <div class="flex flex-col items-center w-full mx-auto max-w-[1250px] pt-5 pb-5 <?php echo $padding_classes_str; ?>">
    <div class="w-full">
      <div class="grid grid-cols-1 gap-2 items-center w-full md:grid-cols-2 lg:grid-cols-[50%_50%] lg:gap-12">

        <!-- Left: Text -->
        <article class="flex flex-col order-2 gap-6 md:order-1 px-5 xl:pl-[7rem]">
          <div class="flex flex-col gap-4">
            <?php if (!empty($heading)): ?>
              <<?php echo esc_attr($heading_tag); ?> class="text-[36px] font-bold leading-[44px] tracking-[-0.72px] font-primary <?php echo esc_attr($heading_color); ?>">
                <?php echo esc_html($heading); ?>
              </<?php echo esc_attr($heading_tag); ?>>
            <?php endif; ?>
            <div class="w-8 h-1 relative -top-[10px] <?php echo esc_attr($accent_color); ?>" aria-hidden="true" role="presentation"></div>
          </div>

          <?php if ($img_url): ?>
          <!-- Image: show only at 640px and down (below heading), hidden on desktop -->
          <img
            src="<?php echo $img_url; ?>"
            alt="<?php echo $img_alt; ?>"
            title="<?php echo $img_title; ?>"
            loading="lazy"
            class="md:hidden w-full object-cover max-w-[582px] max-h-[333px] object-contain rounded-[8px] <?php echo esc_attr($image_radius); ?>"
          />
          <?php endif; ?>

          <?php if (!empty($description)): ?>
            <div class="wp_editor font-red-hat-text <?php echo esc_attr($text_color); ?>">
              <?php echo wp_kses_post($description); ?>
            </div>
          <?php endif; ?>
        </article>

        <!-- Right: Image - show only above 640px, hidden on mobile -->
        <figure class="flex order-1 justify-start items-start w-full rounded-[8px] md:order-2 hidden md:block">
          <?php if ($img_url): ?>
            <img
              src="<?php echo $img_url; ?>"
              alt="<?php echo $img_alt; ?>"
              title="<?php echo $img_title; ?>"
              loading="lazy"
              class="w-full h-full object-cover max-w-[582px] max-h-[333px] rounded-[8px] relative xl:-left-[1rem] <?php echo esc_attr($image_radius); ?>"
            />
          <?php endif; ?>
        </figure>

      </div>
          </div>
  </div>
</section>
