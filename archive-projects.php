<?php
get_header();

// Get the current queried object (category, tag, date, etc.)
$active_slug = 'all';
?>
<main class="w-full overflow-hidden min-h-fit site-main">
  
  <div class="w-full"
       x-data="{
         activeCategory: '<?php echo esc_js($active_slug); ?>',
         searchTerm: '',
         setCategory(category) {
           this.activeCategory = category;
         },
         clearSearch() {
           this.searchTerm = '';
         }
       }">

    <!-- Filter row -->
    <div class="flex flex-col justify-center items-start mx-auto py-6 w-full max-w-[1075px] text-sm leading-none max-xl:px-5">
      <div class="flex flex-wrap items-center gap-6">
        <div class="self-stretch my-auto text-slate-800" id="filterLabel">Filter by</div>

        <!-- Switch to radiogroup/radio for better a11y (no page reloads) -->
        <div class="flex flex-wrap gap-4 items-center self-stretch my-auto font-semibold text-teal-950"
             role="radiogroup" aria-labelledby="filterLabel">

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
          $terms = get_terms([
            'taxonomy'   => 'project_category',
            'hide_empty' => true,
          ]);
          if (!is_wp_error($terms)) :
            foreach ($terms as $term) :
              $term_slug = esc_attr($term->slug);
              $term_name = esc_html($term->name);
          ?>
              <button
                type="button"
                role="radio"
                class="gap-2 self-stretch px-6 py-3 my-auto whitespace-nowrap bg-white border border-slate-400 min-h-[42px] rounded-[100px] max-md:px-5"
                :class="activeCategory === '<?php echo $term_slug; ?>' ? 'bg-[#025A70] text-white' : 'bg-white border text-[#025A70]'"
                :aria-checked="activeCategory === '<?php echo $term_slug; ?>'"
                @click="setCategory('<?php echo $term_slug; ?>')"
                @keydown.enter.prevent="setCategory('<?php echo $term_slug; ?>')"
                @keydown.space.prevent="setCategory('<?php echo $term_slug; ?>')"
              >
                <?php echo $term_name; ?>
              </button>
            <?php endforeach;
          endif; ?>
        </div>

        <!-- Live search (optional, like blog) -->
        <div class="flex items-center gap-2">
          <label for="project-search" class="sr-only">Search projects</label>
          <input
            id="project-search"
            type="search"
            x-model.trim.debounce.200ms="searchTerm"
            placeholder="Search projects"
            class="px-4 py-3 bg-white border border-slate-400 rounded min-h-[42px] text-slate-700"
            aria-label="Search projects"
          />
          <button
            type="button"
            class="px-4 py-3 bg-slate-200 border border-slate-400 rounded min-h-[42px]"
            @click="clearSearch()"
            x-show="searchTerm.length"
            aria-label="Clear search"
          >Clear</button>
        </div>
      </div>
    </div>

    <!-- Results grid -->
    <section class="w-full bg-[#E6EEF1] py-8 lg:py-16 min-h-fit"
             :id="'panel-' + activeCategory"
             role="tabpanel"
             :aria-labelledby="'tab-' + activeCategory">
      <div class="grid gap-x-16 gap-y-8 lg:gap-y-12 xl:gap-y-20 px-8 max-sm:grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 w-full max-w-[1160px] mx-auto bg-[#E6EEF1]">
        <?php
        $args = [
          'post_type'      => 'projects',
          'posts_per_page' => 9,
          'paged'          => get_query_var('paged') ?: 1,
        ];
        $query = new WP_Query($args);

        if ($query->have_posts()) :
          while ($query->have_posts()) : $query->the_post();
            $project_terms = get_the_terms(get_the_ID(), 'project_category');
            $slugs         = $project_terms && !is_wp_error($project_terms) ? wp_list_pluck($project_terms, 'slug') : [];
            $classes_str   = implode(' ', array_map('esc_attr', $slugs));

            $thumb_id = get_post_thumbnail_id();
            $alt      = $thumb_id ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '';
            $alt      = $alt ? $alt : get_the_title();
            $img_url  = get_the_post_thumbnail_url(get_the_ID(), 'large');
            $title    = get_the_title();
        ?>
          <a href="<?php the_permalink(); ?>"
             class="flex flex-col w-full overflow-hidden text-base font-bold text-white group"
             data-title="<?php echo esc_attr(mb_strtolower($title)); ?>"
             :aria-hidden="!( (activeCategory === 'all' || '<?php echo esc_attr($classes_str); ?>'.split(' ').includes(activeCategory)) && (!searchTerm || $el.getAttribute('data-title').includes(searchTerm.toLowerCase())) )"
             aria-label="View project: <?php echo esc_attr($title); ?>"
             x-show="(activeCategory === 'all' || '<?php echo esc_attr($classes_str); ?>'.split(' ').includes(activeCategory)) && (!searchTerm || $el.getAttribute('data-title').includes(searchTerm.toLowerCase()))"
             x-transition.opacity.duration.300ms>
            <div class="relative w-full h-[196px] overflow-hidden rounded-none">
              <?php if ($img_url) : ?>
                <img src="<?php echo esc_url($img_url); ?>"
                     alt="<?php echo esc_attr($alt); ?>"
                     class="object-cover w-full h-full transition-transform duration-300 ease-in-out transform group-hover:scale-110" />
                <div class="absolute inset-0 bg-[#01242D] opacity-50" aria-hidden="true"></div>
              <?php endif; ?>
            </div>

            <div class="flex flex-col w-full mt-4">
              <h4 class="text-2xl font-bold leading-8 text-[#01242D]"><?php echo esc_html($title); ?></h4>
              <div class="text-[#1D2939] text-base font-normal leading-6"><?php the_excerpt(); ?></div>
            </div>
          </a>
        <?php endwhile; wp_reset_postdata();
        else : ?>
          <p>No projects found.</p>
        <?php endif; ?>
      </div>

      <!-- Hide pagination when filtering or searching -->
      <nav class="flex items-center justify-center w-full py-12 pagination"
           x-show="activeCategory === 'all' && !searchTerm"
           aria-label="Pagination">
        <?php if (function_exists('my_custom_pagination')) { my_custom_pagination(); } ?>
      </nav>
    </section>
  </div>
</main>

<?php
get_footer();
