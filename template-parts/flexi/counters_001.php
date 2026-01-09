<?php
// Get ACF fields
$heading = get_sub_field('heading');
$heading_tag = get_sub_field('heading_tag') ?: 'h2';
$counter_items = get_sub_field('counter_items');
$background_color = get_sub_field('background_color') ?: '#ffffff';

// Padding classes
$padding_classes = [];
if (have_rows('padding_settings')) {
    while (have_rows('padding_settings')) {
        the_row();
        $screen_size = get_sub_field('screen_size');
        $padding_top = get_sub_field('padding_top');
        $padding_bottom = get_sub_field('padding_bottom');
        $padding_classes[] = "{$screen_size}:pt-[{$padding_top}rem]";
        $padding_classes[] = "{$screen_size}:pb-[{$padding_bottom}rem]";
    }
}

// Generate unique section ID
$section_id = 'counters-' . uniqid();
?>

<section
    class="relative flex overflow-hidden <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
    style="background-color: <?php echo esc_attr($background_color); ?>;"
    id="<?php echo esc_attr($section_id); ?>"
>
    <div class="flex flex-col items-center w-full mx-auto max-w-[1078px] pt-9 pb-2.5 pl-8 max-lg:px-5">

        <?php if (!empty($heading)): ?>
            <header class="hidden mb-8 w-full">
                <<?php echo esc_attr($heading_tag); ?> class="font-bold text-h2 text-text-primary">
                    <?php echo esc_html($heading); ?>
                </<?php echo esc_attr($heading_tag); ?>>
            </header>
        <?php endif; ?>

        <?php if ($counter_items): ?>
            <div
                class="grid grid-cols-1 gap-5 w-full md:grid-cols-3 max-md:space-y-10"
                x-data="counterAnimation"
                x-init="initCounters"
            >
                <?php foreach ($counter_items as $index => $item):
                    $counter_number = $item['counter_number'] ?: '0';
                    $counter_suffix = $item['counter_suffix'] ?: '';
                    $description = $item['description'] ?: '';
                    $icon = $item['icon'];
                    $icon_alt = get_post_meta($icon, '_wp_attachment_image_alt', true) ?: 'Counter icon';

                    // Extract numeric value for animation
                    $numeric_value = preg_replace('/[^0-9]/', '', $counter_number);
                    $counter_id = "counter-{$index}";
                ?>
                    <article
                        class="flex  items-start min-h-[82px]"
                        x-intersect.once="startCounter('<?php echo esc_attr($counter_id); ?>', <?php echo esc_attr($numeric_value); ?>)"
                    >
                        <div
                            class="flex flex-col justify-center items-center px-6 rounded"
                            role="img"
                            aria-label="<?php echo esc_attr($icon_alt); ?>"
                        >
                            <?php if ($icon): ?>
                                <?php echo wp_get_attachment_image($icon, 'full', false, [
                                    'alt' => esc_attr($icon_alt),
                                    'class' => 'object-contain w-[82px] h-[82px]',
                                ]); ?>
                            <?php else: ?>
                                <div class="w-8 h-8 rounded bg-neutral-300" aria-hidden="true"></div>
                            <?php endif; ?>
                        </div>

                        <div class="flex-1 min-h-14 text-slate-700">
                            <div class="flex flex-col items-start w-full font-secondary">
                                <span
                                    class="tabular-nums text-[18px] font-[500] leading-[24px] text-[#344054]"
                                    x-text="counters['<?php echo esc_attr($counter_id); ?>'] + '<?php echo esc_attr($counter_suffix); ?>'"
                                    aria-live="polite"
                                    aria-label="Counter value"
                                >
                                    0<?php echo esc_html($counter_suffix); ?>
                                </span>
                            </div>

                            <?php if (!empty($description)): ?>
                                <div class="flex flex-col justify-center py-0.5 mt-2 w-full text-secondary">
                                    <p class="m-0 text-[16px] font-normal leading-[24px] text-[#344054]">
                                        <?php echo esc_html($description); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('counterAnimation', () => ({
        counters: {},
        animationDuration: 2000, // 2 seconds

        initCounters() {
            // Initialize all counters to 0
            <?php if ($counter_items): ?>
                <?php foreach ($counter_items as $index => $item): ?>
                    this.counters['counter-<?php echo esc_js($index); ?>'] = 0;
                <?php endforeach; ?>
            <?php endif; ?>
        },

        startCounter(counterId, targetValue) {
            if (this.counters[counterId] !== 0) return; // Already animated

            const startTime = Date.now();
            const startValue = 0;
            const duration = this.animationDuration;

            const animate = () => {
                const elapsed = Date.now() - startTime;
                const progress = Math.min(elapsed / duration, 1);

                // Easing function for smooth animation
                const easeOutQuart = 1 - Math.pow(1 - progress, 4);

                this.counters[counterId] = Math.floor(startValue + (targetValue - startValue) * easeOutQuart);

                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    this.counters[counterId] = targetValue;
                }
            };

            requestAnimationFrame(animate);
        }
    }));
});
</script>
