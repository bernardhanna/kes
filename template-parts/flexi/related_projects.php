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
    data-matrix-block="<?php echo esc_attr(str_replace('_', '-', get_row_layout()) . '-' . get_row_index()); ?>"
    class="relative flex flex-col overflow-hidden <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
<div class="flex flex-col items-start w-full max-w-[1084px] mx-auto mb-8 max-xl:px-5">
        <?php if (!empty($heading)): ?>
            <<?php echo esc_attr($heading_tag); ?>
                id="<?php echo esc_attr($section_id); ?>-heading"
                class="text-primary font-primary text-[30px] font-bold leading-[38px]"
            >
                <?php echo esc_html($heading); ?>
            </<?php echo esc_attr($heading_tag); ?>>
        <?php endif; ?>
        <div class="w-8 h-1 bg-cyan-500" role="presentation" aria-hidden="true"></div>
    </div>
<div class="py-12 w-full" style="background-color: <?php echo esc_attr($background_color); ?>;">
    <div class="flex  flex-col items-center pt-5 pb-5 mx-auto w-full max-w-[1084px] max-xl:px-5">


        <?php if (!empty($projects_to_display)): ?>
            <div class="relative w-full">
                <!-- Navigation Arrow Left -->
                <button
                    type="button"
                    class="absolute left-0 top-1/2 -translate-y-1/2 z-10 w-[58px] h-[58px] rounded-full bg-transparent hover:shadow-lg flex items-center justify-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-text-primary focus:ring-offset-2 btn slick-prev-custom"
                    aria-label="Previous projects"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="31" viewBox="0 0 17 31" fill="none">
                    <path d="M15.5 30L1 15.5L15.5 1" stroke="#2B3990" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <!-- Projects Slider -->
                <div class="overflow-hidden mx-16 related-projects-slider" role="region" aria-label="Related projects carousel">
                    <?php foreach ($projects_to_display as $project):
                        $project_id = is_object($project) ? $project->ID : $project;
                        $project_title = get_the_title($project_id);
                        $project_permalink = get_permalink($project_id);
                        $project_image_id = get_post_thumbnail_id($project_id);
                        $project_image_alt = $project_image_id ? get_post_meta($project_image_id, '_wp_attachment_image_alt', true) : '';
                        $project_image_alt = $project_image_alt ?: $project_title;
                        $project_image_url = get_the_post_thumbnail_url($project_id, 'large');

                        $project_location = get_field('project_location', $project_id);
                        $project_location = is_string($project_location) ? trim($project_location) : '';

                        $project_categories = wp_get_post_terms($project_id, 'project_category');
                        $category_label = '';
                        if (!empty($project_categories) && !is_wp_error($project_categories)) {
                            $sorted_categories = $project_categories;
                            usort($sorted_categories, static function ($a, $b) {
                                return (int) $a->term_id <=> (int) $b->term_id;
                            });
                            $category_label = $sorted_categories[0]->name;
                        }
                    ?>
                        <article class="px-3">
                            <a
                                href="<?php echo esc_url($project_permalink); ?>"
                                class="block w-full group"
                                aria-label="<?php echo esc_attr(sprintf(__('View project: %s', 'matrix-starter'), $project_title)); ?>"
                            >
                                <div class="flex flex-col gap-4 w-full text-left">
                                    <div class="overflow-hidden relative w-full h-48 bg-gradient-to-r rounded-lg from-slate-600 to-slate-700">
                                        <?php if ($project_image_url): ?>
                                            <img
                                                src="<?php echo esc_url($project_image_url); ?>"
                                                alt="<?php echo esc_attr($project_image_alt); ?>"
                                                class="object-cover w-full h-full transition-transform duration-300 ease-in-out transform group-hover:scale-105"
                                            />
                                        <?php endif; ?>

                                        <div
                                            class="pointer-events-none absolute inset-0 z-[1]"
                                            style="background: linear-gradient(90deg, rgba(43, 57, 144, 0.30) 0%, rgba(0, 110, 200, 0.30) 100%);"
                                            aria-hidden="true"
                                        ></div>

                                        <?php if ($category_label !== '') : ?>
                                            <div
                                                class="pointer-events-none absolute left-4 top-4 z-10 flex h-7 min-h-7 max-w-[calc(100%-2rem)] items-center justify-center rounded-full border border-solid border-[#2B3990] bg-white px-3 font-secondary text-sm font-medium leading-5 text-[#262262] transition-[background-color,border-color,color] duration-300 ease-out group-hover:border-[#00ACD8] group-hover:bg-[#00ACD8] group-hover:text-[#262262]"
                                                aria-hidden="true"
                                            >
                                                <span class="truncate"><?php echo esc_html($category_label); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex flex-col gap-1">
                                        <h3 class="text-[#262262] font-secondary text-[18px] font-bold leading-6">
                                            <?php echo esc_html($project_title); ?>
                                        </h3>
                                        <?php if ($project_location !== '') : ?>
                                            <p class="text-[#344054] font-secondary text-base font-normal leading-5">
                                                <?php echo esc_html($project_location); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- Navigation Arrow Right -->
                <button
                    type="button"
                    class="absolute right-0 top-1/2 -translate-y-1/2 z-10 w-[58px] h-[58px] bg-white rounded-full hover:shadow-lg flex items-center justify-center bg-transparent  hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-text-primary focus:ring-offset-2 btn slick-next-custom"
                    aria-label="Next projects"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="31" viewBox="0 0 17 31" fill="none">
                    <path d="M1 30L15.5 15.5L1 1" stroke="#2B3990" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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
            <div class="py-8 text-center">
                <p class="text-slate-700">No related projects found.</p>
            </div>
        <?php endif; ?>
    </div>
    </div>
</section>
