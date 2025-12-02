<?php
// Get ACF fields
$heading = get_sub_field('heading');
$heading_tag = get_sub_field('heading_tag');
$description = get_sub_field('description');
$background_image = get_sub_field('background_image');
$background_image_alt = get_post_meta($background_image, '_wp_attachment_image_alt', true) ?: 'Hero background image';
$gradient_overlay = get_sub_field('gradient_overlay');

// Generate unique section ID
$section_id = 'hero-projects-' . uniqid();

// Build padding classes
$padding_classes = [];
if (have_rows('padding_settings')) {
    while (have_rows('padding_settings')) {
        the_row();
        $screen_size = get_sub_field('screen_size');
        $padding_top = get_sub_field('padding_top');
        $padding_bottom = get_sub_field('padding_bottom');
        $padding_classes[] = "{$screen_size}:pt-[{$padding_top}rem]";
        $padding_classes[] = "{$screen_size}:pb-[{$padding_bottom}rem]";
    }
}

// Default gradient if none specified
$default_gradient = 'linear-gradient(90deg, rgba(38, 34, 98, 0.90) 20.39%, rgba(43, 57, 144, 0.00) 80.17%)';
$overlay_style = $gradient_overlay ? $gradient_overlay : $default_gradient;
?>

<section
    id="<?php echo esc_attr($section_id); ?>"
    class="relative flex overflow-hidden w-full h-[398px] max-md:h-[300px] max-sm:h-[250px] <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
    role="banner"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
    <?php if ($background_image): ?>
        <?php
        $image_srcset = wp_get_attachment_image_srcset($background_image, 'full');
        $image_src = wp_get_attachment_image_src($background_image, 'full')[0];
        ?>
        <img
            src="<?php echo esc_url($image_src); ?>"
            <?php if ($image_srcset): ?>
                srcset="<?php echo esc_attr($image_srcset); ?>"
                sizes="100vw"
            <?php endif; ?>
            alt="<?php echo esc_attr($background_image_alt); ?>"
            class="object-cover absolute top-0 left-0 size-full"
            loading="eager"
            fetchpriority="high"
        />
    <?php endif; ?>

    <div
        class="absolute top-0 left-0 opacity-70 size-full"
        style="background: <?php echo esc_attr($overlay_style); ?>;"
        aria-hidden="true"
    ></div>

    <div class="flex absolute left-24 top-36 flex-col gap-2 items-start h-28 w-[556px] max-md:left-8 max-md:h-auto max-md:top-[100px] max-md:w-[calc(100%_-_64px)] max-sm:left-4 max-sm:top-20 max-sm:gap-3 max-sm:w-[calc(100%_-_32px)]">
        <?php if (!empty($heading)): ?>
            <div class="relative self-stretch">
                <<?php echo esc_attr($heading_tag); ?>
                    id="<?php echo esc_attr($section_id); ?>-heading"
                    class="text-5xl font-bold tracking-tighter text-emerald-100 leading-[60px] max-md:text-4xl max-md:tracking-tighter max-md:leading-10 max-sm:text-3xl max-sm:tracking-tight max-sm:leading-9"
                >
                    <?php echo esc_html($heading); ?>
                </<?php echo esc_attr($heading_tag); ?>>
            </div>
        <?php endif; ?>

        <?php if (!empty($description)): ?>
            <div class="relative self-stretch">
                <p class="text-base font-medium leading-6 text-white max-md:text-base max-md:leading-5 max-sm:text-sm max-sm:leading-5">
                    <?php echo esc_html($description); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>
