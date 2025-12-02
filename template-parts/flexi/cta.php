<?php
/**
 * CTA (Flexible Content block)
 * - Two-column responsive layout (text + button)
 * - get_sub_field only; Tailwind; random id; padding repeater; ACF link array
 */

$show_section   = (bool) get_sub_field('show_section');

$heading_tag    = get_sub_field('heading_tag') ?: 'h1';
$heading_text   = get_sub_field('heading_text') ?: '';
$subheading     = get_sub_field('subheading');

$button_link    = get_sub_field('button_link');
$button_label   = get_sub_field('button_label');

// Design
$bg_color       = get_sub_field('background_color') ?: 'bg-white';
$heading_color  = get_sub_field('heading_color') ?: 'text-blue-500';
$accent_color   = get_sub_field('accent_bar_color') ?: 'bg-blue-100';
$text_color     = get_sub_field('text_color') ?: 'text-gray-800';
$btn_style      = get_sub_field('button_style') ?: 'gradient-blue';
$btn_radius     = get_sub_field('button_radius') ?: 'rounded-full';

// Padding
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

if (!$show_section) {
  return;
}

$section_id = 'cta-' . uniqid();

// Button class builder
$btn_classes = 'btn w-full lg:w-fit text-white px-6 py-3 lg:py-4 font-red-hat-text font-medium text-lg whitespace-nowrap transition-opacity ' . esc_attr($btn_radius);
switch ($btn_style) {
  case 'solid-primary':
    $btn_classes .= ' bg-primary hover:opacity-90';
    break;
  case 'outline':
    $btn_classes .= ' border border-current text-blue-600 hover:opacity-90';
    break;
  case 'gradient-blue':
  default:
    $btn_classes .= ' bg-gradient-to-r from-blue-600 to-blue-400 hover:opacity-90';
    break;
}

// Resolve button data
$cta_url = $cta_title = $cta_target = '';
if (is_array($button_link) && !empty($button_link['url'])) {
  $cta_url    = esc_url($button_link['url']);
  $cta_title  = esc_html($button_label ?: ($button_link['title'] ?? ''));
  $cta_title  = $cta_title ?: esc_html__('Contact us', 'matrix-starter');
  $cta_target = !empty($button_link['target']) ? esc_attr($button_link['target']) : '_self';
}
?>

<section
  id="<?php echo esc_attr($section_id); ?>"
  role="region"
  aria-label="<?php echo esc_attr__('Contact us call to action', 'matrix-starter'); ?>"
  class="relative flex overflow-hidden w-full <?php echo esc_attr($bg_color); ?>"
>
  <div class="flex flex-col items-center w-full mx-auto max-w-container py-24 max-lg:px-5<?php echo $padding_classes_str; ?>">
    <div id="div-content" class="w-full flex flex-col lg:flex-row items-center justify-between gap-8 lg:gap-12 px-0 lg:px-12">

      <!-- Left: Text -->
      <article class="flex flex-col gap-6 flex-1 w-full">
        <div class="flex flex-col gap-4">
          <?php if (!empty($heading_text)): ?>
            <<?php echo esc_attr($heading_tag); ?> class="font-red-hat-display text-3xl lg:text-4xl font-bold leading-tight <?php echo esc_attr($heading_color); ?>">
              <?php echo esc_html($heading_text); ?>
            </<?php echo esc_attr($heading_tag); ?>>
          <?php endif; ?>
          <div class="w-8 h-1 <?php echo esc_attr($accent_color); ?>" aria-hidden="true" role="presentation"></div>
        </div>

        <?php if (!empty($subheading)): ?>
          <div class="wp_editor font-red-hat-text text-lg lg:text-xl font-normal leading-relaxed <?php echo esc_attr($text_color); ?>">
            <?php echo wp_kses_post($subheading); ?>
          </div>
        <?php endif; ?>
      </article>

      <!-- Right: Button -->
      <?php if (!empty($cta_url)): ?>
        <div class="flex-shrink-0 w-full lg:w-auto">
          <a
            href="<?php echo $cta_url; ?>"
            class="<?php echo $btn_classes; ?>"
            target="<?php echo $cta_target; ?>"
            aria-label="<?php echo esc_attr($cta_title); ?>"
          >
            <?php echo $cta_title; ?>
          </a>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>
