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
    // Optional: output nothing or a placeholder
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
$prev_arrow_markup = '<button type="button" class="slick-prev absolute left-4 top-1/2 -translate-y-1/2 z-20">'.($prev_img_html ?: '&#10094;').'</button>';
$next_arrow_markup = '<button type="button" class="slick-next absolute right-4 top-1/2 -translate-y-1/2 z-20">'.($next_img_html ?: '&#10095;').'</button>';
?>

<section id="<?php echo esc_attr($section_id); ?>" class="relative flex overflow-hidden <?php echo esc_attr($rounded); ?>">
  <div class="flex flex-col items-center w-full <?php echo $padding_classes_str ? esc_attr(' '.$padding_classes_str) : ''; ?>">

    <div class="relative w-full min-h-screen  overflow-hidden <?php echo esc_attr($rounded); ?> <?php echo esc_attr($text_color); ?>">
      <div class="js-hero-slider relative w-full h-full <?php echo $use_slider ? 'is-slick' : ''; ?>">
        <?php foreach ($slides as $slide_index => $slide): ?>
            <?php
            $bg = isset($slide['background_image']) ? $slide['background_image'] : null;
            $bg_url   = $bg && !empty($bg['url'])   ? esc_url($bg['url']) : '';
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

            <div class="relative w-full min-h-screen flex items-center justify-center">
              <?php if ($bg_url): ?>
                <img src="<?php echo $bg_url; ?>"
                     alt="<?php echo $bg_alt; ?>"
                     title="<?php echo $bg_title; ?>"
                     class="absolute inset-0 w-full h-full object-cover" />
              <?php endif; ?>

              <?php if ($show_gradient): ?>
                <div class="absolute inset-0 bg-gradient-to-r <?php echo esc_attr($overlay_from . ' ' . $overlay_via . ' ' . $overlay_to); ?>"></div>
              <?php endif; ?>

              <div class="relative z-10 w-full max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="flex flex-col items-start justify-center py-16 sm:py-20 lg:py-24">

                  <?php if ($title_html): ?>
                    <<?php echo esc_attr($title_tag); ?> class="wp_editor font-bold font-primary text-4xl sm:text-5xl lg:text-6xl leading-tight sm:leading-tight lg:leading-tight">
                      <?php echo wp_kses_post($title_html); ?>
                    </<?php echo esc_attr($title_tag); ?>>
                  <?php endif; ?>

                  <?php if ($desc_html): ?>
                    <div class="wp_editor mt-6 sm:mt-8 max-w-2xl">
                      <p class="font-secondary font-medium text-base sm:text-lg lg:text-xl leading-relaxed"><?php echo wp_kses_post($desc_html); ?></p>
                    </div>
                  <?php endif; ?>

                  <?php if (!empty($buttons)): ?>
                    <div class="mt-8 sm:mt-10 flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
                      <?php foreach ($buttons as $btn): ?>
                        <?php
                        $link = isset($btn['button_link']) ? $btn['button_link'] : null;
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
                <!-- Arrows rendered via Slick option; we also keep space for absolute positioning -->
                <div class="pointer-events-none" aria-hidden="true"></div>
              <?php endif; ?>
            </div>
        <?php endforeach; ?>
      </div>

      <?php if ($use_slider && $show_dots): ?>
        <!-- Slick dots will render here; centered by default -->
        <div class="slick-dots-container w-full flex justify-center mt-6 mb-2"></div>
      <?php endif; ?>
    </div>

  </div>
</section>

<?php if ($use_slider): ?>
<script>
  (function($){
    // Defer until window load + retry if slick isn't ready yet
    function initHeroSlider() {
      var $wrap = $('#<?php echo esc_js($section_id); ?>');
      var $el = $wrap.find('.js-hero-slider');

      if (!$el.length) return;

      if (typeof $.fn.slick !== 'function') {
        // Slick not loaded yet (likely enqueued in footer) — retry shortly
        return setTimeout(initHeroSlider, 120);
      }

      if ($el.hasClass('is-initialized')) return; // avoid double-init
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
