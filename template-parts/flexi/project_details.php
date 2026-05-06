<?php
$section_id = 'project-details-' . uniqid();
$heading = get_sub_field('heading');
$heading_tag = get_sub_field('heading_tag');
$description = get_sub_field('description');
$project_image = get_sub_field('project_image');
$project_image_alt = get_post_meta($project_image, '_wp_attachment_image_alt', true) ?: 'Project image';

// Project stats
$status = get_sub_field('status');
$completion_year = get_sub_field('completion_year');
$size_details = get_sub_field('size_details');
$client = get_sub_field('client');
$team_details = get_sub_field('team_details');

// Design options
$background_color = get_sub_field('background_color');

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
    data-matrix-block="<?php echo esc_attr(str_replace('_', '-', get_row_layout()) . '-' . get_row_index()); ?>"
    class="relative flex  <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
    style="background-color: <?php echo esc_attr($background_color); ?>;"
>
    <div class="flex flex-col items-center py-10 mx-auto w-full md:py-14 max-w-[1084px] max-xl:px-5">
        <div class="grid grid-cols-1 gap-8 md:gap-16 items-center w-full md:grid-cols-[45%_50%]">

            <!-- Project Information Column -->
            <article class="flex flex-col w-full">

                <!-- Project Heading Section -->
                <?php if (!empty($heading)): ?>
                <header class="w-full max-md:max-w-full">
                    <div class="w-full text-4xl font-bold tracking-tighter leading-none text-primary max-md:max-w-full">
                        <<?php echo esc_attr($heading_tag); ?> class="text-primary max-md:max-w-full">
                            <?php echo esc_html($heading); ?>
                        </<?php echo esc_attr($heading_tag); ?>>
                        <div class="flex mt-1 w-8 bg-cyan-500 min-h-1" role="presentation" aria-hidden="true"></div>
                    </div>

                    <?php if (!empty($description)): ?>
                    <div class="mt-3  opacity-96 !text-2xl !not-italic !font-normal !leading-8 !text-[#1D2939]">
                        <?php echo wp_kses_post($description); ?>
                    </div>
                    <?php endif; ?>
                </header>
                <?php endif; ?>

                <!-- Project Statistics -->
                <div class="flex flex-col items-start self-start mt-8 text-lg">

                    <?php if (!empty($status)): ?>
                    <div class="leading-none">
                        <div class="text-lg not-italic font-bold leading-6 text-[#2B3990] font-secondary">Status</div>
                        <div class="mt-1 text-lg not-italic font-normal leading-6 text-[#1D2939] opacity-96 font-secondary">
                            <?php echo esc_html($status); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($completion_year)): ?>
                    <div class="mt-4 leading-none">
                        <div class="text-lg not-italic font-bold leading-6 text-[#2B3990] font-secondary">Completion Year</div>
                        <div class="mt-1 text-lg not-italic font-normal leading-6 text-[#1D2939] opacity-96 font-secondary">
                            <?php echo esc_html($completion_year); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($size_details)): ?>
                    <div class="mt-4">
                        <div class="text-lg not-italic font-bold leading-6 text-[#2B3990] font-secondary">Size</div>
                        <div class="mt-1 text-lg not-italic font-normal leading-6 text-[#1D2939] opacity-96 font-secondary wp_editor">
                            <?php echo wp_kses_post($size_details); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($client)): ?>
                    <div class="self-stretch mt-4 leading-none">
                        <div class="text-lg not-italic font-bold leading-6 text-[#2B3990] font-secondary">Client</div>
                        <div class="mt-1 text-lg not-italic font-normal leading-6 text-[#1D2939] opacity-96 font-secondary">
                            <?php echo esc_html($client); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($team_details)): ?>
                    <div class="mt-4">
                        <div class="text-lg not-italic font-bold leading-6 text-[#2B3990] font-secondary">Team</div>
                        <div class="mt-1 text-lg not-italic font-normal leading-6 text-[#1D2939] opacity-96 font-secondary wp_editor">
                            <?php echo wp_kses_post($team_details); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </article>

            <!-- Project Image Column -->
            <?php if ($project_image): ?>
            <div class="flex justify-end items-center rounded-lg max-lg:px-2 max-lg:justify-center lg:justify-self-end">
                <?php echo wp_get_attachment_image($project_image, 'full', false, [
                    'alt' => esc_attr($project_image_alt),
                    'class' => 'object-contain lg:object-cover w-full h-auto lg:max-w-[575px] lg:max-h-[595px]  rounded-lg relative xxl:-right-[2rem] max-md:max-w-[375px] max-md:h-[398px] xxl:top-[.8rem] ',
                    'loading' => 'lazy'
                ]); ?>
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>
