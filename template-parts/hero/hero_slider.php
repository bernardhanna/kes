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

// Locations payload for map slides (from Locations CPT)
$hero_locations_payload = [];
$location_ids = get_posts([
    'post_type'      => 'locations',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'fields'         => 'ids',
]);
foreach ($location_ids as $loc_id) {
    $lat_raw = get_post_meta($loc_id, 'latitude', true);
    $lng_raw = get_post_meta($loc_id, 'longitude', true);
    if ($lat_raw === '' || $lat_raw === null) {
        $lat_raw = get_field('latitude', $loc_id);
    }
    if ($lng_raw === '' || $lng_raw === null) {
        $lng_raw = get_field('longitude', $loc_id);
    }
    $lat = is_string($lat_raw) ? str_replace(',', '.', trim((string) $lat_raw)) : $lat_raw;
    $lng = is_string($lng_raw) ? str_replace(',', '.', trim((string) $lng_raw)) : $lng_raw;
    if ($lat === '' || $lng === '' || !is_numeric($lat) || !is_numeric($lng)) {
        continue;
    }
    $hero_locations_payload[] = [
        'id'    => (int) $loc_id,
        'title' => (string) get_the_title($loc_id),
        'lat'   => (float) $lat,
        'lng'   => (float) $lng,
        'address'   => (string) get_field('address', $loc_id),
        'url'       => (string) get_field('url', $loc_id),
        'link_label'=> (string) get_field('link_label', $loc_id) ?: 'View details',
    ];
}

// Any slide is a map slide?
$has_map_slide = false;
foreach ($slides as $s) {
    if (!empty($s['slide_type']) && $s['slide_type'] === 'map') {
        $has_map_slide = true;
        break;
    }
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

<section id="<?php echo esc_attr($section_id); ?>" class="flex overflow-hidden relative hero-slider-section">
  <div class="flex flex-col mt-[6rem] items-center w-full <?php echo $padding_classes_str ? esc_attr(' '.$padding_classes_str) : ''; ?>">

    <!-- Fixed height container (533px so slider height stays consistent) -->
    <div class="hero-slider-container relative w-full h-[533px] overflow-hidden <?php echo esc_attr($rounded); ?> <?php echo esc_attr($text_color); ?>">
      <div class="js-hero-slider relative w-full h-full <?php echo $use_slider ? 'is-slick' : ''; ?>">
        <?php foreach ($slides as $slide_index => $slide): ?>
            <?php
            $slide_type  = isset($slide['slide_type']) ? $slide['slide_type'] : 'content';
            $is_map_slide = ($slide_type === 'map');

            if ($is_map_slide) {
                $map_lat   = isset($slide['map_center_lat']) && $slide['map_center_lat'] !== '' ? (float) $slide['map_center_lat'] : 53.349805;
                $map_lng   = isset($slide['map_center_lng']) && $slide['map_center_lng'] !== '' ? (float) $slide['map_center_lng'] : -6.26031;
                $map_zoom  = isset($slide['map_zoom']) && $slide['map_zoom'] !== '' ? (int) $slide['map_zoom'] : 6;
                $map_provider = !empty($slide['map_tile_provider']) ? $slide['map_tile_provider'] : 'cartodb_voyager';
                $map_token   = !empty($slide['map_tile_api_key']) ? $slide['map_tile_api_key'] : '';
                $map_blue    = !empty($slide['map_style_blue']);
                $map_gradient = !empty($slide['map_show_gradient']);
                $map_id      = $section_id . '-slide-' . $slide_index . '-map';
            }

            $bg       = isset($slide['background_image']) ? $slide['background_image'] : null;
            $bg_url   = $bg && !empty($bg['url'])   ? esc_url($bg['url']) : '';
            $bg_alt   = $bg && !empty($bg['alt'])   ? esc_attr($bg['alt']) : 'Hero background';
            $bg_title = $bg && !empty($bg['title']) ? esc_attr($bg['title']) : 'Hero background';

            $bg_mobile   = isset($slide['background_image_mobile']) ? $slide['background_image_mobile'] : null;
            $bg_mobile_url = $bg_mobile && !empty($bg_mobile['url']) ? esc_url($bg_mobile['url']) : '';

            // Default gradient on for content slides when field not set (e.g. pre–slide_type slides)
            $show_gradient = isset($slide['show_gradient']) ? (bool) $slide['show_gradient'] : !$is_map_slide;
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

            <!-- Each slide, fixed/max height; on mobile content in lower portion -->
            <div class="flex relative justify-end items-center w-full h-[533px] sm:justify-center">
              <?php if ($is_map_slide): ?>
                <!-- Map slide: full-bleed Leaflet map -->
                <div class="absolute inset-0 w-full h-full">
                  <?php
                  $custom_marker = isset($slide['map_marker_image']) && is_array($slide['map_marker_image']) && !empty($slide['map_marker_image']['url']) ? $slide['map_marker_image']['url'] : '';
                  if ($custom_marker) {
                      $marker_icon_url = esc_url($custom_marker);
                  } else {
                      $default_pin = get_template_directory_uri() . '/assets/images/location-pin.png';
                      $marker_icon_url = file_exists(get_template_directory() . '/assets/images/location-pin.png') ? $default_pin : '';
                  }
                  ?>
                  <div
                    id="<?php echo esc_attr($map_id); ?>"
                    class="hero-slider-map w-full h-full <?php echo $map_blue ? 'hero-slider-map--blue' : ''; ?>"
                    data-hero-map
                    data-section-id="<?php echo esc_attr($section_id); ?>"
                    data-lat="<?php echo esc_attr($map_lat); ?>"
                    data-lng="<?php echo esc_attr($map_lng); ?>"
                    data-zoom="<?php echo esc_attr($map_zoom); ?>"
                    data-provider="<?php echo esc_attr($map_provider); ?>"
                    data-token="<?php echo esc_attr($map_token); ?>"
                    data-locations-id="<?php echo esc_attr($section_id); ?>-locations"
                    <?php if ($marker_icon_url): ?>data-marker-icon="<?php echo esc_url($marker_icon_url); ?>"<?php endif; ?>
                    role="application"
                    aria-label="Interactive map of locations">
                  </div>
                </div>
                <?php if (!empty($map_gradient)): ?>
                <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(1deg, rgba(38, 34, 98, 0.90) 20.85%, rgba(43, 57, 144, 0.00) 79.62%);" aria-hidden="true"></div>
                <?php endif; ?>
              <?php endif; ?>

              <?php
              // Content slide: desktop = main image (+ gradient); mobile = mobile image or main (+ gradient)
              if (!$is_map_slide):
                // --- Desktop only (641px and up): main background image, with gradient when enabled ---
                if ($bg_url):
                  if ($show_gradient):
                    $desktop_bg = "linear-gradient(90deg, rgba(38, 34, 98, 0.90) 20.39%, rgba(43, 57, 144, 0.00) 80.17%), url('" . esc_url($bg_url) . "') lightgray 50% / cover no-repeat";
                  ?>
                <div
                  class="hero-slide-desktop-only absolute inset-0 w-full h-full bg-center bg-no-repeat bg-cover"
                  style="background: <?php echo esc_attr($desktop_bg); ?>"
                  role="img"
                  aria-label="<?php echo esc_attr($bg_title ? $bg_title : 'Hero background'); ?>">
                </div>
                <?php else: ?>
                <div
                  class="hero-slide-desktop-only absolute inset-0 w-full h-full bg-center bg-no-repeat bg-cover"
                  style="<?php echo esc_attr("background-image:url('{$bg_url}')"); ?>"
                  role="img"
                  aria-label="<?php echo esc_attr($bg_title ? $bg_title : 'Hero background'); ?>">
                </div>
                <?php endif;
                endif; ?>

                <?php if ($show_gradient): ?>
                <!-- Mobile only (640px and down): image then 1deg gradient overlay -->
                <?php if ($bg_mobile_url): ?>
                <div
                  class="hero-slide-mobile-only absolute inset-0 w-full h-full bg-center bg-no-repeat bg-cover"
                  style="<?php echo esc_attr("background-image:url('{$bg_mobile_url}')"); ?>"
                  role="img"
                  aria-hidden="true">
                </div>
                <?php elseif ($bg_url): ?>
                <div
                  class="hero-slide-mobile-only absolute inset-0 w-full h-full bg-center bg-no-repeat bg-cover"
                  style="<?php echo esc_attr("background-image:url('{$bg_url}')"); ?>"
                  role="img"
                  aria-hidden="true">
                </div>
                <?php endif; ?>
                <div
                  class="hero-slide-mobile-only absolute inset-0 w-full h-full pointer-events-none"
                  style="background: linear-gradient(1deg, rgba(38, 34, 98, 0.90) 20.85%, rgba(43, 57, 144, 0.00) 79.62%);"
                  aria-hidden="true"></div>
                <?php
                else:
                  // No gradient: desktop = main image, mobile = mobile image or main
                  if ($bg_mobile_url): ?>
                <div
                  class="hero-slide-mobile-only absolute inset-0 w-full h-full bg-center bg-no-repeat bg-cover"
                  style="<?php echo esc_attr("background-image:url('{$bg_mobile_url}')"); ?>"
                  role="img"
                  aria-hidden="true">
                </div>
                <?php endif; ?>
                <?php if ($bg_url): ?>
                <div
                  class="hero-slide-desktop-only absolute inset-0 w-full h-full bg-center bg-no-repeat bg-cover"
                  style="<?php echo esc_attr("background-image:url('{$bg_url}')"); ?>"
                  role="img"
                  aria-label="<?php echo esc_attr($bg_title ? $bg_title : 'Hero background'); ?>">
                </div>
                <?php if (!$bg_mobile_url): ?>
                <div
                  class="hero-slide-mobile-only absolute inset-0 w-full h-full bg-center bg-no-repeat bg-cover"
                  style="<?php echo esc_attr("background-image:url('{$bg_url}')"); ?>"
                  role="img"
                  aria-hidden="true">
                </div>
                <?php endif;
                endif;
                endif;
              endif; ?>

              <?php if (!$is_map_slide): ?>
              <div class="relative z-10 px-6 pb-8 mx-auto w-full max-w-[69rem] sm:px-8 sm:pb-0 lg:px-12">
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
                    <<?php echo esc_attr($title_tag); ?> class="text-[30px] font-bold leading-[38px] wp_editor font-primary sm:text-5xl lg:text-6xl sm:leading-tight lg:leading-tight max-w-[600px]">
                      <?php echo wp_kses_post($highlighted_title); ?>
                    </<?php echo esc_attr($title_tag); ?>>
                  <?php endif; ?>

                  <?php if ($desc_html): ?>
                    <div class="max-w-2xl wp_editor py-[1.5rem] text-[16px] font-normal leading-[20px] font-secondary sm:text-[18px] sm:font-medium sm:leading-[24px]">
                      <p class="text-[16px] font-normal leading-[20px] font-secondary max-w-[600px] sm:text-[18px] sm:font-medium sm:leading-[24px]"><?php echo wp_kses_post($desc_html); ?></p>
                    </div>
                  <?php endif; ?>

                  <?php if (!empty($buttons)): ?>
                    <div class="flex flex-col gap-4 w-full sm:flex-row sm:items-center sm:gap-6">
                      <?php foreach ($buttons as $btn): ?>
                        <?php
                        $link  = isset($btn['button_link']) ? $btn['button_link'] : null;
                        $style = !empty($btn['button_style']) ? $btn['button_style'] : 'primary';
                        if ($link && is_array($link)) {
                            $url    = !empty($link['url']) ? esc_url($link['url']) : '#';
                            $title  = !empty($link['title']) ? esc_html($link['title']) : 'Learn more';
                            $target = !empty($link['target']) ? esc_attr($link['target']) : '_self';
                            if ($style === 'secondary') {
                                $cls = 'hero-slider-btn-secondary flex items-center justify-center h-[38px] py-[9px] text-[14px] font-medium leading-[20px] font-secondary text-blue-dark bg-base-white border-2 border-blue-dark rounded-full px-6 w-full sm:w-auto sm:h-[52px] sm:py-3.5 sm:text-lg sm:leading-[24px] whitespace-nowrap transition-colors duration-200 hover:bg-teal-light hover:border-blue-dark active:bg-blue-100 active:border-blue-dark focus-visible:outline-none focus-visible:outline-[3px] focus-visible:outline-blue-100 focus-visible:outline-offset-2 focus-visible:bg-base-white';
                            } else {
                                $cls = 'btn-primary flex items-center justify-center h-[38px] py-[9px] text-[14px] font-medium leading-[20px] w-full sm:w-auto sm:h-[52px] sm:py-4 sm:text-lg sm:leading-6 whitespace-nowrap';
                            }
                            echo '<a class="'.esc_attr($cls).'" href="'.$url.'" target="'.$target.'">'. $title .'</a>';
                        }
                        ?>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php endif; // !$is_map_slide ?>

              <?php if ($use_slider): ?>
                <div class="pointer-events-none" aria-hidden="true"></div>
              <?php endif; ?>
            </div>
        <?php endforeach; ?>
      </div>
    </div>

    <?php if ($use_slider && $show_dots): ?>
      <div class="flex relative z-10 justify-center pb-3 mt-2 mb-2 w-full bg-white slick-dots-container" aria-hidden="true"></div>
    <?php endif; ?>
  </div>
</section>

<?php if ($has_map_slide): ?>
<script type="application/json" id="<?php echo esc_attr($section_id); ?>-locations"><?php echo wp_json_encode($hero_locations_payload); ?></script>
<?php endif; ?>

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

<?php if ($has_map_slide): ?>
<script>
(function () {
  var LEAFLET_CSS = "https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css";
  var LEAFLET_JS  = "https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js";
  var sectionId = <?php echo wp_json_encode($section_id); ?>;

  function loadLeaflet(cb) {
    if (typeof window.L !== "undefined") { cb(); return; }
    if (!document.querySelector('link[data-leaflet-css]')) {
      var link = document.createElement("link");
      link.rel = "stylesheet";
      link.href = LEAFLET_CSS;
      link.setAttribute("data-leaflet-css", "1");
      document.head.appendChild(link);
    }
    if (document.querySelector('script[data-leaflet-js]')) {
      var t = setInterval(function() { if (typeof window.L !== "undefined") { clearInterval(t); cb(); } }, 30);
      setTimeout(function() { clearInterval(t); }, 8000);
      return;
    }
    var script = document.createElement("script");
    script.src = LEAFLET_JS;
    script.defer = true;
    script.setAttribute("data-leaflet-js", "1");
    script.onload = cb;
    document.body.appendChild(script);
  }

  function escapeHtml(str) {
    return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
  }

  function initHeroMap(container) {
    if (!container || container.dataset.heroMapInit === "1") return;
    if (typeof window.L === "undefined") return;
    var lat = parseFloat(container.getAttribute("data-lat")) || 53.349805;
    var lng = parseFloat(container.getAttribute("data-lng")) || -6.26031;
    var zoom = parseInt(container.getAttribute("data-zoom"), 10) || 6;
    var provider = container.getAttribute("data-provider") || "osm";
    var token = container.getAttribute("data-token") || "";
    var locationsId = container.getAttribute("data-locations-id");
    var markerIconUrl = container.getAttribute("data-marker-icon") || "";
    var blueStyle = container.classList.contains("hero-slider-map--blue");
    var groups = [];
    if (locationsId) {
      var jsonEl = document.getElementById(locationsId);
      if (jsonEl) try { groups = JSON.parse(jsonEl.textContent || "[]"); } catch (e) {}
    }
    var markerIcon = null;
    if (markerIconUrl) {
      markerIcon = L.icon({
        iconUrl: markerIconUrl,
        iconSize: [40, 50],
        iconAnchor: [20, 50],
        popupAnchor: [0, -50]
      });
    }
    var map = L.map(container, { scrollWheelZoom: true }).setView([lat, lng], zoom);
    var tileUrl, tileOpts = {};
    var wantsJawg = (provider === "jawg-light" || provider === "jawg-dark");
    if (provider === "osm" || (wantsJawg && !token)) {
      tileUrl = "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";
      tileOpts = { maxZoom: 19, attribution: "&copy; OpenStreetMap" };
    } else if (provider === "cartodb_voyager") {
      tileUrl = "https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png";
      tileOpts = { maxZoom: 20, attribution: "&copy; OpenStreetMap &copy; CARTO" };
    } else if (provider === "jawg-dark") {
      tileUrl = "https://tile.jawg.io/jawg-dark/{z}/{x}/{y}{r}.png?access-token=" + encodeURIComponent(token);
      tileOpts = { maxZoom: 22, attribution: "&copy; Jawg" };
    } else {
      tileUrl = "https://tile.jawg.io/jawg-light/{z}/{x}/{y}{r}.png?access-token=" + encodeURIComponent(token);
      tileOpts = { maxZoom: 22, attribution: "&copy; Jawg" };
    }
    L.tileLayer(tileUrl, tileOpts).addTo(map);
    var bounds = [];
    groups.forEach(function(g) {
      if (!g || !Number.isFinite(g.lat) || !Number.isFinite(g.lng)) return;
      var marker = L.marker([g.lat, g.lng], markerIcon ? { icon: markerIcon } : {}).addTo(map);
      bounds.push([g.lat, g.lng]);
      var popup = "<div style=\"max-width:240px;\">";
      popup += "<div style=\"font-weight:700;margin-bottom:4px;\">" + escapeHtml(g.title || "") + "</div>";
      if (g.address) popup += "<div style=\"font-size:12px;margin-bottom:4px;\"><strong>Address:</strong> " + escapeHtml(g.address) + "</div>";
      if (g.url) popup += "<a href=\"" + escapeHtml(g.url) + "\" style=\"font-size:12px;\">" + escapeHtml(g.link_label || "View details") + "</a>";
      popup += "</div>";
      marker.bindPopup(popup);
    });
    if (bounds.length > 1) map.fitBounds(bounds, { padding: [30, 30] });
    if (bounds.length === 1) map.setView(bounds[0], Math.max(zoom, 12));
    container.dataset.heroMapInit = "1";
    container._leafletMap = map;
    setTimeout(function() { map.invalidateSize(); }, 50);
    setTimeout(function() { map.invalidateSize(); }, 250);
  }

  function runInits() {
    var section = document.getElementById(sectionId);
    if (!section) return;
    var maps = section.querySelectorAll("[data-hero-map]");
    for (var i = 0; i < maps.length; i++) initHeroMap(maps[i]);
  }

  function onSlideChange() {
    var section = document.getElementById(sectionId);
    if (!section) return;
    var currentSlide = section.querySelector(".slick-current [data-hero-map]");
    if (currentSlide && currentSlide._leafletMap) currentSlide._leafletMap.invalidateSize();
  }

  document.addEventListener("DOMContentLoaded", function() {
    loadLeaflet(function() {
      runInits();
      var $wrap = document.querySelector("#" + sectionId + " .js-hero-slider");
      if ($wrap && $wrap.classList.contains("is-slick") && typeof jQuery !== "undefined") {
        jQuery($wrap).on("afterChange", onSlideChange);
      }
    });
  });
})();
</script>
<?php endif; ?>
