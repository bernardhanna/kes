<?php
get_header();

$projects_opts = get_field('projects_settings', 'option') ?: [];
$archive_blocks_page_id = function_exists('get_field') ? (int) get_field('projects_archive_blocks_page', 'option') : 0;

if ($archive_blocks_page_id > 0 && function_exists('load_hero_templates')) {
    load_hero_templates($archive_blocks_page_id);
}

if (!empty($projects_opts['show_projects_map'])) {
    get_template_part('template-parts/archive/projects-map', null, [
        'projects_opts' => $projects_opts,
    ]);
}

if (function_exists('matrix_starter_render_archive_index_header')) {
    matrix_starter_render_archive_index_header([
        'heading'      => $projects_opts['hero_heading_text'] ?? __('Projects', 'matrix-starter'),
        'heading_tag'  => $projects_opts['hero_heading_tag'] ?? 'h2',
        'intro'        => $projects_opts['hero_intro_content'] ?? '',
        'bg_color'     => $projects_opts['hero_background_color'] ?? '#FFFFFF',
        'accent_color' => $projects_opts['divider_color'] ?? '#00ACD8',
        'bg_image_url' => ! empty($projects_opts['hero_background_image']['url']) ? $projects_opts['hero_background_image']['url'] : '',
    ]);
}

if ($archive_blocks_page_id > 0 && function_exists('load_flexible_content_templates')) {
    load_flexible_content_templates($archive_blocks_page_id);
}

$active_slug = 'all';
?>
<main class="overflow-hidden w-full min-h-fit site-main">
  
  <div class="w-full"
       x-data="{
         activeCategory: '<?php echo esc_js($active_slug); ?>',
         filterDragging: false,
         filterStartX: 0,
         filterStartScrollLeft: 0,
         filterHasOverflow: false,
         filterCanScrollRight: false,
         setCategory(category) { this.activeCategory = category; },
         init() {
           this.$nextTick(() => {
             this.updateFilterOverflow();
             window.addEventListener('resize', () => this.updateFilterOverflow());
           });
         },
         updateFilterOverflow() {
           const el = this.$refs.filterScroller;
           if (!el) return;
           const maxScrollLeft = Math.max(0, el.scrollWidth - el.clientWidth);
           this.filterHasOverflow = maxScrollLeft > 0;
           this.filterCanScrollRight = el.scrollLeft < (maxScrollLeft - 2);
         },
         startFilterDrag(event) {
           const el = this.$refs.filterScroller;
           if (!el) return;
           this.filterDragging = true;
           this.filterStartX = event.pageX;
           this.filterStartScrollLeft = el.scrollLeft;
         },
         onFilterDrag(event) {
           const el = this.$refs.filterScroller;
           if (!el || !this.filterDragging) return;
           const walk = event.pageX - this.filterStartX;
           el.scrollLeft = this.filterStartScrollLeft - walk;
           this.updateFilterOverflow();
         },
         stopFilterDrag() {
           this.filterDragging = false;
         }
       }">

    <div class="flex flex-col justify-center items-start mx-auto py-6 w-full max-w-[1085px] px-8 text-sm leading-none max-xl:px-5">
      <div class="flex flex-wrap gap-6 items-center w-full">
        <div class="self-stretch my-auto font-red-hat-text text-[14px] font-medium leading-5 text-[#262262]" id="filterLabel"><?php esc_html_e('Filter by', 'matrix-starter'); ?></div>

        <div class="relative self-stretch my-auto w-full sm:w-auto">
          <div class="flex gap-4 items-center self-stretch my-auto overflow-x-auto whitespace-nowrap no-scrollbar select-none"
               x-ref="filterScroller"
               role="radiogroup"
               aria-labelledby="filterLabel"
               :class="filterDragging ? 'cursor-grabbing' : 'cursor-grab'"
               @scroll="updateFilterOverflow"
               @mousedown.prevent="startFilterDrag($event)"
               @mousemove="onFilterDrag($event)"
               @mouseup="stopFilterDrag"
               @mouseleave="stopFilterDrag"
               @mouseup.window="stopFilterDrag">

          <button
            type="button"
            role="radio"
            class="inline-flex shrink-0 gap-2 items-center justify-center self-stretch px-6 py-3 my-auto min-h-[44px] min-w-[44px] rounded-[100px] max-md:px-5 whitespace-nowrap font-red-hat-text text-[14px] font-medium leading-5 border border-solid border-[#262262] hover:border-[#00ACD8] transition-[color,background-color,border-color] duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#00ACD8] focus-visible:ring-offset-2 focus-visible:ring-offset-white"
            :class="activeCategory === 'all' ? 'bg-[#262262] text-white' : 'bg-white text-[#262262] hover:bg-[#00ACD8]'"
            :aria-checked="activeCategory === 'all' ? 'true' : 'false'"
            @click="setCategory('all')"
            @keydown.enter.prevent="setCategory('all')"
            @keydown.space.prevent="setCategory('all')"
          >
            <?php esc_html_e('All', 'matrix-starter'); ?>
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
                class="inline-flex shrink-0 gap-2 items-center justify-center self-stretch px-6 py-3 my-auto min-h-[44px] min-w-[44px] rounded-[100px] max-md:px-5 whitespace-nowrap font-red-hat-text text-[14px] font-medium leading-5 border border-solid border-[#262262] hover:border-[#00ACD8] transition-[color,background-color,border-color] duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#00ACD8] focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                :class="activeCategory === '<?php echo $term_slug; ?>' ? 'bg-[#262262] text-white' : 'bg-white text-[#262262] hover:bg-[#00ACD8]'"
                :aria-checked="activeCategory === '<?php echo $term_slug; ?>' ? 'true' : 'false'"
                @click="setCategory('<?php echo $term_slug; ?>')"
                @keydown.enter.prevent="setCategory('<?php echo $term_slug; ?>')"
                @keydown.space.prevent="setCategory('<?php echo $term_slug; ?>')"
              >
                <?php echo $term_name; ?>
              </button>
            <?php endforeach;
          endif; ?>
          </div>
          <div
            x-show="filterHasOverflow && filterCanScrollRight"
            x-transition.opacity
            class="pointer-events-none absolute right-0 top-0 h-full w-16 bg-gradient-to-l from-white to-transparent"
            aria-hidden="true"></div>
        </div>
      </div>
    </div>

    <!-- Results grid -->
    <section class="w-full bg-[#F9FAFB] py-8 lg:py-16 min-h-fit"
             aria-label="<?php esc_attr_e('Projects listing', 'matrix-starter'); ?>">
      <div class="grid gap-x-16 gap-y-8 lg:gap-y-12 xl:gap-y-20 px-8 max-sm:grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 w-full max-w-[1084px] mx-auto bg-[#F9FAFB]">
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

            $primary_cat = '';
            if (! empty($project_terms) && ! is_wp_error($project_terms)) {
              $terms_sorted = $project_terms;
              usort($terms_sorted, static function ($a, $b) {
                return (int) $a->term_id <=> (int) $b->term_id;
              });
              $primary_cat = $terms_sorted[0]->name;
            }

            $location_display = get_field('project_location', get_the_ID());
            $location_display = is_string($location_display) ? trim($location_display) : '';

            $thumb_id = get_post_thumbnail_id();
            $alt      = $thumb_id ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '';
            $alt      = $alt ? $alt : get_the_title();
            $img_url  = get_the_post_thumbnail_url(get_the_ID(), 'large');
            $title    = get_the_title();
        ?>
          <a href="<?php the_permalink(); ?>"
             class="block w-full group"
             aria-label="<?php echo esc_attr(sprintf(__('View project: %s', 'matrix-starter'), $title)); ?>"
             x-show="activeCategory === 'all' || '<?php echo esc_attr($classes_str); ?>'.split(' ').filter(Boolean).includes(activeCategory)"
             x-transition.opacity.duration.300ms>
            <div class="flex flex-col gap-4 w-full text-left">
              <div class="overflow-hidden relative w-full h-48 bg-gradient-to-r rounded-lg from-slate-600 to-slate-700">
                <?php if ($img_url) : ?>
                  <img
                    src="<?php echo esc_url($img_url); ?>"
                    alt="<?php echo esc_attr($alt); ?>"
                    class="object-cover w-full h-full transition-transform duration-300 ease-in-out transform group-hover:scale-105"
                  />
                <?php endif; ?>
                <div
                  class="pointer-events-none absolute inset-0 z-[1]"
                  style="background: linear-gradient(90deg, rgba(43, 57, 144, 0.30) 0%, rgba(0, 110, 200, 0.30) 100%);"
                  aria-hidden="true"
                ></div>
                <?php if ($primary_cat !== '') : ?>
                  <div
                    class="pointer-events-none absolute left-4 top-4 z-10 flex h-7 min-h-7 max-w-[calc(100%-2rem)] items-center justify-center rounded-full border border-solid border-[#2B3990] bg-white px-3 font-secondary text-sm font-medium leading-5 text-[#262262] transition-[background-color,border-color,color] duration-300 ease-out group-hover:border-[#00ACD8] group-hover:bg-[#00ACD8] group-hover:text-[#262262]"
                    aria-hidden="true"
                  >
                    <span class="truncate"><?php echo esc_html($primary_cat); ?></span>
                  </div>
                <?php endif; ?>
              </div>
              <div class="flex flex-col gap-1">
                <h3 class="text-[#262262] font-secondary text-[18px] font-bold leading-6"><?php echo esc_html($title); ?></h3>
                <?php if ($location_display !== '') : ?>
                  <p class="text-[#344054] font-secondary text-base font-normal leading-5"><?php echo esc_html($location_display); ?></p>
                <?php endif; ?>
              </div>
            </div>
          </a>
        <?php endwhile; wp_reset_postdata();
        else : ?>
          <p>No projects found.</p>
        <?php endif; ?>
      </div>

      <!-- Hide pagination when filtering or searching -->
      <nav class="flex justify-center items-center py-12 w-full pagination"
           x-show="activeCategory === 'all'"
           aria-label="<?php esc_attr_e('Pagination', 'matrix-starter'); ?>">
        <?php if (function_exists('my_custom_pagination')) { my_custom_pagination(); } ?>
      </nav>
    </section>
  </div>
</main>

<?php
get_footer();
