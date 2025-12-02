<?php
/**
 * Footer Copyright / Navigation
 * Reads from ACF Options (not a Flexi block).
 */

$links        = get_field('footer_links', 'option');
$credits_text = get_field('credits_text', 'option');

// Base classes (no design options)
$container_classes = 'box-border flex relative justify-between items-center px-6 py-4 mx-auto w-full max-w-screen-xl  max-md:px-4 max-md:py-3 max-sm:flex-col max-sm:gap-4 max-sm:items-start';
$link_text_classes = 'text-sm leading-5 text-violet-950';

// Build a simple array for the select
$options = [];
if (!empty($links) && is_array($links)) {
  foreach ($links as $row) {
    $link = isset($row['link']) ? $row['link'] : null;
    if (!$link || empty($link['url'])) { continue; }
    $options[] = [
      'url'    => esc_url($link['url']),
      'label'  => !empty($link['title']) ? esc_html($link['title']) : esc_html__('Learn more', 'matrix'),
      'target' => !empty($link['target']) ? esc_attr($link['target']) : '_self',
    ];
  }
}
?>

<footer role="contentinfo" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?> footer">
  <nav class="<?php echo esc_attr($container_classes); ?>" aria-label="Footer navigation" role="navigation">
    
    <?php if (!empty($options)) : ?>
      <!-- Desktop/Tablet list (>= sm) -->
      <ul class="hidden sm:flex gap-8 items-start max-md:gap-6 list-none m-0 p-0" role="list">
        <?php foreach ($options as $opt): ?>
          <li class="flex flex-col items-start pt-0.5">
            <a
              href="<?php echo $opt['url']; ?>"
              target="<?php echo $opt['target']; ?>"
              class="nav-link flex gap-1 items-center <?php echo esc_attr($link_text_classes); ?> no-underline transition-colors duration-200 hover:text-violet-800 focus:text-violet-800 focus:outline focus:outline-2 focus:outline-violet-600"
            >
              <span class="text-sm"><?php echo $opt['label']; ?></span>
            </a>
            <div class="nav-underline h-px bg-violet-950 mt-1" role="presentation" aria-hidden="true"></div>
          </li>
        <?php endforeach; ?>
      </ul>

      <!-- Mobile select (< sm ~ below 640px, covering ≤575px) -->
      <div class="w-full sm:hidden">
        <label for="footer-nav-select" class="sr-only"><?php esc_html_e('Footer navigation', 'matrix'); ?></label>
        <select
          id="footer-nav-select"
          class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm leading-5 text-violet-950 focus:outline-none focus:ring-2 focus:ring-violet-600"
          aria-label="<?php esc_attr_e('Footer navigation', 'matrix'); ?>"
        >
          <option value=""><?php esc_html_e('Navigate to…', 'matrix'); ?></option>
          <?php foreach ($options as $opt): ?>
            <option value="<?php echo $opt['url']; ?>" data-target="<?php echo $opt['target']; ?>">
              <?php echo $opt['label']; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php endif; ?>

    <div class="<?php echo esc_attr($link_text_classes); ?>" role="contentinfo" aria-label="Site credits">
      <p class="text-sm max-sm:text-xs m-0">
        <?php echo $credits_text ? esc_html($credits_text) : esc_html__('Designed & Developed by Matrix Internet', 'matrix'); ?>
      </p>
    </div>
  </nav>

  <script>
    // Keyboard nav for desktop list + mobile select navigation
    (function(){
      var nav = document.querySelector('nav[aria-label="Footer navigation"]');
      if (!nav) return;

      // Desktop keyboard nav (only affects the visible list at >= sm)
      var links = nav.querySelectorAll('.nav-link');
      links.forEach(function(link, index){
        link.addEventListener('keydown', function(e){
          switch(e.key){
            case 'ArrowRight':
            case 'ArrowDown':
              e.preventDefault(); links[(index + 1) % links.length].focus(); break;
            case 'ArrowLeft':
            case 'ArrowUp':
              e.preventDefault(); links[(index - 1 + links.length) % links.length].focus(); break;
            case 'Home':
              e.preventDefault(); links[0].focus(); break;
            case 'End':
              e.preventDefault(); links[links.length - 1].focus(); break;
          }
        });
        link.addEventListener('focus', function(){ this.setAttribute('aria-current','true'); });
        link.addEventListener('blur', function(){ this.removeAttribute('aria-current'); });
      });

      // Mobile select behavior
      var sel = document.getElementById('footer-nav-select');
      if (sel) {
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
      }

      nav.setAttribute('aria-live', 'polite');
    })();
  </script>
</footer>
