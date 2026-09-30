<?php
/*
Template Name: Sitemap Page
*/

get_header();

$breadcrumbs_settings = get_field('breadcrumbs_settings', 'option');
$enable_breadcrumbs   = ! empty($breadcrumbs_settings['enable_breadcrumbs']);
?>
<main <?php echo matrix_starter_main_id_attr(); ?> class="overflow-hidden w-full min-h-screen site-main">
    <?php
    if ($enable_breadcrumbs && ! is_front_page() && ! is_home()) {
        get_template_part('template-parts/header/breadcrumbs');
    }

    if (have_posts()) :
        while (have_posts()) :
            the_post();

            if (function_exists('matrix_starter_render_archive_index_header')) {
                matrix_starter_render_archive_index_header([
                    'heading'             => get_the_title(),
                    'heading_tag'         => 'h1',
                    'intro'               => '',
                    'bg_color'            => '#FFFFFF',
                    'accent_color'        => '#00ACD8',
                    'inner_wrapper_class' => 'flex flex-col items-center pt-8 pb-5 mx-auto w-full max-w-container px-5',
                ]);
            }

            get_template_part('template-parts/sitemap/content');
        endwhile;
    endif;
    ?>
</main>
<?php
get_footer();
