<?php
/**
 * HTML sitemap content.
 *
 * @package matrix-starter
 */

if (! defined('ABSPATH')) {
    exit;
}

$sections = function_exists('matrix_starter_get_sitemap_sections')
    ? matrix_starter_get_sitemap_sections()
    : [];

if ($sections === []) {
    return;
}
?>
<section class="w-full bg-white" aria-label="<?php esc_attr_e('Sitemap', 'matrix-starter'); ?>">
    <div class="mx-auto w-full max-w-container px-5 pb-12 pt-4 max-xl:px-5 lg:pb-16">
        <div class="grid gap-x-12 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($sections as $section) : ?>
                <div>
                    <h2 class="mb-4 font-secondary text-lg font-bold leading-6 text-[#262262]">
                        <?php echo esc_html($section['title'] ?? ''); ?>
                    </h2>
                    <?php
                    if (! empty($section['links']) && is_array($section['links'])) {
                        matrix_starter_render_sitemap_links($section['links']);
                    }
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
