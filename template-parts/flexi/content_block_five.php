<?php
$section_id = 'content-section-five-' . uniqid();
$heading = get_sub_field('heading');
if ($heading === '' || $heading === null) {
    $heading = 'Quality, Health & Safety';
}
$heading_tag = get_sub_field('heading_tag') ?: 'h2';
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

        if ($screen_size) {
            $padding_classes[] = "{$screen_size}:pt-[{$padding_top}rem]";
            $padding_classes[] = "{$screen_size}:pb-[{$padding_bottom}rem]";
        } else {
            $padding_classes[] = "pt-[{$padding_top}rem]";
            $padding_classes[] = "pb-[{$padding_bottom}rem]";
        }
    }
}

$background_style = '';
if ($background_gradient) {
    $background_style = "background: {$background_gradient};";
} elseif ($background_color) {
    $background_style = "background-color: {$background_color};";
}

$content_order_class = 'order-1 ' . ($reverse_layout ? 'lg:order-2' : 'lg:order-1');
$image_order_class   = 'order-2 ' . ($reverse_layout ? 'lg:order-1' : 'lg:order-2');
?>

<section
    id="<?php echo esc_attr($section_id); ?>"
    class="content-section-five relative overflow-hidden <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
    style="<?php echo esc_attr($background_style); ?>"
    role="region"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
    <div class="mx-auto w-full max-w-[1280px] px-5 py-[2.5rem] lg:py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[minmax(0,1fr)_502px] gap-8 lg:gap-12 items-start max-w-[1250px] mx-auto pl-0 xl:pl-[5rem] xxl:pl-[11.5rem]">

            <!-- Content Column -->
            <div class="<?php echo esc_attr($content_order_class); ?> min-w-0">
                <header class="w-full">
                    <<?php echo esc_attr($heading_tag); ?>
                        id="<?php echo esc_attr($section_id); ?>-heading"
                        class="text-3xl font-bold leading-none text-white"
                    >
                        <?php echo esc_html($heading); ?>
                    </<?php echo esc_attr($heading_tag); ?>>

                        <div
                            class="mt-1 w-8 h-1"
                            style="background-color: <?php echo esc_attr($heading_underline_color); ?>;"
                            aria-hidden="true"
                        ></div>
                </header>

                <?php if (!empty($main_content)): ?>
                    <div class="mt-6 text-lg leading-6 text-gray-50 wp_editor">
                        <?php echo wp_kses_post($main_content); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($secondary_content)): ?>
                    <div class="mt-6 text-base leading-5 text-gray-50 small_wp_editor">
                        <?php echo wp_kses_post($secondary_content); ?>
                    </div>
                <?php endif; ?>

                <?php if ($button && is_array($button) && isset($button['url'], $button['title'])): ?>
                    <div class="mt-6">
                        <a
                            href="<?php echo esc_url($button['url']); ?>"
                            class="inline-flex gap-2 justify-center items-center w-full sm:w-fit self-start px-6 py-3.5 text-[18px] font-normal font-medium leading-[24px] text-[#262262] font-medium text-lg leading-6 font-secondary bg-white rounded-full transition-all duration-300 content-section-five-btn btn"
                            target="<?php echo esc_attr($button['target'] ?? '_self'); ?>"
                            <?php if (($button['target'] ?? '') === '_blank') : ?>rel="noopener noreferrer"<?php endif; ?>
                            aria-label="<?php echo esc_attr($button['title']); ?>"
                        >
                            <span class="text-[#262262] font-medium text-lg leading-6 font-secondary">
                                <?php echo esc_html($button['title']); ?>
                            </span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Image Column -->
            <?php if ($image): ?>
                <div class="<?php echo esc_attr($image_order_class); ?> w-full max-w-[502px] lg:justify-self-end relative xl:-right-[1rem]">
                    <?php echo wp_get_attachment_image($image, 'full', false, [
                        'alt' => esc_attr($image_alt),
                        'class' => 'block w-full rounded-lg object-contain aspect-[1.28]',
                        'id' => esc_attr($section_id) . '-image',
                    ]); ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
