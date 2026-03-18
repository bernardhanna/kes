<?php
/** Archive template for Services */
get_header();

// THEME OPTIONS
$settings = get_field('services_settings', 'option') ?: [];

$hero_bg = ! empty($settings['hero_background_image']['url']) ? $settings['hero_background_image'] : null;
$bg_style = $hero_bg ? "background-image:url('" . esc_url($hero_bg['url']) . "');background-size:cover;background-position:center;" : '';

$background_color = $settings['hero_background_color'] ?? '#FFFFFF';
$divider_color    = $settings['divider_color'] ?? '#7C3AED';

$heading_tag = $settings['hero_heading_tag']  ?? 'h1';
$heading     = $settings['hero_heading_text'] ?? 'Services';
$content     = $settings['hero_intro_content'] ?? '';

$filter_title = $settings['filter_section_title'] ?? 'Filter by';

// hero paddings
$padding_classes = [];
if (!empty($settings['padding_settings']) && is_array($settings['padding_settings'])) {
  foreach ($settings['padding_settings'] as $row) {
    $ss = $row['screen_size'] ?? null;
    $pt = $row['padding_top'] ?? '';
    $pb = $row['padding_bottom'] ?? '';
    if ($ss !== null && $pt !== '' && $pb !== '') {
      $padding_classes[] = esc_attr("{$ss}:pt-[{$pt}rem]");
      $padding_classes[] = esc_attr("{$ss}:pb-[{$pb}rem]");
    }
  }
}

$active_slug = 'all';
?>
<main class="w-full overflow-hidden min-h-fit site-main">

  <?php
  if (function_exists('matrix_starter_render_archive_index_header')) {
      matrix_starter_render_archive_index_header([
          'heading'        => $heading,
          'heading_tag'    => $heading_tag,
          'intro'          => $content,
          'bg_color'       => $background_color,
          'accent_color'   => $divider_color,
          'bg_image_url'   => $hero_bg ? $hero_bg['url'] : '',
          'section_class'  => implode(' ', $padding_classes),
      ]);
  }
  ?>

  <!-- FILTER + GRID -->
  <div class="w-full"
       x-data="{
         activeCategory: '<?php echo esc_js($active_slug); ?>',
         setCategory(category) {
           window.location.href = category === 'all'
             ? '<?php echo esc_url(home_url('/services/')); ?>'
             : '<?php echo esc_url(home_url('/service-category/')); ?>' + category;
         }
       }">

    <!-- Filter row -->
    <div class="flex flex-col justify-center items-start mx-auto py-6 w-full max-w-[1085px]  px-8text-sm leading-none max-xl:px-5">
      <div class="flex flex-wrap items-center gap-6">
        <div class="self-stretch my-auto font-red-hat-text text-[14px] font-medium leading-5 text-[#262262]" id="filterLabel"><?php echo esc_html($filter_title); ?></div>

        <div class="flex flex-wrap gap-4 items-center self-stretch my-auto"
             role="tablist"
             aria-labelledby="filterLabel">
          <button
            id="tab-all"
            type="button"
            role="tab"
            :aria-selected="activeCategory === 'all' ? 'true' : 'false'"
            :aria-controls="'panel-all'"
            class="inline-flex gap-2 items-center justify-center self-stretch px-6 py-3 my-auto min-h-[44px] min-w-[44px] rounded-[100px] max-md:px-5 whitespace-nowrap font-red-hat-text text-[14px] font-medium leading-5 border border-solid border-[#262262] hover:border-[#00ACD8] transition-[color,background-color,border-color] duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#00ACD8] focus-visible:ring-offset-2 focus-visible:ring-offset-white"
            :class="activeCategory === 'all' ? 'bg-[#262262] text-white' : 'bg-white text-[#262262] hover:bg-[#00ACD8]'"
            @click="setCategory('all')">
            <?php esc_html_e('All', 'matrix-starter'); ?>
          </button>

          <?php
          $terms = get_terms([
            'taxonomy'   => 'service_category',
            'hide_empty' => true,
          ]);
          if (!is_wp_error($terms)) :
            foreach ($terms as $term) : ?>
              <button
                id="tab-<?php echo esc_attr($term->slug); ?>"
                type="button"
                role="tab"
                :aria-selected="activeCategory === '<?php echo esc_attr($term->slug); ?>' ? 'true' : 'false'"
                :aria-controls="'panel-<?php echo esc_attr($term->slug); ?>'"
                class="inline-flex gap-2 items-center justify-center self-stretch px-6 py-3 my-auto min-h-[44px] min-w-[44px] rounded-[100px] max-md:px-5 whitespace-nowrap font-red-hat-text text-[14px] font-medium leading-5 border border-solid border-[#262262] hover:border-[#00ACD8] transition-[color,background-color,border-color] duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#00ACD8] focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                :class="activeCategory === '<?php echo esc_attr($term->slug); ?>' ? 'bg-[#262262] text-white' : 'bg-white text-[#262262] hover:bg-[#00ACD8]'"
                @click="setCategory('<?php echo esc_attr($term->slug); ?>')">
                <?php echo esc_html($term->name); ?>
              </button>
            <?php endforeach;
          endif; ?>
        </div>
      </div>
    </div>

    <!-- Grid -->
    <section class="w-full bg-[#F9FAFB] py-8 lg:py-16 min-h-fit"
             :id="'panel-' + activeCategory"
             role="tabpanel"
             :aria-labelledby="'tab-' + activeCategory">
      <div class="grid gap-x-16 gap-y-8 lg:gap-y-12 xl:gap-y-20 px-8 max-sm:grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 w-full max-w-[1084px] mx-auto bg-[#F9FAFB]">
        <?php
        $args = [
          'post_type'      => 'services',
          'posts_per_page' => 9,
          'paged'          => get_query_var('paged') ?: 1,
        ];
        $query = new WP_Query($args);

        if ($query->have_posts()) :
          while ($query->have_posts()) : $query->the_post();
            $service_terms = get_the_terms(get_the_ID(), 'service_category');
            $slugs         = $service_terms && !is_wp_error($service_terms) ? wp_list_pluck($service_terms, 'slug') : [];
            $classes_str   = implode(' ', array_map('esc_attr', $slugs));

            $thumb_id = get_post_thumbnail_id();
            $alt      = $thumb_id ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '';
            $alt      = $alt ? $alt : get_the_title();
            $img_url  = get_the_post_thumbnail_url(get_the_ID(), 'large');

            $primary_cat = '';
            if (! empty($service_terms) && ! is_wp_error($service_terms)) {
              $terms_sorted = $service_terms;
              usort($terms_sorted, static function ($a, $b) {
                return (int) $a->term_id <=> (int) $b->term_id;
              });
              $primary_cat = $terms_sorted[0]->name;
            }
            $svc_title = get_the_title();
        ?>
          <a href="<?php the_permalink(); ?>"
             class="block w-full group"
             :aria-hidden="activeCategory !== 'all' && !('<?php echo esc_attr($classes_str); ?>'.split(' ').includes(activeCategory))"
             aria-label="<?php echo esc_attr(sprintf(__('View service: %s', 'matrix-starter'), $svc_title)); ?>"
             x-show="activeCategory === 'all' || '<?php echo esc_attr($classes_str); ?>'.split(' ').includes(activeCategory)"
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
                <h3 class="text-[#262262] font-secondary text-[18px] font-bold leading-6"><?php the_title(); ?></h3>
                <div class="text-[#344054] text-base font-normal leading-5 line-clamp-3"><?php the_excerpt(); ?></div>
              </div>
            </div>
          </a>
        <?php endwhile; wp_reset_postdata();
        else : ?>
          <p>No services found.</p>
        <?php endif; ?>
      </div>

      <nav class="flex items-center justify-center w-full py-12 pagination" x-show="activeCategory === 'all'" aria-label="Pagination">
        <?php if (function_exists('my_custom_pagination')) { my_custom_pagination(); } ?>
      </nav>
    </section>
  </div>
</main>

<?php get_footer(); ?>
