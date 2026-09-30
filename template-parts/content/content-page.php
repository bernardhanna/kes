<?php
$content     = get_the_content();
$is_checkout = function_exists('is_checkout') && is_checkout();

if (empty(trim($content)) || $is_checkout) {
    return;
}
?>
<article
    class="relative pb-12 matrix-page-content"
    id="post-<?php the_ID(); ?>"
    <?php post_class(); ?>
>
    <div class="entry-content matrix-page-content__body mt-6 max-md:max-w-full">
        <?php the_content(); ?>
    </div>
</article>
