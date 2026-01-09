<?php
/**
 * FAQ Section (faq_003)
 * - Manual selection (default) OR display all FAQs from CPT.
 * - Tailwind-only, random id, padding repeater classes, accessible accordion.
 * - Uses Alpine.js (ensure Alpine is enqueued).
 */

// Gather fields
$heading_tag          = get_sub_field('heading_tag') ?: 'h2';
$heading_text         = get_sub_field('heading_text');

$display_all          = (bool) get_sub_field('display_all');
$faq_items_rel        = get_sub_field('faq_items');

$section_background   = get_sub_field('section_background') ?: '#FFFFFF';
$heading_color        = get_sub_field('heading_color') ?: '#262262';
$accent_bar_color     = get_sub_field('accent_bar_color') ?: '#00ACD8';
$question_color       = get_sub_field('question_color') ?: '#2B3990';
$border_color         = get_sub_field('border_color') ?: '#E5E7EB';
$active_border_color  = get_sub_field('active_border_color') ?: '#CBE9E1';
$focus_ring_color     = get_sub_field('focus_ring_color') ?: '#262262';

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

$section_id = 'faq-003-' . uniqid();

// Build FAQ list
$faqs = [];

if ($display_all) {
  $count   = (int) (get_sub_field('all_count') ?: 6);
  $orderby = get_sub_field('all_orderby') ?: 'date';
  $order   = get_sub_field('all_order') ?: 'DESC';

  $q = new WP_Query([
    'post_type'      => 'faqs',
    'posts_per_page' => $count,
    'orderby'        => $orderby,
    'order'          => $order,
    'post_status'    => 'publish',
    'no_found_rows'  => true,
  ]);
  if ($q->have_posts()) {
    while ($q->have_posts()) {
      $q->the_post();
      $faqs[] = [
        'id'      => get_the_ID(),
        'title'   => get_the_title(),
        'content' => apply_filters('the_content', get_the_content()),
      ];
    }
    wp_reset_postdata();
  }
} else {
  if (!empty($faq_items_rel) && is_array($faq_items_rel)) {
    foreach ($faq_items_rel as $p) {
      $pid = is_object($p) ? $p->ID : (int) $p;
      $faqs[] = [
        'id'      => $pid,
        'title'   => get_the_title($pid),
        'content' => apply_filters('the_content', get_post_field('post_content', $pid)),
      ];
    }
  }
}

// Guard: nothing to show
if (empty($faqs)) {
  return;
}

// Allowed headings
$allowed_tags = ['h1','h2','h3','h4','h5','h6'];
if (!in_array($heading_tag, $allowed_tags, true)) {
  $heading_tag = 'h2';
}
?>

<section
  id="<?php echo esc_attr($section_id); ?>"
  role="region"
  aria-label="<?php echo esc_attr__('Frequently Asked Questions', 'matrix-starter'); ?>"
  class="flex overflow-hidden relative w-full"
  style="background-color: <?php echo esc_attr($section_background); ?>;"
>
  <div class="flex flex-col items-center w-full mx-auto max-w-[1018px] py-[56px] max-lg:px-5<?php echo $padding_classes_str; ?>">
    <!-- Heading -->
    <div class="flex flex-col gap-4 pb-4 mb-8 w-full">
      <?php if (!empty($heading_text)): ?>
        <<?php echo esc_attr($heading_tag); ?>
          class="text-4xl font-bold leading-tight font-red-hat-display lg:text-5xl"
          style="color: <?php echo esc_attr($heading_color); ?>;"
        >
          <?php echo esc_html($heading_text); ?>
        </<?php echo esc_attr($heading_tag); ?>>
      <?php endif; ?>

      <div class="w-8 h-1" aria-hidden="true" role="presentation"
           style="background-color: <?php echo esc_attr($accent_bar_color); ?>;"></div>
    </div>

    <!-- FAQ Items -->
    <div class="flex flex-col gap-5 w-full">
      <div class="flex flex-col gap-5 w-full">
        <?php foreach ($faqs as $i => $faq): ?>
          <?php
            $qid = $section_id . '-q-' . intval($faq['id']);
            $aid = $section_id . '-a-' . intval($faq['id']);
          ?>
          <article
            x-data="{ open: false }"
            class="flex flex-col gap-2 p-4 rounded-lg border transition-all duration-200"
            :style="open
              ? 'border-width:4px;border-color:<?php echo esc_js($active_border_color); ?>'
              : 'border-width:1px;border-color:<?php echo esc_js($border_color); ?>'"
            :aria-expanded="open.toString()"
          >
            <!-- Header / Toggle -->
            <button
              type="button"
              class="flex gap-6 items-center p-2 w-full text-left rounded-lg btn focus:outline-none"
              :style="'box-shadow: 0 0 0 ' + (document.activeElement=== $el ? '2px' : '0') + ' <?php echo esc_js($focus_ring_color); ?> inset'"
              aria-controls="<?php echo esc_attr($aid); ?>"
              :aria-expanded="open.toString()"
              @click="open = !open"
              @keydown.enter.prevent="open = !open"
              @keydown.space.prevent="open = !open"
            >
              <!-- Plus / Minus -->
              <div class="flex flex-shrink-0 justify-center items-center w-8 h-8">
                <svg x-show="!open" class="w-8 h-8" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                  <path d="M16 6.667V25.333M6.667 16H25.333" stroke="#2B3990" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <svg x-show="open" class="w-8 h-8" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                  <path d="M6.667 16H25.333" stroke="#2B3990" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>

              <!-- Question -->
              <h3 id="<?php echo esc_attr($qid); ?>"
                  class="flex-1 text-lg  font-secondary font-[700]"
                  style="color: <?php echo esc_attr($question_color); ?>;">
                <?php echo esc_html($faq['title']); ?>
              </h3>
            </button>

            <!-- Answer -->
            <div
              id="<?php echo esc_attr($aid); ?>"
              x-show="open"
              x-collapse
              class="flex flex-col gap-4 pl-14 mt-2"
              aria-labelledby="<?php echo esc_attr($qid); ?>"
            >
              <div class="text-base font-normal leading-[20px] text-[#344054] wp_editor font-secondary">
                <?php echo wp_kses_post($faq['content']); ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
        <div clas="w-full mx-auto py-24">
            <?php
            $show_cta = (bool) get_sub_field('show_cta');
            $cta_link = get_sub_field('cta_link'); // ACF link array

            if ( $show_cta && !empty($cta_link) && !empty($cta_link['url']) ) :
              $url    = $cta_link['url'];
              $title  = !empty($cta_link['title']) ? $cta_link['title'] : __("View all FAQ's", 'matrix-starter');
              $target = !empty($cta_link['target']) ? $cta_link['target'] : '_self';
              $rel    = ($target === '_blank') ? 'noopener noreferrer' : '';
              $aria   = $title;
            ?>
              <div class="flex justify-center mt-8 w-full">
                <a
                  href="<?php echo esc_url($url); ?>"
                  target="<?php echo esc_attr($target); ?>"
                  rel="<?php echo esc_attr($rel); ?>"
                  role="button"
                  aria-label="<?php echo esc_attr($aria); ?>"
                  class="btn flex relative gap-2 justify-center items-center px-6 py-3.5 bg-white border-2 border-blue-900 border-solid transition-all duration-200 cursor-pointer ease-[ease-in-out] h-[52px] w-fit whitespace-nowrap rounded-[100px] max-md:px-5 max-md:py-3 max-md:h-12 max-sm:px-5 max-sm:py-2.5 max-sm:w-full max-sm:max-w-full max-sm:h-11 hover:bg-blue-50 hover:border-blue-700 active:bg-blue-100"
                >
                  <span class="relative text-lg font-medium leading-6 text-blue-900 max-md:text-base max-md:leading-6 max-sm:text-sm max-sm:leading-5">
                    <?php echo esc_html($title); ?>
                  </span>
                </a>
              </div>
            <?php endif; ?>
        </div>
  </div>
</section>
