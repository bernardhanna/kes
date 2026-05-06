<?php
/**
 * Jobs (Flexible Content block)
 * — Jobs list: ACF **How jobs are chosen** = Automated (latest + optional pinned) or Manual (relationship order).
 * — Expand/collapse: native `<details>`; exclusive accordion via shared `name` where supported.
 */

$show_section = (bool) get_sub_field('show_section');
if (! $show_section) {
  return;
}

if (! function_exists('matrix_jobs_terms')) {
  /**
   * Career category terms (taxonomy: job_category).
   *
   * @return array<int,\WP_Term>
   */
  function matrix_jobs_terms($job_id) {
    $terms = get_the_terms($job_id, 'job_category');
    if (empty($terms) || is_wp_error($terms)) {
      return [];
    }

    return array_slice($terms, 0, 3);
  }
}

$sr_heading   = get_sub_field('sr_heading') ?: 'Career Opportunities';
$jobs_source  = get_sub_field('jobs_source');
if (! is_string($jobs_source) || $jobs_source === '') {
  $jobs_source = 'automated'; // Rows saved before jobs_source existed
}

$bg_color = get_sub_field('background_color') ?: 'bg-white';

$padding_classes = [];
if (have_rows('padding_settings')) {
  while (have_rows('padding_settings')) {
    the_row();
    $screen_size    = get_sub_field('screen_size');
    $padding_top    = get_sub_field('padding_top');
    $padding_bottom = get_sub_field('padding_bottom');
    if ($screen_size !== '' && $padding_top !== '' && $padding_top !== null) {
      $padding_classes[] = "{$screen_size}:pt-[{$padding_top}rem]";
    }
    if ($screen_size !== '' && $padding_bottom !== '' && $padding_bottom !== null) {
      $padding_classes[] = "{$screen_size}:pb-[{$padding_bottom}rem]";
    }
  }
}
$padding_classes_str = ! empty($padding_classes) ? ' ' . esc_attr(implode(' ', $padding_classes)) : '';

$section_id = 'jobs-' . uniqid();

/** Shared name = only one career card open at a time (HTML exclusive accordion). */
$details_group_name = 'careers-acc-' . preg_replace('/[^a-zA-Z0-9_-]/', '', $section_id);

$ordered_jobs = [];

if ($jobs_source === 'manual') {
  $picked = get_sub_field('selected_jobs');
  if (! empty($picked) && is_array($picked)) {
    foreach ($picked as $row) {
      $jid = is_object($row) ? (int) $row->ID : (int) $row;
      if ($jid <= 0) {
        continue;
      }
      $p = get_post($jid);
      if ($p instanceof WP_Post && $p->post_type === 'jobs' && $p->post_status === 'publish') {
        $ordered_jobs[] = $p;
      }
    }
  }
} else {
  $manual_featured = (bool) get_sub_field('manual_featured');
  $featured_rel    = get_sub_field('featured_job');
  $jobs_to_show    = (int) (get_sub_field('jobs_to_show') ?: 4);
  $jobs_to_show    = max(1, min(24, $jobs_to_show));
  $fetch_n         = min(30, $jobs_to_show + 8);

  $q = new WP_Query([
    'post_type'      => 'jobs',
    'post_status'    => 'publish',
    'posts_per_page' => $fetch_n,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
  ]);

  if (! $q->have_posts()) {
    wp_reset_postdata();

    return;
  }

  $posts       = $q->posts;
  $seen        = [];
  $featured_id = null;

  if ($manual_featured && ! empty($featured_rel) && is_array($featured_rel)) {
    $featured_obj = reset($featured_rel);
    $featured_id  = is_object($featured_obj) ? (int) $featured_obj->ID : (int) $featured_obj;
  }

  if ($featured_id) {
    $featured_post = get_post($featured_id);
    if ($featured_post instanceof WP_Post && $featured_post->post_type === 'jobs' && $featured_post->post_status === 'publish') {
      $ordered_jobs[]               = $featured_post;
      $seen[ $featured_post->ID ] = true;
    }
  }

  foreach ($posts as $p) {
    if (isset($seen[ $p->ID ])) {
      continue;
    }
    $ordered_jobs[] = $p;
    $seen[ $p->ID ] = true;
    if (count($ordered_jobs) >= $jobs_to_show) {
      break;
    }
  }

  wp_reset_postdata();
}

if (empty($ordered_jobs)) {
  return;
}

$careers_apply_email = '';
if (function_exists('get_field')) {
  $careers_apply_email = sanitize_email((string) get_field('careers_apply_email', 'option'));
}
?>

<section id="<?php echo esc_attr($section_id); ?>" data-matrix-block="<?php echo esc_attr(str_replace('_', '-', get_row_layout()) . '-' . get_row_index()); ?>" class="jobs-flexi relative flex overflow-hidden <?php echo esc_attr($bg_color); ?>">
  <div class="flex flex-col items-center w-full mx-auto max-w-7xl pt-5 pb-20 px-24 max-lg:px-5<?php echo $padding_classes_str; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
    <div class="flex flex-col w-full">
      <header class="sr-only">
        <h1><?php echo esc_html($sr_heading); ?></h1>
      </header>

      <div class="flex flex-col gap-4 w-full" role="list" aria-label="<?php echo esc_attr__('Job openings', 'matrix-starter'); ?>">
        <?php foreach ($ordered_jobs as $job) : ?>
          <?php
          $pid      = (int) $job->ID;
          $title    = get_the_title($pid);
          $perma    = get_permalink($pid);
          $excerpt  = get_the_excerpt($pid);
          if (! $excerpt) {
            $excerpt = wp_trim_words(wp_strip_all_tags(get_post_field('post_content', $pid)), 40, '…');
          }
          $chips    = matrix_jobs_terms($pid);
          $label_id = "{$section_id}-job-title-{$pid}";
          $detail_id = "{$section_id}-job-detail-{$pid}";
          $expanded = apply_filters('the_content', get_post_field('post_content', $pid));

          $apply_href = $perma;
          if ($careers_apply_email && is_email($careers_apply_email)) {
            $apply_subject = sprintf(
              /* translators: %s: job title */
              __('Application: %s', 'matrix-starter'),
              $title
            );
            $apply_href = 'mailto:' . $careers_apply_email . '?subject=' . rawurlencode($apply_subject);
          }
          ?>

          <article
            class="job-card-careers flex flex-col px-10 py-8 w-full max-md:px-5 rounded-lg bg-white transition-[background-color] duration-200"
            role="listitem"
            aria-labelledby="<?php echo esc_attr($label_id); ?>"
          >
            <header>
              <h2 id="<?php echo esc_attr($label_id); ?>" class="job-card-careers__title mb-4">
                <?php echo esc_html($title); ?>
              </h2>
            </header>

            <?php if (! empty($chips)) : ?>
              <div class="job-card-careers__tags flex flex-wrap gap-6 items-start mb-4 w-full" role="list" aria-label="<?php echo esc_attr__('Career categories', 'matrix-starter'); ?>">
                <?php foreach ($chips as $t) : ?>
                  <span class="job-card-careers__tag flex gap-2 items-center px-3 py-1 bg-white border border-blue-900 border-solid min-h-7 rounded-full" role="listitem">
                    <?php echo esc_html($t->name); ?>
                  </span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <?php if (! empty($excerpt)) : ?>
              <div class="job-card-careers__excerpt mb-4 w-full">
                <p><?php echo esc_html($excerpt); ?></p>
              </div>
            <?php endif; ?>

            <details id="<?php echo esc_attr($detail_id); ?>" name="<?php echo esc_attr($details_group_name); ?>" class="job-card-careers__details">
              <summary class="job-card-careers__summary flex justify-center mt-4 w-full list-none cursor-pointer rounded-full py-1 text-blue-900 hover:opacity-90 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-900 [&::-webkit-details-marker]:hidden">
                <span class="sr-only"><?php esc_html_e('Open full job description', 'matrix-starter'); ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 42 42" fill="none" class="job-card-careers__chevron job-card-careers__chevron--open shrink-0" aria-hidden="true">
                  <path d="M10.5 15.75L21 26.25L31.5 15.75" stroke="#2B3990" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </summary>

              <div class="job-card-careers__panel pt-2">
                <div class="mb-6 w-full">
                  <h3 class="job-card-careers__section-label mb-6"><?php esc_html_e('Job description:', 'matrix-starter'); ?></h3>
                  <div class="job-card-careers__body-copy wp_editor job-card-accordion__content job-card-builder-content">
                    <?php echo wp_kses_post($expanded); ?>
                  </div>
                </div>

                <a
                  href="<?php echo esc_url($apply_href); ?>"
                  class="job-card-careers__apply mb-6 w-full inline-flex justify-center items-center text-center no-underline focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-900"
                  aria-label="<?php echo esc_attr(sprintf(__('Apply for %s', 'matrix-starter'), $title)); ?>"
                >
                  <?php esc_html_e('Apply now', 'matrix-starter'); ?>
                </a>

                <button
                  type="button"
                  class="job-card-careers__close flex justify-center w-full rounded-full py-2 text-blue-900 hover:bg-black/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-900"
                  aria-label="<?php echo esc_attr(__('Close job details', 'matrix-starter')); ?>"
                  onclick="var d=this.closest('details');if(d){d.removeAttribute('open');var s=d.querySelector('summary');if(s){s.focus();}}"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 42 42" fill="none" class="job-card-careers__chevron job-card-careers__chevron--close shrink-0" aria-hidden="true">
                    <path d="M10.5 15.75L21 26.25L31.5 15.75" stroke="#2B3990" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </button>
              </div>
            </details>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
