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

// Order utilities
$img_col_order     = $reverse_layout ? 'order-2 lg:order-2' : 'order-2 lg:order-2'; // image stays right on lg by default
$content_col_order = $reverse_layout ? 'order-1 lg:order-1' : 'order-1 lg:order-1'; // content stays left on lg by default
// On mobile it's stacked; if you want true flip on lg only, keep as-is. If you want full flip (including lg), swap grid placement below instead.

?>
<section id="<?php echo esc_attr($section_id); ?>" class="flex overflow-hidden relative bg-white">
  <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5 <?php echo $padding_classes_str; ?>">
    <div class="grid grid-cols-1 gap-8 items-center w-full lg:grid-cols-2">

      <?php
      // Decide column order per reverse toggle: default is Content left, Image right
      if ($reverse_layout) :
        // Image left, Content right (reversed)
        ?>
        <!-- Image Column -->
        <figure class="flex overflow-hidden relative items-center <?php echo esc_attr($img_col_order); ?>">
          <?php if ($img_url): ?>
            <img
              src="<?php echo $img_url; ?>"
              alt="<?php echo $img_alt; ?>"
              title="<?php echo $img_title; ?>"
              class="object-cover w-full h-auto rounded-none" />
          <?php endif; ?>
        </figure>

        <!-- Content Column -->
        <article class="flex flex-col gap-8 py-10 pr-10 pl-10 max-md:px-6 <?php echo esc_attr($content_col_order); ?>">
          <header class="flex flex-col gap-1">
            <<?php echo esc_attr($heading_tag); ?> class="text-3xl font-bold leading-10 text-text-primary">
              <?php echo esc_html($heading); ?>
            </<?php echo esc_attr($heading_tag); ?>>
            <div class="w-8 h-1 bg-cyan-500" aria-hidden="true"></div>
          </header>

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
            <nav class="flex flex-wrap gap-4" aria-label="Section actions">
              <?php if (!empty($primary_cta) && is_array($primary_cta)): ?>
                <?php
                  $p_url    = !empty($primary_cta['url']) ? esc_url($primary_cta['url']) : '#';
                  $p_title  = !empty($primary_cta['title']) ? esc_html($primary_cta['title']) : 'Primary action';
                  $p_target = !empty($primary_cta['target']) ? esc_attr($primary_cta['target']) : '_self';
                ?>
                    <a href="<?php echo $p_url; ?>" target="<?php echo $p_target; ?>"
                    class="w-fit whitespace-nowrap flex gap-2 justify-center items-center px-6 py-4 h-[52px] rounded-full text-[18px] font-medium leading-[24px] text-white bg-gradient-to-r from-[#2B3990] to-[#006EC8] hover:from-[#243080] hover:to-[#005eb0] transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-all duration-200">
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
                   class="w-fit whitespace-nowrap flex gap-2 justify-center items-center px-6 py-4 bg-white border-2 border-blue-900 border-solid h-[52px] rounded-full text-[18px] font-medium leading-[24px] text-[#2B3990] hover:bg-blue-50 hover:border-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                  <span class="text-[#2B3990]"><?php echo $s_title; ?></span>
                </a>
              <?php endif; ?>
            </nav>
          <?php endif; ?>
        </article>

      <?php else: ?>
        <!-- Default: Content left, Image right -->

        <!-- Content Column -->
        <article class="flex flex-col gap-8 py-10 pr-10 pl-10 max-md:px-6 <?php echo esc_attr($content_col_order); ?>">
          <header class="flex flex-col gap-1">
            <<?php echo esc_attr($heading_tag); ?> class="text-3xl font-bold leading-10 text-text-primary">
              <?php echo esc_html($heading); ?>
            </<?php echo esc_attr($heading_tag); ?>>
            <div class="w-8 h-1 bg-cyan-500" aria-hidden="true"></div>
          </header>

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
            <nav class="flex flex-wrap gap-4" aria-label="Section actions">
              <?php if (!empty($primary_cta) && is_array($primary_cta)): ?>
                <?php
                  $p_url    = !empty($primary_cta['url']) ? esc_url($primary_cta['url']) : '#';
                  $p_title  = !empty($primary_cta['title']) ? esc_html($primary_cta['title']) : 'Primary action';
                  $p_target = !empty($primary_cta['target']) ? esc_attr($primary_cta['target']) : '_self';
                ?>
                <a href="<?php echo $p_url; ?>" target="<?php echo $p_target; ?>"
                   class="w-fit whitespace-nowrap flex gap-2 justify-center items-center px-6 py-4 h-[52px] rounded-full text-lg font-medium leading-6 text-white gradient-one transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-all duration-200">
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
                   class="w-fit whitespace-nowrap flex gap-2 justify-center items-center px-6 py-4 bg-white border-2 border-blue-900 border-solid h-[52px] rounded-full text-lg font-medium leading-6 text-[#2B3990] hover:bg-blue-50 hover:border-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                  <span class="text-[#2B3990]"><?php echo $s_title; ?></span>
                  <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 w-4 h-4">
                    <path d="M3.3335 8.00004H12.6668M12.6668 8.00004L8.00016 3.33337M12.6668 8.00004L8.00016 12.6667" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                </a>
              <?php endif; ?>
            </nav>
          <?php endif; ?>
        </article>

        <!-- Image Column -->
        <figure class="flex overflow-hidden relative items-center <?php echo esc_attr($img_col_order); ?>">
          <?php if ($img_url): ?>
            <img
              src="<?php echo $img_url; ?>"
              alt="<?php echo $img_alt; ?>"
              title="<?php echo $img_title; ?>"
              class="object-cover w-full h-auto rounded-none max-w-[502px] max-h-[340px]" />
          <?php endif; ?>
        </figure>
      <?php endif; ?>

    </div>
  </div>
</section>
