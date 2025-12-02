<?php
/**
 * Footer (Theme Options)
 * - Logo from options
 * - Footer links from options (NOT a WP menu)
 * - Social links from options
 * - Mobile: select dropdown replaces the list on < sm
 */

$logo             = get_field('footer_logo', 'option');
$footer_links     = get_field('footer_primary_links', 'option'); // repeater of ACF link arrays
$follow_us_text   = get_field('follow_us_text', 'option') ?: __('Follow us', 'matrix');
$social_links     = get_field('social_links', 'option');

// Logo meta
$logo_url   = $logo['url']   ?? '';
$logo_alt   = $logo['alt']   ?? __('Company Logo', 'matrix');
$logo_title = $logo['title'] ?? __('Company Logo', 'matrix');

// Build an array of links for desktop list and mobile select
$link_items = [];
if (!empty($footer_links) && is_array($footer_links)) {
    foreach ($footer_links as $row) {
        $link = $row['link'] ?? null;
        if (!$link || empty($link['url'])) continue;

        $link_items[] = [
            'url'    => esc_url($link['url']),
            'label'  => !empty($link['title']) ? esc_html($link['title']) : esc_html__('Learn more', 'matrix'),
            'target' => !empty($link['target']) ? esc_attr($link['target']) : '_self',
        ];
    }
}
?>

<footer class="w-full relative" role="contentinfo" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?> footer">
  <div class="w-full mx-auto max-w-container max-xl:px-5">

    <!-- Logo row -->
    <div class="flex flex-col items-start pt-5 w-full">
      <?php if ($logo_url): ?>
        <img
          src="<?php echo esc_url($logo_url); ?>"
          alt="<?php echo esc_attr($logo_alt); ?>"
          title="<?php echo esc_attr($logo_title); ?>"
          class="object-contain max-w-full w-[162px]"
        />
      <?php endif; ?>
    </div>

    <div class="flex flex-wrap justify-between items-center  py-4 mt-4 w-full">
      <nav class="flex flex-1 items-center my-auto basis-0 min-w-60" aria-label="Footer navigation" role="navigation">
        <?php if (!empty($link_items)): ?>

          <!-- Desktop/Tablet list (>= sm) -->
          <ul class="hidden sm:flex flex-wrap gap-6 items-center text-base font-medium leading-none text-violet-950 list-none m-0 p-0" role="list">
            <?php foreach ($link_items as $it): ?>
              <li class="flex flex-col justify-center pt-1">
                <a
                  href="<?php echo $it['url']; ?>"
                  target="<?php echo $it['target']; ?>"
                  class="nav-link flex gap-1 items-center text-violet-950 hover:text-violet-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-600"
                >
                  <span class="text-base"><?php echo $it['label']; ?></span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>

          <!-- Mobile select (< sm) -->
          <div class="w-full sm:hidden">
            <label for="footer-nav-select" class="sr-only"><?php esc_html_e('Footer navigation', 'matrix'); ?></label>
            <select
              id="footer-nav-select"
              class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm leading-5 text-violet-950 focus:outline-none focus:ring-2 focus:ring-violet-600"
              aria-label="<?php esc_attr_e('Footer navigation', 'matrix'); ?>"
            >
              <option value=""><?php esc_html_e('Quick Links…', 'matrix'); ?></option>
              <?php foreach ($link_items as $it): ?>
                <option value="<?php echo $it['url']; ?>" data-target="<?php echo $it['target']; ?>">
                  <?php echo $it['label']; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

        <?php endif; ?>
      </nav>

      <!-- Social -->
      <?php if (!empty($social_links) && is_array($social_links)): ?>
        <div class="flex gap-4 justify-end items-center my-auto min-w-60 py-4 sm:py-0" role="complementary" aria-label="<?php esc_attr_e('Social media links', 'matrix'); ?>">
          <p class="text-base leading-none text-violet-950 m-0 max-sm:hidden sm:block">
            <?php echo esc_html($follow_us_text); ?>
          </p>
          <ul class="flex gap-4 items-center list-none m-0 p-0" role="list">
            <?php foreach ($social_links as $row):
                $label = $row['label'] ?? '';
                $url   = $row['url'] ?? '';
                $icon  = $row['icon_image'] ?? null;
                if (!$url || !$icon) continue;

                $icon_url   = $icon['url']   ?? '';
                $icon_alt   = $icon['alt']   ?? $label;
                $icon_title = $icon['title'] ?? $label;
            ?>
              <li>
                <a href="<?php echo esc_url($url); ?>"
                   class="block w-9 h-9 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-600"
                   aria-label="<?php echo esc_attr(sprintf(__('Follow us on %s', 'matrix'), $label)); ?>"
                   target="_blank" rel="noopener noreferrer">
                  <?php if ($icon_url): ?>
                    <img
                      src="<?php echo esc_url($icon_url); ?>"
                      alt="<?php echo esc_attr($icon_alt); ?>"
                      title="<?php echo esc_attr($icon_title); ?>"
                      class="object-contain w-9 h-9"
                    />
                  <?php endif; ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    // Mobile select navigation
    (function(){
      var sel = document.getElementById('footer-nav-select');
      if (!sel) return;
      sel.addEventListener('change', function(){
        var url = this.value;
        if (!url) return;
        var opt = this.options[this.selectedIndex];
        var tgt = opt && opt.getAttribute('data-target') ? opt.getAttribute('data-target') : '_self';
        if (tgt === '_blank') {
          window.open(url, '_blank', 'noopener');
        } else {
          window.location.href = url;
        }
      });
    })();
  </script>
</footer>
