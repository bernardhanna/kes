<?php
$testimonials = get_sub_field('testimonials');
$background_color = get_sub_field('background_color');
$background_gradient = get_sub_field('background_gradient');
$use_gradient = get_sub_field('use_gradient');

// Generate unique ID for slider
$slider_id = uniqid('testimonial-slider-');
$is_slider = $testimonials && count($testimonials) > 1;

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

// Background style
$background_style = '';
if ($use_gradient && $background_gradient) {
    $background_style = "background: {$background_gradient};";
} elseif ($background_color) {
    $background_style = "background-color: {$background_color};";
}
?>

<section
    class="relative flex overflow-hidden <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
    style="<?php echo esc_attr($background_style); ?>"
    role="region"
    aria-label="Customer Testimonials"
    id="<?php echo esc_attr($slider_id); ?>-kudos"
>
    <div class="flex flex-col items-center w-full mx-auto max-w-container pt-20 pb-20 max-lg:px-5">

        <?php if ($testimonials): ?>
            <div
                id="<?php echo esc_attr($slider_id); ?>"
                class="flex w-full <?php echo $is_slider ? 'slick-slider' : ''; ?>"
            >
                <?php foreach ($testimonials as $index => $testimonial):
                    $quote = $testimonial['quote'];
                    $author = $testimonial['author'];
                    $profile_image = $testimonial['profile_image'];
                    $show_quote_icon = $testimonial['show_quote_icon'];

                    // Get image alt text
                    $image_alt = '';
                    if ($profile_image) {
                        $image_alt = get_post_meta($profile_image, '_wp_attachment_image_alt', true);
                        if (empty($image_alt)) {
                            $image_alt = $author ? esc_attr($author) : 'Testimonial author';
                        }
                    }
                ?>
                    <article class="flex relative gap-10 items-center w-full py-20 pr-32 pl-48 text-white max-md:px-5 max-sm:flex-col max-sm:p-6 slick-item">

                        <?php if ($show_quote_icon): ?>
                            <div class="absolute top-24 left-[70px] z-0 max-md:hidden max-sm:hidden" aria-hidden="true">
                                <svg
                                    width="107"
                                    height="66"
                                    viewBox="0 0 107 66"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-[107px] h-[66px] fill-white"
                                >
                                    <path d="M38.9091 66H0L28.3931 0H53.8943L38.9091 66ZM92.0147 66H53.1056L81.4988 0H107L92.0147 66Z" fill="currentColor"/>
                                </svg>
                            </div>
                        <?php endif; ?>

                        <div class="flex flex-col flex-1 justify-center pt-6 my-auto max-md:max-w-full max-sm:pb-8">

                            <?php if ($quote): ?>
                                <blockquote class="text-3xl font-bold leading-10 text-white max-md:max-w-full wp_editor">
                                    <?php echo wp_kses_post($quote); ?>
                                </blockquote>
                            <?php endif; ?>

                            <?php if ($author): ?>
                                <cite class="mt-6 text-xl font-medium leading-7 text-white max-md:max-w-full not-italic">
                                    <?php echo esc_html($author); ?>
                                </cite>
                            <?php endif; ?>

                        </div>

                        <?php if ($profile_image): ?>
                            <div class="flex-shrink-0 my-auto max-sm:w-full">
                                <?php echo wp_get_attachment_image(
                                    $profile_image,
                                    'full',
                                    false,
                                    [
                                        'alt' => esc_attr($image_alt),
                                        'class' => 'object-cover rounded-lg w-[362px] h-auto max-sm:w-full',
                                        'loading' => $index === 0 ? 'eager' : 'lazy'
                                    ]
                                ); ?>
                            </div>
                        <?php endif; ?>

                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php if ($is_slider): ?>
<script>
jQuery(document).ready(function($) {
    $('#<?php echo esc_js($slider_id); ?>').slick({
        dots: true,
        arrows: false,
        autoplay: true,
        autoplaySpeed: 5000,
        adaptiveHeight: true,
        fade: true,
        cssEase: 'linear',
        accessibility: true,
        focusOnSelect: false,
        pauseOnHover: true,
        pauseOnFocus: true,
        customPaging: function(slider, i) {
            return '<button class="w-3 h-3 rounded-full dot bg-secondary btn" aria-label="Go to testimonial ' + (i + 1) + '"></button>';
        },
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    adaptiveHeight: true,
                    fade: false,
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    // Pause autoplay when user interacts with slider
    $('#<?php echo esc_js($slider_id); ?>').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
        // Announce slide change to screen readers
        var announcement = 'Showing testimonial ' + (nextSlide + 1) + ' of ' + slick.slideCount;
        $('<div>', {
            'aria-live': 'polite',
            'aria-atomic': 'true',
            'class': 'sr-only'
        }).text(announcement).appendTo('body').delay(1000).remove();
    });
});
</script>

<style>
#<?php echo esc_attr($slider_id); ?>-kudos .slick-dots {
    display: flex !important;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
    list-style: none;
    padding: 0;
}

#<?php echo esc_attr($slider_id); ?>-kudos  .slick-dots li {
    margin: 0;
}

#<?php echo esc_attr($slider_id); ?>-kudos  .slick-dots button {
    border: none;
    background: rgba(255, 255, 255, 0.5);
    transition: all 0.3s ease;
}

#<?php echo esc_attr($slider_id); ?>-kudos  .slick-dots .slick-active button {
    background: rgba(255, 255, 255, 1);
    transform: scale(1.2);
}

#<?php echo esc_attr($slider_id); ?>-kudos  .sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}
</style>
<?php endif; ?>
