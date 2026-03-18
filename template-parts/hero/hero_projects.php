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
$mobile_overlay_style = 'linear-gradient(1deg, rgba(38, 34, 98, 0.90) 20.85%, rgba(43, 57, 144, 0.00) 79.62%)';
?>

<section
    id="<?php echo esc_attr($section_id); ?>"
    class="relative mt-[5rem] flex overflow-hidden w-full h-[398px] max-md:h-[380px] <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
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
        class="hidden absolute top-0 left-0 opacity-80 size-full sm:block"
        style="background: <?php echo esc_attr($overlay_style); ?>;"
        aria-hidden="true"
    ></div>

    <div
        class="absolute top-0 left-0 size-full sm:hidden"
        style="background: <?php echo esc_attr($mobile_overlay_style); ?>;"
        aria-hidden="true"
    ></div>

    <div class="w-full max-w-[1084px] mx-auto h-full flex flex-col justify-center max-md:px-5 max-sm:justify-end max-sm:pb-5">

            <?php if (!empty($heading)): ?>
                <div class="relative self-stretch">
                    <<?php echo esc_attr($heading_tag); ?>
                        id="<?php echo esc_attr($section_id); ?>-heading"
                        class="text-[#CBE9E1] max-sm:text-3xl max-sm:leading-[38px] text-[48px] font-bold leading-[60px] tracking-[-0.96px] font-primary"
                    >
                        <?php echo esc_html($heading); ?> 
                    </<?php echo esc_attr($heading_tag); ?>>
                </div>
            <?php endif; ?>

            <?php if (!empty($description)): ?>
                <div class="relative self-stretch w-full max-w-[556px] pt-2">
                    <p class="text-white font-secondary  text-base font-medium leading-[22px]">
                        <?php echo esc_html($description); ?>
                    </p>
                </div>
            <?php endif; ?>

    </div>
</section>
