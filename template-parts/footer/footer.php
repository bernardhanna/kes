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
            'active' => (isset($link['url']) && $link['url'] !== '' && (string) $link['url'] === (string) get_permalink(get_queried_object_id())),
        ];
    }
}
?>

<footer class="relative w-full" role="contentinfo" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?> footer">
  <!-- Full-width border line -->
  <div class="w-full border-t-[2px] border-[#CBE9E1]" aria-hidden="true"></div>

  <!-- Logo row (content at max width) -->
  <div class="pt-5 mx-auto mb-4 w-full max-w-container max-xl:px-5">
    <?php if ($logo_url): ?>
      <img
        src="<?php echo esc_url($logo_url); ?>"
        alt="<?php echo esc_attr($logo_alt); ?>"
        title="<?php echo esc_attr($logo_title); ?>"
        class="object-contain max-w-full w-[162px]"
      />
    <?php endif; ?>
  </div>

  <!-- Full-width border line -->
  <div class="w-full border-t-[2px] border-[#CBE9E1]" aria-hidden="true"></div>

  <!-- Nav + Social (content at max width) -->
  <div class="flex flex-wrap justify-between items-center py-4 mx-auto w-full max-w-container max-xl:px-5 max-md:flex-col max-md:items-start">
      <nav class="flex flex-1 items-center my-auto basis-0 min-w-60" aria-label="Footer navigation" role="navigation">
        <?php if (!empty($link_items)): ?>

          <!-- Desktop/Tablet list (>= sm) -->
          <ul class="hidden flex-wrap gap-6 items-center p-0 m-0 list-none sm:flex max-md:flex-col max-md:items-start" role="list">
            <?php foreach ($link_items as $it): ?>
              <li class="flex flex-col justify-center pt-1">
                <a
                  href="<?php echo $it['url']; ?>"
                  target="<?php echo $it['target']; ?>"
                  class="footer-nav-link flex gap-1 items-center font-secondary text-base font-medium leading-[22px] text-primary relative pb-3 after:content-[''] after:block after:absolute after:left-0 after:bottom-[0.5rem] after:h-[2px] after:w-0 after:bg-primary after:transition-[width_0.3s_ease] hover:after:w-full [&.active-item]:after:w-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary max-md:font-[500] <?php echo !empty($it['active']) ? 'active-item' : ''; ?>"
                  <?php echo !empty($it['active']) ? ' aria-current="page"' : ''; ?>
                >
                  <?php echo $it['label']; ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>

          <!-- Mobile select (< sm) -->
          <div class="w-full sm:hidden">
            <label for="footer-nav-select" class="sr-only"><?php esc_html_e('Footer navigation', 'matrix'); ?></label>
            <select
              id="footer-nav-select"
              class="block px-3 py-2 w-full text-sm leading-5 bg-white rounded-md border border-gray-300 text-primary focus:outline-none focus:ring-2 focus:ring-text-primary-light"
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
        <div class="flex gap-4 justify-end items-center py-4 my-auto min-w-60 sm:py-0 max-md:flex-col max-md:items-start max-md:mt-4" role="complementary" aria-label="<?php esc_attr_e('Social media links', 'matrix'); ?>">
          <p class="m-0 font-secondary text-base font-normal leading-[20px] text-primary max-sm:hidden sm:block">
            <?php echo esc_html($follow_us_text); ?>
          </p>
          <ul class="flex gap-4 items-center p-0 m-0 list-none" role="list">
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
                   class="block w-9 h-9 underline cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-text-primary-light hover:non-underline hover:opacity-50"
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
