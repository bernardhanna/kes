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

$section_id  = 'services-hero-' . wp_generate_uuid4();
$active_slug = 'all';
?>
<main class="w-full overflow-hidden min-h-fit site-main">

  <!-- HERO -->
  <section
    id="<?php echo esc_attr($section_id); ?>"
    class="relative flex overflow-hidden <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
    style="background-color: <?php echo esc_attr($background_color); ?>; <?php echo esc_attr($bg_style); ?>"
    role="region"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
  >
    <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5">
      <div class="flex overflow-hidden justify-between items-center self-stretch px-24 py-20 max-md:px-5">
        <div class="flex flex-col flex-1 shrink justify-center self-stretch my-auto w-full basis-0 min-w-60 max-md:max-w-full">
          <?php if (!empty($heading)): ?>
            <div class="w-full text-4xl font-bold tracking-tighter leading-none text-text-primary max-md:max-w-full">
              <<?php echo esc_attr($heading_tag); ?>
                id="<?php echo esc_attr($section_id); ?>-heading"
                class="text-text-primary max-md:max-w-full"
              >
                <?php echo esc_html($heading); ?>
              </<?php echo esc_attr($heading_tag); ?>>
              <div class="flex mt-1 w-8 min-h-1"
                   style="background-color: <?php echo esc_attr($divider_color); ?>;"
                   role="presentation" aria-hidden="true"></div>
            </div>
          <?php endif; ?>

          <?php if (!empty($content)): ?>
            <div class="mt-6 text-lg leading-none text-slate-700 max-md:max-w-full wp_editor">
              <?php echo wp_kses_post($content); ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

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
    <div class="flex flex-col justify-center items-start mx-auto py-6 w-full max-w-[1075px] text-sm leading-none max-xl:px-5">
      <div class="flex flex-wrap items-center gap-6">
        <div class="self-stretch my-auto text-slate-800" id="filterLabel"><?php echo esc_html($filter_title); ?></div>

        <div class="flex flex-wrap gap-4 items-center self-stretch my-auto font-semibold text-teal-950"
             role="tablist" aria-labelledby="filterLabel">
          <button
            id="tab-all"
            role="tab"
            :aria-selected="activeCategory === 'all'"
            :aria-controls="'panel-all'"
            class="gap-2 self-stretch px-6 py-3 my-auto whitespace-nowrap bg-white border border-slate-400 min-h-[42px] rounded-[100px] max-md:px-5"
            :class="{ 'bg-[#025A70] text-white': activeCategory === 'all', 'bg-white border text-[#025A70]': activeCategory !== 'all' }"
            @click="setCategory('all')">
            All
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
                role="tab"
                :aria-selected="activeCategory === '<?php echo esc_attr($term->slug); ?>'"
                :aria-controls="'panel-<?php echo esc_attr($term->slug); ?>'"
                class="gap-2 self-stretch px-6 py-3 my-auto whitespace-nowrap bg-white border border-slate-400 min-h-[42px] rounded-[100px] max-md:px-5"
                :class="{ 'bg-[#025A70] text-white': activeCategory === '<?php echo esc_attr($term->slug); ?>', 'bg-white border text-[#025A70]': activeCategory !== '<?php echo esc_attr($term->slug); ?>' }"
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
      <div class="grid gap-x-16 gap-y-8 lg:gap-y-12 xl:gap-y-20 px-8 max-sm:grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 w-full max-w-[1160px] mx-auto bg-[#F9FAFB]">
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
        ?>
          <a href="<?php the_permalink(); ?>"
             class="flex flex-col w-full overflow-hidden text-base font-bold text-white group"
             :aria-hidden="activeCategory !== 'all' && !('<?php echo esc_attr($classes_str); ?>'.split(' ').includes(activeCategory))"
             aria-label="View service: <?php echo esc_attr(get_the_title()); ?>"
             x-show="activeCategory === 'all' || '<?php echo esc_attr($classes_str); ?>'.split(' ').includes(activeCategory)"
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
              <h4 class="text-2xl font-bold leading-8 text-[#01242D]"><?php the_title(); ?></h4>
              <div class="text-[#1D2939] text-base font-normal leading-6"><?php the_excerpt(); ?></div>
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
