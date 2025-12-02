<?php
/**
 * Content Section One (Flexible Content block)
 * Dynamic conversion of the provided static layout.
 *
 * Conventions:
 * - Use get_sub_field for all values.
 * - <section> has "relative flex overflow-hidden".
 * - Next div: "flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5"
 * - ACF link array for CTA.
 * - WYSIWYG gets class "wp_editor".
 * - Default rounded: rounded-none.
 * - Random section id.
 */

// ========== Fetch fields ==========
$show_section  = (bool) get_sub_field('show_section');

$image         = get_sub_field('image');
$enable_grad   = (bool) get_sub_field('enable_gradient_overlay');

$heading_tag   = get_sub_field('heading_tag') ?: 'h2';
$heading       = get_sub_field('heading') ?: '';
$description   = get_sub_field('description');

$benefits      = get_sub_field('benefits');
$cta_link      = get_sub_field('cta_link');

// Design
$bg_color      = get_sub_field('background_color') ?: 'bg-white';
$text_color    = get_sub_field('text_color') ?: 'text-gray-800';
$accent_color  = get_sub_field('accent_bar_color') ?: 'bg-blue-bright';
$rounded       = get_sub_field('rounded') ?: 'rounded-none';

$grad_from     = get_sub_field('gradient_from') ?: 'from-blue-dark';
$grad_to       = get_sub_field('gradient_to') ?: 'to-blue-bright';

// Padding classes
$padding_classes = [];
if (have_rows('padding_settings')) {
   while (have_rows('padding_settings')) {
       the_row();
       $screen_size = get_sub_field('screen_size');
       $padding_top = get_sub_field('padding_top');
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
?>

<section id="<?php echo esc_attr($section_id); ?>" class="relative flex overflow-hidden <?php echo esc_attr($bg_color); ?>">
  <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5<?php echo $padding_classes_str; ?>">

    <div class="w-full py-16 sm:py-20 lg:py-24">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center <?php echo esc_attr($text_color); ?>">

          <!-- Image -->
          <div class="order-2 lg:order-1">
            <div class="relative w-full <?php echo esc_attr($rounded); ?> overflow-hidden <?php echo $enable_grad ? 'bg-gradient-to-r ' . esc_attr($grad_from . ' ' . $grad_to) : ''; ?> h-64 sm:h-80 lg:h-96">
              <?php if ($img_url): ?>
                <img src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>" title="<?php echo $img_title; ?>" class="w-full h-full object-cover" />
              <?php endif; ?>
            </div>
          </div>

          <!-- Content -->
          <div class="order-1 lg:order-2 flex flex-col gap-6">

            <!-- Heading + Accent -->
            <div class="space-y-3">
              <?php if (!empty($heading)): ?>
                <<?php echo esc_attr($heading_tag); ?> class="font-primary font-bold text-3xl sm:text-4xl lg:text-5xl leading-tight <?php echo $text_color === 'text-white' ? 'text-white' : 'text-blue-dark'; ?>">
                  <?php echo esc_html($heading); ?>
                </<?php echo esc_attr($heading_tag); ?>>
              <?php endif; ?>
              <div class="w-8 h-1 <?php echo esc_attr($accent_color); ?> rounded"></div>
            </div>

            <!-- Description -->
            <?php if (!empty($description)): ?>
              <div class="wp_editor font-secondary font-normal text-base sm:text-lg leading-relaxed <?php echo esc_attr($text_color); ?>">
                <?php echo wp_kses_post($description); ?>
              </div>
            <?php endif; ?>

            <!-- Benefits -->
            <?php if (!empty($benefits) && is_array($benefits)): ?>
              <div class="space-y-4">
                <?php foreach ($benefits as $item): ?>
                  <?php $btxt = isset($item['text']) ? trim($item['text']) : ''; ?>
                  <?php if ($btxt !== ''): ?>
                    <div class="flex items-start gap-4">
                      <svg class="w-5 h-5 flex-shrink-0 mt-1 <?php echo esc_attr($text_color); ?>" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                        <path d="M4.16675 10H15.8334" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10 4.16663L15.8333 9.99996L10 15.8333" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                      <p class="font-secondary font-normal text-base leading-relaxed <?php echo esc_attr($text_color); ?>">
                        <?php echo esc_html($btxt); ?>
                      </p>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <!-- CTA -->
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

    </div>

  </div>
</section>
