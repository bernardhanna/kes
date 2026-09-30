<?php
get_header(); ?>

<?php
// Fetch the entire 404 settings group first
$not_found_settings = get_field('not_found_settings', 'option');

// Fallbacks, in case no data is set in ACF:
$title = $not_found_settings['hero_title'] ?? 'Sorry, We Can’t Find That Page.';
$text = $not_found_settings['hero_text'] ?? 'Here are some helpful links to get you back on track:';
$links = $not_found_settings['links'] ?? []; // The repeater array
$bg_color = $not_found_settings['background_color'] ?? '#f8f9fa';
$text_color = $not_found_settings['text_color'] ?? '#333';
$image_raw = $not_found_settings['hero_image'] ?? 0;
$image_id  = is_array($image_raw)
    ? (int) ($image_raw['ID'] ?? 0)
    : (int) $image_raw;
$fallback_image_url = content_url('uploads/2025/07/cryo-room-16th-June-2023-2.png');
$padding_top = $not_found_settings['padding_top'] ?? 'py-10';
$padding_bottom = $not_found_settings['padding_bottom'] ?? 'pb-10';
?>

<main class="flex overflow-hidden justify-center items-center w-full min-h-screen site-main"
  style="background-color: <?php echo esc_attr($bg_color); ?>; color: <?php echo esc_attr($text_color); ?>;">

      <div
        class="flex flex-col items-center pt-5 pb-5 mx-auto w-full xl:flex-row max-xl:px-5"
      >
   
          <figure
            class="overflow-hidden my-auto rounded-none bg-blend-normal w-[896px] max-md:max-w-full"
          >
            <div
              class="flex relative flex-col w-full min-h-[700px] max-md:max-w-full max-sm:min-h-[246px] rounded-tr-3xl rounded-br-3xl"
            >
              <?php if ($image_id) : ?>
                <?php
                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: __('404 page image', 'matrix-starter');
                echo wp_get_attachment_image($image_id, 'full', false, [
                    'class'         => 'object-cover absolute inset-0 rounded-tr-3xl rounded-br-3xl size-full',
                    'alt'           => esc_attr($image_alt),
                    'aria-hidden'   => 'true',
                    'loading'       => 'eager',
                    'fetchpriority' => 'high',
                ]);
                ?>
              <?php else : ?>
                <img
                  src="<?php echo esc_url($fallback_image_url); ?>"
                  alt="<?php echo esc_attr__('404 page image', 'matrix-starter'); ?>"
                  class="object-cover absolute inset-0 rounded-tr-3xl rounded-br-3xl size-full"
                  aria-hidden="true"
                />
              <?php endif; ?>
            </div>
          </figure>
          <article
            class="flex flex-col flex-1 px-24 my-auto basis-0 max-md:max-w-full max-sm:px-6"
          >
            <h1
              class="text-6xl font-bold tracking-tighter text-primary leading-[72px] max-md:max-w-full max-md:text-4xl max-md:leading-[53px]"
            >
             <?php echo esc_html($title); ?>
            </h1>
            <div
              class="mt-4 text-xl leading-snug text-slate-700 max-md:max-w-full"
            >
             <?php echo wp_kses_post($text); ?>
            </div>
            <div
              class="pt-8 mt-4 text-sm font-semibold leading-nonemax-sm:pt-4"
            >
              <a
                href="/"
                class="flex gap-2 justify-center items-center px-7 py-4 text-white whitespace-nowrap rounded btn border-primary bg-primary min-h-14 max-md:px-5 w-fit hover:bg-tertiary hover:text-primary hover:border-primary"
                role="button"
              >
               Return Home
              </a>
            </div>
          </article>
      </div>
</main>


<?php
get_footer();
?>