<?php
function my_custom_pagination()
{
  global $wp_query;

  $total_pages  = $wp_query->max_num_pages;
  $current_page = max(1, get_query_var('paged'));

  // Only show pagination if more than one page.
  if ($total_pages <= 1) {
    return;
  }

  // Build array of page links.
  $links_array = paginate_links([
    'total'     => $total_pages,
    'current'   => $current_page,
    'type'      => 'array',
    'prev_next' => false, // <--- IMPORTANT: disable WP’s own prev/next
    'end_size'  => 1,
    'mid_size'  => 0,
  ]);
  $num_base = 'font-red-hat-text text-[16px] leading-[22px] [font-weight:700]';
  $page_link = 'inline-flex flex-col justify-center items-center min-w-[48px] h-12 rounded-full bg-transparent border-0 ' . $num_base
    . ' text-[color:var(--Blue-100,#00ACD8)] hover:bg-transparent hover:text-[color:var(--Blue-300,#2B3990)]'
    . ' focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--Blue-100,#00ACD8)] focus-visible:ring-offset-2 transition-colors';
  $page_current = 'flex flex-col justify-center items-center w-12 h-12 shrink-0 rounded-full border-0 ' . $num_base
    . ' bg-[color:var(--Blue-300,#2B3990)] text-[color:var(--Base-White,#FFF)] pointer-events-none';
  $nav_side = 'flex items-center gap-2 font-red-hat-text text-[16px] leading-[22px] [font-weight:700] text-[color:var(--Blue-300,#2B3990)] hover:text-[color:var(--Blue-100,#00ACD8)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--Blue-100,#00ACD8)] focus-visible:ring-offset-2 transition-colors';
?>
  <nav class="pagination-nav" aria-label="Pagination navigation">
    <ul class="flex flex-wrap gap-8 justify-center items-center p-0 list-none whitespace-nowrap">

      <!-- BACK link -->
      <li>
        <?php if (get_previous_posts_link()): ?>
          <a href="<?php echo esc_url(get_pagenum_link($current_page - 1)); ?>"
            class="<?php echo esc_attr($nav_side); ?>"
            aria-label="Go to previous page">
            <svg class="shrink-0 text-[color:var(--Blue-300,#2B3990)]" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none" aria-hidden="true">
              <path d="M20 24L12 16L20 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <span>Back</span>
          </a>
        <?php else: ?>
          <span class="flex items-center gap-2 opacity-40 cursor-not-allowed text-[color:var(--Blue-100,#00ACD8)] font-red-hat-text text-[16px] leading-[22px] [font-weight:700]"
            aria-disabled="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none" aria-hidden="true">
              <path d="M20 24L12 16L20 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <span>Back</span>
          </span>
        <?php endif; ?>
      </li>

      <!-- Numbered pages + dots -->
      <li>
        <ul class="flex flex-wrap gap-x-1 sm:gap-x-2 justify-center items-center p-0 my-auto list-none">
          <?php foreach ($links_array as $link): ?>
            <?php
            // Check for "dots" link: <span class="dots">…</span>.
            if (strpos($link, 'dots') !== false) {
              echo '<li><span class="inline-flex items-center justify-center min-w-[48px] h-12 px-1 font-red-hat-text text-[16px] leading-[22px] [font-weight:700] text-[color:var(--Blue-100,#00ACD8)]" aria-hidden="true">…</span></li>';
              continue;
            }

            // Current page: merge Tailwind into existing class="page-numbers current".
            if (strpos($link, 'current') !== false) {
              $link = preg_replace_callback(
                '/class="([^"]*)"/',
                static function ($m) use ($page_current) {
                  return 'class="' . esc_attr(trim($m[1] . ' ' . $page_current)) . '"';
                },
                $link,
                1
              );
              echo '<li>' . $link . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
              continue;
            }

            // Page number links: merge into class="page-numbers".
            if (preg_match('/class="([^"]*)"/', $link)) {
              $link = preg_replace_callback(
                '/class="([^"]*)"/',
                static function ($m) use ($page_link) {
                  return 'class="' . esc_attr(trim($m[1] . ' ' . $page_link)) . '"';
                },
                $link,
                1
              );
            } else {
              $link = str_replace('<a ', '<a class="' . esc_attr($page_link) . '" ', $link, 1);
            }
            echo '<li>' . $link . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
          <?php endforeach; ?>
        </ul>
      </li>

      <!-- NEXT link -->
      <li>
        <?php if (get_next_posts_link()): ?>
          <a href="<?php echo esc_url(get_pagenum_link($current_page + 1)); ?>"
            class="<?php echo esc_attr($nav_side); ?>"
            aria-label="Go to next page">
            <span>Next</span>
            <svg class="shrink-0 text-[color:var(--Blue-300,#2B3990)]" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none" aria-hidden="true">
              <path d="M12 24L20 16L12 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
          </a>
        <?php else: ?>
          <span class="flex gap-2 items-center opacity-40 cursor-not-allowed text-[color:var(--Blue-100,#00ACD8)] font-red-hat-text text-[16px] leading-[22px] [font-weight:700]"
            aria-disabled="true">
            <span>Next</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none" aria-hidden="true">
              <path d="M12 24L20 16L12 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
          </span>
        <?php endif; ?>
      </li>
    </ul>
  </nav>
<?php
}
?>