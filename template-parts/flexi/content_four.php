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
$section_id = 'content-three-' . uniqid();
?>

<section id="<?php echo esc_attr($section_id); ?>" role="region" aria-label="<?php echo esc_attr__('About section', 'matrix-starter'); ?>" class="relative flex overflow-hidden w-full <?php echo esc_attr($bg_color); ?>">
  <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5<?php echo $padding_classes_str; ?>">
    <main id="main-content" class="w-full">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center w-full">

        <!-- Left: Text -->
        <article class="flex flex-col gap-6 order-2 lg:order-1">
          <div class="flex flex-col gap-4">
            <?php if (!empty($heading)): ?>
              <<?php echo esc_attr($heading_tag); ?> class="font-red-hat-display text-4xl lg:text-5xl font-bold leading-tight <?php echo esc_attr($heading_color); ?>">
                <?php echo esc_html($heading); ?>
              </<?php echo esc_attr($heading_tag); ?>>
            <?php endif; ?>

            <div class="w-8 h-1 <?php echo esc_attr($accent_color); ?>" aria-hidden="true" role="presentation"></div>
          </div>

          <?php if (!empty($description)): ?>
            <div class="wp_editor font-red-hat-text text-lg lg:text-xl leading-relaxed <?php echo esc_attr($text_color); ?>">
              <?php echo wp_kses_post($description); ?>
            </div>
          <?php endif; ?>
        </article>

        <!-- Right: Image -->
        <figure class="w-full h-80 lg:h-auto lg:min-h-96 order-1 lg:order-2 flex items-center justify-center">
          <?php if ($img_url): ?>
            <img
              src="<?php echo $img_url; ?>"
              alt="<?php echo $img_alt; ?>"
              title="<?php echo $img_title; ?>"
              loading="lazy"
              class="w-full h-full object-cover <?php echo esc_attr($image_radius); ?>"
            />
          <?php endif; ?>
        </figure>

      </div>
    </main>
  </div>
</section>
