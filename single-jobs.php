<?php
/**
 * Single Job — application form page (Figma 784:2889).
 */
get_header();

$enable_breadcrumbs = function_exists('get_field') ? get_field('enable_breadcrumbs', 'option') : false;
?>
<main <?php echo matrix_starter_main_id_attr(); ?> class="site-main w-full min-h-screen overflow-hidden">
  <?php if ($enable_breadcrumbs !== false) : ?>
    <?php get_template_part('template-parts/header/breadcrumbs'); ?>
  <?php endif; ?>

  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <?php
      $job_id = get_the_ID();
      $config = matrix_job_application_config($job_id);
      ?>

      <header class="mx-auto w-full max-w-container px-6 py-12 sm:px-12 lg:px-24 lg:py-16 max-xl:px-5">
        <div class="flex w-full max-w-3xl flex-col gap-3">
          <h1
            id="job-application-heading"
            class="font-red-hat-display text-[36px] font-bold leading-[44px] tracking-[-0.72px] text-[#262262] max-md:text-[28px] max-md:leading-[34px]"
          >
            <?php echo esc_html($config['page_heading']); ?>
          </h1>
          <div class="w-8 h-1 shrink-0 bg-blue-100" aria-hidden="true"></div>
          <?php if ($config['intro'] !== '') : ?>
            <p class="mt-2 font-red-hat-text text-[18px] font-normal leading-[24px] text-[#1D2939]">
              <?php echo wp_kses_post($config['intro']); ?>
            </p>
          <?php endif; ?>
        </div>
      </header>

      <?php matrix_render_job_application_form($job_id); ?>
    <?php endwhile; ?>
  <?php else : ?>
    <div class="mx-auto max-w-container px-5 py-12">
      <p><?php esc_html_e('Job not found.', 'matrix-starter'); ?></p>
    </div>
  <?php endif; ?>
</main>
<?php
get_footer();
