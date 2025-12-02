<?php
$content     = get_the_content();
$is_checkout = function_exists('is_checkout') && is_checkout();

$extra_class = (!empty(trim($content)) && !$is_checkout) ? '' : '';
?>
<article class="relative wp_editor" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content <?php echo esc_attr($extra_class); ?> mt-6 text-lg leading-6 text-slate-800 max-md:max-w-full">
        <?php the_content(); ?>
    </div>
</article>