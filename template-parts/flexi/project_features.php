<?php
// Get ACF fields
$section_heading = get_sub_field('section_heading');
$section_heading_tag = get_sub_field('section_heading_tag');
$features = get_sub_field('features');
$background_color = get_sub_field('background_color');

// Generate unique section ID
$section_id = 'project-features-' . uniqid();

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
?>

<section
    id="<?php echo esc_attr($section_id); ?>"
    class="relative flex overflow-hidden <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
    style="background-color: <?php echo esc_attr($background_color); ?>;"
    role="region"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
    <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5">

        <?php if (!empty($section_heading)): ?>
            <<?php echo esc_attr($section_heading_tag); ?>
                id="<?php echo esc_attr($section_id); ?>-heading"
                class="text-2xl font-bold text-slate-600 mb-8 text-center max-sm:text-xl max-sm:mb-6"
            >
                <?php echo esc_html($section_heading); ?>
            </<?php echo esc_attr($section_heading_tag); ?>>
        <?php endif; ?>

        <?php if ($features && is_array($features)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16 w-full max-md:gap-8 max-sm:gap-6">
                <?php foreach ($features as $index => $feature):
                    $feature_image = $feature['feature_image'];
                    $feature_heading = $feature['feature_heading'];
                    $feature_description = $feature['feature_description'];
                    $feature_image_alt = '';

                    if ($feature_image) {
                        $feature_image_alt = get_post_meta($feature_image, '_wp_attachment_image_alt', true);
                        if (empty($feature_image_alt)) {
                            $feature_image_alt = $feature_heading ? esc_attr($feature_heading) . ' icon' : 'Feature icon';
                        }
                    }

                    $feature_id = $section_id . '-feature-' . ($index + 1);
                ?>
                    <article
                        class="flex gap-6 items-start w-full max-md:max-w-[280px] max-md:mx-auto max-sm:max-w-[400px]"
                        role="article"
                        aria-labelledby="<?php echo esc_attr($feature_id); ?>-heading"
                    >
                        <?php if ($feature_image): ?>
                            <div class="shrink-0 w-20 h-[74px] max-sm:w-[60px] max-sm:h-14" role="img" aria-hidden="true">
                                <?php echo wp_get_attachment_image(
                                    $feature_image,
                                    'full',
                                    false,
                                    [
                                        'alt' => esc_attr($feature_image_alt),
                                        'class' => 'w-full h-full object-contain',
                                        'loading' => 'lazy'
                                    ]
                                ); ?>
                            </div>
                        <?php endif; ?>

                        <div class="flex flex-col gap-2 items-start flex-1">
                            <?php if (!empty($feature_heading)): ?>
                                <h3
                                    id="<?php echo esc_attr($feature_id); ?>-heading"
                                    class="text-lg font-bold leading-7 text-slate-600 max-sm:text-base max-sm:leading-6"
                                >
                                    <?php echo esc_html($feature_heading); ?>
                                </h3>
                            <?php endif; ?>

                            <?php if (!empty($feature_description)): ?>
                                <p class="text-sm leading-5 text-slate-600 max-sm:text-sm max-sm:leading-5">
                                    <?php echo esc_html($feature_description); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
