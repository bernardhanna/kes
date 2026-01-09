<?php
/**
 * Content Section One (Flexible Content block)
 * - No Design options (uses fixed defaults)
 * - Reverse layout toggle
 * - Conditional image radius: 10px unless changed via control
 * - Uses get_sub_field throughout
 * - Section wrapper + container per spec
 * - Random section id
 */

// ========== Fetch fields ==========
$show_section   = (bool) get_sub_field('show_section');

$image          = get_sub_field('image');
$heading_tag    = get_sub_field('heading_tag') ?: 'h2';
$heading        = get_sub_field('heading') ?: '';
$description    = get_sub_field('description');
$benefits       = get_sub_field('benefits');
$cta_link       = get_sub_field('cta_link');

// Layout
$reverse_layout = (bool) get_sub_field('reverse_layout'); // toggle

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

// ===== Radius control =====
// ACF field `image_radius` default is 'rounded-none'
// Render 'rounded-[10px]' unless the user picked something else.
$radius_control = get_sub_field('image_radius') ?: 'rounded-none';
$image_radius_class = ($radius_control && $radius_control !== 'rounded-none')
  ? $radius_control
  : 'rounded-[10px]';

// Defaults (Design tab removed)
$section_bg_class   = 'bg-white';
$heading_color      = 'text-primary';
$body_text_color    = 'text-gray-800';
$accent_bar_class   = 'bg-blue-bright';

// Order utilities based on reverse toggle
$img_order_classes     = $reverse_layout ? 'order-2 lg:order-2' : 'order-1 lg:order-1';
$content_order_classes = $reverse_layout ? 'order-1 lg:order-1' : 'order-1 lg:order-2';
?>
<section id="<?php echo esc_attr($section_id); ?>" class="relative flex overflow-hidden <?php echo esc_attr($section_bg_class); ?>">
  <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5<?php echo $padding_classes_str; ?>">

    <div class="py-16 w-full sm:py-20 lg:py-24">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center <?php echo esc_attr($body_text_color); ?>">

        <!-- Image -->
        <div class="<?php echo esc_attr($img_order_classes); ?>">
          <div class="relative w-full overflow-hidden <?php echo esc_attr($image_radius_class); ?>">
            <?php if ($img_url): ?>
              <img
                src="<?php echo $img_url; ?>"
                alt="<?php echo $img_alt; ?>"
                title="<?php echo $img_title; ?>"
                class="w-full h-full object-cover <?php echo esc_attr($image_radius_class); ?> max-h-[310px]" />
            <?php endif; ?>
          </div>
        </div>

        <!-- Content -->
        <div class="flex flex-col gap-6 <?php echo esc_attr($content_order_classes); ?>">

          <!-- Heading + Accent -->
          <div class="w-full">
            <?php if (!empty($heading)): ?>
              <<?php echo esc_attr($heading_tag); ?> class="text-primary font-primary text-[30px] font-bold leading-[38px]">
                <?php echo esc_html($heading); ?>
              </<?php echo esc_attr($heading_tag); ?>>
            <?php endif; ?>
            <div class="w-8 h-1 bg-[#00ACD8] rounded"></div>
          </div>

          <!-- Description -->
          <?php if (!empty($description)): ?>
            <div class="wp_editor font-secondary text-[#1D2939] text-base font-normal leading-5 max-w-[400px]">
              <?php echo wp_kses_post($description); ?>
            </div>
          <?php endif; ?>

          <!-- Benefits -->
          <?php if (!empty($benefits) && is_array($benefits)): ?>
            <div class="space-y-4">
              <?php foreach ($benefits as $item): ?>
                <?php $btxt = isset($item['text']) ? trim($item['text']) : ''; ?>
                <?php if ($btxt !== ''): ?>
                  <div class="flex gap-4 items-start">
                    <svg class="w-5 h-5 flex-shrink-0 mt-1 <?php echo esc_attr($body_text_color); ?>" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                      <path d="M4.16675 10H15.8334" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M10 4.16663L15.8333 9.99996L10 15.8333" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p class="font-secondary font-normal text-base leading-relaxed <?php echo esc_attr($body_text_color); ?>">
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
