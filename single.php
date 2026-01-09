<?php
get_header();
?>
<main class="overflow-hidden min-h-screen site-main max-md:px-5 w-full  max-w-container mx-auto">
  <?php
  // Optional hero partial (yours)
  get_template_part('template-parts/single/hero');

  // Global hero flexi (if present in your theme)
  if (function_exists('load_hero_templates')) {
    load_hero_templates();
  }

  // Breadcrumbs (Theme Options toggle)
  $enable_breadcrumbs = get_field('enable_breadcrumbs', 'option');
  if ($enable_breadcrumbs !== false) {
    get_template_part('template-parts/header/breadcrumbs');
  }
  ?>

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php
      // Post basics
      $post_id     = get_the_ID();
      $title       = get_the_title();
      $datetime    = get_the_date('c');
      $date_human  = get_the_date(); // Use your preferred format e.g. get_the_date('j F Y')

      // Primary category (first term)
      $cats        = get_the_category($post_id);
      $cat_label   = (!empty($cats) && !is_wp_error($cats)) ? $cats[0]->name : '';

      // Excerpt (optional intro)
      $intro       = get_the_excerpt($post_id);
    ?>

    <article
      <?php post_class('flex overflow-hidden justify-between items-center"'); ?>
      role="article"
      aria-labelledby="article-heading"
    >
      <div class="flex flex-col shrink justify-center mt-[2.5rem] w-full  max-md:max-w-full">
        <div class="flex flex-col w-full text-text-primary max-md:max-w-full">
          <?php if ($cat_label) : ?>
            <div
              class="flex gap-2 items-center self-start px-3 py-1 text-sm font-medium leading-none whitespace-nowrap bg-emerald-100 min-h-7 rounded-[100px]"
              aria-label="<?php echo esc_attr($cat_label); ?> category"
            >
              <span class= my-auto text-text-primary"><?php echo esc_html($cat_label); ?></span>
            </div>
          <?php endif; ?>

          <div class="mt-2 w-full text-4xl font-bold tracking-tighter leading-none max-md:max-w-full">
            <h1 id="article-heading" class="text-text-primary max-md:max-w-full">
              <?php echo esc_html($title); ?>
            </h1>
            <div class="flex mt-1 w-8 bg-cyan-500 min-h-1" aria-hidden="true"></div>
          </div>

          <time
            class="mt-2 text-base leading-none text-slate-800"
            datetime="<?php echo esc_attr($datetime); ?>"
            aria-label="<?php echo esc_attr('Published on ' . $date_human); ?>"
          >
            <?php echo esc_html($date_human); ?>
          </time>
        </div>

        <?php if (!empty($intro)) : ?>
          <p class="mt-6 text-lg leading-6 text-slate-800 max-md:max-w-full" aria-describedby="article-heading">
            <?php echo esc_html($intro); ?>
          </p>
        <?php endif; ?>
      </div>
    </article>

    <?php
      // Main content
      if (trim(get_the_content()) !== '') :
    ?>
      <div class="max-w-container mx-auto max-xl:px-5">
        <?php get_template_part('template-parts/content/content', 'page'); ?>
      </div>
    <?php endif; ?>

  <?php endwhile; else : ?>
    <div class="max-w-container mx-auto px-5 py-12">
      <p>No content found</p>
    </div>
  <?php endif; ?>

  <?php
  // Flexi blocks that come after content
  if (function_exists('load_flexible_content_templates')) {
    load_flexible_content_templates();
  }

  // Only show author + related posts on the blog post type
  if (get_post_type() === 'post') :
    get_template_part('template-parts/single/author');
    get_template_part('template-parts/single/related-posts');
  endif;
  ?>
</main>
<?php
get_footer();
