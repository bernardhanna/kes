<?php
/**
 * Flexi: Image 001 (single image, srcset, target size 1088x720)
 * - Random section ID
 * - Container div with padding controls
 * - Optional link wrapper (ACF Link Array)
 * - Alt / Title pulled from media with fallbacks
 * - Border radius control (default none)
 * - Colors via inline style only (per conventions)
 */

$section_id       = 'image-001-' . wp_generate_uuid4();
$image_id         = get_sub_field('image');           // return: ID
$link             = get_sub_field('link');            // ACF link array
$caption          = get_sub_field('caption');         // WYSIWYG (optional)
$background_color = get_sub_field('background_color') ?: '#ffffff';
$radius           = get_sub_field('border_radius') ?: 'none';

// padding controls → classes
$padding_classes = ['pt-5', 'pb-5']; // base
if (have_rows('padding_settings')) {
  while (have_rows('padding_settings')) {
    the_row();
    $screen_size    = get_sub_field('screen_size');
    $padding_top    = get_sub_field('padding_top');
    $padding_bottom = get_sub_field('padding_bottom');

    if ($screen_size !== null && $padding_top !== '' && $padding_bottom !== '') {
      $padding_classes[] = "{$screen_size}:pt-[{$padding_top}rem]";
      $padding_classes[] = "{$screen_size}:pb-[{$padding_bottom}rem]";
    }
  }
}

// radius map (default none)
$radius_map = [
  'none' => 'rounded-none',
  'sm'   => 'rounded-sm',
  'md'   => 'rounded-md',
  'lg'   => 'rounded-lg',
  'xl'   => 'rounded-xl',
  '2xl'  => 'rounded-2xl',
  'full' => 'rounded-full',
];
$radius_class = isset($radius_map[$radius]) ? $radius_map[$radius] : $radius_map['none'];

// resolve image attrs
$img_html = '';
if ($image_id) {
  $media_title = get_the_title($image_id) ?: '';
  $media_alt   = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: $media_title ?: 'Image';

  // Hint browser about desired size (WP will provide srcset automatically)
  // Target display width: 1088px (height depends on chosen size/crop)
  $attr = [
    'class' => 'block w-full h-auto ' . esc_attr($radius_class),
    'alt'   => esc_attr($media_alt),
    'title' => esc_attr($media_title),
    'loading' => 'lazy',
    'sizes' => '(min-width: 1120px) 1088px, 100vw',
  ];

  // Use a registered size if you add it (see note below), else 'large' is OK
  // echo wp_get_attachment_image($image_id, 'flexi_1088x720', false, $attr);
  $img_html = wp_get_attachment_image($image_id, 'large', false, $attr);
}
?>
<section
  id="<?php echo esc_attr($section_id); ?>"
  class="relative flex overflow-hidden"
  style="background-color: <?php echo esc_attr($background_color); ?>;"
  role="region"
  aria-label="Image"
>
  <div class="flex flex-col items-center w-full mx-auto max-w-container <?php echo esc_attr(implode(' ', $padding_classes)); ?> max-lg:px-5">
    <?php if ($img_html) : ?>
      <figure class="w-full flex flex-col items-center">
        <?php if (!empty($link['url'])) : ?>
          <a
            href="<?php echo esc_url($link['url']); ?>"
            target="<?php echo esc_attr($link['target'] ?: '_self'); ?>"
            class="block w-full max-w-[1088px]"
            aria-label="<?php echo esc_attr($link['title'] ?: 'View image link'); ?>"
          >
            <?php echo $img_html; ?>
          </a>
        <?php else : ?>
          <div class="w-full max-w-[1088px]">
            <?php echo $img_html; ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($caption)) : ?>
          <figcaption class="mt-4 text-sm leading-6 text-slate-700 w-full max-w-[1088px] wp_editor">
            <?php echo wp_kses_post($caption); ?>
          </figcaption>
        <?php endif; ?>
      </figure>
    <?php else : ?>
      <!-- Placeholder (when no image selected) -->
      <div class="w-full max-w-[1088px] h-[720px] bg-gray-200 <?php echo esc_attr($radius_class); ?> flex items-center justify-center">
        <span class="text-gray-500 text-sm">Select an image (1088×720 target)</span>
      </div>
    <?php endif; ?>
  </div>
</section>
