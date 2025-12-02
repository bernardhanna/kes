<?php
// Generate unique section ID
$section_id = 'sustainability_' . uniqid();

// Content fields
$heading = get_sub_field('heading');
$heading_tag = get_sub_field('heading_tag');
$content = get_sub_field('content');
$image = get_sub_field('image');
$image_alt = get_post_meta($image, '_wp_attachment_image_alt', true) ?: 'Sustainability image';
$primary_button = get_sub_field('primary_button');
$secondary_button = get_sub_field('secondary_button');

// Design fields
$background_color = get_sub_field('background_color');
$heading_color = get_sub_field('heading_color');
$text_color = get_sub_field('text_color');
$divider_color = get_sub_field('divider_color');

// Layout fields
$reverse_layout = get_sub_field('reverse_layout');

// Padding settings
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
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
    <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5">
        <div class="flex relative mr-auto h-auto bg-white min-h-[auto] w-full <?php echo $reverse_layout ? 'max-md:flex-col max-md:min-h-[auto] md:flex-row-reverse' : 'max-md:flex-col max-md:min-h-[auto] md:flex-row'; ?>">

            <!-- Content Section -->
            <div class="flex relative flex-col gap-8 justify-center items-center  pr-10 w-[640px] max-md:px-10  max-md:w-full max-sm:px-5">

                <!-- Heading Section -->
                <?php if (!empty($heading)): ?>
                <header class="flex relative flex-col gap-1 items-start self-stretch">
                    <<?php echo esc_attr($heading_tag); ?>
                        id="<?php echo esc_attr($section_id); ?>-heading"
                        class="relative self-stretch text-3xl font-bold leading-10 max-sm:text-2xl max-sm:leading-8"
                        style="color: <?php echo esc_attr($heading_color); ?>;"
                    >
                        <?php echo esc_html($heading); ?>
                    </<?php echo esc_attr($heading_tag); ?>>

                    <div
                        class="relative w-8 h-1"
                        style="background-color: <?php echo esc_attr($divider_color); ?>;"
                        role="presentation"
                        aria-hidden="true"
                    ></div>
                </header>
                <?php endif; ?>

                <!-- Content Section -->
                <?php if (!empty($content)): ?>
                <main class="relative self-stretch text-base leading-5 max-sm:text-sm max-sm:leading-5 wp_editor" style="color: <?php echo esc_attr($text_color); ?>;">
                    <?php echo wp_kses_post($content); ?>
                </main>
                <?php endif; ?>

                <!-- Buttons Section -->
                <?php if (($primary_button && is_array($primary_button) && isset($primary_button['url'], $primary_button['title'])) ||
                          ($secondary_button && is_array($secondary_button) && isset($secondary_button['url'], $secondary_button['title']))): ?>
                <div class="flex relative gap-8 items-start max-md:flex-col max-md:gap-4 max-md:w-full" role="group" aria-label="Action buttons">

                    <!-- Primary Button -->
                    <?php if ($primary_button && is_array($primary_button) && isset($primary_button['url'], $primary_button['title'])): ?>
                    <a
                        href="<?php echo esc_url($primary_button['url']); ?>"
                        class="flex relative gap-2 justify-center items-center px-6 py-4 cursor-pointer h-[52px] rounded-[100px] max-md:w-full bg-primary text-white hover:bg-primary-dark focus:bg-primary-dark transition-colors duration-300 btn w-fit whitespace-nowrap"
                        target="<?php echo esc_attr($primary_button['target'] ?? '_self'); ?>"
                        aria-label="<?php echo esc_attr($primary_button['title']); ?>"
                    >
                        <span class="relative text-lg font-medium leading-6 max-sm:text-base">
                            <?php echo esc_html($primary_button['title']); ?>
                        </span>
                    </a>
                    <?php endif; ?>

                    <!-- Secondary Button -->
                    <?php if ($secondary_button && is_array($secondary_button) && isset($secondary_button['url'], $secondary_button['title'])): ?>
                    <a
                        href="<?php echo esc_url($secondary_button['url']); ?>"
                        class="flex relative gap-2 justify-center items-center px-6 py-4 bg-white border-2 border-blue-900 border-solid cursor-pointer h-[52px] rounded-[100px] max-md:w-full text-blue-900 hover:bg-blue-900 hover:text-white focus:bg-blue-900 focus:text-white transition-colors duration-300 btn w-fit whitespace-nowrap"
                        target="<?php echo esc_attr($secondary_button['target'] ?? '_self'); ?>"
                        aria-label="<?php echo esc_attr($secondary_button['title']); ?>"
                    >
                        <span class="relative text-lg font-medium leading-6 max-sm:text-base">
                            <?php echo esc_html($secondary_button['title']); ?>
                        </span>
                    </a>
                    <?php endif; ?>

                </div>
                <?php endif; ?>

            </div>

            <!-- Image Section -->
            <?php if ($image): ?>
            <div class="flex relative flex-1 justify-center items-center rounded-lg max-md:py-10 max-md:pr-10 max-md:pl-0 max-md:w-full max-sm:p-5">
                <?php echo wp_get_attachment_image($image, 'full', false, [
                    'alt' => esc_attr($image_alt),
                    'class' => 'relative rounded-lg h-[340px] w-[502px] max-md:relative max-md:top-0 max-md:left-0 max-md:w-full max-md:h-auto max-md:max-w-[502px] max-sm:w-full max-sm:max-w-full object-cover',
                    'loading' => 'lazy'
                ]); ?>
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>
