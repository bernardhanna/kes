<?php
$content_left = get_sub_field('content_left');
$content_right = get_sub_field('content_right');

$padding_classes = [];
if (have_rows('padding_settings')) {
    while (have_rows('padding_settings')) {
        the_row();
        $screen_size   = get_sub_field('screen_size');
        $padding_top   = get_sub_field('padding_top');
        $padding_bottom = get_sub_field('padding_bottom');

        $padding_classes[] = "{$screen_size}:pt-[{$padding_top}rem]";
        $padding_classes[] = "{$screen_size}:pb-[{$padding_bottom}rem]";
    }
}

$section_id = 'two-col-wysiwyg-' . uniqid();
?>

<section
    id="<?php echo esc_attr($section_id); ?>"
    class="relative flex overflow-hidden"
    role="region"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
    <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5 <?php echo esc_attr(implode(' ', $padding_classes)); ?>">

        <!-- Switched this to a 2-column grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 text-lg leading-6 text-slate-800 w-full">

            <?php if (!empty($content_left)): ?>
                <article class="my-auto text-slate-800 w-full wp_editor">
                    <?php echo wp_kses_post($content_left); ?>
                </article>
            <?php endif; ?>

            <?php if (!empty($content_right)): ?>
                <article class="my-auto text-slate-800 w-full wp_editor">
                    <?php echo wp_kses_post($content_right); ?>
                </article>
            <?php endif; ?>

        </div>
    </div>
</section>
