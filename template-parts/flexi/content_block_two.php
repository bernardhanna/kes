<?php
/**
 * Content Block Two (Flexible Content)
 * - Only layout control: reverse layout
 * - Two WYSIWYG editors with fixed typography
 * - Uses get_sub_field throughout
 * - Section wrapper + container per spec
 * - Random section id
 * - ACF link arrays for CTAs
 * - No aspect-* or min-w-[...] classes
 */

// ===== Fields =====
$show_section     = (bool) get_sub_field('show_section');
$reverse_layout   = (bool) get_sub_field('reverse_layout');

$heading_tag      = get_sub_field('heading_tag') ?: 'h2';
$heading          = get_sub_field('heading') ?: 'Sustainability';

$wysiwyg_one      = get_sub_field('wysiwyg_one');   // 18/24
$wysiwyg_two      = get_sub_field('wysiwyg_two');   // 16/20

$image            = get_sub_field('image');

$primary_cta      = get_sub_field('primary_cta');   // ACF link array
$secondary_cta    = get_sub_field('secondary_cta'); // ACF link array

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

// Guards
if (!$show_section) {
    return;
}

// Random section id
$section_id = 'content-block-two-' . uniqid();

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

// At 640px and below: title → image → rest. From sm up: two columns (content left | image right, or reversed).
$heading_order = 'order-1 sm:order-1 sm:col-start-1 sm:row-start-1';
$img_order     = 'order-2 sm:order-2 sm:col-start-2 sm:row-start-1 sm:row-span-2';
$rest_order    = 'order-3 sm:col-start-1 sm:row-start-2';
if ($reverse_layout) {
    $heading_order = 'order-1 sm:order-2 sm:col-start-2 sm:row-start-1';
    $img_order     = 'order-2 sm:order-1 sm:col-start-1 sm:row-start-1 sm:row-span-2';
    $rest_order    = 'order-3 sm:col-start-2 sm:row-start-2';
}
?>
<section id="<?php echo esc_attr($section_id); ?>" class="flex overflow-hidden relative bg-white">
  <div class="flex flex-col items-center w-full mx-auto max-w-container xl:py-[5rem] pt-5 pb-5 max-xl:px-5 <?php echo $padding_classes_str; ?>">
    <div class="grid grid-cols-1 gap-8 items-start w-full sm:grid-cols-[42%_58%]">

        <!-- Heading (first on mobile, column 1 row 1 on desktop; column 2 row 1 when reversed) -->
        <header class="flex flex-col gap-1 <?php echo esc_attr($heading_order); ?>">
          <<?php echo esc_attr($heading_tag); ?> class="text-3xl font-bold leading-10 text-primary">
            <?php echo esc_html($heading); ?>
          </<?php echo esc_attr($heading_tag); ?>>
          <div class="w-8 h-1 bg-cyan-500" aria-hidden="true"></div>
        </header>

        <!-- Image (second on mobile, column 2 full height on desktop; column 1 when reversed) -->
        <figure class="flex overflow-hidden relative items-center w-full <?php echo esc_attr($img_order); ?>">
          <?php if ($img_url): ?>
            <img
              src="<?php echo $img_url; ?>"
              alt="<?php echo $img_alt; ?>"
              title="<?php echo $img_title; ?>"
              class="object-cover w-full h-auto rounded-none max-w-[502px] max-h-[340px] max-sm:w-full sm:max-w-[502px] sm:max-h-[340px]" />
          <?php endif; ?>
        </figure>

        <!-- WYSIWYG + CTAs (third on mobile, column 1 row 2 on desktop; column 2 row 2 when reversed) -->
        <div class="flex flex-col gap-8 py-10 max-md:py-0 max-md:px-0 <?php echo esc_attr($rest_order); ?>">

          <?php if (!empty($wysiwyg_one)): ?>
            <div class="wp_editor text-[18px] leading-[24px] font-normal text-slate-800">
              <?php echo wp_kses_post($wysiwyg_one); ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($wysiwyg_two)): ?>
            <div class="wp_editor text-[16px] leading-[20px] font-normal text-slate-800">
              <?php echo wp_kses_post($wysiwyg_two); ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($primary_cta) || !empty($secondary_cta)): ?>
            <nav class="flex flex-wrap gap-8" aria-label="Section actions">
              <?php if (!empty($primary_cta) && is_array($primary_cta)): ?>
                <?php
                  $p_url    = !empty($primary_cta['url']) ? esc_url($primary_cta['url']) : '#';
                  $p_title  = !empty($primary_cta['title']) ? esc_html($primary_cta['title']) : 'Primary action';
                  $p_target = !empty($primary_cta['target']) ? esc_attr($primary_cta['target']) : '_self';
                ?>
                <a href="<?php echo $p_url; ?>" target="<?php echo $p_target; ?>"
                   class="btn-primary w-fit whitespace-nowrap flex gap-2 justify-center items-center h-[52px] text-white transition-all duration-200 max-sm:w-full sm:w-fit">
                  <span><?php echo $p_title; ?></span>
                </a>
              <?php endif; ?>

              <?php if (!empty($secondary_cta) && is_array($secondary_cta)): ?>
                <?php
                  $s_url    = !empty($secondary_cta['url']) ? esc_url($secondary_cta['url']) : '#';
                  $s_title  = !empty($secondary_cta['title']) ? esc_html($secondary_cta['title']) : 'Secondary action';
                  $s_target = !empty($secondary_cta['target']) ? esc_attr($secondary_cta['target']) : '_self';
                ?>
                <a href="<?php echo $s_url; ?>" target="<?php echo $s_target; ?>"
                   class="w-fit whitespace-nowrap flex gap-2 justify-center items-center px-6 py-4 h-[52px] rounded-full font-secondary text-lg font-medium leading-[24px] text-blue-dark bg-base-white border-2 border-blue-dark transition-colors duration-200 hover:bg-teal-light hover:border-blue-dark active:bg-blue-100 active:border-blue-dark focus-visible:outline-none focus-visible:outline-[3px] focus-visible:outline-blue-100 focus-visible:outline-offset-2 focus-visible:bg-base-white max-sm:h-[38px] max-sm:w-full sm:h-[52px] sm:w-fit">
                  <span class="text-blue-dark"><?php echo $s_title; ?></span>
                </a>
              <?php endif; ?>
            </nav>
          <?php endif; ?>
        </div>

    </div>
  </div>
</section>
