<?php
/**
 * Index/archive title band markup.
 * Variables: $section_id, $tag, $heading, $intro, $bg_color, $accent, $aria_labelledby, $inner_wrapper_class, $toolbar_html
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

$toolbar_html    = $toolbar_html ?? '';
$overflow_class  = $toolbar_html !== '' ? 'overflow-visible' : 'overflow-hidden';
$section_classes = trim('relative flex ' . $overflow_class . ' ' . ($section_class ?? ''));
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
    <div class="<?php echo esc_attr($inner_wrapper_class ?? ''); ?>">
        <div class="flex flex-col gap-6 justify-between items-stretch self-stretch py-8 w-full max-w-container mx-auto max-xl:px-5 sm:flex-row sm:items-end">
            <div class="flex flex-col flex-1 justify-center min-w-0 max-w-[542px]">
                <?php if ($heading !== '') : ?>
                    <div class="flex flex-col gap-4 w-full max-md:max-w-full">
                        <<?php echo esc_attr($tag); ?> id="<?php echo esc_attr($section_id); ?>-heading" class="text-[36px] font-bold leading-[44px] tracking-[-0.72px] font-primary text-blue-500 max-md:max-w-full">
                            <?php echo esc_html($heading); ?>
                        </<?php echo esc_attr($tag); ?>>
                        <div class="w-8 h-1 relative -top-[10px]" style="background-color: <?php echo esc_attr($accent ?: '#00ACD8'); ?>;" role="presentation" aria-hidden="true"></div>
                    </div>
                <?php endif; ?>

                <?php if ($intro !== '') : ?>
                    <div class="<?php echo esc_attr($heading !== '' ? 'mt-6 ' : ''); ?>text-lg leading-6 text-[#1D2939] max-md:max-w-full wp_editor">
                        <?php echo wp_kses_post($intro); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (! empty($toolbar_html)) : ?>
                <div class="relative z-30 shrink-0 w-full overflow-visible sm:w-auto">
                    <?php echo $toolbar_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
