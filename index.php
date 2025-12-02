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

<main class="w-full overflow-hidden min-h-fit site-main">
  <div class="w-full"
       x-data="blogFilter({
         initialCategory: '<?php echo esc_js($category_slug ?: 'all'); ?>',
       })">

    <!-- Filter & Search -->
    <div class="flex flex-col justify-center items-start mx-auto py-6 w-full max-w-[1075px] text-sm leading-none max-xl:px-5">
      <div class="flex flex-wrap items-center gap-6 max-md:max-w-full">

        <div class="self-stretch my-auto text-slate-800" id="filterLabel">Filter by</div>

        <!-- Category radio group (no page reloads) -->
        <div class="flex flex-wrap gap-4 items-center self-stretch my-auto font-semibold text-teal-950 max-md:max-w-full"
             role="radiogroup"
             aria-labelledby="filterLabel">

          <!-- All -->
          <button
            type="button"
            role="radio"
            class="gap-2 self-stretch px-6 py-3 my-auto whitespace-nowrap bg-white border border-slate-400 min-h-[42px] rounded-[100px] max-md:px-5"
            :class="activeCategory === 'all' ? 'bg-[#025A70] text-white' : 'bg-white border text-[#025A70]'"
            :aria-checked="activeCategory === 'all'"
            @click="setCategory('all')"
            @keydown.enter.prevent="setCategory('all')"
            @keydown.space.prevent="setCategory('all')"
          >
            All
          </button>

          <?php
          $categories = get_categories(['exclude' => get_cat_ID('All')]);
          foreach ($categories as $category) :
            $slug = esc_attr($category->slug);
            $name = esc_html($category->name);
          ?>
            <button
              type="button"
              role="radio"
              class="gap-2 self-stretch px-6 py-3 my-auto whitespace-nowrap bg-white border border-slate-400 min-h-[42px] rounded-[100px] max-md:px-5"
              :class="activeCategory === '<?php echo $slug; ?>' ? 'bg-[#025A70] text-white' : 'bg-white border text-[#025A70]'"
              :aria-checked="activeCategory === '<?php echo $slug; ?>'"
              @click="setCategory('<?php echo $slug; ?>')"
              @keydown.enter.prevent="setCategory('<?php echo $slug; ?>')"
              @keydown.space.prevent="setCategory('<?php echo $slug; ?>')"
            >
              <?php echo $name; ?>
            </button>
          <?php endforeach; ?>
        </div>

        <!-- Live search -->
        <div class="flex items-center gap-2">
          <label for="article-search" class="sr-only">Search articles</label>
          <input
            id="article-search"
            type="search"
            x-model.trim.debounce.200ms="searchTerm"
            placeholder="Search articles"
            class="px-4 py-3 bg-white border border-slate-400 rounded min-h-[42px] text-slate-700"
            aria-label="Search articles"
          />
          <button
            type="button"
            class="px-4 py-3 bg-slate-200 border border-slate-400 rounded min-h-[42px]"
            @click="clearSearch()"
            x-show="searchTerm.length"
            aria-label="Clear search"
          >Clear</button>
        </div>

        <!-- Clear all (category + search) -->
        <div>
          <button
            type="button"
            class="px-4 py-3 bg-gray-200 rounded min-h-[42px] whitespace-nowrap hover:opacity-80"
            @click="clearAll()"
            x-show="activeCategory !== 'all' || searchTerm.length"
            aria-label="Clear filters"
          >
            Clear filters
          </button>
        </div>

      </div>
    </div>

    <!-- Cards grid -->
    <section class="w-full bg-[#F9FAFB] py-8 lg:py-16 min-h-fit" role="tabpanel">
      <div class="grid gap-x-16 gap-y-8 lg:gap-y-12 xl:gap-y-20 px-8 max-sm:grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 w-full max-w-[1160px] mx-auto bg-[#F9FAFB]">
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
            class="group block w-full"
            data-categories="<?php echo esc_attr($post_classes_str); ?>"
            data-title="<?php echo esc_attr(mb_strtolower($title_attr)); ?>"
            x-show="visible($el)"
            x-transition.opacity.duration.300ms
            aria-label="Read more about <?php echo esc_attr($title_attr); ?>"
          >
            <div class="flex flex-col gap-4 w-full text-left">
              <!-- Image + gradient + badge -->
              <div class="relative w-full h-48 rounded-lg overflow-hidden bg-gradient-to-r from-slate-600 to-slate-700">
                <?php if (has_post_thumbnail()) : ?>
                  <img
                    src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>"
                    alt="<?php echo esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true) ?: $title_attr); ?>"
                    class="w-full h-full object-cover transition-transform duration-300 ease-in-out transform group-hover:scale-105"
                  />
                <?php endif; ?>

                <div class="absolute inset-0 bg-gradient-to-r from-[#01242D]/40 to-[#025A70]/40" aria-hidden="true"></div>

                <div class="absolute top-4 left-4 h-7 px-3 flex justify-center items-center rounded-full bg-white/90">
                  <span class="text-xs font-medium leading-5 tracking-wide text-[#01242D]">
                    <?php echo esc_html($badge_label); ?>
                  </span>
                </div>
              </div>

              <!-- Content -->
              <div class="flex flex-col gap-1">
                <h3 class="text-[#01242D] text-lg font-bold leading-6"><?php the_title(); ?></h3>
                <p class="text-[#1D2939] text-base font-normal leading-5 line-clamp-3"><?php the_excerpt(); ?></p>
              </div>
            </div>
          </a>
        <?php endwhile; wp_reset_postdata(); else : ?>
          <p>No posts found.</p>
        <?php endif; ?>
      </div>

      <!-- Pagination (auto hides when filtering/searching) -->
      <nav class="flex items-center justify-center w-full py-12 pagination"
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
      searchTerm: '',
      showPagination: true,

      setCategory(cat) {
        this.activeCategory = cat;
        this.updatePagination();
      },

      clearSearch() {
        this.searchTerm = '';
        this.updatePagination();
      },

      clearAll() {
        this.activeCategory = 'all';
        this.searchTerm = '';
        this.updatePagination();
      },

      // Determine if a card should be visible
      visible(el) {
        const catsRaw = (el.getAttribute('data-categories') || '').trim();
        const cats    = catsRaw ? catsRaw.split(/\s+/) : [];
        const title   = (el.getAttribute('data-title') || '').toLowerCase();

        const catOK    = this.activeCategory === 'all' || cats.includes(this.activeCategory);
        const searchOK = !this.searchTerm || title.indexOf(this.searchTerm.toLowerCase()) !== -1;

        return catOK && searchOK;
      },

      updatePagination() {
        // Hide pagination if any client-side filter is active
        this.showPagination = (this.activeCategory === 'all' && this.searchTerm.length === 0);
      }
    }
  }
</script>

<?php
get_footer();
