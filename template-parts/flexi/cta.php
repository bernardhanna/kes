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

// Button class builder (gradient matches theme primary CTAs: contact form, btn-primary).
$btn_base = 'btn inline-flex justify-center items-center gap-2 px-6 py-3.5 min-h-[52px] w-full lg:w-fit whitespace-nowrap font-red-hat-text text-[18px] font-medium leading-[24px] transition-[background,background-image,color] duration-300 focus-visible:outline focus-visible:outline-[3px] focus-visible:outline-[#00ACD8] focus-visible:outline-offset-2 ' . esc_attr($btn_radius);

switch ($btn_style) {
  case 'solid-primary':
    $btn_classes = $btn_base . ' bg-[#262262] text-white hover:bg-[#006EC8] active:bg-[#262262]';
    break;
  case 'outline':
    $btn_classes = $btn_base . ' border border-solid border-[#2B3990] bg-white text-[#262262] hover:bg-[#CBE9E1] active:bg-[#00ACD8] active:text-white';
    break;
  case 'gradient-blue':
  default:
    $btn_classes = $btn_base . ' text-white bg-gradient-to-r from-[#2B3990] to-[#006EC8] hover:from-[#006EC8] hover:to-[#2B3990] active:bg-[#262262] active:from-transparent active:to-transparent';
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
  data-matrix-block="<?php echo esc_attr(str_replace('_', '-', get_row_layout()) . '-' . get_row_index()); ?>"
  role="region"
  aria-label="<?php echo esc_attr__('Contact us call to action', 'matrix-starter'); ?>"
  class="relative flex overflow-hidden w-full <?php echo esc_attr($bg_color); ?>"
>
  <div class="flex flex-col items-center w-full mx-auto max-w-container py-24 max-xl:px-5<?php echo $padding_classes_str; ?>">
    <div id="div-content" class="flex flex-col gap-8 justify-between items-center px-0 w-full lg:flex-row lg:gap-12">

      <!-- Left: Text -->
      <article class="flex flex-col flex-1 gap-6 w-full">
        <div class="flex flex-col gap-3">
          <?php if (!empty($heading_text)): ?>
            <<?php echo esc_attr($heading_tag); ?> class="font-red-hat-display text-3xl lg:text-4xl font-bold leading-tight <?php echo esc_attr($heading_color); ?>">
              <?php echo esc_html($heading_text); ?>
            </<?php echo esc_attr($heading_tag); ?>>
          <?php endif; ?>
          <div class="w-8 h-1 shrink-0 <?php echo esc_attr($accent_color); ?>" aria-hidden="true" role="presentation"></div>
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
