<?php
get_header();
$enable_breadcrumbs = get_field('enable_breadcrumbs', 'option'); // Returns true/false
?>
<main class="overflow-hidden w-full site-main">
 <?php
    // Read the group from Options
    $breadcrumbs_settings = get_field('breadcrumbs_settings', 'option');
    $enable_breadcrumbs   = !empty($breadcrumbs_settings['enable_breadcrumbs']);

    // Show everywhere except the front/home page
    if ( $enable_breadcrumbs && !is_front_page() && !is_home() ) {
        get_template_part('template-parts/header/breadcrumbs');
    }
    ?>

    <?php load_hero_templates(); ?>



    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            if (trim(get_the_content()) != '') : ?>
                <div class="max-w-[1095px] mx-auto <?php echo (function_exists('is_checkout') && is_checkout()) ? '' : ' max-md:px-5'; ?>">
                    <?php
                    get_template_part('template-parts/content/content', 'page');
                    ?>
                </div>
    <?php endif;
        endwhile;
    else :
        echo '<p>No content found</p>';
    endif;
    ?>

    <?php load_flexible_content_templates(); ?>
</main>

<?php
get_footer();
?>