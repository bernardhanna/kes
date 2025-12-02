<?php
$team_members = get_sub_field('team_members');
$button = get_sub_field('button');
$background_color = get_sub_field('background_color') ?: '#ffffff';

$padding_classes = [];
if (have_rows('padding_settings')) {
    while (have_rows('padding_settings')) {
        the_row();
        $screen_size = get_sub_field('screen_size');
        $padding_top = get_sub_field('padding_top');
        $padding_bottom = get_sub_field('padding_bottom');
        if ($screen_size !== '' && $padding_top !== '' && $padding_top !== null) {
            $padding_classes[] = "{$screen_size}:pt-[{$padding_top}rem]";
        }
        if ($screen_size !== '' && $padding_bottom !== '' && $padding_bottom !== null) {
            $padding_classes[] = "{$screen_size}:pb-[{$padding_bottom}rem]";
        }
    }
}

$section_id = 'team-' . uniqid();
?>

<section
    id="<?php echo esc_attr($section_id); ?>"
    class="relative flex overflow-hidden <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
    style="background-color: <?php echo esc_attr($background_color); ?>;"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
    <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5">
        <div class="box-border flex flex-col gap-8 items-center px-24 pt-0 pb-20 mx-auto my-0 w-full max-w-screen-xl max-md:px-12 max-md:pt-0 max-md:pb-16 max-sm:px-6 max-sm:pt-0 max-sm:pb-10">

            <?php if ($team_members && is_array($team_members)): ?>
                <!-- 3-column responsive grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 max-md:gap-8 max-sm:gap-6 w-full">
                    <?php foreach ($team_members as $member):
                        $image = $member['image'] ?? 0;
                        $name = $member['name'] ?? '';
                        $job_title = $member['job_title'] ?? '';
                        $description = $member['description'] ?? '';
                        $image_alt = '';

                        if ($image) {
                            $fallback_alt = trim($name . ' - ' . $job_title);
                            $image_alt = get_post_meta($image, '_wp_attachment_image_alt', true) ?: $fallback_alt;
                        }
                    ?>
                        <article class="flex flex-col gap-4 justify-start items-start">
                            <?php if ($image): ?>
                                <div class="w-full">
                                    <?php echo wp_get_attachment_image($image, 'large', false, [
                                        'alt'   => esc_attr($image_alt),
                                        'class' => 'object-cover w-full h-80 rounded-lg max-md:h-[280px] max-sm:h-60',
                                    ]); ?>
                                </div>
                            <?php endif; ?>

                            <div class="flex flex-col gap-2 items-start w-full max-sm:gap-1.5">
                                <div class="flex flex-col gap-2 items-start w-full max-sm:gap-1">
                                    <?php if ($name): ?>
                                        <h3 class="w-full text-lg font-bold leading-6 text-violet-950 max-md:text-base max-md:leading-6">
                                            <?php echo esc_html($name); ?>
                                        </h3>
                                    <?php endif; ?>

                                    <?php if ($job_title): ?>
                                        <p class="w-full text-base font-medium leading-6 text-slate-800 max-md:text-sm max-md:leading-5">
                                            <?php echo esc_html($job_title); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <?php if ($description): ?>
                                    <div class="w-full text-base leading-5 text-slate-700 max-md:text-sm max-md:leading-5 wp_editor">
                                        <?php echo wp_kses_post($description); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($button && is_array($button) && isset($button['url'], $button['title'])): ?>
                <div class="flex gap-2 justify-center items-center">
                    <a
                        href="<?php echo esc_url($button['url']); ?>"
                        class="flex gap-2 justify-center items-center px-6 py-4 cursor-pointer h-[52px] rounded-[100px] max-md:px-5 max-md:py-3.5 max-md:h-12 max-sm:px-5 max-sm:py-3 max-sm:h-11 w-fit whitespace-nowrap btn bg-primary hover:bg-primary-dark focus:bg-primary-dark transition-colors duration-300"
                        target="<?php echo esc_attr($button['target'] ?? '_self'); ?>"
                        aria-label="<?php echo esc_attr($button['title']); ?>"
                    >
                        <span class="text-lg font-medium leading-6 text-white max-md:text-base max-md:leading-6">
                            <?php echo esc_html($button['title']); ?>
                        </span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
