<?php
/**
 * Index/archive title band markup.
 * Variables: $section_id, $tag, $heading, $intro, $bg_color, $accent, $aria_labelledby
 *
 * @package matrix-starter
 */

if (! isset($section_id)) {
    return;
}

$style = 'background-color:' . esc_attr($bg_color) . ';';
if (! empty($bg_image_url)) {
    $style .= 'background-image:url(' . esc_url($bg_image_url) . ');background-size:cover;background-position:center;';
}

$section_classes = trim('relative flex overflow-hidden ' . ($section_class ?? ''));
?>
<section
    id="<?php echo esc_attr($section_id); ?>"
    class="<?php echo esc_attr($section_classes); ?>"
    style="<?php echo esc_attr($style); ?>"
    role="region"
    <?php if ($aria_labelledby !== '') : ?>
        aria-labelledby="<?php echo esc_attr($aria_labelledby); ?>"
    <?php else : ?>
        aria-label="<?php echo esc_attr__('Archive', 'matrix-starter'); ?>"
    <?php endif; ?>
>
    <div class="flex flex-col items-center pt-5 pb-5 mx-auto w-full max-w-container max-xl:px-5">
        <div class="flex overflow-hidden justify-between items-center self-stretch px-24 pt-[7rem] pb-5 max-md:px-5">
            <div class="flex flex-col flex-1 justify-center self-stretch my-auto w-full shrink basis-0 min-w-60 max-md:max-w-full">
                <?php if ($heading !== '') : ?>
                    <div class="w-full text-4xl font-bold tracking-tighter leading-none text-primary max-md:max-w-full">
                        <<?php echo esc_attr($tag); ?> id="<?php echo esc_attr($section_id); ?>-heading" class="text-primary max-md:max-w-full">
                            <?php echo esc_html($heading); ?>
                        </<?php echo esc_attr($tag); ?>>
                        <div class="flex mt-1 w-8 min-h-1" style="background-color: <?php echo esc_attr($accent); ?>;" role="presentation" aria-hidden="true"></div>
                    </div>
                <?php endif; ?>

                <?php if ($intro !== '') : ?>
                    <div class="<?php echo esc_attr($heading !== '' ? 'mt-6 ' : ''); ?>text-lg leading-none text-slate-700 max-md:max-w-full wp_editor">
                        <?php echo wp_kses_post($intro); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
