<?php
$heading = get_sub_field('heading');
$heading_tag = get_sub_field('heading_tag');

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

$section_id = 'title-section-' . wp_rand(1000, 9999);
?>

<section
    id="<?php echo esc_attr($section_id); ?>"
    class="relative flex overflow-hidden <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
    role="region"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
    <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5">
        <header class="flex relative flex-col gap-1 items-start self-stretch pl-24 max-md:pl-12 max-sm:pl-6">
            <?php if (!empty($heading)): ?>
                <<?php echo esc_attr($heading_tag); ?>
                    id="<?php echo esc_attr($section_id); ?>-heading"
                    class="relative self-stretch text-3xl font-bold leading-10 text-text-primary max-md:text-2xl max-md:leading-9 max-sm:text-2xl max-sm:leading-8"
                >
                    <?php echo esc_html($heading); ?>
                </<?php echo esc_attr($heading_tag); ?>>
            <?php endif; ?>

            <div
                class="relative w-8 h-1 bg-cyan-500 max-sm:w-7 max-sm:h-[3px]"
                role="presentation"
                aria-hidden="true"
            ></div>
        </header>
    </div>
</section>
