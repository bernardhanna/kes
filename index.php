<?php
get_header();

// Get the current queried object (category, tag, date, etc.)
$queried_object = get_queried_object();
$category_slug  = is_category() ? $queried_object->slug : 'all';

$blog_page_id = get_option('page_for_posts');

// Load hero (page for posts or category override)
if (is_home() && $blog_page_id) {
    load_hero_templates($blog_page_id);
} elseif (is_category()) {
    $category_id = get_queried_object_id();
    if (have_rows('hero_flexible_content', 'category_' . $category_id)) {
        echo '<div class="w-full">';
        load_hero_templates('category_' . $category_id);
        echo '</div>';
    } elseif ($blog_page_id) {
        echo '<div class="w-full">';
        load_hero_templates($blog_page_id);
        echo '</div>';
    }
}

// Load page flexible content (below hero)
if ($blog_page_id) {
  echo '<div class="w-full">';
  load_flexible_content_templates($blog_page_id);
  echo '</div>';
}
?>

<?php
if (function_exists('matrix_starter_render_archive_index_header')) {
    $blog_opts = get_field('blog_settings', 'option') ?: [];
    $show_band = ! array_key_exists('show_blog_index_header', $blog_opts) || ! empty($blog_opts['show_blog_index_header']);
    if ($show_band) {
        $blog_page_id = get_option('page_for_posts');
        $idx_heading   = isset($blog_opts['blog_index_heading']) ? trim((string) $blog_opts['blog_index_heading']) : '';
        if ($idx_heading === '') {
            $idx_heading = $blog_page_id ? get_the_title($blog_page_id) : __('Blog', 'matrix-starter');
        }
        matrix_starter_render_archive_index_header([
            'heading'      => $idx_heading,
            'heading_tag'  => $blog_opts['blog_index_heading_tag'] ?? 'h2',
            'intro'        => $blog_opts['blog_index_intro'] ?? '',
            'bg_color'     => $blog_opts['blog_index_bg'] ?? '#ffffff',
            'accent_color' => $blog_opts['blog_index_underline'] ?? '#00ACD8',
            // Main posts index only (not category/date views that also use index.php).
            'inner_wrapper_class' => is_home()
                ? 'flex flex-col items-center pt-[5rem] pb-5 mx-auto w-full max-w-container max-xl:px-5'
                : '',
        ]);
    }
}
?>

<main class="overflow-hidden w-full min-h-fit site-main">
  <div class="w-full"
       x-data="blogFilter({
         initialCategory: '<?php echo esc_js($category_slug ?: 'all'); ?>',
       })">

    <!-- Filters -->
    <div class="flex flex-col justify-center items-start mx-auto py-6 w-full max-w-[1085px] px-8 text-sm leading-none max-xl:px-5">
      <div class="flex flex-wrap gap-6 items-center max-md:max-w-full">

        <div class="self-stretch my-auto font-red-hat-text text-[14px] font-medium leading-5 text-[#262262]" id="filterLabel"><?php esc_html_e('Filter by', 'matrix-starter'); ?></div>

        <div class="flex flex-wrap gap-4 items-center self-stretch my-auto max-md:max-w-full"
             role="radiogroup"
             aria-labelledby="filterLabel">

          <button
            type="button"
            role="radio"
            class="inline-flex gap-2 items-center justify-center self-stretch px-6 py-3 my-auto min-h-[44px] min-w-[44px] rounded-[100px] max-md:px-5 whitespace-nowrap font-red-hat-text text-[14px] font-medium leading-5 border border-solid border-[#262262] hover:border-[#00ACD8] transition-[color,background-color,border-color] duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#00ACD8] focus-visible:ring-offset-2 focus-visible:ring-offset-white"
            :class="activeCategory === 'all' ? 'bg-[#262262] text-white' : 'bg-white text-[#262262] hover:bg-[#00ACD8]'"
            :aria-checked="activeCategory === 'all' ? 'true' : 'false'"
            @click="setCategory('all')"
            @keydown.enter.prevent="setCategory('all')"
            @keydown.space.prevent="setCategory('all')"
          >
            <?php esc_html_e('All', 'matrix-starter'); ?>
          </button>

          <?php
          $filter_exclude_ids = array_filter([
            get_cat_ID('All') ?: null,
            (int) get_option('default_category') ?: null,
            ($uncat = get_category_by_slug('uncategorized')) ? (int) $uncat->term_id : null,
          ]);
          $filter_exclude_ids = array_values(array_unique($filter_exclude_ids));
          $categories         = get_categories(
            $filter_exclude_ids ? ['exclude' => $filter_exclude_ids] : []
          );
          foreach ($categories as $category) :
            $slug = esc_attr($category->slug);
            $name = esc_html($category->name);
          ?>
            <button
              type="button"
              role="radio"
              class="inline-flex gap-2 items-center justify-center self-stretch px-6 py-3 my-auto min-h-[44px] min-w-[44px] rounded-[100px] max-md:px-5 whitespace-nowrap font-red-hat-text text-[14px] font-medium leading-5 border border-solid border-[#262262] hover:border-[#00ACD8] transition-[color,background-color,border-color] duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#00ACD8] focus-visible:ring-offset-2 focus-visible:ring-offset-white"
              :class="activeCategory === '<?php echo $slug; ?>' ? 'bg-[#262262] text-white' : 'bg-white text-[#262262] hover:bg-[#00ACD8]'"
              :aria-checked="activeCategory === '<?php echo $slug; ?>' ? 'true' : 'false'"
              @click="setCategory('<?php echo $slug; ?>')"
              @keydown.enter.prevent="setCategory('<?php echo $slug; ?>')"
              @keydown.space.prevent="setCategory('<?php echo $slug; ?>')"
            >
              <?php echo $name; ?>
            </button>
          <?php endforeach; ?>
        </div>

        <div>
          <button
            type="button"
            class="px-4 py-3 min-h-[44px] rounded-[100px] whitespace-nowrap font-red-hat-text text-[14px] font-medium leading-5 border border-solid border-[#262262] bg-white text-[#262262] hover:border-[#00ACD8] hover:bg-[#00ACD8] transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#00ACD8] focus-visible:ring-offset-2 focus-visible:ring-offset-white"
            @click="clearAll()"
            x-show="activeCategory !== 'all'"
            aria-label="<?php esc_attr_e('Reset filter to show all categories', 'matrix-starter'); ?>"
          >
            <?php esc_html_e('Clear filters', 'matrix-starter'); ?>
          </button>
        </div>

      </div>
    </div>

    <!-- Cards grid -->
    <section class="w-full bg-[#F9FAFB] py-8 lg:py-16 min-h-fit" aria-label="<?php esc_attr_e('Blog posts listing', 'matrix-starter'); ?>">
      <div class="grid gap-x-16 gap-y-8 lg:gap-y-12 xl:gap-y-20 px-8 max-sm:grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 w-full max-w-[1084px] mx-auto bg-[#F9FAFB]">
        <?php
        $args = [
          'post_type'      => 'post',
          'posts_per_page' => 9,
          'paged'          => get_query_var('paged') ?: 1,
        ];

        // Keep server-side scoping if you're on a category/tag/archive landing
        if (is_category()) {
            $args['category_name'] = get_query_var('category_name');
        } elseif (is_tag()) {
            $args['tag'] = get_query_var('tag');
        } elseif (is_archive()) {
            if (is_year())  $args['year']     = get_query_var('year');
            if (is_month()) $args['monthnum'] = get_query_var('monthnum');
            if (is_day())   $args['day']      = get_query_var('day');
        }

        $query = new WP_Query($args);

        if ($query->have_posts()) :
          while ($query->have_posts()) : $query->the_post();
            $post_categories  = get_the_category();
            $post_classes     = array_map(fn($cat) => $cat->slug, $post_categories);
            $post_classes_str = implode(' ', $post_classes);

            // Simple badge label: "Event" if any category matches, else "News"
            $badge_label = 'News';
            if (!empty($post_categories)) {
              foreach ($post_categories as $cat) {
                if (stripos($cat->slug, 'event') !== false || stripos($cat->name, 'event') !== false) {
                  $badge_label = 'Event';
                  break;
                }
              }
            }

            $title_attr = get_the_title();
        ?>
          <a
            href="<?php the_permalink(); ?>"
            class="block w-full group"
            data-categories="<?php echo esc_attr($post_classes_str); ?>"
            x-show="visible($el)"
            x-transition.opacity.duration.300ms
            aria-label="Read more about <?php echo esc_attr($title_attr); ?>"
          >
            <div class="flex flex-col gap-4 w-full text-left">
              <!-- Image + gradient + badge -->
              <div class="overflow-hidden relative w-full h-48 rounded-lg bg-[#262262]">
                <?php if (has_post_thumbnail()) : ?>
                  <img
                    src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>"
                    alt="<?php echo esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true) ?: $title_attr); ?>"
                    class="object-cover w-full h-full transition-transform duration-300 ease-in-out transform group-hover:scale-105"
                  />
                <?php endif; ?>

                <div
                  class="pointer-events-none absolute inset-0 z-[1]"
                  style="background: linear-gradient(90deg, rgba(43, 57, 144, 0.30) 0%, rgba(0, 110, 200, 0.30) 100%);"
                  aria-hidden="true"
                ></div>

                <div
                  class="pointer-events-none absolute left-4 top-4 z-10 flex h-7 min-h-7 items-center justify-center rounded-full border border-solid border-[#2B3990] bg-white px-3 font-secondary text-sm font-medium leading-5 text-[#262262] transition-[background-color,border-color,color] duration-300 ease-out group-hover:border-[#00ACD8] group-hover:bg-[#00ACD8] group-hover:text-[#262262]"
                  aria-hidden="true"
                >
                  <span><?php echo esc_html($badge_label); ?></span>
                </div>
              </div>

              <!-- Content -->
              <div class="flex flex-col gap-1">
                <h3 class="text-[#262262] font-secondary text-[18px] font-bold leading-6"><?php the_title(); ?></h3>
                <p class="text-[#344054] text-base font-normal leading-5 "><?php the_excerpt(); ?></p>
              </div>
            </div>
          </a>
        <?php endwhile; wp_reset_postdata(); else : ?>
          <p>No posts found.</p>
        <?php endif; ?>
      </div>

      <!-- Pagination (hidden when a category filter is active) -->
      <nav class="flex justify-center items-center py-12 w-full pagination"
           aria-label="Pagination"
           x-show="showPagination">
        <?php my_custom_pagination(); ?>
      </nav>
    </section>
  </div>
</main>

<script>
  // Alpine component for client-side filtering & search
  function blogFilter({ initialCategory = 'all' } = {}) {
    return {
      activeCategory: initialCategory,
      showPagination: true,

      setCategory(cat) {
        this.activeCategory = cat;
        this.updatePagination();
      },

      clearAll() {
        this.activeCategory = 'all';
        this.updatePagination();
      },

      visible(el) {
        const catsRaw = (el.getAttribute('data-categories') || '').trim();
        const cats    = catsRaw ? catsRaw.split(/\s+/) : [];
        return this.activeCategory === 'all' || cats.includes(this.activeCategory);
      },

      updatePagination() {
        this.showPagination = this.activeCategory === 'all';
      }
    }
  }
</script>

<?php
get_footer();
