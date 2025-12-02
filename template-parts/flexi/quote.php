<?php
$quote_text = get_sub_field('quote_text');
$background_color = get_sub_field('background_color');
$text_color = get_sub_field('text_color');

$padding_classes = [];
if (have_rows('padding_settings')) {
    while (have_rows('padding_settings')) {
        the_row();
        $screen_size = get_sub_field('screen_size');
        $padding_top = get_sub_field('padding_top');
        $padding_bottom = get_sub_field('padding_bottom');
        $padding_left = get_sub_field('padding_left');
        $padding_right = get_sub_field('padding_right');

        if ($padding_top) {
            $padding_classes[] = "{$screen_size}:pt-[{$padding_top}rem]";
        }
        if ($padding_bottom) {
            $padding_classes[] = "{$screen_size}:pb-[{$padding_bottom}rem]";
        }
        if ($padding_left) {
            $padding_classes[] = "{$screen_size}:pl-[{$padding_left}rem]";
        }
        if ($padding_right) {
            $padding_classes[] = "{$screen_size}:pr-[{$padding_right}rem]";
        }
    }
}

$section_id = 'quote-' . uniqid();
?>

<section
    id="<?php echo esc_attr($section_id); ?>"
    class="relative flex overflow-hidden <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
    style="background: <?php echo esc_attr($background_color); ?>;"
    role="region"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
    <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5">
        <div class="flex gap-2 justify-center items-center self-stretch py-20 pr-32 pl-48 text-lg leading-6 max-md:px-5 w-full">
            <div class="flex flex-col flex-1 shrink justify-center self-stretch my-auto w-full basis-0 min-w-60 max-md:max-w-full">
                <?php if (!empty($quote_text)): ?>
                    <blockquote
                        class="wp_editor max-md:max-w-full"
                        style="color: <?php echo esc_attr($text_color); ?>;"
                        role="blockquote"
                        aria-label="Quote content"
                    >
                        <?php echo wp_kses_post($quote_text); ?>
                    </blockquote>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
