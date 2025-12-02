<?php
$heading = get_sub_field('heading');
$heading_tag = get_sub_field('heading_tag');
$content = get_sub_field('content');
$background_color = get_sub_field('background_color');
$divider_color = get_sub_field('divider_color');

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
    style="background-color: <?php echo esc_attr($background_color); ?>;"
    role="region"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
    <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5">
        <div class="flex overflow-hidden justify-between items-center self-stretch px-24 py-20 max-md:px-5">
            <div class="flex flex-col flex-1 shrink justify-center self-stretch my-auto w-full basis-0 min-w-60 max-md:max-w-full">
                <?php if (!empty($heading)): ?>
                    <div class="w-full text-4xl font-bold tracking-tighter leading-none text-violet-950 max-md:max-w-full">
                        <<?php echo esc_attr($heading_tag); ?>
                            id="<?php echo esc_attr($section_id); ?>-heading"
                            class="text-violet-950 max-md:max-w-full"
                        >
                            <?php echo esc_html($heading); ?>
                        </<?php echo esc_attr($heading_tag); ?>>
                        <div
                            class="flex mt-1 w-8 min-h-1"
                            style="background-color: <?php echo esc_attr($divider_color); ?>;"
                            role="presentation"
                            aria-hidden="true"
                        ></div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($content)): ?>
                    <div class="mt-6 text-lg leading-none text-slate-700 max-md:max-w-full wp_editor">
                        <?php echo wp_kses_post($content); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
