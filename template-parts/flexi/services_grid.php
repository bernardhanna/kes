<?php
// ===== Pull fields (sub fields only) =====
$services         = get_sub_field('services');
$background_color = get_sub_field('background_color') ?: '#f9fafb';

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
    class="relative flex overflow-hidden"
    style="background-color: <?php echo esc_attr($background_color); ?>;"
    role="region"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
    <div class="flex flex-col items-center w-full mx-auto max-w-container <?php echo esc_attr(implode(' ', $padding_classes)); ?> max-lg:px-5">

        <?php if (!empty($services) && is_array($services)) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full">
                <?php foreach ($services as $index => $service) :
                    // --- Image (ACF return: id). Be robust if the field returns array/id.
                    $image_raw = isset($service['image']) ? $service['image'] : 0;
                    $image_id  = is_array($image_raw)
                        ? (isset($image_raw['ID']) ? (int)$image_raw['ID'] : 0)
                        : (int)$image_raw;

                    $title         = !empty($service['title']) ? $service['title'] : 'Service Title';
                    $title_tag     = !empty($service['title_tag']) ? $service['title_tag'] : 'h3';
                    $description   = !empty($service['description']) ? $service['description'] : 'Service description goes here.';
                    $link          = isset($service['link']) && is_array($service['link']) ? $service['link'] : null;
                    $underline     = !empty($service['underline_color']) ? $service['underline_color'] : '#06b6d4';
                    $width_choice  = isset($service['width']) ? $service['width'] : 'half';
                    $span_class    = ($width_choice === 'full') ? 'md:col-span-2' : '';

                    // Alt/title fallbacks
                    $image_alt   = $image_id ? (get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: $title ?: 'Service image') : '';
                    $image_title = $image_id ? (get_the_title($image_id) ?: $title ?: 'Service') : '';

                    $service_id = $section_id . '-service-' . ($index + 1);
                ?>
                    <article class="overflow-hidden bg-gray-100 <?php echo esc_attr($span_class); ?>">
                        <?php if (!empty($link['url'])) : ?>
                            <a
                                href="<?php echo esc_url($link['url']); ?>"
                                target="<?php echo esc_attr(!empty($link['target']) ? $link['target'] : '_self'); ?>"
                                class="flex overflow-hidden flex-wrap items-center w-full bg-white rounded-lg min-h-[400px] transition-all duration-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 btn"
                                aria-labelledby="<?php echo esc_attr($service_id); ?>-title"
                                aria-describedby="<?php echo esc_attr($service_id); ?>-description"
                            >
                        <?php else : ?>
                            <div class="flex overflow-hidden flex-wrap items-center w-full bg-white rounded-lg min-h-[400px]">
                        <?php endif; ?>

                                <?php if ($image_id) : ?>
                                    <div class="self-stretch pl-11 my-auto w-[167px]">
                                        <?php echo wp_get_attachment_image($image_id, 'medium', false, [
                                            'alt'    => esc_attr($image_alt),
                                            'title'  => esc_attr($image_title),
                                            // no aspect-* utilities
                                            'class'  => 'object-contain w-[123px] h-[123px]',
                                            'loading'=> 'lazy',
                                        ]); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="flex-1 shrink self-stretch px-16 my-auto basis-0 max-md:px-5">
                                    <header class="w-full">
                                        <<?php echo esc_attr($title_tag); ?>
                                            id="<?php echo esc_attr($service_id); ?>-title"
                                            class="text-xl font-bold leading-tight text-blue-900"
                                        >
                                            <?php echo esc_html($title); ?>
                                        </<?php echo esc_attr($title_tag); ?>>

                                        <div
                                            class="flex mt-1 w-8 min-h-1"
                                            style="background-color: <?php echo esc_attr($underline); ?>;"
                                            role="presentation"
                                            aria-hidden="true"
                                        ></div>
                                    </header>

                                    <div
                                        id="<?php echo esc_attr($service_id); ?>-description"
                                        class="mt-4 text-sm leading-5 text-slate-700 wp_editor"
                                    >
                                        <?php echo wp_kses_post($description); ?>
                                    </div>
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
            <!-- Empty state placeholders -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full">
                <?php for ($i = 1; $i <= 4; $i++) : ?>
                    <article class="overflow-hidden bg-gray-100">
                        <div class="flex overflow-hidden flex-wrap items-center w-full bg-white rounded-lg min-h-[400px]">
                            <div class="self-stretch pl-11 my-auto w-[167px]">
                                <div class="w-[123px] h-[123px] bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">Image</span>
                                </div>
                            </div>
                            <div class="flex-1 shrink self-stretch px-16 my-auto basis-0 max-md:px-5">
                                <header class="w-full">
                                    <h3 class="text-xl font-bold leading-tight text-blue-900">Service Title <?php echo (int)$i; ?></h3>
                                    <div class="flex mt-1 w-8 bg-cyan-500 min-h-1" role="presentation" aria-hidden="true"></div>
                                </header>
                                <div class="mt-4 text-sm leading-5 text-slate-700">
                                    <p>Service description goes here. This is placeholder content that demonstrates the layout and styling of the service card.</p>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
