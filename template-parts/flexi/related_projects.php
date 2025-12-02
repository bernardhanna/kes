<?php
$heading = get_sub_field('heading');
$heading_tag = get_sub_field('heading_tag');
$selected_projects = get_sub_field('selected_projects');
$number_of_projects = get_sub_field('number_of_projects') ?: 3;
$background_color = get_sub_field('background_color') ?: '#f9fafb';

// Generate unique ID for this section
$section_id = 'related-projects-' . uniqid();

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

// Get projects to display
$projects_to_display = [];

if ($selected_projects && is_array($selected_projects)) {
    // Use manually selected projects
    $projects_to_display = $selected_projects;
} else {
    // Get related projects by category
    global $post;
    $current_post_id = $post->ID;

    // Get current post's project categories
    $current_categories = wp_get_post_terms($current_post_id, 'project_category', array('fields' => 'ids'));

    if (!empty($current_categories)) {
        $args = array(
            'post_type' => 'projects',
            'posts_per_page' => $number_of_projects,
            'post__not_in' => array($current_post_id),
            'tax_query' => array(
                array(
                    'taxonomy' => 'project_category',
                    'field' => 'term_id',
                    'terms' => $current_categories,
                ),
            ),
        );

        $related_projects = get_posts($args);
        $projects_to_display = $related_projects;
    }
}
?>

<section
    id="<?php echo esc_attr($section_id); ?>"
    class="relative flex overflow-hidden <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
    style="background-color: <?php echo esc_attr($background_color); ?>;"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
    <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5 overflow-hidden">

        <?php if (!empty($heading)): ?>
            <<?php echo esc_attr($heading_tag); ?>
                id="<?php echo esc_attr($section_id); ?>-heading"
                class="text-2xl font-bold text-violet-950 mb-8 text-center"
            >
                <?php echo esc_html($heading); ?>
            </<?php echo esc_attr($heading_tag); ?>>
        <?php endif; ?>

        <?php if (!empty($projects_to_display)): ?>
            <div class="w-full relative">
                <!-- Navigation Arrow Left -->
                <button
                    type="button"
                    class="absolute left-0 top-1/2 -translate-y-1/2 z-10 w-[58px] h-[58px] bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-violet-950 focus:ring-offset-2 btn slick-prev-custom"
                    aria-label="Previous projects"
                >
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <!-- Projects Slider -->
                <div class="related-projects-slider mx-16" role="region" aria-label="Related projects carousel">
                    <?php foreach ($projects_to_display as $project):
                        $project_id = is_object($project) ? $project->ID : $project;
                        $project_title = get_the_title($project_id);
                        $project_permalink = get_permalink($project_id);
                        $project_image = get_post_thumbnail_id($project_id);
                        $project_image_alt = get_post_meta($project_image, '_wp_attachment_image_alt', true) ?: $project_title;
                        $project_excerpt = get_the_excerpt($project_id);

                        // Get project location (assuming it's a custom field)
                        $project_location = get_field('location', $project_id) ?: 'Location not specified';

                        // Get project categories for the label
                        $project_categories = wp_get_post_terms($project_id, 'project_category');
                        $category_label = !empty($project_categories) ? $project_categories[0]->name : 'Heatload Testing';
                    ?>
                        <article class="px-3">
                            <div class="flex flex-col justify-center w-full">
                                <!-- Project Image with Label -->
                                <div class="overflow-hidden w-full text-sm font-medium leading-none rounded-lg text-violet-950">
                                    <div class="flex relative flex-col w-full aspect-[1.602]">
                                        <?php if ($project_image): ?>
                                            <?php echo wp_get_attachment_image($project_image, 'large', false, [
                                                'alt' => esc_attr($project_image_alt),
                                                'class' => 'object-cover absolute inset-0 size-full',
                                            ]); ?>
                                        <?php else: ?>
                                            <div class="absolute inset-0 bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500">No image available</span>
                                            </div>
                                        <?php endif; ?>

                                        <div class="flex relative gap-2.5 justify-center items-center pt-4 pr-7 pb-40 pl-4 w-full min-h-[196px] max-md:pr-5 max-md:pb-24">
                                            <div class="flex gap-2 justify-center items-center self-stretch px-3 py-1 my-auto bg-emerald-100 min-h-7 rounded-[100px]">
                                                <span class="self-stretch my-auto text-sm font-medium text-violet-950">
                                                    <?php echo esc_html($category_label); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Project Details -->
                                <div class="mt-4 w-full">
                                    <h3 class="text-lg font-bold leading-none text-violet-950 mb-1">
                                        <a
                                            href="<?php echo esc_url($project_permalink); ?>"
                                            class="hover:text-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-950 focus:ring-offset-2 rounded"
                                        >
                                            <?php echo esc_html($project_title); ?>
                                        </a>
                                    </h3>

                                    <div class="flex flex-col items-start w-full text-base leading-none text-slate-700">
                                        <div class="flex gap-3 justify-center items-center">
                                            <span class="text-slate-700">
                                                <?php echo esc_html($project_location); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- Navigation Arrow Right -->
                <button
                    type="button"
                    class="absolute right-0 top-1/2 -translate-y-1/2 z-10 w-[58px] h-[58px] bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-violet-950 focus:ring-offset-2 btn slick-next-custom"
                    aria-label="Next projects"
                >
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <!-- Initialize Slick Slider -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof jQuery !== 'undefined' && jQuery.fn.slick) {
                        jQuery('.related-projects-slider').slick({
                            dots: false,
                            infinite: true,
                            speed: 300,
                            slidesToShow: 3,
                            slidesToScroll: 1,
                            autoplay: false,
                            arrows: true,
                            prevArrow: '.slick-prev-custom',
                            nextArrow: '.slick-next-custom',
                            responsive: [
                                {
                                    breakpoint: 1024,
                                    settings: {
                                        slidesToShow: 2,
                                        slidesToScroll: 1
                                    }
                                },
                                {
                                    breakpoint: 640,
                                    settings: {
                                        slidesToShow: 1,
                                        slidesToScroll: 1
                                    }
                                }
                            ]
                        });
                    }
                });
            </script>

        <?php else: ?>
            <div class="text-center py-8">
                <p class="text-slate-700">No related projects found.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
