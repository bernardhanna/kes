<?php
/**
 * Jobs (Flexible Content block)
 * - Left: list of recent jobs (cards)
 * - Right: featured job (full post)
 * - Uses get_sub_field only, Tailwind layout, padding repeater
 */

$show_section   = (bool) get_sub_field('show_section');
if (!$show_section) return;

$sr_heading     = get_sub_field('sr_heading') ?: 'Available Job Positions';
$manual_featured= (bool) get_sub_field('manual_featured');
$featured_rel   = get_sub_field('featured_job');
$jobs_to_show   = (int) (get_sub_field('jobs_to_show') ?: 4);

$bg_color       = get_sub_field('background_color') ?: 'bg-white';
$border_color   = get_sub_field('border_color') ?: 'border-emerald-100';

// Padding classes
$padding_classes = [];
if (have_rows('padding_settings')) {
  while (have_rows('padding_settings')) {
    the_row();
    $screen_size   = get_sub_field('screen_size');
    $padding_top   = get_sub_field('padding_top');
    $padding_bottom= get_sub_field('padding_bottom');
    if ($screen_size !== '' && $padding_top !== '' && $padding_top !== null) {
      $padding_classes[] = "{$screen_size}:pt-[{$padding_top}rem]";
    }
    if ($screen_size !== '' && $padding_bottom !== '' && $padding_bottom !== null) {
      $padding_classes[] = "{$screen_size}:pb-[{$padding_bottom}rem]";
    }
  }
}
$padding_classes_str = !empty($padding_classes) ? ' ' . esc_attr(implode(' ', $padding_classes)) : '';

$section_id = 'jobs-' . uniqid();

/**
 * Query jobs
 * We'll pull (jobs_to_show + 1) so we have enough in case
 * the featured job comes from the latest list.
 */
$q = new WP_Query([
  'post_type'      => 'jobs',
  'post_status'    => 'publish',
  'posts_per_page' => max($jobs_to_show + 1, 2),
  'orderby'        => 'date',
  'order'          => 'DESC',
  'no_found_rows'  => true,
]);

$featured_job = null;
$list_jobs    = [];

if ($q->have_posts()) {
  $posts = $q->posts;

  if ($manual_featured && !empty($featured_rel) && is_array($featured_rel)) {
    $featured_obj = reset($featured_rel);
    $featured_id  = is_object($featured_obj) ? $featured_obj->ID : (int) $featured_obj;
    $featured_job = get_post($featured_id);
    // Build list from others (exclude featured if present)
    foreach ($posts as $p) {
      if ((int)$p->ID === (int)$featured_id) continue;
      $list_jobs[] = $p;
      if (count($list_jobs) >= $jobs_to_show) break;
    }
  } else {
    // Auto: first is featured; next N are list
    $featured_job = array_shift($posts);
    $list_jobs = array_slice($posts, 0, $jobs_to_show);
  }
}
wp_reset_postdata();

if (!$featured_job) {
  // Nothing to show
  return;
}

// Helper: job categories (chips)
function matrix_jobs_terms($job_id) {
  $terms = get_the_terms($job_id, 'job_category');
  if (empty($terms) || is_wp_error($terms)) return [];
  // return up to 3
  return array_slice($terms, 0, 3);
}

// Helper: image meta
function matrix_jobs_featured_image($post_id, $size = 'large') {
  $thumb_id = get_post_thumbnail_id($post_id);
  if (!$thumb_id) return ['', '', ''];
  $url   = wp_get_attachment_image_url($thumb_id, $size) ?: '';
  $alt   = get_post_meta($thumb_id, '_wp_attachment_image_alt', true) ?: get_the_title($post_id);
  $title = get_the_title($thumb_id) ?: get_the_title($post_id);
  return [esc_url($url), esc_attr($alt), esc_attr($title)];
}
?>

<section id="<?php echo esc_attr($section_id); ?>" class="relative flex overflow-hidden w-full <?php echo esc_attr($bg_color); ?>">
  <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5<?php echo $padding_classes_str; ?>">

    <main class="w-full grid grid-cols-1 lg:grid-cols-3 gap-8" role="main" aria-label="<?php echo esc_attr__('Careers page', 'matrix-starter'); ?>">
      <!-- Left: Jobs list (spans 2 cols on lg) -->
      <section class="lg:col-span-2 text-text-primary" aria-label="<?php echo esc_attr__('Job listings', 'matrix-starter'); ?>">
        <header class="sr-only">
          <h1><?php echo esc_html($sr_heading); ?></h1>
        </header>

        <?php if (!empty($list_jobs)): ?>
          <div class="flex flex-col gap-4">
            <?php foreach ($list_jobs as $index => $job): ?>
              <?php
              $pid   = $job->ID;
              $title = get_the_title($pid);
              $perma = get_permalink($pid);
              $excerpt = get_the_excerpt($pid);
              if (!$excerpt) {
                $excerpt = wp_trim_words(wp_strip_all_tags(get_post_field('post_content', $pid)), 35, '…');
              }
              $chips = matrix_jobs_terms($pid);
              ?>
              <article class="job-card flex flex-col px-6 py-6 w-full bg-white rounded-lg border-solid border-[3px] <?php echo esc_attr($border_color); ?>" role="article" aria-labelledby="<?php echo esc_attr("job{$pid}-title"); ?>">
                <header>
                  <h2 id="<?php echo esc_attr("job{$pid}-title"); ?>" class="text-2xl font-bold leading-8 text-text-primary">
                    <a href="<?php echo esc_url($perma); ?>" class="hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-text-primary-light">
                      <?php echo esc_html($title); ?>
                    </a>
                  </h2>
                </header>

                <?php if (!empty($chips)): ?>
                  <div class="flex gap-3 items-start mt-4 text-sm font-medium leading-none" role="list" aria-label="<?php echo esc_attr__('Job tags', 'matrix-starter'); ?>">
                    <?php foreach ($chips as $t): ?>
                      <span class="flex gap-2 items-center px-3 py-1 bg-emerald-100 min-h-7 rounded-full" role="listitem">
                        <span class="text-text-primary"><?php echo esc_html($t->name); ?></span>
                      </span>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>

                <?php if (!empty($excerpt)): ?>
                  <div class="mt-4 w-full text-lg leading-6 text-slate-800">
                    <p><?php echo esc_html($excerpt); ?></p>
                  </div>
                <?php endif; ?>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>

      <!-- Right: Featured Job (full content) -->
      <?php
        $f_id   = $featured_job->ID;
        $f_title= get_the_title($f_id);
        $f_link = get_permalink($f_id);
        $f_terms= matrix_jobs_terms($f_id);
        $f_content = apply_filters('the_content', get_post_field('post_content', $f_id));
        [$f_img, $f_alt, $f_img_title] = matrix_jobs_featured_image($f_id, 'large');
      ?>
      <aside class="flex flex-col px-6 py-6 bg-white rounded-lg border-solid border-[3px] <?php echo esc_attr($border_color); ?> min-w-0" role="complementary" aria-labelledby="<?php echo esc_attr("featured-job-title-{$f_id}"); ?>">
        <header>
          <h1 id="<?php echo esc_attr("featured-job-title-{$f_id}"); ?>" class="text-3xl font-bold text-text-primary">
            <a href="<?php echo esc_url($f_link); ?>" class="hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-text-primary-light">
              <?php echo esc_html($f_title); ?>
            </a>
          </h1>
        </header>

        <?php if ($f_terms): ?>
          <div class="flex gap-3 items-start mt-6 text-sm font-medium leading-none text-text-primary" role="list" aria-label="<?php echo esc_attr__('Job tags', 'matrix-starter'); ?>">
            <?php foreach ($f_terms as $t): ?>
              <span class="flex gap-2 items-center px-3 py-1 bg-emerald-100 min-h-7 rounded-full" role="listitem">
                <span class="text-text-primary"><?php echo esc_html($t->name); ?></span>
              </span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if ($f_img): ?>
          <figure class="mt-6">
            <img src="<?php echo esc_url($f_img); ?>" alt="<?php echo esc_attr($f_alt); ?>" title="<?php echo esc_attr($f_img_title); ?>" class="w-full h-auto object-cover rounded-lg" />
          </figure>
        <?php endif; ?>

        <section class="mt-6 w-full" aria-labelledby="<?php echo esc_attr("job-desc-{$f_id}"); ?>">
          <header>
            <h2 id="<?php echo esc_attr("job-desc-{$f_id}"); ?>" class="font-bold text-blue-900">
              <?php esc_html_e('Job description:', 'matrix-starter'); ?>
            </h2>
          </header>
          <div class="mt-4 leading-6 text-slate-800 wp_editor">
            <?php echo wp_kses_post($f_content); ?>
          </div>
        </section>

        <div class="mt-6">
          <a href="<?php echo esc_url($f_link); ?>" class="btn inline-flex gap-2 justify-center items-center px-6 py-3.5 text-lg font-medium text-white rounded-full bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
            <span><?php esc_html_e('Apply now', 'matrix-starter'); ?></span>
          </a>
        </div>
      </aside>
    </main>
  </div>
</section>
