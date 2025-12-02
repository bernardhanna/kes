<?php
$gallery_images = get_sub_field('gallery_images');
$section_id = 'gallery_' . uniqid();

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
?>

<section id="<?php echo esc_attr($section_id); ?>" class="relative flex overflow-hidden">
    <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5 <?php echo esc_attr(implode(' ', $padding_classes)); ?>">

        <?php if ($gallery_images && is_array($gallery_images)): ?>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full justify-center" role="region" aria-label="Image gallery">

                <!-- Left Column - Two stacked images -->
                <div class="flex flex-col gap-6 min-w-0">
                    <?php
                    $left_images = array_slice($gallery_images, 0, 2);
                    foreach ($left_images as $index => $image_data):
                        $image_id = $image_data['image'];
                        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: 'Gallery image ' . ($index + 1);
                        $image_url = wp_get_attachment_image_url($image_id, 'full');
                    ?>
                        <div class="w-full rounded-lg overflow-hidden">
                            <button
                                type="button"
                                class="w-full h-auto block btn focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-lg"
                                onclick="openModal(<?php echo esc_attr($index); ?>)"
                                aria-label="Open image <?php echo esc_attr($index + 1); ?> in modal view"
                            >
                                <?php echo wp_get_attachment_image($image_id, 'large', false, [
                                    'alt' => esc_attr($image_alt),
                                    'class' => 'object-cover w-full h-auto rounded-lg transition-transform duration-300 hover:scale-105',
                                    'style' => 'aspect-ratio: 1.91;'
                                ]); ?>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Right Column - Single tall image -->
                <?php if (isset($gallery_images[2])):
                    $image_id = $gallery_images[2]['image'];
                    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: 'Gallery image 3';
                    $image_url = wp_get_attachment_image_url($image_id, 'full');
                ?>
                    <div class="w-full rounded-lg overflow-hidden min-w-0">
                        <button
                            type="button"
                            class="w-full h-full block btn focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-lg"
                            onclick="openModal(2)"
                            aria-label="Open image 3 in modal view"
                        >
                            <?php echo wp_get_attachment_image($image_id, 'large', false, [
                                'alt' => esc_attr($image_alt),
                                'class' => 'object-cover w-full h-full rounded-lg transition-transform duration-300 hover:scale-105',
                                'style' => 'aspect-ratio: 0.91;'
                            ]); ?>
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Modal -->
            <div
                id="gallery-modal"
                class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4"
                role="dialog"
                aria-modal="true"
                aria-labelledby="modal-title"
                aria-describedby="modal-description"
            >
                <div class="relative max-w-4xl max-h-full w-full h-full flex items-center justify-center">
                    <!-- Close button -->
                    <button
                        type="button"
                        id="close-modal"
                        class="absolute top-4 right-4 z-10 bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-2 rounded-full transition-colors duration-200 btn focus:outline-none focus:ring-2 focus:ring-white"
                        aria-label="Close modal"
                    >
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <!-- Previous button -->
                    <button
                        type="button"
                        id="prev-image"
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-3 rounded-full transition-colors duration-200 btn focus:outline-none focus:ring-2 focus:ring-white"
                        aria-label="Previous image"
                    >
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <!-- Next button -->
                    <button
                        type="button"
                        id="next-image"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-3 rounded-full transition-colors duration-200 btn focus:outline-none focus:ring-2 focus:ring-white"
                        aria-label="Next image"
                    >
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <!-- Modal image -->
                    <img
                        id="modal-image"
                        src=""
                        alt=""
                        class="max-w-full max-h-full object-contain rounded-lg"
                    />

                    <!-- Image counter -->
                    <div
                        id="image-counter"
                        class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm"
                        aria-live="polite"
                    >
                        <span id="current-image">1</span> / <span id="total-images"><?php echo count($gallery_images); ?></span>
                    </div>

                    <!-- Hidden title for screen readers -->
                    <h2 id="modal-title" class="sr-only">Gallery image viewer</h2>
                    <p id="modal-description" class="sr-only">Use arrow keys or navigation buttons to browse images. Press Escape to close.</p>
                </div>
            </div>

            <script>
            (function() {
                const galleryImages = <?php echo json_encode(array_map(function($image_data) {
                    $image_id = $image_data['image'];
                    return [
                        'url' => wp_get_attachment_image_url($image_id, 'full'),
                        'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: 'Gallery image'
                    ];
                }, $gallery_images)); ?>;

                let currentImageIndex = 0;
                const modal = document.getElementById('gallery-modal');
                const modalImage = document.getElementById('modal-image');
                const currentImageSpan = document.getElementById('current-image');
                const totalImagesSpan = document.getElementById('total-images');
                const closeBtn = document.getElementById('close-modal');
                const prevBtn = document.getElementById('prev-image');
                const nextBtn = document.getElementById('next-image');

                // Store the element that opened the modal for focus restoration
                let modalTrigger = null;

                window.openModal = function(index) {
                    modalTrigger = document.activeElement;
                    currentImageIndex = index;
                    showImage();
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.style.overflow = 'hidden';

                    // Focus the close button for keyboard users
                    closeBtn.focus();

                    // Announce to screen readers
                    const announcement = document.createElement('div');
                    announcement.setAttribute('aria-live', 'assertive');
                    announcement.setAttribute('aria-atomic', 'true');
                    announcement.className = 'sr-only';
                    announcement.textContent = `Gallery modal opened. Image ${currentImageIndex + 1} of ${galleryImages.length}. Use arrow keys to navigate or escape to close.`;
                    document.body.appendChild(announcement);
                    setTimeout(() => document.body.removeChild(announcement), 1000);
                };

                function closeModal() {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.style.overflow = '';

                    // Restore focus to the trigger element
                    if (modalTrigger) {
                        modalTrigger.focus();
                        modalTrigger = null;
                    }
                }

                function showImage() {
                    if (galleryImages[currentImageIndex]) {
                        modalImage.src = galleryImages[currentImageIndex].url;
                        modalImage.alt = galleryImages[currentImageIndex].alt;
                        currentImageSpan.textContent = currentImageIndex + 1;
                        totalImagesSpan.textContent = galleryImages.length;
                    }
                }

                function nextImage() {
                    currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
                    showImage();

                    // Announce change to screen readers
                    const announcement = document.createElement('div');
                    announcement.setAttribute('aria-live', 'polite');
                    announcement.className = 'sr-only';
                    announcement.textContent = `Image ${currentImageIndex + 1} of ${galleryImages.length}`;
                    document.body.appendChild(announcement);
                    setTimeout(() => document.body.removeChild(announcement), 1000);
                }

                function prevImage() {
                    currentImageIndex = currentImageIndex === 0 ? galleryImages.length - 1 : currentImageIndex - 1;
                    showImage();

                    // Announce change to screen readers
                    const announcement = document.createElement('div');
                    announcement.setAttribute('aria-live', 'polite');
                    announcement.className = 'sr-only';
                    announcement.textContent = `Image ${currentImageIndex + 1} of ${galleryImages.length}`;
                    document.body.appendChild(announcement);
                    setTimeout(() => document.body.removeChild(announcement), 1000);
                }

                // Event listeners
                closeBtn.addEventListener('click', closeModal);
                nextBtn.addEventListener('click', nextImage);
                prevBtn.addEventListener('click', prevImage);

                // Close modal when clicking outside the image
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeModal();
                    }
                });

                // Keyboard navigation
                document.addEventListener('keydown', function(e) {
                    if (!modal.classList.contains('hidden')) {
                        switch(e.key) {
                            case 'Escape':
                                e.preventDefault();
                                closeModal();
                                break;
                            case 'ArrowLeft':
                                e.preventDefault();
                                prevImage();
                                break;
                            case 'ArrowRight':
                                e.preventDefault();
                                nextImage();
                                break;
                            case 'Home':
                                e.preventDefault();
                                currentImageIndex = 0;
                                showImage();
                                break;
                            case 'End':
                                e.preventDefault();
                                currentImageIndex = galleryImages.length - 1;
                                showImage();
                                break;
                        }
                    }
                });

                // Focus trapping in modal
                const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';

                modal.addEventListener('keydown', function(e) {
                    if (e.key === 'Tab') {
                        const focusableContent = modal.querySelectorAll(focusableElements);
                        const firstFocusableElement = focusableContent[0];
                        const lastFocusableElement = focusableContent[focusableContent.length - 1];

                        if (e.shiftKey) {
                            if (document.activeElement === firstFocusableElement) {
                                lastFocusableElement.focus();
                                e.preventDefault();
                            }
                        } else {
                            if (document.activeElement === lastFocusableElement) {
                                firstFocusableElement.focus();
                                e.preventDefault();
                            }
                        }
                    }
                });
            })();
            </script>

            <style>
           #<?php echo esc_attr($section_id); ?> .sr-only {
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
    </div>
</section>
