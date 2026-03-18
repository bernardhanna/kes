<?php
$heading = get_sub_field('heading');
$heading_tag = get_sub_field('heading_tag');
$description = get_sub_field('description');
$content_section_1 = get_sub_field('content_section_1');
$content_section_2 = get_sub_field('content_section_2');
$content_section_3 = get_sub_field('content_section_3');
$background_color = get_sub_field('background_color');

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

$section_id = 'quote-section-' . uniqid();
?>

<section
    id="<?php echo esc_attr($section_id); ?>"
    class="relative flex overflow-hidden <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
    style="background-color: <?php echo esc_attr($background_color); ?>;"
    role="region"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
    <div class="flex flex-col items-center pt-5 pb-5 lg:py-12  xl:py-16 mx-auto w-full max-w-[1250px] max-xl:px-5">
            <div class="flex flex-col flex-1 gap-6 justify-center items-center pb-5 lg:pb-12 xl:pl-[7rem]">

                <?php if (!empty($heading)): ?>
                <header class="flex flex-col gap-1 items-start self-stretch">
                    <<?php echo esc_attr($heading_tag); ?>
                        id="<?php echo esc_attr($section_id); ?>-heading"
                        class="self-stretch text-3xl font-bold leading-10 text-primary max-md:text-2xl max-md:leading-9 max-sm:text-2xl max-sm:leading-8"
                    >
                        <?php echo esc_html($heading); ?>
                    </<?php echo esc_attr($heading_tag); ?>>
                    <div
                        class="w-8 h-1 bg-cyan-500"
                        role="presentation"
                        aria-hidden="true"
                    ></div>
                </header>
                <?php endif; ?>

                <?php if (!empty($description)): ?>
                <div class="self-stretch text-[24px] font-normal leading-[32px] text-[#1D2939]">
                    <?php echo wp_kses_post($description); ?>
                </div>
                <?php endif; ?>

                <div class="flex flex-col gap-7 items-start self-stretch max-sm:gap-5">

                    <?php if (!empty($content_section_1) || !empty($content_section_2)): ?>
                    <div class="flex gap-10 items-center self-stretch max-md:gap-6 max-sm:gap-4">
                        <div class="flex flex-1 gap-10 items-center max-md:gap-6 max-sm:flex-col max-sm:gap-4">

                            <?php if (!empty($content_section_1)): ?>
                            <div class="flex-1 text-[#1D2939] text-[16px] font-normal leading-[20px] wp_editor font-secondary">
                                <?php echo wp_kses_post($content_section_1); ?>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($content_section_2)): ?>
                            <div class="flex-1 text-[#1D2939] text-[16px] font-normal leading-[20px] wp_editor font-secondary">
                                <?php echo wp_kses_post($content_section_2); ?>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($content_section_3)): ?>
                    <div class="flex gap-10 items-center self-stretch max-md:gap-6 max-sm:gap-4">
                        <div class="flex flex-1 gap-10 items-center max-md:gap-6 max-sm:flex-col max-sm:gap-4">

                            <div class="flex-1 text-base leading-5 text-slate-800 wp_editor font-secondary">
                                <?php echo wp_kses_post($content_section_3); ?>
                            </div>

                            <div class="flex-1 text-base leading-5 text-slate-800">
                                <!-- Empty column for layout balance -->
                            </div>

                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
    </div>
</section>
