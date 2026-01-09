<?php
/**
 * Hero Slider (Flexible Content block)
 * - Becomes a Slick carousel when slides > 1
 * - Arrows are custom uploadable images
 * - Dots (indicators) centered below
 *
 * Requirements:
 * - Use within a Flexible Content layout row.
 * - All data via get_sub_field().
 */

// Collect fields
$slides         = get_sub_field('slides');
$text_color     = get_sub_field('text_color') ?: 'text-white';
$show_dots      = (bool) get_sub_field('show_dots');
$show_gradient  = null; // per slide
$overlay_from   = get_sub_field('overlay_from') ?: 'from-blue-dark/90';
$overlay_via    = get_sub_field('overlay_via') ?: 'via-blue-dark/50';
$overlay_to     = get_sub_field('overlay_to') ?: 'to-transparent';
$rounded        = get_sub_field('rounded') ?: 'rounded-none';

$arrow_prev     = get_sub_field('arrow_prev');
$arrow_next     = get_sub_field('arrow_next');

// Padding classes builder
$padding_classes = [];
if (have_rows('padding_settings')) {
    while (have_rows('padding_settings')) {
        the_row();
        $screen_size   = get_sub_field('screen_size');
        $padding_top   = get_sub_field('padding_top');
        $padding_bottom= get_sub_field('padding_bottom');
        if ($screen_size !== '' && $padding_top !== '') {
            $padding_classes[] = esc_attr("{$screen_size}:pt-[{$padding_top}rem]");
        }
        if ($screen_size !== '' && $padding_bottom !== '') {
            $padding_classes[] = esc_attr("{$screen_size}:pb-[{$padding_bottom}rem]");
        }
    }
}
$padding_classes_str = implode(' ', $padding_classes);

// Random section ID
$section_id = 'hero-slider-' . uniqid();

// Basic guards
if (empty($slides) || !is_array($slides)) {
    return;
}

// Determine if we need slick
$use_slider = count($slides) > 1;

// Arrow HTML (Slick prev/next)
$prev_img_html = '';
$next_img_html = '';
if (!empty($arrow_prev) && is_array($arrow_prev)) {
    $prev_src = !empty($arrow_prev['url']) ? esc_url($arrow_prev['url']) : '';
    $prev_alt = !empty($arrow_prev['alt']) ? esc_attr($arrow_prev['alt']) : 'Previous';
    if ($prev_src) {
        $prev_img_html = '<img src="'.$prev_src.'" alt="'.$prev_alt.'" />';
    }
}
if (!empty($arrow_next) && is_array($arrow_next)) {
    $next_src = !empty($arrow_next['url']) ? esc_url($arrow_next['url']) : '';
    $next_alt = !empty($arrow_next['alt']) ? esc_attr($arrow_next['alt']) : 'Next';
    if ($next_src) {
        $next_img_html = '<img src="'.$next_src.'" alt="'.$next_alt.'" />';
    }
}

// Build arrow buttons (fallback to simple chevrons if no upload)
$prev_arrow_markup = '<button type="button" class="absolute left-4 top-1/2 z-20 -translate-y-1/2 slick-prev">'.($prev_img_html ?: '&#10094;').'</button>';
$next_arrow_markup = '<button type="button" class="absolute right-4 top-1/2 z-20 -translate-y-1/2 slick-next">'.($next_img_html ?: '&#10095;').'</button>';
?>

<section id="<?php echo esc_attr($section_id); ?>" class="flex overflow-hidden relative">
  <div class="flex flex-col mt-[4.5rem] items-center w-full <?php echo $padding_classes_str ? esc_attr(' '.$padding_classes_str) : ''; ?>">

    <!-- Fixed/Max height container -->
    <div class="relative w-full overflow-hidden <?php echo esc_attr($rounded); ?> <?php echo esc_attr($text_color); ?>">
      <div class="js-hero-slider relative w-full h-full <?php echo $use_slider ? 'is-slick' : ''; ?>">
        <?php foreach ($slides as $slide_index => $slide): ?>
            <?php
            $bg       = isset($slide['background_image']) ? $slide['background_image'] : null;
            $bg_url   = $bg && !empty($bg['url'])   ? esc_url($bg['url']) : '';
            // alt/title are not used on CSS backgrounds; keep for a11y fallback if needed
            $bg_alt   = $bg && !empty($bg['alt'])   ? esc_attr($bg['alt']) : 'Hero background';
            $bg_title = $bg && !empty($bg['title']) ? esc_attr($bg['title']) : 'Hero background';

            $show_gradient = !empty($slide['show_gradient']);
            $title_tag  = !empty($slide['title_tag']) ? $slide['title_tag'] : 'h1';
            $title_html = !empty($slide['title']) ? $slide['title'] : '';
            $desc_html  = !empty($slide['description']) ? $slide['description'] : '';

            // Buttons
            $buttons = !empty($slide['buttons']) && is_array($slide['buttons']) ? $slide['buttons'] : [];

            // Sanitize tag option
            $allowed_tags = ['h1','h2','h3','h4','h5','h6','span','p'];
            if (!in_array($title_tag, $allowed_tags, true)) {
                $title_tag = 'h1';
            }
            ?>

            <!-- Each slide, fixed/max height -->
            <div class="flex relative justify-center items-center w-full h-[533px]">
              <?php if ($bg_url): ?>
                <!-- Background image via CSS -->
                <div
                  class="absolute inset-0 w-full h-full bg-center bg-no-repeat bg-cover"
                  style="<?php echo esc_attr("background-image:url('{$bg_url}')"); ?>"
                  role="img"
                  aria-label="<?php echo $bg_title ? $bg_title : 'Hero background'; ?>">
                </div>
              <?php endif; ?>

              <?php if ($show_gradient): ?>
                <div class="absolute inset-0 bg-gradient-to-r <?php echo esc_attr($overlay_from . ' ' . $overlay_via . ' ' . $overlay_to); ?>"></div>
              <?php endif; ?>

              <div class="relative z-10 px-6 mx-auto w-full max-w-[69rem] sm:px-8 lg:px-12">
                <div class="flex flex-col justify-center items-start pt-[2.5]">
                  <?php if ($title_html): ?>
                    <?php
                    if (!function_exists('highlight_every_second_word')) {
                      function highlight_every_second_word($text) {
                        if (!is_string($text) || $text === '') return '';
                        $parts = preg_split('/(\s+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
                        $wordIndex = 0;
                        foreach ($parts as $k => $part) {
                          if ($part !== '' && !preg_match('/^\s+$/u', $part)) {
                            $escaped = esc_html($part);
                            $parts[$k] = ($wordIndex % 2 === 1)
                              ? '<span class="text-primary-light">'.$escaped.'</span>'
                              : $escaped;
                            $wordIndex++;
                          }
                        }
                        return implode('', $parts);
                      }
                    }
                    $clean_title = wp_strip_all_tags($title_html);
                    $highlighted_title = highlight_every_second_word($clean_title);
                    ?>
                    <<?php echo esc_attr($title_tag); ?> class="text-4xl font-bold leading-tight wp_editor font-primary sm:text-5xl lg:text-6xl sm:leading-tight lg:leading-tight max-w-[600px]">
                      <?php echo wp_kses_post($highlighted_title); ?>
                    </<?php echo esc_attr($title_tag); ?>>
                  <?php endif; ?>

                  <?php if ($desc_html): ?>
                    <div class="max-w-2xl wp_editor py-[1.5rem] text-[18px] font-medium leading-[24px] font-secondary">
                      <p class="text-[18px] font-medium leading-[24px] font-secondary max-w-[600px]"><?php echo wp_kses_post($desc_html); ?></p>
                    </div>
                  <?php endif; ?>

                  <?php if (!empty($buttons)): ?>
                    <div class="flex flex-row gap-4 items-start w-full sm:items-center sm:gap-6">
                      <?php foreach ($buttons as $btn): ?>
                        <?php
                        $link  = isset($btn['button_link']) ? $btn['button_link'] : null;
                        $style = !empty($btn['button_style']) ? $btn['button_style'] : 'primary';
                        if ($link && is_array($link)) {
                            $url    = !empty($link['url']) ? esc_url($link['url']) : '#';
                            $title  = !empty($link['title']) ? esc_html($link['title']) : 'Learn more';
                            $target = !empty($link['target']) ? esc_attr($link['target']) : '_self';
                            $cls = $style === 'secondary' ? 'btn-secondary' : 'btn-primary';
                            echo '<a class="'.esc_attr($cls).' whitespace-nowrap" href="'.$url.'" target="'.$target.'">'. $title .'</a>';
                        }
                        ?>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

              <?php if ($use_slider): ?>
                <div class="pointer-events-none" aria-hidden="true"></div>
              <?php endif; ?>
            </div>
        <?php endforeach; ?>
      </div>

      <?php if ($use_slider && $show_dots): ?>
        <div class="flex justify-center mt-6 mb-2 w-full slick-dots-container"></div>
      <?php endif; ?>
    </div>

  </div>
</section>

<?php if ($use_slider): ?>
<script>
  (function($){
    function initHeroSlider() {
      var $wrap = $('#<?php echo esc_js($section_id); ?>');
      var $el = $wrap.find('.js-hero-slider');

      if (!$el.length) return;

      if (typeof $.fn.slick !== 'function') {
        return setTimeout(initHeroSlider, 120);
      }

      if ($el.hasClass('is-initialized')) return;
      $el.addClass('is-initialized');

      $el.slick({
        arrows: true,
        dots: <?php echo $show_dots ? 'true' : 'false'; ?>,
        appendDots: $wrap.find('.slick-dots-container'),
        prevArrow: '<?php echo wp_kses_post($prev_arrow_markup); ?>',
        nextArrow: '<?php echo wp_kses_post($next_arrow_markup); ?>',
        adaptiveHeight: false,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 5000,
        pauseOnHover: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        cssEase: "ease"
      });
    }

    if (document.readyState === 'complete') {
      initHeroSlider();
    } else {
      window.addEventListener('load', initHeroSlider);
    }
  })(jQuery);
</script>
<?php endif; ?>
