<?php
// ===== Pull fields (sub fields only) =====
$background_color = get_sub_field('background_color') ?: '#f9fafb';
$services_items   = function_exists('matrix_starter_services_grid_resolve_items')
    ? matrix_starter_services_grid_resolve_items()
    : [];

// Padding settings → classes
$padding_classes = ['pt-5', 'pb-5'];
if (have_rows('padding_settings')) {
    while (have_rows('padding_settings')) {
        the_row();
        $screen_size    = get_sub_field('screen_size');
        $padding_top    = get_sub_field('padding_top');
        $padding_bottom = get_sub_field('padding_bottom');

        if ($screen_size !== null && $padding_top !== '' && $padding_bottom !== '') {
            $padding_classes[] = "{$screen_size}:pt-[{$padding_top}rem]";
            $padding_classes[] = "{$screen_size}:pb-[{$padding_bottom}rem]";
        }
    }
}

// Unique section id
$section_id = 'services-grid-' . wp_generate_uuid4();
?>

<section
    id="<?php echo esc_attr($section_id); ?>"
    data-matrix-block="<?php echo esc_attr(str_replace('_', '-', get_row_layout()) . '-' . get_row_index()); ?>"
    class="flex overflow-hidden relative"
    style="background-color: <?php echo esc_attr($background_color); ?>;"
    role="region"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
    <div class="flex flex-col items-center w-full mx-auto py-10 lg:py-20 max-w-container <?php echo esc_attr(implode(' ', $padding_classes)); ?> max-xl:px-5">

        <?php if (! empty($services_items)) : ?>
            <div class="grid grid-cols-1 gap-8 w-full md:grid-cols-2">
                <?php foreach ($services_items as $index => $service) :
                    $image_id    = (int) ($service['image_id'] ?? 0);
                    $title       = $service['title'] ?? __('Service Title', 'matrix-starter');
                    $title_tag   = $service['title_tag'] ?? 'h3';
                    $description = $service['description'] ?? '';
                    $link        = isset($service['link']) && is_array($service['link']) ? $service['link'] : null;
                    $underline   = $service['underline'] ?? '#00ACD8';
                    $width_choice = $service['width'] ?? 'half';
                    $span_class  = ($width_choice === 'full') ? 'md:col-span-2' : '';

                    $image_alt   = $image_id ? (get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: $title ?: 'Service image') : '';
                    $image_title = $image_id ? (get_the_title($image_id) ?: $title ?: 'Service') : '';

                    $service_id = $section_id . '-service-' . ($index + 1);
                ?>
                    <article class="overflow-hidden bg-[#F2F4F7] <?php echo esc_attr($span_class); ?>">
                        <?php
                        $card_classes = 'flex items-center w-full h-[300px] overflow-hidden bg-white rounded-lg max-md:h-auto max-md:flex-col max-md:py-6';
                        if (! empty($link['url'])) {
                            $card_classes .= ' transition-all duration-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 btn';
                        }
                        ?>
                        <?php if (!empty($link['url'])) : ?>
                            <a
                                href="<?php echo esc_url($link['url']); ?>"
                                target="<?php echo esc_attr(!empty($link['target']) ? $link['target'] : '_self'); ?>"
                                class="<?php echo esc_attr($card_classes); ?>"
                                aria-labelledby="<?php echo esc_attr($service_id); ?>-title"
                                aria-describedby="<?php echo esc_attr($service_id); ?>-description"
                            >
                        <?php else : ?>
                            <div class="<?php echo esc_attr($card_classes); ?>">
                        <?php endif; ?>

                                <?php if ($image_id) : ?>
                                    <div class="flex shrink-0 justify-center items-center w-[244px] h-[244px] max-md:w-[135px] max-md:h-[135px]">
                                        <?php echo wp_get_attachment_image($image_id, 'medium', false, [
                                            'alt'     => esc_attr($image_alt),
                                            'title'   => esc_attr($image_title),
                                            'class'   => 'object-contain w-full h-full max-w-[244px] max-h-[244px] max-md:max-w-[135px] max-md:max-h-[135px]',
                                            'loading' => 'lazy',
                                        ]); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="flex flex-col flex-1 gap-4 justify-center items-start min-w-0 px-11 max-md:px-5 max-md:w-full">
                                    <header class="flex flex-col gap-1 items-start w-full">
                                        <<?php echo esc_attr($title_tag); ?>
                                            id="<?php echo esc_attr($service_id); ?>-title"
                                            class="text-[#2B3990] font-bold text-xl leading-[26px] font-secondary break-words"
                                        >
                                            <?php echo esc_html($title); ?>
                                        </<?php echo esc_attr($title_tag); ?>>

                                        <div
                                            class="h-1 w-8 shrink-0"
                                            style="background-color: <?php echo esc_attr($underline); ?>;"
                                            role="presentation"
                                            aria-hidden="true"
                                        ></div>
                                    </header>

                                    <?php if ($description !== '') : ?>
                                        <div
                                            id="<?php echo esc_attr($service_id); ?>-description"
                                            class="w-full text-sm font-normal leading-5 text-[#344054] [&_p]:m-0 [&_p+p]:mt-3.5"
                                        >
                                            <?php echo wp_kses_post($description); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                        <?php if (!empty($link['url'])) : ?>
                            </a>
                        <?php else : ?>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="text-sm text-[#344054]"><?php esc_html_e('No services to display.', 'matrix-starter'); ?></p>
        <?php endif; ?>

    </div>
</section>
