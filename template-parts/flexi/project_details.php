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
    class="relative flex overflow-hidden <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
    style="background-color: <?php echo esc_attr($background_color); ?>;"
>
    <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5">
        <div class="flex flex-wrap gap-8 items-center w-full">

            <!-- Project Information Column -->
            <article class="flex flex-col self-stretch py-20 pr-10 pl-24 my-auto w-full max-w-[640px] max-md:px-5 max-md:max-w-full">

                <!-- Project Heading Section -->
                <?php if (!empty($heading)): ?>
                <header class="w-full max-md:max-w-full">
                    <div class="w-full text-4xl font-bold tracking-tighter leading-none text-text-primary max-md:max-w-full">
                        <<?php echo esc_attr($heading_tag); ?> class="text-text-primary max-md:max-w-full">
                            <?php echo esc_html($heading); ?>
                        </<?php echo esc_attr($heading_tag); ?>>
                        <div class="flex mt-1 w-8 bg-cyan-500 min-h-1" role="presentation" aria-hidden="true"></div>
                    </div>

                    <?php if (!empty($description)): ?>
                    <div class="mt-3 text-2xl leading-8 opacity-96 text-slate-800 max-md:max-w-full wp_editor">
                        <?php echo wp_kses_post($description); ?>
                    </div>
                    <?php endif; ?>
                </header>
                <?php endif; ?>

                <!-- Project Statistics -->
                <div class="flex flex-col items-start self-start mt-8 text-lg">

                    <?php if (!empty($status)): ?>
                    <div class="leading-none">
                        <div class="font-bold text-blue-900">Status</div>
                        <div class="mt-1 opacity-96 text-slate-800">
                            <?php echo esc_html($status); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($completion_year)): ?>
                    <div class="mt-4 leading-none">
                        <div class="font-bold text-blue-900">Completion Year</div>
                        <div class="mt-1 opacity-96 text-slate-800">
                            <?php echo esc_html($completion_year); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($size_details)): ?>
                    <div class="mt-4">
                        <div class="font-bold leading-none text-blue-900">Size</div>
                        <div class="mt-1 leading-6 opacity-96 text-slate-800 wp_editor">
                            <?php echo wp_kses_post($size_details); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($client)): ?>
                    <div class="self-stretch mt-4 leading-none">
                        <div class="font-bold text-blue-900">Client</div>
                        <div class="mt-1 opacity-96 text-slate-800">
                            <?php echo esc_html($client); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($team_details)): ?>
                    <div class="mt-4">
                        <div class="font-bold leading-none text-blue-900">Team</div>
                        <div class="mt-1 leading-6 opacity-96 text-slate-800 wp_editor">
                            <?php echo wp_kses_post($team_details); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </article>

            <!-- Project Image Column -->
            <?php if ($project_image): ?>
            <div class="overflow-hidden self-stretch my-auto rounded-lg w-full max-w-[502px] max-md:max-w-full">
                <?php echo wp_get_attachment_image($project_image, 'full', false, [
                    'alt' => esc_attr($project_image_alt),
                    'class' => 'object-contain w-full h-auto',
                    'loading' => 'lazy'
                ]); ?>
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>
