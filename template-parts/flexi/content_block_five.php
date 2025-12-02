<?php
$section_id = 'content-section-five-' . uniqid();
$heading = get_sub_field('heading');
$heading_tag = get_sub_field('heading_tag');
$heading_underline_color = get_sub_field('heading_underline_color');
$main_content = get_sub_field('main_content');
$secondary_content = get_sub_field('secondary_content');
$image = get_sub_field('image');
$image_alt = get_post_meta($image, '_wp_attachment_image_alt', true) ?: 'Quality, Health & Safety Image';
$button = get_sub_field('button');
$reverse_layout = get_sub_field('reverse_layout');
$background_color = get_sub_field('background_color');
$background_gradient = get_sub_field('background_gradient');

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

$background_style = '';
if ($background_gradient) {
    $background_style = "background: {$background_gradient};";
} elseif ($background_color) {
    $background_style = "background-color: {$background_color};";
}
?>

<section
    id="<?php echo esc_attr($section_id); ?>"
    class="relative flex overflow-hidden <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
    style="<?php echo esc_attr($background_style); ?>"
    role="region"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
    <div class="flex flex-col items-center w-full mx-auto max-w-container pt-20 pb-20 max-lg:px-5">
        <div class="flex overflow-hidden flex-col items-center px-24 w-full max-md:px-5 max-md:max-w-full">
            <div class="flex flex-wrap gap-10 items-start max-w-full w-[1040px] <?php echo $reverse_layout ? 'flex-row-reverse' : ''; ?>">

                <!-- Content Column -->
                <div class="flex flex-col flex-1 shrink text-lg text-gray-50 basis-0 min-w-60 max-md:max-w-full">

                    <?php if (!empty($heading)): ?>
                    <header class="w-full text-3xl font-bold leading-none text-white max-md:max-w-full">
                        <<?php echo esc_attr($heading_tag); ?>
                            id="<?php echo esc_attr($section_id); ?>-heading"
                            class="text-white max-md:max-w-full"
                        >
                            <?php echo esc_html($heading); ?>
                        </<?php echo esc_attr($heading_tag); ?>>

                        <div
                            class="flex mt-1 w-8 min-h-1"
                            style="background-color: <?php echo esc_attr($heading_underline_color); ?>;"
                            role="presentation"
                            aria-hidden="true"
                        ></div>
                    </header>
                    <?php endif; ?>

                    <?php if (!empty($main_content)): ?>
                    <div class="mt-6 leading-6 text-gray-50 max-md:max-w-full wp_editor">
                        <?php echo wp_kses_post($main_content); ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($secondary_content)): ?>
                    <div class="mt-6 text-base leading-5 text-gray-50 max-md:max-w-full wp_editor">
                        <?php echo wp_kses_post($secondary_content); ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($button && is_array($button) && isset($button['url'], $button['title'])): ?>
                    <div class="mt-6">
                        <a
                            href="<?php echo esc_url($button['url']); ?>"
                            class="flex gap-2 justify-center items-center self-start px-6 py-3.5 font-medium leading-none text-blue-900 bg-white rounded-full transition-all duration-300 btn w-fit whitespace-nowrap hover:bg-blue-50 hover:shadow-md focus:ring-2 focus:ring-offset-2 focus:ring-white focus:ring-offset-blue-900"
                            target="<?php echo esc_attr($button['target'] ?? '_self'); ?>"
                            aria-label="<?php echo esc_attr($button['title']); ?> - Learn more about our safety guidelines"
                        >
                            <span class="text-blue-900 font-medium">
                                <?php echo esc_html($button['title']); ?>
                            </span>
                        </a>
                    </div>
                    <?php endif; ?>

                </div>

                <!-- Image Column -->
                <?php if ($image): ?>
                <div class="overflow-hidden rounded-lg w-[502px] max-md:max-w-full" role="img" aria-labelledby="<?php echo esc_attr($section_id); ?>-image-caption">
                    <?php echo wp_get_attachment_image($image, 'full', false, [
                        'alt' => esc_attr($image_alt),
                        'class' => 'object-contain w-full aspect-[1.28] max-md:max-w-full',
                        'id' => esc_attr($section_id) . '-image',
                    ]); ?>
                    <span id="<?php echo esc_attr($section_id); ?>-image-caption" class="sr-only">
                        <?php echo esc_html($image_alt); ?>
                    </span>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>
